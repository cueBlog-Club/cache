<?php
declare(strict_types=1);

namespace CuePhp\Cache;

use CuePhp\Cache\Engine\EngineInterface;
use CuePhp\Cache\Exception\RuntimeException;
use CuePhp\Cache\Traits\InstanceTrait;

final class CacheManager
{
    use InstanceTrait;

    /**
     * @var <name, EngineInterface>
     */
    protected $_engines = [];

    /**
     * @var string $name
     * @throws RuntimeException
     * @return EngineInterface
     */
    public function __get(string $name): EngineInterface
    {
        if ($this->has($name)) {
            return $this->_engines[$name];
        }
        throw new RuntimeException(`Unknown object ${name}`);
    }

    /**
     * @var string $name
     * @var object $value
     * @return void
     */
    public function __set(string $name, object $value): void
    {
        if (array_key_exists($name, $this->_engines)) {
            // remove old value
            unset($this->_engines[$name]);
        }
        $this->_engines[$name] = $value; 
    }

    /**
     * @var string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return isset($this->_engines[$name]);
    }
}
