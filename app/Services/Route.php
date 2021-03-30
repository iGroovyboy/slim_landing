<?php


namespace App\Services;


use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;

class Route
{
    protected $routeName;

    public function __construct($routeName)
    {
        $this->routeName = $routeName;
    }

    public static function getUrl($routeName)
    {
        global $app;

        $url = '';
        try {
            $url = $app->getRouteCollector()->getRouteParser()->urlFor($routeName);
        } catch (\Exception $e) {
            Log::error("Tried to use named route '$routeName' which doesn't exist"); // TODO add trace caller func
        }

        return $url;
    }

    public static function redirectTo($location, ResponseInterface $response = null)
    {
        $response = $response ?: new Response();

        return $response->withHeader('Location', $location)->withStatus(302);
    }

    public static function redirectToRoute($routeName, ResponseInterface $response = null)
    {
        $response = $response ?: new Response();

        return $response->withHeader('Location', self::getUrl($routeName))->withStatus(302);
    }

    public function redirect(ResponseInterface $response = null)
    {
        $response = $response ?: new Response();

//        ->createResponse(301)
//                ->withHeader('Location', (string) $uri->withPath(''))


        return $response->withHeader('Location', self::getUrl($this->routeName))->withStatus(302);
    }

    public static function useNamed($routeName)
    {
        return new self($routeName);
    }
}
