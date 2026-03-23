<?php
// =============================================
// Controllers/SinhVienController.php
// =============================================

require_once BASE_PATH . '/models/SinhVienModel.php';
require_once BASE_PATH . '/models/KhoaModel.php';
require_once BASE_PATH . '/models/UserModel.php';

class SinhVienController extends Controller {

    private SinhVienModel $model;
    private KhoaModel $khoaModel;

    public function __construct() {
        $this->model     = new SinhVienModel();
        $this->khoaModel = new KhoaModel();
    }

    public function index(): void {
        $this->requireAuth();
        $search   = $this->get('search');
        $maKhoa   = $this->get('khoa');
        $page     = max(1, (int)$this->get('page', 1));
        $result   = $this->model->filter($search, $maKhoa, $page);

        $this->view('sinh_vien/index', [
            'title'     => 'Quản lý Sinh viên',
            'pageTitle' => 'Danh sách Sinh viên',
            'result'    => $result,
            'search'    => $search,
            'maKhoa'    => $maKhoa,
            'danhSachKhoa' => $this->khoaModel->getAll('ten_khoa ASC'),
        ]);
    }

    public function show(string $id): void {
        $this->requireAuth();
        $sv = $this->model->getDetail($id);
        if (!$sv) { $this->flash('error', 'Không tìm thấy sinh viên.'); $this->redirect('/sinh-vien'); }
        $this->view('sinh_vien/show', ['title' => 'Chi tiết SV', 'pageTitle' => 'Chi tiết Sinh viên', 'sv' => $sv]);
    }

    public function create(): void {
        $this->requireAdmin();
        $this->view('sinh_vien/form', [
            'title'        => 'Thêm Sinh viên',
            'pageTitle'    => 'Thêm Sinh viên mới',
            'sv'           => null,
            'action'       => BASE_URL . '/sinh-vien/store',
            'danhSachKhoa' => $this->khoaModel->getAll('ten_khoa ASC'),
        ]);
    }

    public function store(): void {
        $this->requireAdmin();
        $data = [
            'ma_sv'        => strtoupper($this->post('ma_sv')),
            'ho_ten'       => $this->post('ho_ten'),
            'email'        => $this->post('email'),
            'ngay_sinh'    => $this->post('ngay_sinh') ?: null,
            'gioi_tinh'    => $this->post('gioi_tinh', 'Nam'),
            'dia_chi'      => $this->post('dia_chi'),
            'ma_khoa'      => $this->post('ma_khoa') ?: null,
            'lop'          => $this->post('lop'),
            'nganh'        => $this->post('nganh'),
            'nam_nhap_hoc' => $this->post('nam_nhap_hoc') ?: null,
            'trang_thai'   => $this->post('trang_thai', 'dang_hoc'),
        ];
        $errors = $this->validate($data, [
            'ma_sv'  => 'required|max:10',
            'ho_ten' => 'required|max:100',
            'email'  => 'required|email',
        ]);
        if (!$errors && $this->model->exists('ma_sv', $data['ma_sv'])) $errors['ma_sv'] = 'Mã SV đã tồn tại.';
        if (!$errors && $this->model->exists('email', $data['email'])) $errors['email'] = 'Email đã tồn tại.';

        if ($errors) {
            $this->view('sinh_vien/form', [
                'title'        => 'Thêm Sinh viên',
                'pageTitle'    => 'Thêm Sinh viên mới',
                'sv'           => $data,
                'errors'       => $errors,
                'action'       => BASE_URL . '/sinh-vien/store',
                'danhSachKhoa' => $this->khoaModel->getAll('ten_khoa ASC'),
            ]);
            return;
        }

        // Tạo tài khoản user
        $userModel = new UserModel();
        $userModel->insert([
            'username' => strtolower($data['ma_sv']),
            'password' => password_hash('Edu@' . $data['ma_sv'], PASSWORD_BCRYPT),
            'email'    => $data['email'],
            'ho_ten'   => $data['ho_ten'],
            'role'     => 'sinh_vien',
        ]);
        $data['user_id'] = $userModel->lastId();
        $this->model->insert($data);
        $this->flash('success', 'Thêm sinh viên thành công! Mật khẩu mặc định: Edu@' . $data['ma_sv']);
        $this->redirect('/sinh-vien');
    }

    public function edit(string $id): void {
        $this->requireAdmin();
        $sv = $this->model->getById($id);
        if (!$sv) { $this->flash('error', 'Không tìm thấy sinh viên.'); $this->redirect('/sinh-vien'); }
        $this->view('sinh_vien/form', [
            'title'        => 'Sửa Sinh viên',
            'pageTitle'    => 'Sửa thông tin Sinh viên',
            'sv'           => $sv,
            'action'       => BASE_URL . '/sinh-vien/update/' . $id,
            'danhSachKhoa' => $this->khoaModel->getAll('ten_khoa ASC'),
        ]);
    }

    public function update(string $id): void {
        $this->requireAdmin();
        $data = [
            'ho_ten'       => $this->post('ho_ten'),
            'email'        => $this->post('email'),
            'ngay_sinh'    => $this->post('ngay_sinh') ?: null,
            'gioi_tinh'    => $this->post('gioi_tinh', 'Nam'),
            'dia_chi'      => $this->post('dia_chi'),
            'ma_khoa'      => $this->post('ma_khoa') ?: null,
            'lop'          => $this->post('lop'),
            'nganh'        => $this->post('nganh'),
            'nam_nhap_hoc' => $this->post('nam_nhap_hoc') ?: null,
            'trang_thai'   => $this->post('trang_thai', 'dang_hoc'),
        ];
        $errors = $this->validate($data, ['ho_ten' => 'required|max:100', 'email' => 'required|email']);
        if (!$errors && $this->model->exists('email', $data['email'], $id)) $errors['email'] = 'Email đã tồn tại.';

        if ($errors) {
            $data['ma_sv'] = $id;
            $this->view('sinh_vien/form', [
                'title'        => 'Sửa Sinh viên',
                'pageTitle'    => 'Sửa thông tin Sinh viên',
                'sv'           => $data,
                'errors'       => $errors,
                'action'       => BASE_URL . '/sinh-vien/update/' . $id,
                'danhSachKhoa' => $this->khoaModel->getAll('ten_khoa ASC'),
            ]);
            return;
        }
        $this->model->update($id, $data);
        $this->flash('success', 'Cập nhật sinh viên thành công!');
        $this->redirect('/sinh-vien');
    }

    public function delete(string $id): void {
        $this->requireAdmin();
        $this->model->delete($id);
        $this->flash('success', 'Xóa sinh viên thành công!');
        $this->redirect('/sinh-vien');
    }
}
