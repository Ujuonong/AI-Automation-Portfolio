<?php

declare(strict_types=1);

namespace Portfolio\Core;

/**
 * Immutable-ish wrapper around the incoming HTTP request.
 */
final class Request
{
    private array $query;
    private array $body;

    public function __construct(array $query, array $body, array $files)
    {
        $this->query = $query;
        $this->body  = $body;
        $this->files = $files;
    }

    public static function capture(): self
    {
        return new self($_GET, $_POST, $_FILES);
    }

    public function method(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    public function isPost(): bool
    {
        return $this->method() === 'POST';
    }

    public function isGet(): bool
    {
        return $this->method() === 'GET';
    }

    public function path(): string
    {
        $uri = preg_replace('/\?.*$/', '', $_SERVER['REQUEST_URI'] ?? '/');
        $uri = rawurldecode((string) $uri);
        $base = $_SERVER['BASE_PATH'] ?? '';
        if ($base !== '' && str_starts_with($uri, $base)) {
            $uri = substr($uri, strlen($base)) ?: '/';
        }
        return $uri === '' ? '/' : $uri;
    }

    /**
     * Retrieve a value from GET/POST merged.
     */
    public function input(string $key, mixed $default = null): mixed
    {
        if (array_key_exists($key, $this->body)) {
            return $this->body[$key];
        }
        if (array_key_exists($key, $this->query)) {
            return $this->query[$key];
        }
        return $default;
    }

    /**
     * All POST body values.
     */
    public function all(): array
    {
        return $this->body;
    }

    public function query(string $key, mixed $default = null): mixed
    {
        return $this->query[$key] ?? $default;
    }

    /**
     * A single uploaded file descriptor or null.
     */
    public function file(string $key): ?array
    {
        $file = $this->files[$key] ?? null;
        if (is_array($file) && isset($file['name']) && $file['error'] !== UPLOAD_ERR_NO_FILE) {
            return $file;
        }
        return null;
    }

    public function ip(): string
    {
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    public function userAgent(): string
    {
        return substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255);
    }

    public function referer(): string
    {
        return $_SERVER['HTTP_REFERER'] ?? '';
    }

    public function isAjax(): bool
    {
        return strtoupper($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHTTPREQUEST';
    }

    /**
     * @return array<int, array{name: string, type: string, tmp_name: string, error: int, size: int}>
     */
    private array $files = [];
}