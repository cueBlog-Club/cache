<?php

declare(strict_types=1);

namespace CuePhp\Cache\Engine;

use CuePhp\Cache\Counter;

interface CounterInterface
{

    /**
     * @param string $key
     * @param int $offset
     * @return Counter
     */
    public function incr(string $key, int $offset = 1): Counter;

    /**
     * @param string $key
     * @param int $offset
     * @return Counter
     */
    public function decr(string $key, int $offset = 1): Counter;
}
