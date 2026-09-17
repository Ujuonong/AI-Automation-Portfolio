<?php
/**
 * Admin login form.
 * Vars: $csrf, $error, $locked_for
 */
?>
<div class="auth-card">
    <a class="brand" href="<?= e(url('/')) ?>">
        <span class="brand-mark">D</span>
        <span>DE-JUNONG<small>Admin Panel</small></span>
    </a>

    <h1>Sign in</h1>
    <p class="muted" style="margin-bottom:24px;">Access the portfolio control panel.</p>

    <?php if ($error): ?>
        <div class="alert alert--error"><?= e($error) ?></div>
    <?php endif; ?>

    <?php if ((int) ($locked_for ?? 0) > 0): ?>
        <div class="alert alert--error">Too many attempts. Try again in <?= (int) ceil($locked_for / 60) ?> minute(s).</div>
    <?php endif; ?>

    <form method="post" action="<?= e(url('/admin/login')) ?>" novalidate>
        <?= $csrf ?>
        <div class="field">
            <label for="email">Email address</label>
            <input class="input" type="email" id="email" name="email" autocomplete="username" required autofocus>
        </div>
        <div class="field">
            <label for="password">Password</label>
            <input class="input" type="password" id="password" name="password" autocomplete="current-password" required>
        </div>
        <button class="btn btn--primary btn--block btn--lg" type="submit">Sign In</button>
    </form>

    <p class="auth-foot"><a href="<?= e(url('/')) ?>"><i class="fa-solid fa-arrow-left"></i> Back to website</a></p>
</div>