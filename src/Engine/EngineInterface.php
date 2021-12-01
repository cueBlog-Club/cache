<?php

declare(strict_types=1);

namespace CuePhp\Cache\Engine;

use CuePhp\Cache\Config\EngineConfig;
use Psr\SimpleCache\CacheInterface;

interface EngineInterface extends CacheInterface
{

    /**
     * @param string $key
     * @param int $offset
     * @return int
     */
    public function incr(string $key, int $offset = 1);

    /**
     * @param string $key
     * @param int $offset
     * @return int
     */
    public function decr(string $key, int $offset = 1);
}
