<?php


namespace App\Models;


use Psr\Http\Message\UploadedFileInterface;

class Upload extends Model
{
    public const TABLE_NAME = 'uploads';

    public function create(array $files)
    {
        foreach ($files as $uploadedFile) { /** @var \Slim\Psr7\UploadedFile $uploadedFile */
            if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                $filename = $this->moveUploadedFile($directory, $uploadedFile);
                // LOG 'Uploaded: ' . $filename . '<br/>'
                // save to db
                // return
            }
        }

    }

    private function moveUploadedFile(string $directory, UploadedFileInterface $uploadedFile)
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);

        // see http://php.net/manual/en/function.random-bytes.php
        $basename = bin2hex(random_bytes(8));
        $filename = sprintf('%s.%0.8s', $basename, $extension);

        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }

    public function delete(array $keys)
    {

    }

}
