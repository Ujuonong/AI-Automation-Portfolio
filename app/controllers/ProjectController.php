<?php

declare(strict_types=1);

namespace Portfolio\Controllers;

use Portfolio\Core\Controller;
use Portfolio\Core\Session;
use Portfolio\Models\Project;
use Portfolio\Models\ProjectMedia;
use Portfolio\Models\Technology;
use Portfolio\Services\SiteService;

final class ProjectController extends Controller
{
    public function index(): void
    {
        $page   = max(1, (int) $this->request->query('page', 1));
        $search = trim((string) $this->request->query('q', ''));
        $type   = trim((string) $this->request->query('type', ''));
        $status = trim((string) $this->request->query('status', ''));
        $techId = (int) $this->request->query('technology', 0);

        $filters = [];
        if ($type !== '' && in_array($type, Project::TYPES, true)) {
            $filters['type'] = $type;
        }
        if ($status !== '' && in_array($status, Project::STATUSES, true)) {
            $filters['status'] = $status;
        }
        if ($techId > 0) {
            $filters['technology'] = $techId;
        }

        $results = (new Project())->search($search, $filters, true, 'created_at', 'DESC', $page, 9);

        $this->view('public/projects/index', [
            'page_title'     => 'Projects — ' . SiteService::get('site_name'),
            'meta_description' => 'Explore AI automation and engineering projects built by Bulus Ujuonong James.',
            'projects'       => $results['items'],
            'pagination'     => $results,
            'search'         => $search,
            'type'           => $type,
            'status'         => $status,
            'tech_id'        => $techId,
            'types'          => Project::TYPES,
            'statuses'       => Project::STATUSES,
            'all_technologies' => (new Technology())->usedInPublishedProjects(),
            'technologies'   => new Technology(),
        ], 'public');
    }

    public function show(string $slug): void
    {
        $project = (new Project())->findBySlug($slug);
        if ($project === null) {
            $this->abort(404);
        }

        if (!$project['published'] && !Session::isAuthenticated()) {
            $this->abort(404);
        }

        $techModel = new Technology();

        $this->view('public/projects/show', [
            'page_title'     => $project['title'] . ' — ' . SiteService::get('site_name'),
            'meta_description' => $project['short_description'] ?? SiteService::get('meta_description'),
            'og_image'       => $project['cover_image'],
            'project'        => $project,
            'technologies'   => $techModel->forProject((int) $project['id']),
            'media'          => (new ProjectMedia())->forProject((int) $project['id']),
            'next_project'   => (new Project())->nextProject((int) $project['id']),
        ], 'public');
    }
}