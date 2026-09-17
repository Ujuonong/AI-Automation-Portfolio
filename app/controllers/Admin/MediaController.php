<?php

declare(strict_types=1);

namespace Portfolio\Controllers\Admin;

use Portfolio\Models\Media;
use Portfolio\Services\UploadService;

final class MediaController extends AdminBaseController
{
    public function index(): void
    {
        $results = (new Media())->paginate(
            max(1, (int) $this->request->query('page', 1)),
            24,
            '',
            [],
            'created_at',
            'DESC'
        );

        $this->view('admin/media/index', [
            'sidebar_active' => 'media',
            'page_title'     => 'Media library',
            'media'          => $results['items'],
            'pagination'     => $results,
            'max_size_mb'    => intdiv((new UploadService())->maxSize(), 1048576),
        ]);
    }

    public function store(): void
    {
        $uploader = new UploadService();

        if (!empty($_FILES['uploads']['name'][0])) {
            $names = $_FILES['uploads']['name'];
            $types = $_FILES['uploads']['type'];
            $tmps  = $_FILES['uploads']['tmp_name'];
            $errors = $_FILES['uploads']['error'];
            $sizes = $_FILES['uploads']['size'];

            $uploaded = 0;
            for ($i = 0; $i < count($names); $i++) {
                $file = [
                    'name'     => $names[$i],
                    'type'     => $types[$i],
                    'tmp_name' => $tmps[$i],
                    'error'    => $errors[$i],
                    'size'     => $sizes[$i],
                ];

                if ($file['error'] === UPLOAD_ERR_NO_FILE) {
                    continue;
                }

                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                try {
                    if (in_array($ext, $uploader->imageExtensions(), true)) {
                        $path = $uploader->uploadImage($file, 'library', 'media');
                        $type = 'image';
                    } elseif (in_array($ext, $uploader->documentExtensions(), true)) {
                        $path = $uploader->uploadDocument($file, 'library', 'media');
                        $type = 'document';
                    } else {
                        flash('error', "Skipped {$file['name']}: file type not allowed.");
                        continue;
                    }

                    (new Media())->record($file['name'], $path, $type, $file['type'], (int) $file['size'], 'library');
                    $uploaded++;
                } catch (\RuntimeException $e) {
                    flash('error', "Skipped {$file['name']}: " . $e->getMessage());
                }
            }

            flash($uploaded > 0 ? 'success' : 'error', $uploaded > 0 ? "{$uploaded} file(s) uploaded." : 'No files were uploaded.');
        } else {
            flash('error', 'No file selected.');
        }

        $this->back('/admin/media');
    }

    public function destroy(string $id): void
    {
        $media = (new Media())->find((int) $id);
        if ($media !== null) {
            if ((new Media())->isInUse($media['stored_path'])) {
                flash('error', 'This file is used in content and cannot be deleted.');
            } else {
                (new UploadService())->delete($media['stored_path']);
                (new Media())->delete((int) $id);
                flash('success', 'File deleted.');
            }
        }
        $this->back('/admin/media');
    }
}