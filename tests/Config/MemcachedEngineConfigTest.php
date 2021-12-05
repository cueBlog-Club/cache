<?php

declare(strict_types=1);

namespace CuePhp\Cache;

use CuePhp\Cache\Config\MemcacheEngineConfig;
use PHPUnit\Framework\TestCase;

class MemcachedEngineConfigTest extends TestCase
{
    public function testAccess()
    {
        $config = new MemcacheEngineConfig();
        $config->setTTL(10);
        $config->setPrefix('cache_');
        $config->setHost('memcached://');

        $this->assertSame('cache_', $config->getPrefix());
        $this->assertSame(10, $config->getTTL());
        $this->assertSame("memcached://", $config->getHost());
        $this->assertSame(100, $config->getWeight());
        $this->assertSame(11211, $config->getPort());
    }

    public function testAccessWithDynamic()
    {
        $config = new MemcacheEngineConfig();
        $config->setHost('memcached://');
        $config->setWeight(50);
        $config->setPort(6379);


        $this->assertSame("memcached://", $config->getHost());
        $this->assertSame(50, $config->getWeight());
        $this->assertSame(6379, $config->getPort());
    }
}
