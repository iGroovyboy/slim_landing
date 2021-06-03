<?php


namespace App\Services;


use Psr\Http\Message\UploadedFileInterface;

class Storage
{
    /**
     * @param string $directory
     * @param UploadedFileInterface $uploadedFile
     *
     * @uses \Slim\Psr7\UploadedFile $uploadedFile
     *
     * @return string
     * @throws \Exception
     */
    public static function moveUploadedFile(string $directory, UploadedFileInterface $uploadedFile)
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);

        // see http://php.net/manual/en/function.random-bytes.php
        $basename = bin2hex(random_bytes(8));
        $filename = sprintf('%s.%0.8s', $basename, $extension);

        if (!file_exists($directory)) {
            mkdir($directory);
        }

        $targetPath = $directory . DIRECTORY_SEPARATOR . $filename;

        $uploadedFile->moveTo($targetPath);

        return $targetPath;
    }
}
