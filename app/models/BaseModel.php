<?php

declare(strict_types=1);

namespace Portfolio\Models;

use PDO;
use Portfolio\Core\Database;

/**
 * Base model providing shared CRUD over a specific table.
 */
abstract class BaseModel
{
    protected PDO $db;
    protected string $table;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function table(): string
    {
        return $this->table;
    }

    public function tableName(): string
    {
        return $this->table;
    }

    public function find(int|string $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM `{$this->table}` WHERE `id` = :id LIMIT 1"
        );
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result === false ? null : $result;
    }

    public function findBy(string $column, mixed $value): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM `{$this->table}` WHERE `{$column}` = :value LIMIT 1"
        );
        $stmt->execute(['value' => $value]);
        $result = $stmt->fetch();
        return $result === false ? null : $result;
    }

    public function all(string $orderBy = 'id', string $direction = 'ASC'): array
    {
        $stmt = $this->db->query(
            "SELECT * FROM `{$this->table}` ORDER BY `{$orderBy}` " . ($direction === 'DESC' ? 'DESC' : 'ASC')
        );
        return $stmt->fetchAll();
    }

    /**
     * Insert a row and return the new id.
     *
     * @param array<string, mixed> $data
     */
    public function create(array $data): int
    {
        unset($data['id']);
        if (!isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }
        if (!isset($data['updated_at'])) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }

        $columns = array_keys($data);
        $placeholders = array_map(fn ($c) => ":{$c}", $columns);

        $sql = sprintf(
            'INSERT INTO `%s` (`%s`) VALUES (%s)',
            $this->table,
            implode('`, `', $columns),
            implode(', ', $placeholders)
        );

        $stmt = $this->db->prepare($sql);
        foreach ($data as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }
        $stmt->execute();

        return (int) $this->db->lastInsertId();
    }

    /**
     * Update a row by id.
     *
     * @param array<string, mixed> $data
     */
    public function update(int $id, array $data): bool
    {
        unset($data['id'], $data['created_at']);
        $data['updated_at'] = date('Y-m-d H:i:s');

        $assignments = [];
        foreach (array_keys($data) as $column) {
            $assignments[] = "`{$column}` = :{$column}";
        }

        $sql = sprintf(
            'UPDATE `%s` SET %s WHERE `id` = :id',
            $this->table,
            implode(', ', $assignments)
        );

        $params = $data;
        $params['id'] = $id;

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }
        return $stmt->execute();
    }

    public function updateColumns(int $id, array $data): bool
    {
        return $this->update($id, $data);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM `{$this->table}` WHERE `id` = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function count(string $where = ''): int
    {
        $sql = "SELECT COUNT(*) FROM `{$this->table}`";
        if ($where !== '') {
            $sql .= ' WHERE ' . $where;
        }
        return (int) $this->db->query($sql)->fetchColumn();
    }

    public function paginate(int $page = 1, int $perPage = 12, string $where = '', array $params = [], string $orderBy = 'id', string $direction = 'DESC'): array
    {
        $page = max(1, $page);
        $offset = ($page - 1) * $perPage;

        $whereSql = $where !== '' ? ' WHERE ' . $where : '';
        $directionSafe = $direction === 'ASC' ? 'ASC' : 'DESC';

        $countSql = "SELECT COUNT(*) FROM `{$this->table}`{$whereSql}";
        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute($params);
        $total = (int) $countStmt->fetchColumn();
        $totalPages = max(1, (int) ceil($total / $perPage));

        $sql = sprintf(
            'SELECT * FROM `%s`%s ORDER BY `%s` %s LIMIT %d OFFSET %d',
            $this->table,
            $whereSql,
            $orderBy,
            $directionSafe,
            $perPage,
            $offset
        );
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return [
            'items'       => $stmt->fetchAll(),
            'total'       => $total,
            'page'        => $page,
            'per_page'    => $perPage,
            'total_pages' => $totalPages,
            'offset'      => $offset,
        ];
    }
}