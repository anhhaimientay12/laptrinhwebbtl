<?php
// =============================================
// Controllers/KhoaController.php
// =============================================

require_once BASE_PATH . '/models/KhoaModel.php';

class KhoaController extends Controller {

    private KhoaModel $model;

    public function __construct() {
        $this->model = new KhoaModel();
    }

    public function index(): void {
        $this->requireAuth();
        $search = $this->get('search');
        $page   = max(1, (int)$this->get('page', 1));

        $result = $search
            ? $this->model->search($search, $page)
            : $this->model->paginate($page, PER_PAGE);

        $this->view('khoa/index', [
            'title'     => 'Quản lý Khoa',
            'pageTitle' => 'Danh sách Khoa',
            'result'    => $result,
            'search'    => $search,
        ]);
    }

    public function create(): void {
        $this->requireAdmin();
        $this->view('khoa/form', [
            'title'     => 'Thêm Khoa mới',
            'pageTitle' => 'Thêm Khoa mới',
            'khoa'      => null,
            'action'    => BASE_URL . '/khoa/store',
        ]);
    }

    public function store(): void {
        $this->requireAdmin();
        $data = [
            'ma_khoa'  => strtoupper($this->post('ma_khoa')),
            'ten_khoa' => $this->post('ten_khoa'),
            'mo_ta'    => $this->post('mo_ta'),
        ];
        $errors = $this->validate($data, [
            'ma_khoa'  => 'required|max:10',
            'ten_khoa' => 'required|max:100',
        ]);

        if (!$errors && $this->model->exists('ma_khoa', $data['ma_khoa'])) {
            $errors['ma_khoa'] = 'Mã khoa đã tồn tại.';
        }

        if ($errors) {
            $this->view('khoa/form', [
                'title'     => 'Thêm Khoa mới',
                'pageTitle' => 'Thêm Khoa mới',
                'khoa'      => $data,
                'errors'    => $errors,
                'action'    => BASE_URL . '/khoa/store',
            ]);
            return;
        }

        $this->model->insert($data);
        $this->flash('success', 'Thêm khoa thành công!');
        $this->redirect('/khoa');
    }

    public function edit(string $id): void {
        $this->requireAdmin();
        $khoa = $this->model->getById($id);
        if (!$khoa) { $this->flash('error', 'Không tìm thấy khoa.'); $this->redirect('/khoa'); }

        $this->view('khoa/form', [
            'title'     => 'Sửa Khoa',
            'pageTitle' => 'Sửa thông tin Khoa',
            'khoa'      => $khoa,
            'action'    => BASE_URL . '/khoa/update/' . $id,
        ]);
    }

    public function update(string $id): void {
        $this->requireAdmin();
        $data = [
            'ten_khoa' => $this->post('ten_khoa'),
            'mo_ta'    => $this->post('mo_ta'),
        ];
        $errors = $this->validate($data, ['ten_khoa' => 'required|max:100']);

        if ($errors) {
            $khoa = array_merge(['ma_khoa' => $id], $data);
            $this->view('khoa/form', [
                'title'     => 'Sửa Khoa',
                'pageTitle' => 'Sửa thông tin Khoa',
                'khoa'      => $khoa,
                'errors'    => $errors,
                'action'    => BASE_URL . '/khoa/update/' . $id,
            ]);
            return;
        }

        $this->model->update($id, $data);
        $this->flash('success', 'Cập nhật khoa thành công!');
        $this->redirect('/khoa');
    }

    public function delete(string $id): void {
        $this->requireAdmin();
        if ($this->model->hasSinhVien($id)) {
            $this->flash('error', 'Không thể xóa: Khoa đang có sinh viên!');
        } else {
            $this->model->delete($id);
            $this->flash('success', 'Xóa khoa thành công!');
        }
        $this->redirect('/khoa');
    }
}
