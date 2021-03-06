<?php


namespace App\DataTypes;

/*
 * Transforms data to be saved to db in specified format
 */

use App\Services\Log;

class Transformer
{
    private $format;

    public function __construct($format)
    {
        $this->format = $format;
    }

    /**
     * @param string $type
     * @param null|array $body
     * @param array $files
     *
     * @return string
     */
    public static function replaceFilenamesWithUploadPaths(string $type, array $body, ?array $files): string
    {
        if (empty($files)) {
            return $body['json'];
        }

        $x = [];
        $data = json_decode($body['json'], true);
        foreach ($data as $i => $element) {
            $filename = $element['src'];

            $data[$i]['src'] = $files[$filename]['full'] ?: $data[$i]['src'];
            $x[] = $files[$filename]['full'] ?: $data[$i]['src'];
        }

        return json_encode($data);
    }

    /**
     * @param array|string $path
     * @param string $filename
     *
     * @return static
     */
    public static function setFormatFromFile($paths, $filename = 'format.json')
    {
        $paths = is_array($paths) ? $paths : [$paths];
        $format = [];

        foreach ($paths as $path) {
            $data = file_get_contents($path . DS . $filename);
            if ($data !== false) {
                $format = array_merge($format, json_decode($data, true));
            }
        }

        return new static( $format );
    }

    protected function map()
    {

    }
}
