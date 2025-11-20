<?php
// index.php (File điều hướng chính - PHIÊN BẢN ĐÃ SỬA LỖI)

// Bật báo lỗi (để gỡ lỗi)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 1. Tải file kết nối CSDL
require_once 'config/db.php';

// 2. Tải layout Header
include_once 'layouts/header.php'; // (File này sẽ tải sidebar.php)

// 3. Logic điều hướng (Routing)
$module = $_GET['module'] ?? 'dashboard'; // Mặc định là dashboard
$action = $_GET['action'] ?? 'list';     // Mặc định là list

// 4. Xây dựng đường dẫn đến file module
$module_path = "modules/$module/$action.php";

// 5. Kiểm tra file có tồn tại không TRƯỚC KHI tải
if (file_exists($module_path)) {
    // Tải file module (ví dụ: modules/tourdl/list.php)
    include_once $module_path;
} else {
    // Nếu không tìm thấy, tải trang dashboard mặc định
    // (Đây là lý do bạn thấy trang "Chào mừng")
    include_once 'modules/dashboard/list.php';
}

// 6. Tải layout Footer
include_once 'layouts/footer.php';

?>