<?php


namespace App\Controllers\Api;


use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class DbController
{
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function checkConnection(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $this->request  = $request;
        $this->response = $response;
        $this->args     = $args;

        $ss = $this->request->getParsedBody();
        $headers = $request->getHeaders();

        $paramsX = (array)$request->getParsedBody();

        $json = $this->request->getBody();
        $data = json_decode($json, true); // parse the JSON into an assoc. array


        $this->response->getBody()->write(json_encode([345, '444']));

        return $response->withHeader('Content-Type', 'application/json');
    }
}
