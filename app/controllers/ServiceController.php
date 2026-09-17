<?php

declare(strict_types=1);

namespace Portfolio\Controllers;

use Portfolio\Core\Controller;
use Portfolio\Models\Service;
use Portfolio\Services\SiteService;

final class ServiceController extends Controller
{
    public function index(): void
    {
        $this->view('public/services', [
            'page_title'   => 'Services — ' . SiteService::get('site_name'),
            'meta_description' => 'AI automation, AI agents, customer support automation, RAG assistants and more.',
            'services'     => (new Service())->published(),
        ], 'public');
    }

    public function show(string $slug): void
    {
        $service = (new Service())->findBySlug($slug);
        if ($service === null || !$service['published']) {
            $this->abort(404);
        }

        $this->view('public/services/show', [
            'page_title'   => $service['title'] . ' — ' . SiteService::get('site_name'),
            'meta_description' => $service['short_description'] ?? SiteService::get('meta_description'),
            'service'      => $service,
            'all_services' => (new Service())->published(),
        ], 'public');
    }
}