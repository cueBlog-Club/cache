<?php

declare(strict_types=1);

namespace CuePhp\Cache\Engine;

use CuePhp\Cache\Exception\RuntimeException;
use CuePhp\Cache\Exception\InvalidArgumentException;
use CuePhp\Cache\Config\YacEngineConfig;
use CuePhp\Cache\Counter;
use CuePhp\Cache\Traits\CounterTrait;

final class YacEngine extends EngineBase implements CounterInterface
{
    use CounterTrait;

    /**
     * yac client
     * @var \Yac
     */
    private $_client = null;

    /**
     * @var YacEngineConfig
     */
    protected $config;

    /**
     * if your key is longer than this, maybe you can use md5 result as the key
     */
    const KEY_MAX_LEN = 48;

    public function __construct(YacEngineConfig $config)
    {
        $this->config = $config;
        parent::__construct();
    }

    protected function init(): bool
    {
        if (!extension_loaded('yac')) {
            throw new RuntimeException(`extension yac must be loaded`);
        }
        // return parent::init( $config );
        $this->_client = new \Yac($this->config->getPrefix());
        return true;
    }

    /**
     * @var string $key
     * @var mix $default
     * @return mix
     * @throws InvalidArgumentException
     */
    public function get($key, $default = null)
    {
        $this->ensureArgument($key);
        if (strlen($key) > self::KEY_MAX_LEN) {
            $key = $this->_fixCacheKey($key);
        }
        return $this->_client->get($key) ?? $default;
    }

    /**
     * @var iterable $keys
     * @var mix $default
     * @return iterable
     * @throws InvalidArgumentException
     */
    public function getMultiple($keys, $default = null): iterable
    {
        if (!is_array($keys)) {
            return [];
        }
        $hashKeyMap = [];
        foreach ($keys as $index => $key) {
            $this->ensureArgument($key);
            if (strlen($key) > self::KEY_MAX_LEN) {
                $keys[$index] = $this->_fixCacheKey($key);
                $hashKeyMap[$keys[$index]] = $key;
            }
        }
        $results = $this->_client->get($keys);
        if ($results !== false) {
            foreach ($results as $key => $value) {
                if (isset($hashKeyMap[$key])) {
                    $results[$hashKeyMap[$key]] = $value;
                    unset($results[$key]);
                }
            }
            return $results;
        }
        $results = [];
        foreach ($keys as $key) {
            $results[$key] = $default;
        }
        return $results;
    }

    /**
     * @var string $key
     * @var mix $value
     * @var int $ttl
     * @return bool
     * @throws InvalidArgumentException
     */
    public function set($key, $value, $ttl = null)
    {
        $this->ensureArgument($key);
        if (strlen($key) > self::KEY_MAX_LEN) {
            $key = $this->_fixCacheKey($key);
        }
        if ($this->config->ttl) {
            return $this->_client->set($key, $value, $ttl ?? $this->config->ttl);
        }
        return $this->_client->set($key, $value);
    }

    /**
     * @var array $values
     * @var int $ttl
     * @return bool
     * @throws InvalidArgumentException
     */
    public function setMultiple($values, $ttl = null)
    {
        if (!is_array($values)) {
            return false;
        }
        foreach ($values as $key => $value) {
            if (strlen($key) > self::KEY_MAX_LEN) {
                $values[$this->_fixCacheKey($key)] = $value;
                unset($values[$key]);
            }
        }
        if ($this->config->ttl) {
            return $this->_client->set($values, $ttl ?? $this->config->ttl);
        }
        return $this->_client->set($values);
    }

    public function clear(): bool
    {
        return $this->_client->flush();
    }

    /**
     * @var string $key
     * @return bool
     */
    public function delete($key): bool
    {
        if (is_array($key)) {
            return  $this->deleteMultiple($key);
        }
        $key = $this->_fixCacheKey($key);
        return $this->_client->delete($key);
    }

    /**
     * @var array $keys
     * @return bool
     */
    public function deleteMultiple($keys): bool
    {
        foreach ($keys as $index => $key) {
            $keys[$index] = $this->_fixCacheKey($key);
        }
        return $this->_client->delete($keys);
    }

    private function _fixCacheKey(string $key): string
    {
        if (strlen($key) > self::KEY_MAX_LEN) {
            $key = md5($key);
        }
        return $key;
    }

    /**
     * @return Yac
     */
    public function getEngine(): Yac
    {
        return $this->_client;
    }

      /**
     * @var string $key
     * @var int $offset
     * @return Counter
     */
    public function incr(string $key, int $offset = 1): Counter
    {
        $key = $this->_fixCacheKey($key);
        $value = $this->_client->get($key);
        $result = $value + $offset;
        $this->_client->set($key, $result);
        return $result;
    }

    /**
     * @var string $key
     * @var int $offset
     * @return Counter
     */
    public function decr(string $key, int $offset = 1): Counter
    {
        $key = $this->_fixCacheKey($key);
        $value = (int) $this->_client->get($key);
        $result = $value - $offset;
        $this->_client->set($key, $result);
        return $result;
    }
}
