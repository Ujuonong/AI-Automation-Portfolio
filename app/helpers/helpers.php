<?php

declare(strict_types=1);

/**
 * Global helper functions. Loaded automatically by the bootstrap.
 */

if (!function_exists('e')) {
    /**
     * Escape output for safe HTML injection.
     */
    function e(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('http_status')) {
    /**
     * Set the HTTP response status code, sending a literal status line so
     * that non-standard codes such as 419 survive Apache/mod_php (which
     * would otherwise rewrite unknown codes to 500).
     */
    function http_status(int $code, string $reason = ''): void
    {
        $phrases = [
            200 => 'OK',
            201 => 'Created',
            301 => 'Moved Permanently',
            302 => 'Found',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            419 => 'Page Expired',
            422 => 'Unprocessable Entity',
            429 => 'Too Many Requests',
            500 => 'Internal Server Error',
        ];
        if ($reason === '') {
            $reason = $phrases[$code] ?? 'Unknown';
        }
        $protocol = $_SERVER['SERVER_PROTOCOL'] ?? 'HTTP/1.1';
        header($protocol . ' ' . $code . ' ' . $reason, true, $code);
        http_response_code($code);
    }
}

if (!function_exists('url')) {
    /**
     * Build a fully-qualified application URL.
     */
    function url(string $path = ''): string
    {
        return APP_URL . '/' . ltrim($path, '/');
    }
}

if (!function_exists('asset')) {
    /**
     * URL for a public asset.
     */
    function asset(string $path): string
    {
        return APP_URL . '/assets/' . ltrim($path, '/');
    }
}

if (!function_exists('upload_url')) {
    /**
     * URL for an uploaded file relative to public/uploads.
     */
    function upload_url(string $path = ''): string
    {
        return APP_URL . '/uploads/' . ltrim($path, '/');
    }
}

if (!function_exists('redirect')) {
    /**
     * Redirect helper.
     */
    function redirect(string $path): never
    {
        header('Location: ' . url($path));
        exit;
    }
}

if (!function_exists('redirect_to_url')) {
    /**
     * Redirect to a raw URL or path without prefixing APP_URL.
     */
    function redirect_to_url(string $url): never
    {
        header('Location: ' . $url);
        exit;
    }
}

if (!function_exists('slugify')) {
    /**
     * Convert arbitrary text into a URL-safe slug.
     */
    function slugify(string $text): string
    {
        $text = mb_strtolower(trim($text), 'UTF-8');

        // Replace non-breaking spaces and common separators
        $text = str_replace(['—', '–', '+', '&', ',', '.', '_'], '-', $text);
        $text = preg_replace('/[^a-z0-9\-\s]+/u', '', $text) ?? '';
        $text = preg_replace('/[\s-]+/', '-', $text) ?? '';

        return trim($text, '-');
    }
}

if (!function_exists('format_date')) {
    /**
     * Human friendly date formatting.
     */
    function format_date(?string $date, string $format = 'M j, Y'): string
    {
        if (!$date) {
            return 'Present';
        }
        try {
            return (new DateTime($date))->format($format);
        } catch (Exception) {
            return 'Present';
        }
    }
}

if (!function_exists('truncate')) {
    /**
     * Truncate a string to a given length without breaking words.
     */
    function truncate(string $text, int $length = 120, string $suffix = '…'): string
    {
        if (mb_strlen($text) <= $length) {
            return $text;
        }
        $trimmed = mb_substr(rtrim($text), 0, $length);
        $pos = mb_strrpos($trimmed, ' ');
        if ($pos !== false) {
            $trimmed = mb_substr($trimmed, 0, $pos);
        }
        return $trimmed . $suffix;
    }
}

if (!function_exists('old')) {
    /**
     * Retrieve a previously submitted form value (for validation re-render).
     */
    function old(string $key, mixed $default = ''): mixed
    {
        return $_SESSION['old_input'][$key] ?? $default;
    }
}

if (!function_exists('content')) {
    /**
     * Resolve an editable content block value (falls back to the registry default).
     */
    function content(string $key): string
    {
        return \Portfolio\Services\SiteService::block($key);
    }
}

if (!function_exists('content_lines')) {
    /**
     * Resolve a content block split into trimmed, non-empty lines.
     *
     * @return array<int, string>
     */
    function content_lines(string $key): array
    {
        return \Portfolio\Services\SiteService::blockLines($key);
    }
}

if (!function_exists('flash')) {
    /**
     * Flash a message into the session for the next request.
     */
    function flash(string $type, string $message): void
    {
        $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
    }
}

if (!function_exists('get_flash')) {
    /**
     * Consume queued flash messages.
     *
     * @return array<int, array{type: string, message: string}>
     */
    function get_flash(): array
    {
        $messages = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);
        return $messages;
    }
}

if (!function_exists('config')) {
    /**
     * Retrieve a value from the loaded application config.
     */
    function config(string $key, mixed $default = null): mixed
    {
        static $config = null;
        if ($config === null) {
            $config = require CONFIG_PATH . '/app.php';
        }

        $segments = explode('.', $key);
        $current  = $config;
        foreach ($segments as $segment) {
            if (!is_array($current) || !array_key_exists($segment, $current)) {
                return $default;
            }
            $current = $current[$segment];
        }
        return $current;
    }
}

if (!function_exists('asset_or_default')) {
    /**
     * Return either an uploaded asset URL or a fallback path.
     */
    function asset_or_default(?string $path, string $fallback = 'images/placeholder.svg'): string
    {
        if ($path) {
            return upload_url($path);
        }
        return asset($fallback);
    }
}

if (!function_exists('qs_params')) {
    /**
     * Build a "&key=value" query-string suffix from non-empty params.
     */
    function qs_params(array $params): string
    {
        $pairs = [];
        foreach ($params as $key => $value) {
            if ($value !== '' && $value !== null && $value !== 0 && (string) $value !== '0') {
                $pairs[] = $key . '=' . urlencode((string) $value);
            }
        }
        return $pairs ? '&' . implode('&', $pairs) : '';
    }
}