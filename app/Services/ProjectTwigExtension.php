<?php


namespace App\Services;


use DebugBar\StandardDebugBar;

class ProjectTwigExtension extends \Twig\Extension\AbstractExtension implements \Twig\Extension\GlobalsInterface
{

    public function getGlobals(): array
    {
        $debugbar = new StandardDebugBar();
        $debugbarRenderer = $debugbar->getJavascriptRenderer();

        return [
            'assets' => '/themes/' . Config::get('app/theme') . '/' . Config::$assetsDir,
            'theme' => Config::get('app/theme'),

            'debug_head' => $debugbarRenderer->renderHead(),
            'debug_js' => $debugbarRenderer->render()


        ];
    }

    public function getFunctions()
    {
        return [
            new \Twig\TwigFunction(
                'route', function ($arg) {
                global $app;


                $url = '';
                try {
                    $url = $app->getRouteCollector()->getRouteParser()->urlFor($arg);
                } catch (\RuntimeException $e) {
                    $templateFile = 'undefined';

                    // get faulty template
                    foreach (debug_backtrace() as $trace) {
                        if (isset($trace['object'])
                            && (strpos($trace['class'], 'TwigTemplate') !== false)
                            && 'Twig_Template' !== get_class($trace['object'])
                        ) {
                            $templateFile = $trace['object']->getTemplateName();
                            break;
                        }
                    }

                    Log::theme("Twig template '$templateFile' contains non-existent route named '$arg'. Request uri: {$_SERVER['REQUEST_URI']}");
                }

                return $url;
            }
            ),
        ];
    }

}
