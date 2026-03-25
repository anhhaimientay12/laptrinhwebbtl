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
        // ========== PHƯƠNG THỨC QUẢN LÝ LỊCH HỌC ==========
    
    /**
     * Lấy lịch học của một lớp
     */
    public function getLichHocByLop($ma_lop) {
        $sql = "SELECT * FROM lich_hoc WHERE ma_lop = ? ORDER BY thu, tiet_bat_dau";
        return $this->db->fetchAll($sql, [$ma_lop]);
    }
    
    /**
     * Xóa lịch học của lớp
     */
    public function xoaLichHoc($ma_lop) {
        return $this->db->execute("DELETE FROM lich_hoc WHERE ma_lop = ?", [$ma_lop]);
    }
    
    /**
     * Thêm lịch học cho lớp
     */
    public function themLichHoc($ma_lop, $ma_gv, $lich_hoc_data) {
        $this->xoaLichHoc($ma_lop);
        $sql = "INSERT INTO lich_hoc (ma_lop, ma_gv, thu, tiet_bat_dau, so_tiet, phong_hoc) 
                VALUES (?, ?, ?, ?, ?, ?)";
        foreach ($lich_hoc_data as $lich) {
            if (!empty($lich['thu']) && !empty($lich['tiet_bat_dau']) && !empty($lich['phong_hoc'])) {
                $this->db->execute($sql, [
                    $ma_lop,
                    $ma_gv,
                    $lich['thu'],
                    $lich['tiet_bat_dau'],
                    $lich['so_tiet'] ?? 2,
                    $lich['phong_hoc']
                ]);
            }
        }
        return true;
    }
    
    /**
     * Lấy TKB của sinh viên
     */
    public function getThoiKhoaBieuSinhVien($ma_sv, $hoc_ky, $nam_hoc) {
        $sql = "SELECT 
                    lh.thu, lh.tiet_bat_dau, lh.so_tiet, lh.phong_hoc,
                    lhp.ma_lop,
                    m.ma_mon, m.ten_mon, m.tin_chi,
                    gv.ho_ten as ten_gv
                FROM dang_ky dk
                JOIN lop_hoc_phan lhp ON dk.ma_lop = lhp.ma_lop
                JOIN lich_hoc lh ON lhp.ma_lop = lh.ma_lop
                JOIN mon_hoc m ON lhp.ma_mon = m.ma_mon
                LEFT JOIN giang_vien gv ON lhp.ma_gv = gv.ma_gv
                WHERE dk.ma_sv = ? 
                    AND dk.trang_thai = 'dang_ky'
                    AND lhp.hoc_ky = ?
                    AND lhp.nam_hoc = ?
                    AND lhp.trang_thai != 'dong'
                ORDER BY lh.thu, lh.tiet_bat_dau";
        return $this->db->fetchAll($sql, [$ma_sv, $hoc_ky, $nam_hoc]);
    }
         /**
     * Lấy TKB của giảng viên
     */
    public function getThoiKhoaBieuGiangVien($ma_gv, $hoc_ky, $nam_hoc) {
        $sql = "SELECT 
                    lh.thu, lh.tiet_bat_dau, lh.so_tiet, lh.phong_hoc,
                    lhp.ma_lop, lhp.hoc_ky, lhp.nam_hoc,
                    m.ma_mon, m.ten_mon, m.tin_chi,
                    (SELECT COUNT(*) FROM dang_ky WHERE ma_lop = lhp.ma_lop AND trang_thai = 'dang_ky') as si_so
                FROM lop_hoc_phan lhp
                JOIN lich_hoc lh ON lhp.ma_lop = lh.ma_lop
                JOIN mon_hoc m ON lhp.ma_mon = m.ma_mon
                WHERE lhp.ma_gv = ?
                    AND lhp.hoc_ky = ?
                    AND lhp.nam_hoc = ?
                    AND lhp.trang_thai != 'dong'
                ORDER BY lh.thu, lh.tiet_bat_dau";
        return $this->db->fetchAll($sql, [$ma_gv, $hoc_ky, $nam_hoc]);
    }
        /**
     * Lấy tất cả lịch học (dành cho Admin)
     */
    public function getAllSchedule($hoc_ky, $nam_hoc) {
        if (empty($hoc_ky)) return [];
        
        $sql = "SELECT 
                    lh.thu, lh.tiet_bat_dau, lh.so_tiet, lh.phong_hoc,
                    lhp.ma_lop, lhp.hoc_ky, lhp.nam_hoc,
                    m.ma_mon, m.ten_mon, m.tin_chi,
                    gv.ho_ten as ten_gv,
                    (SELECT COUNT(*) FROM dang_ky WHERE ma_lop = lhp.ma_lop AND trang_thai = 'dang_ky') as si_so
                FROM lop_hoc_phan lhp
                JOIN lich_hoc lh ON lhp.ma_lop = lh.ma_lop
                JOIN mon_hoc m ON lhp.ma_mon = m.ma_mon
                LEFT JOIN giang_vien gv ON lhp.ma_gv = gv.ma_gv
                WHERE lhp.hoc_ky = ? AND lhp.nam_hoc = ?
                ORDER BY lh.thu, lh.tiet_bat_dau";
        
        return $this->db->fetchAll($sql, [$hoc_ky, $nam_hoc]);
    }
    /**
     * Lấy danh sách học kỳ có dữ liệu
     */
    public function getDanhSachHocKy() {
        $sql = "SELECT DISTINCT hoc_ky, nam_hoc FROM lop_hoc_phan 
                ORDER BY nam_hoc DESC, hoc_ky DESC";
        return $this->db->fetchAll($sql);
    }
}
