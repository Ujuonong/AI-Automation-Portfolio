<?php

declare(strict_types=1);

namespace Portfolio\Middleware;

use Portfolio\Core\Session;

/**
 * Blocks unauthenticated users from protected routes.
 */
final class Auth
{
    public function handle(): bool
    {
        if (!Session::isAuthenticated()) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'] ?? '/admin';
            redirect('/admin/login');
        }
        return true;
    }
}