<?php
declare(strict_types=1);

namespace CuePhp\Cache;

use CuePhp\Cache\Config\InMemoryConfig;
use PHPUnit\Framework\TestCase;

class InMemoryConfigTest extends TestCase 
{
    public function testAccess()
    {
        $config = new InMemoryConfig();
        $config->setTTL(10);
        $config->setPrefix('cache_');

        $this->assertSame('cache_', $config->getPrefix());
        $this->assertSame(10, $config->getTTL());
    }
    
}