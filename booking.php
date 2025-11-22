<?php
session_start();
require_once 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['kh_id'])) {
    
    $ma_khach = $_SESSION['kh_id'];
    $ma_tour = $_POST['ma_tour'];
    $ma_khuyen_mai = $_POST['ma_khuyen_mai']; // Có thể là 0 nếu không chọn
    
    if ($ma_khuyen_mai == 0) {
        $ma_khuyen_mai = null; // Để lưu vào DB là NULL
    }

    try {
        // 1. Lấy giá gốc của Tour (Để đảm bảo an toàn, không tin dữ liệu từ client)
        $stmt = $pdo->prepare("SELECT gia_ban FROM tourdl WHERE maTour = ?");
        $stmt->execute([$ma_tour]);
        $tour = $stmt->fetch();
        $gia_final = $tour['gia_ban'];

        // 2. Tính toán lại giảm giá (Server-side calculation)
        if ($ma_khuyen_mai) {
            $stmt_km = $pdo->prepare("SELECT phan_tram FROM khuyenmai WHERE ma_khuyen_mai = ?");
            $stmt_km->execute([$ma_khuyen_mai]);
            $km = $stmt_km->fetch();
            
            if ($km) {
                $giam = $gia_final * ($km['phan_tram'] / 100);
                $gia_final = $gia_final - $giam;
            }
        }

        // 3. Tạo Đơn đặt tour (Mới)
        $sql_booking = "INSERT INTO dondattour (ma_khach_hang, ma_tour, ma_khuyen_mai, ngay_dat, trang_thai_don_hang) 
                        VALUES (?, ?, ?, NOW(), 'Mới')";
        $stmt_book = $pdo->prepare($sql_booking);
        $stmt_book->execute([$ma_khach, $ma_tour, $ma_khuyen_mai]);
        
        $ma_booking_moi = $pdo->lastInsertId();

        // 4. Tạo Thanh toán (Với số tiền ĐÃ TRỪ giảm giá)
        $sql_pay = "INSERT INTO thanhtoan (ma_booking, so_tien, trang_thai, phuong_thuc_thanh_toan) 
                    VALUES (?, ?, 'Chưa thanh toán', 'Tiền mặt')";
        $stmt_pay = $pdo->prepare($sql_pay);
        $stmt_pay->execute([$ma_booking_moi, $gia_final]);

        echo "<script>
                alert('Đặt tour thành công!\\nTổng tiền phải thanh toán: " . number_format($gia_final) . " VNĐ');
                window.location='home.php';
              </script>";

    } catch (PDOException $e) {
        echo "Lỗi: " . $e->getMessage();
    }
} else {
    header("Location: home.php");
}
?>