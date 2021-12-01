<?php
declare(strict_types=1);

namespace CuePhp\Cache\Config;

final class MemcacheEngineConfig extends EngineConfig
{
    /**
     * @var string
     */
    public $_host = null;

    /**
     *
     * @var string
     */
    public $_username = null;

    /**
     * @var string
     */
    public $_password = "";

    /**
     * @var int
     */
    public $_port = 3306;
}
