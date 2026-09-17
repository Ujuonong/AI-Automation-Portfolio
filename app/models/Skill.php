<?php

declare(strict_types=1);

namespace Portfolio\Models;

final class Skill extends BaseModel
{
    protected string $table = 'skills';

    public function publishedByCategory(): array
    {
        $stmt = $this->db->query(
            'SELECT * FROM skills WHERE published = 1 ORDER BY category ASC, sort_order ASC, id ASC'
        );
        $rows = $stmt->fetchAll();

        $grouped = [];
        foreach ($rows as $row) {
            $grouped[$row['category']][] = $row;
        }
        return $grouped;
    }

    public function publishedCount(): int
    {
        return $this->count('published = 1');
    }
}