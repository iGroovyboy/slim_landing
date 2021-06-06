<?php


namespace App\Services;


use Intervention\Image\ImageManager;

class Image
{

    /**
     * @param \SplFileInfo $fileinfo
     * @param array $files
     * @param string|null $id
     */
    public static function makeThumb(\SplFileInfo $fileinfo, string $size)
    {
        $path         = $fileinfo->getPath();
        $basename     = $fileinfo->getBasename('.' . $fileinfo->getExtension());
        $img_filename = $path . DS . 'thumb_' . $basename;

        $dimensions = array_slice(explode('x', $size), 0, 2);
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

            return $img_filename . $thumbExt;
        } catch (\Intervention\Image\Exception\MissingDependencyException $e) {
            Log::error('Thumbnail creation error: ' . $e->getMessage());
        }

        return $fileinfo->getPathname();
    }
}
