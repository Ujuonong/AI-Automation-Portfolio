<?php

declare(strict_types=1);

namespace Portfolio\Controllers\Admin;

use Portfolio\Models\BlogPost;
use Portfolio\Services\SlugService;
use Portfolio\Services\UploadService;
use Portfolio\Services\Validator;

final class BlogController extends AdminBaseController
{
    public function index(): void
    {
        $results = (new BlogPost())->paginate(
            max(1, (int) $this->request->query('page', 1)),
            10,
            '',
            [],
            'created_at',
            'DESC'
        );

        $this->view('admin/blog/index', [
            'sidebar_active' => 'blog',
            'page_title'     => 'Blog posts',
            'posts'          => $results['items'],
            'pagination'     => $results,
        ]);
    }

    public function create(): void
    {
        $this->view('admin/blog/form', [
            'sidebar_active' => 'blog',
            'page_title'     => 'New Post',
            'post'           => null,
            'categories'     => $this->existingCategories(),
        ]);
    }

    public function store(): void
    {
        $input = $this->request->all();

        $validator = (new Validator($input))
            ->required('title', 'title')
            ->maxLength('title', 'title', 255)
            ->required('slug', 'slug')
            ->required('content', 'content')
            ->date('published_at', 'publish date');

        $uploader = new UploadService();
        $cover = null;

        $file = $this->request->file('cover_image');
        if ($file !== null) {
            try {
                $cover = $uploader->uploadImage($file, 'blog', 'post');
            } catch (\RuntimeException $e) {
                $validator->addError('cover_image', $e->getMessage());
            }
        }

        if ($validator->passes()) {
            try {
                $slug = SlugService::ensureUnique('blog_posts', slugify((string) $input['slug']));

                $published = isset($input['published']) ? 1 : 0;
                $publishedAt = trim((string) ($input['published_at'] ?? ''));
                $publishedAt = $publishedAt !== '' && strtotime($publishedAt) ? date('Y-m-d H:i:s', strtotime($publishedAt)) : null;
                if ($publishedAt === null) {
                    $publishedAt = date('Y-m-d H:i:s');
                }

                $id = (new BlogPost())->create([
                    'title'        => $input['title'],
                    'slug'         => $slug,
                    'excerpt'      => trim((string) ($input['excerpt'] ?? '')),
                    'content'      => $input['content'],
                    'cover_image'  => $cover,
                    'category'     => $input['category'] ?? '',
                    'published'    => $published,
                    'published_at' => $publishedAt,
                ]);

                flash('success', 'Post created.');
                $this->redirect('/admin/blog/edit/' . $id);
            } catch (\Throwable $e) {
                if ($cover) {
                    $uploader->delete($cover);
                }
                error_log('[Blog] Store error: ' . $e->getMessage());
                flash('error', 'Could not save the post.');
            }
        }

        $this->view('admin/blog/form', [
            'sidebar_active' => 'blog',
            'page_title'     => 'New Post',
            'post'           => null,
            'categories'     => $this->existingCategories(),
            'errors'         => $validator->errors(),
            'old'            => $input,
        ]);
    }

    public function edit(string $id): void
    {
        $post = (new BlogPost())->find((int) $id);
        if ($post === null) {
            $this->abort(404);
        }

        $this->view('admin/blog/form', [
            'sidebar_active' => 'blog',
            'page_title'     => 'Edit Post',
            'post'           => $post,
            'categories'     => $this->existingCategories(),
        ]);
    }

    public function update(string $id): void
    {
        $postId = (int) $id;
        $post = (new BlogPost())->find($postId);
        if ($post === null) {
            $this->abort(404);
        }

        $input = $this->request->all();

        $validator = (new Validator($input))
            ->required('title', 'title')
            ->required('slug', 'slug')
            ->required('content', 'content')
            ->date('published_at', 'publish date');

        $uploader = new UploadService();
        $cover = $post['cover_image'];
        $coverError = null;

        $file = $this->request->file('cover_image');
        if ($file !== null) {
            try {
                $new = $uploader->uploadImage($file, 'blog', 'post');
                if ($post['cover_image']) {
                    $uploader->delete($post['cover_image']);
                }
                $cover = $new;
            } catch (\RuntimeException $e) {
                $coverError = $e->getMessage();
            }
        }

        if ($coverError !== null) {
            $validator->addError('cover_image', $coverError);
        }

        if ($validator->passes()) {
            try {
                $slug = SlugService::ensureUnique('blog_posts', slugify((string) $input['slug']), $postId);
                $publishedAt = trim((string) ($input['published_at'] ?? ''));
                $publishedAt = $publishedAt !== '' && strtotime($publishedAt) ? date('Y-m-d H:i:s', strtotime($publishedAt)) : null;

                (new BlogPost())->update($postId, [
                    'title'        => $input['title'],
                    'slug'         => $slug,
                    'excerpt'      => trim((string) ($input['excerpt'] ?? '')),
                    'content'      => $input['content'],
                    'cover_image'  => $cover,
                    'category'     => $input['category'] ?? '',
                    'published'    => isset($input['published']) ? 1 : 0,
                    'published_at' => $publishedAt,
                ]);

                flash('success', 'Post updated.');
                $this->redirect('/admin/blog/edit/' . $postId);
            } catch (\Throwable $e) {
                error_log('[Blog] Update error: ' . $e->getMessage());
                flash('error', 'Could not update the post.');
                $this->back('/admin/blog');
            }
        }

        $this->view('admin/blog/form', [
            'sidebar_active' => 'blog',
            'page_title'     => 'Edit Post',
            'post'           => $post,
            'categories'     => $this->existingCategories(),
            'errors'         => $validator->errors(),
            'old'            => $input,
        ]);
    }

    public function destroy(string $id): void
    {
        $post = (new BlogPost())->find((int) $id);
        if ($post !== null) {
            if ($post['cover_image']) {
                (new UploadService())->delete($post['cover_image']);
            }
            (new BlogPost())->delete((int) $id);
            flash('success', 'Post deleted.');
        }
        $this->redirect('/admin/blog');
    }

    public function togglePublished(string $id): void
    {
        (new BlogPost())->togglePublished((int) $id);
        flash('success', 'Post publish status updated.');
        $this->back('/admin/blog');
    }

    private function existingCategories(): array
    {
        $db = \Portfolio\Core\Database::connection();
        $rows = $db->query("SELECT DISTINCT category FROM blog_posts WHERE category <> '' ORDER BY category")->fetchAll();
        return array_map(fn ($r) => $r['category'], $rows);
    }
}