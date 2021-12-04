<?php

declare(strict_types=1);

namespace CuePhp\Cache\Utils;

class Str
{
    public static function split(string $name, bool $dotAppend = false, ?string $plugin = null)
    {
        if (strpos($name, '.') !== false) {
            $parts = explode('.', $name, 2);
            if ($dotAppend) {
                $parts[0] .= '.';
            }

            /** @psalm-var array{string, string}*/
            return $parts;
        }

        return [$plugin, $name];
    }

     /**
      * 
      */
    public static function endsWith(string $str, string $posfix): bool
    {
        return substr( $str, strpos($str, $posfix) ) === $posfix; 
    }
}
