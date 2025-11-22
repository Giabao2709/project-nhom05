<?php
session_start();
require_once 'config/db.php';

// 1. Kiểm tra đăng nhập
if (!isset($_SESSION['kh_id'])) {
    echo "<script>alert('Vui lòng đăng nhập để đặt tour!'); window.location='login_client.php';</script>";
    exit();
}

// 2. Lấy thông tin Tour muốn đặt
if (isset($_GET['id'])) {
    $ma_tour = $_GET['id'];
    $ma_khach = $_SESSION['kh_id']; // Lấy ID khách từ session đăng nhập

    try {
        // Lấy giá tour để tính tiền
        $stmt = $pdo->prepare("SELECT gia_ban FROM tourdl WHERE maTour = ?");
        $stmt->execute([$ma_tour]);
        $tour = $stmt->fetch();
        $gia_tour = $tour['gia_ban'];

        // A. TẠO ĐƠN ĐẶT TOUR (Insert vào dondattour)
        // Trạng thái mặc định là 'Mới'
        $sql_booking = "INSERT INTO dondattour (ma_khach_hang, ma_tour, ngay_dat, trang_thai_don_hang) 
                        VALUES (?, ?, NOW(), 'Mới')";
        $stmt_book = $pdo->prepare($sql_booking);
        $stmt_book->execute([$ma_khach, $ma_tour]);
        
        // Lấy mã booking vừa tạo
        $ma_booking_moi = $pdo->lastInsertId();

        // B. TẠO HÓA ĐƠN THANH TOÁN (Insert vào thanhtoan)
        // Trạng thái mặc định là 'Chưa thanh toán'
        $sql_pay = "INSERT INTO thanhtoan (ma_booking, so_tien, trang_thai, phuong_thuc_thanh_toan) 
                    VALUES (?, ?, 'Chưa thanh toán', 'Tiền mặt')";
        $stmt_pay = $pdo->prepare($sql_pay);
        $stmt_pay->execute([$ma_booking_moi, $gia_tour]);

        echo "<script>
                alert('Đặt tour thành công! Mã đơn hàng: #$ma_booking_moi. Nhân viên sẽ liên hệ sớm.');
                window.location='home.php';
              </script>";

    } catch (PDOException $e) {
        echo "Lỗi: " . $e->getMessage();
    }
} else {
    header("Location: home.php");
}
?>