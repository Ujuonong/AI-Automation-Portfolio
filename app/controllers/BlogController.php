<?php

declare(strict_types=1);

namespace Portfolio\Controllers;

use Portfolio\Core\Controller;
use Portfolio\Models\BlogPost;
use Portfolio\Services\SiteService;

final class BlogController extends Controller
{
    public function index(): void
    {
        $search = trim((string) $this->request->query('q', ''));
        $posts = $search !== '' ? (new BlogPost())->search($search) : (new BlogPost())->published();

        $this->view('public/blog/index', [
            'page_title'   => 'Blog — ' . SiteService::get('site_name'),
            'meta_description' => 'Insights on AI automation, agents, RAG and business automation.',
            'posts'        => $posts,
            'categories'   => (new BlogPost())->categories(),
            'search'       => $search,
        ], 'public');
    }

    public function category(string $category): void
    {
        $posts = (new BlogPost())->byCategory($category);
        if (count($posts) === 0) {
            $this->abort(404);
        }

        $this->view('public/blog/index', [
            'page_title'   => $category . ' — Blog — ' . SiteService::get('site_name'),
            'meta_description' => "Blog posts in the {$category} category.",
            'posts'        => $posts,
            'categories'   => (new BlogPost())->categories(),
            'search'       => '',
            'active_category' => $category,
        ], 'public');
    }

    public function show(string $slug): void
    {
        $post = (new BlogPost())->findPublishedBySlug($slug);
        if ($post === null) {
            $this->abort(404);
        }

        $this->view('public/blog/show', [
            'page_title'   => $post['title'] . ' — ' . SiteService::get('site_name'),
            'meta_description' => $post['excerpt'] ?? SiteService::get('meta_description'),
            'og_image'     => $post['cover_image'],
            'post'         => $post,
            'categories'   => (new BlogPost())->categories(),
            'related'      => $post['category'] ? array_slice(array_filter((new BlogPost())->byCategory($post['category']), fn ($p) => $p['id'] !== $post['id']), 0, 2) : [],
        ], 'public');
    }
}