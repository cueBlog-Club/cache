<?php

declare(strict_types=1);

namespace CuePhp\Cache\Config;

final class RedisEngineConfig extends EngineConfig
{
    /**
     * @var string
     */
    private $_host = '';

    /**
     * @var int
     */
    private  $_port = 6379;

    /**
     * @var int
     */
    private $_timeout = 5;

    /**
     * @var string
     */
    private $_username = "";

    /**
     * @var string
     */
    private $_password = "";

    /**
     * @var int
     */
    private $_database = 0;

    public function setHost(string $host)
    {
        $this->_host = $host;
    }

    public function setPort(int $port)
    {
        $this->_port = $port;
    }

    public function setTimeout(int $time)
    {
        $this->_host = $time;
    }

    public function setUsername(string $name)
    {
        $this->_username = $name;
    }

    public function setPassword(string $password)
    {
        $this->passthru = $password;
    }

    public function setDatabase(int $db)
    {
        $this->_database = $db;
    }

    public function getHost(): string
    {
        return $this->_host;
    }

    public function getPort(): int
    {
        return $this->_port;
    }

    public function getTimeout(): int
    {
        return $this->_timeout;
    }

    public function getUsername(): string
    {
        return $this->_username;
    }

    public function getPassword(): string
    {
        return $this->_password;
    }

    public function getDatabase(): int
    {
        return $this->_database;
    }
}
