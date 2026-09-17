<?php

declare(strict_types=1);

namespace Portfolio\Controllers\Admin;

use Portfolio\Models\ContentBlock;
use Portfolio\Services\SiteService;

final class ContentController extends AdminBaseController
{
    public function index(): void
    {
        $registry = SiteService::blockRegistry();
        $stored = SiteService::blocks();

        $groups = [];
        foreach ($registry as $key => $entry) {
            $groups[$entry['group']][] = [
                'key'   => $key,
                'label' => $entry['label'],
                'type'  => $entry['type'],
                'value' => array_key_exists($key, $stored) && trim((string) $stored[$key]) !== ''
                    ? (string) $stored[$key]
                    : (string) $entry['default'],
            ];
        }

        $this->view('admin/content/index', [
            'sidebar_active' => 'content',
            'page_title'     => 'Website Content',
            'groups'         => $groups,
        ]);
    }

    public function update(): void
    {
        $input = $this->request->all();
        $registry = SiteService::blockRegistry();

        $blocks = [];
        $labels = [];
        foreach ($registry as $key => $entry) {
            $value = $input[$key] ?? '';
            if (!is_string($value)) {
                continue;
            }
            $blocks[$key] = $value;
            $labels[$key] = (string) $entry['label'];
        }

        (new ContentBlock())->setMany($blocks, $labels);
        flash('success', 'Website content saved.');
        $this->redirect('/admin/content');
    }
}