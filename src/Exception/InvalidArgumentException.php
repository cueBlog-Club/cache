<?php
declare(strict_types=1);

namespace CuePhp\Cache\Exception;

use Psr\SimpleCache\InvalidArgumentException as InvalidArgumentInterface;

/**
 * Exception interface for invalid cache arguments.
 *
 * When an invalid argument is passed it must throw an exception which implements
 * this interface
 */
class InvalidArgumentException extends \InvalidArgumentException implements InvalidArgumentInterface
{
}
