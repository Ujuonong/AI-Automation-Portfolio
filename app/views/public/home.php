<?php
/**
 * Home page.
 * Vars: $stats, $featured_projects, $services, $skills, $posts, $site, $project_tech
 */
use Portfolio\Services\SiteService;

$fullName    = 'Bulus Ujuonong James';
$siteName    = $site['site_name'] ?? 'DE-JUNONG AI';
$title       = $site['professional_title'] ?? 'AI Automation Engineer & Consultant';
$bio         = $site['bio'] ?? '';
$profileImg  = $site['profile_image'] ?? '';
$resume      = $site['resume_file'] ?? '';
$profileUrl  = $profileImg ? upload_url($profileImg) : asset('images/avatar.svg');
$name = $site['name'] ?? $fullName;
?>
<!-- HERO -->
<section class="hero">
    <div class="hero-grid" aria-hidden="true"></div>
    <div class="container hero-content">
        <div class="hero-badge reveal"><span class="dot" aria-hidden="true"></span> <?= e(content('hero_badge')) ?></div>
        <div class="eyebrow reveal"><?= e($name) ?></div>
        <p class="hero-title reveal"><?= e($title) ?></p>
        <h1 class="reveal"><?= e(content('hero_heading')) ?></h1>
        <p class="lead reveal"><?= e(content('hero_lead')) ?></p>
        <div class="hero-actions reveal">
            <a class="btn btn--primary" href="<?= e(url('/projects')) ?>"><?= e(content('hero_primary_cta')) ?></a>
            <a class="btn btn--ghost" href="<?= e(url('/contact')) ?>"><?= e(content('hero_secondary_cta')) ?></a>
            <?php if ($resume): ?>
            <a class="btn btn--outline-accent" href="<?= e(upload_url($resume)) ?>" target="_blank" rel="noopener">
                <i class="fa-solid fa-download" aria-hidden="true"></i> <?= e(content('hero_download_cv')) ?>
            </a>
            <?php endif; ?>
        </div>

        <div class="hero-stats reveal">
            <div class="stat"><b><?= (int) $stats['projects'] ?></b><span><?= e(content('hero_stat_projects')) ?></span></div>
            <div class="stat"><b><?= (int) $stats['technologies'] ?></b><span><?= e(content('hero_stat_skills')) ?></span></div>
            <div class="stat"><b><?= (int) $stats['certificates'] ?></b><span><?= e(content('hero_stat_certifications')) ?></span></div>
            <div class="stat"><b><?= (int) $stats['services'] ?></b><span><?= e(content('hero_stat_services')) ?></span></div>
        </div>
    </div>
</section>

<?php if ($featured_projects): ?>
<!-- FEATURED PROJECTS -->
<section class="section section-alt">
    <div class="container">
        <div class="section-head reveal">
            <span class="eyebrow"><?= e(content('home_featured_eyebrow')) ?></span>
            <h2><?= e(content('home_featured_heading')) ?></h2>
            <p><?= e(content('home_featured_intro')) ?></p>
        </div>
        <div class="grid grid--3">
            <?php foreach ($featured_projects as $project): ?>
            <article class="card reveal">
                <?php if ($project['cover_image']): ?>
                <a class="card-media" href="<?= e(url('/projects/' . $project['slug'])) ?>" aria-label="<?= e($project['title']) ?>">
                    <img src="<?= e(upload_url($project['cover_image'])) ?>" alt="<?= e($project['title']) ?>" loading="lazy">
                </a>
                <?php endif; ?>
                <div class="card-body">
                    <div class="tags">
                        <span class="tag tag--muted"><?= e(str_replace('_', ' ', ucwords($project['project_type'], '_'))) ?></span>
                        <span class="tag tag--muted"><?= e($project['status']) ?></span>
                    </div>
                    <h3><a href="<?= e(url('/projects/' . $project['slug'])) ?>"><?= e($project['title']) ?></a></h3>
                    <?php if ($project['short_description']): ?><p class="card-text"><?= e(truncate($project['short_description'], 130)) ?></p><?php endif; ?>
                    <div class="card-foot tags">
                        <?php foreach ($project_tech->forProject((int) $project['id']) as $tech): ?>
                        <span class="tag"><?= e($tech['name']) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- SERVICES PREVIEW -->
<section class="section">
    <div class="container">
        <div class="section-head reveal">
            <span class="eyebrow"><?= e(content('home_services_eyebrow')) ?></span>
            <h2><?= e(content('home_services_heading')) ?></h2>
            <p><?= e(content('home_services_intro')) ?></p>
        </div>
        <div class="grid grid--3">
            <?php foreach (array_slice($services, 0, 6) as $service): ?>
            <a class="card" href="<?= e(url('/services/' . $service['slug'])) ?>" style="text-decoration:none;">
                <div class="card-body">
                    <span style="font-size:1.5rem;color:var(--accent);"><i class="fa-solid fa-<?= e($service['icon']) ?>" aria-hidden="true"></i></span>
                    <h3><?= e($service['title']) ?></h3>
                    <?php if ($service['short_description']): ?><p class="card-text"><?= e($service['short_description']) ?></p><?php endif; ?>
                    <span class="card-text" style="color:var(--accent);font-weight:600;font-size:.85rem;"><?= e(content('home_services_learn_more')) ?> <i class="fa-solid fa-arrow-right-long"></i></span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- HOW I BUILD YOUR SYSTEM -->
<section class="section section-alt">
    <div class="container">
        <div class="section-head reveal">
            <span class="eyebrow"><?= e(content('process_eyebrow')) ?></span>
            <h2><?= e(content('process_heading')) ?></h2>
            <p><?= e(content('process_intro')) ?></p>
        </div>
        <ol class="process">
            <?php for ($i = 1; $i <= 5; $i++): ?>
            <li class="process-step reveal">
                <span class="process-num">0<?= $i ?></span>
                <h3><?= e(content('process_' . $i . '_title')) ?></h3>
                <p><?= e(content('process_' . $i . '_text')) ?></p>
            </li>
            <?php endfor; ?>
        </ol>
        <div class="process-cta reveal">
            <a class="btn btn--primary btn--lg" href="<?= e(url('/contact?enquiry=ai_automation_audit')) ?>">
                <?= e(SiteService::get('ai_consultant_cta', 'Audit My Business')) ?> <i class="fa-solid fa-arrow-right-long" aria-hidden="true"></i>
            </a>
        </div>
    </div>
</section>

<?php if ($skills): ?>
<!-- SKILLS -->
<section class="section section-alt">
    <div class="container">
        <div class="section-head reveal">
            <span class="eyebrow"><?= e(content('home_skills_eyebrow')) ?></span>
            <h2><?= e(content('home_skills_heading')) ?></h2>
            <p><?= e(content('home_skills_intro')) ?></p>
        </div>
        <div class="grid grid--2">
            <?php foreach ($skills as $category => $skillGroup): ?>
            <div class="card reveal">
                <div class="card-body">
                    <h3><?= e($category) ?></h3>
                    <div class="tags">
                        <?php foreach ($skillGroup as $skill): ?>
                        <span class="tag"><?= e($skill['name']) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ABOUT PREVIEW -->
<section class="section">
    <div class="container">
        <div class="grid grid--2" style="align-items:center;">
            <div class="reveal">
                <?php if ($profileUrl): ?>
                <img src="<?= e($profileUrl) ?>" alt="Portrait of <?= e($name) ?>" style="border-radius:22px;max-height:440px;object-fit:cover;width:100%;" loading="lazy">
                <?php endif; ?>
            </div>
            <div class="reveal">
                <span class="eyebrow"><?= e(content('home_about_eyebrow')) ?></span>
                <h2><?= e(content('home_about_heading')) ?></h2>
                <p class="muted">
                    <?= e($bio !== '' ? $bio : content('home_about_text')) ?>
                </p>
                <div class="mt-2">
                    <a class="btn btn--primary" href="<?= e(url('/about')) ?>"><?= e(content('home_about_primary_cta')) ?></a>
                    <a class="btn btn--ghost" href="<?= e(url('/experience')) ?>"><?= e(content('home_about_secondary_cta')) ?></a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if ($posts): ?>
<!-- LATEST INSIGHTS -->
<section class="section section-alt">
    <div class="container">
        <div class="section-head reveal">
            <span class="eyebrow"><?= e(content('home_blog_eyebrow')) ?></span>
            <h2><?= e(content('home_blog_heading')) ?></h2>
        </div>
        <div class="grid grid--3">
            <?php foreach ($posts as $post): ?>
            <article class="card reveal">
                <?php if ($post['cover_image']): ?>
                <a class="card-media" href="<?= e(url('/blog/' . $post['slug'])) ?>">
                    <img src="<?= e(upload_url($post['cover_image'])) ?>" alt="<?= e($post['title']) ?>" loading="lazy">
                </a>
                <?php endif; ?>
                <div class="card-body">
                    <?php if ($post['category']): ?><span class="tag tag--muted"><?= e($post['category']) ?></span><?php endif; ?>
                    <h3><a href="<?= e(url('/blog/' . $post['slug'])) ?>"><?= e($post['title']) ?></a></h3>
                    <?php if ($post['excerpt']): ?><p class="card-text"><?= e(truncate($post['excerpt'], 130)) ?></p><?php endif; ?>
                    <div class="post-meta"><time><?= e(format_date((string) ($post['published_at'] ?? $post['created_at']), 'M j, Y')) ?></time></div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA -->
<section class="section">
    <div class="container">
        <div class="cta reveal">
            <h2><?= e(content('home_cta_heading')) ?></h2>
            <p><?= e(content('home_cta_text')) ?></p>
            <a class="btn btn--primary btn--lg" href="<?= e(url('/contact')) ?>"><?= e(content('home_cta_button')) ?></a>
        </div>
    </div>
</section>