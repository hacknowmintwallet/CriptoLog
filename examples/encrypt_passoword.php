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
