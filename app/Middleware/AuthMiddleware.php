<?php

namespace App\Middleware;

use App\Services\Auth;
use App\Services\Route;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class AuthMiddleware
{
    /**
     * @param Request $request PSR-7 request
     * @param RequestHandler $handler PSR-15 request handler
     *
     * @return Response
     */
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        if (Auth::isLogged()) {
            $response        = $handler->handle($request);
            $content = (string)$response->getBody();

            $response = new Response();
            $response->getBody()->write($content);

            return $response;
        }

        return Route::redirectToRoute('login');
    }
}

