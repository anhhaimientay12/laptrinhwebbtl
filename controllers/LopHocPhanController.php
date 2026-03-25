<?php
// Controllers/LopHocPhanController.php
require_once BASE_PATH . '/models/LopHocPhanModel.php';
require_once BASE_PATH . '/models/MonHocModel.php';
require_once BASE_PATH . '/models/GiangVienModel.php';
require_once BASE_PATH . '/models/DangKyModel.php';
require_once BASE_PATH . '/models/SinhVienModel.php';

class LopHocPhanController extends Controller {
    private LopHocPhanModel $model;

    public function __construct() { $this->model = new LopHocPhanModel(); }

    public function index(): void {
        $this->requireAuth();
        $page   = max(1, (int)$this->get('page', 1));
        $search = $this->get('search');
        $result = $this->model->filter($search, $page);
        $this->view('lop_hoc_phan/index', ['title' => 'Lớp học phần', 'pageTitle' => 'Danh sách Lớp học phần',
            'result' => $result, 'search' => $search]);
    }

    public function create(): void {
        $this->requireAdmin();
        $this->view('lop_hoc_phan/form', ['title' => 'Thêm Lớp HP', 'pageTitle' => 'Thêm Lớp học phần',
            'lop' => null, 'action' => BASE_URL . '/lop-hoc-phan/store',
            'danhSachMon' => (new MonHocModel())->getAll('ten_mon ASC'),
            'danhSachGV'  => (new GiangVienModel())->getAll('ho_ten ASC')]);
    }

        public function store(): void {
        $this->requireAdmin();
        $data = ['ma_lop' => strtoupper($this->post('ma_lop')), 'ma_mon' => $this->post('ma_mon'),
            'ma_gv' => $this->post('ma_gv') ?: null, 'hoc_ky' => $this->post('hoc_ky'),
            'nam_hoc' => $this->post('nam_hoc'), 'si_so_max' => (int)$this->post('si_so_max', 40),
            'phong_hoc' => $this->post('phong_hoc'), 'lich_hoc' => '',
            'trang_thai' => $this->post('trang_thai', 'mo')];
        $errors = $this->validate($data, ['ma_lop' => 'required', 'ma_mon' => 'required', 'hoc_ky' => 'required', 'nam_hoc' => 'required']);
        if (!$errors && $this->model->exists('ma_lop', $data['ma_lop'])) $errors['ma_lop'] = 'Mã lớp đã tồn tại.';
        if ($errors) {
            $this->view('lop_hoc_phan/form', ['title' => 'Thêm Lớp HP', 'pageTitle' => 'Thêm Lớp học phần',
                'lop' => $data, 'errors' => $errors, 'action' => BASE_URL . '/lop-hoc-phan/store',
                'danhSachMon' => (new MonHocModel())->getAll('ten_mon ASC'),
                'danhSachGV'  => (new GiangVienModel())->getAll('ho_ten ASC')]);
            return;
        }
        
        // Insert lớp học phần
        $this->model->insert($data);
        
        // Xử lý lịch học từ form
        $lich_hoc_array = $this->post('lich_hoc');
        if (!empty($lich_hoc_array) && is_array($lich_hoc_array)) {
            $this->model->themLichHoc($data['ma_lop'], $data['ma_gv'], $lich_hoc_array);
        }
        
        $this->flash('success', 'Thêm lớp học phần thành công!');
        $this->redirect('/lop-hoc-phan');
    }

    public function edit(string $id): void {
        $this->requireAdmin();
        $lop = $this->model->getById($id);
        if (!$lop) { $this->flash('error', 'Không tìm thấy lớp.'); $this->redirect('/lop-hoc-phan'); }
        $this->view('lop_hoc_phan/form', ['title' => 'Sửa Lớp HP', 'pageTitle' => 'Sửa Lớp học phần',
            'lop' => $lop, 'action' => BASE_URL . '/lop-hoc-phan/update/' . $id,
            'danhSachMon' => (new MonHocModel())->getAll('ten_mon ASC'),
            'danhSachGV'  => (new GiangVienModel())->getAll('ho_ten ASC')]);
    }

        public function update(string $id): void {
        $this->requireAdmin();
        $data = ['ma_mon' => $this->post('ma_mon'), 'ma_gv' => $this->post('ma_gv') ?: null,
            'hoc_ky' => $this->post('hoc_ky'), 'nam_hoc' => $this->post('nam_hoc'),
            'si_so_max' => (int)$this->post('si_so_max', 40), 'phong_hoc' => $this->post('phong_hoc'),
            'lich_hoc' => '', 'trang_thai' => $this->post('trang_thai', 'mo')];
        
        $this->model->update($id, $data);
        
        // Xử lý lịch học từ form
        $lich_hoc_array = $this->post('lich_hoc');
        if (!empty($lich_hoc_array) && is_array($lich_hoc_array)) {
            $this->model->themLichHoc($id, $data['ma_gv'], $lich_hoc_array);
        }
        
        $this->flash('success', 'Cập nhật lớp học phần thành công!');
        $this->redirect('/lop-hoc-phan');
    }

    public function delete(string $id): void {
        $this->requireAdmin();
        $this->model->delete($id);
        $this->flash('success', 'Xóa lớp học phần thành công!');
        $this->redirect('/lop-hoc-phan');
    }

    /** [ADMIN] Xem danh sách sinh viên trong lớp */
    public function students(string $maLop): void {
        $this->requireAdmin();
        $lop = $this->model->getDetail($maLop);
        if (!$lop) { $this->flash('error', 'Không tìm thấy lớp.'); $this->redirect('/lop-hoc-phan'); }
        
        $dkModel = new DangKyModel();
        $students = $dkModel->getByLop($maLop);
        
        $this->view('lop_hoc_phan/students', [
            'title'     => 'Quản lý sinh viên',
            'pageTitle' => 'Danh sách sinh viên — ' . $maLop,
            'lop'       => $lop,
            'students'  => $students
        ]);
    }

    /** [ADMIN] Thêm sinh viên vào lớp */
    public function addStudent(string $maLop): void {
        $this->requireAdmin();
        $maSv = strtoupper($this->post('ma_sv'));
        
        if (empty($maSv)) {
            $this->flash('error', 'Vui lòng nhập mã sinh viên.');
            $this->redirect('/lop-hoc-phan/sinh-vien/' . $maLop);
        }

        $svModel = new SinhVienModel();
        if (!$svModel->exists('ma_sv', $maSv)) {
            $this->flash('error', 'Sinh viên không tồn tại.');
            $this->redirect('/lop-hoc-phan/sinh-vien/' . $maLop);
        }

        $dkModel = new DangKyModel();
        
        // 1. Kiểm tra đã có trong lớp chưa
        if ($dkModel->isRegistered($maSv, $maLop)) {
            $this->flash('error', 'Sinh viên này đã có trong lớp.');
            $this->redirect('/lop-hoc-phan/sinh-vien/' . $maLop);
        }

        // 2. Kiểm tra trùng môn (trong cùng học kỳ)
        if ($dkModel->checkSubjectDuplicate($maSv, $maLop)) {
            $this->flash('error', 'Sinh viên đã đăng ký một lớp khác của môn học này.');
            $this->redirect('/lop-hoc-phan/sinh-vien/' . $maLop);
        }

        // 3. Kiểm tra trùng lịch học
        $conflictLop = $dkModel->checkScheduleConflict($maSv, $maLop);
        if ($conflictLop) {
            $this->flash('error', "Trùng lịch học với lớp $conflictLop.");
            $this->redirect('/lop-hoc-phan/sinh-vien/' . $maLop);
        }

        // 4. Kiểm tra tổng tín chỉ (Max 20)
        $newLopInfo = $dkModel->getSubjectByLop($maLop);
        $currentCredits = $dkModel->getTotalCredits($maSv, $newLopInfo['hoc_ky'], $newLopInfo['nam_hoc']);
        if ($currentCredits + $newLopInfo['tin_chi'] > 20) {
            $this->flash('error', "Tổng tín chỉ vượt quá 20 TC (Hiện tại: $currentCredits).");
            $this->redirect('/lop-hoc-phan/sinh-vien/' . $maLop);
        }

        // Kiểm tra sĩ số
        $lop = $this->model->getById($maLop);
        if ($lop['si_so_hien'] >= $lop['si_so_max']) {
            $this->flash('error', 'Lớp đã đầy.');
            $this->redirect('/lop-hoc-phan/sinh-vien/' . $maLop);
        }

        if ($dkModel->register($maSv, $maLop)) {
            $this->flash('success', 'Thêm sinh viên thành công!');
        } else {
            $this->flash('error', 'Lỗi khi thêm sinh viên.');
        }
        $this->redirect('/lop-hoc-phan/sinh-vien/' . $maLop);
    }

    /** [ADMIN] Xóa sinh viên khỏi lớp */
    public function removeStudent(string $id): void {
        $this->requireAdmin();
        $dkModel = new DangKyModel();
        $dk = $dkModel->getById($id);
        if (!$dk) { $this->flash('error', 'Không tìm thấy bản ghi đăng ký.'); $this->redirect('/lop-hoc-phan'); }
        
        $maLop = $dk['ma_lop'];
        if ($dkModel->unregister($id)) {
            $this->flash('success', 'Đã xóa sinh viên khỏi lớp.');
        } else {
            $this->flash('error', 'Lỗi khi xóa sinh viên.');
        }
        $this->redirect('/lop-hoc-phan/sinh-vien/' . $maLop);
    }
}
