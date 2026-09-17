<?php
/**
 * Site settings.
 * Vars: $settings (key => value array)
 */
$get = fn (string $key, string $fallback = '') => (string) ($settings[$key] ?? $fallback);
?>
<form method="post" action="<?= e(url('/admin/settings')) ?>" enctype="multipart/form-data">
    <?= $csrf ?>
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
        <h1 class="mb-0">Site settings</h1>
        <button class="btn btn--primary" type="submit"><i class="fa-solid fa-floppy-disk"></i> Save settings</button>
    </div>

    <?php foreach (get_flash() as $fl): ?>
    <div class="alert alert--<?= e($fl['type'] === 'error' ? 'error' : 'success') ?>"><?= e($fl['message']) ?></div>
    <?php endforeach; ?>

    <div class="card">
        <h2>Identity</h2>
        <div class="form-grid">
            <div class="field">
                <label for="site_name">Site name</label>
                <input class="input" id="site_name" name="site_name" type="text" value="<?= e($get('site_name')) ?>">
            </div>
            <div class="field">
                <label for="professional_title">Professional title</label>
                <input class="input" id="professional_title" name="professional_title" type="text" value="<?= e($get('professional_title')) ?>">
            </div>
            <div class="field span-2">
                <label for="bio">Bio</label>
                <textarea class="input" id="bio" name="bio" rows="2"><?= e($get('bio')) ?></textarea>
            </div>
            <div class="field span-2">
                <label for="meta_description">Meta description (SEO)</label>
                <textarea class="input" id="meta_description" name="meta_description" rows="2"><?= e($get('meta_description')) ?></textarea>
            </div>
        </div>
    </div>

    <div class="card">
        <h2>Contact details</h2>
        <div class="form-grid">
            <div class="field">
                <label for="email">Email</label>
                <input class="input" id="email" name="email" type="email" value="<?= e($get('email')) ?>">
            </div>
            <div class="field">
                <label for="phone">Phone</label>
                <input class="input" id="phone" name="phone" type="text" value="<?= e($get('phone')) ?>">
            </div>
            <div class="field">
                <label for="location">Location</label>
                <input class="input" id="location" name="location" type="text" value="<?= e($get('location')) ?>">
            </div>
        </div>
    </div>

    <div class="card">
        <h2>Social links</h2>
        <div class="form-grid">
            <div class="field">
                <label for="linkedin_url">LinkedIn</label>
                <input class="input" id="linkedin_url" name="linkedin_url" type="url" value="<?= e($get('linkedin_url')) ?>">
            </div>
            <div class="field">
                <label for="github_url">GitHub</label>
                <input class="input" id="github_url" name="github_url" type="url" value="<?= e($get('github_url')) ?>">
            </div>
            <div class="field">
                <label for="x_url">X (Twitter)</label>
                <input class="input" id="x_url" name="x_url" type="url" value="<?= e($get('x_url')) ?>">
            </div>
            <div class="field">
                <label for="youtube_url">YouTube</label>
                <input class="input" id="youtube_url" name="youtube_url" type="url" value="<?= e($get('youtube_url')) ?>">
            </div>
            <div class="field span-2">
                <label for="whatsapp_url">WhatsApp link</label>
                <input class="input" id="whatsapp_url" name="whatsapp_url" type="url" value="<?= e($get('whatsapp_url')) ?>">
            </div>
        </div>
    </div>

    <div class="card">
        <h2>Media & files</h2>
        <div class="form-grid">
            <div class="field">
                <label for="profile_image">Profile image</label>
                <?php if ($get('profile_image')): ?>
                <img src="<?= e(upload_url($get('profile_image'))) ?>" alt="" style="max-width:140px;border-radius:50%;margin-bottom:10px;">
                <?php endif; ?>
                <input class="input" type="file" id="profile_image" name="profile_image" accept="image/*">
            </div>
            <div class="field">
                <label for="logo">Logo</label>
                <?php if ($get('logo')): ?>
                <img src="<?= e(upload_url($get('logo'))) ?>" alt="" style="max-width:140px;margin-bottom:10px;">
                <?php endif; ?>
                <input class="input" type="file" id="logo" name="logo" accept="image/*">
            </div>
            <div class="field">
                <label for="favicon">Favicon</label>
                <?php if ($get('favicon')): ?>
                <img src="<?= e(upload_url($get('favicon'))) ?>" alt="" style="max-width:60px;margin-bottom:10px;">
                <?php endif; ?>
                <input class="input" type="file" id="favicon" name="favicon" accept="image/*, .ico">
            </div>
            <div class="field">
                <label for="og_image">Open Graph image (sharing)</label>
                <?php if ($get('og_image')): ?>
                <img src="<?= e(upload_url($get('og_image'))) ?>" alt="" style="max-width:220px;margin-bottom:10px;">
                <?php endif; ?>
                <input class="input" type="file" id="og_image" name="og_image" accept="image/*">
            </div>
            <div class="field span-2">
                <label for="resume_file">Resume / CV (PDF)</label>
                <?php if ($get('resume_file')): ?>
                <div class="mb-1"><a class="btn btn--ghost btn--sm" href="<?= e(upload_url($get('resume_file'))) ?>" target="_blank" rel="noopener"><i class="fa-solid fa-file-pdf"></i> Current file</a></div>
                <?php endif; ?>
                <input class="input" type="file" id="resume_file" name="resume_file" accept="application/pdf">
            </div>
        </div>
    </div>

    <div class="card">
        <h2>AI Consultation chat</h2>
        <p class="muted" style="margin-bottom:14px;">Powers the "Audit My Business" chat on the home page. The webhook URL is stored in the database, so you can point it at any n8n / AI agent endpoint without touching code.</p>
        <div class="form-grid">
            <div class="field span-2">
                <label class="check" for="ai_consultation_enabled">
                    <input type="checkbox" id="ai_consultation_enabled" name="ai_consultation_enabled" value="1" <?= $get('ai_consultation_enabled', '1') === '1' ? 'checked' : '' ?>>
                    Enable AI consultation chat
                </label>
            </div>
            <div class="field span-2">
                <label for="ai_consultant_webhook_url">Webhook URL (n8n / AI agent endpoint)</label>
                <input class="input" id="ai_consultant_webhook_url" name="ai_consultant_webhook_url" type="text" value="<?= e($get('ai_consultant_webhook_url')) ?>"
                       placeholder="https://your-n8n-instance.com/webhook/..." autocomplete="off" spellcheck="false">
                <small class="field-hint">Replaces <code>YOUR_WEBHOOK_URL_HERE</code>. No code changes needed — save here and the public chat picks it up.</small>
            </div>
            <div class="field">
                <label for="ai_consultant_name">AI name (status text)</label>
                <input class="input" id="ai_consultant_name" name="ai_consultant_name" type="text" value="<?= e($get('ai_consultant_name', 'AI Consultant')) ?>">
            </div>
            <div class="field">
                <label for="ai_consultant_cta">CTA button label</label>
                <input class="input" id="ai_consultant_cta" name="ai_consultant_cta" type="text" value="<?= e($get('ai_consultant_cta', 'Audit My Business')) ?>">
            </div>
            <div class="field span-2">
                <label for="ai_consultant_welcome">Welcome message</label>
                <textarea class="input" id="ai_consultant_welcome" name="ai_consultant_welcome" rows="2"><?= e($get('ai_consultant_welcome', "Hi! Welcome. Tell me a little about your business and what you'd like to automate.")) ?></textarea>
            </div>
        </div>
    </div>

    <div style="display:flex;justify-content:flex-end;margin-top:8px;">
        <button class="btn btn--primary" type="submit"><i class="fa-solid fa-floppy-disk"></i> Save settings</button>
    </div>
</form>