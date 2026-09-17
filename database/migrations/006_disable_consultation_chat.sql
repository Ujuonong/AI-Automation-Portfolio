-- =====================================================================
-- Migration 006: Disable public chatbot + new contact success message
-- =====================================================================

UPDATE `site_settings`
SET `value` = '0'
WHERE `key` = 'ai_consultation_enabled';

INSERT INTO `content_blocks` (`block_key`, `label`, `value`, `updated_at`)
VALUES ('contact_success', 'Success flash message', 'Thanks for reaching out. I''ve received your request and will review the details before getting back to you.', NOW())
ON DUPLICATE KEY UPDATE
    `value`    = VALUES(`value`),
    `updated_at` = NOW();
