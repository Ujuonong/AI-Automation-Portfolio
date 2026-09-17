<?php

declare(strict_types=1);

namespace Portfolio\Controllers\Admin;

use Portfolio\Core\Csrf;
use Portfolio\Core\View;
use Portfolio\Services\SiteService;

/**
 * View-rendering shared between admin controllers.
 */
trait RendersAdminViews
{
    private function adminSharedData(array $data): array
    {
        return array_merge([
            'admin'           => $this->adminUser,
            'unread_messages' => $this->unreadMessages,
            'site'            => SiteService::settings(),
            'sidebar_active'  => ($data['sidebar_active'] ?? ''),
            'csrf'            => Csrf::field(),
        ], $data);
    }

    /**
     * Echo an admin view inside the admin layout.
     */
    protected function view(string $view, array $data = [], ?string $layout = null): void
    {
        echo View::render($view, $this->adminSharedData($data), $layout ?? 'admin');
    }

    /**
     * Return an admin view as a string.
     */
    protected function renderView(string $view, array $data = [], ?string $layout = null): string
    {
        return View::render($view, $this->adminSharedData($data), $layout ?? 'admin');
    }
}