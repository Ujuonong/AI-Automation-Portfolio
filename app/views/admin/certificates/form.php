<?php
/**
 * Certificate create/edit form.
 * Vars: $certificate, $errors, $old
 */
use Portfolio\Core\Session;

$isEdit = $certificate !== null;
$c      = $certificate ?? [];
$errors = $errors ?? [];
$old    = $old ?? [];
$cv     = fn ($key, $fallback = '') => $isEdit ? ($c[$key] ?? $fallback) : ($old[$key] ?? $fallback);
?>
<form method="post" action="<?= e($isEdit ? url('/admin/certificates/' . $c['id']) : url('/admin/certificates')) ?>" enctype="multipart/form-data">
    <?= $csrf ?>
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;margin-bottom:18px;">
        <h1 class="mb-0"><?= $isEdit ? 'Edit certificate' : 'New certificate' ?></h1>
        <div style="display:flex;gap:8px;">
            <a class="btn btn--ghost" href="<?= e(url('/admin/certificates')) ?>">Cancel</a>
            <button class="btn btn--primary" type="submit"><?= $isEdit ? 'Save changes' : 'Create certificate' ?></button>
        </div>
    </div>

    <?php foreach (get_flash() as $fl): ?>
    <div class="alert alert--<?= e($fl['type'] === 'error' ? 'error' : 'success') ?>"><?= e($fl['message']) ?></div>
    <?php endforeach; ?>

    <?php if (!empty($errors)): ?>
    <div class="alert alert--error">
        <strong>Please fix the following:</strong>
        <ul style="margin-top:6px;margin-bottom:0;">
            <?php foreach ($errors as $err): ?><li><?= e(is_array($err) ? implode(' ', $err) : $err) ?></li><?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <div class="form-grid">
        <div class="field">
            <label for="title">Title *</label>
            <input class="input" id="title" name="title" type="text" value="<?= e($cv('title')) ?>" required maxlength="255">
        </div>
        <div class="field">
            <label for="issuer">Issuer *</label>
            <input class="input" id="issuer" name="issuer" type="text" value="<?= e($cv('issuer')) ?>" required maxlength="255">
        </div>
        <div class="field">
            <label for="issue_date">Issue date</label>
            <input class="input" id="issue_date" name="issue_date" type="date" value="<?= e($cv('issue_date')) ?>">
        </div>
        <div class="field">
            <label for="credential_id">Credential ID</label>
            <input class="input" id="credential_id" name="credential_id" type="text" value="<?= e($cv('credential_id')) ?>" maxlength="190">
        </div>
        <div class="field span-2">
            <label for="credential_url">Credential URL</label>
            <input class="input" id="credential_url" name="credential_url" type="url" value="<?= e($cv('credential_url')) ?>" maxlength="500">
        </div>
        <div class="field span-2">
            <label for="description">Description</label>
            <textarea class="input" id="description" name="description" rows="4"><?= e($cv('description')) ?></textarea>
        </div>

        <div class="field">
            <label for="thumbnail">Certificate image</label>
            <?php if (isset($errors['thumbnail'])): ?><div class="field-error mb-1"><?= e($errors['thumbnail']) ?></div><?php endif; ?>
            <?php if ($cv('thumbnail')): ?>
            <img src="<?= e(upload_url($cv('thumbnail'))) ?>" alt="" style="max-width:220px;border-radius:10px;margin-bottom:10px;">
            <?php endif; ?>
            <input class="input" type="file" id="thumbnail" name="thumbnail" accept="image/*">
        </div>
        <div class="field">
            <label for="certificate_file">Certificate PDF</label>
            <?php if (isset($errors['certificate_file'])): ?><div class="field-error mb-1"><?= e($errors['certificate_file']) ?></div><?php endif; ?>
            <?php if ($cv('certificate_file')): ?>
            <div class="mb-1"><a href="<?= e(upload_url($cv('certificate_file'))) ?>" target="_blank" rel="noopener" class="btn btn--ghost btn--sm"><i class="fa-solid fa-file-pdf"></i> Current PDF</a></div>
            <?php endif; ?>
            <input class="input" type="file" id="certificate_file" name="certificate_file" accept="application/pdf">
        </div>
    </div>

    <div class="card">
        <h3>Visibility</h3>
        <label class="check"><input type="checkbox" name="published" <?= $cv('published', 1) ? 'checked' : '' ?>> Published (visible to visitors)</label>
        <label class="check"><input type="checkbox" name="featured" <?= $cv('featured') ? 'checked' : '' ?>> Featured (highlighted on home page)</label>
    </div>

    <div style="display:flex;gap:10px;justify-content:flex-end;">
        <a class="btn btn--ghost" href="<?= e(url('/admin/certificates')) ?>">Cancel</a>
        <button class="btn btn--primary" type="submit"><?= $isEdit ? 'Save changes' : 'Create certificate' ?></button>
    </div>
</form>