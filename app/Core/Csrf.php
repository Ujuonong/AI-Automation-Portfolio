<?php

declare(strict_types=1);

namespace Portfolio\Core;

/**
 * CSRF token generation and verification.
 */
final class Csrf
{
    public static function token(): string
    {
        if (!Session::has('_csrf_token')) {
            Session::set('_csrf_token', bin2hex(random_bytes(32)));
        }
        return (string) Session::get('_csrf_token');
    }

    public static function field(): string
    {
        return '<input type="hidden" name="csrf_token" value="' . e(self::token()) . '">';
    }

    public static function verify(?string $token): bool
    {
        $expected = Session::get('_csrf_token');
        if ($expected === null || !is_string($token) || $token === '') {
            return false;
        }
        return hash_equals($expected, $token);
    }

    public static function rotate(): void
    {
        Session::set('_csrf_token', bin2hex(random_bytes(32)));
    }
}