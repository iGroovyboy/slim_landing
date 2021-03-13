<?php


namespace App\Services\TemplatesCache;


use App\Services\Config;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;

class TemplatesCache implements CacheItemPoolInterface
{


    public function getItem($key)
    {
        // TODO: Implement getItem() method.
    }

    public function getItems($keys = array())
    {
        // TODO: Implement getItems() method.
    }

    public function hasItem($key)
    {
        $path = Config::getPath('app/paths/cache', $key . '.html' );
        return file_exists($path);
    }

    public function clear()
    {
        // TODO: Implement clear() method.
    }

    public function deleteItem($key)
    {
        // TODO: Implement deleteItem() method.
    }

    public function deleteItems($keys)
    {
        // TODO: Implement deleteItems() method.
    }

    public function save(CacheItemInterface $item)
    {

        // TODO: Implement save() method.
    }

    public function saveDeferred(CacheItemInterface $item)
    {
        // TODO: Implement saveDeferred() method.
    }

    public function commit()
    {
        // TODO: Implement commit() method.
    }
}
