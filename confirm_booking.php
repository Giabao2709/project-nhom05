<?php
session_start();
require_once 'config/db.php';

// 1. KIỂM TRA ĐĂNG NHẬP
// Nếu chưa có session kh_id (chưa đăng nhập) thì đá về trang chủ hoặc hiện thông báo
if (!isset($_SESSION['kh_id'])) {
    echo "<script>
            alert('Vui lòng đăng nhập để đặt tour!');
            window.location.href = 'home.php';
          </script>";
    exit();
}

// 2. KIỂM TRA DỮ LIỆU POST TỪ FORM
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $ma_khach = $_SESSION['kh_id'];
    
    // Lấy dữ liệu từ input của form (tên input phải khớp với bên home.php)
    $ma_tour = isset($_POST['id']) ? $_POST['id'] : null;           // Bên home là name="id"
    $ma_khuyen_mai = isset($_POST['ma_khuyen_mai']) ? $_POST['ma_khuyen_mai'] : null;
    $payment_code = isset($_POST['payment']) ? $_POST['payment'] : 'tien_mat'; // Bên home là name="payment"

    if (!$ma_tour) {
        die("Lỗi: Không tìm thấy thông tin tour!");
    }

    // Xử lý mapping tên phương thức thanh toán cho đẹp (để lưu vào DB)
    $phuong_thuc = 'Tiền mặt';
    if ($payment_code == 'chuyen_khoan') {
        $phuong_thuc = 'Chuyển khoản';
    }

    try {
        // --- BƯỚC 1: LẤY GIÁ GỐC TỪ DB (Bảo mật, không tin giá từ client) ---
        $stmt = $pdo->prepare("SELECT gia_ban FROM tourdl WHERE maTour = ?");
        $stmt->execute([$ma_tour]);
        $tour = $stmt->fetch();
        
        if (!$tour) { die("Lỗi: Tour không tồn tại trong hệ thống."); }

        $gia_goc = $tour['gia_ban'];
        $gia_final = $gia_goc; 

        // --- BƯỚC 2: TÍNH TOÁN GIẢM GIÁ (Server Side) ---
        // Phải tính lại trên server để đảm bảo user không sửa code HTML gian lận giá
        if (!empty($ma_khuyen_mai)) {
            $stmt_km = $pdo->prepare("SELECT phan_tram FROM khuyenmai WHERE ma_khuyen_mai = ?");
            $stmt_km->execute([$ma_khuyen_mai]);
            $km = $stmt_km->fetch();
            
            if ($km) {
                $phan_tram = intval($km['phan_tram']);
                $so_tien_giam = $gia_goc * ($phan_tram / 100);
                $gia_final = $gia_goc - $so_tien_giam;
            } else {
                // Nếu mã không hợp lệ (hack), reset về null
                $ma_khuyen_mai = null; 
            }
        } else {
            $ma_khuyen_mai = null; // Đảm bảo null nếu rỗng
        }

        // --- BƯỚC 3: XÁC ĐỊNH TRẠNG THÁI THANH TOÁN ---
        $trang_thai_thanh_toan = 'Chưa thanh toán';
        $ngay_thanh_toan = null; 

        if ($phuong_thuc == 'Chuyển khoản') {
            // Giả định chọn chuyển khoản là đã thanh toán (hoặc bạn có thể để 'Chờ duyệt')
            $trang_thai_thanh_toan = 'Đã thanh toán'; 
            $ngay_thanh_toan = date('Y-m-d H:i:s'); 
        } 

        // --- BƯỚC 4: INSERT VÀO BẢNG dondattour ---
        $pdo->beginTransaction(); // Dùng transaction để đảm bảo toàn vẹn dữ liệu

        $sql_booking = "INSERT INTO dondattour (ma_khach_hang, ma_tour, ma_khuyen_mai, ngay_dat, trang_thai_don_hang) 
                        VALUES (?, ?, ?, NOW(), 'Mới')";
        $stmt_book = $pdo->prepare($sql_booking);
        $stmt_book->execute([$ma_khach, $ma_tour, $ma_khuyen_mai]);
        
        $ma_booking_moi = $pdo->lastInsertId();

        // --- BƯỚC 5: INSERT VÀO BẢNG thanhtoan ---
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

        $pdo->commit(); // Xác nhận lưu

        // --- BƯỚC 6: THÔNG BÁO THÀNH CÔNG ---
        $msg = "ĐẶT TOUR THÀNH CÔNG!\\n";
        $msg .= "Mã đơn: #$ma_booking_moi\\n";
        $msg .= "Thanh toán: " . number_format($gia_final, 0, ',', '.') . " VNĐ";

        echo "<script>
                alert('$msg');
                window.location.href = 'home.php';
              </script>";

    } catch (PDOException $e) {
        $pdo->rollBack(); // Nếu lỗi thì hoàn tác
        echo "Lỗi hệ thống: " . $e->getMessage();
    }

} else {
    // Nếu ai đó cố tình truy cập file này mà không qua form POST
    header("Location: home.php");
    exit();
}
?>