<?php
/**
 * About page.
 * Vars: $skills, $experience, $education, $stats, $site
 */
use Portfolio\Services\SiteService;

$profileImg = $site['profile_image'] ?? '';
$profileUrl = $profileImg ? upload_url($profileImg) : asset('images/avatar.svg');
$name       = $site['name'] ?? 'Bulus Ujuonong James';
$title      = $site['professional_title'] ?? 'AI Automation Engineer & Consultant';
?>
<section class="section section--tight">
    <div class="container container--narrow">
        <a href="<?= e(url('/')) ?>" class="breadcrumbs">← Home</a>
        <div class="eyebrow"><?= e(content('about_eyebrow')) ?></div>
        <h1><?= e($name) ?></h1>
        <p class="lead"><?= e($title) ?></p>
    </div>
</section>

<section class="section section--tight">
    <div class="container">
        <div class="grid grid--2" style="align-items:start;gap:56px;">
            <div>
                <?php if ($profileUrl): ?>
                <img src="<?= e($profileUrl) ?>" alt="Portrait of <?= e($name) ?>" style="border-radius:22px;width:100%;object-fit:cover;max-height:520px;" loading="lazy">
                <?php endif; ?>

                <div class="grid grid--2 mt-3">
                    <div class="stat"><b><?= (int) $stats['projects'] ?></b><span>Projects</span></div>
                    <div class="stat"><b><?= (int) $stats['certificates'] ?></b><span>Certifications</span></div>
                </div>
            </div>

            <div>
                <span class="eyebrow"><?= e(content('about_intro_eyebrow')) ?></span>
                <h2><?= e(content('about_intro_heading')) ?></h2>
                <p class="muted">
                    <?= e($site['bio'] ?? '') ?>
                </p>
                <p><?= e(content('about_intro_paragraph')) ?></p>

                <h3 class="mt-3"><?= e(content('about_focus_heading')) ?></h3>
                <ul>
                    <?php foreach (content_lines('about_focus_items') as $item): ?>
                    <li><?= e($item) ?></li>
                    <?php endforeach; ?>
                </ul>

                <h3 class="mt-3"><?= e(content('about_approach_heading')) ?></h3>
                <p><?= e(content('about_approach_text')) ?></p>
            </div>
        </div>
    </div>
</section>

<?php if ($experience): ?>
<section class="section section-alt">
    <div class="container container--narrow">
        <span class="eyebrow"><?= e(content('about_experience_eyebrow')) ?></span>
        <h2><?= e(content('about_experience_heading')) ?></h2>
        <div class="timeline mt-3">
            <?php foreach ($experience as $item): ?>
            <div class="timeline-item">
                <div class="timeline-head">
                    <h3><?= e($item['position']) ?></h3>
                    <span class="timeline-meta"><?= e(format_date((string) $item['start_date'], 'M Y')) ?> — <?= e(format_date((string) $item['end_date'], 'M Y')) ?></span>
                </div>
                <p class="timeline-org" style="margin-top:2px;"><?= e($item['organization']) ?><?= $item['location'] ? ' · ' . e($item['location']) : '' ?></p>
                <?php if ($item['description']): ?><p class="muted" style="font-size:.95rem;"><?= e($item['description']) ?></p><?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($education): ?>
<section class="section section-alt">
    <div class="container container--narrow">
        <span class="eyebrow"><?= e(content('edu_eyebrow')) ?></span>
        <h2><?= e(content('about_education_heading')) ?></h2>
        <div class="timeline mt-3">
            <?php foreach ($education as $item): ?>
            <div class="timeline-item">
                <div class="timeline-head">
                    <h3><?= e($item['degree']) ?></h3>
                    <span class="timeline-meta"><?= e(format_date((string) $item['start_date'], 'M Y')) ?> — <?= e(format_date((string) $item['end_date'], 'M Y')) ?></span>
                </div>
                <p class="timeline-org"><?= e($item['institution']) ?><?= $item['field'] ? ' · ' . e($item['field']) : '' ?></p>
                <?php if ($item['description']): ?><p class="muted" style="font-size:.95rem;"><?= e($item['description']) ?></p><?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($skills): ?>
<section class="section">
    <div class="container">
        <span class="eyebrow"><?= e(content('about_skills_eyebrow')) ?></span>
        <h2><?= e(content('about_skills_heading')) ?></h2>
        <div class="grid grid--3 mt-3">
            <?php foreach ($skills as $category => $group): ?>
            <div class="card">
                <div class="card-body">
                    <h3><?= e($category) ?></h3>
                    <div class="tags">
                        <?php foreach ($group as $skill): ?>
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

<section class="section section-alt">
    <div class="container">
        <div class="cta reveal">
            <h2><?= e(content('about_cta_heading')) ?></h2>
            <p><?= e(content('about_cta_text')) ?></p>
            <a class="btn btn--primary btn--lg" href="<?= e(url('/contact')) ?>"><?= e(content('about_cta_button')) ?></a>
        </div>
    </div>
</section>