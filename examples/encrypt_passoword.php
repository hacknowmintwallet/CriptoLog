<?php

require_once __DIR__ . '/../vendor/autoload.php';

use HackNOW\CriptLog\KeyManager;
use HackNOW\CriptLog\PasswordManager;

// Load settings
$settings = require __DIR__ . '/settings.php';

// Initialize managers
$keyManager = new KeyManager();
$passwordManager = new PasswordManager(
    $keyManager,
    $settings['key_file'],
    $settings['master_passphrase']
);

// Example usage
$password = 'my_secure_password_123';
$encrypted = $passwordManager->encryptPassword($password);

echo "Original: $password\n";
echo "Encrypted: $encrypted\n";
echo "Verified: " . ($passwordManager->verifyPassword($password, $encrypted) ? 'Yes' : 'No') . "\n";