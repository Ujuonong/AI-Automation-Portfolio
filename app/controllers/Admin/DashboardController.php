<?php

declare(strict_types=1);

namespace Portfolio\Controllers\Admin;

use Portfolio\Models\BlogPost;
use Portfolio\Models\Certificate;
use Portfolio\Models\ContactMessage;
use Portfolio\Models\Project;

final class DashboardController extends AdminBaseController
{
    public function index(): void
    {
        $projectModel  = new Project();
        $certModel     = new Certificate();
        $blogModel     = new BlogPost();
        $messageModel  = new ContactMessage();

        $stats = [
            'total_projects'   => $projectModel->count(),
            'published_projects' => $projectModel->publishedCount(),
            'draft_projects'   => $projectModel->draftCount(),
            'featured_projects' => $projectModel->count('featured = 1'),
            'certificates'     => $certModel->count(),
            'published_certificates' => $certModel->publishedCount(),
            'blog_posts'       => $blogModel->count(),
            'published_posts'  => $blogModel->publishedCount(),
            'unread_messages'  => $messageModel->unreadCount(),
            'total_messages'   => $messageModel->count(),
        ];

        $recentProjects  = $projectModel->recent(5, false);
        $recentMessages  = array_slice($messageModel->all('created_at', 'DESC'), 0, 5, true);
        $recentPosts     = array_slice($blogModel->all('created_at', 'DESC'), 0, 5, true);

        $this->view('admin/dashboard', [
            'sidebar_active' => 'dashboard',
            'stats'          => $stats,
            'recent_projects' => $recentProjects,
            'recent_messages' => $recentMessages,
            'recent_posts'    => $recentPosts,
            'page_title'      => 'Dashboard',
        ]);
    }
}