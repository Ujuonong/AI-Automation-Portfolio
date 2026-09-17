<?php

declare(strict_types=1);

namespace Portfolio\Models;

final class Project extends BaseModel
{
    protected string $table = 'projects';

    public const STATUSES = ['completed', 'in_progress', 'coming_soon'];
    public const TYPES = ['ai_automation', 'ai_agent', 'ai_support', 'ai_receptionist', 'rag', 'process_automation', 'document_intelligence', 'lead_automation', 'other'];

    public function findBySlug(string $slug): ?array
    {
        return $this->findBy('slug', $slug);
    }

    /**
     * Published project by slug (for public pages).
     */
    public function findPublishedBySlug(string $slug, bool $includeDrafts = false): ?array
    {
        $sql = 'SELECT * FROM projects WHERE slug = :slug';
        if (!$includeDrafts) {
            $sql .= ' AND published = 1';
        }
        $sql .= ' LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['slug' => $slug]);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    public function search(string $term, array $filters = [], bool $publishedOnly = true, string $orderBy = 'created_at', string $direction = 'DESC', int $page = 1, int $perPage = 9): array
    {
        $where  = [];
        $params = [];

        if ($publishedOnly) {
            $where[] = 'p.published = 1';
        }

        $term = trim($term);
        if ($term !== '') {
            $where[] = '(p.title LIKE :term OR p.short_description LIKE :term2)';
            $params['term']  = "%{$term}%";
            $params['term2'] = "%{$term}%";
        }

        if (!empty($filters['type']) && in_array($filters['type'], self::TYPES, true)) {
            $where[] = 'p.project_type = :type';
            $params['type'] = $filters['type'];
        }

        if (!empty($filters['status']) && in_array($filters['status'], self::STATUSES, true)) {
            $where[] = 'p.status = :status';
            $params['status'] = $filters['status'];
        }

        if (!empty($filters['featured'])) {
            $where[] = 'p.featured = 1';
        }

        $techIdFilter = (int) ($filters['technology'] ?? 0);
        if ($techIdFilter > 0) {
            $where[] = 'EXISTS (SELECT 1 FROM project_technologies pf WHERE pf.project_id = p.id AND pf.technology_id = :tech_id)';
            $params['tech_id'] = $techIdFilter;
        }

        $whereSql = $where ? ' WHERE ' . implode(' AND ', $where) : '';
        $orderWhitelist = ['title', 'created_at', 'updated_at', 'featured'];
        $orderBySafe = in_array($orderBy, $orderWhitelist, true) ? $orderBy : 'created_at';
        $directionSafe = $direction === 'ASC' ? 'ASC' : 'DESC';

        $page = max(1, $page);

        $countStmt = $this->db->prepare("SELECT COUNT(*) FROM projects p{$whereSql}");
        $countStmt->execute($params);
        $total = (int) $countStmt->fetchColumn();
        $totalPages = max(1, (int) ceil($total / $perPage));
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT p.* FROM projects p{$whereSql}
                ORDER BY p.{$orderBySafe} {$directionSafe}
                LIMIT {$perPage} OFFSET {$offset}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return [
            'items'       => $stmt->fetchAll(),
            'total'       => $total,
            'page'        => $page,
            'per_page'    => $perPage,
            'total_pages' => $totalPages,
            'offset'      => $offset,
        ];
    }

    public function featured(int $limit = 3, bool $publishedOnly = true): array
    {
        $sql = 'SELECT * FROM projects WHERE featured = 1';
        if ($publishedOnly) {
            $sql .= ' AND published = 1';
        }
        $sql .= ' ORDER BY updated_at DESC LIMIT ' . (int) $limit;
        return $this->db->query($sql)->fetchAll();
    }

    public function nextProject(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT id, title, slug FROM projects WHERE published = 1 AND id > :id ORDER BY id ASC LIMIT 1'
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    public function recent(int $limit = 3, bool $publishedOnly = true): array
    {
        $sql = 'SELECT * FROM projects';
        if ($publishedOnly) {
            $sql .= ' WHERE published = 1';
        }
        $sql .= ' ORDER BY created_at DESC LIMIT ' . (int) $limit;
        return $this->db->query($sql)->fetchAll();
    }

    public function duplicate(int $id): ?int
    {
        $project = $this->find($id);
        if ($project === null) {
            return null;
        }

        $this->db->beginTransaction();
        try {
            $copy = $project;
            unset($copy['id'], $copy['created_at'], $copy['updated_at']);
            $copy['title']    = $copy['title'] . ' (Copy)';
            $copy['slug']     = \Portfolio\Services\SlugService::ensureUnique('projects', $copy['slug'] . '-copy');
            $copy['published'] = 0;

            $newId = $this->create($copy);

            $media = (new ProjectMedia())->forProject($id);
            $mediaStmt = $this->db->prepare(
                'INSERT INTO project_media (project_id, file_path, media_type, caption, sort_order, created_at)
                 VALUES (:pid, :file_path, :media_type, :caption, :sort_order, NOW())'
            );
            foreach ($media as $m) {
                $mediaStmt->execute([
                    'pid'        => $newId,
                    'file_path'  => $m['file_path'],
                    'media_type' => $m['media_type'],
                    'caption'    => $m['caption'],
                    'sort_order' => $m['sort_order'],
                ]);
            }

            $tech = (new Technology())->forProject($id);
            $names = array_column($tech, 'name');
            (new Technology())->syncProject($newId, $names);

            $this->db->commit();
            return $newId;
        } catch (\Throwable $e) {
            $this->db->rollBack();
            error_log('[Project] Duplicate failed: ' . $e->getMessage());
            return null;
        }
    }

    public function deleteWithMedia(int $id): bool
    {
        $project = $this->find($id);
        if ($project === null) {
            return false;
        }

        $this->db->beginTransaction();
        try {
            $mediaRows = (new ProjectMedia())->forProject($id);
            $uploader = new \Portfolio\Services\UploadService();
            foreach ($mediaRows as $media) {
                $uploader->delete($media['file_path']);
            }

            if ($project['cover_image']) {
                $uploader->delete($project['cover_image']);
            }

            $this->db->prepare('DELETE FROM project_technologies WHERE project_id = :id')->execute(['id' => $id]);
            $this->db->prepare('DELETE FROM project_media WHERE project_id = :id')->execute(['id' => $id]);
            $this->delete($id);

            $this->db->commit();
            return true;
        } catch (\Throwable $e) {
            $this->db->rollBack();
            error_log('[Project] Delete failed: ' . $e->getMessage());
            return false;
        }
    }

    public function togglePublished(int $id): bool
    {
        return $this->toggleFlag($id, 'published');
    }

    public function toggleFeatured(int $id): bool
    {
        return $this->toggleFlag($id, 'featured');
    }

    private function toggleFlag(int $id, string $column): bool
    {
        $stmt = $this->db->prepare("UPDATE projects SET {$column} = 1 - {$column}, updated_at = NOW() WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function publishedCount(): int
    {
        return $this->count('published = 1');
    }

    public function draftCount(): int
    {
        return $this->count('published = 0');
    }
}