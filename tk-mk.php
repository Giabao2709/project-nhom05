
<?php
// Tool tạo mật khẩu nhanh
$password = 'nhom05'; 
$hash = password_hash($password, PASSWORD_DEFAULT);
?>
<div style="font-family: sans-serif; padding: 20px; border: 1px solid #ccc; max-width: 600px; margin: 50px auto;">
    <h3 style="margin-top: 0;">🔑 Công cụ mã hóa mật khẩu (BCRYPT)</h3>
    <p>Mật khẩu gốc: <code><?php echo $password; ?></code></p>
    <p>Chuỗi mã hóa (Copy vào Database):</p>
    <input type="text" value="<?php echo $hash; ?>" style="width: 100%; padding: 10px; font-size: 1.1em;">
</div>