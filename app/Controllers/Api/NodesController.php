<?php


namespace App\Controllers\Api;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class NodesController
{
    protected string $key;
    protected ?string $value;

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $this->request  = $request;
        $this->response = $response;

        $this->key      = $args['key'];
        $this->body     = $request->getParsedBody();

        $method         = strtolower($request->getMethod());
        $this->body     = $this->$method();

        $this->response->getBody()->write(json_encode(['data' => $this->body]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    protected function get()
    {


        return 'get val';
    }

    protected function put()
    {


        return 'set val';
    }
}