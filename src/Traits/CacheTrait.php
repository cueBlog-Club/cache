<?php
declare(strict_types=1);

namespace CuePhp\Cache\Traits;

use CuePhp\Cache\Cache;
use Psr\Cache\CacheItemInterface;

trait CacheTrait
{

    /**
     * @return this
     */
    protected function transferToCache(string $key, $value): CacheItemInterface
    {
         return  new Cache($key, null, -100);
    }
}
