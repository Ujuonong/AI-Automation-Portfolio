<?php

declare(strict_types=1);

/**
 * Creates the initial administrator account.
 *
 * Usage:
 *   php database/seed_admin.php "Bulus Ujuonong James" admin@dejunong.com your-password
 */

use Portfolio\Env;

define('BASE_PATH', dirname(__DIR__));

require BASE_PATH . '/app/Env.php';
\Portfolio\Env::load(BASE_PATH . '/.env');

spl_autoload_register(static function (string $class): void {
    $prefix = 'Portfolio\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }
    $relative = substr($class, strlen($prefix));
    $file = BASE_PATH . '/app/' . str_replace('\\', '/', $relative) . '.php';
    if (is_file($file)) {
        require_once $file;
    }
});

$config = require BASE_PATH . '/config/database.php';

// Connect
$dsn = sprintf('mysql:host=%s;port=%d;charset=utf8mb4', $config['host'], $config['port']);
$pdo = new PDO($dsn, $config['username'], $config['password'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);
$pdo->exec(sprintf('CREATE DATABASE IF NOT EXISTS `%s` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci', $config['database']));
$pdo->exec(sprintf('USE `%s`', $config['database']));

$name  = $argv[1] ?? 'Bulus Ujuonong James';
$email = $argv[2] ?? 'admin@dejunong.com';
$pass  = $argv[3] ?? 'admin123';

$stmt = $pdo->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
$stmt->execute(['email' => $email]);
if ($stmt->fetch() !== false) {
    echo "[Seed] Admin account already exists ({$email}).\n";
    exit(0);
}

$hash = password_hash($pass, PASSWORD_BCRYPT, ['cost' => 12]);
$pdo->prepare('INSERT INTO users (name, email, password, role, status, created_at, updated_at)
               VALUES (:name, :email, :hash, :role, :status, NOW(), NOW())')
   ->execute([
       'name'   => $name,
       'email'  => $email,
       'hash'   => $hash,
       'role'   => 'admin',
       'status' => 'active',
   ]);

echo "[Seed] Admin account created.\n";
echo "       Email: {$email}\n";
echo "       Password: {$pass}\n";
echo "       >>> CHANGE THE PASSWORD AFTER FIRST LOGIN <<<\n";
echo "[Seed] Done.\n";