<?php

declare(strict_types=1);

namespace CuePhp\Cache\Engine;

use CuePhp\Cache\Config\InMemoryConfig;
use CuePhp\Cache\Engine\EngineBase;
use CuePhp\Cache\Exception\InvalidArgumentException;
use CuePhp\Cache\Exception\RuntimeException;

class InMemoryEngine extends EngineBase
{
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
    protected $_config = null;

    const DATA_VALUE_KEY_NAME = 'value';
    const DATA_EXPIRE_KEY_NAME = 'expire';

    public function __construct(?InMemoryConfig $_config)
    {
        $this->_config = $_config;
        parent::__construct();
    }

    protected function init(): bool
    {
        return true;
    }

    /**
     * @var string $key
     * @var mixed $default
     * @return @nixed
     */
    public function get($key, $default = null)
    {
        $this->ensureArgument($key);
        if (!$this->has($key)) {
            return $default;
        }
        return $this->_data[$key][self::DATA_VALUE_KEY_NAME];
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
        $this->_data[$key] = [
            self::DATA_VALUE_KEY_NAME => $value,
            self::DATA_EXPIRE_KEY_NAME => $ttl
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
        unset($this->_data[$key]);
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
        if (!isset($this->_data[$key])) {
            return false;
        }
        $expire = $this->_data[$key][self::DATA_EXPIRE_KEY_NAME];
        if ($expire && $expire < time()) {
            // lazy-delete
            $this->delete($key);
            return false;
        }
        return true;
    }

    public function incr(string $key, int $offset = 1)
    {
        throw new RuntimeException(' can not be incr ');
    }

    public function decr(string $key, int $offset = 1)
    {
        throw new RuntimeException(' can not be decr ');
    }
}
