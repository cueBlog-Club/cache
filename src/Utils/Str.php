<?php

declare(strict_types=1);

namespace CuePhp\Cache\Utils;

class Str
{
     /**
      *
      */
    public static function endsWith(string $str, string $posfix): bool
    {
        $strpos = strpos($str, $posfix);
        if( $strpos === false ) {
            return false;
        }
        return substr($str, $strpos) === $posfix;
    }
}
