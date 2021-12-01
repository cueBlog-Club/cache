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
    public $_path;

    /**
     * file ops
     * @var int
     */
    public $_mask = 0664;
}
