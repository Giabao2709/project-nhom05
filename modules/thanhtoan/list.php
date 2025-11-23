<?php
// modules/thanhtoan/list.php
// Module Payment - Dev: Khoa

$page_title = 'Quản lý Thanh Toán';
$ds_thanhtoan = [];

try {
    // JOIN với dondattour và khachhang để lấy thông tin
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

<style>
    /* Ép buộc tiêu đề bảng hiển thị rõ nét */
    table thead th, 
    .table thead th {
        background-color: #c42c9bff !important; /* Giữ màu tím đặc trưng của module này */
        color: white !important;            
        font-weight: 800 !important;          
        border-bottom: 2px solid #b1b1b1 !important;
        text-shadow: none !important;
    }
    
    /* Làm rõ nội dung trong bảng */
    table tbody tr td {
        color: #333 !important;
        vertical-align: middle !important;
    }
</style>

<div class="container">
    <a href="index.php?module=thanhtoan&action=add" class="btn-add">Thêm Thanh Toán</a>
    <h3>DANH SÁCH GIAO DỊCH</h3>

    <table border="1" style="width:100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr>
                <th>Mã TT</th>
                <th>Mã Booking</th>
                <th>Khách Hàng</th>
                <th>Số Tiền</th>
                <th>Ngày Thanh Toán</th>
                <th>Phương Thức</th>
                <th>Trạng Thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($ds_thanhtoan)): ?>
                <tr><td colspan="8" style="text-align:center; padding:20px;">Chưa có giao dịch nào.</td></tr>
            <?php else: ?>
                <?php foreach ($ds_thanhtoan as $tt): ?>
                    <tr>
                        <td style="font-weight:bold;">#<?php echo htmlspecialchars($tt['ma_thanh_toan']); ?></td>
                        <td>#<?php echo htmlspecialchars($tt['ma_booking']); ?></td>
                        <td><?php echo htmlspecialchars($tt['ten_khach_hang']); ?></td>

                        <td style="font-weight:bold; color:#d9534f;">
                            <?php echo number_format($tt['so_tien']); ?> VNĐ
                        </td>

                        <td>
                            <?php 
                                $ngay = $tt['ngay_thanh_toan'];
                                // Kiểm tra nếu ngày rỗng hoặc là ngày mặc định của MySQL
                                if (empty($ngay) || $ngay == '0000-00-00 00:00:00' || $ngay == '0000-00-00') {
                                    echo "<span style='color:#999; font-style:italic;'>Chưa ghi nhận</span>";
                                } else {
                                    // Định dạng lại ngày giờ cho đẹp (Ngày/Tháng/Năm Giờ:Phút)
                                    echo date("d/m/Y H:i", strtotime($ngay));
                                }
                            ?>
                        </td>

                        <td><?php echo htmlspecialchars($tt['phuong_thuc_thanh_toan']); ?></td>

                        <td style="font-weight: bold; color: 
                            <?php echo ($tt['trang_thai'] == 'Đã thanh toán') ? 'green' : '#e67e22'; ?>;">
                            <?php echo htmlspecialchars($tt['trang_thai']); ?>
                        </td>

                        <td>
                            <a href="index.php?module=thanhtoan&action=edit&id=<?php echo $tt['ma_thanh_toan']; ?>" class="btn-edit">Sửa</a> |
                            <a href="index.php?module=thanhtoan&action=delete&id=<?php echo $tt['ma_thanh_toan']; ?>" onclick="return confirm('Xóa giao dịch này?');" class="btn-delete">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>