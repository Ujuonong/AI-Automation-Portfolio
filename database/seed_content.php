<?php

declare(strict_types=1);

/**
 * Seeds editable website content blocks from config/content.php.
 * Only fills rows that do not already exist with a value.
 *
 * Usage:
 *   php database/seed_content.php
 */

use Portfolio\Env;

define('BASE_PATH', dirname(__DIR__));

require BASE_PATH . '/app/Env.php';
require_once BASE_PATH . '/config/constants.php';

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

$config = require CONFIG_PATH . '/database.php';

try {
    $dsn = sprintf(
        'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
        $config['host'],
        $config['port'],
        $config['database']
    );
    $pdo = new PDO($dsn, $config['username'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    $entries = require CONFIG_PATH . '/content.php';

    $upsert = $pdo->prepare(
        'INSERT INTO content_blocks (block_key, label, `value`, updated_at)
         VALUES (:key, :label, :value, NOW())
         ON DUPLICATE KEY UPDATE
             label = IF(`value` = \'\' OR `value` IS NULL, VALUES(label), label),
             `value` = IF(`value` = \'\' OR `value` IS NULL, VALUES(`value`), `value`),
             updated_at = NOW()'
    );

    foreach ($entries as $entry) {
        $upsert->execute([
            'key'   => $entry['key'],
            'label' => $entry['label'],
            'value' => $entry['default'],
        ]);
    }

    echo '[Seed] Content blocks ready (' . count($entries) . " keys).\n";
} catch (Throwable $e) {
    fwrite(STDERR, '[Seed] FAILED: ' . $e->getMessage() . "\n");
    exit(1);
}