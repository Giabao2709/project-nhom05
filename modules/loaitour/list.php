<?php
// modules/loaitour/list.php
// Module Category - Dev: Khoa (UI Update)

$page_title = 'Quản lý Loại Tour';

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
    <a href="index.php?module=loaitour&action=add" class="btn-add">Thêm Loại Tour</a>

    <h3>DANH MỤC LOẠI TOUR</h3>

    <table border="1" style="width:100%; border-collapse: collapse; text-align: left;">
        <thead style="background-color: #f8f9fa;">
            <tr>
                <th>Mã Loại</th>
                <th>Tên Loại Tour</th>
                <th>Ghi chú</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($danh_sach_loaitour)): ?>
                <tr>
                    <td colspan="3">Trống.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($danh_sach_loaitour as $lt): ?>
                    <tr>
                        <td>
                            <span style="background: #17a2b8; color: #fff; padding: 3px 8px; border-radius: 10px; font-weight: bold; font-size: 0.9em;">
                                <?php echo strtoupper(htmlspecialchars($lt['maloai'])); ?>
                            </span>
                        </td>

                        <td><?php echo htmlspecialchars($lt['tenloai']); ?></td>

                        <td style="color: #888;">(System Defined)</td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
