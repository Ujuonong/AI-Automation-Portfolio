<?php

declare(strict_types=1);

namespace Portfolio\Models;

final class Certificate extends BaseModel
{
    protected string $table = 'certificates';

    public function published(): array
    {
        $stmt = $this->db->query(
            'SELECT * FROM certificates WHERE published = 1 ORDER BY issue_date DESC'
        );
        return $stmt->fetchAll();
    }

    public function featuredPublished(): array
    {
        $stmt = $this->db->query(
            'SELECT * FROM certificates WHERE published = 1 AND featured = 1 ORDER BY issue_date DESC LIMIT 6'
        );
        return $stmt->fetchAll();
    }

    public function publishedCount(): int
    {
        return $this->count('published = 1');
    }

    public function togglePublished(int $id): bool
    {
        $stmt = $this->db->prepare('UPDATE certificates SET published = 1 - published WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

    public function toggleFeatured(int $id): bool
    {
        $stmt = $this->db->prepare('UPDATE certificates SET featured = 1 - featured WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}