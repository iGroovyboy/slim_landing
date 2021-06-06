<?php


namespace App\Controllers\Api;


use App\DataTypes\Transformer;
use App\Models\Node;
use App\Services\Config;
use App\Services\Image;
use App\Services\Log;
use App\Services\Storage;
use App\Services\Str;
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
     * @return bool
     * @throws \Exception
     * @uses  \Slim\Psr7\UploadedFile $uploadedFile
     */
    public function post()
    {
        $parent = $this->body['parent'] ?: null;
        unset($this->body['parent']);

        if (count($this->body) === 1) {
            return Node::set($this->key, reset($this->body), $parent);
        }

        if ($uploadedFiles = $this->uploadedFiles['files']) {
            $subFolder = date('Y') . DS . date('m');
            $directory = Config::getPath('app/paths/uploads', $subFolder);

            $files = $this->uploadFiles($uploadedFiles, $directory);
        }

        return Node::set(
            $this->key,
            Transformer::run($this->body['datatype'], $this->body, $files),
            $parent
        );
    }


    /**
     * @param $uploadedFiles
     * @param string $directory
     * @param array $files
     *
     * @return array
     * @throws \Exception
     */
    protected function uploadFiles($uploadedFiles, string $directory): array
    {
        $files = [];

        // TODO: check if filetype is allowed
        foreach ($uploadedFiles as $i => $uploadedFile) {
            /** @var \Slim\Psr7\UploadedFile $uploadedFile */
            if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                $fileinfo = Storage::moveUploadedFile($directory, $uploadedFile);

                $id = $uploadedFile->getClientFilename();

                $files[$id]['full'] = $fileinfo->getPathname();

                if ( ! empty($this->body['thumbnailSize']) && Str::str_starts_with($uploadedFile->getClientMediaType(), 'image')) {
                    $files[$id]['thumb'] = Image::makeThumb($fileinfo, $this->body['thumbnailSize']);
                }
            }
        }

        Log::info('Uploaded files: ' . count($files) . '. Files: ' . join(', ', $files));

        return $files;
}
}
