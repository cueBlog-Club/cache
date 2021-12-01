<?php
declare(strict_types=1);

namespace CuePhp\Cache\Traits;

trait InstanceTrait
{
    /**
     * @var this|null
     */
    protected static $_single = null;

    /**
     * @return this
     */
    public static function getInstance()
    {
        if (static::$_single !== null) {
            static::$_single = new static();
        }
        return static::$_single;
    }
}
