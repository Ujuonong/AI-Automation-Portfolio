<?php
/**
 * Experience timeline.
 * Vars: $experience
 */
?>
<section class="section section--tight">
    <div class="container container--narrow">
        <a href="<?= e(url('/about')) ?>" class="breadcrumbs">← About</a>
        <div class="eyebrow"><?= e(content('exp_eyebrow')) ?></div>
        <h1><?= e(content('exp_heading')) ?></h1>
        <p class="lead"><?= e(content('exp_intro')) ?></p>
    </div>
</section>

<section class="section section--tight">
    <div class="container container--narrow">
        <?php if (!$experience): ?>
            <p class="muted text-center mt-3"><?= e(content('exp_empty')) ?></p>
        <?php endif; ?>

        <div class="timeline">
            <?php foreach ($experience as $item): ?>
            <div class="timeline-item">
                <div class="timeline-head">
                    <h3><?= e($item['position']) ?></h3>
                    <span class="timeline-meta"><?= e(format_date((string) $item['start_date'], 'M Y')) ?> — <?= e(format_date((string) $item['end_date'], 'M Y')) ?></span>
                </div>
                <p class="timeline-org"><?= e($item['organization']) ?>
                    <?php if ($item['employment_type']): ?> · <span class="muted"><?= e(ucwords(str_replace('_', ' ', $item['employment_type']))) ?></span><?php endif; ?>
                    <?php if ($item['location']): ?> · <span class="muted"><?= e($item['location']) ?></span><?php endif; ?>
                </p>
                <?php if ($item['description']): ?><p class="muted"><?= e($item['description']) ?></p><?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section section--tight section-alt">
    <div class="container">
        <div class="cta reveal">
            <h2><?= e(content('exp_cta_heading')) ?></h2>
            <a class="btn btn--primary btn--lg" href="<?= e(url('/contact')) ?>"><?= e(content('exp_cta_button')) ?></a>
        </div>
    </div>
</section>