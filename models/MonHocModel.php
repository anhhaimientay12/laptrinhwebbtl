<?php
// Models/MonHocModel.php
class MonHocModel extends Model {
    protected string $table      = 'mon_hoc';
    protected string $primaryKey = 'ma_mon';

    public function filter(?string $search, ?string $maKhoa, int $page): array {
        $where  = [];
        $params = [];
        if ($search) {
            $kw = "%{$search}%";
            $where[]  = "(m.ten_mon LIKE ? OR m.ma_mon LIKE ?)";
            $params   = [$kw, $kw];
        }
        if ($maKhoa) { $where[] = "m.ma_khoa = ?"; $params[] = $maKhoa; }
        $whereStr = $where ? implode(' AND ', $where) : '1=1';
        $total    = $this->db->fetchOne(
            "SELECT COUNT(*) as c FROM mon_hoc m WHERE {$whereStr}", $params)['c'] ?? 0;
        $offset   = ($page - 1) * PER_PAGE;
        $data     = $this->db->fetchAll("
            SELECT m.*, k.ten_khoa, tq.ten_mon as ten_tien_quyet
            FROM mon_hoc m
            LEFT JOIN khoa k ON k.ma_khoa = m.ma_khoa
            LEFT JOIN mon_hoc tq ON tq.ma_mon = m.mon_tien_quyet
            WHERE {$whereStr} ORDER BY m.ten_mon ASC
            LIMIT " . PER_PAGE . " OFFSET {$offset}", $params);
        return ['data' => $data, 'total' => (int)$total, 'per_page' => PER_PAGE,
                'current_page' => $page, 'last_page' => (int)ceil($total / PER_PAGE)];
    }
}
