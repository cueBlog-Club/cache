<?php
declare(strict_types=1);

namespace CuePhp\Cache;

use PHPUnit\Framework\TestCase;
use CuePhp\Cache\Counter;

class CounterTest extends TestCase 
{
    public function testCreate()
    {
        $counter = Counter::create( 'key', 1 );

        $this->assertSame('key', $counter->getName());
        $this->assertSame(1, $counter->getData());
    }
    
}