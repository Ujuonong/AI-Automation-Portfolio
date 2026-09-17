<?php

declare(strict_types=1);

namespace Portfolio\Models;

final class Experience extends BaseModel
{
    protected string $table = 'experiences';

    public function published(): array
    {
        $stmt = $this->db->query(
            'SELECT * FROM experiences WHERE published = 1 ORDER BY sort_order ASC, id ASC'
        );
        return $stmt->fetchAll();
    }

    public function publishedCount(): int
    {
        return $this->count('published = 1');
    }
}