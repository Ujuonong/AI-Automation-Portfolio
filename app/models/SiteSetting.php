<?php

declare(strict_types=1);

namespace Portfolio\Models;

final class SiteSetting extends BaseModel
{
    protected string $table = 'site_settings';

    public function allAsArray(): array
    {
        $stmt = $this->db->query('SELECT `key`, `value` FROM site_settings');
        $rows = $stmt->fetchAll();
        $out = [];
        foreach ($rows as $row) {
            $out[$row['key']] = $row['value'];
        }
        return $out;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $stmt = $this->db->prepare('SELECT `value` FROM site_settings WHERE `key` = :key LIMIT 1');
        $stmt->execute(['key' => $key]);
        $value = $stmt->fetchColumn();
        return $value === false ? $default : $value;
    }

    /**
     * Bulk upsert settings from a key => value map.
     */
    public function setMany(array $settings): void
    {
        $upsert = $this->db->prepare(
            'INSERT INTO site_settings (`key`, `value`, `updated_at`)
             VALUES (:key, :value, NOW())
             ON DUPLICATE KEY UPDATE `value` = :value2, `updated_at` = NOW()'
        );
        foreach ($settings as $key => $value) {
            if (!is_string($key) || $key === '') {
                continue;
            }
            $upsert->execute([
                'key'    => $key,
                'value'  => (string) ($value ?? ''),
                'value2' => (string) ($value ?? ''),
            ]);
        }
    }
}