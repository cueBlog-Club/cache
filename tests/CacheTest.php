<?php
declare(strict_types=1);

namespace CuePhp\Cache;

use PHPUnit\Framework\TestCase;
use CuePhp\Cache\Cache;
use CuePhp\Cache\Exception\InvalidArgumentException;
use DateInterval;
use DateTime;

class CacheTest extends TestCase 
{

    public function testAccess()
    {
        $cache  = new Cache('key', 'value');
        $this->assertSame( 'key', $cache->getKey() );
        $this->assertSame( Cache::DEFAULT_TTL, $cache->getTTL() );
        $this->assertSame(false, $cache->isHit());
        // 
        $cache->setIsHit(true);
        $this->assertSame( 'value', $cache->get() );
        $this->assertSame(true, $cache->isHit());
        //
        $cache->setIsHit(false);
        $this->assertNull(  $cache->get() );

    }

    public function testExpiresAt()
    {
        $cache  = new Cache('key', 'value', 10);

        // assert null
        $cache->expiresAt(null);
        $this->assertSame(0, $cache->getTTL());

        // asset datetime
        $date = new DateTime('+1 day');
        $cache->expiresAt($date);
        $this->assertSame(86400, $cache->getTTL());

        // assert error
        $this->expectException( InvalidArgumentException::class );
        $cache->expiresAt(100);
    }

    public function testExpiresAfter()
    {
        $cache  = new Cache('key', 'value', 10);

        // assert null
        $cache->expiresAfter(null);
        $this->assertSame(0, $cache->getTTL());

        // asset DateInterval
        $date = (new DateTime( "+1 day" ))->diff( new DateTime() );
        $cache->expiresAfter($date);
        $this->assertSame(-86400, $cache->getTTL());

        //assert int
        $cache->expiresAfter(20);
        $this->assertSame(20, $cache->getTTL());

        // assert error
        $date = new DateTime('+1 day');
        $this->expectException( InvalidArgumentException::class );
        $cache->expiresAfter($date);

    }
}