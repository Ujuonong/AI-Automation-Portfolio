<?php

declare(strict_types=1);

namespace Portfolio\Middleware;

use Portfolio\Core\Csrf;
use Portfolio\Exceptions\HttpException;

/**
 * Validates the CSRF token on every state-changing POST request.
 */
final class CsrfToken
{
    public function handle(): bool
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $token = $_POST['csrf_token'] ?? '';
            if (!Csrf::verify(is_string($token) ? $token : '')) {
                throw new HttpException(419, 'Your session token has expired. Please go back, refresh the page and try again.');
            }
        }
        return true;
    }
}