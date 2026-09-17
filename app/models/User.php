<?php

declare(strict_types=1);

namespace Portfolio\Models;

final class User extends BaseModel
{
    protected string $table = 'users';

    public function findByEmail(string $email): ?array
    {
        return $this->findBy('email', $email);
    }

    public function activeAdmins(): array
    {
        $stmt = $this->db->query("SELECT id, name, email, role, status, last_login_at FROM users WHERE status = 'active' ORDER BY id");
        return $stmt->fetchAll();
    }
}