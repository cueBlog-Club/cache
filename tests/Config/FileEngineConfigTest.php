<?php
declare(strict_types=1);

namespace CuePhp\Cache;

use CuePhp\Cache\Config\FileEngineConfig;
use PHPUnit\Framework\TestCase;

class FileEngineConfigTest extends TestCase 
{
    public function testAccess()
    {
        $config = new FileEngineConfig();
        $config->setTTL(10);
        $config->setPrefix('cache_');

        $this->assertSame('cache_', $config->getPrefix());
        $this->assertSame(10, $config->getTTL());
        $this->assertSame("/tmp", $config->getPath());
        $this->assertSame(0664, $config->getMask());
    }

    public function testAccessWithPath()
    {
        $config = new FileEngineConfig( "/newpath" );
        $config->setMask(0666);

        $this->assertSame("/newpath", $config->getPath());
        $this->assertSame(0666, $config->getMask());
    }
    
}