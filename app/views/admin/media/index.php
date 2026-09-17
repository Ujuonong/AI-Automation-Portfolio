<?php
/**
 * Media library index.
 * Vars: $media, $pagination, $max_size_mb
 */
$isImage = fn (array $m) => $m['media_type'] === 'image';
?>
<div class="toolbar">
    <form method="post" action="<?= e(url('/admin/media')) ?>" enctype="multipart/form-data">
        <?= $csrf ?>
        <input class="input" type="file" name="uploads[]" multiple accept="image/*,.pdf,.doc,.docx,.txt">
        <button class="btn btn--primary btn--sm" type="submit"><i class="fa-solid fa-upload"></i> Upload</button>
        <small class="field-hint">Max <?= (int) $max_size_mb ?>MB per file. Images (jpg, jpeg, png, webp, gif) and documents (pdf, doc, docx, txt).</small>
    </form>
</div>

<?php foreach (get_flash() as $fl): ?>
<div class="alert alert--<?= e($fl['type'] === 'error' ? 'error' : 'success') ?>"><?= e($fl['message']) ?></div>
<?php endforeach; ?>

<?php if ($media): ?>
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(170px,1fr));gap:14px;">
    <?php foreach ($media as $m): ?>
    <div class="card media-card" style="padding:0;overflow:hidden;">
        <?php if ($isImage($m)): ?>
            <a href="<?= e(upload_url($m['stored_path'])) ?>" target="_blank" rel="noopener">
                <img src="<?= e(upload_url($m['stored_path'])) ?>" alt="<?= e($m['original_name']) ?>" style="width:100%;height:130px;object-fit:cover;display:block;">
            </a>
        <?php else: ?>
            <a href="<?= e(upload_url($m['stored_path'])) ?>" target="_blank" rel="noopener" class="media-file">
                <i class="fa-solid fa-file"></i>
            </a>
        <?php endif; ?>
        <div style="padding:10px;">
            <div style="display:flex;justify-content:space-between;align-items:center;gap:8px;">
                <div style="min-width:0;">
                    <div class="muted" style="font-size:.8rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= e($m['original_name']) ?></div>
                    <div class="faint" style="font-size:.72rem;"><?= e(format_date((string) $m['created_at'], 'M j, Y')) ?></div>
                </div>
                <form method="post" action="<?= e(url('/admin/media/' . $m['id'] . '/delete')) ?>" data-confirm="Delete this file?"><?= $csrf ?>
                    <button class="btn btn--danger btn--sm" type="submit" title="Delete"><i class="fa-solid fa-trash"></i></button>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php if ($pagination['total_pages'] > 1): ?>
<nav class="pagination">
    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
        <?php if ($i === (int) $pagination['page']): ?><span class="current"><?= $i ?></span>
        <?php else: ?><a href="<?= e(url('/admin/media?page=' . $i)) ?>"><?= $i ?></a><?php endif; ?>
    <?php endfor; ?>
</nav>
<?php endif; ?>

<?php else: ?>
<div class="empty"><i class="fa-regular fa-image"></i>No files in the library yet.</div>
<?php endif; ?>