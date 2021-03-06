<?php


namespace App\Controllers\Api;


use App\DataTypes\Transformer;
use App\Models\Node;
use App\Models\Option;
use App\Services\Config\Config;
use App\Services\Image;
use App\Services\Log;
use App\Services\Storage;
use App\Services\Str;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use xobotyi\MimeType;

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
        return Node::of('')->get($this->key) ?: '';
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
            return Node::of($parent)->set($this->key, reset($this->body));
        }

        if ($uploadedFiles = $this->uploadedFiles['files']) {
            $subFolder = date('Y') . DS . date('m');
            $urlWithoutFilename = Config::get('app/paths/storage') . DS . $subFolder;

            $files = $this->uploadFiles($uploadedFiles, $urlWithoutFilename);
        }

        $body = $this->body['json'];

        if ($files) {
            // TODO: delete old files ??
            $body = Transformer::replaceFilenamesWithUploadPaths($this->body['datatype'], $this->body, $files);
        }

        return Node::of($parent)->set(
            $this->key,
            $body
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
        $allowedFileExtensions = Storage::getExtensionsMimes(Option::get('allowedFileExtensions'));

        foreach ($uploadedFiles as $i => $uploadedFile) {
            $isUploadWithoutErrors = $uploadedFile->getError() === UPLOAD_ERR_OK;
            $isAllowedUpload       = in_array($uploadedFile->getClientMediaType(), $allowedFileExtensions);

            /** @var \Slim\Psr7\UploadedFile $uploadedFile */
            if ($isUploadWithoutErrors && $isAllowedUpload) {
                $fullpath = Config::getPath('app/paths/public', $directory);
                $fileinfo = Storage::moveUploadedFile($fullpath, $uploadedFile);

                $id = $uploadedFile->getClientFilename();

                $files[$id]['full'] = str_replace('\\', '/', DS . $directory . DS . $id);

                if ( ! empty($this->body['thumbnailSize']) && Str::str_starts_with($uploadedFile->getClientMediaType(), 'image')) {
                    $thumbnailFile =  Image::makeThumb($fileinfo, $this->body['thumbnailSize']);
                    $files[$id]['thumb'] = str_replace('\\', '/', DS . $directory . DS . $thumbnailFile);
                }
            }
        }

        Log::info('Uploaded files: ' . count($files) . '. Files: ' . join(', ', $files));

        return $files;
    }
}
