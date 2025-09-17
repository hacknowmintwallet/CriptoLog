<?php

declare(strict_types=1);

namespace HackNOW\CriptLog\Tests;

use HackNOW\CriptLog\Vigenere2D;
use PHPUnit\Framework\TestCase;

class Vigenere2DTest extends TestCase
{
    private string $charset;
    private array $charMap;

    protected function setUp(): void
    {
        $this->charset = 'abcdefghijklmnopqrstuvwxyz';
        $this->charMap = [
            'a' => 3, 'b' => 1, 'c' => 4, 'd' => 1, 'e' => 5,
            'f' => 9, 'g' => 2, 'h' => 6, 'i' => 5, 'j' => 3,
            'k' => 5, 'l' => 8, 'm' => 9, 'n' => 7, 'o' => 9,
            'p' => 3, 'q' => 2, 'r' => 3, 's' => 8, 't' => 4,
            'u' => 6, 'v' => 2, 'w' => 6, 'x' => 4, 'y' => 3,
            'z' => 8
        ];
    }

    public function testEncryptDecrypt(): void
    {
        $vigenere = new Vigenere2D($this->charset, $this->charMap);
        $plaintext = 'hello';
        $key = 'abc';

        $encrypted = $vigenere->encrypt($plaintext, $key);
        $decrypted = $vigenere->decrypt($encrypted, $key);

        $this->assertSame($plaintext, $decrypted);
    }

    public function testUnsupportedCharacter(): void
    {
        $vigenere = new Vigenere2D($this->charset, $this->charMap);

        $this->expectException(\InvalidArgumentException::class);
        $vigenere->encrypt('hello€', 'abc');
    }

    public function testBidimensionalMode(): void
    {
        $vigenere = new Vigenere2D($this->charset, $this->charMap, true, 'modular');
        $plaintext = 'test';
        $key = 'key';

        $encrypted = $vigenere->encrypt($plaintext, $key);
        $decrypted = $vigenere->decrypt($encrypted, $key);

        $this->assertSame($plaintext, $decrypted);
    }

    public function testSaturateMode(): void
    {
        $vigenere = new Vigenere2D($this->charset, $this->charMap, false, 'saturate');
        $plaintext = 'zoo';
        $key = 'abc';

        $encrypted = $vigenere->encrypt($plaintext, $key);
        $decrypted = $vigenere->decrypt($encrypted, $key);

        $this->assertSame($plaintext, $decrypted);
    }
}
