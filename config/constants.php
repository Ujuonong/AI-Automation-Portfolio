<?php

declare(strict_types=1);

use Portfolio\Env;

// Global path constants
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}
define('APP_PATH', BASE_PATH . '/app');
define('CONFIG_PATH', BASE_PATH . '/config');
define('ROUTES_PATH', BASE_PATH . '/routes');
define('STORAGE_PATH', BASE_PATH . '/storage');
define('UPLOADS_PATH', BASE_PATH . '/storage/uploads');
define('PUBLIC_UPLOADS_PATH', BASE_PATH . '/public/uploads');
define('PUBLIC_PATH', BASE_PATH . '/public');

// App constants
define('APP_NAME', (string) Env::get('APP_NAME', 'Bulus Ujuonong James'));
define('APP_ENV', (string) Env::get('APP_ENV', 'production'));
define('APP_URL', rtrim((string) Env::get('APP_URL', 'http://localhost'), '/'));
define('APP_DEBUG', Env::bool('APP_DEBUG', false));
define('APP_TIMEZONE', (string) Env::get('APP_TIMEZONE', 'UTC'));

// Session constants
define('SESSION_NAME', (string) Env::get('SESSION_NAME', 'portfolio_session'));
define('SESSION_LIFETIME', Env::int('SESSION_LIFETIME', 7200));

// Security constants
define('CSRF_KEY', (string) Env::get('CSRF_KEY', 'change_me'));
define('BCRYPT_COST', Env::int('BCRYPT_COST', 12));
define('LOGIN_MAX_ATTEMPTS', Env::int('LOGIN_MAX_ATTEMPTS', 5));
define('LOGIN_LOCKOUT_MINUTES', Env::int('LOGIN_LOCKOUT_MINUTES', 15));

// Upload constants
define('MAX_FILE_SIZE', Env::int('MAX_FILE_SIZE', 8 * 1024 * 1024));
define('ALLOWED_IMAGE_EXTENSIONS', (string) Env::get('ALLOWED_IMAGE_EXTENSIONS', 'jpg,jpeg,png,webp,gif'));
define('ALLOWED_DOCUMENT_EXTENSIONS', (string) Env::get('ALLOWED_DOCUMENT_EXTENSIONS', 'pdf'));

