<?php

declare(strict_types=1);

namespace CuePhp\Cache\Config;

class EngineConfig
{

    /**
     * @var int
     */
    protected $ttl = 3600;

    /**
     * @var string
     */
    protected $prefix = '';

    /**
     * @var int $ttl
     * @return $this
     */
    public function setTTL( int $ttl )
    {
        $this->ttl = $ttl;
        return $this;
    }

    /**
     * @var string $prefix
     * @return $this
     */
    public function setPrefix( string $prefix )
    {
        $this->prefix = $prefix;
        return $this;
    }

    /**
     * @var string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * @return int
     */
    public function getTTL(): int
    {
        return $this->ttl;
    }
}
