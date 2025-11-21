<?php
// modules/thanhtoan/list.php
// Module Payment - Dev: Khoa (UI Update: Status Badge)

$page_title = 'Quản lý Thanh Toán';
$ds_thanhtoan = [];
try {
    $sql = "SELECT 
                thanhtoan.*, 
                dondattour.ma_booking,
                khachhang.ho_ten AS ten_khach_hang
            FROM thanhtoan
            LEFT JOIN dondattour ON thanhtoan.ma_booking = dondattour.ma_booking
            LEFT JOIN khachhang ON dondattour.ma_khach_hang = khachhang.ma_khach_hang
            ORDER BY thanhtoan.ma_thanh_toan DESC";
    $stmt = $pdo->query($sql);
    $ds_thanhtoan = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Lỗi truy vấn: " . $e->getMessage(); 
}
?>

<div class="container">
    <a href="index.php?module=thanhtoan&action=add" class="btn-add">Thêm Thanh Toán</a>
    <h3>DANH SÁCH GIAO DỊCH</h3>

    <table border="1" style="width:100%; border-collapse: collapse; text-align: left;">
        <thead style="background-color: #f8f9fa;">
            <tr>
                <th>Mã TT</th>
                <th>Khách Hàng</th>
                <th>Số Tiền</th>
                <th>Ngày TT</th>
                <th>Phương Thức</th>
                <th>Trạng Thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($ds_thanhtoan)): ?>
                <tr><td colspan="7">Chưa có giao dịch nào.</td></tr>
            <?php else: ?>
                <?php foreach ($ds_thanhtoan as $tt): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($tt['ma_thanh_toan']); ?></td>
                        <td><?php echo htmlspecialchars($tt['ten_khach_hang']); ?></td>
                        <td><?php echo number_format($tt['so_tien']); ?> VNĐ</td>
                        <td><?php echo htmlspecialchars($tt['ngay_thanh_toan']); ?></td>
                        <td><?php echo htmlspecialchars($tt['phuong_thuc_thanh_toan']); ?></td>
                        
                        <td style="font-weight: bold; color: <?php echo ($tt['trang_thai'] == 'Đã thanh toán') ? 'green' : '#e67e22'; ?>;">
    <?php echo htmlspecialchars($tt['trang_thai']); ?>
</td>

                        <td>
                            <a href="index.php?module=thanhtoan&action=edit&id=<?php echo $tt['ma_thanh_toan']; ?>">Sửa</a> |
                            <a href="index.php?module=thanhtoan&action=delete&id=<?php echo $tt['ma_thanh_toan']; ?>" onclick="return confirm('Xóa?');">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>