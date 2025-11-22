<?php
session_start();
require_once 'config/db.php';

// Kiểm tra nếu có POST dữ liệu và đã đăng nhập
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['kh_id'])) {
    
    $ma_khach = $_SESSION['kh_id'];
    $ma_tour = $_POST['ma_tour'];
    $ma_khuyen_mai = isset($_POST['ma_khuyen_mai']) ? $_POST['ma_khuyen_mai'] : null;
    $phuong_thuc = $_POST['phuong_thuc']; // Lấy phương thức vừa chọn (Tiền mặt / Chuyển khoản)
    
    // Xử lý mã giảm giá = 0
    if ($ma_khuyen_mai == 0 || $ma_khuyen_mai == '0') {
        $ma_khuyen_mai = null; 
    }

    try {
        // 1. Lấy giá gốc từ DB (Không tin giá từ form gửi lên để tránh hack)
        $stmt = $pdo->prepare("SELECT gia_ban FROM tourdl WHERE maTour = ?");
        $stmt->execute([$ma_tour]);
        $tour = $stmt->fetch();
        
        if (!$tour) { die("Lỗi: Không tìm thấy tour!"); }

        $gia_final = $tour['gia_ban']; // Giá cuối cùng

        // 2. Tính toán giảm giá (Nếu có)
        if ($ma_khuyen_mai) {
            $stmt_km = $pdo->prepare("SELECT phan_tram FROM khuyenmai WHERE ma_khuyen_mai = ?");
            $stmt_km->execute([$ma_khuyen_mai]);
            $km = $stmt_km->fetch();
            
            if ($km) {
                $phan_tram = intval($km['phan_tram']);
                $giam = $gia_final * ($phan_tram / 100);
                $gia_final = $gia_final - $giam;
            }
        }

        // 3. XÁC ĐỊNH TRẠNG THÁI & NGÀY THANH TOÁN (LOGIC CHÍNH)
        $trang_thai_thanh_toan = 'Chưa thanh toán';
        $ngay_thanh_toan = null; // Mặc định là chưa có ngày

        if ($phuong_thuc == 'Chuyển khoản') {
            // Nếu chuyển khoản -> Coi như đã trả tiền ngay lúc này
            $trang_thai_thanh_toan = 'Đã thanh toán'; 
            $ngay_thanh_toan = date('Y-m-d H:i:s'); // Lấy giờ hiện tại (NOW)
        } 
        // Nếu là 'Tiền mặt' -> Giữ nguyên là 'Chưa thanh toán' và ngày là NULL

        // 4. Tạo Đơn đặt tour (dondattour)
        $sql_booking = "INSERT INTO dondattour (ma_khach_hang, ma_tour, ma_khuyen_mai, ngay_dat, trang_thai_don_hang) 
                        VALUES (?, ?, ?, NOW(), 'Mới')";
        $stmt_book = $pdo->prepare($sql_booking);
        $stmt_book->execute([$ma_khach, $ma_tour, $ma_khuyen_mai]);
        
        $ma_booking_moi = $pdo->lastInsertId();

        // 5. Tạo Thanh toán (thanhtoan) - Lưu đúng ngày giờ và trạng thái
        $sql_pay = "INSERT INTO thanhtoan (ma_booking, so_tien, trang_thai, phuong_thuc_thanh_toan, ngay_thanh_toan) 
                    VALUES (?, ?, ?, ?, ?)";
        $stmt_pay = $pdo->prepare($sql_pay);
        $stmt_pay->execute([
            $ma_booking_moi, 
            $gia_final, 
            $trang_thai_thanh_toan, 
            $phuong_thuc,
            $ngay_thanh_toan
        ]);

        // Thông báo thành công
        $msg = "Đặt tour thành công!\\n";
        $msg .= "Mã đơn: #$ma_booking_moi\\n";
        $msg .= "Tổng tiền: " . number_format($gia_final) . " VNĐ\\n";
        $msg .= "Phương thức: $phuong_thuc";

        echo "<script>
                alert('$msg');
                window.location='home.php';
              </script>";

    } catch (PDOException $e) {
        echo "Lỗi hệ thống: " . $e->getMessage();
    }
} else {
    header("Location: home.php");
}
?>