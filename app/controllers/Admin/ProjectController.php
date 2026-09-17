<?php

declare(strict_types=1);

namespace Portfolio\Controllers\Admin;

use Portfolio\Models\Project;
use Portfolio\Models\ProjectMedia;
use Portfolio\Models\Technology;
use Portfolio\Services\SlugService;
use Portfolio\Services\UploadService;
use Portfolio\Services\Validator;

final class ProjectController extends AdminBaseController
{
    public function index(): void
    {
        $page    = max(1, (int) $this->request->query('page', 1));
        $search  = (string) $this->request->query('search', '');
        $status  = (string) $this->request->query('status', '');
        $type    = (string) $this->request->query('type', '');
        $sort    = (string) $this->request->query('sort', 'created_at');
        $dir     = (string) $this->request->query('dir', 'DESC');

        $filters = [];
        if ($status !== '' && in_array($status, Project::STATUSES, true)) {
            $filters['status'] = $status;
        }
        if ($type !== '' && in_array($type, Project::TYPES, true)) {
            $filters['type'] = $type;
        }

        $results = (new Project())->search($search, $filters, false, $sort, $dir, $page, 10);

        $this->view('admin/projects/index', [
            'sidebar_active' => 'projects',
            'page_title'     => 'Projects',
            'projects'       => $results['items'],
            'pagination'     => $results,
            'search'         => $search,
            'type'           => $type,
            'status'         => $status,
            'sort'           => $sort,
            'dir'            => $dir,
            'statuses'       => Project::STATUSES,
            'types'          => Project::TYPES,
        ]);
    }

    public function create(): void
    {
        $this->view('admin/projects/form', [
            'sidebar_active' => 'projects',
            'page_title'     => 'New Project',
            'project'        => null,
            'technologies'   => (new Technology())->allOrdered(),
            'project_tech'   => [],
            'media'          => [],
            'statuses'       => Project::STATUSES,
            'types'          => Project::TYPES,
        ]);
    }

    public function store(): void
    {
        $input = $this->request->all();

        $validator = (new Validator($input))
            ->required('title', 'title')
            ->maxLength('title', 'title', 255)
            ->required('slug', 'slug')
            ->in('status', 'status', Project::STATUSES)
            ->in('project_type', 'project type', Project::TYPES);

        if ($validator->passes()) {
            $slug = SlugService::ensureUnique('projects', slugify((string) $input['slug']));
        }

        $uploader = new UploadService();
        $cover = null;

        $file = $this->request->file('cover_image');
        if ($file !== null) {
            try {
                $cover = $uploader->uploadImage($file, 'projects', 'project');
                flash('success', 'Cover image uploaded.');
            } catch (\RuntimeException $e) {
                $validator->addError('cover_image', $e->getMessage());
            }
        }

        if ($validator->passes()) {
            try {
                $projectId = (new Project())->create([
                    'title'              => $input['title'],
                    'slug'               => $slug,
                    'short_description'  => $input['short_description'] ?? '',
                    'description'        => $input['description'] ?? '',
                    'problem'            => $input['problem'] ?? '',
                    'solution'           => $input['solution'] ?? '',
                    'architecture'       => $input['architecture'] ?? '',
                    'results'            => $input['results'] ?? '',
                    'project_type'       => $input['project_type'] ?? 'other',
                    'status'             => $input['status'] ?? 'completed',
                    'featured'           => isset($input['featured']) ? 1 : 0,
                    'published'          => isset($input['published']) ? 1 : 0,
                    'github_url'         => trim((string) ($input['github_url'] ?? '')),
                    'demo_url'           => trim((string) ($input['demo_url'] ?? '')),
                    'video_url'          => trim((string) ($input['video_url'] ?? '')),
                    'cover_image'        => $cover,
                ]);

                $techNames = $this->parseTechnologies($input);

                if (!empty($techNames)) {
                    (new Technology())->syncProject($projectId, $techNames);
                }

                $this->storeMedia($projectId, $input, $uploader);

                flash('success', 'Project created.');
                $this->redirect('/admin/projects/edit/' . $projectId);
            } catch (\Throwable $e) {
                if ($cover !== null) {
                    $uploader->delete($cover);
                }
                error_log('[Project] Store error: ' . $e->getMessage());
                flash('error', 'Could not save the project. Please try again.');
            }
        }

        $this->view('admin/projects/form', [
            'sidebar_active' => 'projects',
            'page_title'     => 'New Project',
            'project'        => null,
            'technologies'   => (new Technology())->allOrdered(),
            'project_tech'   => (array) ($input['technologies'] ?? []),
            'media'          => [],
            'statuses'       => Project::STATUSES,
            'types'          => Project::TYPES,
            'errors'         => $validator->errors(),
            'old'            => $input,
        ]);
    }

    public function edit(string $id): void
    {
        $project = (new Project())->find((int) $id);
        if ($project === null) {
            $this->abort(404);
        }

        $this->view('admin/projects/form', [
            'sidebar_active' => 'projects',
            'page_title'     => 'Edit Project',
            'project'        => $project,
            'technologies'   => (new Technology())->allOrdered(),
            'project_tech'   => array_column((new Technology())->forProject((int) $id), 'name'),
            'media'          => (new ProjectMedia())->forProject((int) $id),
            'statuses'       => Project::STATUSES,
            'types'          => Project::TYPES,
        ]);
    }

    public function update(string $id): void
    {
        $projectId = (int) $id;
        $project = (new Project())->find($projectId);
        if ($project === null) {
            $this->abort(404);
        }

        $input = $this->request->all();

        $validator = (new Validator($input))
            ->required('title', 'title')
            ->maxLength('title', 'title', 255)
            ->required('slug', 'slug')
            ->in('status', 'status', Project::STATUSES)
            ->in('project_type', 'project type', Project::TYPES);

        $errors = $validator->errors();

        $uploader = new UploadService();
        $cover = $project['cover_image'];
        $coverError = null;

        $file = $this->request->file('cover_image');
        if ($file !== null) {
            try {
                $newCover = $uploader->uploadImage($file, 'projects', 'project');
                if ($project['cover_image']) {
                    $uploader->delete($project['cover_image']);
                }
                $cover = $newCover;
            } catch (\RuntimeException $e) {
                $coverError = $e->getMessage();
            }
        }

        if ($coverError !== null) {
            $errors['cover_image'] = $coverError;
        }

        if ($validator->passes() && $coverError === null) {
            try {
                $slug = SlugService::ensureUnique('projects', slugify((string) $input['slug']), $projectId);
                (new Project())->update($projectId, [
                    'title'              => $input['title'],
                    'slug'               => $slug,
                    'short_description'  => $input['short_description'] ?? '',
                    'description'        => $input['description'] ?? '',
                    'problem'            => $input['problem'] ?? '',
                    'solution'           => $input['solution'] ?? '',
                    'architecture'       => $input['architecture'] ?? '',
                    'results'            => $input['results'] ?? '',
                    'project_type'       => $input['project_type'] ?? 'other',
                    'status'             => $input['status'] ?? 'completed',
                    'featured'           => isset($input['featured']) ? 1 : 0,
                    'published'          => isset($input['published']) ? 1 : 0,
                    'github_url'         => trim((string) ($input['github_url'] ?? '')),
                    'demo_url'           => trim((string) ($input['demo_url'] ?? '')),
                    'video_url'          => trim((string) ($input['video_url'] ?? '')),
                    'cover_image'        => $cover,
                ]);

                (new Technology())->syncProject($projectId, $this->parseTechnologies($input));

                $mediaModel = new ProjectMedia();
                $deleteMedia = (array) ($input['delete_media'] ?? []);
                foreach ($deleteMedia as $mediaId) {
                    $mediaRow = $mediaModel->find((int) $mediaId);
                    if ($mediaRow !== null && (int) $mediaRow['project_id'] === $projectId) {
                        $uploader->delete($mediaRow['file_path']);
                        $mediaModel->delete((int) $mediaId);
                    }
                }

                $this->storeMedia($projectId, $input, $uploader);

                flash('success', 'Project updated.');
                $this->redirect('/admin/projects/edit/' . $projectId);
            } catch (\Throwable $e) {
                error_log('[Project] Update error: ' . $e->getMessage());
                flash('error', 'Could not update the project.');
                $this->back('/admin/projects');
            }
        }

        $this->view('admin/projects/form', [
            'sidebar_active' => 'projects',
            'page_title'     => 'Edit Project',
            'project'        => $project,
            'technologies'   => (new Technology())->allOrdered(),
            'project_tech'   => (array) ($input['technologies'] ?? array_column((new Technology())->forProject($projectId), 'name')),
            'media'          => (new ProjectMedia())->forProject($projectId),
            'statuses'       => Project::STATUSES,
            'types'          => Project::TYPES,
            'errors'         => $errors,
            'old'            => $input,
        ]);
    }

    public function destroy(string $id): void
    {
        if (!(new Project())->deleteWithMedia((int) $id)) {
            flash('error', 'Could not delete the project.');
            $this->back('/admin/projects');
        }
        flash('success', 'Project deleted.');
        $this->redirect('/admin/projects');
    }

    public function togglePublished(string $id): void
    {
        (new Project())->togglePublished((int) $id);
        flash('success', 'Project publish status updated.');
        $this->back('/admin/projects');
    }

    public function toggleFeatured(string $id): void
    {
        (new Project())->toggleFeatured((int) $id);
        flash('success', 'Project featured status updated.');
        $this->back('/admin/projects');
    }

    public function duplicate(string $id): void
    {
        $newId = (new Project())->duplicate((int) $id);
        if ($newId === null) {
            flash('error', 'Could not duplicate the project.');
            $this->back('/admin/projects');
        }
        flash('success', 'Project duplicated as a draft.');
        $this->redirect('/admin/projects/edit/' . $newId);
    }

    /**
     * Handle additional gallery image uploads from project forms.
     */
    private function storeMedia(int $projectId, array $input, UploadService $uploader): void
    {
        if (empty($_FILES['additional_media']['name'][0])) {
            return;
        }

        $mediaModel = new ProjectMedia();
        $sort = (int) $this->dbSortStart($projectId);

        $names = $_FILES['additional_media']['name'];
        $types = $_FILES['additional_media']['type'];
        $tmps  = $_FILES['additional_media']['tmp_name'];
        $errors = $_FILES['additional_media']['error'];
        $sizes = $_FILES['additional_media']['size'];

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

            try {
                $path = $uploader->uploadImage($file, 'projects/gallery', 'project');
                $caption = isset($input['media_captions'][$i]) ? trim((string) $input['media_captions'][$i]) : '';
                $mediaModel->create([
                    'project_id' => $projectId,
                    'file_path'  => $path,
                    'media_type' => 'image',
                    'caption'    => $caption,
                    'sort_order' => $sort,
                ]);
                $sort++;
            } catch (\RuntimeException $e) {
                flash('error', 'A gallery image was skipped: ' . $e->getMessage());
            }
        }
    }

    private function dbSortStart(int $projectId): int
    {
        $db = \Portfolio\Core\Database::connection();
        $stmt = $db->prepare('SELECT MAX(sort_order) FROM project_media WHERE project_id = :id');
        $stmt->execute(['id' => $projectId]);
        $max = (int) $stmt->fetchColumn();
        return $max + 1;
    }

    private function parseTechnologies(array $input): array
    {
        $raw = (string) ($input['technologies'] ?? '');
        if (is_array($input['technologies'] ?? null)) {
            $raw = implode(',', $input['technologies']);
        }
        $names = array_filter(array_map(static fn ($t) => trim((string) $t), explode(',', $raw)));
        return array_values($names);
    }
}