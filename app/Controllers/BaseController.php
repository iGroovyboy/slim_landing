<?php

namespace App\Controllers;

use App\Services\View;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class BaseController
{
    protected $container;

    protected $request;
    protected $response;
    protected $args;

    protected $body;

    protected $view;


    public function __construct(ContainerInterface $container, View $view)
    {
        $this->container = $container;
        $this->view = $view;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $this->request  = $request;
        $this->response = $response;
        $this->args     = $args;

        $this->body = $this->default();

        $this->response->getBody()->write($this->body);

        return $response;
    }

    public function default()
    {
        return '';
    }

}