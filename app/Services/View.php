<?php


namespace App\Services;


use App\Services\TemplatesCache\TemplateCacheItem;

class View
{
    public const PHP_TEMPLATE = 'php';
    public const TWIG_TEMPLATE = 'twig';

    public function __construct(Config $config, HtmlCache $htmlCache)
    {
        $this->config    = $config;
        $this->htmlCache = $htmlCache;
    }

    private static function getEngineFromFileExtension(string $filename)
    {
        if (file_exists("$filename.php")) {
            return self::PHP_TEMPLATE;
        }

        return self::TWIG_TEMPLATE;
    }

    // TODO: add caching using ob_clean..
    public function render(string $filename, array $vars = [])
    {
        if (
            Config::get('cache/enabled')
            && $this->htmlCache->has($filename)
            && ! empty($cachedHtml = $this->htmlCache->get($filename))
        ) {
            return $cachedHtml;
        }

        $themesDir = Config::getPath('app/paths/themes');
        $theme     = Config::get('app/theme') ?: 'default';
        $themePath = $themesDir . $theme . DS;
        $ext       = self::getEngineFromFileExtension($themePath . $filename);

        if ( ! file_exists($themePath)) {
            $themePath = $themesDir . 'default' . DS;
        }

        if ('twig' === $ext) {
            $html = self::renderTWIG($themePath, $filename, $ext, $vars);
        } else {
            $html = self::renderPHP("$themePath$filename.$ext", $vars);
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

}
