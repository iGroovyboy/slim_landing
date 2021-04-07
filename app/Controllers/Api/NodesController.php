<?php


namespace App\Controllers\Api;


use App\Models\Node;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class NodesController
{
    protected string $key;
    protected $body;
    protected ?string $value;

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $this->request  = $request;
        $this->response = $response;

        $this->key  = $args['key'];
        $this->body = $request->getParsedBody();

        $method     = strtolower($request->getMethod());
        $this->body = $this->$method();

        $this->response->getBody()->write(json_encode(['data' => $this->body]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    protected function get()
    {
        return Node::get($this->key) ?: '';
    }

    protected function put()
    {
        $parent = $this->body['parent'] ?: null;
        unset($this->body['parent']);

        if (count($this->body) === 1) {
            return Node::set($this->key, reset($this->body), $parent);
        }

        return Node::set($this->key, serialize($this->body), $parent);
    }
}
