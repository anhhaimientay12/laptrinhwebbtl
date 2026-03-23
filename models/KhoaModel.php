<?php
// Models/KhoaModel.php
class KhoaModel extends Model {
    protected string $table      = 'khoa';
    protected string $primaryKey = 'ma_khoa';

    public function search(string $keyword, int $page): array {
        $kw = "%{$keyword}%";
        return $this->paginate($page, PER_PAGE,
            "ten_khoa LIKE ? OR ma_khoa LIKE ?",
            [$kw, $kw], 'ten_khoa ASC');
    }

    public function hasSinhVien(string $maKhoa): bool {
        $row = $this->db->fetchOne(
            "SELECT COUNT(*) as c FROM sinh_vien WHERE ma_khoa = ?", [$maKhoa]);
        return (int)($row['c'] ?? 0) > 0;
    }

    public function getAllWithCount(): array {
        return $this->db->fetchAll("
            SELECT k.*,
                   COUNT(DISTINCT sv.ma_sv) as so_sv,
                   COUNT(DISTINCT gv.ma_gv) as so_gv,
                   COUNT(DISTINCT m.ma_mon) as so_mon
            FROM khoa k
            LEFT JOIN sinh_vien sv ON sv.ma_khoa = k.ma_khoa
            LEFT JOIN giang_vien gv ON gv.ma_khoa = k.ma_khoa
            LEFT JOIN mon_hoc m ON m.ma_khoa = k.ma_khoa
            GROUP BY k.ma_khoa
            ORDER BY k.ten_khoa ASC
        ");
    }
}
