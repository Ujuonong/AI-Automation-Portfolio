<?php

declare(strict_types=1);

namespace Portfolio\Controllers\Admin;

use Portfolio\Core\Session;
use Portfolio\Models\User;
use Portfolio\Services\Validator;

final class ProfileController extends AdminBaseController
{
    public function index(): void
    {
        $user = (new User())->find((int) Session::get('user_id'));
        if ($user === null) {
            $this->abort(403, 'Account not found.');
        }

        $this->view('admin/settings/profile', [
            'sidebar_active' => 'settings',
            'page_title'     => 'Profile',
            'user'           => $user,
        ]);
    }

    public function update(): void
    {
        $user = (new User())->find((int) Session::get('user_id'));
        if ($user === null) {
            $this->abort(403);
        }

        $input = $this->request->all();

        $validator = (new Validator($input))
            ->required('name', 'name')
            ->email('email', 'email');

        if ($input['password'] ?? '') {
            if (strlen((string) $input['password']) < 8) {
                $validator->addError('password', 'The password must be at least 8 characters.');
            }
            if (($input['password'] ?? '') !== ($input['password_confirmation'] ?? '')) {
                $validator->addError('password_confirmation', 'The password confirmation does not match.');
            }
        }

        if ($validator->passes()) {
            $data = [
                'name'  => trim((string) $input['name']),
                'email' => trim((string) $input['email']),
            ];
            if (!empty($input['password'])) {
                $data['password'] = password_hash((string) $input['password'], PASSWORD_BCRYPT, ['cost' => 12]);
            }

            (new User())->update((int) $user['id'], $data);

            Session::set('user_name', $data['name']);
            Session::set('user_email', $data['email']);

            flash('success', 'Profile updated.');
            $this->redirect('/admin/profile');
        }

        $this->view('admin/settings/profile', [
            'sidebar_active' => 'settings',
            'page_title'     => 'Profile',
            'user'           => $user,
            'errors'         => $validator->errors(),
            'old'            => $input,
        ]);
    }
}