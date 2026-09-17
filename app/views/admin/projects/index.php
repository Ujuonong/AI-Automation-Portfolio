<?php
/**
 * Projects index.
 * Vars: $projects, $pagination, $search, $type, $status, $sort, $dir, $statuses, $types
 */
$pqs = qs_params(['search' => $search, 'status' => $status, 'type' => $type, 'sort' => $sort, 'dir' => $dir]);
?>
<div class="toolbar">
    <form method="get" action="<?= e(url('/admin/projects')) ?>" style="display:flex;gap:10px;flex-wrap:wrap;">
        <input class="input" type="search" name="search" value="<?= e($search) ?>" placeholder="Search title or description...">
        <select class="select" name="type">
            <option value="">All types</option>
            <?php foreach ($types as $t): ?>
            <option value="<?= e($t) ?>" <?= $type === $t ? 'selected' : '' ?>><?= e(str_replace('_', ' ', ucwords($t, '_'))) ?></option>
            <?php endforeach; ?>
        </select>
        <select class="select" name="status">
            <option value="">All statuses</option>
            <?php foreach ($statuses as $s): ?>
            <option value="<?= e($s) ?>" <?= $status === $s ? 'selected' : '' ?>><?= e(str_replace('_', ' ', ucwords($s, '_'))) ?></option>
            <?php endforeach; ?>
        </select>
        <button class="btn btn--primary btn--sm" type="submit">Filter</button>
        <?php if ($search !== '' || $status !== '' || $type !== ''): ?><a class="btn btn--ghost btn--sm" href="<?= e(url('/admin/projects')) ?>">Clear</a><?php endif; ?>
    </form>
    <span class="spacer"></span>
    <a class="btn btn--primary" href="<?= e(url('/admin/projects/create')) ?>"><i class="fa-solid fa-plus"></i> New Project</a>
</div>

<div class="card" style="padding:0;">
    <div class="table-wrap">
        <table class="data">
            <thead>
                <tr>
                    <th>Project</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Published</th>
                    <th>Featured</th>
                    <th>Updated</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($projects as $p): ?>
            <tr>
                <td>
                    <a class="table-title" href="<?= e(url('/admin/projects/edit/' . $p['id'])) ?>">
                        <?php if ($p['cover_image']): ?><img class="table-title-cover" src="<?= e(upload_url($p['cover_image'])) ?>" alt=""><?php endif; ?>
                        <div>
                            <strong style="color:#fff;"><?= e($p['title']) ?></strong>
                            <div class="faint" style="font-size:.76rem;">/projects/<?= e($p['slug']) ?></div>
                        </div>
                    </a>
                </td>
                <td><span class="badge badge--gray"><?= e(str_replace('_', ' ', ucwords($p['project_type'], '_'))) ?></span></td>
                <td><span class="badge badge--<?= $p['status'] === 'completed' ? 'green' : ($p['status'] === 'in_progress' ? 'amber' : 'blue') ?>"><?= e(str_replace('_', ' ', $p['status'])) ?></span></td>
                <td><span class="badge badge--<?= $p['published'] ? 'green' : 'amber' ?>"><?= $p['published'] ? 'Live' : 'Draft' ?></span></td>
                <td><?= $p['featured'] ? '<span class="badge badge--blue">Featured</span>' : '—' ?></td>
                <td class="muted"><?= e(format_date((string) $p['updated_at'], 'M j, Y')) ?></td>
                <td style="text-align:right;white-space:nowrap;">
                    <div style="display:flex;gap:6px;justify-content:flex-end;">
                        <a class="btn btn--ghost btn--sm" href="<?= e(url('/projects/' . $p['slug'])) ?>" target="_blank" rel="noopener" title="View on site"><i class="fa-solid fa-eye"></i></a>
                        <form method="post" action="<?= e(url('/admin/projects/' . $p['id'] . '/toggle-published')) ?>"><?= $csrf ?>
                            <button class="btn btn--<?= $p['published'] ? 'success' : 'ghost' ?> btn--sm" type="submit" title="<?= $p['published'] ? 'Unpublish' : 'Publish' ?>"><i class="fa-solid fa-<?= $p['published'] ? 'cloud-arrow-down' : 'cloud-arrow-up' ?>"></i></button>
                        </form>
                        <form method="post" action="<?= e(url('/admin/projects/' . $p['id'] . '/toggle-featured')) ?>"><?= $csrf ?>
                            <button class="btn btn--<?= $p['featured'] ? 'blue' : 'ghost' ?> btn--sm" type="submit" title="<?= $p['featured'] ? 'Remove feature' : 'Feature' ?>"><i class="fa-solid fa-star"></i></button>
                        </form>
                        <form method="post" action="<?= e(url('/admin/projects/' . $p['id'] . '/duplicate')) ?>"><?= $csrf ?>
                            <button class="btn btn--ghost btn--sm" type="submit" title="Duplicate"><i class="fa-solid fa-copy"></i></button>
                        </form>
                        <form method="post" action="<?= e(url('/admin/projects/' . $p['id'] . '/delete')) ?>" data-confirm="Delete this project and all its media? This cannot be undone."><?= $csrf ?>
                            <button class="btn btn--danger btn--sm" type="submit" title="Delete"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (!$projects): ?>
            <tr><td colspan="7"><div class="empty"><i class="fa-regular fa-folder-open"></i>No projects found.</div></td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if ($pagination['total_pages'] > 1): ?>
<nav class="pagination">
    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
        <?php if ($i === (int) $pagination['page']): ?>
        <span class="current"><?= $i ?></span>
        <?php else: ?>
        <a href="<?= e(url('/admin/projects?page=' . $i . $pqs)) ?>"><?= $i ?></a>
        <?php endif; ?>
    <?php endfor; ?>
</nav>
<?php endif; ?>