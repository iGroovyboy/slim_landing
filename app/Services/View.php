<?php


namespace App\Services;


use function Composer\Autoload\includeFile;
use function PHPUnit\Framework\fileExists;

class View
{
    public const PHP_TEMPLATE = 'php';
    public const TWIG_TEMPLATE = 'twig';

    public function __construct(Config $config)
    {
        $this->config = $config;
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
        if (Config::get('cache/enabled') && Cache::has($filename)) {
            return Cache::get($filename);
        }

        $themesDir = ROOT_DIR . DIRECTORY_SEPARATOR . Config::get('app/paths/themes') . DIRECTORY_SEPARATOR;
        $theme     = Config::get('app/theme') ?: 'default';
        $themePath = $themesDir . $theme . DIRECTORY_SEPARATOR;
        $ext       = self::getEngineFromFileExtension($themePath . $filename);

        if ( ! file_exists($themePath)) {
            $themePath = $themesDir . 'default' . DIRECTORY_SEPARATOR;
        }

        if ('twig' === $ext) {
            $html = self::renderTWIG($themePath, $filename, $ext, $vars);
        } else {
            $html = self::renderPHP("$themePath$filename.$ext", $vars);
        }

        if (Config::get('cache/enabled')) {
            Cache::set($filename, $html);
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

        $xx = ob_get_clean();

        return $xx;
    }


    protected function buildUsing($renderer)
    {
    }


}
