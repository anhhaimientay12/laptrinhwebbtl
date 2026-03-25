<?php
require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/core/Database.php';
require_once BASE_PATH . '/models/LopHocPhanModel.php';

class ThoiKhoaBieuController extends Controller {
    
        public function index() {
        $this->requireAuth();
        
        $role = $_SESSION['user']['role'] ?? '';
        $model = new LopHocPhanModel();
        
        // Lấy học kỳ từ GET
        $hoc_ky = $this->get('hoc_ky');
        $nam_hoc = $this->get('nam_hoc');
        
        // Lấy danh sách học kỳ có dữ liệu
        $danhSachHK = $model->getDanhSachHocKy();
        
        // Nếu chưa chọn học kỳ, lấy học kỳ gần nhất
        if (empty($hoc_ky) && !empty($danhSachHK)) {
            $hoc_ky = $danhSachHK[0]['hoc_ky'];
            $nam_hoc = $danhSachHK[0]['nam_hoc'];
        }
        
        // Lấy dữ liệu theo role
        $tkb = [];
        $view = 'thoi_khoa_bieu/sinh_vien';
        $pageTitle = 'Thời khóa biểu';
        
        switch ($role) {
            case 'sinh_vien':
                $ma_sv = $_SESSION['ma_sv'] ?? $_SESSION['user']['ma_sv'] ?? '';
                if (!empty($ma_sv) && !empty($hoc_ky)) {
                    $tkb = $model->getThoiKhoaBieuSinhVien($ma_sv, $hoc_ky, $nam_hoc);
                }
                $view = 'thoi_khoa_bieu/sinh_vien';
                $pageTitle = 'Thời khóa biểu';
                break;
                
            case 'giang_vien':
                $ma_gv = $_SESSION['ma_gv'] ?? $_SESSION['user']['ma_gv'] ?? '';
                if (!empty($ma_gv) && !empty($hoc_ky)) {
                    $tkb = $model->getThoiKhoaBieuGiangVien($ma_gv, $hoc_ky, $nam_hoc);
                }
                $view = 'thoi_khoa_bieu/giang_vien';
                $pageTitle = 'Lịch giảng dạy';
                break;
                
            case 'admin':
                if (!empty($hoc_ky)) {
                    $tkb = $model->getAllSchedule($hoc_ky, $nam_hoc);
                }
                $view = 'thoi_khoa_bieu/admin';
                $pageTitle = 'Quản lý lịch học';
                break;
                
            default:
                $this->redirect('/dashboard');
                return;
        }
        
        $this->view($view, [
            'tkb' => $tkb,
            'hoc_ky' => $hoc_ky,
            'nam_hoc' => $nam_hoc,
            'danhSachHK' => $danhSachHK,
            'title' => $pageTitle,
            'pageTitle' => $pageTitle
        ]);
    }
    
