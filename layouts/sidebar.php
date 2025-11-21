<?php
// layouts/sidebar.php
?>
<aside class="sidebar">
    <div style="padding: 15px; border-bottom: 1px solid #4b545c; margin-bottom: 10px;">
        <h3 style="margin:0; color: #fff;">MENU QUẢN LÝ</h3>
    </div>
    
    <nav>
        <ul>
            <li><a href="index.php">🏠 Trang Chủ (Dashboard)</a></li>
            
            <li style="color: #aaa; font-size: 0.85em; padding: 10px 15px 5px;">NGHIỆP VỤ</li>
            <li><a href="index.php?module=tourdl&action=list">✈️ Quản lý Tour</a></li>
            <li><a href="index.php?module=dondattour&action=list">🛒 Đơn Đặt Tour</a></li>
            <li><a href="index.php?module=khachhang&action=list">👥 Khách Hàng</a></li>
            <li><a href="index.php?module=thanhtoan&action=list">💸 Thanh Toán</a></li>

            <li style="color: #aaa; font-size: 0.85em; padding: 10px 15px 5px;">DANH MỤC</li>
            <li><a href="index.php?module=diemden&action=list">📍 Điểm Đến</a></li>
            <li><a href="index.php?module=loaitour&action=list">🏷️ Loại Tour</a></li>
            <li><a href="index.php?module=hdv&action=list">🎤 Hướng Dẫn Viên</a></li>
            <li><a href="index.php?module=donvivanchuyen&action=list">🚌 ĐV Vận Chuyển</a></li>
            <li><a href="index.php?module=khuyenmai&action=list">🎁 Khuyến Mãi</a></li>
            
            <li style="margin-top: 20px; border-top: 1px solid #4b545c; padding-top: 10px;">
                <a href="logout.php" style="color: #ff6b6b;">🚪 Đăng xuất</a>
            </li>
        </ul>
    </nav>
</aside>