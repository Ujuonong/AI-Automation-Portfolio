<?php

declare(strict_types=1);

namespace Portfolio\Models;

final class BlogPost extends BaseModel
{
    protected string $table = 'blog_posts';

    public function findBySlug(string $slug): ?array
    {
        return $this->findBy('slug', $slug);
    }

    public function findPublishedBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM blog_posts WHERE slug = :slug AND published = 1 LIMIT 1'
        );
        $stmt->execute(['slug' => $slug]);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    public function published(): array
    {
        $stmt = $this->db->query(
            'SELECT * FROM blog_posts WHERE published = 1 ORDER BY published_at DESC, id DESC'
        );
        return $stmt->fetchAll();
    }

    public function latest(int $limit = 3): array
    {
        $stmt = $this->db->query(
            'SELECT * FROM blog_posts WHERE published = 1 ORDER BY published_at DESC, id DESC LIMIT ' . (int) $limit
        );
        return $stmt->fetchAll();
    }

    public function byCategory(string $category): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM blog_posts WHERE published = 1 AND category = :category ORDER BY published_at DESC'
        );
        $stmt->execute(['category' => $category]);
        return $stmt->fetchAll();
    }

    public function search(string $term): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM blog_posts
             WHERE published = 1 AND (title LIKE :term OR excerpt LIKE :term2 OR content LIKE :term3)
             ORDER BY published_at DESC'
        );
        $like = '%' . $term . '%';
        $stmt->execute(['term' => $like, 'term2' => $like, 'term3' => $like]);
        return $stmt->fetchAll();
    }

    public function categories(): array
    {
        $stmt = $this->db->query(
            "SELECT category, COUNT(*) AS total FROM blog_posts WHERE published = 1 AND category <> '' GROUP BY category ORDER BY category ASC"
        );
        return $stmt->fetchAll();
    }

    public function nextPost(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT id, title, slug FROM blog_posts WHERE published = 1 AND id > :id ORDER BY id ASC LIMIT 1'
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    public function publishedCount(): int
    {
        return $this->count('published = 1');
    }

    public function togglePublished(int $id): bool
    {
        $stmt = $this->db->prepare('UPDATE blog_posts SET published = 1 - published, updated_at = NOW() WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}