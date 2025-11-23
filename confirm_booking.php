<?php
session_start();
require_once 'config/db.php';

// 1. KIỂM TRA ĐĂNG NHẬP (SỬA LẠI CHO KHỚP VỚI LOGIN_CLIENT.PHP)
// File login của bạn dùng 'client_id', nên ở đây phải check 'client_id'
if (!isset($_SESSION['client_id'])) {
    echo "<script>
            alert('Vui lòng đăng nhập để đặt tour!');
            window.location.href = 'home.php'; // Quay lại trang chủ để đăng nhập
          </script>";
    exit();
}

// 2. KIỂM TRA DỮ LIỆU POST TỪ FORM
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Lấy ID khách hàng từ Session đúng
    $ma_khach = $_SESSION['client_id'];
    
    // Lấy dữ liệu từ input của form (tên input phải khớp với bên home.php)
    $ma_tour = isset($_POST['id']) ? $_POST['id'] : null;
    $ma_khuyen_mai = isset($_POST['ma_khuyen_mai']) ? $_POST['ma_khuyen_mai'] : null;
    $payment_code = isset($_POST['payment']) ? $_POST['payment'] : 'tien_mat';

    if (!$ma_tour) {
        die("Lỗi: Không tìm thấy thông tin tour!");
    }

    // Mapping tên phương thức thanh toán
    $phuong_thuc = 'Tiền mặt';
    if ($payment_code == 'chuyen_khoan') {
        $phuong_thuc = 'Chuyển khoản';
    }

    try {
        // --- BƯỚC 1: LẤY GIÁ GỐC TỪ DB ---
        $stmt = $pdo->prepare("SELECT gia_ban FROM tourdl WHERE maTour = ?");
        $stmt->execute([$ma_tour]);
        $tour = $stmt->fetch();
        
        if (!$tour) { die("Lỗi: Tour không tồn tại trong hệ thống."); }

        $gia_goc = $tour['gia_ban'];
        $gia_final = $gia_goc; 

        // --- BƯỚC 2: TÍNH TOÁN GIẢM GIÁ ---
        // Xử lý nếu mã khuyến mãi rỗng hoặc bằng 0
        if (empty($ma_khuyen_mai) || $ma_khuyen_mai == '0') {
            $ma_khuyen_mai = null; 
        } else {
            $stmt_km = $pdo->prepare("SELECT phan_tram FROM khuyenmai WHERE ma_khuyen_mai = ?");
            $stmt_km->execute([$ma_khuyen_mai]);
            $km = $stmt_km->fetch();
            
            if ($km) {
                $phan_tram = intval($km['phan_tram']);
                $so_tien_giam = $gia_goc * ($phan_tram / 100);
                $gia_final = $gia_goc - $so_tien_giam;
            } else {
                $ma_khuyen_mai = null; 
            }
        }

        // --- BƯỚC 3: XÁC ĐỊNH TRẠNG THÁI THANH TOÁN ---
        $trang_thai_thanh_toan = 'Chưa thanh toán';
        $ngay_thanh_toan = null; 

        if ($phuong_thuc == 'Chuyển khoản') {
            $trang_thai_thanh_toan = 'Đã thanh toán'; 
            $ngay_thanh_toan = date('Y-m-d H:i:s'); 
        } 

        // Bắt đầu Transaction (đảm bảo lưu cả 2 bảng thành công mới tính là xong)
        $pdo->beginTransaction(); 

        // --- BƯỚC 4: INSERT VÀO BẢNG dondattour (Để hiện bên Admin) ---
        $sql_booking = "INSERT INTO dondattour (ma_khach_hang, ma_tour, ma_khuyen_mai, ngay_dat, trang_thai_don_hang) 
                        VALUES (?, ?, ?, NOW(), 'Mới')";
        $stmt_book = $pdo->prepare($sql_booking);
        $stmt_book->execute([$ma_khach, $ma_tour, $ma_khuyen_mai]);
        
        $ma_booking_moi = $pdo->lastInsertId(); // Lấy mã đơn vừa tạo

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

        $pdo->commit(); // Xác nhận lưu vào DB

        // --- BƯỚC 6: THÔNG BÁO THÀNH CÔNG ---
        $msg = "ĐẶT TOUR THÀNH CÔNG!\\n";
        $msg .= "Mã đơn: #$ma_booking_moi\\n";
        $msg .= "Admin đã nhận được đơn hàng của bạn.";

        echo "<script>
                alert('$msg');
                window.location.href = 'home.php';
              </script>";

    } catch (PDOException $e) {
        $pdo->rollBack(); // Nếu lỗi thì hoàn tác
        echo "Lỗi hệ thống: " . $e->getMessage();
    }

} else {
    header("Location: home.php");
    exit();
}
?>