<?php
/**
 * Public layout.
 *
 * Available data: $site (settings array), $_layout_* via View::render ,
 * $page_title, $meta_description, $og_image, plus anything the controller passed.
 */
use Portfolio\Core\Csrf;

$site        = $site ?? [];
$siteName    = $site['site_name'] ?? 'Bulus Ujuonong James';
$title       = $page_title ?? $siteName;
$description = $meta_description ?? ($site['meta_description'] ?? 'AI Automation Engineer & Consultant');
$ogImage     = !empty($og_image) ? upload_url((string) $og_image) : (!empty($site['og_image']) ? upload_url((string) $site['og_image']) : asset('images/og-default.jpg'));

$nav = [
    '/'              => 'Home',
    '/about'         => 'About',
    '/services'      => 'Services',
    '/projects'      => 'Projects',
    '/certifications'=> 'Certifications',
    '/blog'          => 'Blog',
    '/contact'       => 'Contact',
];

$currentPath = $path ?? '/';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title) ?></title>
    <meta name="description" content="<?= e($description) ?>">
    <?php if (!empty($keywords)): ?><meta name="keywords" content="<?= e($keywords) ?>"><?php endif; ?>
    <link rel="canonical" href="<?= e($canonical_url ?? (rtrim(APP_URL, '/') . $currentPath)) ?>">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?= e($siteName) ?>">
    <meta property="og:title" content="<?= e($title) ?>">
    <meta property="og:description" content="<?= e($description) ?>">
    <meta property="og:image" content="<?= e($ogImage) ?>">
    <meta property="og:url" content="<?= e($canonical_url ?? (rtrim(APP_URL, '/') . $currentPath)) ?>">

    <!-- Twitter / X -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($title) ?>">
    <meta name="twitter:description" content="<?= e($description) ?>">
    <meta name="twitter:image" content="<?= e($ogImage) ?>">

    <?php if (!empty($site['favicon'])): ?>
    <link rel="icon" type="image/png" href="<?= e(upload_url($site['favicon'])) ?>">
    <?php else: ?>
    <link rel="icon" type="image/png" href="<?= e(asset('images/favicon.svg')) ?>">
    <?php endif; ?>

    <link rel="stylesheet" href="<?= e(asset('css/styles.css')) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
</head>
<body>
<header class="site-header">
    <div class="container nav">
        <a class="brand" href="<?= e(url('/')) ?>">
            <?php if (!empty($site['logo'])): ?>
            <img class="brand-logo" src="<?= e(upload_url($site['logo'])) ?>" alt="<?= e($siteName) ?>">
            <?php else: ?>
            <span class="brand-mark">D</span>
            <?php endif; ?>
            <span>
                DE-JUNONG<small>Bulus Ujuonong James</small>
            </span>
        </a>

        <nav aria-label="Primary">
            <ul class="nav-links" id="navLinks">
                <?php foreach ($nav as $href => $label): ?>
                <li><a href="<?= e(url($href)) ?>" class="<?= $currentPath === $href ? 'active' : '' ?>"><?= e($label) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </nav>

        <div class="nav-cta">
            <a class="btn btn--primary btn--sm" href="<?= e(url('/contact')) ?>"><?= e(content('nav_cta_label')) ?></a>
            <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation" aria-expanded="false" aria-controls="navLinks">☰</button>
        </div>
    </div>
</header>

<main id="main">
    <?php foreach (get_flash() as $flash): ?>
        <div class="container"><div class="alert alert--<?= e($flash['type'] === 'error' ? 'error' : 'success') ?>" role="status"><?= e($flash['message']) ?></div></div>
    <?php endforeach; ?>

    <?= $layout_content ?? '' ?>
</main>

<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div>
                <a class="brand" href="<?= e(url('/')) ?>" style="flex-direction:column;align-items:flex-start;gap:8px;">
                    <?php if (!empty($site['logo'])): ?>
                    <img class="brand-logo" src="<?= e(upload_url($site['logo'])) ?>" alt="<?= e($siteName) ?>" style="max-width:140px;max-height:56px;">
                    <?php else: ?>
                    <span class="brand-mark">D</span>
                    <?php endif; ?>
                    <span>DE-JUNONG<small>Bulus Ujuonong James</small></span>
                </a>
                <p class="mt-2" style="color: var(--text-muted); max-width: 340px;">
                    <?= e($site['bio'] ?? 'Building intelligent automation systems for smarter businesses.') ?>
                </p>
                <div class="socials">
                    <?php if (!empty($site['github_url'])): ?><a href="<?= e($site['github_url']) ?>" target="_blank" rel="noopener" aria-label="GitHub"><i class="fa-brands fa-github"></i></a><?php endif; ?>
                    <?php if (!empty($site['linkedin_url'])): ?><a href="<?= e($site['linkedin_url']) ?>" target="_blank" rel="noopener" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a><?php endif; ?>
                    <?php if (!empty($site['x_url'])): ?><a href="<?= e($site['x_url']) ?>" target="_blank" rel="noopener" aria-label="X"><i class="fa-brands fa-x-twitter"></i></a><?php endif; ?>
                    <?php if (!empty($site['youtube_url'])): ?><a href="<?= e($site['youtube_url']) ?>" target="_blank" rel="noopener" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a><?php endif; ?>
                    <?php if (!empty($site['whatsapp_url'])): ?><a href="<?= e($site['whatsapp_url']) ?>" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a><?php endif; ?>
                </div>
            </div>
            <div>
                <h4><?= e(content('footer_explore_heading')) ?></h4>
                <ul>
                    <?php foreach ($nav as $href => $label): ?>
                    <li><a href="<?= e(url($href)) ?>"><?= e($label) ?></a></li>
                    <?php endforeach; ?>
                    <li><a href="<?= e(url('/testimonials')) ?>">Testimonials</a></li>
                    <li><a href="<?= e(url('/experience')) ?>">Experience</a></li>
                    <li><a href="<?= e(url('/education')) ?>">Education</a></li>
                </ul>
            </div>
            <div>
                <h4><?= e(content('footer_contact_heading')) ?></h4>
                <ul>
                    <?php if (!empty($site['email'])): ?><li><a href="mailto:<?= e($site['email']) ?>"><?= e($site['email']) ?></a></li><?php endif; ?>
                    <?php if (!empty($site['phone'])): ?><li><a href="tel:<?= e(preg_replace('/[^+\d]/', '', (string) $site['phone'])) ?>"><?= e($site['phone']) ?></a></li><?php endif; ?>
                    <?php if (!empty($site['location'])): ?><li><span class="faint"><?= e($site['location']) ?></span></li><?php endif; ?>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <span>© <?= date('Y') ?> <?= e($siteName) ?>. All rights reserved.</span>
            <span><?= e(content('footer_tagline')) ?></span>
        </div>
    </div>
</footer>

<script src="<?= e(asset('js/main.js')) ?>" defer></script>
</body>
</html>