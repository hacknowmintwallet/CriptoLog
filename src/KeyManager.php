<?php

declare(strict_types=1);

namespace HackNOW\CriptLog;

class KeyManager
{
    private const DEFAULT_CHARSET = ' !"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\\]^_`abcdefghijklmnopqrstuvwxyz{|}~';
    
    public function generateKey(
        string $masterPassphrase,
        ?string $charset = null,
        ?array $map = null,
        array $options = []
    ): string {
        $charset = $charset ?? self::DEFAULT_CHARSET;
        $map = $map ?? $this->generateRandomMap($charset, $options['seed'] ?? null);
        
        $keyData = [
            'charset' => $charset,
            'map' => $map,
            'created_at' => date('c'),
            'options' => $options
        ];
        
        $jsonData = json_encode($keyData, JSON_THROW_ON_ERROR);
        $nonce = random_bytes(SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_NPUBBYTES);
        
        $key = $this->deriveKey($masterPassphrase, $nonce);
        $encrypted = sodium_crypto_aead_xchacha20poly1305_ietf_encrypt(
            $jsonData,
            '',
            $nonce,
            $key
        );
        
        $result = [
            'nonce' => base64_encode($nonce),
            'data' => base64_encode($encrypted),
            'key_id' => bin2hex(random_bytes(16))
        ];
        
        return json_encode($result, JSON_THROW_ON_ERROR);
    }
    
    public function loadKey(string $keyFile, string $masterPassphrase): array
    {
        if (!file_exists($keyFile)) {
            throw new \RuntimeException("Key file not found: $keyFile");
        }
        
        $keyData = json_decode(file_get_contents($keyFile), true, 512, JSON_THROW_ON_ERROR);
        $nonce = base64_decode($keyData['nonce']);
        $encrypted = base64_decode($keyData['data']);
        
        $key = $this->deriveKey($masterPassphrase, $nonce);
        $decrypted = sodium_crypto_aead_xchacha20poly1305_ietf_decrypt(
            $encrypted,
            '',
            $nonce,
            $key
        );
        
        if ($decrypted === false) {
            throw new \RuntimeException('Failed to decrypt key file');
        }
        
        return json_decode($decrypted, true, 512, JSON_THROW_ON_ERROR);
    }
    
    public function generateRandomMap(string $charset, ?string $seed = null): array
    {
        $map = [];
        $length = strlen($charset);
        
        if ($seed !== null) {
            srand(crc32($seed));
        }
        
        for ($i = 0; $i < $length; $i++) {
            $char = $charset[$i];
            $map[$char] = $seed !== null ? rand(-100, 100) : random_int(-100, 100);
        }
        
        return $map;
    }
    
    private function deriveKey(string $passphrase, string $salt): string
    {
        return sodium_crypto_pwhash(
            32,
            $passphrase,
            $salt,
            SODIUM_CRYPTO_PWHASH_OPSLIMIT_MODERATE,
            SODIUM_CRYPTO_PWHASH_MEMLIMIT_MODERATE
        );
    }
}