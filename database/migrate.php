<?php

declare(strict_types=1);

/**
 * Database migrator.
 *
 * Usage:
 *   php database/migrate.php
 *
 * Creates the configured database if missing, then applies every
 * migration file in database/migrations in filename order.
 */

use Portfolio\Env;

define('BASE_PATH', dirname(__DIR__));

require BASE_PATH . '/app/Env.php';
require_once BASE_PATH . '/config/constants.php';

\Portfolio\Env::load(BASE_PATH . '/.env');

$config = require CONFIG_PATH . '/database.php';

function migration_connect(array $config, bool $createDb): PDO
{
    $dsn = sprintf(
        'mysql:host=%s;port=%d;charset=utf8mb4',
        $config['host'],
        $config['port']
    );
    $pdo = new PDO($dsn, $config['username'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    if ($createDb) {
        $database = $config['database'];
        $pdo->exec(sprintf(
            'CREATE DATABASE IF NOT EXISTS `%s` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci',
            $database
        ));
        echo "[Migrate] Database `{$database}` ready.\n";
    }

    $pdo->exec(sprintf('USE `%s`', $config['database']));
    return $pdo;
}

try {
    $pdo = migration_connect($config, true);

    // Migration log
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS schema_migrations (
             id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
             migration VARCHAR(255) NOT NULL UNIQUE,
             applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
         ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
    );

    $applied = $pdo->query('SELECT migration FROM schema_migrations')->fetchAll(PDO::FETCH_COLUMN);

    $migrationDir = __DIR__ . '/migrations';
    $files = glob($migrationDir . '/*.sql') ?: [];
    sort($files);

    foreach ($files as $file) {
        $name = basename($file);
        if (in_array($name, $applied, true)) {
            echo "[Migrate] Skipping {$name} (already applied)\n";
            continue;
        }

        $sql = (string) file_get_contents($file);
        $pdo->exec($sql);

        $stmt = $pdo->prepare('INSERT INTO schema_migrations (migration) VALUES (:migration)');
        $stmt->execute(['migration' => $name]);

        echo "[Migrate] Applied {$name}\n";
    }

    echo "[Migrate] All migrations are up to date.\n";
} catch (Throwable $e) {
    fwrite(STDERR, '[Migrate] FAILED: ' . $e->getMessage() . "\n");
    exit(1);
}