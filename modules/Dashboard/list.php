<?php
// modules/dashboard/list.php
// Module Dashboard - Dev: Thanh (UI Update) - Merged by SV3

$page_title = 'Bảng Điều Khiển';

// Khởi tạo biến thống kê (Tránh lỗi undefined variable)
$total_tours = 0;
$total_khachhang = 0;
$total_dondat = 0;
$total_hdv = 0;

try {
    // Truy vấn dữ liệu thống kê nhanh
    $total_tours = $pdo->query("SELECT COUNT(*) FROM tourdl")->fetchColumn();
    $total_khachhang = $pdo->query("SELECT COUNT(*) FROM khachhang")->fetchColumn();
    $total_dondat = $pdo->query("SELECT COUNT(*) FROM dondattour")->fetchColumn();
    $total_hdv = $pdo->query("SELECT COUNT(*) FROM hdv")->fetchColumn();
} catch (PDOException $e) {
    echo "Lỗi: " . $e->getMessage();
}
?>

<style>
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
    <!-- Phần chào mừng -->
    <div class="welcome-box">
        <h2 style="margin-top: 0;">👋 Xin chào, Quản Trị Viên!</h2>
        <p>Chúc bạn một ngày làm việc hiệu quả. Hôm nay là: <strong><?php echo date('d/m/Y'); ?></strong></p>
    </div>

    <!-- Phần thống kê -->
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