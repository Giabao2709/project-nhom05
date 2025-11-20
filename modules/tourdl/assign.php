<?php
// modules/tourdl/assign.php

$page_title = 'Gán Điểm Đến cho Tour';
$error_message = null;
$tour = null;
$all_destinations = [];
$assigned_destination_ids = []; // Mảng lưu ID các điểm đến ĐÃ ĐƯỢC GÁN

// 1. Lấy ID của tour cần gán từ URL
if (isset($_GET['id'])) {
    $maTour = $_GET['id'];
} else {
    header("Location: index.php?module=tourdl&action=list");
    exit();
}

// --- Xử lý POST (Khi người dùng nhấn nút "Lưu") ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Lấy danh sách các ID điểm đến được check
    $selected_destinations = $_POST['diemden_ids'] ?? []; // Mảng các ID

    try {
        // Bắt đầu Transaction (để đảm bảo an toàn)
        $pdo->beginTransaction();

        // Bước 1: Xóa tất cả liên kết cũ của tour này
        $sql_delete = "DELETE FROM tour_diemden WHERE ma_tour = ?";
        $stmt_delete = $pdo->prepare($sql_delete);
        $stmt_delete->execute([$maTour]);

        // Bước 2: Thêm lại các liên kết mới (cho những ô được check)
        if (!empty($selected_destinations)) {
            $sql_insert = "INSERT INTO tour_diemden (ma_tour, ma_diem_den) VALUES (?, ?)";
            $stmt_insert = $pdo->prepare($sql_insert);
            
            foreach ($selected_destinations as $ma_diem_den) {
                $stmt_insert->execute([$maTour, $ma_diem_den]);
            }
        }
        
        // Hoàn tất, xác nhận thay đổi
        $pdo->commit();

        // Chuyển hướng về trang danh sách
        header("Location: index.php?module=tourdl&action=list");
        exit();

    } catch (PDOException $e) {
        // Nếu có lỗi, hủy bỏ mọi thay đổi
        $pdo->rollBack();
        $error_message = "Lỗi khi cập nhật: " . $e->getMessage();
    }
}


// --- Lấy dữ liệu (Khi tải trang) ---
try {
    // 1. Lấy thông tin tour để biết tên
    $stmt_tour = $pdo->prepare("SELECT TenTour FROM tourdl WHERE maTour = ?");
    $stmt_tour->execute([$maTour]);
    $tour = $stmt_tour->fetch(PDO::FETCH_ASSOC);

    if (!$tour) {
        $error_message = "Không tìm thấy tour!";
    }

    // 2. Lấy TẤT CẢ điểm đến có trong CSDL
    $stmt_all_dest = $pdo->query("SELECT * FROM diemden");
    $all_destinations = $stmt_all_dest->fetchAll(PDO::FETCH_ASSOC);

    // 3. Lấy các điểm đến ĐÃ ĐƯỢC GÁN cho tour này
    $stmt_assigned = $pdo->prepare("SELECT ma_diem_den FROM tour_diemden WHERE ma_tour = ?");
    $stmt_assigned->execute([$maTour]);
    // Lấy về một mảng ID đơn giản (ví dụ: [1, 3, 5])
    $assigned_destination_ids = $stmt_assigned->fetchAll(PDO::FETCH_COLUMN, 0); 

} catch (PDOException $e) {
    $error_message = "Lỗi khi tải dữ liệu: " . $e->getMessage();
}
?>

<div class="container">
    <?php if (isset($error_message)): ?>
        <div class="error" style="color: red;"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <?php if ($tour): ?>
    
    <h3>Gán Điểm Đến cho Tour: <?php echo htmlspecialchars($tour['TenTour']); ?></h3>
    
    <form action="index.php?module=tourdl&action=assign&id=<?php echo $maTour; ?>" method="POST">
        
        <p>Chọn các điểm đến sẽ có trong tour này:</p>
        
        <div style="line-height: 2;">
            <?php if (empty($all_destinations)): ?>
                <p>Bạn chưa thêm điểm đến nào. Vui lòng thêm điểm đến trước.</p>
            <?php else: ?>
                <?php foreach ($all_destinations as $dd): ?>
                    <label>
                        <input type="checkbox" 
                               name="diemden_ids[]" 
                               value="<?php echo $dd['ma_diem_den']; ?>"
                               <?php 
                                   // Nếu ID điểm đến này có trong mảng "đã gán", thì check
                                   if (in_array($dd['ma_diem_den'], $assigned_destination_ids)) {
                                       echo 'checked';
                                   } 
                               ?>>
                        <?php echo htmlspecialchars($dd['ten_dia_danh']); ?> 
                        (<?php echo htmlspecialchars($dd['dia_chi']); ?>)
                    </label>
                    <br>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <br>
        <div>
            <button type="submit">Lưu Thay Đổi</button>
            <a href="index.php?module=tourdl&action=list">Quay lại danh sách</a>
        </div>
    </form>
    
    <?php endif; // Đóng if ($tour) ?>
</div>