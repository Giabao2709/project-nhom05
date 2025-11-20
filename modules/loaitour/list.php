<?php
// modules/loaitour/list.php

// 1. Đặt tiêu đề cho trang
$page_title = 'Quản lý Loại Tour';

// 2. Lấy dữ liệu (DÙNG BIẾN $pdo TỪ index.php)
$danh_sach_loaitour = [];
try {
    $sql = "SELECT * FROM loaitour";
    $stmt = $pdo->query($sql);
    $danh_sach_loaitour = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Lỗi truy vấn: " . $e->getMessage(); 
}
?>

<div class="container">
    <a href="index.php?module=loaitour&action=add" class="btn-add">Thêm Loại Tour Mới</a>
    
    <h3>Danh sách các Loại Tour</h3>

    <table border="1" style="width:100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr>
                <th>Mã Loại</th>
                <th>Tên Loại Tour</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($danh_sach_loaitour)): ?>
                <tr>
                    <td colspan="3">Chưa có loại tour nào.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($danh_sach_loaitour as $lt): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($lt['maloai']); ?></td>
                        <td><?php echo htmlspecialchars($lt['tenloai']); ?></td>
                        <td>
                            <a href="index.php?module=loaitour&action=edit&id=<?php echo $lt['maloai']; ?>">Sửa</a>
                            <a href="index.php?module=loaitour&action=delete&id=<?php echo $lt['maloai']; ?>" onclick="return confirm('Bạn có chắc muốn xóa?');">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>