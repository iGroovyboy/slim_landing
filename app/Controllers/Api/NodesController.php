<?php


namespace App\Controllers\Api;


use App\DataTypes\Transformer;
use App\Models\Node;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class NodesController
{
    private ServerRequestInterface $request;

    private ResponseInterface $response;

    protected string $key;

    protected $body;

    protected ?string $value;

    protected ?array $uploadedFiles;

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $this->request  = $request;
        $this->response = $response;

        $this->key  = $args['key'];
        $this->body = $request->getParsedBody();
        $this->uploadedFiles = $request->getUploadedFiles();

        $method     = strtolower($request->getMethod());
        $this->body = $this->$method();

        $this->response->getBody()->write(json_encode(['data' => $this->body]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function get()
    {
        return Node::get($this->key) ?: '';
    }

    public function post()
    {
        $parent = $this->body['parent'] ?: null;
        unset($this->body['parent']);

        if ($this->uploadedFiles){

            foreach ($this->uploadedFiles['files'] as $uploadedFile) { /** @var \Slim\Psr7\UploadedFile $uploadedFile */
                if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                    $filename = moveUploadedFile($directory, $uploadedFile);
                    $response->getBody()->write('Uploaded: ' . $filename . '<br/>');
                }
            }
        }

        $files = [];

//        $transformer->data    = $this->body;
//        $transformer->type    = $this->body['datatype'];
//        $transformer->uploads = $files ?: [];
//        $transformer->encode();

        if (count($this->body) === 1) {
            return Node::set($this->key, reset($this->body), $parent);
        }

        return Node::set($this->key, serialize($this->body), $parent);
    }
}
