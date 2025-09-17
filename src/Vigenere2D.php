<?php

declare(strict_types=1);

namespace HackNOW\CriptLog;

class Vigenere2D
{
    private string $charset;
    private array $charMap;
    private int $charsetLength;
    private bool $bidimensional;
    private string $mode;
    private bool $allowNegative;

    public function __construct(
        string $charset,
        array $charMap,
        bool $bidimensional = true,
        string $mode = 'modular',
        bool $allowNegative = false
    ) {
        $this->charset = $this->padCharset($charset);
        $this->charMap = $charMap;
        $this->charsetLength = strlen($this->charset);
        $this->bidimensional = $bidimensional;
        $this->mode = $mode;
        $this->allowNegative = $allowNegative;
    }

    public function encrypt(string $plaintext, string $key): string
    {
        return $this->process($plaintext, $key, true);
    }

    public function decrypt(string $ciphertext, string $key): string
    {
        return $this->process($ciphertext, $key, false);
    }

    private function process(string $input, string $key, bool $encrypt): string
    {
        $output = '';
        $keyLength = strlen($key);

        for ($i = 0; $i < strlen($input); $i++) {
            $char = $input[$i];
            $keyChar = $key[$i % $keyLength];

            // ✅ controllo prima se il carattere è valido
            if (strpos($this->charset, $char) === false) {
                throw new \InvalidArgumentException("Unsupported character: $char");
            }

            $charPos = strpos($this->charset, $char);
            $shift = $this->charMap[$keyChar] ?? 0;

            if (!$encrypt) {
                $shift = -$shift;
            }

            if ($this->bidimensional) {
                $newPos = $this->bidimensionalShift($charPos, $shift);
            } else {
                $newPos = $this->unidimensionalShift($charPos, $shift);
            }

            $output .= $this->charset[$newPos];
        }

        return $output;
    }

    private function unidimensionalShift(int $position, int $shift): int
    {
        $newPosition = $position + $shift;

        if ($this->mode === 'modular') {
            $newPosition = $newPosition % $this->charsetLength;
            if ($newPosition < 0) {
                $newPosition += $this->charsetLength;
            }
        } else { // ✅ saturate mode
            if ($newPosition < 0) {
                $newPosition = $this->allowNegative ? $newPosition : 0;
            } elseif ($newPosition >= $this->charsetLength) {
                $newPosition = $this->charsetLength - 1;
            }
        }

        return $newPosition;
    }

    private function bidimensionalShift(int $position, int $shift): int
    {
        $rowSize = (int) sqrt($this->charsetLength);

        if ($rowSize * $rowSize !== $this->charsetLength) {
            throw new \RuntimeException("Charset length must be a perfect square for bidimensional mode");
        }

        $row = (int) ($position / $rowSize);
        $col = $position % $rowSize;

        $rowShift = (int) ($shift / $rowSize);
        $colShift = $shift % $rowSize;

        $newRow = $row + $rowShift;
        $newCol = $col + $colShift;

        if ($this->mode === 'modular') {
            $newRow = $newRow % $rowSize;
            $newCol = $newCol % $rowSize;

            if ($newRow < 0) $newRow += $rowSize;
            if ($newCol < 0) $newCol += $rowSize;
        } else { // ✅ saturate mode
            if ($newRow < 0) $newRow = 0;
            if ($newRow >= $rowSize) $newRow = $rowSize - 1;
            if ($newCol < 0) $newCol = 0;
            if ($newCol >= $rowSize) $newCol = $rowSize - 1;
        }

        return $newRow * $rowSize + $newCol;
    }

    /**
     * ✅ Aggiunge padding automatico al charset per renderlo quadrato perfetto
     */
    private function padCharset(string $charset): string
    {
        $length = strlen($charset);
        $nextSquare = (int) ceil(sqrt($length)) ** 2;

        if ($length === $nextSquare) {
            return $charset;
        }

        $needed = $nextSquare - $length;
        $extraChars = '';

        for ($i = 0; $i < $needed; $i++) {
            $extraChars .= chr(33 + $i); // simboli a partire da "!"
        }

        return $charset . $extraChars;
    }
}
