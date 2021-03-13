<?php


namespace App\Services;


use App\Services\TemplatesCache\TemplateCacheItem;
use App\Services\TemplatesCache\TemplatesCache;

class HtmlCache implements \Psr\SimpleCache\CacheInterface
{
    protected TemplatesCache $pool;

    public function __construct(TemplatesCache $pool)
    {
        $this->pool = $pool;
    }

    /**
     * @inheritDoc
     */
    public function get($key, $default = null)
    {
        $item = $this->pool->getItem($key);

        return $item->isHit() ? $item->get() : $default;
    }

    /**
     * @inheritDoc
     */
    public function set($key, $value, $ttl = null)
    {
        $cacheItem = new TemplateCacheItem($key);
        $cacheItem->set($value)->expiresAfter($ttl);

        $this->pool->save($cacheItem);
    }

    /**
     * @inheritDoc
     */
    public function delete($key)
    {
        $this->pool->deleteItem($key);
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
            $this->set($key, $value, $ttl);
        }
    }

    /**
     * @inheritDoc
     */
    public function deleteMultiple($keys)
    {
        $this->pool->deleteItems($keys);
    }

    /**
     * @inheritDoc
     */
    public function has($key)
    {
        return $this->pool->hasItem($key);
    }
}
