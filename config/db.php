<?php
// db.php - File kết nối cơ sở dữ liệu

$host = 'localhost';    // Thường là 'localhost'
$dbname = 'quanlytour';     // Tên CSDL bạn đã tạo
$username = 'root';     // Tên đăng nhập CSDL (mặc định của XAMPP)
$password = '';         // Mật khẩu (mặc định của XAMPP là rỗng)
$charset = 'utf8mb4';   // Hỗ trợ tiếng Việt

// DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

try {
    // Tạo đối tượng PDO (PHP Data Objects)
    $pdo = new PDO($dsn, $username, $password);
    
    // Thiết lập chế độ báo lỗi để dễ dàng gỡ lỗi
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Bạn không cần echo ở đây, file này chỉ để kết nối
    // echo "Kết nối thành công!"; 
    
} catch (PDOException $e) {
    // Nếu kết nối thất bại, hiển thị lỗi
    echo "Kết nối thất bại: " . $e->getMessage();
    exit(); // Dừng chương trình
}

?>