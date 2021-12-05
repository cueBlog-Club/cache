<?php

declare(strict_types=1);

namespace CuePhp\Cache\Engine;

use CuePhp\Cache\Exception\RuntimeException;
use CuePhp\Cache\Exception\InvalidArgumentException;
use Redis as RedisClient;
use CuePhp\Cache\Config\RedisEngineConfig;
use CuePhp\Cache\Counter;
use CuePhp\Cache\Traits\CounterTrait;

final class RedisEngine extends EngineBase implements CounterInterface
{
    use CounterTrait;
    /**
     * redis engine instance
     * @var RedisClient
     */
    private $_redis;

    /**
     * @var RedisEngineConfig
     */
    protected $config;


    public function __construct(RedisEngineConfig $config)
    {
        $this->config = $config;
        parent::__construct();
    }

    protected function init(): bool
    {
        if (!extension_loaded('redis')) {
            throw new RuntimeException("redis extension is missing");
        }
        $this->_connect();
        $this->selectDatabase($this->config->getDatabase());
        return true;
    }

    /**
     * connect to redis server
     */
    private function _connect()
    {
        $this->_redis = new RedisClient();
        if ($this->config->getHost() === '') {
            throw new InvalidArgumentException('Redis Host must be not empty');
        }
        $this->_redis->pconnect($this->config->getHost(), $this->config->getPort(), $this->config->getTimeout());
        if (!empty($this->config->getUsername()) || !empty($this->config->getPassword())) {
            $this->_auth();
        }
    }

    /**
     * auth redis username and password
     */
    private function _auth()
    {
        if ($this->config->getPassword() && $this->config->getUsername()) {
            $this->_redis->auth([$this->config->getUsername(), $this->config->getPassword()]);
        } elseif (!empty($this->config->getPassword())) {
            $this->_redis->auth($this->config->getPassword());
        } else {
            throw new RuntimeException('user password is woring');
        }
    }

    /**
     * select redis database, default is 0
     */
    public function selectDatabase(int $database): bool
    {
        return $this->_redis->select($database);
    }

    /**
     * @var string key
     */
    public function get($key, $default = null)
    {
        $this->ensureArgument($key);
        return $this->_redis->get($key) ?? $default;
    }

    /**
     * @var string $key
     * @var mixed $value
     * @var int $ttl
     */
    public function set($key, $value, $ttl = null)
    {
        $this->ensureArgument($key);
        if ($ttl) {
            return $this->_redis->setex($key, $ttl, $value);
        }
        return $this->_redis->set($key, $value);
    }

    public function clear(): bool
    {
        return $this->_redis->flushDB();
    }

    public function delete($key): bool
    {
        $this->ensureArgument($key);
        return $this->_redis->del($key) >= 0;
    }

    /**
     * @var string $key
     * @return bool
     */
    public function has($key): bool
    {
        $isExist = $this->_redis->exists($key);
        //  phpredis <= 4.0.0 is return true or false
        if (is_bool($isExist)) {
            return $isExist;
        }
        return $isExist > 0;
    }

    /**
     * @var array<string> $keys
     * @var mixed $default
     * @return iterable
     */
    public function getMultiple($keys, $default = null): iterable
    {
        foreach ($keys as $value) {
            $this->ensureArgument($value);
        }
        $items = array_combine((array)$keys, $this->_redis->mGet($keys));
        foreach ($items as $key => $value) {
            if ($value === `FALSE`) {
                $items[$key] = $default[$key];
            }
        }
        return $items;
    }

    /**
     * @var array<string, mixed> $values
     * @var int $ttl
     * @return bool
     */
    public function setMultiple($values, $ttl = null)
    {
        if ($ttl) {
            $multi = $this->_redis->multi(RedisClient::PIPELINE);
            foreach ($values as $key => $value) {
                $this->ensureArgument($key);
                $multi->setex($key, $ttl, $value);
            }
            $successCount = count(array_filter($multi->exec()));
            return $successCount === count($values);
        }
        return $this->_redis->mSet($values);
    }

    /**
     * @var array<string> $keys
     */
    public function deleteMultiple($keys): bool
    {
        return $this->_redis->del($keys);
    }

    /**
     * @return RedisClient
     */
    public function getEngine(): RedisClient
    {
        return $this->_redis;
    }

      /**
     * @var string $key
     * @var int $offset
     * @return int
     */
    public function incr(string $key, int $offset = 1): Counter
    {
        $this->ensureArgument($key);
        if ($offset > 1) {
            $result = $this->_redis->incrBy($key, $offset);
        } else {
            $result = $this->_redis->incr($key);
        }
        if ($result === false) {
            throw new RuntimeException('value must be number');
        }
        return $result;
    }

    /**
     * @var string $key
     * @var int $offset
     * @return bool
     */
    public function decr(string $key, int $offset = 1): Counter
    {
        $this->ensureArgument($key);
        if ($offset > 1) {
            $result = $this->_redis->decrBy($key, $offset);
        } else {
            $result = $this->_redis->decr($key);
        }
        if ($result === false) {
            throw new RuntimeException('key is missing');
        }
        return $result;
    }
}
