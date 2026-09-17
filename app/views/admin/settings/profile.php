<?php
/**
 * Admin profile (name, email, password).
 * Vars: $user, $errors, $old
 */
$errors = $errors ?? [];
$old    = $old ?? [];
$cv     = fn (string $key, $fallback = '') => ($old[$key] ?? $fallback);
?>
<div class="card" style="max-width:640px;">
    <h1 style="font-size:1.4rem;">Profile</h1>
    <p class="muted">Update your name, login email, or password.</p>

    <form method="post" action="<?= e(url('/admin/profile')) ?>">
        <?= $csrf ?>

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

        <div class="field">
            <label for="name">Name</label>
            <input class="input" id="name" name="name" type="text" value="<?= e($cv('name', (string) $user['name'])) ?>" required>
        </div>
        <div class="field">
            <label for="email">Email</label>
            <input class="input" id="email" name="email" type="email" value="<?= e($cv('email', (string) $user['email'])) ?>" required>
        </div>
        <div class="field">
            <label for="password">New password</label>
            <input class="input" id="password" name="password" type="password" autocomplete="new-password" placeholder="Leave blank to keep current">
            <small class="field-hint">At least 8 characters.</small>
        </div>
        <div class="field">
            <label for="password_confirmation">Confirm new password</label>
            <input class="input" id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password">
        </div>

        <button class="btn btn--primary" type="submit"><i class="fa-solid fa-floppy-disk"></i> Update profile</button>
    </form>
</div>