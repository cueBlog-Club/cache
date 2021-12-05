<?php

declare(strict_types=1);

namespace CuePhp\Cache;

use CuePhp\Cache\Config\RedisEngineConfig;
use PHPUnit\Framework\TestCase;

class RedisEngineConfigTest extends TestCase
{
    public function testAccess()
    {
        $config = new RedisEngineConfig();
        $config->setTTL(10);
        $config->setPrefix('cache_');
        $config->setHost("redis://");

        $this->assertSame('cache_', $config->getPrefix());
        $this->assertSame(10, $config->getTTL());
        $this->assertSame("redis://", $config->getHost());
    }
}
