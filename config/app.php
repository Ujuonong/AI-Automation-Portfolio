<?php

declare(strict_types=1);

use Portfolio\Env;

return [
    'name'     => APP_NAME,
    'env'      => APP_ENV,
    'url'      => APP_URL,
    'debug'    => APP_DEBUG,
    'timezone' => APP_TIMEZONE,

    'session' => [
        'name'     => SESSION_NAME,
        'lifetime' => SESSION_LIFETIME,
        'secure'   => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
        'httponly' => true,
        'samesite' => 'Lax',
    ],

    'upload' => [
        'max_size'      => MAX_FILE_SIZE,
        'image_exts'    => explode(',', ALLOWED_IMAGE_EXTENSIONS),
        'document_exts' => explode(',', ALLOWED_DOCUMENT_EXTENSIONS),
    ],
];