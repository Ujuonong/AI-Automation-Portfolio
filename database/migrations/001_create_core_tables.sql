-- =====================================================================
-- Migration 001: Core portfolio CMS schema
-- Engine: InnoDB / utf8mb4
-- =====================================================================

-- ---------------------------------------------------------------
-- users
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
    `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`          VARCHAR(150) NOT NULL,
    `email`         VARCHAR(190) NOT NULL,
    `password`      VARCHAR(255) NOT NULL,
    `role`          ENUM('admin') NOT NULL DEFAULT 'admin',
    `status`        ENUM('active','suspended') NOT NULL DEFAULT 'active',
    `last_login_at` DATETIME NULL,
    `created_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_users_email` (`email`),
    KEY `idx_users_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------
-- projects
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `projects` (
    `id`                INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title`             VARCHAR(255) NOT NULL,
    `slug`              VARCHAR(255) NOT NULL,
    `short_description` TEXT NULL,
    `description`       MEDIUMTEXT NULL,
    `problem`           MEDIUMTEXT NULL,
    `solution`          MEDIUMTEXT NULL,
    `architecture`      MEDIUMTEXT NULL,
    `results`           MEDIUMTEXT NULL,
    `project_type`      VARCHAR(60) NOT NULL DEFAULT 'other',
    `status`            ENUM('completed','in_progress','coming_soon') NOT NULL DEFAULT 'completed',
    `featured`          TINYINT(1) NOT NULL DEFAULT 0,
    `published`         TINYINT(1) NOT NULL DEFAULT 0,
    `github_url`        VARCHAR(500) NULL,
    `demo_url`          VARCHAR(500) NULL,
    `video_url`         VARCHAR(500) NULL,
    `cover_image`       VARCHAR(255) NULL,
    `created_at`        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_projects_slug` (`slug`),
    KEY `idx_projects_status` (`status`),
    KEY `idx_projects_published` (`published`),
    KEY `idx_projects_featured` (`featured`),
    KEY `idx_projects_title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------
-- project_media
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `project_media` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `project_id` INT UNSIGNED NOT NULL,
    `file_path`  VARCHAR(255) NOT NULL,
    `media_type` ENUM('image','video','document') NOT NULL DEFAULT 'image',
    `caption`    VARCHAR(255) NULL,
    `sort_order` INT NOT NULL DEFAULT 0,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_project_media_project` (`project_id`),
    CONSTRAINT `fk_media_project`
        FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------
-- technologies
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `technologies` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`       VARCHAR(120) NOT NULL,
    `slug`       VARCHAR(140) NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_technologies_slug` (`slug`),
    UNIQUE KEY `uq_technologies_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------
-- project_technologies (many-to-many)
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `project_technologies` (
    `project_id`    INT UNSIGNED NOT NULL,
    `technology_id` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`project_id`, `technology_id`),
    KEY `idx_pt_technology` (`technology_id`),
    CONSTRAINT `fk_pt_project`
        FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_pt_technology`
        FOREIGN KEY (`technology_id`) REFERENCES `technologies` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------
-- certificates
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `certificates` (
    `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title`           VARCHAR(255) NOT NULL,
    `issuer`          VARCHAR(255) NOT NULL,
    `description`     TEXT NULL,
    `issue_date`      DATE NULL,
    `credential_id`   VARCHAR(190) NULL,
    `credential_url`  VARCHAR(500) NULL,
    `certificate_file` VARCHAR(255) NULL,
    `thumbnail`       VARCHAR(255) NULL,
    `featured`        TINYINT(1) NOT NULL DEFAULT 0,
    `published`       TINYINT(1) NOT NULL DEFAULT 0,
    `created_at`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_certificates_published` (`published`),
    KEY `idx_certificates_featured` (`featured`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------
-- services
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `services` (
    `id`                INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title`             VARCHAR(255) NOT NULL,
    `slug`              VARCHAR(255) NOT NULL,
    `short_description` TEXT NULL,
    `description`       MEDIUMTEXT NULL,
    `icon`              VARCHAR(60) NOT NULL DEFAULT 'rocket',
    `sort_order`        INT NOT NULL DEFAULT 0,
    `published`         TINYINT(1) NOT NULL DEFAULT 0,
    `created_at`        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_services_slug` (`slug`),
    KEY `idx_services_published` (`published`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------
-- skills
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `skills` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`        VARCHAR(150) NOT NULL,
    `category`    VARCHAR(100) NOT NULL DEFAULT 'General',
    `proficiency` TINYINT UNSIGNED NOT NULL DEFAULT 80,
    `icon`        VARCHAR(60) NULL,
    `sort_order`  INT NOT NULL DEFAULT 0,
    `published`   TINYINT(1) NOT NULL DEFAULT 0,
    `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_skills_category` (`category`),
    KEY `idx_skills_published` (`published`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------
-- experiences
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `experiences` (
    `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `position`        VARCHAR(255) NOT NULL,
    `organization`    VARCHAR(255) NOT NULL,
    `employment_type` VARCHAR(60) NULL,
    `location`        VARCHAR(150) NULL,
    `start_date`      DATE NULL,
    `end_date`        DATE NULL,
    `description`     TEXT NULL,
    `sort_order`      INT NOT NULL DEFAULT 0,
    `published`       TINYINT(1) NOT NULL DEFAULT 0,
    `created_at`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_experiences_published` (`published`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------
-- education
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `education` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `institution` VARCHAR(255) NOT NULL,
    `degree`      VARCHAR(255) NULL,
    `field`       VARCHAR(255) NULL,
    `start_date`  DATE NULL,
    `end_date`    DATE NULL,
    `description` TEXT NULL,
    `sort_order`  INT NOT NULL DEFAULT 0,
    `published`   TINYINT(1) NOT NULL DEFAULT 0,
    `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_education_published` (`published`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------
-- testimonials
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `testimonials` (
    `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `client_name`  VARCHAR(150) NOT NULL,
    `client_role`  VARCHAR(150) NULL,
    `company`      VARCHAR(150) NULL,
    `testimonial`  TEXT NOT NULL,
    `client_image` VARCHAR(255) NULL,
    `published`    TINYINT(1) NOT NULL DEFAULT 0,
    `featured`     TINYINT(1) NOT NULL DEFAULT 0,
    `created_at`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_testimonials_published` (`published`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------
-- blog_posts
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `blog_posts` (
    `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title`        VARCHAR(255) NOT NULL,
    `slug`         VARCHAR(255) NOT NULL,
    `excerpt`      TEXT NULL,
    `content`      MEDIUMTEXT NULL,
    `cover_image`  VARCHAR(255) NULL,
    `category`     VARCHAR(120) NULL,
    `published`    TINYINT(1) NOT NULL DEFAULT 0,
    `published_at` DATETIME NULL,
    `created_at`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_blog_posts_slug` (`slug`),
    KEY `idx_blog_posts_published` (`published`),
    KEY `idx_blog_posts_category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------
-- contact_messages
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `contact_messages` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`       VARCHAR(150) NOT NULL,
    `email`      VARCHAR(190) NOT NULL,
    `company`    VARCHAR(150) NULL,
    `subject`    VARCHAR(255) NOT NULL,
    `message`    TEXT NOT NULL,
    `status`     ENUM('new','read','replied','archived') NOT NULL DEFAULT 'new',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_messages_status` (`status`),
    KEY `idx_messages_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------
-- site_settings
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `site_settings` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `key`        VARCHAR(120) NOT NULL,
    `value`      TEXT NULL,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_site_settings_key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------
-- media (media library registry)
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `media` (
    `id`                INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `original_filename` VARCHAR(255) NOT NULL,
    `stored_path`       VARCHAR(255) NOT NULL,
    `file_type`         ENUM('image','document') NOT NULL DEFAULT 'image',
    `mime_type`         VARCHAR(120) NULL,
    `size`              BIGINT UNSIGNED NOT NULL DEFAULT 0,
    `category`          VARCHAR(60) NOT NULL DEFAULT 'misc',
    `created_at`        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_media_stored_path` (`stored_path`),
    KEY `idx_media_type` (`file_type`),
    KEY `idx_media_category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;