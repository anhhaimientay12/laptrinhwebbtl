<?php
// Models/GiangVienModel.php
class GiangVienModel extends Model {
    protected string $table      = 'giang_vien';
    protected string $primaryKey = 'ma_gv';

    public function filter(?string $search, int $page): array {
        $where  = '1=1';
        $params = [];
        if ($search) {
            $kw = "%{$search}%";
            $where    = "(gv.ho_ten LIKE ? OR gv.ma_gv LIKE ? OR gv.email LIKE ?)";
            $params   = [$kw, $kw, $kw];
        }
        $total  = $this->db->fetchOne(
            "SELECT COUNT(*) as c FROM giang_vien gv WHERE {$where}", $params)['c'] ?? 0;
        $offset = ($page - 1) * PER_PAGE;
        $data   = $this->db->fetchAll("
            SELECT gv.*, k.ten_khoa FROM giang_vien gv
            LEFT JOIN khoa k ON k.ma_khoa = gv.ma_khoa
            WHERE {$where} ORDER BY gv.ho_ten ASC
            LIMIT " . PER_PAGE . " OFFSET {$offset}", $params);
        return ['data' => $data, 'total' => (int)$total, 'per_page' => PER_PAGE,
                'current_page' => $page, 'last_page' => (int)ceil($total / PER_PAGE)];
    }
}
