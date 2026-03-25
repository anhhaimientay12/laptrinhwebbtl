<?php
// Models/DangKyModel.php
class DangKyModel extends Model {
    protected string $table = 'dang_ky';

    /** Lấy danh sách học phần mà sinh viên đã đăng ký */
    public function getByStudent(string $maSv): array {
        return $this->db->fetchAll("
            SELECT dk.*, lop.ma_mon, mon.ten_mon, mon.tin_chi, 
                   gv.ho_ten as ten_gv, lop.hoc_ky, lop.nam_hoc, 
                   lop.phong_hoc, lop.lich_hoc
            FROM dang_ky dk
            JOIN lop_hoc_phan lop ON lop.ma_lop = dk.ma_lop
            JOIN mon_hoc mon ON mon.ma_mon = lop.ma_mon
            LEFT JOIN giang_vien gv ON gv.ma_gv = lop.ma_gv
            WHERE dk.ma_sv = ? 
            ORDER BY lop.nam_hoc DESC, lop.hoc_ky DESC", [$maSv]);
    }

    /** Lấy danh sách lớp học phần đang mở để đăng ký */
    public function getOpenLop(string $maSv): array {
        // Lấy danh sách lớp chưa đăng ký và đang ở trạng thái 'mo'
        return $this->db->fetchAll("
            SELECT lop.*, mon.ten_mon, mon.tin_chi, gv.ho_ten as ten_gv
            FROM lop_hoc_phan lop
            JOIN mon_hoc mon ON mon.ma_mon = lop.ma_mon
            LEFT JOIN giang_vien gv ON gv.ma_gv = lop.ma_gv
            WHERE lop.trang_thai = 'mo' 
              AND lop.ma_lop NOT IN (SELECT ma_lop FROM dang_ky WHERE ma_sv = ?)
            ORDER BY mon.ten_mon ASC", [$maSv]);
    }

    /** Kiểm tra xem sinh viên đã đăng ký học phần này chưa */
    public function isRegistered(string $maSv, string $maLop): bool {
        $result = $this->db->fetchOne("SELECT id FROM dang_ky WHERE ma_sv = ? AND ma_lop = ?", [$maSv, $maLop]);
        return (bool)$result;
    }

    /** Thực hiện đăng ký */
    public function register(string $maSv, string $maLop): bool {
        // Cập nhật sĩ số hiện tại của lớp
        $this->db->execute("UPDATE lop_hoc_phan SET si_so_hien = si_so_hien + 1 WHERE ma_lop = ?", [$maLop]);
        return $this->insert(['ma_sv' => $maSv, 'ma_lop' => $maLop, 'trang_thai' => 'dang_ky']);
    }

    /** Hủy đăng ký */
    public function unregister(string $id): bool {
        $dk = $this->getById($id);
        if ($dk) {
            $this->db->execute("UPDATE lop_hoc_phan SET si_so_hien = si_so_hien - 1 WHERE ma_lop = ?", [$dk['ma_lop']]);
            return $this->delete($id);
        }
        return false;
    }

    /** Lấy danh sách sinh viên trong một lớp học phần */
    public function getByLop(string $maLop): array {
        return $this->db->fetchAll("
            SELECT dk.id, dk.ma_sv, dk.ngay_dk, sv.ho_ten, sv.lop as lop_sv
            FROM dang_ky dk
            JOIN sinh_vien sv ON sv.ma_sv = dk.ma_sv
            WHERE dk.ma_lop = ?
            ORDER BY sv.ho_ten ASC", [$maLop]);
    }
    /** Lấy môn học của một lớp */
    public function getSubjectByLop(string $maLop): ?array {
        return $this->db->fetchOne("
            SELECT mon.*, lop.hoc_ky, lop.nam_hoc, lop.lich_hoc
            FROM lop_hoc_phan lop
            JOIN mon_hoc mon ON mon.ma_mon = lop.ma_mon
            WHERE lop.ma_lop = ?", [$maLop]);
    }

    /** Kiểm tra xem sinh viên đã đăng ký môn học này trong học kỳ này chưa */
    public function checkSubjectDuplicate(string $maSv, string $maLop): bool {
        $newLop = $this->getSubjectByLop($maLop);
        if (!$newLop) return false;

        $sql = "SELECT COUNT(*) as c 
                FROM dang_ky dk
                JOIN lop_hoc_phan lop ON lop.ma_lop = dk.ma_lop
                WHERE dk.ma_sv = ? 
                  AND lop.ma_mon = ? 
                  AND lop.hoc_ky = ? 
                  AND lop.nam_hoc = ? 
                  AND dk.ma_lop != ?";
        $row = $this->db->fetchOne($sql, [
            $maSv, $newLop['ma_mon'], $newLop['hoc_ky'], $newLop['nam_hoc'], $maLop
        ]);
        return (int)($row['c'] ?? 0) > 0;
    }

       /**
     * Kiểm tra trùng lịch học (dùng bảng lich_hoc)
     */
    public function checkScheduleConflict(string $maSv, string $maLop): ?string {
        // Lấy thông tin lớp mới
        $lopMoi = $this->db->fetchOne("SELECT hoc_ky, nam_hoc FROM lop_hoc_phan WHERE ma_lop = ?", [$maLop]);
        if (!$lopMoi) return null;
        
        // Lấy tất cả lịch của lớp mới
        $lichMoi = $this->db->fetchAll("SELECT * FROM lich_hoc WHERE ma_lop = ?", [$maLop]);
        if (empty($lichMoi)) return null;
        
        // Lấy tất cả lớp đã đăng ký của sinh viên trong cùng học kỳ
        $cacLopDaDk = $this->db->fetchAll("
            SELECT dk.ma_lop FROM dang_ky dk
            JOIN lop_hoc_phan lhp ON lhp.ma_lop = dk.ma_lop
            WHERE dk.ma_sv = ? AND dk.trang_thai = 'dang_ky'
              AND lhp.hoc_ky = ? AND lhp.nam_hoc = ?",
            [$maSv, $lopMoi['hoc_ky'], $lopMoi['nam_hoc']]
        );
        
        foreach ($cacLopDaDk as $lopDaDk) {
            $lichCu = $this->db->fetchAll("SELECT * FROM lich_hoc WHERE ma_lop = ?", [$lopDaDk['ma_lop']]);
            foreach ($lichMoi as $lm) {
                foreach ($lichCu as $lc) {
                    if ($this->isConflict($lm, $lc)) {
                        return $lopDaDk['ma_lop'];
                    }
                }
            }
        }
        return null;
    }
    
    /**
     * Kiểm tra xung đột giữa 2 lịch học
     */
    private function isConflict($lich1, $lich2): bool {
        // Khác thứ thì không xung đột
        if ($lich1['thu'] != $lich2['thu']) return false;
        
        // Tính khoảng tiết học
        $start1 = $lich1['tiet_bat_dau'];
        $end1 = $start1 + $lich1['so_tiet'] - 1;
        $start2 = $lich2['tiet_bat_dau'];
        $end2 = $start2 + $lich2['so_tiet'] - 1;
        
        // Kiểm tra trùng giờ
        if ($end1 < $start2 || $end2 < $start1) return false;
        
        // Kiểm tra trùng phòng
        if ($lich1['phong_hoc'] === $lich2['phong_hoc']) return true;
        
        return false;
    }

    /** Lấy tổng số tín chỉ hiện tại của sinh viên */
    public function getTotalCredits(string $maSv, string $hocKy, string $namHoc): int {
        $sql = "SELECT SUM(mon.tin_chi) as total
                FROM dang_ky dk
                JOIN lop_hoc_phan lop ON lop.ma_lop = dk.ma_lop
                JOIN mon_hoc mon ON mon.ma_mon = lop.ma_mon
                WHERE dk.ma_sv = ? AND lop.hoc_ky = ? AND lop.nam_hoc = ?";
        $row = $this->db->fetchOne($sql, [$maSv, $hocKy, $namHoc]);
        return (int)($row['total'] ?? 0);
    }
}
