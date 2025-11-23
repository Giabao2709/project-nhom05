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

<style>
    

    /* CSS cho nút thêm mới đẹp hơn */
    .btn-add-hdv {
        background: linear-gradient(45deg, #28a745, #218838);
        color: white !important;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 50px;
        font-weight: bold;
        box-shadow: 0 4px 10px rgba(40, 167, 69, 0.3);
        display: inline-block;
        margin-bottom: 20px;
        transition: 0.3s;
    }
    .btn-add-hdv:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(40, 167, 69, 0.4);
    }
</style>

<div class="container">
    <a href="index.php?module=hdv&action=add" class="btn-add-hdv">+ Thêm HDV</a>
    
    <h3>DANH SÁCH HƯỚNG DẪN VIÊN</h3>

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
                    <td colspan="7" style="text-align:center; padding: 20px;">Chưa có hướng dẫn viên nào.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($danh_sach_hdv as $hdv): ?>
                    <tr>
                        <td style="font-weight:bold;">#<?php echo htmlspecialchars($hdv['ma_hdv']); ?></td>
                        <td><?php echo htmlspecialchars($hdv['ho_ten']); ?></td>
                        <td><?php echo htmlspecialchars($hdv['so_dien_thoai']); ?></td>
                        <td><?php echo htmlspecialchars($hdv['email']); ?></td>
                        <td><?php echo htmlspecialchars($hdv['chuyen_mon']); ?></td>
                        <td><?php echo htmlspecialchars($hdv['ngon_ngu_thong_thao']); ?></td>
                        <td>
                            <a href="index.php?module=hdv&action=edit&id=<?php echo $hdv['ma_hdv']; ?>">Sửa</a> | 
                            <a href="index.php?module=hdv&action=delete&id=<?php echo $hdv['ma_hdv']; ?>" onclick="return confirm('Bạn có chắc muốn xóa HDV này?');">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>