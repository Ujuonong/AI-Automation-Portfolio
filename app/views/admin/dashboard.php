<?php
/**
 * Admin dashboard.
 * Vars: $stats, $recent_projects, $recent_messages, $recent_posts
 */
?>
<div class="grid grid--4">
    <div class="card stat-card"><i class="fa-solid fa-layer-group stat-icon"></i><b><?= (int) $stats['total_projects'] ?></b><span>Total projects</span></div>
    <div class="card stat-card" style="border-left:3px solid var(--success);"><i class="fa-solid fa-check stat-icon" style="color:var(--success);"></i><b><?= (int) $stats['published_projects'] ?></b><span>Published</span></div>
    <div class="card stat-card" style="border-left:3px solid var(--warning);"><i class="fa-solid fa-pen stat-icon" style="color:var(--warning);"></i><b><?= (int) $stats['draft_projects'] ?></b><span>Drafts</span></div>
    <div class="card stat-card"><i class="fa-solid fa-award stat-icon"></i><b><?= (int) $stats['certificates'] ?></b><span>Certificates</span></div>
    <div class="card stat-card"><i class="fa-solid fa-blog stat-icon"></i><b><?= (int) $stats['blog_posts'] ?></b><span>Blog posts</span></div>
    <div class="card stat-card" style="border-left:3px solid var(--danger);"><i class="fa-solid fa-envelope stat-icon" style="color:var(--danger);"></i><b><?= (int) $stats['unread_messages'] ?></b><span>Unread messages</span></div>
    <div class="card stat-card"><i class="fa-solid fa-star stat-icon"></i><b><?= (int) $stats['featured_projects'] ?></b><span>Featured</span></div>
    <div class="card stat-card"><i class="fa-solid fa-inbox stat-icon"></i><b><?= (int) $stats['total_messages'] ?></b><span>Total messages</span></div>
</div>

<div class="grid" style="grid-template-columns: 1.6fr 1fr;">
    <div>
        <div class="card">
            <div class="table-title" style="justify-content:space-between;align-items:center;">
                <h2>Recent projects</h2>
                <a class="btn btn--primary btn--sm" href="<?= e(url('/admin/projects/create')) ?>"><i class="fa-solid fa-plus"></i> New</a>
            </div>
            <div class="table-wrap mt-2">
                <table class="data">
                    <thead><tr><th>Title</th><th>Status</th><th>Published</th></tr></thead>
                    <tbody>
                    <?php foreach ($recent_projects as $p): ?>
                    <tr>
                        <td><a class="table-title" href="<?= e(url('/admin/projects/edit/' . $p['id'])) ?>">
                            <?php if ($p['cover_image']): ?><img class="table-title-cover" src="<?= e(upload_url($p['cover_image'])) ?>" alt=""><?php endif; ?>
                            <span><?= e($p['title']) ?></span></a>
                        </td>
                        <td><span class="badge badge--gray"><?= e(str_replace('_', ' ', $p['status'])) ?></span></td>
                        <td><?= $p['published'] ? '<span class="badge badge--green">Live</span>' : '<span class="badge badge--amber">Draft</span>' ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (!$recent_projects): ?><tr><td colspan="3" class="faint">No projects yet.</td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="table-title" style="justify-content:space-between;align-items:center;">
                <h2>Recent posts</h2>
                <a class="btn btn--ghost btn--sm" href="<?= e(url('/admin/blog/create')) ?>"><i class="fa-solid fa-plus"></i> New</a>
            </div>
            <div class="table-wrap mt-2">
                <table class="data">
                    <thead><tr><th>Title</th><th>Category</th><th>Status</th></tr></thead>
                    <tbody>
                    <?php foreach ($recent_posts as $p): ?>
                    <tr>
                        <td><a href="<?= e(url('/admin/blog/edit/' . $p['id'])) ?>"><?= e($p['title']) ?></a></td>
                        <td class="muted"><?= e($p['category'] ?: '—') ?></td>
                        <td><?= $p['published'] ? '<span class="badge badge--green">Live</span>' : '<span class="badge badge--amber">Draft</span>' ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (!$recent_posts): ?><tr><td colspan="3" class="faint">No posts yet.</td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div>
        <div class="card">
            <div class="table-title" style="justify-content:space-between;align-items:center;">
                <h2>Latest messages</h2>
                <a class="btn btn--ghost btn--sm" href="<?= e(url('/admin/messages')) ?>">View all</a>
            </div>
            <div style="display:grid;gap:12px;margin-top:12px;">
                <?php foreach ($recent_messages as $m): ?>
                <a href="<?= e(url('/admin/messages/' . $m['id'])) ?>" style="display:block;border:1px solid var(--border);border-radius:10px;padding:12px;color:var(--text);">
                    <div style="display:flex;justify-content:space-between;gap:10px;">
                        <strong><?= e($m['name']) ?></strong>
                        <span class="badge badge--<?= $m['status'] === 'new' ? 'blue' : 'gray' ?>"><?= e($m['status']) ?></span>
                    </div>
                    <div class="muted" style="font-size:.88rem;"><?= e(truncate((string) $m['subject'], 60)) ?></div>
                    <div class="faint" style="font-size:.78rem;"><?= e(format_date((string) $m['created_at'], 'M j, Y g:ia')) ?></div>
                </a>
                <?php endforeach; ?>
                <?php if (!$recent_messages): ?><p class="muted">No messages yet.</p><?php endif; ?>
            </div>
        </div>

        <div class="card">
            <h2>Quick actions</h2>
            <div style="display:grid;gap:8px;">
                <a class="btn btn--ghost" href="<?= e(url('/admin/projects/create')) ?>"><i class="fa-solid fa-plus"></i> Add project</a>
                <a class="btn btn--ghost" href="<?= e(url('/admin/certificates/create')) ?>"><i class="fa-solid fa-plus"></i> Add certificate</a>
                <a class="btn btn--ghost" href="<?= e(url('/admin/blog/create')) ?>"><i class="fa-solid fa-plus"></i> Write blog post</a>
                <a class="btn btn--ghost" href="<?= e(url('/admin/settings')) ?>"><i class="fa-solid fa-gear"></i> Manage settings</a>
            </div>
        </div>
    </div>
</div>