<?php

declare(strict_types=1);

namespace Portfolio\Controllers;

use Portfolio\Core\Controller;
use Portfolio\Services\SiteService;

final class ConsultationController extends Controller
{
    public function index(): void
    {
        $this->view('public/consultation', [
            'page_title'      => 'AI Consultation — ' . SiteService::get('site_name'),
            'meta_description' => 'Tell me about your business and the process you want to automate.',
            'enabled'         => SiteService::get('ai_consultation_enabled', '1') === '1',
            'assistant_name'  => (string) SiteService::get('ai_consultant_name', 'AI Consultant'),
            'welcome'         => (string) SiteService::get('ai_consultant_welcome', "Hi! Welcome. Tell me a little about your business and what you'd like to automate."),
            'cta'             => (string) SiteService::get('ai_consultant_cta', 'Audit My Business'),
            'webhook_url'     => (string) SiteService::get('ai_consultant_webhook_url', 'YOUR_WEBHOOK_URL_HERE'),
        ], 'public');
    }
}