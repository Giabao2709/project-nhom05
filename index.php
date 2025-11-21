<?php
/**
 * SYSTEM ROUTER (Bộ điều hướng trung tâm)
 * Fixed by: SV1 - Thuan (Fix path errors & White screen)
 */

// 1. Bật báo lỗi để dễ debug (Quan trọng)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Định nghĩa đường dẫn gốc
define('BASE_PATH', __DIR__);

// 3. Nạp kết nối CSDL
require_once BASE_PATH . '/config/db.php';

// 4. Nạp giao diện chính
include_once BASE_PATH . '/layouts/header.php';

// 5. Xử lý điều hướng
$module = $_GET['module'] ?? 'dashboard';
$action = $_GET['action'] ?? 'list';

$path = BASE_PATH . "/modules/$module/$action.php";

if (file_exists($path)) {
    include_once $path;
} else {
    // Fallback về dashboard nếu không tìm thấy file
    include_once BASE_PATH . '/modules/dashboard/list.php';
}

include_once BASE_PATH . '/layouts/footer.php';
?>