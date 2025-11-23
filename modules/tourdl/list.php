<?php
// modules/tourdl/list.php
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

    <div style="overflow-x: auto;">
        <table border="1" style="width:100%; border-collapse: collapse; text-align: left; min-width: 1000px;">
            <thead style="background-color: #0669cdff; color: white;">
                <tr>
                    <th>Mã Tour</th>
                    <th>Tên Tour</th>
                    <th>Khởi Hành</th>
                    <th>Giá Bán</th>
                    <th>Số Chỗ</th>
                    <th>Thời Gian</th>
                    
                    <th>Loại Tour</th>
                    <th>Hướng Dẫn Viên</th>
                    <th>Vận Chuyển</th>
                    <th style="width: 180px;">Hành động</th>
                </tr>
            </thead>

            <tbody>
                <?php if (empty($danh_sach_tour)): ?>
                    <tr><td colspan="10" style="text-align:center; padding: 20px;">Chưa có tour nào.</td></tr>

                <?php else: ?>
                    <?php foreach ($danh_sach_tour as $tour): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($tour['maTour']); ?></td>
                            <td style="font-weight: bold;"><?php echo htmlspecialchars($tour['TenTour']); ?></td>

                            <td>
                                <?php echo date("d/m/Y", strtotime($tour['ngay_khoi_hanh'])); ?>
                            </td>

                            <td style="color: #dc3545; font-weight: bold;">
                                <?php echo number_format($tour['gia_ban']); ?> VNĐ
                            </td>

                            <td style="text-align: center;"><?php echo htmlspecialchars($tour['so_cho_toi_da']); ?></td>
                            <td><?php echo htmlspecialchars($tour['thoi_gian']); ?></td>

                            <td><?php echo htmlspecialchars($tour['tenloai'] ?? 'Chưa rõ'); ?></td>
                            <td><?php echo htmlspecialchars($tour['ten_hdv'] ?? 'Chưa xếp'); ?></td>
                            <td><?php echo htmlspecialchars($tour['ten_don_vi'] ?? 'Tự túc'); ?></td>
                            <td style="white-space: nowrap;">
                                <a href="index.php?module=tourdl&action=edit&id=<?php echo $tour['maTour']; ?>" 
                                   style="color: orange; font-weight: bold; margin-right: 5px;">Sửa</a> |
                                
                                <a href="index.php?module=tourdl&action=delete&id=<?php echo $tour['maTour']; ?>" 
                                   onclick="return confirm('Xóa tour này?');"
                                   style="color: red; font-weight: bold; margin: 0 5px;">Xóa</a> |
                                
                                <a href="index.php?module=tourdl&action=assign&id=<?php echo $tour['maTour']; ?>" 
                                   style="background-color: #dc3545; color: white; padding: 3px 8px; border-radius: 4px; text-decoration: none; font-size: 12px;">Gán Điểm Đến</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>