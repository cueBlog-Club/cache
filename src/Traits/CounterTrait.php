<?php
declare(strict_types=1);

namespace CuePhp\Cache\Traits;

use CuePhp\Cache\Counter;

trait CounterTrait
{

    /**
     * @return this
     */
    protected function transferToCounter(string $key, int $value): Counter
    {
         return  Counter::create($key, $value);
    }
}
