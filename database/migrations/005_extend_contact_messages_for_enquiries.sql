-- =====================================================================
-- Migration 005: Extend contact_messages for enquiry-type workflow
-- Adds phone, enquiry_type, preferred audit date/time, additional_info.
-- =====================================================================

ALTER TABLE `contact_messages`
    ADD COLUMN `phone` VARCHAR(30) NULL AFTER `email`,
    ADD COLUMN `enquiry_type` VARCHAR(60) NULL AFTER `company`,
    ADD COLUMN `preferred_audit_date` DATE NULL AFTER `enquiry_type`,
    ADD COLUMN `preferred_audit_time` VARCHAR(5) NULL AFTER `preferred_audit_date`,
    ADD COLUMN `additional_info` TEXT NULL AFTER `message`;
