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

        // TODO
//        $this->expiry = 123123;

//        if () {
//            $this->isHit = true;
//        }
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
        return $this->isHit;
    }

    /**
     * @inheritDoc
     */
    public function set($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function expiresAt($expiration)
    {
        if (null === $expiration) {
            $this->expiry = null;
        } elseif ($expiration instanceof \DateTimeInterface) {
            $this->expiry = (float)$expiration->format('U.u');
        } else {
            throw new \Exception(sprintf('Expiration date must implement DateTimeInterface or be null, "%s" given.', get_debug_type($expiration)));
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function expiresAfter($time)
    {
        if (null === $time) {
            $this->expiry = null;
        } elseif ($time instanceof \DateInterval) {
            $this->expiry = microtime(true) + \DateTime::createFromFormat('U', 0)->add($time)->format('U.u');
        } elseif (\is_int($time)) {
            $this->expiry = $time + microtime(true);
        } else {
            throw new \Exception(sprintf('Expiration date must be an integer, a DateInterval or null, "%s" given.', get_debug_type($time)));
        }

        return $this;
    }
}
