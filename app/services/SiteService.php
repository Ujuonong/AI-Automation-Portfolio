<?php

declare(strict_types=1);

namespace Portfolio\Services;

use Portfolio\Models\ContentBlock;
use Portfolio\Models\SiteSetting;

/**
 * Provides site-wide content pulled from site_settings and content_blocks,
 * cached per request.
 */
final class SiteService
{
    private static ?array $settings = null;
    private static ?array $blocks = null;
    private static ?array $blockRegistry = null;

    public static function settings(): array
    {
        if (self::$settings === null) {
            self::$settings = (new SiteSetting())->allAsArray();
        }
        return self::$settings;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $settings = self::settings();
        $value = $settings[$key] ?? $default;
        if ($value === '' || $value === null) {
            return $default;
        }
        return $value;
    }

    /**
     * All content blocks keyed by block_key (only rows persisted in DB).
     *
     * @return array<string, string>
     */
    public static function blocks(): array
    {
        if (self::$blocks === null) {
            self::$blocks = (new ContentBlock())->allAsArray();
        }
        return self::$blocks;
    }

    /**
     * Registry of editable content blocks (key => [label, type, group, default]).
     *
     * @return array<string, array<string, mixed>>
     */
    public static function blockRegistry(): array
    {
        if (self::$blockRegistry === null) {
            $entries = require CONFIG_PATH . '/content.php';
            self::$blockRegistry = [];
            foreach ($entries as $entry) {
                self::$blockRegistry[$entry['key']] = $entry;
            }
        }
        return self::$blockRegistry;
    }

    /**
     * Resolve a content block's editable value (DB row or registry default).
     */
    public static function block(string $key): string
    {
        $blocks = self::blocks();
        if (array_key_exists($key, $blocks) && trim((string) $blocks[$key]) !== '') {
            return (string) $blocks[$key];
        }
        $registry = self::blockRegistry();
        return (string) ($registry[$key]['default'] ?? '');
    }

    /**
     * Content block split into trimmed non-empty lines.
     *
     * @return array<int, string>
     */
    public static function blockLines(string $key): array
    {
        $value = self::block($key);
        $lines = preg_split('/\r?\n/', $value) ?: [];
        $lines = array_map('trim', $lines);
        return array_values(array_filter($lines, static fn (string $line): bool => $line !== ''));
    }
}