<?php

/**
 * Copyright 2025 hacknow.blog
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

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
