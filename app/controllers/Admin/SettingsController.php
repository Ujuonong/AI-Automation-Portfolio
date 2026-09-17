<?php

declare(strict_types=1);

namespace Portfolio\Controllers\Admin;

use Portfolio\Models\SiteSetting;
use Portfolio\Services\UploadService;
use Portfolio\Services\Validator;

final class SettingsController extends AdminBaseController
{
    public function index(): void
    {
        $this->view('admin/settings/index', [
            'sidebar_active' => 'settings',
            'page_title'     => 'Settings',
            'settings'       => (new SiteSetting())->allAsArray(),
        ]);
    }

    public function update(): void
    {
        $input = $this->request->all();

        // Checkbox: absent from POST when unchecked -> always persist an explicit value.
        $input['ai_consultation_enabled'] = isset($input['ai_consultation_enabled']) ? '1' : '0';

        $validator = (new Validator($input))
            ->email('email', 'email')
            ->url('linkedin_url', 'LinkedIn URL')
            ->url('github_url', 'GitHub URL')
            ->url('x_url', 'X URL')
            ->url('youtube_url', 'YouTube URL')
            ->url('whatsapp_url', 'WhatsApp URL');

        // Webhook URL is stored in the DB so it can be changed without code edits.
        // Placeholder stays valid so the rest of the form can be saved first.
        $webhook = trim((string) ($input['ai_consultant_webhook_url'] ?? ''));
        if ($webhook !== '' && $webhook !== 'YOUR_WEBHOOK_URL_HERE' && !filter_var($webhook, FILTER_VALIDATE_URL)) {
            $validator->addError('ai_consultant_webhook_url', 'The AI consultant webhook URL must be a valid URL.');
        }

        $uploader = new UploadService();

        // Handle uploadable fields
        foreach (['profile_image' => 'images', 'logo' => 'images', 'favicon' => 'images', 'og_image' => 'images', 'resume_file' => 'documents'] as $field => $category) {
            $file = $this->request->file($field);
            if ($file === null) {
                continue;
            }
            try {
                $new = $category === 'documents'
                    ? $uploader->uploadDocument($file, 'settings', $field)
                    : $uploader->uploadImage($file, 'settings', $field);

                $old = (new SiteSetting())->get($field, '');
                if ($old && $old !== $new) {
                    $uploader->delete($old);
                }
                $input[$field] = $new;
            } catch (\RuntimeException $e) {
                $validator->addError($field, $e->getMessage());
            }
        }

        if ($validator->passes()) {
            foreach ($input as $key => $value) {
                if (is_array($value)) {
                    continue;
                }
                $input[$key] = trim((string) $value);
            }
            // Remove internal keys
            unset($input['csrf_token']);

            (new SiteSetting())->setMany($input);
            flash('success', 'Settings saved.');
            $this->redirect('/admin/settings');
        }

        flash('error', 'Some settings were invalid: ' . implode(' ', $validator->errors()));
        $this->back('/admin/settings');
    }
}