<?php


namespace App\Controllers\Api;


use App\DataTypes\Transformer;
use App\Models\Node;
use App\Services\Config;
use App\Services\Log;
use App\Services\Storage;
use App\Services\Str;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;
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

        $files = [];
        $subFolder = date('Y') . DS . date('m');
        $directory = Config::getPath('app/paths/uploads', $subFolder);

        // TODO: check if filetype is allowed
        // upload files
        if ($uploadedFiles = $this->uploadedFiles['files']) {
            foreach ($uploadedFiles as $i => $uploadedFile) {
                /** @var \Slim\Psr7\UploadedFile $uploadedFile */
                if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                    $fileinfo  = Storage::moveUploadedFile($directory, $uploadedFile);

                    $id = $uploadedFile->getClientFilename();

                    $files[$id]['full'] = $fileinfo->getPathname();

                    if (!empty($this->body['thumbnailSize']) && Str::str_starts_with($uploadedFile->getClientMediaType(), 'image')) {
                        $path     = $fileinfo->getPath();
                        $basename = $fileinfo->getBasename('.' . $fileinfo->getExtension());
                        $img_filename = $path . DS . 'thumb_' . $basename;

                        $dimensions = array_slice(explode('x', $this->body['thumbnailSize']), 0, 2);
                        array_walk(
                            $dimensions,
                            function (&$size) {
                                $size = (int)$size;
                            }
                        );

                        try {
                            $img_manager = new ImageManager();
                            $thumbExt    = 'jpg';
                            $thumb       = $img_manager->make($fileinfo->getPathname())
                                                       ->fit(300, 300)
                                                       ->save($img_filename, 60, $thumbExt);
                            $files[$id]['thumb'] = $img_filename . $thumbExt;

                        } catch (\Intervention\Image\Exception\MissingDependencyException $e) {
                            Log::error('Thumbnail creation error: ' . $e->getMessage());
                        }
                    }
                }
            }

            Log::info('Uploaded files: ' . count($files) . '. Files: ' . join(', ', $files));
        }

        $body = Transformer::run($this->body['datatype'], $this->body, $files);

        if (count($this->body) === 1) {
            return Node::set($this->key, reset($this->body), $parent);
        }

        return Node::set($this->key, serialize($this->body), $parent);
    }
}
