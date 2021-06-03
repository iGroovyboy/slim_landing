<?php


namespace App\Controllers\Api;


use App\DataTypes\Transformer;
use App\Models\Node;
use App\Services\Config;
use App\Services\Log;
use App\Services\Storage;
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

    /**
     * @uses  \Slim\Psr7\UploadedFile $uploadedFile
     * @return bool
     * @throws \Exception
     */
    public function post()
    {
        $parent = $this->body['parent'] ?: null;
        unset($this->body['parent']);

        $files = [];
        $directory = Config::getPath('app/paths/uploads','ttt');

        if ($uploadedFiles = $this->uploadedFiles['files']) {
            foreach ($uploadedFiles as $i => $uploadedFile) {
                if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                    $files[$i] = Storage::moveUploadedFile($directory, $uploadedFile);
                }
            }

            Log::info('Uploaded files: ' . count($files) . '. Files: ' . join(', ', $files));
        }

//        $formatSubpath = DS . 'assets' . DS . 'blockeditors' . DS;
//        $formatPaths = [
//            Config::getPath('app/paths/themes', 'default' . $formatSubpath ),
//            Config::getPath('app/paths/themes', Config::get('app/theme') . $formatSubpath )
//        ];

        $d = json_decode($this->body['data'], true);

        $body = Transformer::run($this->body['datatype'], $this->body, $files);

        if (count($this->body) === 1) {
            return Node::set($this->key, reset($this->body), $parent);
        }

        return Node::set($this->key, serialize($this->body), $parent);
    }
}
