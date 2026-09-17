<?php

declare(strict_types=1);

namespace Portfolio\Controllers;

use Portfolio\Core\Controller;
use Portfolio\Models\Certificate;
use Portfolio\Services\SiteService;

final class CertificateController extends Controller
{
    public function index(): void
    {
        $this->view('public/certificates', [
            'page_title'   => 'Certificates — ' . SiteService::get('site_name'),
            'meta_description' => 'Professional certificates and credentials earned in AI engineering and automation.',
            'certificates' => (new Certificate())->published(),
        ], 'public');
    }
}