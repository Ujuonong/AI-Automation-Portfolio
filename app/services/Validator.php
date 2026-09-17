<?php

declare(strict_types=1);

namespace Portfolio\Services;

/**
 * Validates untrusted user input into typed values or error messages.
 */
final class Validator
{
    private array $errors = [];
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function required(string $field, string $label): self
    {
        $value = $this->data[$field] ?? null;
        if (is_string($value) && trim($value) !== '') {
            return $this;
        }
        if (is_numeric($value) && (float) $value !== 0.0) {
            return $this;
        }
        $this->errors[$field] = "The {$label} field is required.";
        return $this;
    }

    public function email(string $field, string $label): self
    {
        $value = (string) ($this->data[$field] ?? '');
        if ($value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = "The {$label} must be a valid email address.";
        }
        return $this;
    }

    public function url(string $field, string $label): self
    {
        $value = (string) ($this->data[$field] ?? '');
        if ($value !== '' && !filter_var($value, FILTER_VALIDATE_URL)) {
            $this->errors[$field] = "The {$label} must be a valid URL.";
        }
        return $this;
    }

    public function maxLength(string $field, string $label, int $max): self
    {
        $value = (string) ($this->data[$field] ?? '');
        $hasError = $this->errors[$field] ?? null;
        if ($hasError) {
            return $this;
        }
        if (mb_strlen($value) > $max) {
            $this->errors[$field] = "The {$label} must not exceed {$max} characters.";
        }
        return $this;
    }

    public function minLength(string $field, string $label, int $min): self
    {
        $value = (string) ($this->data[$field] ?? '');
        if (mb_strlen($value) < $min) {
            $this->errors[$field] = "The {$label} must be at least {$min} characters.";
        }
        return $this;
    }

    public function in(string $field, string $label, array $allowed): self
    {
        $value = $this->data[$field] ?? null;
        if ($value === null || $value === '') {
            return $this;
        }
        if (!in_array($value, $allowed, true)) {
            $this->errors[$field] = "The {$label} is invalid.";
        }
        return $this;
    }

    public function phone(string $field, string $label): self
    {
        $value = (string) ($this->data[$field] ?? '');
        if ($value !== '' && self::normalizePhone($value) === '') {
            $this->errors[$field] = "The {$label} must be a valid phone or WhatsApp number.";
        }
        return $this;
    }

    public function date(string $field, string $label): self
    {
        $value = (string) ($this->data[$field] ?? '');
        if ($value !== '' && !strtotime($value)) {
            $this->errors[$field] = "The {$label} is not a valid date.";
        }
        return $this;
    }

    public function dateNotPast(string $field, string $label): self
    {
        $value = (string) ($this->data[$field] ?? '');
        if ($value === '') {
            return $this;
        }

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $value) || !strtotime($value)) {
            $this->errors[$field] = "The {$label} is not a valid date.";
            return $this;
        }

        if ($value < date('Y-m-d')) {
            $this->errors[$field] = 'Please select a future date.';
        }

        return $this;
    }

    /**
     * Normalise a phone number to E.164 form, or return empty string if invalid.
     */
    public static function normalizePhone(string $value): string
    {
        $digits = preg_replace('/[\s\-\.\(\)]+/', '', trim($value)) ?? '';

        // Nigerian 11-digit mobile: 0XXXXXXXXX → +234XXXXXXXXXX
        if (preg_match('/^0([7-9][01]\d{8})$/', $digits, $m)) {
            return '+234' . $m[1];
        }

        // International prefix without +: 234XXXXXXXXXX → +234XXXXXXXXXX
        if (preg_match('/^234([7-9][01]\d{8})$/', $digits, $m)) {
            return '+234' . $m[1];
        }

        // Generic international: + or bare number starting with 1-9, 8-15 digits
        if (preg_match('/^\+?[1-9]\d{7,14}$/', $digits)) {
            return '+' . ltrim($digits, '+');
        }

        return '';
    }

    public function passes(): bool
    {
        return count($this->errors) === 0;
    }

    /**
     * Manually attach an error to a field (e.g. upload failures).
     */
    public function addError(string $field, string $message): self
    {
        $this->errors[$field] = $message;
        return $this;
    }

    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * Apply a callback per field for custom rules.
     */
    public function custom(string $field, callable $rule, string $message): self
    {
        if (!$rule($this->data[$field] ?? null)) {
            $this->errors[$field] = $message;
        }
        return $this;
    }
}