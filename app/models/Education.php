<?php

declare(strict_types=1);

namespace Portfolio\Models;

final class Education extends BaseModel
{
    protected string $table = 'education';

    public function published(): array
    {
        $stmt = $this->db->query(
            'SELECT * FROM education WHERE published = 1 ORDER BY sort_order ASC, id ASC'
        );
        return $stmt->fetchAll();
    }

    public function publishedCount(): int
    {
        return $this->count('published = 1');
    }
}