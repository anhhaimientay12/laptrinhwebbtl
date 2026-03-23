<?php
// Models/DiemSoModel.php
class DiemSoModel extends Model {
    protected string $table = 'diem_so';

    public function getByLop(string $maLop): array {
        return $this->db->fetchAll("
            SELECT dk.ma_sv, sv.ho_ten, sv.lop,
                   ds.diem_cc, ds.diem_gk, ds.diem_ck, ds.diem_tong, ds.xep_loai
            FROM dang_ky dk
            JOIN sinh_vien sv ON sv.ma_sv = dk.ma_sv
            LEFT JOIN diem_so ds ON ds.ma_sv = dk.ma_sv AND ds.ma_lop = dk.ma_lop
            WHERE dk.ma_lop = ? AND dk.trang_thai = 'dang_ky'
            ORDER BY sv.ho_ten ASC", [$maLop]);
    }

    public function upsert(string $maSv, string $maLop, float $cc, float $gk, float $ck, float $tong, string $xepLoai): void {
        $this->db->execute("
            INSERT INTO diem_so (ma_sv, ma_lop, diem_cc, diem_gk, diem_ck, diem_tong, xep_loai)
            VALUES (?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
                diem_cc = VALUES(diem_cc), diem_gk = VALUES(diem_gk),
                diem_ck = VALUES(diem_ck), diem_tong = VALUES(diem_tong),
                xep_loai = VALUES(xep_loai)",
            [$maSv, $maLop, $cc, $gk, $ck, $tong, $xepLoai]);
    }
}
