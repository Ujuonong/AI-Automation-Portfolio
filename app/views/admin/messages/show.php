<?php
/**
 * Message detail.
 * Vars: $message, $enquiry_types
 */
$m = $message;
$enq = $m['enquiry_type'] ?? '';
$enqLabel = ($enq && isset($enquiry_types[$enq])) ? $enquiry_types[$enq] : ($m['subject'] ?: '(no subject)');
?>
<div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;margin-bottom:18px;">
    <a class="btn btn--ghost" href="<?= e(url('/admin/messages')) ?>"><i class="fa-solid fa-arrow-left"></i> Back</a>
    <div style="display:flex;gap:8px;">
        <form method="post" action="<?= e(url('/admin/messages/' . $m['id'] . '/status')) ?>"><?= $csrf ?>
            <input type="hidden" name="status" value="archived">
            <button class="btn btn--ghost" type="submit"><i class="fa-solid fa-box-archive"></i> Archive</button>
        </form>
        <form method="post" action="<?= e(url('/admin/messages/' . $m['id'] . '/delete')) ?>" data-confirm="Delete this message?"><?= $csrf ?>
            <button class="btn btn--danger" type="submit"><i class="fa-solid fa-trash"></i> Delete</button>
        </form>
    </div>
</div>

<div class="card">
    <div style="display:flex;justify-content:space-between;gap:16px;flex-wrap:wrap;border-bottom:1px solid var(--border);padding-bottom:16px;margin-bottom:16px;">
        <div>
            <h1 class="mb-0" style="font-size:1.4rem;"><?= e($enqLabel) ?></h1>
            <div class="muted" style="margin-top:6px;">
                From <strong style="color:#fff;"><?= e($m['name']) ?></strong> &lt;<?= e($m['email']) ?>&gt;
                <?php if (!empty($m['phone'])): ?> · <?= e($m['phone']) ?><?php endif; ?>
            </div>
            <div class="faint" style="font-size:.8rem;">Received <?= e(format_date((string) $m['created_at'], 'M j, Y g:ia')) ?></div>
        </div>
        <span class="badge badge--<?= $m['status'] === 'new' ? 'blue' : ($m['status'] === 'archived' ? 'gray' : 'green') ?>"><?= e(ucfirst($m['status'])) ?></span>
    </div>

    <?php if (!empty($m['company']) || !empty($m['preferred_audit_date'])): ?>
    <div style="display:flex;flex-wrap:wrap;gap:16px;margin-bottom:16px;">
        <?php if (!empty($m['company'])): ?>
        <div>
            <div class="faint" style="font-size:.76rem;">COMPANY</div>
            <div><?= e($m['company']) ?></div>
        </div>
        <?php endif; ?>
        <?php if (!empty($m['preferred_audit_date'])): ?>
        <div>
            <div class="faint" style="font-size:.76rem;">PREFERRED AUDIT</div>
            <div><?= e(format_date((string) $m['preferred_audit_date'], 'M j, Y')) ?><?= !empty($m['preferred_audit_time']) ? ' · ' . e($m['preferred_audit_time']) : '' ?></div>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div style="white-space:pre-wrap;line-height:1.7;"><?= e($m['message']) ?></div>

    <?php if (!empty($m['additional_info'])): ?>
    <div style="margin-top:16px;border-top:1px solid var(--border);padding-top:16px;">
        <div class="faint" style="font-size:.76rem;margin-bottom:6px;">ADDITIONAL INFORMATION</div>
        <div style="white-space:pre-wrap;line-height:1.7;"><?= e($m['additional_info']) ?></div>
    </div>
    <?php endif; ?>
</div>

<div class="card">
    <h2 style="font-size:1rem;">Reply</h2>
    <p class="muted">Open your email client to reply to this message:</p>
    <a class="btn btn--primary"
       href="mailto:<?= e($m['email']) ?>?subject=<?= e(rawurlencode('Re: ' . $enqLabel)) ?>"
       target="_blank" rel="noopener">
        <i class="fa-solid fa-reply"></i> Reply via email
    </a>
</div>