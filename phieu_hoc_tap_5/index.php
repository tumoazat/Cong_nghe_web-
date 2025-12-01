<?php
// Tệp Controller - "Não" của ứng dụng

// Import Model
require_once 'models/SinhVienModel.php';

// === THIẾT LẬP KẾT NỐI PDO ===
$host = '127.0.0.1';
$dbname = 'cse485_web';
$username = 'root';
$password = '';
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Kết nối thất bại: " . $e->getMessage());
}
// === KẾT THÚC KẾT NỐI PDO ===

// === LOGIC CỦA CONTROLLER ===

// Kiểm tra xem có hành động POST (thêm sinh viên) không
if (isset($_POST['ten_sinh_vien']) && isset($_POST['email'])) {
    // Lấy dữ liệu từ form
    $ten = $_POST['ten_sinh_vien'];
    $email = $_POST['email'];
    
    // Gọi hàm addSinhVien() từ Model
    addSinhVien($pdo, $ten, $email);
    
    // Chuyển hướng về index.php để làm mới trang
    header('Location: index.php');
    exit;
}

// Lấy danh sách tất cả sinh viên từ Model
$danh_sach_sv = getAllSinhVien($pdo);

// Import View để hiển thị
// View sẽ tự động "nhìn thấy" biến $danh_sach_sv
include 'views/sinhvien_view.php';
?>
```

---

