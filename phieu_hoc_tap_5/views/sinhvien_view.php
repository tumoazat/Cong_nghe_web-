<?php
// Tệp View - CHỈ chứa HTML và logic hiển thị
// KHÔNG chứa câu lệnh SQL
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>PHT Chương 5 - MVC</title>
    <style>
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px;
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 8px; 
            text-align: left;
        }
        th { 
            background-color: #f2f2f2; 
        }
        form {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        input[type="text"], input[type="email"] {
            padding: 8px;
            margin: 5px;
            width: 250px;
        }
        button {
            padding: 8px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h2>Thêm Sinh Viên Mới (Kiến trúc MVC)</h2>
    
    <form method="POST" action="index.php">
        <input type="text" name="ten_sinh_vien" placeholder="Tên sinh viên" required>
        <input type="email" name="email" placeholder="Email" required>
        <button type="submit">Thêm Sinh Viên</button>
    </form>

    <h2>Danh Sách Sinh Viên (Kiến trúc MVC)</h2>
    
    <table>
        <tr>
            <th>ID</th>
            <th>Tên Sinh Viên</th>
            <th>Email</th>
            <th>Ngày Tạo</th>
        </tr>
        
        <?php
        // Duyệt qua danh sách sinh viên được truyền từ Controller
        foreach ($danh_sach_sv as $sv) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($sv['id']) . "</td>";
            echo "<td>" . htmlspecialchars($sv['ten_sinh_vien']) . "</td>";
            echo "<td>" . htmlspecialchars($sv['email']) . "</td>";
            echo "<td>" . htmlspecialchars($sv['created_at'] ?? 'N/A') . "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>