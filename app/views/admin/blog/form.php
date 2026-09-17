<?php
/**
 * Blog post create/edit form.
 * Vars: $post, $categories, $errors, $old
 */
use Portfolio\Core\Session;

$isEdit = $post !== null;
$p      = $post ?? [];
$errors = $errors ?? [];
$old    = $old ?? [];
$cv     = fn ($key, $fallback = '') => $isEdit ? ($p[$key] ?? $fallback) : ($old[$key] ?? $fallback);
?>
<form method="post" action="<?= e($isEdit ? url('/admin/blog/' . $p['id']) : url('/admin/blog')) ?>" enctype="multipart/form-data">
    <?= $csrf ?>
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;margin-bottom:18px;">
        <div>
            <h1 class="mb-0"><?= $isEdit ? 'Edit post' : 'New post' ?></h1>
            <?php if ($isEdit): ?><a class="faint" style="font-size:.82rem;" href="<?= e(url('/blog/' . $p['slug'])) ?>" target="_blank" rel="noopener">View on site →</a><?php endif; ?>
        </div>
        <div style="display:flex;gap:8px;">
            <a class="btn btn--ghost" href="<?= e(url('/admin/blog')) ?>">Cancel</a>
            <button class="btn btn--primary" type="submit"><?= $isEdit ? 'Save changes' : 'Create post' ?></button>
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
        <div class="field span-2">
            <label for="title">Title *</label>
            <input class="input" id="title" name="title" type="text" value="<?= e($cv('title')) ?>" required maxlength="255">
        </div>
        <div class="field">
            <label for="slug">Slug <small class="field-hint">e.g. building-ai-automation-agents</small></label>
            <input class="input" id="slug" name="slug" type="text" value="<?= e($cv('slug')) ?>" required maxlength="255">
        </div>
        <div class="field">
            <label for="category">Category</label>
            <input class="input" id="category" name="category" type="text" value="<?= e($cv('category')) ?>" list="category-suggestions" maxlength="190" placeholder="AI, Automation, Career...">
            <datalist id="category-suggestions">
                <?php foreach ($categories as $cat): ?><option value="<?= e($cat) ?>"></option><?php endforeach; ?>
            </datalist>
        </div>
        <div class="field span-2">
            <label for="excerpt">Excerpt</label>
            <textarea class="input" id="excerpt" name="excerpt" rows="2"><?= e($cv('excerpt')) ?></textarea>
        </div>
        <div class="field span-2">
            <label for="content">Content *</label>
            <textarea class="input" id="content" name="content" rows="14" required><?= e($cv('content')) ?></textarea>
            <small class="field-hint">Plain text with one blank line between paragraphs. Lines starting with ## become headings.</small>
        </div>
    </div>

    <div class="card form-grid">
        <div class="field">
            <label>Cover image</label>
            <?php if (isset($errors['cover_image'])): ?><div class="field-error mb-1"><?= e($errors['cover_image']) ?></div><?php endif; ?>
            <?php if ($cv('cover_image')): ?>
            <img src="<?= e(upload_url($cv('cover_image'))) ?>" alt="" style="max-width:280px;border-radius:10px;margin-bottom:10px;">
            <?php endif; ?>
            <input class="input" type="file" name="cover_image" accept="image/*">
        </div>
        <div class="field">
            <label for="published_at">Publish date</label>
            <input class="input" id="published_at" name="published_at" type="datetime-local" value="<?= e($cv('published_at')) ?>">
        </div>
    </div>

    <div class="card">
        <label class="check">
            <input type="checkbox" name="published" <?= $cv('published', 1) ? 'checked' : '' ?>>
            Published (visible to visitors)
        </label>
    </div>

    <div style="display:flex;gap:10px;justify-content:flex-end;">
        <a class="btn btn--ghost" href="<?= e(url('/admin/blog')) ?>">Cancel</a>
        <button class="btn btn--primary" type="submit"><?= $isEdit ? 'Save changes' : 'Create post' ?></button>
    </div>
</form>