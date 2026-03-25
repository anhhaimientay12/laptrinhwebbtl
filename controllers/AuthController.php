<?php
// =============================================
// Controllers/AuthController.php
// =============================================

require_once BASE_PATH . '/models/UserModel.php';
require_once BASE_PATH . '/core/Database.php';

class AuthController extends Controller {

    private UserModel $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    /** Hiển thị form đăng nhập */
    public function loginForm(): void {
        if (isset($_SESSION['user'])) {
            $this->redirect('/dashboard');
        }
        $this->render('auth/login', ['title' => 'Đăng nhập — ' . APP_NAME]);
    }

    /** Xử lý đăng nhập */
    public function login(): void {
        $username = $this->rawPost('username');
        $password = $this->rawPost('password');
        $errors   = [];

        if (empty($username)) $errors['username'] = 'Vui lòng nhập tên đăng nhập.';
        if (empty($password)) $errors['password'] = 'Vui lòng nhập mật khẩu.';

        if (!$errors) {
            $user = $this->userModel->findByUsername($username);

            if (!$user || !password_verify($password, $user['password'])) {
                $errors['general'] = 'Tên đăng nhập hoặc mật khẩu không đúng.';
            } elseif (!$user['is_active']) {
                $errors['general'] = 'Tài khoản đã bị vô hiệu hóa.';
            } else {
                // Đăng nhập thành công - lưu thông tin cơ bản
                $_SESSION['user'] = [
                    'id'       => $user['id'],
                    'username' => $user['username'],
                    'ho_ten'   => $user['ho_ten'],
                    'email'    => $user['email'],
                    'role'     => $user['role'],
                    'avatar'   => $user['avatar'],
                ];
                
                $db = Database::getInstance();
                
                // Lấy mã theo role
                if ($user['role'] === 'admin') {
                    // Admin: không cần mã đặc biệt
                    $_SESSION['is_admin'] = true;
                    
                } elseif ($user['role'] === 'giang_vien') {
                    // Lấy ma_gv từ bảng giang_vien
                    $sql = "SELECT ma_gv FROM giang_vien WHERE user_id = ?";
                    $gv = $db->fetchOne($sql, [$user['id']]);
                    if ($gv) {
                        $_SESSION['ma_gv'] = $gv['ma_gv'];
                        $_SESSION['user']['ma_gv'] = $gv['ma_gv'];
                    } else {
                        // Fallback: tìm theo email
                        $sql2 = "SELECT ma_gv FROM giang_vien WHERE email = ?";
                        $gv2 = $db->fetchOne($sql2, [$user['email']]);
                        if ($gv2) {
                            $_SESSION['ma_gv'] = $gv2['ma_gv'];
                            $_SESSION['user']['ma_gv'] = $gv2['ma_gv'];
                        }
                    }
                    
                } elseif ($user['role'] === 'sinh_vien') {
                    // Lấy ma_sv từ bảng sinh_vien
                    $sql = "SELECT ma_sv FROM sinh_vien WHERE user_id = ?";
                    $sv = $db->fetchOne($sql, [$user['id']]);
                    if ($sv) {
                        $_SESSION['ma_sv'] = $sv['ma_sv'];
                        $_SESSION['user']['ma_sv'] = $sv['ma_sv'];
                    } else {
                        // Fallback: tìm theo email
                        $sql2 = "SELECT ma_sv FROM sinh_vien WHERE email = ?";
                        $sv2 = $db->fetchOne($sql2, [$user['email']]);
                        if ($sv2) {
                            $_SESSION['ma_sv'] = $sv2['ma_sv'];
                            $_SESSION['user']['ma_sv'] = $sv2['ma_sv'];
                        }
                    }
                }
                
                session_regenerate_id(true);
                $this->redirect('/dashboard');
            }
        }

        $this->render('auth/login', [
            'title'    => 'Đăng nhập — ' . APP_NAME,
            'errors'   => $errors,
            'username' => htmlspecialchars($username),
        ]);
    }

    /** Đăng xuất */
    public function logout(): void {
        session_destroy();
        $this->redirect('/auth/login');
    }
}
