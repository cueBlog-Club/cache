<?php
declare(strict_types=1);

namespace CuePhp\Cache\Engine;

use CuePhp\Cache\Exception\RuntimeException;
use CuePhp\Cache\Exception\InvalidArgumentException;
use Memcached;
use CuePhp\Cache\Config\MemcacheEngineConfig;

final class MemcacheEngine extends EngineBase
{
    /**
     * memcached engine instance
     * @var Memcached
     */
    private $_memcached;

    /**
     * @var MemcacheEngineConfig
     */
    protected $config;

    public function init(): bool
    {
        return true;
    }

    public function get($key, $default = null)
    {
    }

    public function set($key, $value, $ttl = null)
    {
        return true;
    }

    public function clear(): bool
    {
        return true;
    }

    public function delete($key): bool
    {
        return true;
    }

    public function incr(string $key, int $offset = 1)
    {
        return $offset;
    }

    public function decr(string $key, int $offset = 1)
    {
        return $offset;
    }
}
