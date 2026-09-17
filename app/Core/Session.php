<?php

declare(strict_types=1);

namespace Portfolio\Core;

/**
 * Secure session management.
 */
final class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        $config = config('session');

        session_name((string) $config['name']);
        session_set_cookie_params([
            'lifetime' => (int) $config['lifetime'],
            'path'     => '/',
            'httponly' => (bool) $config['httponly'],
            'secure'   => (bool) $config['secure'],
            'samesite' => (string) $config['samesite'],
        ]);

        session_start();

        // Prevent session fixation: rotate id periodically
        if (!isset($_SESSION['_started'])) {
            session_regenerate_id(true);
            $_SESSION['_started'] = true;
        }
    }

    public static function regenerate(): void
    {
        session_regenerate_id(true);
    }

    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function has(string $key): bool
    {
        return array_key_exists($key, $_SESSION);
    }

    public static function forget(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public static function flush(string $key, mixed $default = null): mixed
    {
        $value = self::get($key, $default);
        self::forget($key);
        return $value;
    }

    public static function rememberOld(array $input): void
    {
        $_SESSION['old_input'] = $input;
    }

    public static function clearErrors(): void
    {
        unset($_SESSION['errors']);
    }

    public static function isAuthenticated(): bool
    {
        return isset($_SESSION['user_id'])
            && $_SESSION['user_id'] > 0
            && !empty($_SESSION['auth_role']['admin']);
    }

    public static function userId(): ?int
    {
        $id = $_SESSION['user_id'] ?? null;
        return $id !== null ? (int) $id : null;
    }

    public static function destroy(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
    }
}