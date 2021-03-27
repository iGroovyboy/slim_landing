<?php


namespace App\Services;


class ProjectTwigExtension extends \Twig\Extension\AbstractExtension implements \Twig\Extension\GlobalsInterface
{

    public function getGlobals(): array
    {
        return [
            'assets' => '/themes/' . Config::get('app/theme') . '/' . Config::$assetsDir,
        ];
    }

    public function getFunctions()
    {
        return [
            new \Twig\TwigFunction(
                'route', function ($arg)     {
                global $app;

                $url = '';
                try {
                    $url = $app->getRouteCollector()->getRouteParser()->urlFor($arg);
                } catch (\Exception $e) {
                    // TODO log
                }

                return $url;
            }
            ),
        ];
    }

}
