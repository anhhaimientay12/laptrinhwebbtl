<?php
// Controllers/DangKyController.php
require_once BASE_PATH . '/models/DangKyModel.php';
require_once BASE_PATH . '/models/LopHocPhanModel.php';
require_once BASE_PATH . '/models/SinhVienModel.php';

class DangKyController extends Controller {
    private DangKyModel $model;

    public function __construct() {
        $this->model = new DangKyModel();
    }

    /** Trang danh sách đã đăng ký */
    public function index(): void {
        $this->requireAuth();
        $user = $_SESSION['user'];
        
        // Nếu là sinh viên, lấy ma_sv từ session (phải map từ user_id sang)
        $maSv = $this->getMaSv($user['id']);
        if (!$maSv) {
            $this->flash('error', 'Không tìm thấy thông tin sinh viên.');
            $this->redirect('/dashboard');
        }

        $list = $this->model->getByStudent($maSv);
        $this->view('dang_ky/index', [
            'title'     => 'Học phần đã đăng ký',
            'pageTitle' => 'Danh sách học phần đã đăng ký',
            'list'      => $list
        ]);
    }

    /** Form đăng ký mới */
    public function create(): void {
        $this->requireAuth();
        $user = $_SESSION['user'];
        $maSv = $this->getMaSv($user['id']);
        if (!$maSv) {
            $this->flash('error', 'Không tìm thấy thông tin sinh viên.');
            $this->redirect('/dashboard');
        }

        $openLops = $this->model->getOpenLop($maSv);
        $this->view('dang_ky/form', [
            'title'     => 'Đăng ký học phần',
            'pageTitle' => 'Đăng ký học phần mới',
            'openLops'  => $openLops
        ]);
    }

    /** Xử lý đăng ký */
    public function store(): void {
        $this->requireAuth();
        if (!$this->isPost()) $this->redirect('/dang-ky/moi');

        $maLop = $this->post('ma_lop');
        $user  = $_SESSION['user'];
        $maSv  = $this->getMaSv($user['id']);

        if (!$maSv || !$maLop) {
            $this->flash('error', 'Dữ liệu không hợp lệ.');
            $this->redirect('/dang-ky/moi');
        }

        // Kiểm tra sĩ số
        $lopModel = new LopHocPhanModel();
        $lop = $lopModel->getById($maLop);
        if (!$lop || $lop['si_so_hien'] >= $lop['si_so_max']) {
            $this->flash('error', 'Lớp đã đầy hoặc không tồn tại.');
            $this->redirect('/dang-ky/moi');
        }

        // 1. Kiểm tra đã đăng ký lớp này chưa
        if ($this->model->isRegistered($maSv, $maLop)) {
            $this->flash('error', 'Bạn đã đăng ký lớp này rồi.');
            $this->redirect('/dang-ky/moi');
        }

        // 2. Kiểm tra trùng môn (trong cùng học kỳ)
        if ($this->model->checkSubjectDuplicate($maSv, $maLop)) {
            $this->flash('error', 'Bạn đã đăng ký một lớp khác của môn học này trong học kỳ này.');
            $this->redirect('/dang-ky/moi');
        }

        // 3. Kiểm tra trùng lịch học
        $conflictLop = $this->model->checkScheduleConflict($maSv, $maLop);
        if ($conflictLop) {
            $this->flash('error', "Trùng lịch học với lớp $conflictLop. Vui lòng chọn lớp khác.");
            $this->redirect('/dang-ky/moi');
        }

        // 4. Kiểm tra tổng tín chỉ (Max 20)
        $newLopInfo = $this->model->getSubjectByLop($maLop);
        $currentCredits = $this->model->getTotalCredits($maSv, $newLopInfo['hoc_ky'], $newLopInfo['nam_hoc']);
        if ($currentCredits + $newLopInfo['tin_chi'] > 20) {
            $this->flash('error', "Tổng số tín chỉ đăng ký không được vượt quá 20 TC. (Hiện tại: $currentCredits + " . $newLopInfo['tin_chi'] . ")");
            $this->redirect('/dang-ky/moi');
        }

        if ($this->model->register($maSv, $maLop)) {
            $this->flash('success', 'Đăng ký học phần thành công!');
        } else {
            $this->flash('error', 'Có lỗi xảy ra khi đăng ký.');
        }

        $this->redirect('/dang-ky');
    }

    /** Hủy đăng ký */
    public function delete(string $id): void {
        $this->requireAuth();
        if ($this->model->unregister($id)) {
            $this->flash('success', 'Hủy đăng ký học phần thành công!');
        } else {
            $this->flash('error', 'Không thể hủy đăng ký.');
        }
        $this->redirect('/dang-ky');
    }

    /** Helper: Lấy mã sinh viên từ user_id */
    private function getMaSv(int $userId): ?string {
        $svModel = new SinhVienModel();
        $sv = $svModel->getByUserId($userId);
        return $sv['ma_sv'] ?? null;
    }
}
