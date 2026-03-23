<?php
// =============================================
// index.php - Front Controller
// =============================================

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/Model.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Router.php';

// Khởi động session
session_name(SESSION_NAME);
session_start();

// Session timeout
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > SESSION_TIMEOUT) {
    session_destroy();
    header('Location: ' . BASE_URL . '/auth/login');
    exit;
}
$_SESSION['last_activity'] = time();

// =============================================
// Định nghĩa Routes
// =============================================
$router = new Router();

// Auth
$router->get('/auth/login',  'AuthController', 'loginForm');
$router->post('/auth/login', 'AuthController', 'login');
$router->get('/auth/logout', 'AuthController', 'logout');

// Dashboard
$router->get('/',          'DashboardController', 'index');
$router->get('/dashboard', 'DashboardController', 'index');

// Khoa
$router->get('/khoa',             'KhoaController', 'index');
$router->get('/khoa/create',      'KhoaController', 'create');
$router->post('/khoa/store',      'KhoaController', 'store');
$router->get('/khoa/edit/:id',    'KhoaController', 'edit');
$router->post('/khoa/update/:id', 'KhoaController', 'update');
$router->get('/khoa/delete/:id',  'KhoaController', 'delete');

// Sinh viên
$router->get('/sinh-vien',                'SinhVienController', 'index');
$router->get('/sinh-vien/create',         'SinhVienController', 'create');
$router->post('/sinh-vien/store',         'SinhVienController', 'store');
$router->get('/sinh-vien/edit/:id',       'SinhVienController', 'edit');
$router->post('/sinh-vien/update/:id',    'SinhVienController', 'update');
$router->get('/sinh-vien/delete/:id',     'SinhVienController', 'delete');
$router->get('/sinh-vien/show/:id',       'SinhVienController', 'show');

// Giảng viên
$router->get('/giang-vien',               'GiangVienController', 'index');
$router->get('/giang-vien/create',        'GiangVienController', 'create');
$router->post('/giang-vien/store',        'GiangVienController', 'store');
$router->get('/giang-vien/edit/:id',      'GiangVienController', 'edit');
$router->post('/giang-vien/update/:id',   'GiangVienController', 'update');
$router->get('/giang-vien/delete/:id',    'GiangVienController', 'delete');

// Môn học
$router->get('/mon-hoc',                  'MonHocController', 'index');
$router->get('/mon-hoc/create',           'MonHocController', 'create');
$router->post('/mon-hoc/store',           'MonHocController', 'store');
$router->get('/mon-hoc/edit/:id',         'MonHocController', 'edit');
$router->post('/mon-hoc/update/:id',      'MonHocController', 'update');
$router->get('/mon-hoc/delete/:id',       'MonHocController', 'delete');

// Lớp học phần
$router->get('/lop-hoc-phan',             'LopHocPhanController', 'index');
$router->get('/lop-hoc-phan/create',      'LopHocPhanController', 'create');
$router->post('/lop-hoc-phan/store',      'LopHocPhanController', 'store');
$router->get('/lop-hoc-phan/edit/:id',    'LopHocPhanController', 'edit');
$router->post('/lop-hoc-phan/update/:id', 'LopHocPhanController', 'update');
$router->get('/lop-hoc-phan/delete/:id',  'LopHocPhanController', 'delete');

// Điểm số
$router->get('/diem-so',                  'DiemSoController', 'index');
$router->get('/diem-so/lop/:maLop',       'DiemSoController', 'byLop');
$router->post('/diem-so/nhap/:maLop',     'DiemSoController', 'nhap');

$router->dispatch();
