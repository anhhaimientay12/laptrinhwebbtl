<?php
// Models/LopHocPhanModel.php
class LopHocPhanModel extends Model {
    protected string $table      = 'lop_hoc_phan';
    protected string $primaryKey = 'ma_lop';

    public function filter(?string $search, int $page): array {
        $where  = '1=1';
        $params = [];
        if ($search) {
            $kw = "%{$search}%";
            $where  = "(lhp.ma_lop LIKE ? OR m.ten_mon LIKE ?)";
            $params = [$kw, $kw];
        }
        $total  = $this->db->fetchOne(
            "SELECT COUNT(*) as c FROM lop_hoc_phan lhp
             LEFT JOIN mon_hoc m ON m.ma_mon = lhp.ma_mon WHERE {$where}", $params)['c'] ?? 0;
        $offset = ($page - 1) * PER_PAGE;
        $data   = $this->db->fetchAll("
            SELECT lhp.*, m.ten_mon, m.tin_chi, gv.ho_ten as ten_gv
            FROM lop_hoc_phan lhp
            LEFT JOIN mon_hoc m ON m.ma_mon = lhp.ma_mon
            LEFT JOIN giang_vien gv ON gv.ma_gv = lhp.ma_gv
            WHERE {$where} ORDER BY lhp.nam_hoc DESC, lhp.hoc_ky DESC
            LIMIT " . PER_PAGE . " OFFSET {$offset}", $params);
        return ['data' => $data, 'total' => (int)$total, 'per_page' => PER_PAGE,
                'current_page' => $page, 'last_page' => (int)ceil($total / PER_PAGE)];
    }

    public function getWithMon(): array {
        return $this->db->fetchAll("
            SELECT lhp.*, m.ten_mon, m.tin_chi, gv.ho_ten as ten_gv
            FROM lop_hoc_phan lhp
            LEFT JOIN mon_hoc m ON m.ma_mon = lhp.ma_mon
            LEFT JOIN giang_vien gv ON gv.ma_gv = lhp.ma_gv
            ORDER BY lhp.nam_hoc DESC, lhp.hoc_ky ASC");
    }

    public function getDetail(string $maLop): ?array {
        return $this->db->fetchOne("
            SELECT lhp.*, m.ten_mon, m.tin_chi, gv.ho_ten as ten_gv
            FROM lop_hoc_phan lhp
            LEFT JOIN mon_hoc m ON m.ma_mon = lhp.ma_mon
            LEFT JOIN giang_vien gv ON gv.ma_gv = lhp.ma_gv
            WHERE lhp.ma_lop = ?", [$maLop]);
    }
}
