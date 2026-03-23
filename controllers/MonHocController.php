<?php
// Controllers/MonHocController.php
require_once BASE_PATH . '/models/MonHocModel.php';
require_once BASE_PATH . '/models/KhoaModel.php';

class MonHocController extends Controller {
    private MonHocModel $model;
    private KhoaModel $khoaModel;

    public function __construct() {
        $this->model     = new MonHocModel();
        $this->khoaModel = new KhoaModel();
    }

    public function index(): void {
        $this->requireAuth();
        $page   = max(1, (int)$this->get('page', 1));
        $search = $this->get('search');
        $khoa   = $this->get('khoa');
        $result = $this->model->filter($search, $khoa, $page);
        $this->view('mon_hoc/index', ['title' => 'Quản lý Môn học', 'pageTitle' => 'Danh sách Môn học',
            'result' => $result, 'search' => $search, 'maKhoa' => $khoa,
            'danhSachKhoa' => $this->khoaModel->getAll('ten_khoa ASC')]);
    }

    public function create(): void {
        $this->requireAdmin();
        $this->view('mon_hoc/form', ['title' => 'Thêm Môn học', 'pageTitle' => 'Thêm Môn học mới',
            'mon' => null, 'action' => BASE_URL . '/mon-hoc/store',
            'danhSachKhoa' => $this->khoaModel->getAll('ten_khoa ASC'),
            'danhSachMon'  => $this->model->getAll('ten_mon ASC')]);
    }

    public function store(): void {
        $this->requireAdmin();
        $data = ['ma_mon' => strtoupper($this->post('ma_mon')), 'ten_mon' => $this->post('ten_mon'),
            'tin_chi' => (int)$this->post('tin_chi', 3), 'ma_khoa' => $this->post('ma_khoa') ?: null,
            'mon_tien_quyet' => $this->post('mon_tien_quyet') ?: null, 'mo_ta' => $this->post('mo_ta')];
        $errors = $this->validate($data, ['ma_mon' => 'required|max:10', 'ten_mon' => 'required|max:150', 'tin_chi' => 'required|numeric']);
        if (!$errors && $this->model->exists('ma_mon', $data['ma_mon'])) $errors['ma_mon'] = 'Mã môn đã tồn tại.';
        if ($errors) {
            $this->view('mon_hoc/form', ['title' => 'Thêm Môn học', 'pageTitle' => 'Thêm Môn học mới',
                'mon' => $data, 'errors' => $errors, 'action' => BASE_URL . '/mon-hoc/store',
                'danhSachKhoa' => $this->khoaModel->getAll('ten_khoa ASC'),
                'danhSachMon'  => $this->model->getAll('ten_mon ASC')]);
            return;
        }
        $this->model->insert($data);
        $this->flash('success', 'Thêm môn học thành công!');
        $this->redirect('/mon-hoc');
    }

    public function edit(string $id): void {
        $this->requireAdmin();
        $mon = $this->model->getById($id);
        if (!$mon) { $this->flash('error', 'Không tìm thấy môn học.'); $this->redirect('/mon-hoc'); }
        $this->view('mon_hoc/form', ['title' => 'Sửa Môn học', 'pageTitle' => 'Sửa thông tin Môn học',
            'mon' => $mon, 'action' => BASE_URL . '/mon-hoc/update/' . $id,
            'danhSachKhoa' => $this->khoaModel->getAll('ten_khoa ASC'),
            'danhSachMon'  => $this->model->getAll('ten_mon ASC')]);
    }

    public function update(string $id): void {
        $this->requireAdmin();
        $data = ['ten_mon' => $this->post('ten_mon'), 'tin_chi' => (int)$this->post('tin_chi', 3),
            'ma_khoa' => $this->post('ma_khoa') ?: null,
            'mon_tien_quyet' => $this->post('mon_tien_quyet') ?: null, 'mo_ta' => $this->post('mo_ta')];
        $errors = $this->validate($data, ['ten_mon' => 'required|max:150', 'tin_chi' => 'required|numeric']);
        if ($errors) {
            $data['ma_mon'] = $id;
            $this->view('mon_hoc/form', ['title' => 'Sửa Môn học', 'pageTitle' => 'Sửa thông tin Môn học',
                'mon' => $data, 'errors' => $errors, 'action' => BASE_URL . '/mon-hoc/update/' . $id,
                'danhSachKhoa' => $this->khoaModel->getAll('ten_khoa ASC'),
                'danhSachMon'  => $this->model->getAll('ten_mon ASC')]);
            return;
        }
        $this->model->update($id, $data);
        $this->flash('success', 'Cập nhật môn học thành công!');
        $this->redirect('/mon-hoc');
    }

    public function delete(string $id): void {
        $this->requireAdmin();
        $this->model->delete($id);
        $this->flash('success', 'Xóa môn học thành công!');
        $this->redirect('/mon-hoc');
    }
}
