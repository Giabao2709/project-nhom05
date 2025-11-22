<?php
// modules/loaitour/list.php
<<<<<<< HEAD
// Module Category - Dev: Khoa (UI Update: Badge Style)

$page_title = 'Quản lý Loại Tour';
$ds_loai = [];
try {
    $ds_loai = $pdo->query("SELECT * FROM loaitour")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Lỗi: " . $e->getMessage(); 
=======
// Danh muc Tour - Dev: Khoa

$page_title = 'Quản lý Loại Tour';

$danh_sach_loaitour = [];
try {
    $sql = "SELECT * FROM loaitour";
    $stmt = $pdo->query($sql);
    $danh_sach_loaitour = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Lỗi truy vấn: " . $e->getMessage(); 
>>>>>>> sv2-thanh
}
?>

<div class="container">
<<<<<<< HEAD
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
            <?php if (empty($ds_loai)): ?>
                <tr><td colspan="3">Trống.</td></tr>
            <?php else: ?>
                <?php foreach ($ds_loai as $lt): ?>
                    <tr>
                        <td>
                            <span style="background: #17a2b8; color: #fff; padding: 3px 8px; border-radius: 10px; font-weight: bold; font-size: 0.9em;">
                                <?php echo strtoupper(htmlspecialchars($lt['maloai'])); ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($lt['tenloai']); ?></td>
                        <td style="color: #888;">(System Defined)</td>
=======
    <a href="index.php?module=loaitour&action=add" class="btn-add">Thêm Loại Tour Mới</a>
    
    <h3>DANH SÁCH CÁC LOẠI TOUR</h3>

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
                        <td>
                            <span style="background-color: #17a2b8; color: white; padding: 3px 8px; border-radius: 4px; font-weight: bold; font-size: 0.9em;">
                                <?php echo strtoupper(htmlspecialchars($lt['maloai'])); ?>
                            </span>
                        </td>

                        <td><?php echo htmlspecialchars($lt['tenloai']); ?></td>
                        <td>
                            <span style="color: #aaa;">(Mặc định)</span>
                        </td>
>>>>>>> sv2-thanh
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>