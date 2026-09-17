<?php
/**
 * Generic resource form (services, skills, experience, education, testimonials).
 * Vars: $resource, $item, $errors, $old, $csrf
 */
use Portfolio\Core\Session;

$r      = $resource;
$isEdit = $item !== null;
$errors = $errors ?? [];
$old    = $old ?? [];
$cv     = fn ($key, $fallback = '') => $isEdit ? ($item[$key] ?? $fallback) : ($old[$key] ?? $fallback);
?>
<form method="post" action="<?= e($isEdit ? url('/admin/' . $r['route'] . '/' . $item['id']) : url('/admin/' . $r['route'])) ?>" enctype="multipart/form-data">
    <?= $csrf ?>
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;margin-bottom:18px;">
        <h1 class="mb-0"><?= $isEdit ? 'Edit ' . e(rtrim($r['label'], 's')) : 'New ' . e(rtrim($r['label'], 's')) ?></h1>
        <div style="display:flex;gap:8px;">
            <a class="btn btn--ghost" href="<?= e(url('/admin/' . $r['route'])) ?>">Cancel</a>
            <button class="btn btn--primary" type="submit"><?= $isEdit ? 'Save changes' : 'Create' ?></button>
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
        <?php foreach ($r['fields'] as $field => $config): ?>
            <?php
            $required = in_array('required', $config['rules'] ?? [], true);
            $type     = $config['type'];
            ?>
            <div class="field <?= in_array($type, ['textarea', 'image'], true) ? 'span-2' : '' ?>">
                <label for="f-<?= e($field) ?>"><?= e($config['label']) ?><?= $required ? ' *' : '' ?></label>

                <?php if (isset($errors[$field])): ?><div class="field-error mb-1"><?= e($errors[$field]) ?></div><?php endif; ?>

                <?php if ($type === 'textarea'): ?>
                    <textarea class="input" id="f-<?= e($field) ?>" name="<?= e($field) ?>" rows="4"><?= e($cv($field)) ?></textarea>

                <?php elseif ($type === 'select'): ?>
                    <select class="select" id="f-<?= e($field) ?>" name="<?= e($field) ?>">
                        <option value="">—</option>
                        <?php foreach ($config['options'] ?? [] as $option): ?>
                        <option value="<?= e($option) ?>" <?= $cv($field) === $option ? 'selected' : '' ?>><?= e(ucwords(str_replace('_', ' ', (string) $option))) ?></option>
                        <?php endforeach; ?>
                    </select>

                <?php elseif ($type === 'checkbox'): ?>
                    <label class="check">
                        <input type="checkbox" name="<?= e($field) ?>" <?= $cv($field, 1) ? 'checked' : '' ?>>
                        Visible to visitors
                    </label>

                <?php elseif ($type === 'image'): ?>
                    <?php if ($cv($field)): ?>
                    <img src="<?= e(upload_url($cv($field))) ?>" alt="" style="max-width:220px;border-radius:10px;margin-bottom:10px;">
                    <?php endif; ?>
                    <input class="input" type="file" id="f-<?= e($field) ?>" name="<?= e($field) ?>" accept="image/*">
                    <small class="field-hint">Upload a new image to replace (optional).</small>

                <?php elseif ($type === 'number'): ?>
                    <input class="input" type="number" id="f-<?= e($field) ?>" name="<?= e($field) ?>" value="<?= e($cv($field)) ?>" <?= in_array('numeric', $config['rules'] ?? [], true) ? 'step="1"' : '' ?>>

                <?php elseif ($type === 'date'): ?>
                    <input class="input" type="date" id="f-<?= e($field) ?>" name="<?= e($field) ?>" value="<?= e($cv($field)) ?>">

                <?php else: ?>
                    <input class="input" type="<?= $type === 'email' ? 'email' : 'text' ?>" id="f-<?= e($field) ?>" name="<?= e($field) ?>" value="<?= e($cv($field)) ?>" <?= $required ? 'required' : '' ?>>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div style="display:flex;gap:10px;justify-content:flex-end;">
        <a class="btn btn--ghost" href="<?= e(url('/admin/' . $r['route'])) ?>">Cancel</a>
        <button class="btn btn--primary" type="submit"><?= $isEdit ? 'Save changes' : 'Create' ?></button>
    </div>
</form>