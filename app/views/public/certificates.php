<?php
/**
 * Certifications page.
 * Vars: $certificates
 */
?>
<section class="section section--tight">
    <div class="container container--narrow">
        <div class="eyebrow"><?= e(content('certs_eyebrow')) ?></div>
        <h1><?= e(content('certs_heading')) ?></h1>
        <p class="lead"><?= e(content('certs_intro')) ?></p>
    </div>
</section>

<section class="section section--tight">
    <div class="container">
        <?php if (!$certificates): ?>
            <p class="muted text-center mt-3"><?= e(content('certs_empty')) ?></p>
        <?php endif; ?>

        <div class="grid grid--3">
            <?php foreach ($certificates as $cert): ?>
            <article class="cert reveal">
                <div class="cert-icon"><i class="fa-solid fa-award" aria-hidden="true"></i></div>
                <h3><?= e($cert['title']) ?></h3>
                <p class="cert-issuer"><?= e($cert['issuer']) ?></p>
                <p class="cert-meta">
                    <?= $cert['issue_date'] ? 'Issued ' . e(format_date((string) $cert['issue_date'])) : '' ?>
                    <?= $cert['credential_id'] ? ' · ID ' . e($cert['credential_id']) : '' ?>
                </p>
                <?php if ($cert['description']): ?><p class="card-text"><?= e(truncate($cert['description'], 150)) ?></p><?php endif; ?>
                <div class="card-foot tags" style="margin-top:auto;padding-top:8px;">
                    <?php if ($cert['credential_url']): ?>
                    <a class="btn btn--outline-accent btn--sm" href="<?= e($cert['credential_url']) ?>" target="_blank" rel="noopener nofollow"><?= e(content('certs_verify')) ?></a>
                    <?php endif; ?>
                    <?php if ($cert['thumbnail']): ?>
                    <button type="button" class="btn btn--ghost btn--sm" onclick="openModal('cert-<?= (int) $cert['id'] ?>')">View certificate</button>
                    <?php endif; ?>
                    <?php if ($cert['certificate_file']): ?>
                    <a class="btn btn--ghost btn--sm" href="<?= e(upload_url($cert['certificate_file'])) ?>" target="_blank" rel="noopener">PDF</a>
                    <?php endif; ?>
                </div>
            </article>

            <?php if ($cert['thumbnail']): ?>
            <div class="modal-overlay" id="cert-<?= (int) $cert['id'] ?>" role="dialog" aria-modal="true" aria-label="<?= e($cert['title']) ?>"
                 onclick="if(event.target===this)closeModal('cert-<?= (int) $cert['id'] ?>')">
                <div class="modal">
                    <div class="modal-header">
                        <strong><?= e($cert['title']) ?> — <?= e($cert['issuer']) ?></strong>
                        <button type="button" class="btn btn--ghost btn--sm" onclick="closeModal('cert-<?= (int) $cert['id'] ?>')">✕ Close</button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="<?= e(upload_url($cert['thumbnail'])) ?>" alt="<?= e($cert['title']) ?> certificate preview" style="border-radius:12px;max-height:70vh;margin:0 auto;">
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section section--tight section-alt">
    <div class="container">
        <div class="cta reveal">
            <h2><?= e(content('certs_cta_heading')) ?></h2>
            <a class="btn btn--primary btn--lg" href="<?= e(url('/contact')) ?>"><?= e(content('certs_cta_button')) ?></a>
        </div>
    </div>
</section>