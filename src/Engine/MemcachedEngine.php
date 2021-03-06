<?php

declare(strict_types=1);

namespace CuePhp\Cache\Engine;

use CuePhp\Cache\Exception\RuntimeException;
use CuePhp\Cache\Exception\InvalidArgumentException;
use Memcached as MemcachedClient;
use CuePhp\Cache\Config\MemcacheEngineConfig;
use CuePhp\Cache\Counter;
use CuePhp\Cache\Traits\CacheTrait;
use CuePhp\Cache\Traits\CounterTrait;
use Psr\Cache\CacheItemInterface;

final class MemcachedEngine extends EngineBase implements CounterInterface
{
    use CounterTrait;
    use CacheTrait;
    /**
     * memcached engine instance
     * @var MemcachedClient
     */
    private $_memcached;

    /**
     * @var MemcacheEngineConfig
     */
    protected $config;

    const MAX_TTL = 30 * 60 * 3600;


    public function __construct(MemcacheEngineConfig $config)
    {
        $this->config = $config;
        parent::__construct();
    }

    /**
     * init loaded memcached engine
     * @return bool
     * @throws RuntimeException
     */
    protected function init(): bool
    {
        if (!extension_loaded('memcached')) {
            throw new RuntimeException("memcached extension is missing");
        }
        $this->_connect();
        return true;
    }

    private function _connect()
    {
        $this->_memcached = new MemcachedClient();
        if ($this->config->getHost() === '') {
            throw new InvalidArgumentException('Memcached Host must be not empty');
        }
        $this->_memcached->addServer($this->config->getHost(), $this->config->getPort(), $this->config->getWeight());
    }

    /**
     * @var string $key
     * @return CacheItemInterface
     */
    public function get($key, $default = null): CacheItemInterface
    {
        $this->ensureArgument($key);
        return $this->transferToCache($key, $this->_memcached->get($this->getCacheKey($key)));
    }

    /**
     * @var string $key
     * @var mixed $value
     * @var int ttl
     * @var bool
     */
    public function set($key, $value, $ttl = null)
    {
        $this->ensureArgument($key);
        if ($ttl > self::MAX_TTL) {
            $ttl = time() + $ttl;
        }
        return $this->_memcached->set($key, $value, $ttl);
    }

    public function clear(): bool
    {
        return $this->_memcached->flush();
    }

    public function delete($key): bool
    {
        $this->ensureArgument($key);
        return $this->_memcached->delete($key) || $this->_memcached->getResultCode() === MemcachedClient::RES_NOTFOUND;
    }

    /**
     * @return bool
     */
    public function has($key): bool
    {
        $this->get($key);
        return $this->_memcached->getResultCode() === MemcachedClient::RES_SUCCESS;
    }

    /**
     * @var array<string, mixed> $values
     * @var int $ttl
     * @return bool
     */
    public function setMultiple($values, $ttl = null)
    {
        if ($ttl > self::MAX_TTL) {
            $ttl = time() + $ttl;
        }
        foreach ($values as $key => $value) {
            $this->ensureArgument($key);
        }
        return $this->_memcached->setMulti($values, $ttl);
    }

    /**
     * @var array<string> $keys
     * @var array<string, mixed> $default
     * @return iterable array<string, mixed>
     */
    public function getMultiple($keys, $default = null): iterable
    {
        foreach ($keys as $value) {
            $this->ensureArgument($value);
        }
        $result = $this->_memcached->getMulti($keys);
        if ($result === false) {
            foreach ($keys as $index => $key) {
                $result[$key] = $default[$index];
            }
        }
        return $result;
    }

    /**
     * @var array<string> $keys
     * @return bool
     */
    public function deleteMultiple($keys): bool
    {
        foreach ($keys as $value) {
            $this->ensureArgument($value);
        }
        return $this->_memcached->deleteMulti($keys) || $this->_memcached->getResultCode() === MemcachedClient::RES_NOTFOUND;
    }

    /**
     * @return MemcachedClient
     */
    public function getEngine(): MemcachedClient
    {
        return $this->_memcached;
    }

       /**
     * @var string $key
     * @var int $offset
     * @return Counter
     */
    public function incr(string $key, int $offset = 1): Counter
    {
        $this->ensureArgument($key);
        $result = $this->_memcached->increment($this->config->getPrefix() . $key, $offset);
        if ($result === false) {
            throw new RuntimeException('value must be number');
        }
        return $this->transferToCounter($key, $result);
    }

    /**
     * @var string $key
     * @var int $offset
     * @return Counter
     */
    public function decr(string $key, int $offset = 1): Counter
    {
        $this->ensureArgument($key);
        $result = $this->_memcached->decrement($this->config->getPrefix() .$key, $offset);
        if ($result === false) {
            throw new RuntimeException('key is missing');
        }
        return $this->transferToCounter($key, $result);
    }
}
