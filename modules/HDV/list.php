<?php
// modules/hdv/list.php

// 1. Đặt tiêu đề cho trang
$page_title = 'Quản lý Hướng Dẫn Viên';

// 2. Lấy dữ liệu
$danh_sach_hdv = [];
try {
    $sql = "SELECT * FROM hdv ORDER BY ma_hdv DESC";
    $stmt = $pdo->query($sql);
    $danh_sach_hdv = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Lỗi truy vấn: " . $e->getMessage(); 
}
?>

<div class="container">
   <a href="index.php?module=hdv&action=add" class="btn-add" style="background-color: #28a745; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px;">+ Thêm HDV Mới</a>
    
    <h3>Danh sách Hướng Dẫn Viên</h3>

    <table border="1" style="width:100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr>
                <th>Mã HDV</th>
                <th>Họ Tên</th>
                <th>Số Điện Thoại</th>
                <th>Email</th>
                <th>Chuyên Môn</th>
                <th>Ngôn Ngữ</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($danh_sach_hdv)): ?>
                <tr>
                    <td colspan="7">Chưa có hướng dẫn viên nào.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($danh_sach_hdv as $hdv): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($hdv['ma_hdv']); ?></td>
                        <td><?php echo htmlspecialchars($hdv['ho_ten']); ?></td>
                        <td><?php echo htmlspecialchars($hdv['so_dien_thoai']); ?></td>
                        <td><?php echo htmlspecialchars($hdv['email']); ?></td>
                        <td><?php echo htmlspecialchars($hdv['chuyen_mon']); ?></td>
                        <td><?php echo htmlspecialchars($hdv['ngon_ngu_thong_thao']); ?></td>
                        <td>
                            <a href="index.php?module=hdv&action=edit&id=<?php echo $hdv['ma_hdv']; ?>">Sửa</a>
                            <a href="index.php?module=hdv&action=delete&id=<?php echo $hdv['ma_hdv']; ?>" onclick="return confirm('Bạn có chắc muốn xóa?');">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>