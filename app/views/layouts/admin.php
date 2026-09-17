<?php
/**
 * Admin layout.
 * Vars: $admin, $unread_messages, $site, $sidebar_active, $csrf, $page_title, $layout_content
 */
use Portfolio\Core\Csrf;

$active = $sidebar_active ?? '';
$badge  = (int) ($unread_messages ?? 0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?= e($page_title ?? 'Dashboard') ?> — <?= e($site['site_name'] ?? 'DE-JUNONG AI') ?> Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="<?= e(asset('css/admin.css')) ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="admin-shell">

    <aside class="admin-sidebar" id="adminSidebar">
        <a class="brand" href="<?= e(url('/admin')) ?>">
            <span class="brand-mark">D</span>
            <span>DE-JUNONG<small>Admin Panel</small></span>
        </a>

        <span class="side-group">Main</span>
        <a class="side-link <?= $active === 'dashboard' ? 'active' : '' ?>" href="<?= e(url('/admin')) ?>"><i class="fa-solid fa-gauge"></i> Dashboard</a>

        <span class="side-group">Content</span>
        <a class="side-link <?= $active === 'projects' ? 'active' : '' ?>" href="<?= e(url('/admin/projects')) ?>"><i class="fa-solid fa-layer-group"></i> Projects</a>
        <a class="side-link <?= $active === 'certificates' ? 'active' : '' ?>" href="<?= e(url('/admin/certificates')) ?>"><i class="fa-solid fa-award"></i> Certificates</a>
        <a class="side-link <?= $active === 'services' ? 'active' : '' ?>" href="<?= e(url('/admin/services')) ?>"><i class="fa-solid fa-rocket"></i> Services</a>
        <a class="side-link <?= $active === 'skills' ? 'active' : '' ?>" href="<?= e(url('/admin/skills')) ?>"><i class="fa-solid fa-code"></i> Skills</a>
        <a class="side-link <?= $active === 'experience' ? 'active' : '' ?>" href="<?= e(url('/admin/experience')) ?>"><i class="fa-solid fa-briefcase"></i> Experience</a>
        <a class="side-link <?= $active === 'education' ? 'active' : '' ?>" href="<?= e(url('/admin/education')) ?>"><i class="fa-solid fa-graduation-cap"></i> Education</a>
        <a class="side-link <?= $active === 'testimonials' ? 'active' : '' ?>" href="<?= e(url('/admin/testimonials')) ?>"><i class="fa-solid fa-quote-left"></i> Testimonials</a>
        <a class="side-link <?= $active === 'blog' ? 'active' : '' ?>" href="<?= e(url('/admin/blog')) ?>"><i class="fa-solid fa-blog"></i> Blog</a>
        <a class="side-link <?= $active === 'media' ? 'active' : '' ?>" href="<?= e(url('/admin/media')) ?>"><i class="fa-solid fa-images"></i> Media</a>
        <a class="side-link <?= $active === 'messages' ? 'active' : '' ?>" href="<?= e(url('/admin/messages')) ?>">
            <i class="fa-solid fa-envelope"></i> Messages
            <?php if ($badge > 0): ?><span class="side-badge"><?= $badge ?></span><?php endif; ?>
        </a>

        <span class="side-group">System</span>
        <a class="side-link <?= $active === 'content' ? 'active' : '' ?>" href="<?= e(url('/admin/content')) ?>"><i class="fa-solid fa-file-lines"></i> Website Content</a>
        <a class="side-link <?= $active === 'settings' ? 'active' : '' ?>" href="<?= e(url('/admin/settings')) ?>"><i class="fa-solid fa-gear"></i> Settings</a>
        <a class="side-link <?= $active === 'profile' ? 'active' : '' ?>" href="<?= e(url('/admin/profile')) ?>"><i class="fa-solid fa-user-gear"></i> Profile</a>
        <a class="side-link" href="<?= e(url('/')) ?>" target="_blank" rel="noopener"><i class="fa-solid fa-globe"></i> View site</a>

        <form method="post" action="<?= e(url('/admin/logout')) ?>" style="margin-top:auto;padding-top:14px;">
            <?= Csrf::field() ?>
            <button class="side-link" style="width:100%;background:none;border:none;cursor:pointer;font-family:inherit;font-size:.9rem;color:var(--danger);display:flex;gap:11px;align-items:center;padding:10px 12px;">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </button>
        </form>
    </aside>

    <div class="admin-main">
        <header class="admin-topbar">
            <div style="display:flex;align-items:center;gap:12px;">
                <button class="menu-toggle" id="menuToggle" aria-label="Toggle menu">☰</button>
                <h1><?= e($page_title ?? 'Dashboard') ?></h1>
            </div>
            <div class="topbar-right">
                <a href="<?= e(url('/contact')) ?>" target="_blank" rel="noopener" class="btn btn--ghost btn--sm" title="Public contact page"><i class="fa-solid fa-envelope-open-text"></i> <span class="topbar-user-label">Contact</span></a>
                <?php if ($badge > 0): ?>
                <a href="<?= e(url('/admin/messages')) ?>" class="btn btn--ghost btn--sm" title="Unread messages"><i class="fa-solid fa-bell"></i> <span class="topbar-user-label"><?= $badge ?> new</span></a>
                <?php endif; ?>
                <div class="admin-user">
                    <div class="avatar"><?= e(strtoupper(mb_substr((string) ($admin['name'] ?? 'A'), 0, 1))) ?></div>
                    <div class="topbar-user-label">
                        <strong style="font-size:.82rem;color:#fff;display:block;line-height:1.2;"><?= e($admin['name'] ?? 'Admin') ?></strong>
                        <span class="faint" style="font-size:.72rem;"><?= e($admin['role'] ?? 'admin') ?></span>
                    </div>
                </div>
            </div>
        </header>

        <main class="admin-content" id="adminContent">
            <?php foreach (get_flash() as $flash): ?>
                <div class="alert alert--<?= e($flash['type'] === 'error' ? 'error' : 'success') ?>" role="status"><?= e($flash['message']) ?></div>
            <?php endforeach; ?>

            <?= $layout_content ?? '' ?>
        </main>
    </div>
</div>

<script src="<?= e(asset('js/admin.js')) ?>" defer></script>
</body>
</html>