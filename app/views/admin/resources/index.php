<?php
/**
 * Generic resource index (services, skills, experience, education, testimonials).
 * Vars: $items, $pagination, $resource
 */
$r = $resource;
?>
<div class="toolbar">
    <span class="spacer"></span>
    <a class="btn btn--primary" href="<?= e(url('/admin/' . $r['route'] . '/create')) ?>"><i class="fa-solid fa-plus"></i> New <?= e(rtrim($r['label'], 's')) ?></a>
</div>

<div class="card" style="padding:0;">
    <div class="table-wrap">
        <table class="data">
            <thead>
                <tr>
                    <th><?= e(ucfirst($r['fields'][array_key_first($r['fields'])]['label'])) ?></th>
                    <?php foreach (array_slice($r['fields'], 1) as $field => $config): ?>
                        <?php if ($config['type'] === 'checkbox' || $config['type'] === 'image') { continue; } ?>
                        <th><?= e($config['label']) ?></th>
                    <?php endforeach; ?>
                    <th>Published</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><strong style="color:#fff;"><?= e($item[array_key_first($r['fields'])]) ?></strong></td>
                <?php foreach (array_slice($r['fields'], 1) as $field => $config): ?>
                    <?php if ($config['type'] === 'checkbox' || $config['type'] === 'image') { continue; } ?>
                    <td class="muted" style="max-width:260px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= e((string) ($item[$field] ?? '')) ?></td>
                <?php endforeach; ?>
                <td><span class="badge badge--<?= $item['published'] ? 'green' : 'amber' ?>"><?= $item['published'] ? 'Live' : 'Draft' ?></span></td>
                <td style="text-align:right;white-space:nowrap;">
                    <div style="display:flex;gap:6px;justify-content:flex-end;">
                        <a class="btn btn--ghost btn--sm" href="<?= e(url('/admin/' . $r['route'] . '/edit/' . $item['id'])) ?>"><i class="fa-solid fa-pen"></i> Edit</a>
                        <form method="post" action="<?= e(url('/admin/' . $r['route'] . '/' . $item['id'] . '/toggle-published')) ?>"><?= $csrf ?>
                            <button class="btn btn--<?= $item['published'] ? 'success' : 'ghost' ?> btn--sm" type="submit" title="Toggle publish"><i class="fa-solid fa-<?= $item['published'] ? 'cloud-arrow-down' : 'cloud-arrow-up' ?>"></i></button>
                        </form>
                        <form method="post" action="<?= e(url('/admin/' . $r['route'] . '/' . $item['id'] . '/delete')) ?>" data-confirm="Delete this item? This cannot be undone."><?= $csrf ?>
                            <button class="btn btn--danger btn--sm" type="submit" title="Delete"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (!$items): ?>
            <tr><td colspan="8"><div class="empty"><i class="fa-regular fa-folder-open"></i>No <?= e(strtolower($r['label'])) ?> yet.</div></td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if ($pagination['total_pages'] > 1): ?>
<nav class="pagination">
    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
        <?php if ($i === (int) $pagination['page']): ?><span class="current"><?= $i ?></span>
        <?php else: ?><a href="<?= e(url('/admin/' . $r['route'] . '?page=' . $i)) ?>"><?= $i ?></a><?php endif; ?>
    <?php endfor; ?>
</nav>
<?php endif; ?>