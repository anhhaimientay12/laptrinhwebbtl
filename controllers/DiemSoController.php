<?php
// Controllers/DiemSoController.php
require_once BASE_PATH . '/models/DiemSoModel.php';
require_once BASE_PATH . '/models/LopHocPhanModel.php';

class DiemSoController extends Controller {
    private DiemSoModel $model;

    public function __construct() { $this->model = new DiemSoModel(); }

    public function index(): void {
        $this->requireAuth();
        $lops = (new LopHocPhanModel())->getWithMon();
        $this->view('diem_so/index', ['title' => 'Quản lý Điểm số', 'pageTitle' => 'Quản lý Điểm số', 'lops' => $lops]);
    }

    public function byLop(string $maLop): void {
        $this->requireAuth();
        $lop  = (new LopHocPhanModel())->getDetail($maLop);
        if (!$lop) { $this->flash('error', 'Không tìm thấy lớp.'); $this->redirect('/diem-so'); }
        $diem = $this->model->getByLop($maLop);
        $this->view('diem_so/by_lop', ['title' => 'Bảng điểm', 'pageTitle' => 'Bảng điểm lớp ' . $maLop,
            'lop' => $lop, 'diem' => $diem, 'maLop' => $maLop]);
    }

    public function nhap(string $maLop): void {
        $this->requireAuth();
        $diemList = $_POST['diem'] ?? [];
        foreach ($diemList as $maSv => $d) {
            $cc   = min(10, max(0, (float)($d['diem_cc'] ?? 0)));
            $gk   = min(10, max(0, (float)($d['diem_gk'] ?? 0)));
            $ck   = min(10, max(0, (float)($d['diem_ck'] ?? 0)));
            $tong = round($cc * 0.1 + $gk * 0.3 + $ck * 0.6, 2);
            $xepLoai = $this->xepLoai($tong);
            $this->model->upsert($maSv, $maLop, $cc, $gk, $ck, $tong, $xepLoai);
        }
        $this->flash('success', 'Lưu điểm thành công!');
        $this->redirect('/diem-so/lop/' . $maLop);
    }

    private function xepLoai(float $diem): string {
        if ($diem >= 9)   return 'A+';
        if ($diem >= 8.5) return 'A';
        if ($diem >= 8)   return 'B+';
        if ($diem >= 7)   return 'B';
        if ($diem >= 6.5) return 'C+';
        if ($diem >= 5.5) return 'C';
        if ($diem >= 5)   return 'D+';
        if ($diem >= 4)   return 'D';
        return 'F';
    }
}
