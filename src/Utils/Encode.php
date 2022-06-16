<?php

declare(strict_types=1);

namespace PeibinLaravel\Nacos\Utils;

class Encode
{
    public static function twoEncode(): string
    {
        return pack("C*", 2);
    }

    public static function oneEncode(): string
    {
        return pack("C*", 1);
    }
}
