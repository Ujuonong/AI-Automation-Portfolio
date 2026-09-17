# DE-JUNONG AI | Automation Portfolio

**Bulus Ujuonong James — AI Automation Engineer & Consultant**

A fully-featured personal portfolio CMS built on a **custom PHP framework** (no heavy dependencies) with a complete **admin dashboard**. It powers a live brand site with a blog, services, projects, certifications, contact & consultation forms — and lets the owner manage every piece of content from an intuitive back office.

```
🌐 Live:  https://ujuonongjames.atwebpages.com
📦 Repo:  https://github.com/Ujuonong/AI-Automation-Portfolio
```

![PHP](https://img.shields.io/badge/PHP-8.1%2B-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?logo=mysql&logoColor=white)
![Framework](https://img.shields.io/badge/Custom%20Framework-Homegrown%20MVC-6FB1FC)
![License](https://img.shields.io/badge/License-Proprietary-lightgrey)

---

## Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Project Structure](#project-structure)
- [Public Pages & Routes](#public-pages--routes)
- [Requirements](#requirements)
- [Local Setup (XAMPP)](#local-setup-xampp)
- [Admin Dashboard](#admin-dashboard)
- [Deployment to Shared Hosting](#deployment-to-shared-hosting)
- [Security Notes](#security-notes)
- [Credits](#credits)

---

## Features

### Public Website
- **Responsive portfolio site** — modern, mobile-first UI with a consistent design system ([`public/assets/css/styles.css`](public/assets/css/styles.css)).
- **Home** — hero, featured projects & services, statistics, and latest posts.
- **Services** — service catalog with detail pages and slgified URLs.
- **Projects** — searchable/filterable portfolio with galleries and cover images.
- **Blog** — categories, `{slug}` detail pages, and SEO-ready markup.
- **Certifications & Testimonials** — credential showcase and social proof.
- **Experience & Education** — structured timeline content.
- **Contact form** — server-side validation, CSRF protection, Honeypot spam trap, and a success flow (`/contact` → `ContactController@send`).
- **Consultation booking** — form with date/time rules and an optional (off-by-default) AI assistant webhook.
- **SEO** — auto-generated [`/sitemap.xml`](routes/web.php), [`/robots.txt`](routes/web.php), Open Graph tags, and canonical URLs.

### Admin Dashboard (`/admin`)
Full CRUD back office with an authenticated, permission-gated area:

| Module | What it manages |
|---|---|
| **Projects** | create, edit, delete, duplicate, publish/unpublish, feature toggle, galleries |
| **Certificates** | credential entries with PDF/image uploads |
| **Services** | catalog, descriptions, publish state |
| **Skills** | skill items with levels |
| **Experience / Education** | timeline entries |
| **Testimonials** | quotes, ratings, publish state |
| **Blog** | posts, categories, SEO slug preview |
| **Media Library** | upload & re-use images/PDFs from one place |
| **Messages** | contact form inbox with read/status management |
| **Settings** | site metadata, logo, profile image, resume file, AI consultation config |
| **Content Blocks** | editable text sections (seeded from [`config/content.php`](config/content.php)) |
| **Profile** | account & password management |

---

## Tech Stack

| Layer | Technology | Link |
|---|---|---|
| Backend | **PHP 8.1+** (custom MVC framework, zero Composer runtime deps) | [php.net](https://www.php.net/) |
| Database | **MySQL 8.0 / MariaDB** via PDO | [mysql.com](https://www.mysql.com/) |
| Frontend | **HTML5 / CSS3 / Vanilla JS** (no build step) | [MDN Web Docs](https://developer.mozilla.org/) |
| Local dev | **XAMPP** (Apache + PHP + MySQL) | [apachefriends.org](https://www.apachefriends.org/) |
| Hosting | **AwardSpace** shared hosting (live site) | [awardspace.com](https://www.awardspace.com/) |
| Version control | **Git / GitHub** | [github.com](https://github.com/) |

Key framework components live in [`app/Core`](app/Core): `App` (dispatcher & case-insensitive autoloader), `Router`, `Controller`, `Request`, `Response`, `Session`, `View`, `Database`, `Csrf`.

---

## Project Structure

```
├── app/
│   ├── Core/            # Custom framework (Router, Database, Session, CSRF, View…)
│   ├── Controllers/     # Public + Admin/ controllers
│   ├── Middleware/      # Auth, Guest, CsrfToken
│   ├── Models/          # PDO models (BlogPost, Project, Service, Message…)
│   ├── Services/        # Validator, UploadService, AuthService, SiteService…
│   └── Views/           # public/, admin/, auth/, layouts/, errors/
├── config/              # database.php, app.php, constants.php, content.php…
├── database/
│   ├── migrations/      # 001_create_core_tables.sql … 006_*.sql (schema only)
│   ├── migrate.php      # PHP migration runner
│   ├── seed.php         # demo content seeder
│   ├── seed_content.php # content-blocks seeder
│   └── seed_admin.php   # creates the admin account
├── public/              # document root (index.php, assets/, uploads/)
├── routes/
│   └── web.php          # all URL routes
├── storage/             # runtime logs/uploads (.gitkeep preserved)
├── composer.json        # metadata + autoload (intended for PHP 8.1+)
├── .env.example         # environment template (placeholders only)
└── .htaccess            # Apache rewrite rules
```

> **Note:** real credentials live only in `.env` (never committed). The repository ships `.env.example` with placeholders.

---

## Public Pages & Routes

| URL | Description |
|---|---|
| `/` | Home |
| `/about` | About page |
| `/services` · `/services/{slug}` | Services listing & detail |
| `/projects` · `/projects/{slug}` | Projects listing & detail |
| `/certifications` | Certifications |
| `/experience` · `/education` | Timeline pages |
| `/testimonials` | Testimonials |
| `/blog` · `/blog/{slug}` · `/blog/category/{category}` | Blog |
| `/contact` | Contact form (POST `/contact`) |
| `/consultation` | Consultation request (POST `/consultation`) |
| `/sitemap.xml` · `/robots.txt` | SEO artifacts |

Route definitions: [`routes/web.php`](routes/web.php)

---

## Requirements

- PHP **8.1+** with PDO, mbstring, json, and fileinfo extensions
- MySQL **5.7+ / 8.0** or MariaDB
- Apache with `mod_rewrite` (XAMPP works out of the box)

---

## Local Setup (XAMPP)

### 1. Get the code
```bash
git clone https://github.com/Ujuonong/AI-Automation-Portfolio.git
```
Place the folder inside your XAMPP web root, e.g. `C:\xampp\htdocs\DEjunong Portfolio`.

### 2. Configure the environment
```bash
cp .env.example .env
```
Edit `.env`:
```ini
APP_URL=http://localhost/DEjunong%20Portfolio/public
APP_ENV=development
APP_DEBUG=true

DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=portfolio_cms
DB_USERNAME=root
DB_PASSWORD=

CSRF_KEY=replace_with_a_long_random_string
```

### 3. Create the database & tables
Start Apache + MySQL in the XAMPP Control Panel, then run:
```bash
php database/migrate.php
```

### 4. Seed content & admin account
```bash
php database/seed.php          # optional demo content
php database/seed_content.php  # editable content blocks
php database/seed_admin.php "Your Name" you@example.com your-password
```

### 5. Run the app
Open `http://localhost/DEjunong%20Portfolio/public` in your browser.

---

## Admin Dashboard

- **Login:** `http://localhost/DEjunong%20Portfolio/public/admin/login` (use the credentials you supplied to `seed_admin.php`).
- Every content area (projects, blog, services, media, messages, settings…) is listed in the sidebar.
- **Change your password after first login** via **Settings → Profile**.

> Security: login is protected by bcrypt hashing, CSRF tokens, session management, and rate-limiting (max-attempt lockout) — see [`app/services/AuthService.php`](app/services/AuthService.php) and [`app/middleware`](app/middleware).

---

## Deployment to Shared Hosting

The app is built to run on any PHP 8.1+ shared host. Steps used for the live site (**AwardSpace**):

1. **Create the MySQL database** in your host's control panel. Note the host (e.g. `fdb1028.awardspace.net`), port, database name, and credentials.
2. **Build your `.env`** from `.env.example` and set:
   ```ini
   APP_URL=https://your-domain.com      # or http if no SSL
   DB_HOST=your-db-host                 # e.g. fdb1028.awardspace.net
   DB_PORT=3306
   DB_DATABASE=your_db_name
   DB_USERNAME=your_db_user
   DB_PASSWORD=your_db_password
   ```
3. **Upload the project** (everything from this repository **except** local-only artifacts: `.env`, `*.sql` dumps, `*.zip`, `/dejunong_portal`, `/public/portfolio`) to your web root via FTP or the host file manager.
4. **Import the schema/data** — through phpMyAdmin (or run `php database/migrate.php`):
   - If you have a full dump like `portfolio_cms_hosting.sql`, import it (it's re-import-safe; every table starts with `DROP TABLE IF EXISTS`).
   - Otherwise import the files in [`database/migrations`](database/migrations) in filename order.
5. **Root bootstrap (optional):** if `/` shows a host placeholder page, add a small root `index.php`:
   ```php
   <?php require __DIR__ . '/public/index.php';
   ```
6. **Verify:** load the site, check every route, and confirm `/sitemap.xml` serves.

> **Hosting tip:** the framework now resolves class names case-insensitively, so it works on Linux hosts even when your local Windows copy used different casing.

---

## Security Notes

- `.env` (real DB credentials & CSRF key) is **never committed**.
- Database dumps and package zips (which contain your credentials) are **git-ignored** — check `.gitignore`.
- On the live HTTP site, avoid logging into `/admin` from public/untrusted Wi-Fi; your admin password travels unencrypted until the host supports HTTPS/SSL.

---

## Credits

- **Author:** [Bulus Ujuonong James](https://github.com/Ujuonong) — AI Automation Engineer & Consultant.
- **Live site:** [ujuonongjames.atwebpages.com](https://ujuonongjames.atwebpages.com)
- **Developed with the help of [opencode](https://opencode.ai)** — an AI-powered software engineering assistant that helped design, build, test, and deploy this project from start to finish.

---

## License

**Proprietary** — all rights reserved. This codebase is not licensed for reuse, copying, or redistribution without the author's written permission.

© 2026 Bulus Ujuonong James.