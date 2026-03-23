<?php
// =============================================
// Controllers/DashboardController.php
// =============================================

require_once BASE_PATH . '/models/KhoaModel.php';
require_once BASE_PATH . '/models/SinhVienModel.php';
require_once BASE_PATH . '/models/GiangVienModel.php';
require_once BASE_PATH . '/models/MonHocModel.php';
require_once BASE_PATH . '/models/LopHocPhanModel.php';

class DashboardController extends Controller {

    public function index(): void {
        $this->requireAuth();

        $khoaModel      = new KhoaModel();
        $svModel        = new SinhVienModel();
        $gvModel        = new GiangVienModel();
        $monModel       = new MonHocModel();
        $lopModel       = new LopHocPhanModel();

        $stats = [
            'total_khoa'     => $khoaModel->count(),
            'total_sv'       => $svModel->count(),
            'total_gv'       => $gvModel->count(),
            'total_mon'      => $monModel->count(),
            'total_lop'      => $lopModel->count(),
            'sv_dang_hoc'    => $svModel->count("trang_thai = 'dang_hoc'"),
        ];

        $recentSV  = $svModel->getRecent(5);
        $svByKhoa  = $svModel->countByKhoa();

        $this->view('dashboard/index', [
            'title'    => 'Dashboard — ' . APP_NAME,
            'stats'    => $stats,
            'recentSV' => $recentSV,
            'svByKhoa' => $svByKhoa,
            'pageTitle'=> 'Tổng quan hệ thống',
        ]);
    }
}
