<?php

declare(strict_types=1);

namespace HackNOW\CriptLog;

class CryptoHelper
{
    public static function secureZeroString(string $string): void
    {
        sodium_memzero($string);
    }
    
    public static function bin2hex(string $binary): string
    {
        return sodium_bin2hex($binary);
    }
    
    public static function hex2bin(string $hex): string
    {
        return sodium_hex2bin($hex);
    }
}