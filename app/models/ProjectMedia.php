<?php

declare(strict_types=1);

namespace Portfolio\Models;

final class ProjectMedia extends BaseModel
{
    protected string $table = 'project_media';

    public function forProject(int $projectId): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM project_media WHERE project_id = :id ORDER BY sort_order ASC, id ASC'
        );
        $stmt->execute(['id' => $projectId]);
        return $stmt->fetchAll();
    }

    public function deleteForProject(int $projectId): void
    {
        $this->db->prepare('DELETE FROM project_media WHERE project_id = :id')->execute(['id' => $projectId]);
    }
}