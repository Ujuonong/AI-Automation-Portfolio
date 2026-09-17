<?php

declare(strict_types=1);

namespace Portfolio\Controllers\Admin;

use Portfolio\Core\Controller;
use Portfolio\Core\Session;
use Portfolio\Models\ContactMessage;

/**
 * Base class for authenticated admin controllers.
 */
abstract class AdminBaseController extends Controller
{
    use RendersAdminViews;

    protected array $adminUser = [];
    protected int $unreadMessages = 0;

    public function __construct()
    {
        parent::__construct();

        $this->adminUser = [
            'id'    => (int) Session::get('user_id', 0),
            'name'  => (string) Session::get('user_name', 'Admin'),
            'email' => (string) Session::get('user_email', ''),
            'role'  => (string) Session::get('user_role', 'admin'),
        ];

        try {
            $this->unreadMessages = (new ContactMessage())->unreadCount();
        } catch (\Throwable $e) {
            $this->unreadMessages = 0;
            error_log('[AdminBase] unread count failed: ' . $e->getMessage());
        }
    }
}