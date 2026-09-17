<?php
/**
 * Service detail.
 * Vars: $service, $all_services
 */
?>
<section class="section section--tight">
    <div class="container container--narrow">
        <a href="<?= e(url('/services')) ?>" class="breadcrumbs">← All services</a>
        <span style="font-size:2rem;color:var(--accent);"><i class="fa-solid fa-<?= e($service['icon']) ?>" aria-hidden="true"></i></span>
        <h1 class="mt-1"><?= e($service['title']) ?></h1>
        <p class="lead"><?= e($service['short_description']) ?></p>
        <?php if ($service['description']): ?>
        <div class="article-body mt-3">
            <?= nl2br(e($service['description'])) ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<section class="section section--tight section-alt">
    <div class="container container--narrow">
        <span class="eyebrow"><?= e(content('services_related_eyebrow')) ?></span>
        <div class="grid grid--3">
            <?php foreach ($all_services as $s): ?>
            <?php if ((int) $s['id'] === (int) $service['id']) { continue; } ?>
            <a class="card" href="<?= e(url('/services/' . $s['slug'])) ?>" style="text-decoration:none;">
                <div class="card-body">
                    <span style="font-size:1.3rem;color:var(--accent);"><i class="fa-solid fa-<?= e($s['icon']) ?>" aria-hidden="true"></i></span>
                    <h3><?= e($s['title']) ?></h3>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section section--tight">
    <div class="container">
        <div class="cta reveal">
            <h2><?= e(content('services_show_cta_heading')) ?></h2>
            <a class="btn btn--primary btn--lg" href="<?= e(url('/contact')) ?>"><?= e(content('services_show_cta_button')) ?></a>
        </div>
    </div>
</section>