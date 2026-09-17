<?php

declare(strict_types=1);

namespace Portfolio\Controllers;

use Portfolio\Core\Controller;
use Portfolio\Core\Session;
use Portfolio\Services\AuthService;
use Portfolio\Services\Validator;

final class AuthController extends Controller
{
    public function showLogin(): void
    {
        $this->view('auth/login', [
            'page_title'  => 'Admin Login',
            'csrf'        => \Portfolio\Core\Csrf::field(),
            'error'       => Session::flush('login_error'),
            'locked_for'  => (new AuthService())->lockoutRemainingSeconds(),
        ], 'auth');
    }

    public function login(): void
    {
        $input = $this->request->all();

        $validator = (new Validator($input))
            ->required('email', 'email')
            ->email('email', 'email')
            ->required('password', 'password');

        if ($validator->passes()) {
            $auth = new AuthService();

            if ($auth->isLockedOut()) {
                Session::set('login_error', 'Too many failed attempts. Please wait a few minutes and try again.');
                $this->redirect('/admin/login');
            }

            if ($auth->attempt((string) $input['email'], (string) $input['password'])) {
                $after = Session::flush('redirect_after_login');
                $this->redirect($after && str_starts_with($after, '/') ? $after : '/admin');
            }

            Session::set('login_error', 'These credentials do not match our records.');
            $this->redirect('/admin/login');
        }

        Session::set('login_error', 'Please provide your email and password.');
        $this->redirect('/admin/login');
    }

    public function logout(): void
    {
        (new AuthService())->logout();
        flash('success', 'You have been logged out.');
        $this->redirect('/admin/login');
    }
}