<?php

declare(strict_types=1);

namespace Portfolio\Exceptions;

use RuntimeException;

/**
 * Thrown when a route wants to abort with a specific HTTP status.
 */
final class HttpException extends RuntimeException
{
    public int $status;

    public function __construct(int $status, string $message = '')
    {
        parent::__construct($message !== '' ? $message : 'HTTP ' . $status);
        $this->status = $status;
    }
}