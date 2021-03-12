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

        $ext = self::getEngineFromFileExtension($filename);

        $theme     = Config::get('app/theme') ?: 'default';
        $themePath = $themesDir . $theme . DIRECTORY_SEPARATOR;
        if ( ! file_exists($themePath)) {
            $themePath = $themesDir . 'default' . DIRECTORY_SEPARATOR;
        }

        if ('twig' === $ext) {
            $twigOptions = [];
            if (Config::get('cache/twig/caching_enabled')) {
                $twigOptions['cache'] = ROOT_DIR . '/cache';
            }

            $twig = new \Twig\Environment(
                new \Twig\Loader\FilesystemLoader($themePath),
                $twigOptions
            );

            $html = $twig->render("$filename.$ext", $vars);
        } else {
            $html = include("$themePath$filename.$ext");
        }

        if (Config::get('cache/enabled')) {
            Cache::set($filename, $html);
        }

        return $html;
    }


    protected function buildUsing($renderer)
    {
    }


}
