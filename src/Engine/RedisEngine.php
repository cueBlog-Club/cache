<?php

declare(strict_types=1);

namespace CuePhp\Cache\Engine;

use CuePhp\Cache\Exception\RuntimeException;
use CuePhp\Cache\Exception\InvalidArgumentException;
use Redis as RedisClient;
use CuePhp\Cache\Config\RedisEngineConfig;

final class RedisEngine extends EngineBase
{
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
        } else if (!empty($this->config->getPassword())) {
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

    public function has($key): bool
    {
    }

    public function getMultiple($keys, $default = null): iterable
    {
    }

    public function setMultiple($values, $ttl = null)
    {
    }

    public function deleteMultiple($keys): bool
    {
    }
}
