<?php

declare(strict_types=1);

namespace Portfolio\Models;

final class ContactMessage extends BaseModel
{
    protected string $table = 'contact_messages';

    public const STATUSES = ['new', 'read', 'replied', 'archived'];

    public function unreadCount(): int
    {
        return $this->count("status = 'new'");
    }

    public function setStatus(int $id, string $status): bool
    {
        if (!in_array($status, self::STATUSES, true)) {
            return false;
        }
        $stmt = $this->db->prepare('UPDATE contact_messages SET status = :status WHERE id = :id');
        return $stmt->execute(['status' => $status, 'id' => $id]);
    }
}