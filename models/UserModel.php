<?php
// Models/UserModel.php
class UserModel extends Model {
    protected string $table = 'users';

    public function findByUsername(string $username): ?array {
        return $this->db->fetchOne("SELECT * FROM users WHERE username = ? AND is_active = 1", [$username]);
    }

    public function lastId(): string {
        return $this->db->lastInsertId();
    }
}
