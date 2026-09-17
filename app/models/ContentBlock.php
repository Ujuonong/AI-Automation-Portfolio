<?php

declare(strict_types=1);

namespace Portfolio\Models;

final class ContentBlock extends BaseModel
{
    protected string $table = 'content_blocks';

    /**
     * Return every block as block_key => value.
     *
     * @return array<string, string>
     */
    public function allAsArray(): array
    {
        $stmt = $this->db->query('SELECT block_key, `value` FROM content_blocks');
        $rows = $stmt->fetchAll();
        $out = [];
        foreach ($rows as $row) {
            $out[$row['block_key']] = (string) $row['value'];
        }
        return $out;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $stmt = $this->db->prepare('SELECT `value` FROM content_blocks WHERE block_key = :key LIMIT 1');
        $stmt->execute(['key' => $key]);
        $value = $stmt->fetchColumn();
        return $value === false ? $default : $value;
    }

    /**
     * Bulk upsert blocks from block_key => value map.
     *
     * @param array<string, string> $blocks
     * @param array<string, string> $labels
     */
    public function setMany(array $blocks, array $labels = []): void
    {
        $upsert = $this->db->prepare(
            'INSERT INTO content_blocks (block_key, label, `value`, updated_at)
             VALUES (:key, :label, :value, NOW())
             ON DUPLICATE KEY UPDATE `value` = :value2, updated_at = NOW()'
        );
        foreach ($blocks as $key => $value) {
            if (!is_string($key) || $key === '') {
                continue;
            }
            $upsert->execute([
                'key'    => $key,
                'label'  => $labels[$key] ?? '',
                'value'  => (string) ($value ?? ''),
                'value2' => (string) ($value ?? ''),
            ]);
        }
    }
}