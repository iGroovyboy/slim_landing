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
                    Log::warning("Twig template tried to reference non-existent route '$arg'. Request uri: {$_SERVER['REQUEST_URI']}");
                }

                return $url;
            }
            ),
        ];
    }

}
