<?php


namespace App\Controllers;


use App\Services\Auth;
use App\Services\Log;
use App\Services\Route;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AuthController extends BaseController
{
    public function default()
    {
        return $this->view->render('login');
    }

    public function login(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $post = $request->getParsedBody();
        if (isset($post->parsedBody['email']) && isset($post->parsedBody["pass"])) {
            Auth::attempt($post->parsedBody['email'], $post->parsedBody["pass"]);
            if(Auth::isLogged()){
                // redirect to admin OR to previous
            }
        }

        $response->getBody()->write($this->view->render('login'));

        return $response;
    }

    public function logout(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        Auth::logout();
        Log::info('User logged out');

//        $uri = $request->getUri();
//        return $response->withHeader('Location', (string) $uri->withPath(Route::getUrl('login')))
//                 ->withStatus(302);

//        return $this->response->createResponse(301)
//                ->withHeader('Location', (string) $uri->withPath(''));

        return Route::useNamed('login')->redirect();
    }

}
