<?php

declare(strict_types=1);

namespace CuePhp\Cache\Engine;

use CuePhp\Cache\Config\InMemoryConfig;
use CuePhp\Cache\Counter;
use CuePhp\Cache\Engine\EngineBase;
use CuePhp\Cache\Exception\InvalidArgumentException;
use CuePhp\Cache\Exception\RuntimeException;
use CuePhp\Cache\Traits\CounterTrait;

class InMemoryEngine extends EngineBase implements CounterInterface
{
    use CounterTrait;

    /**
     * @var array<string, array>
     * {
     *      "$key" => [
     *               "value" => "xxx",
     *               "expire" => 100
     *        ]
     * }
     */
    protected $_data;

    /**
     * @var InMemoryConfig|null
     */
    protected $config = null;

    const DATA_VALUE_KEY_NAME = 'value';
    const DATA_EXPIRE_KEY_NAME = 'expire';

    public function __construct(InMemoryConfig $config = null)
    {
        if( $config === null ) {
            $config = new InMemoryConfig();
        }
        $this->config = $config;
        parent::__construct();
    }

    protected function init(): bool
    {
        return true;
    }

    /**
     * @var string $key
     * @var mixed $default
     * @return $mixed
     * @throws InvalidArgumentException
     */
    public function get($key, $default = null)
    {
        $this->ensureArgument($key);
        if ($this->has($key)) {
            return $this->_data[$this->getCacheKey($key)][self::DATA_VALUE_KEY_NAME];
        }
        return $default;
    }

    /**
     * @var string $key
     * @var mixed $value
     * @var int $ttl
     * @return bool
     */
    public function set($key, $value, $ttl = null)
    {
        $this->ensureArgument($key);
        $this->_data[$this->getCacheKey($key)] = [
            self::DATA_VALUE_KEY_NAME => $value,
            self::DATA_EXPIRE_KEY_NAME => $ttl + time()
        ];
        return true;
    }

    /**
     * @var string $key
     * @return bool
     */
    public function delete($key): bool
    {
        $this->ensureArgument($key);
        unset($this->_data[$this->getCacheKey($key)]);
        return true;
    }

    /**
     * @return bool
     */
    public function clear(): bool
    {
        $this->_data = [];
        return true;
    }

    /**
     * @var string $key
     * @return bool
     */
    public function has($key): bool
    {
        $this->ensureArgument($key);
        if (!isset($this->_data[$this->getCacheKey($key)])) {
            return false;
        }
        $expire = $this->_data[$this->getCacheKey($key)][self::DATA_EXPIRE_KEY_NAME];
        if ($expire && $expire < time()) {
            // lazy-delete
            $this->delete($key);
            return false;
        }
        return true;
    }

    /**
    * @var string $key
    * @var int $offset
    * @return Counter
    */
    public function incr(string $key, int $offset = 1): Counter
    {
        $this->ensureArgument($key);
        if (isset($this->_data[$this->getCacheKey($key)][self::DATA_VALUE_KEY_NAME]) && !is_numeric($this->_data[$this->getCacheKey($key)][self::DATA_VALUE_KEY_NAME])) {
            throw new RuntimeException('value must be number');
        } elseif (!isset($this->_data[$this->getCacheKey($key)][self::DATA_VALUE_KEY_NAME])) {
            $this->_data[$this->getCacheKey($key)][self::DATA_VALUE_KEY_NAME]  = 0;
        }

        $this->_data[$this->getCacheKey($key)][self::DATA_VALUE_KEY_NAME] += $offset;
        return $this->transferToCounter($key, $this->_data[$this->getCacheKey($key)][self::DATA_VALUE_KEY_NAME]);
    }

    /**
     * @var string $key
     * @var int $offset
     * @return Counter
     */
    public function decr(string $key, int $offset = 1): Counter
    {
        $this->ensureArgument($key);
        if (isset($this->_data[$this->getCacheKey($key)][self::DATA_VALUE_KEY_NAME]) && !is_numeric($this->_data[$this->getCacheKey($key)][self::DATA_VALUE_KEY_NAME])) {
            throw new RuntimeException('value must be number');
        } elseif (!isset($this->_data[$this->getCacheKey($key)][self::DATA_VALUE_KEY_NAME])) {
            $this->_data[$this->getCacheKey($key)][self::DATA_VALUE_KEY_NAME]  = 0;
        }

        $this->_data[$this->getCacheKey($key)][self::DATA_VALUE_KEY_NAME] -= $offset;
        return $this->transferToCounter($key, $this->_data[$this->getCacheKey($key)][self::DATA_VALUE_KEY_NAME]);
    }
}
