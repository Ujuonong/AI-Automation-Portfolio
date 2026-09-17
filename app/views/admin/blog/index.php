<?php
/**
 * Blog posts index.
 * Vars: $posts, $pagination
 */
?>
<div class="toolbar">
    <span class="spacer"></span>
    <a class="btn btn--primary" href="<?= e(url('/admin/blog/create')) ?>"><i class="fa-solid fa-plus"></i> New Post</a>
</div>

<div class="card" style="padding:0;">
    <div class="table-wrap">
        <table class="data">
            <thead>
                <tr>
                    <th>Post</th>
                    <th>Category</th>
                    <th>Published</th>
                    <th>Publish date</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($posts as $post): ?>
            <tr>
                <td>
                    <div class="table-title">
                        <?php if ($post['cover_image']): ?><img class="table-title-cover" src="<?= e(upload_url($post['cover_image'])) ?>" alt=""><?php endif; ?>
                        <div>
                            <strong style="color:#fff;"><?= e($post['title']) ?></strong>
                            <div class="faint" style="font-size:.76rem;">/blog/<?= e($post['slug']) ?></div>
                        </div>
                    </div>
                </td>
                <td><span class="badge badge--gray"><?= e($post['category'] ?: '—') ?></span></td>
                <td><span class="badge badge--<?= $post['published'] ? 'green' : 'amber' ?>"><?= $post['published'] ? 'Live' : 'Draft' ?></span></td>
                <td class="muted"><?= e(format_date((string) $post['published_at'], 'M j, Y g:ia')) ?></td>
                <td style="text-align:right;white-space:nowrap;">
                    <div style="display:flex;gap:6px;justify-content:flex-end;">
                        <a class="btn btn--ghost btn--sm" href="<?= e(url('/blog/' . $post['slug'])) ?>" target="_blank" rel="noopener" title="View on site"><i class="fa-solid fa-eye"></i></a>
                        <a class="btn btn--ghost btn--sm" href="<?= e(url('/admin/blog/edit/' . $post['id'])) ?>"><i class="fa-solid fa-pen"></i> Edit</a>
                        <form method="post" action="<?= e(url('/admin/blog/' . $post['id'] . '/toggle-published')) ?>"><?= $csrf ?>
                            <button class="btn btn--<?= $post['published'] ? 'success' : 'ghost' ?> btn--sm" type="submit" title="Toggle publish"><i class="fa-solid fa-<?= $post['published'] ? 'cloud-arrow-down' : 'cloud-arrow-up' ?>"></i></button>
                        </form>
                        <form method="post" action="<?= e(url('/admin/blog/' . $post['id'] . '/delete')) ?>" data-confirm="Delete this post? This cannot be undone."><?= $csrf ?>
                            <button class="btn btn--danger btn--sm" type="submit" title="Delete"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (!$posts): ?>
            <tr><td colspan="5"><div class="empty"><i class="fa-regular fa-newspaper"></i>No posts yet.</div></td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if ($pagination['total_pages'] > 1): ?>
<nav class="pagination">
    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
        <?php if ($i === (int) $pagination['page']): ?><span class="current"><?= $i ?></span>
        <?php else: ?><a href="<?= e(url('/admin/blog?page=' . $i)) ?>"><?= $i ?></a><?php endif; ?>
    <?php endfor; ?>
</nav>
<?php endif; ?>