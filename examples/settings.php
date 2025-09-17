<?php

return [
    'key_file' => __DIR__ . '/keyfile.json',
    'master_passphrase' => getenv('MASTER_PASSPHRASE') ?: 'default_secret_passphrase',
    'charset' => ' !"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\\]^_`abcdefghijklmnopqrstuvwxyz{|}~'
];