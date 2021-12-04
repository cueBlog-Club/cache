<?php

declare(strict_types=1);

namespace CuePhp\Cache\Engine;

use CuePhp\Cache\Config\FileEngineConfig;
use CuePhp\Cache\Engine\EngineBase;
use CuePhp\Cache\Exception\InvalidArgumentException;
use CuePhp\Cache\Exception\RuntimeException;
use CuePhp\Cache\Utils\Str;
use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

use const PHP_EOL;
use const DIRECTORY_SEPARATOR;
use const PATHINFO_DIRNAME;

class FileEngine extends EngineBase
{
    /**
     * @var FileEngineConfig
     */
    protected $config;


    const FILE_EXTENSION = '.cachedo';

    public function __construct(FileEngineConfig $_config)
    {
        $this->config = $_config;
        parent::__construct();
    }

    protected function init(): bool
    {
        $path = $this->config->getPath();
        if ($this->_createPathIfNeed($path) === false) {
            throw new InvalidArgumentException(`The Path ${path} does not exist and create failed`);
        }
        if (!is_writable($path)) {
            throw new InvalidArgumentException(`The Path ${path} is not writable`);
        }
        return true;
    }

    /**
     * @var string $path
     * @return bool
     */
    private function _createPathIfNeed(string $path): bool
    {
        if (!is_dir($path)) {
            if (@mkdir($path, $this->config->getMask(), true) === false) {
                return false;
            }
        }
        return true;
    }

    /**
     * @var string $key
     */
    public function get($key, $default = null)
    {
        $this->ensureArgument($key);
        $data = '';
        $ttl = -1;
        $filename = $this->_getFilename($key);
        $fp = fopen($filename, 'r');
        $line = fgets($fp);
        if ($line !== false) {
            $ttl = (int)$line;
        }
        if ($ttl === 0 && $ttl < time()) {
            fclose($fp);
            return $default;
        }
        while (($line = fgets($fp)) !== false) {
            $data .= $line;
        }
        fclose($fp);
        return unserialize($data);
    }

    /**
     * @var string $key
     * @var mixed $value
     * @var int $ttl
     */
    public function set($key, $value, $ttl = null)
    {
        $this->ensureArgument($key);
        if ($ttl) {
            $ttl = time() + $ttl;
        }
        $value = serialize($value);
        $filename = $this->_getFilename($key);
        return $this->writeFile($filename, $ttl . PHP_EOL . $value);
    }

    private function writeFile( string $filename, string $content ): bool
    {
        $filepath = pathinfo($filename, PATHINFO_DIRNAME);
        if (!$this->_createPathIfNeed($filepath)) {
            return false;
        }
        if (! is_writable($filepath)) {
            return false;
        }

        $tmpFile = tempnam($filepath, 'swap');
        @chmod($tmpFile, $this->config->getMask()  );
        if (file_put_contents($tmpFile, $content) !== false) {
            @chmod($tmpFile, $this->config->getMask() );
            if (@rename($tmpFile, $filename)) {
                return true;
            }
            @unlink($tmpFile);
        }

        return false;
    }

    /**
     * @var string $key
     * @return bool
     */
    public function delete($key): bool
    {
        $this->ensureArgument($key);
        $filename = $this->_getFilename($key);
        return @unlink($filename) || !file_exists($filename);
    }

    /**
     * @return bool
     */
    public function clear(): bool
    {
        $fileIterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $this->config->getPath(),
                FilesystemIterator::SKIP_DOTS
            ),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($fileIterator as $name => $file) {
            if ($file->isDir()) {
                @rmdir($name);
            } elseif (Str::endsWith($name, self::FILE_EXTENSION)) {
                @unlink($name);
            }
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

    /**
     * @var string $key
     * @return bool
     */
    public function has($key): bool
    {
        $ttl = -1;
        $filename = $this->_getFilename($key);
        $fp = fopen($filename, 'r');
        $line = fgets($fp);
        if ($line !== false) {
            $ttl = (int)$line;
        }
        fclose($fp);
        return $ttl === 0 || $ttl > time();
    }

    /**
     * @var string $key
     * @return string
     */
    private function _getFilename(string $key): string
    {
        return $this->config->getPath() . DIRECTORY_SEPARATOR . $key . self::FILE_EXTENSION;
    }
}
