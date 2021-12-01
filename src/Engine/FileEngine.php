<?php
declare(strict_types=1);

namespace CuePhp\Cache\Engine;

use CuePhp\Cache\Config\FileEngineConfig;
use CuePhp\Cache\Engine\EngineBase;
use FilesystemIterator;
use SplFileInfo;
use SplFileObject;
use CuePhp\Cache\Exception\InvalidArgumentException;
use CuePhp\Cache\Exception\RuntimeException;

class FileEngine extends EngineBase
{
    /**
     * file instance
     * @var SplFileObject|null
     */
    protected $_file;

    /**
     * @var FileEngineConfig
     */
    protected $_config;

    public function __construct(FileEngineConfig $_config)
    {
        $this->_config = $_config;
        parent::__construct();
    }

    protected function init(): bool
    {
        return true;
    }

    public function get($key, $default = null)
    {
    }

    public function set($key, $value, $ttl = null)
    {
        return true;
    }

    public function delete($key): bool
    {
        return true;
    }

    public function clear(): bool
    {
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
