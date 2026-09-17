<?php

declare(strict_types=1);

namespace Portfolio\Services;

use Portfolio\Core\Database;
use Portfolio\Core\Session;
use PDO;

/**
 * Authentication, authorization and login rate limiting.
 */
final class AuthService
{
    /**
     * Attempt a login. Returns true on success.
     */
    public function attempt(string $email, string $password): bool
    {
        if ($this->isLockedOut()) {
            return false;
        }

        $db = Database::connection();
        $stmt = $db->prepare(
            "SELECT * FROM `users`
             WHERE `email` = :email AND `status` = 'active'
             LIMIT 1"
        );
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user === false || !password_verify($password, $user['password'])) {
            $this->recordFailure();
            return false;
        }

        if (password_needs_rehash($user['password'], PASSWORD_BCRYPT, ['cost' => BCRYPT_COST])) {
            $rehash = password_hash($password, PASSWORD_BCRYPT, ['cost' => BCRYPT_COST]);
            $db->prepare('UPDATE `users` SET `password` = :hash WHERE `id` = :id')
               ->execute(['hash' => $rehash, 'id' => $user['id']]);
        }

        // Successful login: clear failures, regenerate session, establish identity
        $this->clearFailures();
        Session::regenerate();

        Session::set('user_id', (int) $user['id']);
        Session::set('user_name', $user['name']);
        Session::set('user_email', $user['email']);
        Session::set('user_role', $user['role']);
        Session::set('auth_role', ['admin' => $user['role'] === 'admin']);

        $db->prepare('UPDATE `users` SET `last_login_at` = NOW() WHERE `id` = :id')
           ->execute(['id' => $user['id']]);

        return true;
    }

    public function logout(): void
    {
        Session::forget('user_id');
        Session::forget('user_name');
        Session::forget('user_email');
        Session::forget('user_role');
        Session::forget('auth_role');
        Session::forget('redirect_after_login');
        Session::regenerate();
    }

    public function user(): ?array
    {
        $id = Session::userId();
        if ($id === null) {
            return null;
        }
        $db = Database::connection();
        $stmt = $db->prepare('SELECT * FROM `users` WHERE `id` = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();
        return $user === false ? null : $user;
    }

    public function isAdmin(): bool
    {
        return Session::isAuthenticated()
            && Session::get('user_role') === 'admin';
    }

    // --- Rate limiting ---

    private function failureKey(): string
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        return 'login_failures_' . md5($ip);
    }

    private function lockoutKey(): string
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        return 'login_locked_until_' . md5($ip);
    }

    public function isLockedOut(): bool
    {
        $until = Session::get($this->lockoutKey());
        if ($until !== null && time() < (int) $until) {
            return true;
        }
        if ($until !== null && time() >= (int) $until) {
            Session::forget($this->lockoutKey());
            Session::forget($this->failureKey());
        }
        return false;
    }

    public function lockoutRemainingSeconds(): int
    {
        $until = (int) Session::get($this->lockoutKey(), 0);
        $remaining = $until - time();
        return $remaining > 0 ? $remaining : 0;
    }

    private function recordFailure(): void
    {
        $key = $this->failureKey();
        $count = (int) Session::get($key, 0) + 1;
        Session::set($key, $count);

        if ($count >= LOGIN_MAX_ATTEMPTS) {
            $lock = time() + (LOGIN_LOCKOUT_MINUTES * 60);
            Session::set($this->lockoutKey(), $lock);
        }
    }

    private function clearFailures(): void
    {
        Session::forget($this->failureKey());
        Session::forget($this->lockoutKey());
    }

    /**
     * Create the initial admin account. Idempotent.
     */
    public static function seedAdmin(string $name, string $email, string $password): bool
    {
        $db = Database::connection();
        $stmt = $db->prepare('SELECT id FROM `users` WHERE `email` = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        if ($stmt->fetch() !== false) {
            return false;
        }

        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => BCRYPT_COST]);
        $db->prepare('INSERT INTO `users` (name, email, password, role, status, created_at, updated_at)
                      VALUES (:name, :email, :password, :role, :status, NOW(), NOW())')
           ->execute([
               'name'     => $name,
               'email'    => $email,
               'password' => $hash,
               'role'     => 'admin',
               'status'   => 'active',
           ]);
        return true;
    }
}