<?php
// Controllers/LopHocPhanController.php
require_once BASE_PATH . '/models/LopHocPhanModel.php';
require_once BASE_PATH . '/models/MonHocModel.php';
require_once BASE_PATH . '/models/GiangVienModel.php';

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
            'phong_hoc' => $this->post('phong_hoc'), 'lich_hoc' => $this->post('lich_hoc'),
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
        $this->model->insert($data);
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
            'lich_hoc' => $this->post('lich_hoc'), 'trang_thai' => $this->post('trang_thai', 'mo')];
        $this->model->update($id, $data);
        $this->flash('success', 'Cập nhật lớp học phần thành công!');
        $this->redirect('/lop-hoc-phan');
    }

    public function delete(string $id): void {
        $this->requireAdmin();
        $this->model->delete($id);
        $this->flash('success', 'Xóa lớp học phần thành công!');
        $this->redirect('/lop-hoc-phan');
    }
}
