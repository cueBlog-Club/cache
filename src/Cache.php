<?php

declare(strict_types=1);

namespace CuePhp\Cache;

use CuePhp\Cache\Exception\RuntimeException;
use CuePhp\Cache\CacheManager;
use CuePhp\Cache\Engine\EngineInterface;

final class Cache
{
    /**
     * @var EngineInterface
     */
    protected $engine = null;

    /**
     * @var EngineInterface
     */
    public function __construct(EngineInterface $engine)
    {
        $this->engine = $engine;
    }

    /**
     * @var string $key
     * @var mixed $values
     * @var int $ttl
     */
    public function write(string $key, $values, $ttl = 0)
    {
        return $this->engine->set($key, $values, $ttl);
    }

    public function read(string $key)
    {
        return $this->engine->get($key);
    }

    public function exist(string $key)
    {
        return $this->engine->has($key);
    }
}
