<?php
/**
 * Project case study.
 * Vars: $project, $technologies, $media, $next_project, $site
 */
?>
<section class="section section--tight">
    <div class="container">
        <a href="<?= e(url('/projects')) ?>" class="breadcrumbs">← All projects</a>
        <div class="tags mb-1">
            <span class="badge-pill badge-pill--<?= $project['status'] === 'completed' ? 'green' : ($project['status'] === 'in_progress' ? 'amber' : 'blue') ?>"><?= e(str_replace('_', ' ', $project['status'])) ?></span>
            <span class="badge-pill badge-pill--muted"><?= e(str_replace('_', ' ', ucwords($project['project_type'], '_'))) ?></span>
            <?php if ($project['featured']): ?><span class="tag">Featured</span><?php endif; ?>
        </div>
        <h1><?= e($project['title']) ?></h1>
        <?php if ($project['short_description']): ?><p class="lead"><?= e($project['short_description']) ?></p><?php endif; ?>

        <?php if ($project['cover_image']): ?>
        <img src="<?= e(upload_url($project['cover_image'])) ?>" alt="<?= e($project['title']) ?> — project cover" style="border-radius:22px;margin-top:26px;width:100%;max-height:560px;object-fit:cover;" loading="eager">
        <?php endif; ?>
    </div>
</section>

<section class="section section--tight section-alt">
    <div class="container container--narrow">
        <div class="tags" style="gap:10px;">
            <?php if ($project['github_url']): ?><a class="btn btn--ghost btn--sm" href="<?= e($project['github_url']) ?>" target="_blank" rel="noopener nofollow"><i class="fa-brands fa-github"></i> GitHub</a><?php endif; ?>
            <?php if ($project['demo_url']): ?><a class="btn btn--ghost btn--sm" href="<?= e($project['demo_url']) ?>" target="_blank" rel="noopener nofollow"><i class="fa-solid fa-arrow-up-right-from-square"></i> Live Demo</a><?php endif; ?>
            <?php if ($project['video_url']): ?><a class="btn btn--primary btn--sm" href="#demo-video"><i class="fa-solid fa-play"></i> Watch Demo</a><?php endif; ?>
            <?php foreach ($technologies as $tech): ?><span class="tag"><?= e($tech['name']) ?></span><?php endforeach; ?>
        </div>
    </div>
</section>

<?php if ($project['description']): ?>
<section class="section section--tight">
    <div class="container container--narrow">
        <div class="section-head"><span class="eyebrow">01</span><h2>Project Overview</h2></div>
        <div class="article-body"><?= nl2br(e($project['description'])) ?></div>
    </div>
</section>
<?php endif; ?>

<?php if ($project['problem']): ?>
<section class="section section--tight section-alt">
    <div class="container container--narrow">
        <div class="section-head"><span class="eyebrow">02</span><h2>The Problem</h2></div>
        <div class="article-body"><?= nl2br(e($project['problem'])) ?></div>
    </div>
</section>
<?php endif; ?>

<?php if ($project['solution']): ?>
<section class="section section--tight">
    <div class="container container--narrow">
        <div class="section-head"><span class="eyebrow">03</span><h2>The Solution</h2></div>
        <div class="article-body"><?= nl2br(e($project['solution'])) ?></div>
    </div>
</section>
<?php endif; ?>

<?php if ($project['architecture']): ?>
<section class="section section--tight section-alt">
    <div class="container container--narrow">
        <div class="section-head"><span class="eyebrow">04</span><h2>Architecture</h2></div>
        <div class="article-body"><?= nl2br(e($project['architecture'])) ?></div>
    </div>
</section>
<?php endif; ?>

<?php if ($project['results']): ?>
<section class="section section--tight">
    <div class="container container--narrow">
        <div class="section-head"><span class="eyebrow">05</span><h2>Results & Outcomes</h2></div>
        <div class="article-body"><?= nl2br(e($project['results'])) ?></div>
    </div>
</section>
<?php endif; ?>

<?php if ($project['video_url']): ?>
<section class="section section--tight section-alt" id="demo-video">
    <div class="container container--narrow">
        <div class="section-head"><span class="eyebrow">Demo</span><h2>Watch it in action</h2></div>
        <?php $embed = \Portfolio\Services\VideoService::embedUrl($project['video_url']); ?>
        <?php if ($embed): ?>
        <div style="position:relative;padding-top:56.25%;border-radius:18px;overflow:hidden;background:#000;">
            <iframe src="<?= e($embed) ?>" title="<?= e($project['title']) ?> — demo video" style="position:absolute;inset:0;width:100%;height:100%;border:0;" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen loading="lazy"></iframe>
        </div>
        <?php else: ?>
        <a class="btn btn--ghost" href="<?= e($project['video_url']) ?>" target="_blank" rel="noopener nofollow">Open video <i class="fa-solid fa-arrow-up-right-from-square"></i></a>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<?php if ($media): ?>
<section class="section section--tight">
    <div class="container">
        <div class="section-head"><span class="eyebrow">Gallery</span><h2>Product screens & media</h2></div>
        <div class="grid grid--2">
            <?php foreach ($media as $m): ?>
            <figure class="card reveal" style="margin:0;">
                <?php if ($m['media_type'] === 'image'): ?>
                <img src="<?= e(upload_url($m['file_path'])) ?>" alt="<?= e($m['caption'] ?: $project['title']) ?>" loading="lazy" style="width:100%;aspect-ratio:16/10;object-fit:cover;">
                <?php else: ?>
                <div class="card-media"><i class="fa-solid fa-file" style="margin:auto;display:grid;place-items:center;font-size:2rem;color:var(--text-faint);height:100%;"></i></div>
                <?php endif; ?>
                <?php if ($m['caption']): ?><figcaption class="card-text" style="padding:14px 18px;"><?= e($m['caption']) ?></figcaption><?php endif; ?>
            </figure>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="section section--tight">
    <div class="container">
        <div class="cta reveal">
            <h2><?= e(content('projects_show_cta_heading')) ?></h2>
            <p><?= e(content('projects_show_cta_text')) ?></p>
            <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;position:relative;">
                <a class="btn btn--primary btn--lg" href="<?= e(url('/contact')) ?>"><?= e(content('projects_show_cta_button')) ?></a>
                <?php if ($next_project): ?>
                <a class="btn btn--ghost btn--lg" href="<?= e(url('/projects/' . $next_project['slug'])) ?>"><?= e(content('projects_show_next')) ?> <i class="fa-solid fa-arrow-right-long"></i></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>