<?php
/**
 * Single blog post.
 * Vars: $post, $related, $categories
 */
?>
<section class="section section--tight">
    <div class="container container--narrow">
        <a href="<?= e(url('/blog')) ?>" class="breadcrumbs">← All insights</a>
        <div class="post-meta mb-1">
            <time datetime="<?= e(substr((string) ($post['published_at'] ?? $post['created_at']), 0, 10)) ?>"><?= e(format_date((string) ($post['published_at'] ?? $post['created_at']), 'F j, Y')) ?></time>
            <?php if ($post['category']): ?><span>· <a href="<?= e(url('/blog/category/' . urlencode($post['category']))) ?>"><?= e($post['category']) ?></a></span><?php endif; ?>
        </div>
        <h1><?= e($post['title']) ?></h1>
        <?php if ($post['excerpt']): ?><p class="lead"><?= e($post['excerpt']) ?></p><?php endif; ?>
    </div>
</section>

<?php if ($post['cover_image']): ?>
<section class="section section--tight" style="padding-top:0;">
    <div class="container container--narrow">
        <img src="<?= e(upload_url($post['cover_image'])) ?>" alt="<?= e($post['title']) ?>" style="border-radius:18px;width:100%;max-height:460px;object-fit:cover;">
    </div>
</section>
<?php endif; ?>

<section class="section section--tight" style="padding-top:0;">
    <div class="container container--narrow">
        <div class="article-body">
            <?= nl2br(e($post['content'])) ?>
        </div>
    </div>
</section>

<?php if ($related): ?>
<section class="section section--tight section-alt">
    <div class="container">
        <span class="eyebrow"><?= e(content('blog_related_eyebrow')) ?></span>
        <div class="grid grid--2">
            <?php foreach ($related as $r): ?>
            <a class="card" href="<?= e(url('/blog/' . $r['slug'])) ?>" style="text-decoration:none;">
                <div class="card-body">
                    <h3><?= e($r['title']) ?></h3>
                    <?php if ($r['excerpt']): ?><p class="card-text"><?= e(truncate($r['excerpt'], 120)) ?></p><?php endif; ?>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="section section--tight">
    <div class="container">
        <div class="cta reveal">
            <h2><?= e(content('blog_show_cta_heading')) ?></h2>
            <a class="btn btn--primary btn--lg" href="<?= e(url('/contact')) ?>"><?= e(content('blog_show_cta_button')) ?></a>
        </div>
    </div>
</section>