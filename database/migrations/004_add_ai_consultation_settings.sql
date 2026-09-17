-- =====================================================================
-- Migration 004: AI Consultation settings
-- Seeds the AI consultant chat configuration rows into site_settings.
-- Uses INSERT IGNORE so existing user-edited values are never clobbered
-- when this migration is re-applied on an existing install.
-- =====================================================================

INSERT IGNORE INTO site_settings (`key`, `value`, `updated_at`) VALUES
    ('ai_consultation_enabled',  '1',                      NOW()),
    ('ai_consultant_webhook_url', 'YOUR_WEBHOOK_URL_HERE', NOW()),
    ('ai_consultant_name',       'AI Consultant',          NOW()),
    ('ai_consultant_welcome',     'Hi! Welcome. Tell me a little about your business and what you''d like to automate.', NOW()),
    ('ai_consultant_cta',         'Audit My Business',      NOW());