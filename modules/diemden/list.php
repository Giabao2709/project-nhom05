<?php
// modules/diemden/list.php
<<<<<<< HEAD

// 1. Đặt tiêu đề cho trang
$page_title = 'Quản lý Booking (Đơn Đặt Tour)';

// 2. Lấy dữ liệu (DÙNG BIẾN $pdo TỪ index.php)
=======
// Destination Module - Dev: Thanh

$page_title = 'Quản lý Điểm Đến';
>>>>>>> sv2-thanh
$danh_sach_diemden = [];
try {
    $sql = "SELECT * FROM diemden ORDER BY ma_diem_den DESC";
    $stmt = $pdo->query($sql);
    $danh_sach_diemden = $stmt->fetchAll(PDO::FETCH_ASSOC);
<<<<<<< HEAD

} catch (PDOException $e) {
    echo "Lỗi truy vấn: " . $e->getMessage(); 
=======
} catch (PDOException $e) {
    echo "Lỗi: " . $e->getMessage(); 
>>>>>>> sv2-thanh
}
?>

<div class="container">
<<<<<<< HEAD
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
=======
    <a href="index.php?module=diemden&action=add" class="btn-add">Thêm Địa Điểm</a>
    <h3>DANH SÁCH ĐIỂM ĐẾN DU LỊCH</h3>

    <table border="1" style="width:100%; border-collapse: collapse; text-align: left;">
        <thead style="background-color: #17a2b8; color: white;">
            <tr>
                <th>ID</th>
                <th>Tên Địa Điểm</th>
                <th>Địa Chỉ Cụ Thể</th>
                <th>Mô Tả</th>
                <th>Thao tác</th>
>>>>>>> sv2-thanh
            </tr>
        </thead>
        <tbody>
            <?php if (empty($danh_sach_diemden)): ?>
<<<<<<< HEAD
                <tr>
                    <td colspan="5">Chưa có điểm đến nào.</td>
                </tr>
=======
                <tr><td colspan="5">Trống.</td></tr>
>>>>>>> sv2-thanh
            <?php else: ?>
                <?php foreach ($danh_sach_diemden as $dd): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($dd['ma_diem_den']); ?></td>
<<<<<<< HEAD
                        <td><?php echo htmlspecialchars($dd['ten_dia_danh']); ?></td>
                        <td><?php echo htmlspecialchars($dd['dia_chi']); ?></td>
                        <td><?php echo htmlspecialchars($dd['mo_ta_chi_tiet']); ?></td>
                        <td>
                            <a href="index.php?module=diemden&action=edit&id=<?php echo $dd['ma_diem_den']; ?>">Sửa</a>
                            <a href="index.php?module=diemden&action=delete&id=<?php echo $dd['ma_diem_den']; ?>" onclick="return confirm('Bạn có chắc muốn xóa?');">Xóa</a>
=======
                        <td style="font-weight: 500;"><?php echo htmlspecialchars($dd['ten_dia_danh']); ?></td>
                        <td><?php echo htmlspecialchars($dd['dia_chi']); ?></td>
                        <td><?php echo htmlspecialchars($dd['mo_ta_chi_tiet']); ?></td>
                        <td>
                            <a href="index.php?module=diemden&action=edit&id=<?php echo $dd['ma_diem_den']; ?>">Sửa</a> |
                            <a href="index.php?module=diemden&action=delete&id=<?php echo $dd['ma_diem_den']; ?>" onclick="return confirm('Xóa?');">Xóa</a>
>>>>>>> sv2-thanh
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>