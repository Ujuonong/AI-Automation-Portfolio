<?php
/**
 * Education page.
 * Vars: $education
 */
?>
<section class="section section--tight">
    <div class="container container--narrow">
        <a href="<?= e(url('/about')) ?>" class="breadcrumbs">← About</a>
        <div class="eyebrow"><?= e(content('edu_eyebrow')) ?></div>
        <h1><?= e(content('edu_heading')) ?></h1>
        <p class="lead"><?= e(content('edu_intro')) ?></p>
    </div>
</section>

<section class="section section--tight">
    <div class="container container--narrow">
        <?php if (!$education): ?>
            <p class="muted text-center mt-3"><?= e(content('edu_empty')) ?></p>
        <?php endif; ?>

        <div class="timeline">
            <?php foreach ($education as $item): ?>
            <div class="timeline-item">
                <div class="timeline-head">
                    <h3><?= e($item['degree'] ?: $item['institution']) ?></h3>
                    <span class="timeline-meta"><?= e(format_date((string) $item['start_date'], 'M Y')) ?> — <?= e(format_date((string) $item['end_date'], 'M Y')) ?></span>
                </div>
                <p class="timeline-org"><?= e($item['institution']) ?><?= $item['field'] ? ' · ' . e($item['field']) : '' ?></p>
                <?php if ($item['description']): ?><p class="muted"><?= e($item['description']) ?></p><?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>