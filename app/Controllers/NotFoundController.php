<?php


namespace App\Controllers;


use App\Services\Log;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class NotFoundController extends BaseController
{
    public function default(): string
    {
        Log::info('404: ' . $_SERVER["REQUEST_URI"]);

        return $this->view->render('404', ['uri' => $_SERVER["REQUEST_URI"]]);
    }

    public function api(ServerRequestInterface $request, ResponseInterface $response, array $args): string
    {
        $slug = $this->args['slug'] ?? $_SERVER["REQUEST_URI"];

        $this->response->getBody()->write(json_encode(['404'])); // TODO rest/api/json response class with status etc

        return $this->response->withHeader('Content-Type', 'application/json');
    }
}
