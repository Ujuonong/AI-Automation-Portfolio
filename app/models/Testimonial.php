<?php

declare(strict_types=1);

namespace Portfolio\Models;

final class Testimonial extends BaseModel
{
    protected string $table = 'testimonials';

    public function published(): array
    {
        $stmt = $this->db->query(
            'SELECT * FROM testimonials WHERE published = 1 ORDER BY featured DESC, created_at DESC'
        );
        return $stmt->fetchAll();
    }

    public function featuredPublished(int $limit = 3): array
    {
        $stmt = $this->db->query(
            'SELECT * FROM testimonials WHERE published = 1 AND featured = 1 ORDER BY created_at DESC LIMIT ' . (int) $limit
        );
        return $stmt->fetchAll();
    }

    public function publishedCount(): int
    {
        return $this->count('published = 1');
    }
}