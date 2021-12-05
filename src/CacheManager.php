<?php

declare(strict_types=1);

namespace CuePhp\Cache;

use CuePhp\Cache\Exception\InvalidArgumentException;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\SimpleCache\CacheInterface;
use CuePhp\Cache\Cache;
use Closure;

final class CacheManager implements CacheItemPoolInterface
{
    /**
     * @var CacheInterface
     */
    protected $engine = null;

    /**
     * @var Closure
     */
    protected $closure = null;

    public function __construct(CacheInterface  $engine)
    {
        $this->engine = $engine;
        $this->closure =  Closure::bind(
            static function (string $key, $val) {
                $item = new Cache($key, $val);
                $item->setIsHit($val !== null);
                return $item;
            },
            null,
            Cache::class
        );
    }

    /**
     * @var string $key
     * @return CacheItemInterface
     */
    public function getItem($key)
    {
        if (!is_string($key) || empty($key)) {
            throw new InvalidArgumentException('key must not be empty string');
        }
        $value = $this->engine->get($key);
        return ($this->closure)($key, $value);
    }

    /**
     * @var array<string> $keys
     * @return CacheItemInterface[]
     */
    public function getItems(array $keys = array())
    {
        foreach ($keys as $key) {
            if (!is_string($key) || empty($key)) {
                throw new InvalidArgumentException('key must not be empty string');
            }
        }
        $values = $this->engine->getMultiple($keys);
        return array_map($this->closure, array_keys(iterator_to_array($values)), $values);
    }

    /**
     * @var string
     * @return bool
     */
    public function hasItem($key)
    {
        return $this->getItem($key)->isHit();
    }

    /**
     * @return bool
     */
    public function clear()
    {
        return $this->engine->clear();
    }

    /**
     * @var string $key
     * @return bool
     */
    public function deleteItem($key)
    {
        return $this->deleteItems([$key]);
    }

    /**
     * @var array<string> $keys
     * @return bool
     * @throws InvalidArgumentException
     */
    public function deleteItems(array $keys)
    {
        foreach ($keys as $key) {
            if (!is_string($key) || empty($key)) {
                throw new InvalidArgumentException('key must not be empty string');
            }
        }
        return $this->engine->deleteMultiple($keys);
    }

    /**
     * @var CacheItemInterface $item
     * @return bool
     */
    public function save(CacheItemInterface $item)
    {
        if ($item instanceof Cache) {
            return $this->engine->set($item->getKey(), $item->get(), $item->getTTL());
        }
        throw new InvalidArgumentException(sprintf('Invalid type %s for $item', get_class($item)));
    }

    /**
     * TODO
     */
    public function saveDeferred(CacheItemInterface $item)
    {
        return false;
    }

    /**
     * TODO
     */
    public function commit()
    {
        return false;
    }
}
