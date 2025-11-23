<?php
session_start();
require_once 'config/db.php';

// [QUAN TRỌNG] Thiết lập múi giờ Việt Nam ngay đầu file
date_default_timezone_set('Asia/Ho_Chi_Minh');

// 1. KIỂM TRA ĐĂNG NHẬP
if (!isset($_SESSION['client_id'])) {
    echo "<script>
            alert('Vui lòng đăng nhập để đặt tour!');
            window.location.href = 'home.php';
          </script>";
    exit();
}

// 2. KIỂM TRA DỮ LIỆU POST TỪ FORM
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $ma_khach = $_SESSION['client_id'];
    
    $ma_tour = isset($_POST['id']) ? $_POST['id'] : null;
    $ma_khuyen_mai = isset($_POST['ma_khuyen_mai']) ? $_POST['ma_khuyen_mai'] : null;
    $payment_code = isset($_POST['payment']) ? $_POST['payment'] : 'tien_mat';

    if (!$ma_tour) {
        die("Lỗi: Không tìm thấy thông tin tour!");
    }

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

        // --- BƯỚC 3: XÁC ĐỊNH TRẠNG THÁI VÀ NGÀY GIỜ ---
        
        // [SỬA ĐỔI] Luôn lấy ngày giờ hiện tại cho mọi giao dịch
        $ngay_thanh_toan = date('Y-m-d H:i:s'); 

        // Mặc định Tiền mặt là 'Chưa thanh toán' (nhưng vẫn lưu ngày tạo giao dịch)
        $trang_thai_thanh_toan = 'Chưa thanh toán'; 

        if ($phuong_thuc == 'Chuyển khoản') {
            $trang_thai_thanh_toan = 'Đã thanh toán'; 
        } 
        // Nếu bạn muốn Tiền mặt cũng tính là Đã thanh toán luôn thì mở comment dòng dưới ra:
        // else { $trang_thai_thanh_toan = 'Đã thanh toán'; }

        // --- BƯỚC 4: INSERT VÀO DB ---
        $pdo->beginTransaction(); 

        // Lưu đơn hàng
        $sql_booking = "INSERT INTO dondattour (ma_khach_hang, ma_tour, ma_khuyen_mai, ngay_dat, trang_thai_don_hang) 
                        VALUES (?, ?, ?, ?, 'Mới')";
        // Dùng biến $ngay_thanh_toan cho đồng bộ
        $stmt_book = $pdo->prepare($sql_booking);
        $stmt_book->execute([$ma_khach, $ma_tour, $ma_khuyen_mai, $ngay_thanh_toan]);
        
        $ma_booking_moi = $pdo->lastInsertId();

        // Lưu thanh toán
        $sql_pay = "INSERT INTO thanhtoan (ma_booking, so_tien, trang_thai, phuong_thuc_thanh_toan, ngay_thanh_toan) 
                    VALUES (?, ?, ?, ?, ?)";
        $stmt_pay = $pdo->prepare($sql_pay);
        $stmt_pay->execute([
            $ma_booking_moi, 
            $gia_final, 
            $trang_thai_thanh_toan, 
            $phuong_thuc,
            $ngay_thanh_toan // Lưu ngày giờ VN vào đây
        ]);

        $pdo->commit();

        // --- BƯỚC 6: THÔNG BÁO THÀNH CÔNG ---
        $msg = "ĐẶT TOUR THÀNH CÔNG!\\n";
        $msg .= "Mã đơn: #$ma_booking_moi\\n";
        $msg .= "Thời gian: " . date('H:i d/m/Y', strtotime($ngay_thanh_toan));

        echo "<script>
                alert('$msg');
                window.location.href = 'home.php';
              </script>";

    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "Lỗi hệ thống: " . $e->getMessage();
    }

} else {
    header("Location: home.php");
    exit();
}
?>