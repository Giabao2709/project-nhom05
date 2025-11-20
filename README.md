README - ĐỒ ÁN LẬP TRÌNH ỨNG DỤNG WEB
====================================

NHÓM: 05 
HỌC PHẦN: Lập Trình Ứng Dụng Web
Giảng viên: Trần Công Thanh

----------------------------------------------------------------------
I. THÔNG TIN THÀNH VIÊN
----------------------------------------------------------------------
- 2474802010196 – Trần Nhật Khoa (Trưởng nhóm) – SourceCode + Documents/BaoCao.docx
- 2474802010361 – Trương Đức Thành – SourceCode + Database/database.sql
- 2474802010040 – Trần Gia Bảo – SourceCode + Slides/SlideDoAn.pptx
- 2474802010380 – Nguyễn Hữu Thuận – SourceCode + README.txt

----------------------------------------------------------------------
II. MÔ TẢ ĐỀ TÀI
----------------------------------------------------------------------
Tên đề tài: Quản lý Tour du lịch (QuanLyTour)

Mô tả ngắn:
Website xây dựng hệ thống quản lý nghiệp vụ du lịch sử dụng ngôn ngữ PHP (PDO) và cơ sở dữ liệu MySQL. Hệ thống được tổ chức theo mô hình Module hóa giúp dễ dàng quản lý và mở rộng.

Các chức năng chính đã hoàn thiện:
1. Hệ thống: Đăng nhập, Đăng xuất, Dashboard thống kê tổng quan.
2. Nghiệp vụ Tour: Quản lý Tour (Thêm/Sửa/Xóa/Xem), Quản lý Điểm đến, Gán điểm đến cho Tour.
3. Danh mục: Quản lý Loại Tour, Hướng dẫn viên, Đơn vị vận chuyển.
4. Kinh doanh: Quản lý Khách hàng, Quản lý Đơn đặt Tour (Booking), Quản lý Thanh toán, Quản lý Khuyến mãi.

----------------------------------------------------------------------
III. CÁCH CÀI ĐẶT & CHẠY DỰ ÁN (LOCALHOST - XAMPP)
----------------------------------------------------------------------
1. Cài đặt XAMPP (Apache & MySQL).
2. Copy thư mục dự án tên là "project-nhom05" vào đường dẫn:
   C:/xampp/htdocs/project-nhom05/

3. Cấu hình Cơ sở dữ liệu:
   - Khởi động Apache và MySQL trong XAMPP Control Panel.
   - Truy cập: http://localhost/phpmyadmin
   - Tạo database mới tên là: quanlytour
   - Nhấn vào database vừa tạo, chọn tab "Nhập" (Import).
   - Chọn file: Database/quanlytour.sql (nằm trong thư mục bài nộp) -> Nhấn "Thực hiện".

4. Kiểm tra kết nối (Nếu cần thiết):
   - Mở file: config/db.php
   - Đảm bảo biến $dbname = 'quanlytour';

5. Chạy dự án:
   - Mở trình duyệt và truy cập: http://localhost/project-nhom05/index.php

----------------------------------------------------------------------
IV. TÀI KHOẢN ĐĂNG NHẬP (QUẢN TRỊ VIÊN)
----------------------------------------------------------------------
Tài khoản chính (Đã cập nhật trong CSDL):
- Tên đăng nhập: projectnhom05
- Mật khẩu:      nhom05

----------------------------------------------------------------------
V. LINK TRIỂN KHAI ONLINE (FREE HOST)
----------------------------------------------------------------------
URL: http://project-nhom05.infinityfree.me

(*) Ghi chú: 
Web đã được deploy thành công lên InfinityFree. Tuy nhiên, do sử dụng host miễn phí, hệ thống DNS có thể mất từ 1-24h để cập nhật tên miền. 
Nếu Thầy không truy cập được link trên, vui lòng chấm bài dựa trên SourceCode chạy Localhost hoặc Video Demo đính kèm.

----------------------------------------------------------------------
VI. LINK GITHUB (BẮT BUỘC)
----------------------------------------------------------------------
Repo chính (public): 
https://github.com/Giabao2709/project-nhom05

Nhánh từng sinh viên (Minh chứng đóng góp):
- SV1 (Thuận): https://github.com/Giabao2709/project-nhom05/tree/sv1-thuan
- SV2 (Thành): https://github.com/Giabao2709/project-nhom05/tree/sv2-thanh
- SV3 (Bảo):   https://github.com/Giabao2709/project-nhom05/tree/sv3-bao
- SV4 (Khoa):  https://github.com/Giabao2709/project-nhom05/tree/sv4-khoa

Ghi chú:
=> Mỗi thành viên đã có log commit rõ ràng xuyên suốt quá trình làm bài.
=> Đã hoàn thành phân chia công việc và merge code về nhánh chính.

----------------------------------------------------------------------
VII. CẤU TRÚC THƯ MỤC BÀI NỘP
----------------------------------------------------------------------
/SourceCode
    (Chứa thư mục project-nhom05 gồm toàn bộ mã nguồn)
    
/Database
    quanlytour.sql (File SQL để import vào phpMyAdmin)

/Documents
    BaoCao_DoAn_WebApp.pdf (Báo cáo chi tiết)

/Slides
    SlideThuyetTrinh.pptx

/README.txt

----------------------------------------------------------------------
