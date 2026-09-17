<?php

declare(strict_types=1);

use Portfolio\Env;

return [
    'driver'   => 'mysql',
    'host'     => (string) Env::get('DB_HOST', 'localhost'),
    'port'     => Env::int('DB_PORT', 3306),
    'database' => (string) Env::get('DB_DATABASE', 'portfolio_cms'),
    'username' => (string) Env::get('DB_USERNAME', 'root'),
    'password' => (string) Env::get('DB_PASSWORD', ''),
    'charset'  => 'utf8mb4',
    'collation'=> 'utf8mb4_unicode_ci',
    'options'  => [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::ATTR_STRINGIFY_FETCHES  => false,
    ],
];