<?php

declare(strict_types=1);

namespace Portfolio\Models;

final class Media extends BaseModel
{
    protected string $table = 'media';

    public function record(string $originalName, string $storedPath, string $fileType, string $mimeType, int $size, string $category): int
    {
        return $this->create([
            'original_filename' => $originalName,
            'stored_path'       => $storedPath,
            'file_type'         => $fileType,
            'mime_type'         => $mimeType,
            'size'              => $size,
            'category'          => $category,
        ]);
    }

    public function isInUse(string $storedPath): bool
    {
        $checks = [
            ['projects', 'cover_image'],
            ['project_media', 'file_path'],
            ['certificates', 'thumbnail'],
            ['certificates', 'certificate_file'],
            ['testimonials', 'client_image'],
            ['blog_posts', 'cover_image'],
            ['site_settings', 'profile_image'],
            ['site_settings', 'resume_file'],
            ['site_settings', 'logo'],
            ['site_settings', 'favicon'],
            ['site_settings', 'og_image'],
        ];

        foreach ($checks as [$table, $column]) {
            $stmt = $this->db->prepare(
                "SELECT COUNT(*) FROM `{$table}` WHERE `{$column}` = :path"
            );
            $stmt->execute(['path' => $storedPath]);
            if ((int) $stmt->fetchColumn() > 0) {
                return true;
            }
        }
        return false;
    }
}