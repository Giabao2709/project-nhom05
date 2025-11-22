<?php
// modules/dondattour/list.php
// Booking Management - Dev: Thanh (UI Update)

$page_title = 'Quản lý Đơn Đặt Tour';
$ds_dondattour = [];
try {
    $sql = "SELECT 
                dondattour.*, 
                khachhang.ho_ten AS ten_khach_hang, 
                tourdl.TenTour AS ten_tour,
                khuyenmai.gia_tri AS ten_khuyen_mai
            FROM dondattour
            LEFT JOIN khachhang ON dondattour.ma_khach_hang = khachhang.ma_khach_hang
            LEFT JOIN tourdl ON dondattour.ma_tour = tourdl.maTour
            LEFT JOIN khuyenmai ON dondattour.ma_khuyen_mai = khuyenmai.ma_khuyen_mai
            ORDER BY dondattour.ma_booking DESC";
    $stmt = $pdo->query($sql);
    $ds_dondattour = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Lỗi: " . $e->getMessage(); 
}
?>

<div class="container">
    <a href="index.php?module=dondattour&action=add" class="btn-add">Tạo Đơn Mới</a>
    <h3>DANH SÁCH ĐƠN ĐẶT TOUR (BOOKING)</h3>

    <table border="1" style="width:100%; border-collapse: collapse; text-align: left;">
        <thead style="background-color: #343a40; color: white;">
            <tr>
                <th>Mã Đơn</th>
                <th>Khách Hàng</th>
                <th>Tour</th>
                <th>Ngày Đặt</th>
                <th>Trạng Thái</th>
                <th>Khuyến Mãi</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($ds_dondattour)): ?>
                <tr><td colspan="7" style="text-align: center; padding: 20px;">Chưa có dữ liệu.</td></tr>
            <?php else: ?>
                <?php foreach ($ds_dondattour as $dd): ?>
                    <tr>
                        <td style="font-weight: bold;">#<?php echo htmlspecialchars($dd['ma_booking']); ?></td>
                        <td><?php echo htmlspecialchars($dd['ten_khach_hang']); ?></td>
                        <td><?php echo htmlspecialchars($dd['ten_tour']); ?></td>
                        <td><?php echo date("d/m/Y H:i", strtotime($dd['ngay_dat'])); ?></td>
                        
                        <td>
                            <?php 
                                $status = $dd['trang_thai_don_hang'];
                                $color = ($status == 'Mới') ? 'blue' : (($status == 'Đã hủy') ? 'red' : 'green');
                                echo "<span style='color: $color; font-weight: bold;'>$status</span>";
                            ?>
                        </td>
                        
                        <td><?php echo htmlspecialchars($dd['ten_khuyen_mai'] ?? 'Không'); ?></td>
                        <td>
                            <a href="index.php?module=dondattour&action=edit&id=<?php echo $dd['ma_booking']; ?>">Sửa</a> |
                            <a href="index.php?module=dondattour&action=delete&id=<?php echo $dd['ma_booking']; ?>" onclick="return confirm('Xóa đơn này?');">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>