<?php
declare(strict_types=1);

namespace CuePhp\Cache;

use CuePhp\Cache\Config\YacEngineConfig;
use PHPUnit\Framework\TestCase;

class YacEngineConfigTest extends TestCase 
{
    public function testAccess()
    {
        $config = new YacEngineConfig();
        $config->setTTL(10);
        $config->setPrefix('cache_');

        $this->assertSame('cache_', $config->getPrefix());
        $this->assertSame(10, $config->getTTL());
    }
    
}