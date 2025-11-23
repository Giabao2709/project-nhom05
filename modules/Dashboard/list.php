<?php
$page_title = 'Tổng Quan Hệ Thống';

// Lấy số liệu
try {
    $total_tours       = $pdo->query("SELECT COUNT(*) FROM tourdl")->fetchColumn();
    $total_khachhang   = $pdo->query("SELECT COUNT(*) FROM khachhang")->fetchColumn();
    $total_dondat      = $pdo->query("SELECT COUNT(*) FROM dondattour")->fetchColumn();
    $total_doanhthu    = $pdo->query("SELECT IFNULL(SUM(so_tien), 0) FROM thanhtoan WHERE trang_thai = 'Đã thanh toán'")->fetchColumn();
} catch (PDOException $e) {
    echo "Lỗi truy vấn: " . $e->getMessage();
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }
    .card {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: 0.25s ease;
    }
    .card:hover {
        transform: translateY(-6px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }
    .card-info h3 {
        font-size: 2.4em;
        margin: 0;
        color: #333;
    }
    .card-info p {
        margin: 6px 0 0;
        color: #555;
        font-weight: 600;
        font-size: 1.05em;
    }
    .card-icon {
        font-size: 3.3em;
        opacity: 0.15;
    }
    .c-blue { color: #007bff; }
    .c-green { color: #28a745; }
    .c-orange { color: #ffc107; }
    .c-red { color: #dc3545; }
</style>

<div class="container">
    <h2 style="border-left: 5px solid #007bff; padding-left: 15px; color: #444; margin-top:10px;">
        👋 Xin chào, Admin!
    </h2>
    <p>Báo cáo tổng quan ngày: <strong><?php echo date('d/m/Y'); ?></strong></p>

    <div class="dashboard-grid">

        <div class="card" style="border-bottom: 4px solid #007bff;">
            <div class="card-info">
                <h3><?php echo $total_tours; ?></h3>
                <p>Tổng Tour Du Lịch</p>
            </div>
            <div class="card-icon c-blue"><i class="fas fa-plane-departure"></i></div>
        </div>

        <div class="card" style="border-bottom: 4px solid #28a745;">
            <div class="card-info">
                <h3><?php echo $total_khachhang; ?></h3>
                <p>Khách Hàng</p>
            </div>
            <div class="card-icon c-green"><i class="fas fa-users"></i></div>
        </div>

        <div class="card" style="border-bottom: 4px solid #ffc107;">
            <div class="card-info">
                <h3><?php echo $total_dondat; ?></h3>
                <p>Đơn Đặt Tour</p>
            </div>
            <div class="card-icon c-orange"><i class="fas fa-shopping-cart"></i></div>
        </div>

        <div class="card" style="border-bottom: 4px solid #dc3545;">
            <div class="card-info">
                <h3 style="font-size: 1.9em;"><?php echo number_format($total_doanhthu); ?>₫</h3>
                <p>Doanh Thu</p>
            </div>
            <div class="card-icon c-red"><i class="fas fa-money-bill-wave"></i></div>
        </div>

    </div>
</div>
