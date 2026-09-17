<?php

declare(strict_types=1);

/**
 * Editable website content blocks.
 *
 * Each entry: key, label, type (text | textarea | textarea_lines), group, default.
 * Keys are stable identifiers referenced by the public views via content('key').
 */

/*
 * Shared default text body reused by several lines.
 */
const _PORTFOLIO_HERO_LEAD = 'I design and build AI-powered automation systems that help businesses reduce repetitive work, improve customer experiences, and operate more efficiently.';

function _content_block_defaults(): array
{
    return [
        // ------------------------------------------------------------------
        // Global
        // ------------------------------------------------------------------
        ['key' => 'nav_cta_label',             'label' => 'Navbar button text',                 'type' => 'text',          'group' => 'Global', 'default' => 'Work With Me'],
        ['key' => 'footer_tagline',            'label' => 'Footer tagline',                     'type' => 'text',          'group' => 'Global', 'default' => 'Built with DE-JUNONG AI'],
        ['key' => 'footer_explore_heading',    'label' => 'Footer “Explore” heading',           'type' => 'text',          'group' => 'Global', 'default' => 'Explore'],
        ['key' => 'footer_contact_heading',    'label' => 'Footer “Contact” heading',           'type' => 'text',          'group' => 'Global', 'default' => 'Contact'],

        // ------------------------------------------------------------------
        // Home — hero
        // ------------------------------------------------------------------
        ['key' => 'hero_badge',                'label' => 'Availability badge',                 'type' => 'text',          'group' => 'Home — Hero', 'default' => 'Available for automation projects'],
        ['key' => 'hero_heading',              'label' => 'Main headline (H1)',                 'type' => 'text',          'group' => 'Home — Hero', 'default' => 'Building intelligent automation systems for smarter businesses.'],
        ['key' => 'hero_lead',                 'label' => 'Intro paragraph',                    'type' => 'textarea',      'group' => 'Home — Hero', 'default' => _PORTFOLIO_HERO_LEAD],
        ['key' => 'hero_primary_cta',          'label' => 'Primary button',                     'type' => 'text',          'group' => 'Home — Hero', 'default' => 'View My Work'],
        ['key' => 'hero_secondary_cta',        'label' => 'Secondary button',                   'type' => 'text',          'group' => 'Home — Hero', 'default' => 'Work With Me'],
        ['key' => 'hero_download_cv',          'label' => 'Download CV button',                 'type' => 'text',          'group' => 'Home — Hero', 'default' => 'Download CV'],
        ['key' => 'hero_stat_projects',        'label' => 'Stat label — Projects',              'type' => 'text',          'group' => 'Home — Hero', 'default' => 'Projects'],
        ['key' => 'hero_stat_skills',          'label' => 'Stat label — Skills',                'type' => 'text',          'group' => 'Home — Hero', 'default' => 'Skills'],
        ['key' => 'hero_stat_certifications',  'label' => 'Stat label — Certifications',        'type' => 'text',          'group' => 'Home — Hero', 'default' => 'Certifications'],
        ['key' => 'hero_stat_services',        'label' => 'Stat label — Services',              'type' => 'text',          'group' => 'Home — Hero', 'default' => 'Services'],

        // ------------------------------------------------------------------
        // Home — sections
        // ------------------------------------------------------------------
        ['key' => 'home_featured_eyebrow',     'label' => 'Featured projects — eyebrow',        'type' => 'text',          'group' => 'Home — Sections', 'default' => 'Selected work'],
        ['key' => 'home_featured_heading',     'label' => 'Featured projects — heading',        'type' => 'text',          'group' => 'Home — Sections', 'default' => 'Featured projects'],
        ['key' => 'home_featured_intro',       'label' => 'Featured projects — intro',          'type' => 'textarea',      'group' => 'Home — Sections', 'default' => 'Real systems built to remove manual work and create smarter business operations.'],
        ['key' => 'home_services_eyebrow',     'label' => 'Services — eyebrow',                 'type' => 'text',          'group' => 'Home — Sections', 'default' => 'What I do'],
        ['key' => 'home_services_heading',     'label' => 'Services — heading',                 'type' => 'text',          'group' => 'Home — Sections', 'default' => 'Services'],
        ['key' => 'home_services_intro',       'label' => 'Services — intro',                   'type' => 'textarea',      'group' => 'Home — Sections', 'default' => 'Automation, AI agents and intelligent systems tailored to real business problems.'],
        ['key' => 'home_services_learn_more',  'label' => 'Services — “Learn more” link',       'type' => 'text',          'group' => 'Home — Sections', 'default' => 'Learn more'],
        ['key' => 'home_skills_eyebrow',       'label' => 'Skills — eyebrow',                   'type' => 'text',          'group' => 'Home — Sections', 'default' => 'Capabilities'],
        ['key' => 'home_skills_heading',       'label' => 'Skills — heading',                   'type' => 'text',          'group' => 'Home — Sections', 'default' => 'Skills & technologies'],
        ['key' => 'home_skills_intro',         'label' => 'Skills — intro',                     'type' => 'textarea',      'group' => 'Home — Sections', 'default' => 'Tools and techniques I apply when building automation systems.'],
        ['key' => 'home_about_eyebrow',        'label' => 'About — eyebrow',                    'type' => 'text',          'group' => 'Home — Sections', 'default' => 'About'],
        ['key' => 'home_about_heading',        'label' => 'About — heading',                    'type' => 'text',          'group' => 'Home — Sections', 'default' => 'I help businesses automate the work that slows them down.'],
        ['key' => 'home_about_text',           'label' => 'About — intro text',                 'type' => 'textarea',      'group' => 'Home — Sections', 'default' => 'I design and build AI-powered automation systems covering AI agents, customer support, knowledge assistants and business process automation.'],
        ['key' => 'home_about_primary_cta',    'label' => 'About — primary button',             'type' => 'text',          'group' => 'Home — Sections', 'default' => 'More about me'],
        ['key' => 'home_about_secondary_cta',  'label' => 'About — secondary button',           'type' => 'text',          'group' => 'Home — Sections', 'default' => 'Experience'],
        ['key' => 'home_blog_eyebrow',         'label' => 'Blog — eyebrow',                     'type' => 'text',          'group' => 'Home — Sections', 'default' => 'Insights'],
        ['key' => 'home_blog_heading',         'label' => 'Blog — heading',                     'type' => 'text',          'group' => 'Home — Sections', 'default' => 'Latest from the blog'],
        ['key' => 'home_cta_heading',          'label' => 'Closing CTA — heading',              'type' => 'text',          'group' => 'Home — Sections', 'default' => 'Have a process that could be automated?'],
        ['key' => 'home_cta_text',             'label' => 'Closing CTA — text',                 'type' => 'textarea',      'group' => 'Home — Sections', 'default' => "Tell me about the repetitive work your team is doing by hand. Let's map out how AI can handle it."],
        ['key' => 'home_cta_button',           'label' => 'Closing CTA — button',               'type' => 'text',          'group' => 'Home — Sections', 'default' => "Let's Talk"],

        // ------------------------------------------------------------------
        // About page
        // ------------------------------------------------------------------
        ['key' => 'about_eyebrow',             'label' => 'Page eyebrow',                       'type' => 'text',          'group' => 'About', 'default' => 'About me'],
        ['key' => 'about_intro_eyebrow',      'label' => 'Introduction section eyebrow',        'type' => 'text',          'group' => 'About', 'default' => 'Introduction'],
        ['key' => 'about_intro_heading',       'label' => 'Introduction heading',               'type' => 'text',          'group' => 'About', 'default' => 'Building intelligent automation systems for smarter businesses.'],
        ['key' => 'about_intro_paragraph',     'label' => 'Introduction paragraph',             'type' => 'textarea',      'group' => 'About', 'default' => "I focus on the intersection of applied AI and workflow automation. That means studying how a business actually operates today, finding the repetitive, rule-based or document-heavy work, and building systems — agents, assistants, integrations — that reliably remove that burden."],
        ['key' => 'about_focus_heading',       'label' => 'Professional focus heading',          'type' => 'text',          'group' => 'About', 'default' => 'Professional focus'],
        ['key' => 'about_focus_items',         'label' => 'Professional focus items (one per line)', 'type' => 'textarea_lines', 'group' => 'About', 'default' => "AI agents that act on business tools and data\nCustomer support and enquiry automation with human escalation\nRAG / knowledge assistants built from business documents\nBusiness process automation and system integrations\nDocument intelligence and lead automation"],
        ['key' => 'about_approach_heading',    'label' => 'Approach heading',                   'type' => 'text',          'group' => 'About', 'default' => 'Approach'],
        ['key' => 'about_approach_text',       'label' => 'Approach paragraph',                 'type' => 'textarea',      'group' => 'About', 'default' => 'Every engagement starts with the business problem, not the technology. I map the process end to end, agree on measurable outcomes, prototype quickly, and ship systems that are monitored and iterated on.'],
        ['key' => 'about_experience_eyebrow',  'label' => 'Experience — eyebrow',               'type' => 'text',          'group' => 'About', 'default' => 'Experience'],
        ['key' => 'about_experience_heading',  'label' => 'Experience — heading',               'type' => 'text',          'group' => 'About', 'default' => 'Selected experience'],
        ['key' => 'about_education_heading',   'label' => 'Education — heading',                'type' => 'text',          'group' => 'About', 'default' => 'Education'],
        ['key' => 'about_skills_eyebrow',      'label' => 'Skills — eyebrow',                   'type' => 'text',          'group' => 'About', 'default' => 'Areas of expertise'],
        ['key' => 'about_skills_heading',      'label' => 'Skills — heading',                   'type' => 'text',          'group' => 'About', 'default' => 'Skills'],
        ['key' => 'about_cta_heading',         'label' => 'Closing CTA — heading',              'type' => 'text',          'group' => 'About', 'default' => 'Want to talk about an automation project?'],
        ['key' => 'about_cta_text',            'label' => 'Closing CTA — text',                 'type' => 'textarea',      'group' => 'About', 'default' => "Share the business problem. I'll help you see what's possible."],
        ['key' => 'about_cta_button',          'label' => 'Closing CTA — button',               'type' => 'text',          'group' => 'About', 'default' => "Let's Talk"],

        // ------------------------------------------------------------------
        // Services
        // ------------------------------------------------------------------
        ['key' => 'services_eyebrow',          'label' => 'Page eyebrow',                       'type' => 'text',          'group' => 'Services', 'default' => 'Services'],
        ['key' => 'services_heading',          'label' => 'Page heading (H1)',                  'type' => 'text',          'group' => 'Services', 'default' => 'Automation services built around real business problems'],
        ['key' => 'services_intro',            'label' => 'Intro paragraph',                    'type' => 'textarea',      'group' => 'Services', 'default' => 'From AI agents and support automation to document intelligence and full business process automation — each service starts with the process you want to improve.'],
        ['key' => 'services_empty',            'label' => 'Empty state message',                'type' => 'text',          'group' => 'Services', 'default' => 'Services are being updated. Check back soon.'],
        ['key' => 'services_card_cta',         'label' => 'Card button text',                     'type' => 'text',          'group' => 'Services', 'default' => 'Explore'],
        ['key' => 'services_related_eyebrow',  'label' => 'Related services eyebrow',           'type' => 'text',          'group' => 'Services', 'default' => 'Related services'],
        ['key' => 'services_cta_heading',      'label' => 'Closing CTA — heading',              'type' => 'text',          'group' => 'Services', 'default' => 'Not sure which service fits your process?'],
        ['key' => 'services_cta_text',         'label' => 'Closing CTA — text',                 'type' => 'textarea',      'group' => 'Services', 'default' => "Describe what your team does by hand today — I'll recommend the right automation approach."],
        ['key' => 'services_cta_button',       'label' => 'Closing CTA — button',               'type' => 'text',          'group' => 'Services', 'default' => "Let's Talk"],
        ['key' => 'services_show_cta_heading', 'label' => 'Service page CTA — heading',         'type' => 'text',          'group' => 'Services', 'default' => 'Ready to put this into practice in your business?'],
        ['key' => 'services_show_cta_button',  'label' => 'Service page CTA — button',          'type' => 'text',          'group' => 'Services', 'default' => 'Get in touch'],

        // ------------------------------------------------------------------
        // Projects
        // ------------------------------------------------------------------
        ['key' => 'projects_eyebrow',          'label' => 'Page eyebrow',                       'type' => 'text',          'group' => 'Projects', 'default' => 'Portfolio'],
        ['key' => 'projects_heading',          'label' => 'Page heading (H1)',                  'type' => 'text',          'group' => 'Projects', 'default' => 'Projects'],
        ['key' => 'projects_intro',            'label' => 'Intro paragraph',                    'type' => 'textarea',      'group' => 'Projects', 'default' => 'Case studies of automation systems built end to end — from business problem to deployed solution.'],
        ['key' => 'projects_empty',            'label' => 'Empty state message',                'type' => 'text',          'group' => 'Projects', 'default' => 'New projects are being added soon.'],
        ['key' => 'projects_empty_search',     'label' => 'Empty — “try a search” hint',        'type' => 'text',          'group' => 'Projects', 'default' => 'Try a different search.'],
        ['key' => 'projects_view_case',        'label' => 'Card button text',                   'type' => 'text',          'group' => 'Projects', 'default' => 'View Case Study'],
        ['key' => 'projects_filter',           'label' => 'Filter button',                      'type' => 'text',          'group' => 'Projects', 'default' => 'Filter'],
        ['key' => 'projects_clear',            'label' => 'Clear button',                       'type' => 'text',          'group' => 'Projects', 'default' => 'Clear'],
        ['key' => 'projects_filter_types',     'label' => 'Filter — “All types” label',         'type' => 'text',          'group' => 'Projects', 'default' => 'All types'],
        ['key' => 'projects_filter_statuses',  'label' => 'Filter — “All statuses” label',      'type' => 'text',          'group' => 'Projects', 'default' => 'All statuses'],
        ['key' => 'projects_filter_technologies', 'label' => 'Filter — “All technologies” label', 'type' => 'text',       'group' => 'Projects', 'default' => 'All technologies'],
        ['key' => 'projects_search_placeholder','label' => 'Search placeholder',                'type' => 'text',          'group' => 'Projects', 'default' => 'Search projects...'],
        ['key' => 'projects_show_cta_heading', 'label' => 'Case study CTA — heading',           'type' => 'text',          'group' => 'Projects', 'default' => "Have a similar process you'd like to automate?"],
        ['key' => 'projects_show_cta_text',    'label' => 'Case study CTA — text',              'type' => 'textarea',      'group' => 'Projects', 'default' => "Let's discuss turning your business problem into an automation system."],
        ['key' => 'projects_show_cta_button',  'label' => 'Case study CTA — button',            'type' => 'text',          'group' => 'Projects', 'default' => "Let's Talk"],
        ['key' => 'projects_show_next',        'label' => '“Next project” button',              'type' => 'text',          'group' => 'Projects', 'default' => 'Next project'],

        // ------------------------------------------------------------------
        // Certifications
        // ------------------------------------------------------------------
        ['key' => 'certs_eyebrow',             'label' => 'Page eyebrow',                       'type' => 'text',          'group' => 'Certifications', 'default' => 'Certifications'],
        ['key' => 'certs_heading',             'label' => 'Page heading (H1)',                  'type' => 'text',          'group' => 'Certifications', 'default' => 'Certifications & credentials'],
        ['key' => 'certs_intro',               'label' => 'Intro paragraph',                    'type' => 'textarea',      'group' => 'Certifications', 'default' => 'Training and certifications across AI, automation and software engineering.'],
        ['key' => 'certs_empty',               'label' => 'Empty state message',                'type' => 'text',          'group' => 'Certifications', 'default' => 'Certifications are being updated. Check back soon.'],
        ['key' => 'certs_verify',              'label' => '‘Verify credential’ button',         'type' => 'text',          'group' => 'Certifications', 'default' => 'Verify credential'],
        ['key' => 'certs_cta_heading',         'label' => 'Closing CTA — heading',              'type' => 'text',          'group' => 'Certifications', 'default' => 'Curious how AI automation could apply to your business?'],
        ['key' => 'certs_cta_button',          'label' => 'Closing CTA — button',               'type' => 'text',          'group' => 'Certifications', 'default' => "Let's Talk"],

        // ------------------------------------------------------------------
        // Experience
        // ------------------------------------------------------------------
        ['key' => 'exp_eyebrow',               'label' => 'Page eyebrow',                       'type' => 'text',          'group' => 'Experience', 'default' => 'Career'],
        ['key' => 'exp_heading',               'label' => 'Page heading (H1)',                  'type' => 'text',          'group' => 'Experience', 'default' => 'Experience'],
        ['key' => 'exp_intro',                 'label' => 'Intro paragraph',                    'type' => 'textarea',      'group' => 'Experience', 'default' => 'Professional and project experience across AI automation, engineering and consulting.'],
        ['key' => 'exp_empty',                 'label' => 'Empty state message',                'type' => 'text',          'group' => 'Experience', 'default' => 'Experience entries are being updated. Check back soon.'],
        ['key' => 'exp_cta_heading',           'label' => 'Closing CTA — heading',              'type' => 'text',          'group' => 'Experience', 'default' => "Let's build something together"],
        ['key' => 'exp_cta_button',            'label' => 'Closing CTA — button',               'type' => 'text',          'group' => 'Experience', 'default' => 'Get in touch'],

        // ------------------------------------------------------------------
        // Education
        // ------------------------------------------------------------------
        ['key' => 'edu_eyebrow',               'label' => 'Page eyebrow',                       'type' => 'text',          'group' => 'Education', 'default' => 'Education'],
        ['key' => 'edu_heading',               'label' => 'Page heading (H1)',                  'type' => 'text',          'group' => 'Education', 'default' => 'Education'],
        ['key' => 'edu_intro',                 'label' => 'Intro paragraph',                    'type' => 'textarea',      'group' => 'Education', 'default' => 'Academic background and lifelong learning.'],
        ['key' => 'edu_empty',                 'label' => 'Empty state message',                'type' => 'text',          'group' => 'Education', 'default' => 'Education entries are being updated. Check back soon.'],

        // ------------------------------------------------------------------
        // Testimonials
        // ------------------------------------------------------------------
        ['key' => 'test_eyebrow',              'label' => 'Page eyebrow',                       'type' => 'text',          'group' => 'Testimonials', 'default' => 'Testimonials'],
        ['key' => 'test_heading',              'label' => 'Page heading (H1)',                  'type' => 'text',          'group' => 'Testimonials', 'default' => 'What people say'],
        ['key' => 'test_intro',                'label' => 'Intro paragraph',                    'type' => 'textarea',      'group' => 'Testimonials', 'default' => 'Feedback from clients and collaborators on automation projects.'],
        ['key' => 'test_empty',                'label' => 'Empty state message',                'type' => 'text',          'group' => 'Testimonials', 'default' => 'No testimonials yet.'],
        ['key' => 'test_cta_heading',          'label' => 'Closing CTA — heading',              'type' => 'text',          'group' => 'Testimonials', 'default' => 'Ready to work together?'],
        ['key' => 'test_cta_button',           'label' => 'Closing CTA — button',               'type' => 'text',          'group' => 'Testimonials', 'default' => "Let's Talk"],

        // ------------------------------------------------------------------
        // Blog
        // ------------------------------------------------------------------
        ['key' => 'blog_eyebrow',              'label' => 'Page eyebrow',                       'type' => 'text',          'group' => 'Blog', 'default' => 'Blog'],
        ['key' => 'blog_heading',              'label' => 'Page heading (H1)',                  'type' => 'text',          'group' => 'Blog', 'default' => 'Insights'],
        ['key' => 'blog_intro',                'label' => 'Intro paragraph',                    'type' => 'textarea',      'group' => 'Blog', 'default' => 'Notes on AI automation, agents, RAG and building smarter businesses.'],
        ['key' => 'blog_empty',                'label' => 'Empty state message',                'type' => 'text',          'group' => 'Blog', 'default' => 'New articles are on the way.'],
        ['key' => 'blog_empty_search',         'label' => 'Empty — “try a search” hint',        'type' => 'text',          'group' => 'Blog', 'default' => 'Try a different search.'],
        ['key' => 'blog_search_placeholder',   'label' => 'Search placeholder',                 'type' => 'text',          'group' => 'Blog', 'default' => 'Search posts...'],
        ['key' => 'blog_search',               'label' => 'Search button',                      'type' => 'text',          'group' => 'Blog', 'default' => 'Search'],
        ['key' => 'blog_all',                  'label' => '‘All’ category label',               'type' => 'text',          'group' => 'Blog', 'default' => 'All'],
        ['key' => 'blog_read',                 'label' => '‘Read article’ button',              'type' => 'text',          'group' => 'Blog', 'default' => 'Read article'],
        ['key' => 'blog_related_eyebrow',      'label' => 'Related eyebrow',                    'type' => 'text',          'group' => 'Blog', 'default' => 'Related'],
        ['key' => 'blog_show_cta_heading',     'label' => 'Post CTA — heading',                 'type' => 'text',          'group' => 'Blog', 'default' => 'Want automation ideas for your own business?'],
        ['key' => 'blog_show_cta_button',      'label' => 'Post CTA — button',                  'type' => 'text',          'group' => 'Blog', 'default' => "Let's Talk"],

        // ------------------------------------------------------------------
        // Contact
        // ------------------------------------------------------------------
        ['key' => 'contact_eyebrow',        'label' => 'Page eyebrow',                        'type' => 'text',     'group' => 'Contact', 'default' => 'Contact'],
        ['key' => 'contact_heading',        'label' => 'Page heading (H1)',                   'type' => 'text',     'group' => 'Contact', 'default' => "Let's work together"],
        ['key' => 'contact_intro',          'label' => 'Intro paragraph',                     'type' => 'textarea', 'group' => 'Contact', 'default' => "Have a process that could be automated? Tell me about it and I'll be in touch — usually within one business day."],
        ['key' => 'contact_submit',         'label' => 'Submit button',                       'type' => 'text',     'group' => 'Contact', 'default' => 'Send Message'],
        ['key' => 'contact_email_label',    'label' => 'Email card label',                    'type' => 'text',     'group' => 'Contact', 'default' => 'Email'],
        ['key' => 'contact_phone_label',    'label' => 'Phone card label',                    'type' => 'text',     'group' => 'Contact', 'default' => 'Phone'],
        ['key' => 'contact_location_label', 'label' => 'Location card label',                 'type' => 'text',     'group' => 'Contact', 'default' => 'Location'],
        ['key' => 'contact_success',        'label' => 'Success flash message',               'type' => 'text',     'group' => 'Contact', 'default' => 'Thanks for reaching out. I\'ve received your request and will review the details before getting back to you.'],

        // ------------------------------------------------------------------
        // How I Build Your System (process section, home page)
        // ------------------------------------------------------------------
        ['key' => 'process_eyebrow',        'label' => 'Section eyebrow',                     'type' => 'text',     'group' => 'How I Build Your System', 'default' => 'How I build'],
        ['key' => 'process_heading',        'label' => 'Section heading (H2)',                'type' => 'text',     'group' => 'How I Build Your System', 'default' => 'How I Build Your System'],
        ['key' => 'process_intro',          'label' => 'Intro paragraph',                     'type' => 'textarea', 'group' => 'How I Build Your System', 'default' => 'A proven five-step path from the first conversation to a working automation — built, tested, and deployed to fit your business.',],
        ['key' => 'process_1_title',        'label' => 'Step 01 — title',                     'type' => 'text',     'group' => 'How I Build Your System', 'default' => 'Audit the Opportunity'],
        ['key' => 'process_1_text',         'label' => 'Step 01 — description',               'type' => 'textarea', 'group' => 'How I Build Your System', 'default' => 'I examine your current processes, identify bottlenecks and repetitive work, and find where AI and automation can create real value.'],
        ['key' => 'process_2_title',        'label' => 'Step 02 — title',                     'type' => 'text',     'group' => 'How I Build Your System', 'default' => 'Design the Solution'],
        ['key' => 'process_2_text',         'label' => 'Step 02 — description',               'type' => 'textarea', 'group' => 'How I Build Your System', 'default' => 'I turn the opportunity into a practical automation strategy tailored to your business and goals.'],
        ['key' => 'process_3_title',        'label' => 'Step 03 — title',                     'type' => 'text',     'group' => 'How I Build Your System', 'default' => 'Build the System'],
        ['key' => 'process_3_text',         'label' => 'Step 03 — description',               'type' => 'textarea', 'group' => 'How I Build Your System', 'default' => 'I develop the AI agents, workflows, integrations, and tools needed to bring the solution to life.'],
        ['key' => 'process_4_title',        'label' => 'Step 04 — title',                     'type' => 'text',     'group' => 'How I Build Your System', 'default' => 'Make It Reliable'],
        ['key' => 'process_4_text',         'label' => 'Step 04 — description',               'type' => 'textarea', 'group' => 'How I Build Your System', 'default' => 'I test, refine, and optimize the system using realistic business scenarios before it goes live.'],
        ['key' => 'process_5_title',        'label' => 'Step 05 — title',                     'type' => 'text',     'group' => 'How I Build Your System', 'default' => 'Put It to Work'],
        ['key' => 'process_5_text',         'label' => 'Step 05 — description',               'type' => 'textarea', 'group' => 'How I Build Your System', 'default' => 'I deploy the system and provide the documentation and tools needed to use and manage it.'],

        // ------------------------------------------------------------------
        // AI Consultation (chat interface)
        // ------------------------------------------------------------------
        ['key' => 'consult_page_eyebrow',   'label' => 'Page eyebrow',                        'type' => 'text',     'group' => 'AI Consultation', 'default' => 'AI Consultation'],
        ['key' => 'consult_page_heading',   'label' => 'Page heading (H1)',                   'type' => 'text',     'group' => 'AI Consultation', 'default' => 'Tell me about your business'],
        ['key' => 'consult_page_intro',     'label' => 'Page intro paragraph',                'type' => 'textarea', 'group' => 'AI Consultation', 'default' => 'Describe the work you\'d like to automate and I\'ll help you scope it — like starting a conversation with me.'],
        ['key' => 'consult_back_label',     'label' => 'Back button label',                   'type' => 'text',     'group' => 'AI Consultation', 'default' => 'Back to portfolio'],
        ['key' => 'consult_placeholder',    'label' => 'Input placeholder',                   'type' => 'text',     'group' => 'AI Consultation', 'default' => 'Describe your business or the process you want to automate…'],
        ['key' => 'consult_error',          'label' => 'Connection error message',            'type' => 'textarea', 'group' => 'AI Consultation', 'default' => 'Sorry, I couldn\'t connect right now. Please try again or use the contact form below.'],
        ['key' => 'consult_footer_note',    'label' => 'Contact fallback note',               'type' => 'text',     'group' => 'AI Consultation', 'default' => 'Prefer to email instead?'],
        ['key' => 'consult_footer_link',    'label' => 'Contact fallback link text',          'type' => 'text',     'group' => 'AI Consultation', 'default' => 'Use the contact form'],
    ];
}

return _content_block_defaults();