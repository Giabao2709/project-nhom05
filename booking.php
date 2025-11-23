<?php
session_start();
require_once 'config/db.php';

// 1. LẤY ID TỪ URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: home.php");
    exit();
}

$ma_tour = $_GET['id'];

// 2. TRUY VẤN DỮ LIỆU TOUR (CẬP NHẬT MỚI: JOIN VỚI CÁC BẢNG KHÁC)
$tour = null;
try {
    // SỬA LẠI SQL: Nối bảng để lấy tên Loại, tên HDV, tên Nhà xe
    // Lưu ý: Tôi dùng LEFT JOIN để lỡ tour nào chưa có HDV thì web vẫn chạy bình thường không bị lỗi
    $sql = "SELECT 
                tourdl.*, 
                loaitour.tenloai AS ten_loai_tour, 
                hdv.ho_ten AS ten_hdv, 
                donvivanchuyen.ten_don_vi 
            FROM tourdl
            LEFT JOIN loaitour ON tourdl.ma_loai = loaitour.maloai
            LEFT JOIN hdv ON tourdl.ma_hdv = hdv.ma_hdv
            LEFT JOIN donvivanchuyen ON tourdl.ma_don_vi = donvivanchuyen.ma_don_vi
            WHERE tourdl.maTour = ?";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$ma_tour]);
    $tour = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Lỗi kết nối: " . $e->getMessage());
}

if (!$tour) { die("Không tìm thấy thông tin tour này!"); }

// 3. LẤY DANH SÁCH KHUYẾN MÃI
$promotions = [];
try {
    $currentDate = date('Y-m-d');
    $sql_km = "SELECT * FROM khuyenmai WHERE ngay_ket_thuc >= '$currentDate'";
    $stmt_km = $pdo->query($sql_km);
    $promotions = $stmt_km->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) { $promotions = []; }

// Kiểm tra đăng nhập
$is_logged_in = false;
$user_name = "Khách";
if (isset($_SESSION['client_name'])) {
    $is_logged_in = true;
    $user_name = $_SESSION['client_name'];
} elseif (isset($_SESSION['kh_name'])) {
    $is_logged_in = true;
    $user_name = $_SESSION['kh_name'];
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($tour['TenTour']); ?> - Chi tiết</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="layouts/client_style.css">
    
    <style>
        body { background-color: #f4f7f6; }

        .tour-detail-container { 
            max-width: 1100px; 
            margin: 120px auto 60px; 
            padding: 0 20px; 
            position: relative;
            z-index: 1;
        }

        .detail-header { 
            display: flex; gap: 30px; margin-bottom: 30px; 
            background: white; padding: 25px; border-radius: 15px; 
            box-shadow: 0 5px 20px rgba(0,0,0,0.05); align-items: flex-start; 
        }
        
        /* Chỉnh lại chiều cao ảnh để chứa đủ thông tin mới */
        .detail-image { 
            flex: 1.2; border-radius: 10px; overflow: hidden; 
            height: 520px; /* Tăng chiều cao lên */
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .detail-image img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s; }
        .detail-image:hover img { transform: scale(1.05); } 
        
        .detail-info { flex: 1; padding-left: 10px; display: flex; flex-direction: column; justify-content: center; }
        .detail-title { font-size: 2rem; font-weight: 800; color: #2c3e50; margin-bottom: 10px; line-height: 1.2; }
        .detail-price { font-size: 2rem; color: #e63946; font-weight: bold; margin-bottom: 20px; border-bottom: 2px solid #eee; padding-bottom: 15px; display: inline-block; width: 100%; }
        
        .info-list { list-style: none; padding: 0; margin-bottom: 25px; }
        .info-list li { margin-bottom: 12px; font-size: 1rem; color: #555; display: flex; align-items: center; }
        .info-list li i { width: 32px; height: 32px; line-height: 32px; background: #e9f7ef; color: #27ae60; text-align: center; border-radius: 50%; margin-right: 12px; font-size: 0.9rem; box-shadow: 0 2px 5px rgba(39, 174, 96, 0.1); }
        
        .tour-description { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); margin-top: 30px; }
        .tour-description h3 { border-left: 5px solid #27ae60; padding-left: 15px; margin-bottom: 25px; color: #2c3e50; font-size: 1.5rem; text-transform: uppercase; font-weight: 700; }
        .desc-content { line-height: 1.8; color: #444; font-size: 1.05rem; text-align: justify; }
        .desc-content img { max-width: 100%; height: auto; margin: 20px 0; border-radius: 8px; }

        .btn-book-big { background: linear-gradient(135deg, #ff512f 0%, #dd2476 100%); color: white; border: none; padding: 15px 0; width: 100%; font-size: 1.2rem; font-weight: bold; text-transform: uppercase; border-radius: 10px; cursor: pointer; transition: 0.3s; box-shadow: 0 10px 20px rgba(221, 36, 118, 0.3); display: flex; justify-content: center; align-items: center; gap: 10px; }
        .btn-book-big:hover { transform: translateY(-3px); box-shadow: 0 15px 25px rgba(221, 36, 118, 0.4); }

        .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 2000; justify-content: center; align-items: center; backdrop-filter: blur(5px); }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="logo">VIVU VIETNAM <i class="fas fa-paper-plane"></i></div>
        <div class="menu">
            <a href="home.php">Trang Chủ</a>
            <a href="home.php#tour-hot">Tour Hot</a>
            <a href="#">Tin Tức</a>
        </div>
        <div class="user-action">
            <?php if ($is_logged_in): ?>
                <span style="color: white; margin-right: 15px;">Chào, <b><?php echo htmlspecialchars($user_name); ?></b></span>
                <a href="logout_client.php" class="btn-login btn-logout">Thoát</a>
            <?php else: ?>
                <a href="home.php" class="btn-login">Đăng Nhập</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="tour-detail-container">
        <div class="detail-header">
            <div class="detail-image">
                <?php $hinh = !empty($tour['hinh_anh']) ? "uploads/".$tour['hinh_anh'] : "https://source.unsplash.com/random/800x600/?travel"; ?>
                <img src="<?php echo $hinh; ?>" alt="<?php echo htmlspecialchars($tour['TenTour']); ?>">
            </div>
            
            <div class="detail-info">
                <h1 class="detail-title"><?php echo htmlspecialchars($tour['TenTour']); ?></h1>
                <div class="detail-price"><?php echo number_format($tour['gia_ban'], 0, ',', '.'); ?> VNĐ</div>
                
                <ul class="info-list">
                    <li><i class="far fa-calendar-alt"></i> <b>Khởi hành:</b> &nbsp; <?php echo date("d/m/Y", strtotime($tour['ngay_khoi_hanh'])); ?></li>
                    <li><i class="far fa-clock"></i> <b>Thời gian:</b> &nbsp; <?php echo htmlspecialchars($tour['thoi_gian']); ?></li>
                    <li><i class="fas fa-chair"></i> <b>Số chỗ:</b> &nbsp; <?php echo $tour['so_cho_toi_da']; ?> khách</li>
                    <li><i class="fas fa-map-marker-alt"></i> <b>Nơi khởi hành:</b> &nbsp; TP. Hồ Chí Minh</li>

                    <li><i class="fas fa-flag"></i> <b>Loại Tour:</b> &nbsp; <?php echo htmlspecialchars($tour['ten_loai_tour'] ?? 'Tour tham quan'); ?></li>
                    <li><i class="fas fa-user-tie"></i> <b>Hướng Dẫn Viên:</b> &nbsp; <?php echo htmlspecialchars($tour['ten_hdv'] ?? 'Đang sắp xếp'); ?></li>
                    <li><i class="fas fa-bus"></i> <b>ĐV Vận Chuyển:</b> &nbsp; <?php echo htmlspecialchars($tour['ten_don_vi'] ?? 'Xe du lịch đời mới'); ?></li>
                </ul>

                <button class="btn-book-big" 
                    onclick="openBookingModal('<?php echo $tour['maTour']; ?>', '<?php echo htmlspecialchars($tour['TenTour']); ?>', <?php echo $tour['gia_ban']; ?>)">
                    <i class="fas fa-shopping-cart"></i> ĐẶT TOUR NGAY
                </button>
            </div>
        </div>

        <div class="tour-description">
            <h3>CHƯƠNG TRÌNH CHI TIẾT</h3>
            <div class="desc-content">
                <?php 
                    if (!empty($tour['mo_ta_hanh_trinh'])) {
                        echo html_entity_decode($tour['mo_ta_hanh_trinh']); 
                    } else {
                        echo "<p>Đang cập nhật lịch trình chi tiết...</p>";
                    }
                ?>
            </div>
        </div>
    </div>

    <div id="bookingModal" class="modal-overlay">
        <div class="booking-popup">
            <div class="booking-header"><h3>XÁC NHẬN THANH TOÁN</h3></div>
            <div class="booking-body">
                <form action="confirm_booking.php" method="POST">
                    <input type="hidden" name="id" id="modal_tour_id">
                    <input type="hidden" name="ma_khuyen_mai" id="hidden_ma_khuyen_mai" value="">

                    <div class="tour-summary">
                        <div class="tour-name" id="modal_tour_name">Loading...</div>
                        <div class="summary-row"><span>Giá vé:</span> <span id="modal_tour_price">0 VNĐ</span></div>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-ticket-alt"></i> Mã Giảm Giá:</label>
                        <select class="form-control" id="discount_select" onchange="calculateTotal()">
                            <option value="0" data-id="">-- Không áp dụng --</option>
                            <?php if (!empty($promotions)): ?>
                                <?php foreach ($promotions as $km): ?>
                                    <option value="<?php echo $km['phan_tram']; ?>" data-id="<?php echo $km['ma_khuyen_mai']; ?>">
                                        <?php echo htmlspecialchars($km['gia_tri']); ?> (Giảm <?php echo $km['phan_tram']; ?>%)
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="summary-row" style="color: green;"><span>Được giảm:</span> <span id="discount_amount">- 0 VNĐ</span></div>
                    <div class="total-row"><span>TỔNG CỘNG:</span> <span id="modal_tour_total">0 VNĐ</span></div>

                    <div class="payment-methods">
                        <label style="display:block; margin: 15px 0 10px; font-weight:600;">💳 Phương thức thanh toán:</label>
                        <label class="payment-option"><input type="radio" name="payment" value="tien_mat" checked> Tiền mặt</label>
                        <label class="payment-option"><input type="radio" name="payment" value="chuyen_khoan"> Chuyển khoản</label>
                    </div>

                    <button type="submit" class="btn-confirm">XÁC NHẬN ĐẶT VÉ</button>
                    <div class="btn-cancel" onclick="closeModal('bookingModal')">Hủy bỏ</div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let currentTourPrice = 0;
        function openBookingModal(id, name, price) {
            currentTourPrice = price;
            document.getElementById('modal_tour_id').value = id;
            document.getElementById('modal_tour_name').innerText = name;
            document.getElementById('discount_select').value = "0";
            document.getElementById('hidden_ma_khuyen_mai').value = "";
            updateDisplay();
            document.getElementById('bookingModal').style.display = 'flex';
        }
        function closeModal(modalId) { document.getElementById(modalId).style.display = 'none'; }
        window.onclick = function(event) { if (event.target.classList.contains('modal-overlay')) { event.target.style.display = "none"; } }
        function calculateTotal() { updateDisplay(); }
        function updateDisplay() {
            let selectBox = document.getElementById('discount_select');
            let selectedOption = selectBox.options[selectBox.selectedIndex];
            let codeId = selectedOption.getAttribute('data-id');
            document.getElementById('hidden_ma_khuyen_mai').value = codeId;
            let discountPercent = parseInt(selectBox.value);
            let discountAmount = currentTourPrice * (discountPercent / 100);
            let totalAmount = currentTourPrice - discountAmount;
            let fmt = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' });
            document.getElementById('modal_tour_price').innerText = fmt.format(currentTourPrice);
            document.getElementById('discount_amount').innerText = "- " + fmt.format(discountAmount);
            document.getElementById('modal_tour_total').innerText = fmt.format(totalAmount);
        }
    </script>
</body>
</html>