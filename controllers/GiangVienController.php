<?php
// Controllers/GiangVienController.php
require_once BASE_PATH . '/models/GiangVienModel.php';
require_once BASE_PATH . '/models/KhoaModel.php';
require_once BASE_PATH . '/models/UserModel.php';

class GiangVienController extends Controller {
    private GiangVienModel $model;
    private KhoaModel $khoaModel;

    public function __construct() {
        $this->model     = new GiangVienModel();
        $this->khoaModel = new KhoaModel();
    }

    public function index(): void {
        $this->requireAuth();
        $page   = max(1, (int)$this->get('page', 1));
        $search = $this->get('search');
        $result = $this->model->filter($search, $page);
        $this->view('giang_vien/index', [
            'title' => 'Quản lý Giảng viên', 'pageTitle' => 'Danh sách Giảng viên',
            'result' => $result, 'search' => $search,
        ]);
    }

    public function create(): void {
        $this->requireAdmin();
        $this->view('giang_vien/form', [
            'title' => 'Thêm Giảng viên', 'pageTitle' => 'Thêm Giảng viên mới',
            'gv' => null, 'action' => BASE_URL . '/giang-vien/store',
            'danhSachKhoa' => $this->khoaModel->getAll('ten_khoa ASC'),
        ]);
    }

    public function store(): void {
        $this->requireAdmin();
        $data = [
            'ma_gv'      => strtoupper($this->post('ma_gv')),
            'ho_ten'     => $this->post('ho_ten'),
            'email'      => $this->post('email'),
            'ma_khoa'    => $this->post('ma_khoa') ?: null,
            'hoc_vi'     => $this->post('hoc_vi', 'Thac si'),
            'dien_thoai' => $this->post('dien_thoai'),
        ];
        $errors = $this->validate($data, ['ma_gv' => 'required|max:10', 'ho_ten' => 'required', 'email' => 'required|email']);
        if (!$errors && $this->model->exists('ma_gv', $data['ma_gv'])) $errors['ma_gv'] = 'Mã GV đã tồn tại.';

        if ($errors) {
            $this->view('giang_vien/form', ['title' => 'Thêm Giảng viên', 'pageTitle' => 'Thêm Giảng viên mới',
                'gv' => $data, 'errors' => $errors, 'action' => BASE_URL . '/giang-vien/store',
                'danhSachKhoa' => $this->khoaModel->getAll('ten_khoa ASC')]);
            return;
        }
        $userModel = new UserModel();
        $userModel->insert(['username' => strtolower($data['ma_gv']), 'password' => password_hash('Edu@' . $data['ma_gv'], PASSWORD_BCRYPT),
            'email' => $data['email'], 'ho_ten' => $data['ho_ten'], 'role' => 'giang_vien']);
        $data['user_id'] = $userModel->lastId();
        $this->model->insert($data);
        $this->flash('success', 'Thêm giảng viên thành công!');
        $this->redirect('/giang-vien');
    }

    public function edit(string $id): void {
        $this->requireAdmin();
        $gv = $this->model->getById($id);
        if (!$gv) { $this->flash('error', 'Không tìm thấy giảng viên.'); $this->redirect('/giang-vien'); }
        $this->view('giang_vien/form', ['title' => 'Sửa Giảng viên', 'pageTitle' => 'Sửa thông tin Giảng viên',
            'gv' => $gv, 'action' => BASE_URL . '/giang-vien/update/' . $id,
            'danhSachKhoa' => $this->khoaModel->getAll('ten_khoa ASC')]);
    }

    public function update(string $id): void {
        $this->requireAdmin();
        $data = ['ho_ten' => $this->post('ho_ten'), 'email' => $this->post('email'),
            'ma_khoa' => $this->post('ma_khoa') ?: null, 'hoc_vi' => $this->post('hoc_vi'), 'dien_thoai' => $this->post('dien_thoai')];
        $errors = $this->validate($data, ['ho_ten' => 'required', 'email' => 'required|email']);
        if ($errors) {
            $data['ma_gv'] = $id;
            $this->view('giang_vien/form', ['title' => 'Sửa Giảng viên', 'pageTitle' => 'Sửa thông tin Giảng viên',
                'gv' => $data, 'errors' => $errors, 'action' => BASE_URL . '/giang-vien/update/' . $id,
                'danhSachKhoa' => $this->khoaModel->getAll('ten_khoa ASC')]);
            return;
        }
        $this->model->update($id, $data);
        $this->flash('success', 'Cập nhật giảng viên thành công!');
        $this->redirect('/giang-vien');
    }

    public function delete(string $id): void {
        $this->requireAdmin();
        $this->model->delete($id);
        $this->flash('success', 'Xóa giảng viên thành công!');
        $this->redirect('/giang-vien');
    }
}
