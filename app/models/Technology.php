<?php

declare(strict_types=1);

namespace Portfolio\Models;

final class Technology extends BaseModel
{
    protected string $table = 'technologies';

    public function findBySlug(string $slug): ?array
    {
        return $this->findBy('slug', $slug);
    }

    /**
     * All technologies ordered by name.
     */
    public function allOrdered(): array
    {
        $stmt = $this->db->query('SELECT * FROM technologies ORDER BY name ASC');
        return $stmt->fetchAll();
    }

    /**
     * Technologies used by a specific project.
     */
    public function forProject(int $projectId): array
    {
        $stmt = $this->db->prepare(
            'SELECT t.* FROM technologies t
             INNER JOIN project_technologies pt ON pt.technology_id = t.id
             WHERE pt.project_id = :id
             ORDER BY t.name ASC'
        );
        $stmt->execute(['id' => $projectId]);
        return $stmt->fetchAll();
    }

    /**
     * Technologies used across all published projects (deduped).
     */
    public function usedInPublishedProjects(): array
    {
        $stmt = $this->db->query(
            'SELECT DISTINCT t.id, t.name, t.slug
             FROM technologies t
             INNER JOIN project_technologies pt ON pt.technology_id = t.id
             INNER JOIN projects p ON p.id = pt.project_id
             WHERE p.published = 1
             ORDER BY t.name ASC'
        );
        return $stmt->fetchAll();
    }

    public function findOrCreate(string $name): int
    {
        $slug = slugify($name);
        $stmt = $this->db->prepare('SELECT id FROM technologies WHERE slug = :slug OR name = :name LIMIT 1');
        $stmt->execute(['slug' => $slug, 'name' => $name]);
        $id = $stmt->fetchColumn();
        if ($id !== false) {
            return (int) $id;
        }

        $uniqueSlug = \Portfolio\Services\SlugService::ensureUnique('technologies', $slug, null, 'slug');
        $this->db->prepare('INSERT INTO technologies (name, slug, created_at) VALUES (:name, :slug, NOW())')
            ->execute(['name' => $name, 'slug' => $uniqueSlug]);
        return (int) $this->db->lastInsertId();
    }

    public function syncProject(int $projectId, array $techNames): void
    {
        $this->db->prepare('DELETE FROM project_technologies WHERE project_id = :id')->execute(['id' => $projectId]);

        $stmt = $this->db->prepare('INSERT INTO project_technologies (project_id, technology_id) VALUES (:pid, :tid)');
        foreach ($techNames as $name) {
            $name = trim((string) $name);
            if ($name === '') {
                continue;
            }
            $techId = $this->findOrCreate($name);
            $stmt->execute(['pid' => $projectId, 'tid' => $techId]);
        }
    }
}