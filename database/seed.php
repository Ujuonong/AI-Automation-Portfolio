<?php

declare(strict_types=1);

/**
 * Development seed script — populates demo content.
 *
 * Usage:
 *   php database/seed.php
 *
 * Run AFTER migrate.php has created all tables.
 */

use Portfolio\Env;

define('BASE_PATH', dirname(__DIR__));

require BASE_PATH . '/app/Env.php';
\Portfolio\Env::load(BASE_PATH . '/.env');

spl_autoload_register(static function (string $class): void {
    $prefix = 'Portfolio\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }
    $relative = substr($class, strlen($prefix));
    $file = BASE_PATH . '/app/' . str_replace('\\', '/', $relative) . '.php';
    if (is_file($file)) {
        require_once $file;
    }
});

$config = require BASE_PATH . '/config/database.php';

try {
    $dsn = sprintf('mysql:host=%s;port=%d;charset=utf8mb4', $config['host'], $config['port']);
    $pdo = new PDO($dsn, $config['username'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    $pdo->exec(sprintf('USE `%s`', $config['database']));

    echo "[Seed] Populating demo content...\n";

    // --- Technologies ---
    $techs = [
        'PHP', 'MySQL', 'Python', 'JavaScript', 'Node.js', 'HTML5', 'CSS3',
        'OpenAI API', 'LangChain', 'RAG', 'n8n', 'Zapier',
        'Bootstrap', 'Tailwind CSS', 'REST APIs', 'Docker', 'Git', 'Linux',
        'FastAPI', 'Flask', 'Vector Databases', 'Pinecone',
    ];
    $techStmt = $pdo->prepare('INSERT IGNORE INTO technologies (name, slug, created_at) VALUES (:name, :slug, NOW())');
    foreach ($techs as $t) {
        $slug = preg_replace('/[^a-z0-9]+/', '-', strtolower($t));
        $techStmt->execute(['name' => $t, 'slug' => trim($slug, '-')]);
    }
    echo "[Seed] Technologies added.\n";

    // --- Site Settings ---
    $settings = [
        'site_name'          => 'Bulus Ujuonong James | DE-JUNONG AI',
        'professional_title' => 'AI Automation Engineer & Consultant',
        'bio'                => 'Building intelligent automation systems for smarter businesses.',
        'email'              => 'info@dejunong.com',
        'phone'              => '+234 XXX XXX XXXX',
        'location'           => 'Nigeria',
        'linkedin_url'       => 'https://linkedin.com/',
        'github_url'         => 'https://github.com/',
        'x_url'              => 'https://x.com/',
        'youtube_url'        => '',
        'whatsapp_url'       => '',
        'profile_image'      => '',
        'resume_file'        => '',
        'favicon'            => '',
        'logo'               => '',
        'og_image'           => '',
        'meta_description'   => 'AI Automation Engineer & Consultant — Building intelligent automation systems for smarter businesses.',
        'ai_consultation_enabled'  => '1',
        'ai_consultant_webhook_url' => 'YOUR_WEBHOOK_URL_HERE',
        'ai_consultant_name'       => 'AI Consultant',
        'ai_consultant_welcome'    => 'Hi! Welcome. Tell me a little about your business and what you\'d like to automate.',
        'ai_consultant_cta'        => 'Audit My Business',
    ];
    $upsert = $pdo->prepare(
        "INSERT INTO site_settings (`key`, `value`, `updated_at`)
         VALUES (:k, :v, NOW())
         ON DUPLICATE KEY UPDATE `value` = :v2, `updated_at` = NOW()"
    );
    foreach ($settings as $key => $value) {
        $upsert->execute(['k' => $key, 'v' => $value, 'v2' => $value]);
    }
    echo "[Seed] Site settings added.\n";

    // --- Services ---
    $services = [
        ['title' => 'AI Automation',                'icon' => 'robot',      'desc' => 'Automating repetitive business processes using AI and workflow automation.'],
        ['title' => 'AI Agents',                    'icon' => 'brain',      'desc' => 'Building intelligent agents capable of interacting with users and business tools.'],
        ['title' => 'AI Customer Support',          'icon' => 'headset',    'desc' => 'AI-powered support systems with knowledge bases and human escalation.'],
        ['title' => 'AI Receptionists',             'icon' => 'bell',       'desc' => 'Automated customer enquiry and booking systems.'],
        ['title' => 'RAG / Knowledge Assistants',  'icon' => 'book',       'desc' => 'Turning business documents and internal knowledge into intelligent assistants.'],
        ['title' => 'Business Process Automation',  'icon' => 'gears',      'desc' => 'Connecting business applications and automating repetitive operations.'],
        ['title' => 'Document Intelligence',        'icon' => 'file-lines', 'desc' => 'Extracting and processing information from business documents.'],
        ['title' => 'Lead Automation',              'icon' => 'chart-line', 'desc' => 'Capturing, qualifying and following up with leads automatically.'],
    ];
    $svcStmt = $pdo->prepare('INSERT IGNORE INTO services (title, slug, short_description, icon, sort_order, published, created_at, updated_at)
                              VALUES (:title, :slug, :desc, :icon, :sort, 1, NOW(), NOW())');
    foreach ($services as $i => $svc) {
        $slug = preg_replace('/[^a-z0-9]+/', '-', strtolower($svc['title']));
        $svcStmt->execute([
            'title' => $svc['title'],
            'slug'  => trim($slug, '-'),
            'desc'  => $svc['desc'],
            'icon'  => $svc['icon'],
            'sort'  => $i,
        ]);
    }
    echo "[Seed] Services added.\n";

    echo "[Seed] Done. Run `php database/seed_admin.php` to create your admin login.\n";
} catch (Throwable $e) {
    fwrite(STDERR, '[Seed] FAILED: ' . $e->getMessage() . "\n");
    exit(1);
}