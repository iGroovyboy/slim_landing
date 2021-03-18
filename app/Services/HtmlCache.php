<?php


namespace App\Services;


use App\Services\TemplatesCache\TemplateCacheItem;
use App\Services\TemplatesCache\TemplatesCache;

class HtmlCache implements \Psr\SimpleCache\CacheInterface
{
    protected TemplatesCache $pool;
    protected string $ext = '.html';
    protected $cache_fallback_dir = '';

    public function __construct(TemplatesCache $pool)
    {
        $this->pool = $pool;
    }

    public function setCacheFallbackDir(string $path)
    {
        $this->cache_fallback_dir = $path;

        return $this;
    }

    public function setCacheDir(string $path)
    {
        $this->pool->setCacheDir($path);

        return $this;
    }

    public function setCacheExtension(string $ext)
    {
        $this->ext = '.' . $ext;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function has($key)
    {
        $hasItem = $this->pool->hasItem($key . $this->ext);

        // if key not found in cacheDir - look in fallback dir
        if ( ! $hasItem) {
            $originalCacheDir = $this->pool->getCacheDir();

            $this->setCacheDir($this->cache_fallback_dir);

            if ($this->pool->hasItem($key . $this->ext)) {
                $this->setCacheDir($originalCacheDir);

                return true;
            }
        }

        return $hasItem;
    }

    /**
     * @inheritDoc
     */
    public function get($key, $default = null)
    {
        $item = $this->pool->getItem($key . $this->ext);

//        return $item->isHit() ? $item->get() : $default; // TODO
        return $item->get() ?: $default;
    }

    /**
     * @inheritDoc
     */
    public function set($key, $value, $ttl = null)
    {
        $cacheItem = new TemplateCacheItem($key . $this->ext);
        $cacheItem->set($value)->expiresAfter($ttl);

        $this->pool->save($cacheItem);
    }

    /**
     * @inheritDoc
     */
    public function delete($key)
    {
        $this->pool->deleteItem($key . $this->ext);
    }

    /**
     * @inheritDoc
     */
    public function clear()
    {
        $this->pool->clear();
    }

    /**
     * @inheritDoc
     */
    public function getMultiple($keys, $default = null)
    {
        return $this->pool->getItems($keys) ?: $default;
    }

    /**
     * @inheritDoc
     */
    public function setMultiple($values, $ttl = null)
    {
        foreach ($values as $key => $value) {
            $this->set($key . $this->ext, $value, $ttl);
        }
    }

    /**
     * @inheritDoc
     */
    public function deleteMultiple($keys)
    {
        $this->pool->deleteItems($keys);
    }

}
