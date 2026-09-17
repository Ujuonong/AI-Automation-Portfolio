<?php
/**
 * Testimonials page.
 * Vars: $testimonials
 */
?>
<section class="section section--tight">
    <div class="container container--narrow">
        <div class="eyebrow"><?= e(content('test_eyebrow')) ?></div>
        <h1><?= e(content('test_heading')) ?></h1>
        <p class="lead"><?= e(content('test_intro')) ?></p>
    </div>
</section>

<section class="section section--tight">
    <div class="container">
        <?php if (!$testimonials): ?>
            <div class="text-center mt-3">
                <span class="muted"><?= e(content('test_empty')) ?></span>
            </div>
        <?php endif; ?>

        <div class="grid grid--3">
            <?php foreach ($testimonials as $t): ?>
            <figure class="cert reveal" style="margin:0;">
                <div style="color:var(--warning);font-size:.9rem;letter-spacing:2px;" aria-label="5 star rating">
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                </div>
                <blockquote style="margin:0;border:none;padding:0;color:var(--text);font-size:1.02rem;line-height:1.6;">
                    “<?= e($t['testimonial']) ?>”
                </blockquote>
                <figcaption style="display:flex;align-items:center;gap:12px;margin-top:18px;margin-bottom:0;">
                    <?php if ($t['client_image']): ?>
                    <div class="avatar"><img src="<?= e(upload_url($t['client_image'])) ?>" alt="<?= e($t['client_name']) ?>"></div>
                    <?php else: ?>
                    <div class="avatar"><?= e(strtoupper(mb_substr($t['client_name'], 0, 1))) ?></div>
                    <?php endif; ?>
                    <div>
                        <strong style="color:#fff;display:block;"><?= e($t['client_name']) ?></strong>
                        <span class="faint" style="font-size:.85rem;"><?= e($t['client_role']) ?><?= $t['company'] ? ' · ' . e($t['company']) : '' ?></span>
                    </div>
                </figcaption>
            </figure>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section section--tight section-alt">
    <div class="container">
        <div class="cta reveal">
            <h2><?= e(content('test_cta_heading')) ?></h2>
            <a class="btn btn--primary btn--lg" href="<?= e(url('/contact')) ?>"><?= e(content('test_cta_button')) ?></a>
        </div>
    </div>
</section>