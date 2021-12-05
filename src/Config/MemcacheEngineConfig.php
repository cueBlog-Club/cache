<?php

declare(strict_types=1);

namespace CuePhp\Cache\Config;

final class MemcacheEngineConfig extends EngineConfig
{
    /**
     * @var string
     */
    private $_host = null;

    /**
     * @var int
     */
    private $_port = 11211;

    /**
     * @var int
     */
    private $_weight = 100;

    public function getHost(): string
    {
        return $this->_host;
    }

    public function getPort(): int
    {
        return $this->_port;
    }

    public function getWeight(): int
    {
        return $this->_weight;
    }

    public function setHost(string $host)
    {
        $this->_host = $host;
    }

    public function setPort(int $port)
    {
        $this->_port = $port;
    }

    public function setWeight(int $weight)
    {
        $this->_weight = $weight;
    }
}
