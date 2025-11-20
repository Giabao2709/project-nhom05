

<div class="container">
    <h3>Chào mừng bạn đến với trang quản trị!</h3>
    <p>Vui lòng chọn một chức năng từ menu bên trái.</p>
    
    <ul>
        <li><a href="index.php?module=khachhang&action=list">Quản lý Khách Hàng</a></li>
        <li><a href="index.php?module=tourdl&action=list">Quản lý Tour Du Lịch</a></li>
        <li><a href="index.php?module=diemden&action=list">Quản lý Điểm Đến</a></li>
        <li><a href="index.php?module=loaitour&action=list">Quản lý Loại Tour</a></li>
        <li><a href="index.php?module=hdv&action=list">Quản lý Hướng Dẫn Viên</a></li>
        <li><a href="index.php?module=donvivanchuyen&action=list">Quản lý ĐV Vận Chuyển</a></li>
        <li><a href="index.php?module=khuyenmai&action=list">Quản lý Khuyến mãi</a></li>
        <li><a href="index.php?module=dondattour&action=list">Quản lý Đơn Đặt Tour</a></li>
        <li><a href="index.php?module=thanhtoan&action=list">Quản lý Thanh Toán</a></li>
        <li style="border-top: 1px solid #ccc; margin-top: 10px; padding-top: 10px;">
                <a href="logout.php">Đăng xuất</a>
        </li>
    </ul>
</div>
<?php
// modules/dashboard/list.php (Trang Chủ - Bản Thống Kê)

// 1. Đặt tiêu đề cho trang
$page_title = 'Trang Chủ';

// 2. Khởi tạo các biến thống kê
$total_tours = 0;
$total_khachhang = 0;
$total_dondat = 0;
$total_hdv = 0;

try {
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
}
?>

<style>
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

