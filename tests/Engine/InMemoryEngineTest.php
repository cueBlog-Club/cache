<?php

declare(strict_types=1);

namespace CuePhp\Cache;

use CuePhp\Cache\Config\InMemoryConfig;
use PHPUnit\Framework\TestCase;
use CuePhp\Cache\Engine\InMemoryEngine;
use CuePhp\Cache\Exception\InvalidArgumentException;
use TypeError;

class InMemoryEngineTest extends TestCase
{

    /**
     * @var InMemoryEngine
     */
    protected $engine = null;

    protected function setUp(): void
    {
        $config = new InMemoryConfig();
        $config->setPrefix('mem_');
        $config->setTTL(100);
        $this->engine = new InMemoryEngine($config);
    }

    protected function tearDown(): void
    {
        unset($this->engine);
    }

    public function testSetAndGet()
    {
        // assert default
        $this->assertSame(10, $this->engine->get('key2', 10));
        // assert set func
        $this->engine->set('key1', 'value1', 100);
        $this->assertSame('value1', $this->engine->get('key1'));

        // assert exception
        $this->expectException(InvalidArgumentException::class);
        $this->engine->get('');
        $this->expectException(TypeError::class);
        $this->engine->get(7);
    }

    public function testDeleteAndHas()
    {
        $this->engine->set('key3', 1);
        $this->assertSame(true, $this->engine->has('key3'));

        $this->engine->delete('key3');
        $this->assertSame(false, $this->engine->has('key3'));

        // 
        $this->engine->set('key3', 1);
        sleep(2);
        $this->assertSame(false, $this->engine->has('key3'));
    }

    public function testIncr()
    {
        $this->engine->set('key4', 1);

        $this->assertSame(2, $this->engine->incr('key4')->getData());
        $this->assertSame(12, $this->engine->incr('key4', 10)->getData());
    }

    public function testDesc()
    {
        $this->engine->set('key5', 20);

        $this->assertSame(19, $this->engine->decr('key5')->getData());
        $this->assertSame(9, $this->engine->decr('key5', 10)->getData());
    }
}
