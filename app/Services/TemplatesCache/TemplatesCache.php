<?php


namespace App\Services\TemplatesCache;


use App\Services\Config;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

class TemplatesCache implements CacheItemPoolInterface
{
    protected $cache_dir = '';

    public function __construct()
    {
        $this->cache_dir = Config::getPath('app/paths/cache');
    }

    public function getItem($key)
    {
        $cacheItem = new TemplateCacheItem($key);

        $path = $this->cache_dir . $key;
        $cacheItem->set(file_get_contents($path) ?: '');

        return $cacheItem;
    }

    public function getItems($keys = array())
    {
        $items = [];
        foreach ($keys as $key) {
            $items[] = $this->getItem($key);
        }

        return $items;
    }

    public function hasItem($key)
    {
        $path = Config::getPath('app/paths/cache', $key);

        return file_exists($path);
    }

    public function clear()
    {
        $files = glob($this->cache_dir);
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    public function deleteItem($key)
    {
        $file = $this->cache_dir . $key;
        if (is_file($file)) {
            return unlink($file);
        }

        return false;
    }

    public function deleteItems($keys)
    {
        $items = [];
        foreach ($keys as $key) {
            $file = $this->cache_dir . $key;
            if (is_file($file)) {
                $items[$key] = unlink($file);
            }
        }

        return $items;
    }

    public function save(CacheItemInterface $item)
    {
        return file_put_contents($this->cache_dir .$item->getKey(), $item->get());
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
