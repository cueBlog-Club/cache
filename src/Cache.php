<?php
declare(strict_types=1);

namespace CuePhp\Cache;

use CuePhp\Cache\Exception\RuntimeException;
use CuePhp\Cache\CacheManager;
use CuePhp\Cache\Engine\EngineInterface;

final class Cache
{

    public static function addEngine(string $name, EngineInterface $engine)
    {
        $manager = CacheManager::getInstance();
    }

    protected static function _createEngine()
    {
    }

    public static function write()
    {
    }

    public static function read()
    {
    }
}
