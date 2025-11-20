<?php
// modules/diemden/list.php

// 1. Đặt tiêu đề cho trang
$page_title = 'Quản lý Booking (Đơn Đặt Tour)';

// 2. Lấy dữ liệu (DÙNG BIẾN $pdo TỪ index.php)
$danh_sach_diemden = [];
try {
    $sql = "SELECT * FROM diemden ORDER BY ma_diem_den DESC";
    $stmt = $pdo->query($sql);
    $danh_sach_diemden = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Lỗi truy vấn: " . $e->getMessage(); 
}
?>

<div class="container">
    <a href="index.php?module=diemden&action=add" class="btn-add">Thêm Điểm Đến Mới</a>
    
    <h3>Danh sách các Điểm Đến</h3>

    <table border="1" style="width:100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr>
                <th>Mã Điểm Đến</th>
                <th>Tên Địa Điểm Du Lịch</th>
                <th>Địa Chỉ</th>
                <th>Mô Tả</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($danh_sach_diemden)): ?>
                <tr>
                    <td colspan="5">Chưa có điểm đến nào.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($danh_sach_diemden as $dd): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($dd['ma_diem_den']); ?></td>
                        <td><?php echo htmlspecialchars($dd['ten_dia_danh']); ?></td>
                        <td><?php echo htmlspecialchars($dd['dia_chi']); ?></td>
                        <td><?php echo htmlspecialchars($dd['mo_ta_chi_tiet']); ?></td>
                        <td>
                            <a href="index.php?module=diemden&action=edit&id=<?php echo $dd['ma_diem_den']; ?>">Sửa</a>
                            <a href="index.php?module=diemden&action=delete&id=<?php echo $dd['ma_diem_den']; ?>" onclick="return confirm('Bạn có chắc muốn xóa?');">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>