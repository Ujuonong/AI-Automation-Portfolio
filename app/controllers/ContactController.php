<?php

declare(strict_types=1);

namespace Portfolio\Controllers;

use Portfolio\Core\Controller;
use Portfolio\Core\Session;
use Portfolio\Models\ContactMessage;
use Portfolio\Services\SiteService;
use Portfolio\Services\Validator;

final class ContactController extends Controller
{
    public function index(): void
    {
        $types = $this->enquiryTypes();

        $selected = (string) $this->request->query('enquiry', '');
        if (!array_key_exists($selected, $types)) {
            $selected = '';
        }

        $this->view('public/contact', [
            'page_title'       => 'Contact — ' . SiteService::get('site_name'),
            'meta_description' => 'Get in touch to discuss AI automation for your business.',
            'csrf'             => \Portfolio\Core\Csrf::field(),
            'errors'           => Session::flush('errors', []),
            'form_success'     => Session::flush('contact_success'),
            'enquiry_types'    => $types,
            'selected_enquiry' => $selected,
        ], 'public');
    }

    public function send(): void
    {
        $input = $this->request->all();

        // Honeypot: field must be empty for real humans
        if (!empty($input['website'])) {
            $this->redirect('/contact');
        }

        if ($this->throttled()) {
            Session::set('contact_success', 'You have sent too many messages. Please wait a moment and try again.');
            $this->redirect('/contact');
        }

        // Trim all string inputs
        foreach (['name', 'email', 'phone', 'company', 'enquiry_type', 'preferred_audit_date', 'preferred_audit_time', 'message', 'additional_info'] as $field) {
            if (isset($input[$field]) && is_string($input[$field])) {
                $input[$field] = trim($input[$field]);
            }
        }

        $types       = $this->enquiryTypes();
        $isAudit     = ($input['enquiry_type'] ?? '') === 'ai_automation_audit';

        $validator = (new Validator($input))
            ->required('name', 'name')
            ->maxLength('name', 'name', 150)
            ->required('email', 'email')
            ->email('email', 'email')
            ->maxLength('email', 'email', 190)
            ->required('phone', 'phone')
            ->phone('phone', 'phone')
            ->maxLength('phone', 'phone', 30)
            ->maxLength('company', 'company', 150)
            ->required('enquiry_type', 'enquiry type')
            ->in('enquiry_type', 'enquiry type', array_keys($types))
            ->required('message', 'message')
            ->minLength('message', 'message', 10);

        if ($isAudit) {
            $validator
                ->required('preferred_audit_date', 'preferred audit date')
                ->dateNotPast('preferred_audit_date', 'preferred audit date');
        }

        if (!$validator->passes()) {
            Session::set('errors', $this->friendlyErrors($validator->errors(), $input));
            Session::rememberOld($input);
            $this->redirect('/contact');
        }

        try {
            $normalizedPhone = Validator::normalizePhone((string) $input['phone']);
            $subject = $types[$input['enquiry_type']] ?? 'General Enquiry';

            (new ContactMessage())->create([
                'name'                 => (string) $input['name'],
                'email'                => (string) $input['email'],
                'phone'                => $normalizedPhone !== '' ? $normalizedPhone : (string) $input['phone'],
                'company'              => (string) ($input['company'] ?? ''),
                'subject'              => $subject,
                'enquiry_type'         => (string) $input['enquiry_type'],
                'preferred_audit_date' => $isAudit && ($input['preferred_audit_date'] ?? '') !== '' ? (string) $input['preferred_audit_date'] : null,
                'preferred_audit_time' => $isAudit && ($input['preferred_audit_time'] ?? '') !== '' ? (string) $input['preferred_audit_time'] : null,
                'message'              => (string) $input['message'],
                'additional_info'      => (string) ($input['additional_info'] ?? ''),
                'status'               => 'new',
            ]);

            $this->recordSubmission();
            Session::set('contact_success', SiteService::block('contact_success'));
            $this->redirect('/contact');
        } catch (\Throwable $e) {
            error_log('[Contact] Store error: ' . $e->getMessage());
            Session::set('errors', ['form' => 'Something went wrong. Please try again later.']);
            $this->redirect('/contact');
        }
    }

    /**
     * Per-session rate limit: max 3 messages / 15 minutes.
     */
    private function throttled(): bool
    {
        $stamp = Session::get('contact_window_start');
        $count = (int) Session::get('contact_window_count', 0);

        if ($stamp === null || $stamp < time() - 900) {
            Session::set('contact_window_start', time());
            Session::set('contact_window_count', 1);
            return false;
        }

        if ($count >= 3) {
            return true;
        }

        Session::set('contact_window_count', $count + 1);
        return false;
    }

    private function recordSubmission(): void
    {
        Session::set('contact_window_start', time());
        Session::set('contact_window_count', (int) Session::get('contact_window_count', 0) + 1);
    }

    private function friendlyErrors(array $errors, array $input): array
    {
        $friendly = [];
        foreach ($errors as $field => $message) {
            $friendly[$field] = match ($field) {
                'name' => 'Please enter your name.',
                'email' => ($input['email'] ?? '') === ''
                    ? 'Please enter your email address.'
                    : 'Please enter a valid email address.',
                'phone' => ($input['phone'] ?? '') === ''
                    ? 'Please enter your phone or WhatsApp number.'
                    : 'Please enter a valid phone or WhatsApp number.',
                'enquiry_type' => 'Please select what you need help with.',
                'message' => 'Please tell me a little about your business and the process you\'d like to automate.',
                'preferred_audit_date' => ($input['preferred_audit_date'] ?? '') === ''
                    ? 'Please select your preferred audit date.'
                    : 'Please select a future date.',
                default => $message,
            };
        }
        return $friendly;
    }

    private function enquiryTypes(): array
    {
        return require CONFIG_PATH . '/enquiries.php';
    }
}
