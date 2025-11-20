<?php
// modules/donvivanchuyen/list.php

$page_title = 'Quản lý Đơn Vị Vận Chuyển';
$ds_dvvc = [];
try {
    $sql = "SELECT * FROM donvivanchuyen ORDER BY ma_don_vi DESC";
    $stmt = $pdo->query($sql);
    $ds_dvvc = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Lỗi truy vấn: " . $e->getMessage(); 
}
?>

<div class="container">
    <a href="index.php?module=donvivanchuyen&action=add" class="btn-add">Thêm ĐV Vận Chuyển Mới</a>
    <h3>Danh sách Đơn Vị Vận Chuyển</h3>

    <table border="1" style="width:100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr>
                <th>Mã ĐV</th>
                <th>Tên Đơn Vị</th>
                <th>Loại Phương Tiện</th>
                <th>Thông Tin Liên Lạc</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($ds_dvvc)): ?>
                <tr>
                    <td colspan="5">Chưa có đơn vị vận chuyển nào.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($ds_dvvc as $dv): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($dv['ma_don_vi']); ?></td>
                        <td><?php echo htmlspecialchars($dv['ten_don_vi']); ?></td>
                        <td><?php echo htmlspecialchars($dv['loai_phuong_tien']); ?></td>
                        <td><?php echo htmlspecialchars($dv['thong_tin_lien_lac']); ?></td>
                        <td>
                            <a href="index.php?module=donvivanchuyen&action=edit&id=<?php echo $dv['ma_don_vi']; ?>">Sửa</a>
                            <a href="index.php?module=donvivanchuyen&action=delete&id=<?php echo $dv['ma_don_vi']; ?>" onclick="return confirm('Bạn có chắc muốn xóa?');">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>