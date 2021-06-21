<?php


namespace App\Services;


use Psr\Http\Message\UploadedFileInterface;
use SplFileInfo;
use xobotyi\MimeType;

class Storage
{
    const IMAGE_MIME = [
        'image/jpeg',
        'image/pjpeg',
        'image/svg+xml',
        'image/gif',
        'image/webp',
        'image/png'
    ];
    /**
     * @param string $directory
     * @param UploadedFileInterface $uploadedFile
     * @param bool $randomName
     *
     * @uses \Slim\Psr7\UploadedFile $uploadedFile
     *
     * @return SplFileInfo
     * @throws \Exception
     */
    public static function moveUploadedFile(string $directory, UploadedFileInterface $uploadedFile, $randomName = false): SplFileInfo
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);

        $filename = $uploadedFile->getClientFilename();
        if ($randomName) {
            // see http://php.net/manual/en/function.random-bytes.php
            $basename = bin2hex(random_bytes(8));
            $filename = sprintf('%s.%0.8s', $basename, $extension);
        }

        if (!file_exists($directory)) {
            mkdir($directory, 0777,true);
        }

        $targetPath = $directory . DIRECTORY_SEPARATOR . $filename;

        $uploadedFile->moveTo($targetPath);

        return new SplFileInfo($targetPath);
    }

    public static function getExtensionsMimes($allowedFileExtensions)
    {
        if ( ! is_array($allowedFileExtensions)) {
            return MimeType::getExtensionMimes($allowedFileExtensions);
        }

        $mimes = [];
        foreach ($allowedFileExtensions as $ext) {
            $mimes = array_merge($mimes, array_values(MimeType::getExtensionMimes($ext)));
        }
        sort($mimes);

        return $mimes;
    }
}
