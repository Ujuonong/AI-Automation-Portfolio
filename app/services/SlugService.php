<?php

declare(strict_types=1);

namespace Portfolio\Services;

/**
 * Handles slug generation and uniqueness.
 */
final class SlugService
{
    public static function make(string $title): string
    {
        $slug = slugify($title);
        return $slug !== '' ? $slug : 'item-' . substr(md5((string) time()), 0, 6);
    }

    /**
     * Ensure the slug is unique against a table, appending a counter.
     */
    public static function ensureUnique(string $table, string $slug, ?int $ignoreId = null, ?string $column = 'slug'): string
    {
        $db = \Portfolio\Core\Database::connection();

        $sql = "SELECT COUNT(*) FROM `{$table}` WHERE `{$column}` = :slug";
        $params = ['slug' => $slug];
        if ($ignoreId !== null) {
            $sql .= ' AND `id` <> :id';
            $params['id'] = $ignoreId;
        }

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $count = (int) $stmt->fetchColumn();

        if ($count === 0) {
            return $slug;
        }

        $i = 2;
        while (true) {
            $candidate = $slug . '-' . $i;
            $stmt = $db->prepare("SELECT COUNT(*) FROM `{$table}` WHERE `{$column}` = :slug" . ($ignoreId !== null ? ' AND `id` <> :id' : ''));
            $checkParams = ['slug' => $candidate];
            if ($ignoreId !== null) {
                $checkParams['id'] = $ignoreId;
            }
            $stmt->execute($checkParams);
            if ((int) $stmt->fetchColumn() === 0) {
                return $candidate;
            }
            $i++;
        }
    }
}