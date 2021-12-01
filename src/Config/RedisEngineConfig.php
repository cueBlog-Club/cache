<?php
declare(strict_types=1);

namespace CuePhp\Cache\Config;

final class RedisEngineConfig extends EngineConfig
{
    /**
     * @var string
     */
    public $_host = true;

    /**
     * @var int
     */
    public $_port = 3316;

    /**
     * @var string
     */
    public $_password = null;

    /**
     * @var int
     */
    public $_database = 0;
}
