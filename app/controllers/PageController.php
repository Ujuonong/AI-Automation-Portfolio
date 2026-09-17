<?php

declare(strict_types=1);

namespace Portfolio\Controllers;

use Portfolio\Core\Controller;
use Portfolio\Models\Education;
use Portfolio\Models\Experience;
use Portfolio\Models\Project;
use Portfolio\Models\Skill;
use Portfolio\Services\SiteService;

final class PageController extends Controller
{
    public function about(): void
    {
        $this->view('public/about', [
            'page_title'   => 'About — ' . SiteService::get('site_name', 'Bulus Ujuonong James'),
            'meta_description' => SiteService::get('meta_description'),
            'skills'       => (new Skill())->publishedByCategory(),
            'experience'   => (new Experience())->published(),
            'education'    => (new Education())->published(),
            'stats'        => [
                'projects' => (new Project())->publishedCount(),
                'certificates' => (new \Portfolio\Models\Certificate())->publishedCount(),
            ],
        ], 'public');
    }

    public function experience(): void
    {
        $this->view('public/experience', [
            'page_title'   => 'Experience — ' . SiteService::get('site_name'),
            'meta_description' => 'Professional experience and project timeline.',
            'experience'   => (new Experience())->published(),
        ], 'public');
    }

    public function education(): void
    {
        $this->view('public/education', [
            'page_title'   => 'Education — ' . SiteService::get('site_name'),
            'meta_description' => 'Academic background and certifications timeline.',
            'education'    => (new Education())->published(),
        ], 'public');
    }
}