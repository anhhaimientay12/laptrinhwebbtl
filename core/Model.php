<?php
// =============================================
// Core/Model.php - Base Model
// =============================================

abstract class Model {
    protected Database $db;
    protected string $table = '';
    protected string $primaryKey = 'id';

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /** Lấy tất cả bản ghi */
    public function getAll(string $orderBy = ''): array {
        $sql = "SELECT * FROM `{$this->table}`";
        if ($orderBy) $sql .= " ORDER BY {$orderBy}";
        return $this->db->fetchAll($sql);
    }

    /** Lấy bản ghi theo ID */
    public function getById(mixed $id): ?array {
        $sql = "SELECT * FROM `{$this->table}` WHERE `{$this->primaryKey}` = ?";
        return $this->db->fetchOne($sql, [$id]);
    }

    /** Thêm bản ghi */
    public function insert(array $data): bool {
        $cols   = implode('`, `', array_keys($data));
        $places = implode(', ', array_fill(0, count($data), '?'));
        $sql    = "INSERT INTO `{$this->table}` (`{$cols}`) VALUES ({$places})";
        return $this->db->execute($sql, array_values($data));
    }

    /** Cập nhật bản ghi */
    public function update(mixed $id, array $data): bool {
        $sets   = implode(' = ?, ', array_map(fn($k) => "`{$k}`", array_keys($data))) . ' = ?';
        $sql    = "UPDATE `{$this->table}` SET {$sets} WHERE `{$this->primaryKey}` = ?";
        return $this->db->execute($sql, [...array_values($data), $id]);
    }

    /** Xóa bản ghi */
    public function delete(mixed $id): bool {
        $sql = "DELETE FROM `{$this->table}` WHERE `{$this->primaryKey}` = ?";
        return $this->db->execute($sql, [$id]);
    }

    /** Đếm tổng bản ghi */
    public function count(string $where = '', array $params = []): int {
        $sql = "SELECT COUNT(*) as total FROM `{$this->table}`";
        if ($where) $sql .= " WHERE {$where}";
        $row = $this->db->fetchOne($sql, $params);
        return (int)($row['total'] ?? 0);
    }

    /** Kiểm tra tồn tại */
    public function exists(string $field, mixed $value, mixed $excludeId = null): bool {
        $sql    = "SELECT COUNT(*) as c FROM `{$this->table}` WHERE `{$field}` = ?";
        $params = [$value];
        if ($excludeId !== null) {
            $sql .= " AND `{$this->primaryKey}` != ?";
            $params[] = $excludeId;
        }
        $row = $this->db->fetchOne($sql, $params);
        return (int)($row['c'] ?? 0) > 0;
    }

    /** Phân trang */
    public function paginate(int $page, int $perPage = PER_PAGE, string $where = '', array $params = [], string $orderBy = ''): array {
        $offset = ($page - 1) * $perPage;
        $total  = $this->count($where, $params);

        $sql = "SELECT * FROM `{$this->table}`";
        if ($where) $sql .= " WHERE {$where}";
        if ($orderBy) $sql .= " ORDER BY {$orderBy}";
        $sql .= " LIMIT {$perPage} OFFSET {$offset}";

        return [
            'data'        => $this->db->fetchAll($sql, $params),
            'total'       => $total,
            'per_page'    => $perPage,
            'current_page'=> $page,
            'last_page'   => (int)ceil($total / $perPage),
        ];
    }
}
