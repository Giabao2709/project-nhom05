<<<<<<< HEAD


<div class="container">
    <h3 style="color: #2c3e50;">👋 Chào mừng quản trị viên quay trở lại!</h3>
<p style="color: #7f8c8d;">Hệ thống quản lý tour du lịch v1.0</p>
</div>
<?php
// modules/dashboard/list.php (Trang Chủ - Bản Thống Kê)

// 1. Đặt tiêu đề cho trang
$page_title = 'Trang Chủ';

// 2. Khởi tạo các biến thống kê
=======
<?php
// modules/dashboard/list.php
// Module Dashboard - Dev: Thanh (UI Update)

$page_title = 'Bảng Điều Khiển';

// Khởi tạo biến thống kê (Tránh lỗi undefined variable)
>>>>>>> sv2-thanh
$total_tours = 0;
$total_khachhang = 0;
$total_dondat = 0;
$total_hdv = 0;

try {
<<<<<<< HEAD
    // $pdo đã có sẵn từ index.php
    
    // 3. Đếm tổng số Tour (từ bảng tourdl)
    $sql_tours = "SELECT COUNT(*) FROM tourdl";
    $total_tours = $pdo->query($sql_tours)->fetchColumn();

    // 4. Đếm tổng số Khách hàng
    $sql_kh = "SELECT COUNT(*) FROM khachhang";
    $total_khachhang = $pdo->query($sql_kh)->fetchColumn();

    // 5. Đếm tổng số Đơn đặt tour
    $sql_dd = "SELECT COUNT(*) FROM dondattour";
    $total_dondat = $pdo->query($sql_dd)->fetchColumn();

    // 6. Đếm tổng số Hướng dẫn viên
    $sql_hdv = "SELECT COUNT(*) FROM hdv";
    $total_hdv = $pdo->query($sql_hdv)->fetchColumn();

} catch (PDOException $e) {
    echo "Lỗi khi tải thống kê: " . $e->getMessage();
=======
    $total_tours = $pdo->query("SELECT COUNT(*) FROM tourdl")->fetchColumn();
    $total_khachhang = $pdo->query("SELECT COUNT(*) FROM khachhang")->fetchColumn();
    $total_dondat = $pdo->query("SELECT COUNT(*) FROM dondattour")->fetchColumn();
    $total_hdv = $pdo->query("SELECT COUNT(*) FROM hdv")->fetchColumn();
} catch (PDOException $e) {
    echo "Lỗi: " . $e->getMessage();
>>>>>>> sv2-thanh
}
?>

<style>
<<<<<<< HEAD
    .stat-container {
        display: flex; /* Sắp xếp các ô theo hàng ngang */
        flex-wrap: wrap; /* Tự xuống hàng nếu không đủ chỗ */
        gap: 20px; /* Khoảng cách giữa các ô */
        margin-top: 20px;
    }
    .stat-box {
        flex-basis: 200px; /* Chiều rộng cơ bản của mỗi ô */
        flex-grow: 1; /* Cho phép các ô tự co giãn */
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #f9f9f9;
        text-align: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .stat-box h2 {
        font-size: 3em;
        margin: 0 0 10px 0;
        color: #007bff; /* Màu xanh dương */
    }
    .stat-box p {
        font-size: 1.1em;
        margin: 0;
        color: #333;
        font-weight: bold;
    }
</style>

<div class="container">
    <p>Thống kê tổng quan hệ thống:</p>

    <div class="stat-container">
        
        <div class="stat-box">
            <h2><?php echo $total_tours; ?></h2>
            <p>Tổng số Tour</p>
        </div>

        <div class="stat-box">
            <h2><?php echo $total_khachhang; ?></h2>
            <p>Tổng số Khách Hàng</p>
        </div>

        <div class="stat-box">
            <h2><?php echo $total_dondat; ?></h2>
            <p>Tổng Đơn Đặt Tour</p>
        </div>

        <div class="stat-box">
            <h2><?php echo $total_hdv; ?></h2>
            <p>Tổng số HDV</p>
        </div>

    </div>
    
    </div>
<?php

=======
    .welcome-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 10px;
        margin-bottom: 30px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .stat-container { display: flex; gap: 20px; flex-wrap: wrap; }
    .stat-box {
        flex: 1;
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        border-left: 5px solid #007bff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        text-align: center;
    }
    .stat-box h2 { margin: 0; font-size: 2.5em; color: #333; }
    .stat-box p { margin: 5px 0 0; color: #666; font-weight: bold; text-transform: uppercase; font-size: 0.9em;}
</style>

<div class="container">
    <div class="welcome-box">
        <h2 style="margin-top: 0;">👋 Xin chào, Quản Trị Viên!</h2>
        <p>Chúc bạn một ngày làm việc hiệu quả. Hôm nay là: <strong><?php echo date('d/m/Y'); ?></strong></p>
    </div>

    <div class="stat-container">
        <div class="stat-box" style="border-color: #007bff;">
            <h2><?php echo $total_tours; ?></h2>
            <p>Tour Du Lịch</p>
        </div>
        <div class="stat-box" style="border-color: #28a745;">
            <h2><?php echo $total_khachhang; ?></h2>
            <p>Khách Hàng</p>
        </div>
        <div class="stat-box" style="border-color: #ffc107;">
            <h2><?php echo $total_dondat; ?></h2>
            <p>Đơn Đặt Tour</p>
        </div>
        <div class="stat-box" style="border-color: #dc3545;">
            <h2><?php echo $total_hdv; ?></h2>
            <p>Hướng Dẫn Viên</p>
        </div>
    </div>
</div>
>>>>>>> sv2-thanh
