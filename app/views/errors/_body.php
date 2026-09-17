<?php
/**
 * Shared error body. Rendered inside the public layout.
 * Vars: $status, $title, $message
 */
?>
<section class="section">
    <div class="container text-center">
        <h1 style="font-size:clamp(4rem,12vw,7rem);margin-bottom:0;color:var(--accent);"><?= (int) $status ?></h1>
        <h2><?= e($title ?? 'Error') ?></h2>
        <p class="lead" style="max-width:520px;margin:0 auto 30px;"><?= e($message ?? 'Something went wrong.') ?></p>
        <a class="btn btn--primary btn--lg" href="<?= e(url('/')) ?>">Back to Home</a>
        <?php if ((int) $status === 404): ?>
        <a class="btn btn--ghost btn--lg" href="<?= e(url('/projects')) ?>">View Projects</a>
        <?php elseif ((int) $status === 403): ?>
        <a class="btn btn--ghost btn--lg" href="<?= e(url('/admin/login')) ?>">Admin Login</a>
        <?php endif; ?>
    </div>
</section>