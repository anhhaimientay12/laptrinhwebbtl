<?php
// =============================================
// Core/Controller.php - Base Controller
// =============================================

abstract class Controller {

    /** Render view với data */
    protected function render(string $view, array $data = []): void {
        extract($data);
        $viewFile = BASE_PATH . "/views/{$view}.php";
        if (!file_exists($viewFile)) {
            die("View không tồn tại: {$view}");
        }
        require $viewFile;
    }

    /** Render với layout admin */
    protected function view(string $view, array $data = [], string $layout = 'layout/main'): void {
        $data['content_view'] = $view;
        $this->render($layout, $data);
    }

    /** Redirect */
    protected function redirect(string $url): void {
        header("Location: " . BASE_URL . $url);
        exit;
    }

    /** Trả về JSON */
    protected function json(mixed $data, int $code = 200): void {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /** Kiểm tra POST */
    protected function isPost(): bool {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /** Lấy & làm sạch POST */
    protected function post(string $key, mixed $default = ''): mixed {
        return isset($_POST[$key]) ? $this->sanitize($_POST[$key]) : $default;
    }

    /** Lấy & làm sạch GET */
    protected function get(string $key, mixed $default = ''): mixed {
        return isset($_GET[$key]) ? $this->sanitize($_GET[$key]) : $default;
    }

    /** Sanitize input */
    protected function sanitize(mixed $input): mixed {
        if (is_array($input)) {
            return array_map([$this, 'sanitize'], $input);
        }
        return htmlspecialchars(strip_tags(trim((string)$input)), ENT_QUOTES, 'UTF-8');
    }

    /** Lấy raw POST (không sanitize - dùng cho password) */
    protected function rawPost(string $key, mixed $default = ''): mixed {
        return $_POST[$key] ?? $default;
    }

    /** Set flash message */
    protected function flash(string $type, string $message): void {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    /** Kiểm tra đăng nhập */
    protected function requireAuth(): void {
        if (!isset($_SESSION['user'])) {
            $this->redirect('/auth/login');
        }
    }

    /** Kiểm tra quyền admin */
    protected function requireAdmin(): void {
        $this->requireAuth();
        if ($_SESSION['user']['role'] !== 'admin') {
            $this->flash('error', 'Bạn không có quyền truy cập trang này.');
            $this->redirect('/dashboard');
        }
    }

    /** Validate dữ liệu */
    protected function validate(array $data, array $rules): array {
        $errors = [];
        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? '';
            $ruleList = explode('|', $rule);
            foreach ($ruleList as $r) {
                if ($r === 'required' && empty($value)) {
                    $errors[$field] = "Trường {$field} là bắt buộc.";
                    break;
                }
                if (str_starts_with($r, 'min:')) {
                    $min = (int)substr($r, 4);
                    if (mb_strlen($value) < $min) {
                        $errors[$field] = "Trường {$field} phải có ít nhất {$min} ký tự.";
                        break;
                    }
                }
                if (str_starts_with($r, 'max:')) {
                    $max = (int)substr($r, 4);
                    if (mb_strlen($value) > $max) {
                        $errors[$field] = "Trường {$field} không được quá {$max} ký tự.";
                        break;
                    }
                }
                if ($r === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field] = "Email không hợp lệ.";
                    break;
                }
                if ($r === 'numeric' && !is_numeric($value)) {
                    $errors[$field] = "Trường {$field} phải là số.";
                    break;
                }
            }
        }
        return $errors;
    }
}
