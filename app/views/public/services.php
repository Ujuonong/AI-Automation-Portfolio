<?php
/**
 * Services listing.
 * Vars: $services
 */
?>
<section class="section section--tight">
    <div class="container container--narrow">
        <div class="eyebrow"><?= e(content('services_eyebrow')) ?></div>
        <h1><?= e(content('services_heading')) ?></h1>
        <p class="lead"><?= e(content('services_intro')) ?></p>
    </div>
</section>

<section class="section section--tight">
    <div class="container">
        <?php if (!$services): ?>
            <p class="muted text-center mt-3"><?= e(content('services_empty')) ?></p>
        <?php endif; ?>
        <div class="grid grid--2">
            <?php foreach ($services as $service): ?>
            <article class="card reveal">
                <div class="card-body">
                    <span style="font-size:1.6rem;color:var(--accent);"><i class="fa-solid fa-<?= e($service['icon']) ?>" aria-hidden="true"></i></span>
                    <h3><a href="<?= e(url('/services/' . $service['slug'])) ?>"><?= e($service['title']) ?></a></h3>
                    <p class="card-text"><?= e($service['short_description']) ?></p>
                    <?php if ($service['description']): ?>
                    <p class="card-text" style="opacity:.85;"><?= e(truncate($service['description'], 160)) ?></p>
                    <?php endif; ?>
                    <div class="card-foot">
                        <a class="btn btn--outline-accent btn--sm" href="<?= e(url('/services/' . $service['slug'])) ?>"><?= e(content('services_card_cta')) ?> <i class="fa-solid fa-arrow-right-long"></i></a>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section section-alt">
    <div class="container">
        <div class="cta reveal">
            <h2><?= e(content('services_cta_heading')) ?></h2>
            <p><?= e(content('services_cta_text')) ?></p>
            <a class="btn btn--primary btn--lg" href="<?= e(url('/contact')) ?>"><?= e(content('services_cta_button')) ?></a>
        </div>
    </div>
</section>