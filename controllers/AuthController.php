<?php
// =============================================
// Controllers/AuthController.php
// =============================================

require_once BASE_PATH . '/models/UserModel.php';

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
                // Đăng nhập thành công
                $_SESSION['user'] = [
                    'id'     => $user['id'],
                    'username'=> $user['username'],
                    'ho_ten' => $user['ho_ten'],
                    'email'  => $user['email'],
                    'role'   => $user['role'],
                    'avatar' => $user['avatar'],
                ];
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
