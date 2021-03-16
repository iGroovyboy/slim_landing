<?php


namespace App\Services;


use App\Services\TemplatesCache\TemplateCacheItem;

class View
{
    public const PHP_TEMPLATE = 'php';
    public const TWIG_TEMPLATE = 'twig';

    private $theme;
    private $theme_path;

    public function __construct(Config $config, HtmlCache $htmlCache)
    {
        $this->config    = $config;
        $this->htmlCache = $htmlCache;

        $this->theme      = Config::get('app/theme') ?: 'default';
        $this->theme_path = Config::getPath('app/paths/themes') . $this->theme . DS;

        $this->htmlCache->setCacheDir(Config::getPath('app/paths/cache') . $this->theme . DS);
        $this->htmlCache->setCacheExtension(Config::get('cache/html_extension'));
    }

    /**
     * @param string $themePath
     * @param string $theme
     */
    protected function createAssetsLink(): void
    {
        $originalDir    = $this->theme_path . Config::$assetsDir . DS;
        $publicThemeDir = Config::getPath('app/paths/public')
                          . Config::get('app/paths/themes')
                          . DS . $this->theme;
        $link           = $publicThemeDir . DS . Config::$assetsDir . DS;

        if (is_link($link)) {
            return;
        }

        $mkdir = mkdir($publicThemeDir, 0664); //0775?
        if ( ! $mkdir) {
            if ( ! file_exists($mkdir) || ! is_dir($mkdir)) {
                // TODO log?
            }
        }

        $symlink = symlink($originalDir, $link);

        if ( ! $symlink) {
            // TODO log?
        }
    }

    private static function getEngineFromFileExtension(string $filename)
    {
        if (file_exists("$filename.php")) {
            return self::PHP_TEMPLATE;
        }

        return self::TWIG_TEMPLATE;
    }

    public function render(string $filename, array $vars = [])
    {
        if (
            Config::get('cache/enabled')
            && $this->htmlCache->has($filename)
            && ! empty($cachedHtml = $this->htmlCache->get($filename))
        ) {
            $this->createAssetsLink();

            return $cachedHtml;
        }

        $cachedHtml = $this->htmlCache->get($filename);

        $ext = self::getEngineFromFileExtension($this->theme_path . $filename);

        if (Config::get('app/theme_dev')) {
            $this->createAssetsLink();
        }

        if ( ! file_exists($this->theme_path)) {
            $this->theme_path = Config::getPath('app/paths/themes') . 'default' . DS;
        }

        if ('twig' === $ext) {
            $html = self::renderTWIG($this->theme_path, $filename, $ext, $vars);
        } else {
            $html = self::renderPHP("$this->theme_path$filename.$ext", $vars);
        }

        if (Config::get('cache/enabled')) {
            $this->htmlCache->set($filename, $html, Config::get('cache/expiresAfter'));
        }

        return $html;
    }

    /**
     * @param string $themePath
     * @param string $filename
     * @param string $ext
     * @param array $vars
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    protected static function renderTWIG(string $themePath, string $filename, string $ext, array $vars): string
    {
        $twigOptions = [];
        if (Config::get('cache/twig/caching_enabled')) {
            $twigOptions['cache'] = ROOT_DIR . '/cache';
        }

        $twig = new \Twig\Environment(
            new \Twig\Loader\FilesystemLoader($themePath),
            $twigOptions
        );

        $twig->addExtension(new ProjectTwigExtension());

        $html = $twig->render("$filename.$ext", $vars);

        return $html;
    }

    protected static function renderPHP($file, $vars = []): string
    {
        $level = ob_get_level();

        ob_start();

        try {
            extract($vars);
            include($file);
        } catch (\Exception $e) {
            // used to handle exceptions properly when using output buffering
            // for rendering a view which may or may not be using output buffering
            while (ob_get_level() > $level) {
                ob_end_clean();
            }

            throw $e;
        }

        return ob_get_clean();
    }

    public static function themeActivate(string $theme)
    {
    }

    public static function themeDeactivate(string $theme = null)
    {
    }

}
