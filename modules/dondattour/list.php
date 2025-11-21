<?php
/**
 * MODULE: QUẢN LÝ ĐƠN ĐẶT TOUR (Booking)
 * --------------------------------------
 * Chức năng: Quản lý thông tin đặt tour của khách hàng.
 * Liên kết bảng: dondattour -> khachhang, tourdl, khuyenmai.
 * Reviewed by: Trương Đức Thành (SV3)
 */
// modules/dondattour/list.php

$page_title = 'Quản lý Đơn Đặt Tour';
$ds_dondattour = [];
try {
    // Câu SQL JOIN 4 bảng
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
    echo "Lỗi truy vấn: " . $e->getMessage(); 
}
?>

<div class="container">
    <a href="index.php?module=dondattour&action=add" class="btn-add">Thêm Đơn Đặt Tour Mới</a>
    <h3>Danh sách Đơn Đặt Tour</h3>

    <table border="1" style="width:100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr>
                <th>Mã Vé (Booking ID)</th>
                <th>Khách Hàng</th>
                <th>Tour Đã Đặt</th>
                <th>Ngày Đặt</th>
                <th>Trạng Thái Đơn</th>
                <th>Khuyến mãi</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($ds_dondattour)): ?>
                <tr>
                    <td colspan="7">Chưa có đơn đặt tour nào.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($ds_dondattour as $dd): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($dd['ma_booking']); ?></td>
                        <td><?php echo htmlspecialchars($dd['ten_khach_hang']); ?></td>
                        <td><?php echo htmlspecialchars($dd['ten_tour']); ?></td>
                        <td><?php echo htmlspecialchars($dd['ngay_dat']); ?></td>
                        <td><?php echo htmlspecialchars($dd['trang_thai_don_hang']); ?></td>
                        <td><?php echo htmlspecialchars($dd['ten_khuyen_mai']); ?></td>
                        <td>
                            <a href="index.php?module=dondattour&action=edit&id=<?php echo $dd['ma_booking']; ?>">Sửa</a>
                            <a href="index.php?module=dondattour&action=delete&id=<?php echo $dd['ma_booking']; ?>" onclick="return confirm('Bạn có chắc muốn xóa?');">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>