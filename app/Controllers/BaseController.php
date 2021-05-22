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
        $this->view      = $view;
    }

    /**
     * @param ServerRequestInterface $request PSR-7 request
     * @param ResponseInterface $response PSR-7 response
     * @param array $args The route's placeholder arguments
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, ?array $args): ResponseInterface
    {
        $this->request  = $request;
        $this->response = $response;
        $this->args     = $args;

        $alsoArgs =  $this->request->getAttributes()['__route__']->getArguments();

        $this->body = $this->default();

        $this->response->getBody()->write($this->body);

        return $response;
    }

    public function default()
    {
        return '';
    }

}
