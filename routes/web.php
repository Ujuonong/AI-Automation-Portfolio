<?php

declare(strict_types=1);

/**
 * Application routes.
 *
 * Public routes
 */

// Home
$router->get('/', 'HomeController@index');

// About
$router->get('/about', 'PageController@about');

// Services
$router->get('/services', 'ServiceController@index');
$router->get('/services/{slug}', 'ServiceController@show');

// Projects
$router->get('/projects', 'ProjectController@index');
$router->get('/projects/{slug}', 'ProjectController@show');

// Certificates
$router->get('/certifications', 'CertificateController@index');

// Experience & Education
$router->get('/experience', 'PageController@experience');
$router->get('/education', 'PageController@education');

// Testimonials
$router->get('/testimonials', 'TestimonialController@index');

// Blog
$router->get('/blog', 'BlogController@index');
$router->get('/blog/category/{category}', 'BlogController@category');
$router->get('/blog/{slug}', 'BlogController@show');

// Contact
$router->get('/contact', 'ContactController@index');
$router->post('/contact', 'ContactController@send');

// AI Consultation (audit chat)
$router->get('/consultation', 'ConsultationController@index');

// SEO
$router->get('/sitemap.xml', 'SeoController@sitemap');
$router->get('/robots.txt', 'SeoController@robots');

/**
 * Authentication
 */
$router->get('/admin/login', 'AuthController@showLogin', ['Guest']);
$router->post('/admin/login', 'AuthController@login', ['Guest']);
$router->post('/admin/logout', 'AuthController@logout', ['Auth']);

/**
 * Admin
 */
$router->get('/admin', 'Admin\DashboardController@index', ['Auth']);
$router->get('/admin/dashboard', 'Admin\DashboardController@index', ['Auth']);

// Projects
$router->get('/admin/projects', 'Admin\ProjectController@index', ['Auth']);
$router->get('/admin/projects/create', 'Admin\ProjectController@create', ['Auth']);
$router->post('/admin/projects', 'Admin\ProjectController@store', ['Auth']);
$router->get('/admin/projects/edit/{id}', 'Admin\ProjectController@edit', ['Auth']);
$router->post('/admin/projects/{id}', 'Admin\ProjectController@update', ['Auth']);
$router->post('/admin/projects/{id}/delete', 'Admin\ProjectController@destroy', ['Auth']);
$router->post('/admin/projects/{id}/toggle-published', 'Admin\ProjectController@togglePublished', ['Auth']);
$router->post('/admin/projects/{id}/toggle-featured', 'Admin\ProjectController@toggleFeatured', ['Auth']);
$router->post('/admin/projects/{id}/duplicate', 'Admin\ProjectController@duplicate', ['Auth']);

// Certificates
$router->get('/admin/certificates', 'Admin\CertificateController@index', ['Auth']);
$router->get('/admin/certificates/create', 'Admin\CertificateController@create', ['Auth']);
$router->post('/admin/certificates', 'Admin\CertificateController@store', ['Auth']);
$router->get('/admin/certificates/edit/{id}', 'Admin\CertificateController@edit', ['Auth']);
$router->post('/admin/certificates/{id}', 'Admin\CertificateController@update', ['Auth']);
$router->post('/admin/certificates/{id}/delete', 'Admin\CertificateController@destroy', ['Auth']);
$router->post('/admin/certificates/{id}/toggle-published', 'Admin\CertificateController@togglePublished', ['Auth']);
$router->post('/admin/certificates/{id}/toggle-featured', 'Admin\CertificateController@toggleFeatured', ['Auth']);

// Services
$router->get('/admin/services', 'Admin\ServiceController@index', ['Auth']);
$router->get('/admin/services/create', 'Admin\ServiceController@create', ['Auth']);
$router->post('/admin/services', 'Admin\ServiceController@store', ['Auth']);
$router->get('/admin/services/edit/{id}', 'Admin\ServiceController@edit', ['Auth']);
$router->post('/admin/services/{id}', 'Admin\ServiceController@update', ['Auth']);
$router->post('/admin/services/{id}/delete', 'Admin\ServiceController@destroy', ['Auth']);
$router->post('/admin/services/{id}/toggle-published', 'Admin\ServiceController@togglePublished', ['Auth']);

// Skills
$router->get('/admin/skills', 'Admin\SkillController@index', ['Auth']);
$router->get('/admin/skills/create', 'Admin\SkillController@create', ['Auth']);
$router->post('/admin/skills', 'Admin\SkillController@store', ['Auth']);
$router->get('/admin/skills/edit/{id}', 'Admin\SkillController@edit', ['Auth']);
$router->post('/admin/skills/{id}', 'Admin\SkillController@update', ['Auth']);
$router->post('/admin/skills/{id}/delete', 'Admin\SkillController@destroy', ['Auth']);
$router->post('/admin/skills/{id}/toggle-published', 'Admin\SkillController@togglePublished', ['Auth']);

// Experience
$router->get('/admin/experience', 'Admin\ExperienceController@index', ['Auth']);
$router->get('/admin/experience/create', 'Admin\ExperienceController@create', ['Auth']);
$router->post('/admin/experience', 'Admin\ExperienceController@store', ['Auth']);
$router->get('/admin/experience/edit/{id}', 'Admin\ExperienceController@edit', ['Auth']);
$router->post('/admin/experience/{id}', 'Admin\ExperienceController@update', ['Auth']);
$router->post('/admin/experience/{id}/delete', 'Admin\ExperienceController@destroy', ['Auth']);
$router->post('/admin/experience/{id}/toggle-published', 'Admin\ExperienceController@togglePublished', ['Auth']);

// Education
$router->get('/admin/education', 'Admin\EducationController@index', ['Auth']);
$router->get('/admin/education/create', 'Admin\EducationController@create', ['Auth']);
$router->post('/admin/education', 'Admin\EducationController@store', ['Auth']);
$router->get('/admin/education/edit/{id}', 'Admin\EducationController@edit', ['Auth']);
$router->post('/admin/education/{id}', 'Admin\EducationController@update', ['Auth']);
$router->post('/admin/education/{id}/delete', 'Admin\EducationController@destroy', ['Auth']);
$router->post('/admin/education/{id}/toggle-published', 'Admin\EducationController@togglePublished', ['Auth']);

// Testimonials
$router->get('/admin/testimonials', 'Admin\TestimonialController@index', ['Auth']);
$router->get('/admin/testimonials/create', 'Admin\TestimonialController@create', ['Auth']);
$router->post('/admin/testimonials', 'Admin\TestimonialController@store', ['Auth']);
$router->get('/admin/testimonials/edit/{id}', 'Admin\TestimonialController@edit', ['Auth']);
$router->post('/admin/testimonials/{id}', 'Admin\TestimonialController@update', ['Auth']);
$router->post('/admin/testimonials/{id}/delete', 'Admin\TestimonialController@destroy', ['Auth']);
$router->post('/admin/testimonials/{id}/toggle-published', 'Admin\TestimonialController@togglePublished', ['Auth']);

// Blog
$router->get('/admin/blog', 'Admin\BlogController@index', ['Auth']);
$router->get('/admin/blog/create', 'Admin\BlogController@create', ['Auth']);
$router->post('/admin/blog', 'Admin\BlogController@store', ['Auth']);
$router->get('/admin/blog/edit/{id}', 'Admin\BlogController@edit', ['Auth']);
$router->post('/admin/blog/{id}', 'Admin\BlogController@update', ['Auth']);
$router->post('/admin/blog/{id}/delete', 'Admin\BlogController@destroy', ['Auth']);
$router->post('/admin/blog/{id}/toggle-published', 'Admin\BlogController@togglePublished', ['Auth']);

// Media
$router->get('/admin/media', 'Admin\MediaController@index', ['Auth']);
$router->post('/admin/media', 'Admin\MediaController@store', ['Auth']);
$router->post('/admin/media/{id}/delete', 'Admin\MediaController@destroy', ['Auth']);

// Messages
$router->get('/admin/messages', 'Admin\MessageController@index', ['Auth']);
$router->get('/admin/messages/{id}', 'Admin\MessageController@show', ['Auth']);
$router->post('/admin/messages/{id}/status', 'Admin\MessageController@status', ['Auth']);
$router->post('/admin/messages/{id}/delete', 'Admin\MessageController@destroy', ['Auth']);

// Settings
$router->get('/admin/settings', 'Admin\SettingsController@index', ['Auth']);
$router->post('/admin/settings', 'Admin\SettingsController@update', ['Auth']);

// Website content (page copy)
$router->get('/admin/content', 'Admin\ContentController@index', ['Auth']);
$router->post('/admin/content', 'Admin\ContentController@update', ['Auth']);

// Profile
$router->get('/admin/profile', 'Admin\ProfileController@index', ['Auth']);
$router->post('/admin/profile', 'Admin\ProfileController@update', ['Auth']);