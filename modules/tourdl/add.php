<?php
// modules/tourdl/add.php (Code ĐẦY ĐỦ - ĐÃ SỬA LỖI AUTO_INCREMENT)

$page_title = 'Thêm Tour Mới';

// --- Lấy dữ liệu cho các Dropdown ---
try {
    $stmt_loaitour = $pdo->query("SELECT * FROM loaitour");
    $loaitours = $stmt_loaitour->fetchAll(PDO::FETCH_ASSOC);

    $stmt_hdv = $pdo->query("SELECT * FROM hdv");
    $hdvs = $stmt_hdv->fetchAll(PDO::FETCH_ASSOC);

    $stmt_dvvc = $pdo->query("SELECT * FROM donvivanchuyen");
    $dvvcs = $stmt_dvvc->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Lỗi khi tải dữ liệu dropdown: " . $e->getMessage();
}

// --- Xử lý POST (khi người dùng nhấn nút "Lưu") ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Lấy tất cả dữ liệu từ form
    $TenTour = $_POST['TenTour'];
    $ngay_khoi_hanh = $_POST['ngay_khoi_hanh'];
    $gia_ban = $_POST['gia_ban'];
    $so_cho_toi_da = $_POST['so_cho_toi_da'];
    $thoi_gian = $_POST['thoi_gian'];
    $mo_ta_hanh_trinh = $_POST['mo_ta_hanh_trinh'];
    $ma_loai = $_POST['ma_loai'];
    $ma_hdv = $_POST['ma_hdv'];
    $ma_don_vi = $_POST['ma_don_vi'];
    
    try {
        // Câu SQL INSERT (ĐÃ XÓA MaTour - VÌ NÓ TỰ ĐỘNG TĂNG)
        $sql = "INSERT INTO `tourdl` (
                    `TenTour`, `ngay_khoi_hanh`, `gia_ban`, `so_cho_toi_da`, 
                    `thoi_gian`, `mo_ta_hanh_trinh`, `ma_loai`, `ma_hdv`, `ma_don_vi`
                ) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $TenTour, $ngay_khoi_hanh, $gia_ban, $so_cho_toi_da, 
            $thoi_gian, $mo_ta_hanh_trinh, $ma_loai, $ma_hdv, $ma_don_vi
        ]);

        header("Location: index.php?module=tourdl&action=list");
        exit(); 

    } catch (PDOException $e) {
        $error_message = "Lỗi khi thêm: " . $e->getMessage();
    }
}
?>

<div class="container">
    <?php if (isset($error_message)): ?>
        <div class="error" style="color: red; background: #fee; border: 1px solid red; padding: 10px; margin-bottom: 15px;">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>
    
    <form action="index.php?module=tourdl&action=add" method="POST" style="line-height: 2;">
        
        <div>
            <label for="TenTour">Tên Tour:</label><br>
            <input type="text" id="TenTour" name="TenTour" required style="width: 400px;">
        </div>
        <div>
            <label for="ngay_khoi_hanh">Ngày Khởi Hành:</label><br>
            <input type="date" id="ngay_khoi_hanh" name="ngay_khoi_hanh">
        </div>
        <div>
            <label for="gia_ban">Giá Bán:</label><br>
            <input type="number" id="gia_ban" name="gia_ban">
        </div>
        <div>
            <label for="so_cho_toi_da">Số Chỗ Tối Đa:</label><br>
            <input type="number" id="so_cho_toi_da" name="so_cho_toi_da">
        </div>
        <div>
            <label for="thoi_gian">Thời gian:</label><br>
            <input type="text" id="thoi_gian" name="thoi_gian">
        </div>
        <div>
            <label for="mo_ta_hanh_trinh">Mô tả hành trình:</label><br>
            <textarea id="mo_ta_hanh_trinh" name="mo_ta_hanh_trinh" rows="5" style="width: 400px;"></textarea>
        </div>
        
        <hr> <div>
            <label for="ma_loai">Loại Tour:</label><br>
            <select id="ma_loai" name="ma_loai" required>
                <option value="">-- Chọn Loại Tour --</option>
                <?php foreach ($loaitours as $loai): ?>
                    <option value="<?php echo htmlspecialchars($loai['maloai']); ?>">
                        <?php echo htmlspecialchars($loai['tenloai']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div>
            <label for="ma_hdv">Hướng Dẫn Viên:</label><br>
            <select id="ma_hdv" name="ma_hdv" required>
                <option value="">-- Chọn Hướng Dẫn Viên --</option>
                <?php foreach ($hdvs as $hdv): ?>
                    <option value="<?php echo htmlspecialchars($hdv['ma_hdv']); ?>">
                        <?php echo htmlspecialchars($hdv['ho_ten']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="ma_don_vi">ĐV Vận Chuyển:</label><br>
            <select id="ma_don_vi" name="ma_don_vi" required>
                <option value="">-- Chọn ĐV Vận Chuyển --</option>
                <?php foreach ($dvvcs as $dv): ?>
                    <option value="<?php echo htmlspecialchars($dv['ma_don_vi']); ?>">
                        <?php echo htmlspecialchars($dv['ten_don_vi']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <br>
        <div>
            <button type="submit">Lưu Tour</button>
            <a href="index.php?module=tourdl&action=list">Hủy</a>
        </div>
    </form>
</div>