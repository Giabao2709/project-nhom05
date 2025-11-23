<?php
session_start();
require_once 'config/db.php';

// KIỂM TRA ĐĂNG NHẬP
$is_logged_in = false;
$user_name = "Khách";
if (isset($_SESSION['client_name']) && !empty($_SESSION['client_name'])) {
    $is_logged_in = true;
    $user_name = $_SESSION['client_name'];
} elseif (isset($_SESSION['kh_name']) && !empty($_SESSION['kh_name'])) {
    $is_logged_in = true;
    $user_name = $_SESSION['kh_name'];
}

// 1. LẤY DANH SÁCH TOUR
$tours = [];
try {
    $sql = "SELECT * FROM tourdl ORDER BY maTour DESC LIMIT 6";
    $stmt = $pdo->query($sql);
    $tours = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) { $tours = []; }

// 2. LẤY DANH SÁCH KHUYẾN MÃI (Chỉ lấy mã còn hạn sử dụng)
$promotions = [];
try {
    // Lấy ngày hiện tại
    $currentDate = date('Y-m-d');
    // Query: Lấy mã có ngày kết thúc >= ngày hiện tại
    $sql_km = "SELECT * FROM khuyenmai WHERE ngay_ket_thuc >= '$currentDate'";
    $stmt_km = $pdo->query($sql_km);
    $promotions = $stmt_km->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) { $promotions = []; }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vivu Vietnam - Trang Chủ</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="layouts/client_style.css">
    
    <style>
        .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 2000; justify-content: center; align-items: center; backdrop-filter: blur(5px); }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="logo">VIVU VIETNAM <i class="fas fa-paper-plane"></i></div>
        <div class="menu">
            <a href="home.php">Trang Chủ</a>
            <a href="#tour-hot">Tour Hot</a>
            <a href="#">Tin Tức</a>
            <a href="#">Liên Hệ</a>
        </div>
        <div class="user-action">
            <?php if ($is_logged_in): ?>
                <span style="color: white; margin-right: 15px;">Chào, <b><?php echo htmlspecialchars($user_name); ?></b></span>
                <a href="logout_client.php" class="btn-login btn-logout">Thoát</a>
            <?php else: ?>
                <a href="#" onclick="openLoginModal()" class="btn-login">Đăng Nhập</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="hero-banner">
        <div class="overlay"></div>
        <div class="hero-content">
            <h1>VIỆT NAM TRONG TẦM TAY</h1>
            <p>Khám phá vẻ đẹp bất tận cùng Vivu Vietnam</p>
            <a href="#tour-hot" class="btn-explore">Đặt Tour Ngay</a>
        </div>
    </div>

    <div id="tour-hot" class="container">
        <div class="section-header">
            <h2>TOUR DU LỊCH NỔI BẬT</h2>
            <div class="divider"></div>
        </div>

        <div class="tour-grid">
            <?php if (!empty($tours)): ?>
                <?php foreach ($tours as $tour): ?>
                    <div class="tour-card">
                        <div class="card-header">
                            <?php 
                                $hinh = !empty($tour['hinh_anh']) ? "uploads/".$tour['hinh_anh'] : "https://source.unsplash.com/random/400x300/?travel";
                            ?>
                            <img src="<?php echo $hinh; ?>">
                            <span class="badge-hot">HOT</span>
                        </div>
                        <div class="card-body">
                            <h3 class="tour-title"><?php echo htmlspecialchars($tour['TenTour']); ?></h3>
                            <div class="card-footer">
                                <div class="price"><?php echo number_format($tour['gia_ban'], 0, ',', '.'); ?> ₫</div>
                                <div class="card-actions">
                                    <a href="booking.php?id=<?php echo $tour['maTour']; ?>" class="btn-detail">Chi tiết</a>
                                    <button class="btn-book-now" 
                                        onclick="openBookingModal('<?php echo $tour['maTour']; ?>', '<?php echo htmlspecialchars($tour['TenTour']); ?>', <?php echo $tour['gia_ban']; ?>)">
                                        Đặt Ngay
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-data"><p>Chưa có tour nào.</p></div>
            <?php endif; ?>
        </div>
    </div>

    <div id="loginModal" class="modal-overlay">
        <div class="login-popup" style="background:white; padding:30px; border-radius:15px; width:350px; text-align:center;">
            <h2 style="color:#333;">Đăng Nhập</h2>
            <form action="login_client.php" method="POST">
                <input type="email" name="email" placeholder="Email" required style="width:100%; padding:10px; margin:10px 0; border:1px solid #ddd; border-radius:5px;">
                <input type="password" name="password" placeholder="Mật khẩu" required style="width:100%; padding:10px; margin:10px 0; border:1px solid #ddd; border-radius:5px;">
                <button type="submit" class="btn-login" style="width:100%; border:none; cursor:pointer;">Đăng Nhập</button>
            </form>
            <p style="margin-top:15px; font-size:0.9rem;">Chưa có tài khoản? <a href="register_client.php">Đăng ký</a></p>
            <p onclick="closeModal('loginModal')" style="cursor:pointer; color:#666; margin-top:10px;">Đóng</p>
        </div>
    </div>

    <div id="bookingModal" class="modal-overlay">
        <div class="booking-popup">
            <div class="booking-header">
                <h3>XÁC NHẬN THANH TOÁN</h3>
            </div>
            <div class="booking-body">
                <form action="confirm_booking.php" method="POST">
                    <input type="hidden" name="id" id="modal_tour_id">
                    
                    <input type="hidden" name="ma_khuyen_mai" id="hidden_ma_khuyen_mai" value="">

                    <div class="tour-summary">
                        <div class="tour-name" id="modal_tour_name">Tên Tour Loading...</div>
                        <div class="summary-row">
                            <span>Giá vé:</span>
                            <span id="modal_tour_price">0 VNĐ</span>
                        </div>
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

                    <div class="summary-row" style="color: green;">
                        <span>Được giảm:</span>
                        <span id="discount_amount">- 0 VNĐ</span>
                    </div>

                    <div class="total-row">
                        <span>TỔNG CỘNG:</span>
                        <span id="modal_tour_total">0 VNĐ</span>
                    </div>

                    <div class="payment-methods">
                        <label style="display:block; margin: 15px 0 10px; font-weight:600;">💳 Phương thức thanh toán:</label>
                        <label class="payment-option">
                            <input type="radio" name="payment" value="tien_mat" checked> Tiền mặt
                        </label>
                        <label class="payment-option">
                            <input type="radio" name="payment" value="chuyen_khoan"> Chuyển khoản
                        </label>
                    </div>

                    <button type="submit" class="btn-confirm">XÁC NHẬN ĐẶT VÉ</button>
                    <div class="btn-cancel" onclick="closeModal('bookingModal')">Hủy bỏ</div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let currentTourPrice = 0;

        function openLoginModal() { document.getElementById('loginModal').style.display = 'flex'; }
        
        function openBookingModal(id, name, price) {
            currentTourPrice = price;
            document.getElementById('modal_tour_id').value = id;
            document.getElementById('modal_tour_name').innerText = name;
            
            // Reset dropdown về mặc định
            document.getElementById('discount_select').value = "0";
            document.getElementById('hidden_ma_khuyen_mai').value = "";
            
            updateDisplay();
            document.getElementById('bookingModal').style.display = 'flex';
        }

        function closeModal(modalId) { document.getElementById(modalId).style.display = 'none'; }

        window.onclick = function(event) {
            if (event.target.classList.contains('modal-overlay')) {
                event.target.style.display = "none";
            }
        }

        function calculateTotal() { updateDisplay(); }

        function updateDisplay() {
            // Lấy thẻ select
            let selectBox = document.getElementById('discount_select');
            
            // Lấy option đang được chọn
            let selectedOption = selectBox.options[selectBox.selectedIndex];
            
            // Lấy ID mã giảm giá từ attribute data-id và gán vào input hidden
            let codeId = selectedOption.getAttribute('data-id');
            document.getElementById('hidden_ma_khuyen_mai').value = codeId;

            // Tính toán tiền
            let discountPercent = parseInt(selectBox.value); // Lấy value (là % giảm)
            let discountAmount = currentTourPrice * (discountPercent / 100);
            let totalAmount = currentTourPrice - discountAmount;
            
            // Format tiền tệ
            let fmt = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' });

            document.getElementById('modal_tour_price').innerText = fmt.format(currentTourPrice);
            document.getElementById('discount_amount').innerText = "- " + fmt.format(discountAmount);
            document.getElementById('modal_tour_total').innerText = fmt.format(totalAmount);
        }
    </script>

</body>
</html>