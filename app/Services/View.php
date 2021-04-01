<?php


namespace App\Services;


use App\Services\TemplatesCache\TemplateCacheItem;

class View
{
    public const PHP_TEMPLATE = 'php';
    public const TWIG_TEMPLATE = 'twig';

    private $theme;

    /**
     * @return mixed|string
     */
    public function getTheme(): string
    {
        return $this->theme;
    }

    /**
     * @return string
     */
    public function getThemePath(): string
    {
        return $this->theme_path;
    }
    private $theme_path;

    public function __construct(Config $config, HtmlCache $htmlCache)
    {
        $this->config    = $config;
        $this->htmlCache = $htmlCache;

        $this->theme      = Config::get('app/theme') ?: 'default';
        $this->theme_path = Config::getPath('app/paths/themes') . $this->theme . DS;

        $this->htmlCache->setCacheFallbackDir(Config::getPath('app/paths/cache') . 'default' . DS);
        $this->htmlCache->setCacheDir(Config::getPath('app/paths/cache') . $this->theme . DS);
        $this->htmlCache->setCacheExtension(Config::get('cache/html_extension'));
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
            && ! in_array($filename, Config::get('cache/exclude'))
            && $this->htmlCache->has($filename)
            && ! empty($cachedHtml = $this->htmlCache->get($filename))
        ) {
            Install::createAssetsSymlink(); // TODO move this to theme.install module

            return $cachedHtml;
        }

        //$cachedHtml = $this->htmlCache->get($filename);

        $ext = self::getEngineFromFileExtension($this->theme_path . $filename);

        if (Config::get('app/theme_dev')) {
            Install::createAssetsSymlink($this);
        }

        if ( ! file_exists("$this->theme_path$filename.$ext")) {
            $this->theme_path = Config::getPath('app/paths/themes') . 'default' . DS;
        }

        if ('twig' === $ext) {
            $html = self::renderTWIG($this->theme_path, $filename, $ext, $vars);
        } else {
            $html = self::renderPHP("$this->theme_path$filename.$ext", $vars);
        }

        if (Config::get('cache/enabled') && ! in_array($filename, Config::get('cache/exclude'))) {
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

        $loader = new \Twig\Loader\FilesystemLoader($themePath);
        $loader->addPath(Config::getPath('app/paths/themes', 'default'), 'default_theme');

        $twig = new \Twig\Environment(
            $loader,
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

    public function themeActivate(string $theme)
    {
        //
        // check all dirs +default exist

        Install::createAssetsSymlink($this);
    }

    public static function themeDeactivate(string $theme = null)
    {
    }

}
