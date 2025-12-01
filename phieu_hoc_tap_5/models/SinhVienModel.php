<?php
// Tệp Model - Chứa tất cả logic truy vấn CSDL

// Hàm lấy tất cả sinh viên
function getAllSinhVien($pdo) {
    $sql = "SELECT * FROM sinhvien ORDER BY ngay_tao DESC";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Hàm thêm sinh viên mới
function addSinhVien($pdo, $ten, $email) {
    $sql = "INSERT INTO sinhvien (ten_sinh_vien, email) VALUES (?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$ten, $email]);
}
?>