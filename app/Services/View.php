<?php


namespace App\Services;


class View
{

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public static function render($template, array $vars = []): string
    {
//        echo $config::APP_DIR;
//        $x = $this->get('config');

//        global $app;
//        $config = $app->getContainer()->get('config');
//
//        echo $config::APP_DIR;


        // TODO get all folders from config
        $loader = new \Twig\Loader\FilesystemLoader(THEMES_DIR . '/default/');
        $twig = new \Twig\Environment($loader, [
            'cache' => ROOT_DIR . '/public/cache',
        ]);

        return $twig->render($template, $vars);
    }

}