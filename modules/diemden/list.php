<?php
// modules/diemden/list.php
// Destination Module - Dev: Thanh

$page_title = 'Quản lý Điểm Đến';
$danh_sach_diemden = [];
try {
    $sql = "SELECT * FROM diemden ORDER BY ma_diem_den DESC";
    $stmt = $pdo->query($sql);
    $danh_sach_diemden = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Lỗi: " . $e->getMessage(); 
}
?>

<div class="container">
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
            </tr>
        </thead>
        <tbody>
            <?php if (empty($danh_sach_diemden)): ?>
                <tr><td colspan="5">Trống.</td></tr>
            <?php else: ?>
                <?php foreach ($danh_sach_diemden as $dd): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($dd['ma_diem_den']); ?></td>
                        <td style="font-weight: 500;"><?php echo htmlspecialchars($dd['ten_dia_danh']); ?></td>
                        <td><?php echo htmlspecialchars($dd['dia_chi']); ?></td>
                        <td><?php echo htmlspecialchars($dd['mo_ta_chi_tiet']); ?></td>
                        <td>
                            <a href="index.php?module=diemden&action=edit&id=<?php echo $dd['ma_diem_den']; ?>">Sửa</a> |
                            <a href="index.php?module=diemden&action=delete&id=<?php echo $dd['ma_diem_den']; ?>" onclick="return confirm('Xóa?');">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>