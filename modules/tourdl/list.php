<?php
// modules/tourdl/list.php
// Core Product Tour - Dev: Khoa

$page_title = 'Danh sách Tour Du Lịch';
$danh_sach_tour = [];
try {
    $sql = "SELECT 
                tourdl.*, 
                loaitour.tenloai, 
                hdv.ho_ten AS ten_hdv,
                donvivanchuyen.ten_don_vi
            FROM tourdl
            LEFT JOIN loaitour ON tourdl.ma_loai = loaitour.maloai
            LEFT JOIN hdv ON tourdl.ma_hdv = hdv.ma_hdv
            LEFT JOIN donvivanchuyen ON tourdl.ma_don_vi = donvivanchuyen.ma_don_vi
            ORDER BY tourdl.maTour DESC";
            
    $stmt = $pdo->query($sql);
    $danh_sach_tour = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Lỗi truy vấn: " . $e->getMessage();
}
?>

<div class="container">
    <a href="index.php?module=tourdl&action=add" class="btn-add">Thêm Tour Mới</a>
    <h3>QUẢN LÝ DANH SÁCH TOUR DU LỊCH</h3>

    <table border="1" style="width:100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr>
                <th>Mã Tour</th>
                <th>Tên Tour</th>
                <th>Ngày Khởi Hành</th>
                <th>Giá Bán (VNĐ)</th>
                <th>Số Chỗ</th>
                <th>Thời Gian</th>
                <th>Mô Tả Hành Trình</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($danh_sach_tour)): ?>
                <tr>
                    <td colspan="8">Chưa có tour nào.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($danh_sach_tour as $tour): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($tour['maTour']); ?></td>
                        <td><?php echo htmlspecialchars($tour['TenTour']); ?></td>
                        <td><?php echo htmlspecialchars($tour['ngay_khoi_hanh']); ?></td>
                        
                        <td style="color: #d9534f; font-weight: bold;">
                            <?php echo number_format($tour['gia_ban']); ?> VNĐ
                        </td>

                        <td><?php echo htmlspecialchars($tour['so_cho_toi_da']); ?></td>
                        <td><?php echo htmlspecialchars($tour['thoi_gian']); ?></td>
                        <td><?php echo htmlspecialchars($tour['mo_ta_hanh_trinh']); ?></td>
                        
                        <td style="white-space: nowrap;">
                            <a href="index.php?module=tourdl&action=edit&id=<?php echo $tour['maTour']; ?>">Sửa</a> |
                            <a href="index.php?module=tourdl&action=delete&id=<?php echo $tour['maTour']; ?>" onclick="return confirm('Bạn có chắc muốn xóa?');">Xóa</a> |
                            <a href="index.php?module=tourdl&action=assign&id=<?php echo $tour['maTour']; ?>" style="color: blue; font-weight: bold;">Gán Điểm Đến</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>