<?php


namespace App\Services\TemplatesCache;

use Psr\Cache\CacheItemInterface;

final class TemplateCacheItem implements CacheItemInterface
{

    protected $key;
    protected $value;
    protected $isHit = false;
    protected $expiry;

    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * @inheritDoc
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @inheritDoc
     */
    public function get()
    {
        return $this->value;
    }

    /**
     * @inheritDoc
     */
    public function isHit()
    {
        // TODO: Implement isHit() method.
    }

    /**
     * @inheritDoc
     */
    public function set($value)
    {
        $this->value = $value;
    }

    /**
     * @inheritDoc
     */
    public function expiresAt($expiration)
    {
        // TODO: Implement expiresAt() method.
    }

    /**
     * @inheritDoc
     */
    public function expiresAfter($time)
    {
        // TODO: Implement expiresAfter() method.
    }
}
