<?php
declare(strict_types=1);

namespace CuePhp\Cache\Config;

final class FileEngineConfig extends EngineConfig
{
    /**
     * @var bool
     */
    public $_lock = true;

    /**
     * file path
     * @var string
     */
    private $_path;

    /**
     * file ops
     * @var int
     */
    private $_mask = 0664;

    public function __construct( string $path )
    {
        $this->_path = $path;
    }

    public function setMask( int $mask )
    {
        $this->_mask = $mask;
        return  $this;
    }

    public function getPath(): string
    {
        return $this->_path;
    }

    public function getMask(): int
    {
        return $this->_mask;
    }
}
