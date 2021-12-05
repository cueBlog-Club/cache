<?php
declare(strict_types=1);

namespace CuePhp\Cache;

use PHPUnit\Framework\TestCase;
use CuePhp\Cache\Config\EngineConfig;

class EngineConfigTest extends TestCase 
{
    public function testAccess()
    {
        $config = new EngineConfig();
        $config->setTTL(10);
        $config->setPrefix('cache_');

        $this->assertSame('cache_', $config->getPrefix());
        $this->assertSame(10, $config->getTTL());
    }
    
}