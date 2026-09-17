<?php
/**
 * Messages index.
 * Vars: $messages, $pagination, $current_status, $statuses, $enquiry_types
 */
?>
<div class="toolbar">
    <form method="get" action="<?= e(url('/admin/messages')) ?>" style="display:flex;gap:10px;">
        <select class="select" name="status">
            <option value="">All statuses</option>
            <?php foreach ($statuses as $s): ?>
            <option value="<?= e($s) ?>" <?= $current_status === $s ? 'selected' : '' ?>><?= e(ucfirst($s)) ?></option>
            <?php endforeach; ?>
        </select>
        <button class="btn btn--primary btn--sm" type="submit">Filter</button>
        <?php if ($current_status !== ''): ?><a class="btn btn--ghost btn--sm" href="<?= e(url('/admin/messages')) ?>">Clear</a><?php endif; ?>
    </form>
    <span class="spacer"></span>
</div>

<div class="card" style="padding:0;">
    <div class="table-wrap">
        <table class="data">
            <thead>
                <tr>
                    <th style="width:30px;"></th>
                    <th>From</th>
                    <th>Enquiry</th>
                    <th>Status</th>
                    <th>Received</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($messages as $m): ?>
            <tr>
                <td>
                    <?php if ($m['status'] === 'new'): ?><span class="badge dot-dot badge--blue" title="Unread"></span>
                    <?php else: ?><span class="badge dot-dot badge--gray" title="Read"></span><?php endif; ?>
                </td>
                <td>
                    <div>
                        <strong style="color:#fff;"><?= e($m['name']) ?></strong>
                        <div class="faint" style="font-size:.76rem;"><?= e($m['email']) ?></div>
                        <?php if (!empty($m['phone'])): ?><div class="faint" style="font-size:.76rem;"><?= e($m['phone']) ?></div><?php endif; ?>
                    </div>
                </td>
                <td class="muted" style="max-width:300px;">
                    <?php
                    $enq = $m['enquiry_type'] ?? '';
                    $enqLabel = ($enq && isset($enquiry_types[$enq])) ? $enquiry_types[$enq] : ($m['subject'] ?: '—');
                    ?>
                    <div><?= e($enqLabel) ?></div>
                    <?php if (!empty($m['preferred_audit_date'])): ?>
                    <div class="faint" style="font-size:.76rem;margin-top:2px;">
                        Audit: <?= e(format_date((string) $m['preferred_audit_date'], 'M j, Y')) ?><?= !empty($m['preferred_audit_time']) ? ' ' . e($m['preferred_audit_time']) : '' ?>
                    </div>
                    <?php endif; ?>
                </td>
                <td><span class="badge badge--<?= $m['status'] === 'new' ? 'blue' : ($m['status'] === 'archived' ? 'gray' : 'green') ?>"><?= e(ucfirst($m['status'])) ?></span></td>
                <td class="muted"><?= e(format_date((string) $m['created_at'], 'M j, Y g:ia')) ?></td>
                <td style="text-align:right;white-space:nowrap;">
                    <div style="display:flex;gap:6px;justify-content:flex-end;">
                        <a class="btn btn--ghost btn--sm" href="<?= e(url('/admin/messages/' . $m['id'])) ?>"><i class="fa-solid fa-eye"></i> View</a>
                        <form method="post" action="<?= e(url('/admin/messages/' . $m['id'] . '/status')) ?>"><?= $csrf ?>
                            <input type="hidden" name="status" value="<?= $m['status'] === 'archived' ? 'new' : 'archived' ?>">
                            <button class="btn btn--ghost btn--sm" type="submit" title="<?= $m['status'] === 'archived' ? 'Mark as new' : 'Archive' ?>"><i class="fa-solid fa-box-archive"></i></button>
                        </form>
                        <form method="post" action="<?= e(url('/admin/messages/' . $m['id'] . '/delete')) ?>" data-confirm="Delete this message?"><?= $csrf ?>
                            <button class="btn btn--danger btn--sm" type="submit" title="Delete"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (!$messages): ?>
            <tr><td colspan="6"><div class="empty"><i class="fa-regular fa-envelope-open"></i>No messages found.</div></td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if ($pagination['total_pages'] > 1): ?>
<nav class="pagination">
    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
        <?php if ($i === (int) $pagination['page']): ?><span class="current"><?= $i ?></span>
        <?php else: ?><a href="<?= e(url('/admin/messages?page=' . $i . ($current_status !== '' ? '&status=' . urlencode($current_status) : ''))) ?>"><?= $i ?></a><?php endif; ?>
    <?php endfor; ?>
</nav>
<?php endif; ?>
