<?php
/**
 * Project create/edit form.
 * Vars: $project, $technologies, $project_tech, $media, $statuses, $types, $errors, $old
 */
use Portfolio\Core\Session;

$isEdit  = $project !== null;
$p       = $project ?? [];
$old     = $old ?? [];
$errors  = $errors ?? [];
$val     = fn (string $key, mixed $fallback = '') => $old[$key] ?? $fallback;
$techs   = $isEdit ? $project_tech : ($project_tech ?? []);

$cv = fn ($key, $fallback = '') => $isEdit ? (array_key_exists($key, $p) && $p[$key] !== null ? $p[$key] : $fallback) : $val($key, $fallback);
?>
<form method="post" action="<?= e($isEdit ? url('/admin/projects/' . $p['id']) : url('/admin/projects')) ?>" enctype="multipart/form-data">
    <?= $csrf ?>
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;margin-bottom:18px;">
        <div>
            <h1 class="mb-0"><?= $isEdit ? 'Edit project' : 'New project' ?></h1>
            <?php if ($isEdit): ?><a class="faint" style="font-size:.82rem;" href="<?= e(url('/projects/' . $p['slug'])) ?>" target="_blank" rel="noopener">View on site →</a><?php endif; ?>
        </div>
        <div style="display:flex;gap:8px;">
            <?php if ($isEdit): ?><a class="btn btn--ghost" href="<?= e(url('/admin/projects')) ?>">Cancel</a><?php endif; ?>
            <button class="btn btn--primary" type="submit"><?= $isEdit ? 'Save changes' : 'Create project' ?></button>
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
            <input class="input" type="text" id="title" name="title" value="<?= e($cv('title')) ?>" required maxlength="255">
        </div>
        <div class="field">
            <label for="slug">Slug <small class="field-hint">URL-friendly identifier, e.g. telcy-ai-assistant</small></label>
            <input class="input" type="text" id="slug" name="slug" value="<?= e($cv('slug')) ?>" required maxlength="255">
        </div>

        <div class="field">
            <label for="project_type">Project type</label>
            <select class="select" id="project_type" name="project_type">
                <?php foreach ($types as $t): ?>
                <option value="<?= e($t) ?>" <?= $cv('project_type', 'other') === $t ? 'selected' : '' ?>><?= e(str_replace('_', ' ', ucwords($t, '_'))) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="field">
            <label for="status">Status</label>
            <select class="select" id="status" name="status">
                <?php foreach ($statuses as $s): ?>
                <option value="<?= e($s) ?>" <?= $cv('status', 'completed') === $s ? 'selected' : '' ?>><?= e(str_replace('_', ' ', ucwords($s, '_'))) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="field span-2">
            <label for="short_description">Short description</label>
            <textarea class="input" id="short_description" name="short_description" rows="2"><?= e($cv('short_description')) ?></textarea>
        </div>

        <div class="field span-2">
            <label for="description">Project overview / description</label>
            <textarea class="input" id="description" name="description" rows="6"><?= e($cv('description')) ?></textarea>
        </div>

        <div class="field">
            <label for="problem">Problem</label>
            <textarea class="input" id="problem" name="problem" rows="5"><?= e($cv('problem')) ?></textarea>
        </div>
        <div class="field">
            <label for="solution">Solution</label>
            <textarea class="input" id="solution" name="solution" rows="5"><?= e($cv('solution')) ?></textarea>
        </div>

        <div class="field">
            <label for="architecture">Architecture</label>
            <textarea class="input" id="architecture" name="architecture" rows="5"><?= e($cv('architecture')) ?></textarea>
        </div>
        <div class="field">
            <label for="results">Results / outcomes</label>
            <textarea class="input" id="results" name="results" rows="5"><?= e($cv('results')) ?></textarea>
        </div>

        <div class="field">
            <label for="github_url">GitHub URL</label>
            <input class="input" type="url" id="github_url" name="github_url" value="<?= e($cv('github_url')) ?>" maxlength="500">
        </div>
        <div class="field">
            <label for="demo_url">Live demo URL</label>
            <input class="input" type="url" id="demo_url" name="demo_url" value="<?= e($cv('demo_url')) ?>" maxlength="500">
        </div>

        <div class="field span-2">
            <label for="video_url">Video URL <small class="field-hint">YouTube or Vimeo link — embedded safely on the case study page</small></label>
            <input class="input" type="url" id="video_url" name="video_url" value="<?= e($cv('video_url')) ?>" maxlength="500">
        </div>

        <div class="field span-2">
            <label for="technologies">Technologies <small class="field-hint">Type names separated by commas, e.g. PHP, MySQL, OpenAI API</small></label>
            <input class="input" type="text" id="technologies" name="technologies" value="<?= e(is_array($techs) ? implode(', ', $techs) : '') ?>" placeholder="PHP, MySQL, LangChain">
        </div>
    </div>

    <div class="card">
        <h3>Cover image</h3>
        <?php if (isset($errors['cover_image'])): ?><div class="field-error mb-1"><?= e($errors['cover_image']) ?></div><?php endif; ?>
        <?php if ($cv('cover_image')): ?>
        <img src="<?= e(upload_url($cv('cover_image'))) ?>" alt="Current cover" style="max-width:320px;border-radius:10px;margin-bottom:12px;">
        <?php endif; ?>
        <input class="input" type="file" id="cover_image" name="cover_image" accept="image/*">
        <small class="field-hint">Upload a new cover image to replace (optional).</small>
    </div>

    <div class="card">
        <h3>Additional media (gallery)</h3>
        <?php if ($media): ?>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:12px;margin-bottom:16px;">
            <?php foreach ($media as $m): ?>
            <div style="border:1px solid var(--border);border-radius:10px;overflow:hidden;">
                <img src="<?= e(upload_url($m['file_path'])) ?>" alt="" style="width:100%;aspect-ratio:16/10;object-fit:cover;">
                <div style="padding:8px 10px;display:flex;gap:8px;align-items:center;">
                    <label class="check" style="font-size:.78rem;padding:0;"><input type="checkbox" name="delete_media[]" value="<?= (int) $m['id'] ?>"> Remove</label>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <input class="input" type="file" name="additional_media[]" multiple accept="image/*">
        <small class="field-hint">Select one or more gallery images.</small>
    </div>

    <div class="card">
        <h3>Visibility</h3>
        <label class="check">
            <input type="checkbox" name="published" id="published" <?= $cv('published', 1) ? 'checked' : '' ?>>
            Published (visible to visitors)
        </label>
        <label class="check">
            <input type="checkbox" name="featured" id="featured" <?= $cv('featured') ? 'checked' : '' ?>>
            Featured (highlight on home page)
        </label>
    </div>

    <div style="display:flex;gap:10px;justify-content:flex-end;">
        <a class="btn btn--ghost" href="<?= e(url('/admin/projects')) ?>">Cancel</a>
        <button class="btn btn--primary" type="submit"><?= $isEdit ? 'Save changes' : 'Create project' ?></button>
    </div>
</form>