<?php

declare(strict_types=1);

namespace Portfolio\Controllers;

use Portfolio\Core\Controller;
use Portfolio\Models\Testimonial;
use Portfolio\Services\SiteService;

final class TestimonialController extends Controller
{
    public function index(): void
    {
        $this->view('public/testimonials', [
            'page_title'   => 'Testimonials — ' . SiteService::get('site_name'),
            'meta_description' => 'What clients say about working with Bulus Ujuonong James.',
            'testimonials' => (new Testimonial())->published(),
        ], 'public');
    }
}