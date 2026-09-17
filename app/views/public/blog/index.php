<?php
/**
 * Blog listing.
 * Vars: $posts, $categories, $search, $active_category
 */
?>
<section class="section section--tight">
    <div class="container container--narrow">
        <div class="eyebrow"><?= e(content('blog_eyebrow')) ?></div>
        <h1><?= e(content('blog_heading')) ?></h1>
        <p class="lead"><?= e(content('blog_intro')) ?></p>
    </div>
</section>

<section class="section section--tight">
    <div class="container">
        <div class="filter-bar">
            <form method="get" action="<?= e(url('/blog')) ?>" style="display:contents;">
                <input class="input" type="search" name="q" value="<?= e($search) ?>" placeholder="<?= e(content('blog_search_placeholder')) ?>" aria-label="<?= e(content('blog_search_placeholder')) ?>">
                <button class="btn btn--primary btn--sm" type="submit"><?= e(content('blog_search')) ?></button>
            </form>
            <nav aria-label="Blog categories" style="display:flex;gap:8px;flex-wrap:wrap;">
                <a class="tag tag--muted <?= empty($active_category) ? 'tag' : '' ?>" style="text-decoration:none;<?= empty($active_category) ? 'color:var(--accent);' : '' ?>" href="<?= e(url('/blog')) ?>"><?= e(content('blog_all')) ?></a>
                <?php foreach ($categories as $cat): ?>
                <a class="tag tag--muted" style="text-decoration:none;<?= ($active_category === $cat['category']) ? 'color:var(--accent);' : '' ?>" href="<?= e(url('/blog/category/' . urlencode($cat['category']))) ?>"><?= e($cat['category']) ?> (<?= (int) $cat['total'] ?>)</a>
                <?php endforeach; ?>
            </nav>
        </div>

        <?php if (!$posts): ?>
            <p class="muted text-center mt-3"><?= $search !== '' ? content('blog_empty_search') : content('blog_empty') ?></p>
        <?php endif; ?>

        <div class="grid grid--3">
            <?php foreach ($posts as $post): ?>
            <article class="card reveal">
                <?php if ($post['cover_image']): ?>
                <a class="card-media" href="<?= e(url('/blog/' . $post['slug'])) ?>">
                    <img src="<?= e(upload_url($post['cover_image'])) ?>" alt="<?= e($post['title']) ?>" loading="lazy">
                </a>
                <?php endif; ?>
                <div class="card-body">
                    <div class="post-meta">
                        <time datetime="<?= e(substr((string) ($post['published_at'] ?? $post['created_at']), 0, 10)) ?>"><?= e(format_date((string) ($post['published_at'] ?? $post['created_at']), 'M j, Y')) ?></time>
                        <?php if ($post['category']): ?><span>· <?= e($post['category']) ?></span><?php endif; ?>
                    </div>
                    <h3><a href="<?= e(url('/blog/' . $post['slug'])) ?>"><?= e($post['title']) ?></a></h3>
                    <?php if ($post['excerpt']): ?><p class="card-text"><?= e(truncate($post['excerpt'], 130)) ?></p><?php endif; ?>
                    <div class="card-foot">
                        <a class="btn btn--outline-accent btn--sm" href="<?= e(url('/blog/' . $post['slug'])) ?>"><?= e(content('blog_read')) ?> <i class="fa-solid fa-arrow-right-long"></i></a>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>