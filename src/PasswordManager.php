<?php

declare(strict_types=1);

namespace HackNOW\CriptLog;

class PasswordManager
{
    private KeyManager $keyManager;
    private string $keyFile;
    private string $masterPassphrase;
    
    public function __construct(KeyManager $keyManager, string $keyFile, string $masterPassphrase)
    {
        $this->keyManager = $keyManager;
        $this->keyFile = $keyFile;
        $this->masterPassphrase = $masterPassphrase;
    }
    
    public function encryptPassword(string $plainPassword): string
    {
        $keyData = $this->keyManager->loadKey($this->keyFile, $this->masterPassphrase);
        $vigenere = new Vigenere2D($keyData['charset'], $keyData['map']);
        
        $salt = random_bytes(16);
        $nonce = random_bytes(SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_NPUBBYTES);
        
        $key = $this->deriveKey($this->masterPassphrase, $salt);
        $ciphertext = $vigenere->encrypt($plainPassword, $this->masterPassphrase);
        
        $encrypted = sodium_crypto_aead_xchacha20poly1305_ietf_encrypt(
            $ciphertext,
            '',
            $nonce,
            $key
        );
        
        $payload = [
            'salt' => base64_encode($salt),
            'nonce' => base64_encode($nonce),
            'ciphertext' => base64_encode($encrypted)
        ];
        
        $payloadJson = json_encode($payload, JSON_THROW_ON_ERROR);
        $keyId = hash_file('sha256', $this->keyFile);
        
        return "v2|$keyId|" . base64_encode($payloadJson);
    }
    
    public function verifyPassword(string $plainPassword, string $storedPassword): bool
    {
        $parts = explode('|', $storedPassword, 3);
        
        if (count($parts) !== 3 || $parts[0] !== 'v2') {
            return false;
        }
        
        $keyId = $parts[1];
        $payload = base64_decode($parts[2]);
        $payloadData = json_decode($payload, true, 512, JSON_THROW_ON_ERROR);
        
        $salt = base64_decode($payloadData['salt']);
        $nonce = base64_decode($payloadData['nonce']);
        $ciphertext = base64_decode($payloadData['ciphertext']);
        
        $key = $this->deriveKey($this->masterPassphrase, $salt);
        $decrypted = sodium_crypto_aead_xchacha20poly1305_ietf_decrypt(
            $ciphertext,
            '',
            $nonce,
            $key
        );
        
        if ($decrypted === false) {
            return false;
        }
        
        $keyData = $this->keyManager->loadKey($this->keyFile, $this->masterPassphrase);
        $vigenere = new Vigenere2D($keyData['charset'], $keyData['map']);
        $decryptedPassword = $vigenere->decrypt($decrypted, $this->masterPassphrase);
        
        return hash_equals($plainPassword, $decryptedPassword);
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