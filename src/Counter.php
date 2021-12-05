<?php
declare(strict_types=1);

namespace CuePhp\Cache;

final class Counter
{
    /**
     * @var string
     */
    private $_name = '';

    /**
     * @var int
     */
    private $_val = 0;

    private function __construct(string $name, int $val)
    {
        $this->_name = $name;
        $this->_val = $val;
    }

    /**
     * @param string $key
     * @param int $val
     */
    public static function create(string  $name, int $val): Counter
    {
        return new self($name, $val);
    }

    public function getData(): int
    {
        return $this->_val;
    }

    public function getName(): string
    {
        return $this->_name;
    }
}
