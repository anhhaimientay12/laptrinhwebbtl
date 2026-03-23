<?php
// =============================================
// EduManager - Cấu hình hệ thống
// =============================================

// Database
define('DB_HOST',     'localhost');
define('DB_NAME',     'edumanager');
define('DB_USER',     'root');
define('DB_PASSWORD', '');
define('DB_CHARSET',  'utf8mb4');

// App
define('APP_NAME',    'EduManager');
define('APP_VERSION', '1.0.0');
define('BASE_URL',    'http://localhost/edumanager');
define('BASE_PATH',   dirname(__DIR__));

// Session
define('SESSION_NAME',    'edu_session');
define('SESSION_TIMEOUT', 7200); // 2 giờ

// Pagination
define('PER_PAGE', 15);

// Upload
define('UPLOAD_PATH', BASE_PATH . '/uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB

// Timezone
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Error reporting (tắt khi production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
