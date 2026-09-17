<?php

declare(strict_types=1);

namespace Portfolio\Core;

use PDO;
use Throwable;

/**
 * Thin PDO wrapper exposing a shared connection.
 */
final class Database
{
    private static ?PDO $instance = null;

    private function __construct()
    {
    }

    public static function connection(): PDO
    {
        if (self::$instance === null) {
            $config = require CONFIG_PATH . '/database.php';

            $dsn = sprintf(
                'mysql:host=%s;port=%d;dbname=%s;charset=%s',
                $config['host'],
                $config['port'],
                $config['database'],
                $config['charset']
            );

            try {
                self::$instance = new PDO($dsn, $config['username'], $config['password'], $config['options']);
            } catch (Throwable $e) {
                error_log('[Database] Connection failed: ' . $e->getMessage());
                throw new \RuntimeException('Database connection failed. Check configuration and try again.');
            }
        }

        return self::$instance;
    }

    public static function disconnect(): void
    {
        self::$instance = null;
    }
}