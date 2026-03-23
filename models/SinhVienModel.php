<?php
// Models/SinhVienModel.php
class SinhVienModel extends Model {
    protected string $table      = 'sinh_vien';
    protected string $primaryKey = 'ma_sv';

    public function filter(?string $search, ?string $maKhoa, int $page): array {
        $where  = [];
        $params = [];
        if ($search) {
            $kw = "%{$search}%";
            $where[]  = "(sv.ho_ten LIKE ? OR sv.ma_sv LIKE ? OR sv.email LIKE ?)";
            $params   = array_merge($params, [$kw, $kw, $kw]);
        }
        if ($maKhoa) {
            $where[]  = "sv.ma_khoa = ?";
            $params[] = $maKhoa;
        }
        $whereStr = $where ? implode(' AND ', $where) : '1=1';
        $total    = $this->db->fetchOne(
            "SELECT COUNT(*) as c FROM sinh_vien sv WHERE {$whereStr}", $params)['c'] ?? 0;
        $offset   = ($page - 1) * PER_PAGE;
        $data     = $this->db->fetchAll("
            SELECT sv.*, k.ten_khoa
            FROM sinh_vien sv
            LEFT JOIN khoa k ON k.ma_khoa = sv.ma_khoa
            WHERE {$whereStr}
            ORDER BY sv.created_at DESC
            LIMIT " . PER_PAGE . " OFFSET {$offset}", $params);
        return ['data' => $data, 'total' => (int)$total, 'per_page' => PER_PAGE,
                'current_page' => $page, 'last_page' => (int)ceil($total / PER_PAGE)];
    }

    public function getDetail(string $maSv): ?array {
        return $this->db->fetchOne("
            SELECT sv.*, k.ten_khoa
            FROM sinh_vien sv
            LEFT JOIN khoa k ON k.ma_khoa = sv.ma_khoa
            WHERE sv.ma_sv = ?", [$maSv]);
    }

    public function getRecent(int $limit = 5): array {
        return $this->db->fetchAll("
            SELECT sv.*, k.ten_khoa FROM sinh_vien sv
            LEFT JOIN khoa k ON k.ma_khoa = sv.ma_khoa
            ORDER BY sv.created_at DESC LIMIT {$limit}");
    }

    public function countByKhoa(): array {
        return $this->db->fetchAll("
            SELECT k.ten_khoa, COUNT(sv.ma_sv) as so_luong
            FROM khoa k
            LEFT JOIN sinh_vien sv ON sv.ma_khoa = k.ma_khoa
            GROUP BY k.ma_khoa, k.ten_khoa
            ORDER BY so_luong DESC");
    }

    public function getByUserId(int $userId): ?array {
        return $this->db->fetchOne("SELECT * FROM sinh_vien WHERE user_id = ?", [$userId]);
    }
}
