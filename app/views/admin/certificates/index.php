<?php
/**
 * Certificates index.
 * Vars: $certificates, $pagination
 */
?>
<div class="toolbar">
    <span class="spacer"></span>
    <a class="btn btn--primary" href="<?= e(url('/admin/certificates/create')) ?>"><i class="fa-solid fa-plus"></i> New Certificate</a>
</div>

<div class="card" style="padding:0;">
    <div class="table-wrap">
        <table class="data">
            <thead>
                <tr>
                    <th>Certificate</th>
                    <th>Issued</th>
                    <th>Published</th>
                    <th>Featured</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($certificates as $c): ?>
            <tr>
                <td>
                    <div class="table-title">
                        <?php if ($c['thumbnail']): ?><img class="table-title-cover" src="<?= e(upload_url($c['thumbnail'])) ?>" alt=""><?php endif; ?>
                        <div>
                            <strong style="color:#fff;"><?= e($c['title']) ?></strong>
                            <div class="faint" style="font-size:.76rem;"><?= e($c['issuer']) ?></div>
                        </div>
                    </div>
                </td>
                <td class="muted"><?= e(format_date((string) $c['issue_date'])) ?></td>
                <td><span class="badge badge--<?= $c['published'] ? 'green' : 'amber' ?>"><?= $c['published'] ? 'Live' : 'Draft' ?></span></td>
                <td><?= $c['featured'] ? '<span class="badge badge--blue">Featured</span>' : '—' ?></td>
                <td style="text-align:right;white-space:nowrap;">
                    <div style="display:flex;gap:6px;justify-content:flex-end;">
                        <a class="btn btn--ghost btn--sm" href="<?= e(url('/admin/certificates/edit/' . $c['id'])) ?>"><i class="fa-solid fa-pen"></i> Edit</a>
                        <form method="post" action="<?= e(url('/admin/certificates/' . $c['id'] . '/toggle-published')) ?>"><?= $csrf ?>
                            <button class="btn btn--<?= $c['published'] ? 'success' : 'ghost' ?> btn--sm" type="submit" title="<?= $c['published'] ? 'Unpublish' : 'Publish' ?>"><i class="fa-solid fa-<?= $c['published'] ? 'cloud-arrow-down' : 'cloud-arrow-up' ?>"></i></button>
                        </form>
                        <form method="post" action="<?= e(url('/admin/certificates/' . $c['id'] . '/toggle-featured')) ?>"><?= $csrf ?>
                            <button class="btn btn--<?= $c['featured'] ? 'blue' : 'ghost' ?> btn--sm" type="submit" title="Feature"><i class="fa-solid fa-star"></i></button>
                        </form>
                        <form method="post" action="<?= e(url('/admin/certificates/' . $c['id'] . '/delete')) ?>" data-confirm="Delete this certificate and its files?">
                            <?= $csrf ?>
                            <button class="btn btn--danger btn--sm" type="submit" title="Delete"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (!$certificates): ?>
            <tr><td colspan="5"><div class="empty"><i class="fa-regular fa-folder-open"></i>No certificates yet.</div></td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if ($pagination['total_pages'] > 1): ?>
<nav class="pagination">
    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
        <?php if ($i === (int) $pagination['page']): ?><span class="current"><?= $i ?></span>
        <?php else: ?><a href="<?= e(url('/admin/certificates?page=' . $i)) ?>"><?= $i ?></a><?php endif; ?>
    <?php endfor; ?>
</nav>
<?php endif; ?>