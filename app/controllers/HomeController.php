<?php

declare(strict_types=1);

namespace Portfolio\Controllers;

use Portfolio\Core\Controller;
use Portfolio\Models\BlogPost;
use Portfolio\Models\Certificate;
use Portfolio\Models\Project;
use Portfolio\Models\Service;
use Portfolio\Models\Skill;
use Portfolio\Services\SiteService;

final class HomeController extends Controller
{
    public function index(): void
    {
        $settings = SiteService::settings();

        $stats = [
            'projects'      => (new Project())->publishedCount(),
            'technologies'  => (new Skill())->publishedCount(),
            'certificates'  => (new Certificate())->publishedCount(),
            'services'      => (new Service())->publishedCount(),
        ];

        $this->view('public/home', [
            'page_title'   => SiteService::get('site_name', 'Bulus Ujuonong James'),
            'meta_description' => SiteService::get('meta_description'),
            'og_image'     => SiteService::get('og_image'),
            'featured_projects' => (new Project())->featured(3),
            'services'     => (new Service())->published(),
            'skills'       => (new Skill())->publishedByCategory(),
            'posts'        => (new BlogPost())->latest(3),
            'stats'        => $stats,
            'project_tech' => new \Portfolio\Models\Technology(),
        ], 'public');
    }
}