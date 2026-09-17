<?php

declare(strict_types=1);

namespace Portfolio\Core;

/**
 * HTTP response builder.
 */
final class Response
{
    private int $status = 200;
    private array $headers = [];

    public function status(int $code): self
    {
        $this->status = $code;
        return $this;
    }

    public function header(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function json(mixed $data, int $status = 200): never
    {
        $this->status($status);
        $this->header('Content-Type', 'application/json; charset=utf-8');
        $this->sendHeaders();
        echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function text(string $content, int $status = 200): never
    {
        $this->status($status);
        $this->header('Content-Type', 'text/plain; charset=utf-8');
        $this->sendHeaders();
        echo $content;
        exit;
    }

    public function notFound(): never
    {
        $this->status(404);
        $this->sendHeaders();
        exit;
    }

    private function sendHeaders(): void
    {
        http_response_code($this->status);
        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }
    }
}