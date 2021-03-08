<?php


namespace App\Services;


class View
{

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function render($template, $vars): string
    {
//        echo $config::APP_DIR;
//        $x = $this->get('config');

//        global $app;
//        $config = $app->getContainer()->get('config');
//
//        echo $config::APP_DIR;



        $loader = new \Twig\Loader\FilesystemLoader(APP_DIR . '/Themes/default/');
        $twig = new \Twig\Environment($loader, [
            'cache' => PUB_DIR . '/cache',
        ]);

        return $twig->render($template, $vars);
    }

}