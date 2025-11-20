<?php
// modules/khuyenmai/list.php

$page_title = 'Quản lý Khuyến mãi';
$ds_khuyenmai = [];
try {
    $sql = "SELECT * FROM khuyenmai ORDER BY ma_khuyen_mai DESC";
    $stmt = $pdo->query($sql);
    $ds_khuyenmai = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Lỗi truy vấn: " . $e->getMessage(); 
}
?>

<div class="container">
    <a href="index.php?module=khuyenmai&action=add" class="btn-add">Thêm Khuyến mãi Mới</a>
    <h3>Danh sách Khuyến mãi</h3>

    <table border="1" style="width:100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr>
                <th>Mã KM</th>
                <th>Giá trị (Mô tả)</th>
                <th>Ngày Bắt Đầu</th>
                <th>Ngày Kết Thúc</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($ds_khuyenmai)): ?>
                <tr>
                    <td colspan="5">Chưa có khuyến mãi nào.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($ds_khuyenmai as $km): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($km['ma_khuyen_mai']); ?></td>
                        <td><?php echo htmlspecialchars($km['gia_tri']); ?></td>
                        <td><?php echo htmlspecialchars($km['ngay_bat_dau']); ?></td>
                        <td><?php echo htmlspecialchars($km['ngay_ket_thuc']); ?></td>
                        <td>
                            <a href="index.php?module=khuyenmai&action=edit&id=<?php echo $km['ma_khuyen_mai']; ?>">Sửa</a>
                            <a href="index.php?module=khuyenmai&action=delete&id=<?php echo $km['ma_khuyen_mai']; ?>" onclick="return confirm('Bạn có chắc muốn xóa?');">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>