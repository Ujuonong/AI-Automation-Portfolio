<?php
/**
 * Website content editor.
 * Vars: $groups (group => array of ['key','label','type','value'])
 */
?>
<form method="post" action="<?= e(url('/admin/content')) ?>">
    <?= $csrf ?>
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
        <h1 class="mb-0">Website content</h1>
        <button class="btn btn--primary" type="submit"><i class="fa-solid fa-floppy-disk"></i> Save content</button>
    </div>
    <p class="faint" style="margin:-6px 0 18px;">Edits here update the text and buttons across your public site. Contact details, images and SEO live under Settings.</p>

    <?php foreach ($groups as $group => $fields): ?>
    <div class="card">
        <h2><?= e($group) ?></h2>
        <div class="form-grid">
            <?php foreach ($fields as $f): ?>
            <?php $id = 'block-' . e($f['key']); ?>
            <?php if ($f['type'] === 'textarea' || $f['type'] === 'textarea_lines'): ?>
            <div class="field span-2">
                <label for="<?= $id ?>"><?= e($f['label']) ?></label>
                <textarea class="input" id="<?= $id ?>" name="<?= e($f['key']) ?>" rows="<?= $f['type'] === 'textarea_lines' ? 6 : 3 ?>"><?= e($f['value']) ?></textarea>
                <?php if ($f['type'] === 'textarea_lines'): ?><small class="faint">One item per line.</small><?php endif; ?>
            </div>
            <?php else: ?>
            <div class="field">
                <label for="<?= $id ?>"><?= e($f['label']) ?></label>
                <input class="input" id="<?= $id ?>" name="<?= e($f['key']) ?>" type="text" value="<?= e($f['value']) ?>">
            </div>
            <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>

    <div style="display:flex;justify-content:flex-end;margin-top:8px;">
        <button class="btn btn--primary" type="submit"><i class="fa-solid fa-floppy-disk"></i> Save content</button>
    </div>
</form>