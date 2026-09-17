<?php
/**
 * AI Consultation chat.
 * Vars: $webhook_url, $site, $enabled, $assistant_name, $welcome, $cta
 */
$siteName = (string) ($site['site_name'] ?? 'DE-JUNONG AI');
$hasLogo  = !empty($site['logo']);
?>
<section class="section section--tight">
    <div class="container">
        <div class="consult-head reveal">
            <span class="eyebrow"><?= e(content('consult_page_eyebrow')) ?></span>
            <h1><?= e(content('consult_page_heading')) ?></h1>
            <p class="lead"><?= e(content('consult_page_intro')) ?></p>
        </div>

        <?php if ($enabled): ?>
        <div class="chat" role="application" aria-label="AI consultation chat">
            <header class="chat-header">
                <a class="brand" href="<?= e(url('/')) ?>" aria-label="Back to portfolio">
                    <?php if ($hasLogo): ?>
                    <img class="brand-logo" src="<?= e(upload_url($site['logo'])) ?>" alt="<?= e($siteName) ?>">
                    <?php else: ?>
                    <span class="brand-mark">D</span>
                    <?php endif; ?>
                    <span>
                        DE-JUNONG<small>Bulus Ujuonong James</small>
                    </span>
                </a>
                <div class="chat-header-tools">
                    <span class="chat-status"><span class="dot" aria-hidden="true"></span> <?= e($assistant_name) ?></span>
                    <a class="btn btn--ghost btn--sm" href="<?= e(url('/')) ?>"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i> <?= e(content('consult_back_label')) ?></a>
                </div>
            </header>

            <div class="chat-body" id="chatBody" aria-live="polite">
                <!-- Messages are rendered by consultation.js -->
            </div>

            <form class="chat-form" id="chatForm" novalidate>
                <label class="sr-only" for="chatInput"><?= e(content('consult_placeholder')) ?></label>
                <input class="input chat-input" id="chatInput" type="text" name="message" autocomplete="off"
                       placeholder="<?= e(content('consult_placeholder')) ?>" maxlength="1000" required>
                <button class="btn btn--primary chat-send" id="chatSend" type="submit" disabled aria-label="Send message">
                    <i class="fa-solid fa-paper-plane" aria-hidden="true"></i>
                </button>
            </form>
        </div>
        <?php else: ?>
        <div class="chat">
            <header class="chat-header">
                <a class="brand" href="<?= e(url('/')) ?>" aria-label="Back to portfolio">
                    <?php if ($hasLogo): ?>
                    <img class="brand-logo" src="<?= e(upload_url($site['logo'])) ?>" alt="<?= e($siteName) ?>">
                    <?php else: ?>
                    <span class="brand-mark">D</span>
                    <?php endif; ?>
                    <span>
                        DE-JUNONG<small>Bulus Ujuonong James</small>
                    </span>
                </a>
                <div class="chat-header-tools">
                    <a class="btn btn--ghost btn--sm" href="<?= e(url('/')) ?>"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i> <?= e(content('consult_back_label')) ?></a>
                </div>
            </header>
            <div class="chat-body">
                <div class="chat-state">
                    <h3><?= e($assistant_name) ?> is offline</h3>
                    <p>Start a conversation the classic way instead.</p>
                    <a class="btn btn--primary" href="<?= e(url('/contact')) ?>"><?= e(content('consult_footer_link')) ?></a>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <p class="chat-fallback">
            <?= e(content('consult_footer_note')) ?>
            <a href="<?= e(url('/contact')) ?>"><?= e(content('consult_footer_link')) ?></a>
        </p>
    </div>
</section>

<?php if ($enabled): ?>
<script>
window.PORTFOLIO_CONSULT = {
    webhookUrl: <?= json_encode($webhook_url, JSON_UNESCAPED_SLASHES) ?>,
    source: "portfolio-ai-consultation",
    welcome: <?= json_encode($welcome) ?>,
    error: <?= json_encode(content('consult_error')) ?>,
    contactLabel: <?= json_encode(content('consult_footer_link')) ?>,
    contactUrl: <?= json_encode(url('/contact'), JSON_UNESCAPED_SLASHES) ?>};
</script>
<script src="<?= e(asset('js/consultation.js')) ?>" defer></script>
<?php endif; ?>