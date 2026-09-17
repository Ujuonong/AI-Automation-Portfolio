<?php

declare(strict_types=1);

namespace Portfolio\Middleware;

use Portfolio\Core\Session;

/**
 * Redirects already-authenticated users away from public-only routes.
 */
final class Guest
{
    public function handle(): bool
    {
        if (Session::isAuthenticated()) {
            redirect('/admin');
        }
        return true;
    }
}