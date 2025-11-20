<?php
// Customer Management - Dev: Bao
// modules/khachhang/list.php

$page_title = 'Quản lý Khách Hàng';
$ds_khachhang = [];
try {
    $sql = "SELECT * FROM khachhang ORDER BY ma_khach_hang DESC";
    $stmt = $pdo->query($sql);
    $ds_khachhang = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Lỗi truy vấn: " . $e->getMessage(); 
}
?>

<div class="container">
    <a href="index.php?module=khachhang&action=add" class="btn-add">Thêm Khách Hàng Mới</a>
    <h3>Danh sách Khách Hàng</h3>

    <table border="1" style="width:100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr>
                <th>Mã KH</th>
                <th>Họ Tên</th>
                <th>Số Điện Thoại</th>
                <th>Email</th>
                <th>Địa Chỉ</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($ds_khachhang)): ?>
                <tr>
                    <td colspan="6">Chưa có khách hàng nào.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($ds_khachhang as $kh): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($kh['ma_khach_hang']); ?></td>
                        <td><?php echo htmlspecialchars($kh['ho_ten']); ?></td>
                        <td><?php echo htmlspecialchars($kh['so_dien_thoai']); ?></td>
                        <td><?php echo htmlspecialchars($kh['email']); ?></td>
                        <td><?php echo htmlspecialchars($kh['dia_chi']); ?></td>
                        <td>
                            <a href="index.php?module=khachhang&action=edit&id=<?php echo $kh['ma_khach_hang']; ?>">Sửa</a>
                            <a href="index.php?module=khachhang&action=delete&id=<?php echo $kh['ma_khach_hang']; ?>" onclick="return confirm('Bạn có chắc muốn xóa?');">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>