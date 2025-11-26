-- ============================================================
-- TẠO CƠ SỞ DỮ LIỆU CHO SQL SERVER
-- ============================================================

-- Tạo database
CREATE DATABASE congngheweb;
GO

-- Chọn database để làm việc
USE congngheweb;
GO

-- ============================================================
-- BẢNG 1: DANH SÁCH HOA (Bài tập 1)
-- ============================================================
CREATE TABLE flowers (
    id INT IDENTITY(1,1) PRIMARY KEY,   -- IDENTITY = tự tăng trong SQL Server
    name NVARCHAR(255) NOT NULL,        -- NVARCHAR để hỗ trợ tiếng Việt
    description NVARCHAR(MAX),          -- NVARCHAR(MAX) thay cho TEXT
    image NVARCHAR(255),
    created_at DATETIME DEFAULT GETDATE()  -- GETDATE() thay cho CURRENT_TIMESTAMP
);
GO

-- Thêm dữ liệu mẫu cho bảng flowers
INSERT INTO flowers (name, description, image) VALUES
(N'Hoa Đỗ Quyên', N'Hoa đỗ quyên nở rực rỡ với những chùm hoa dày và nổi bật. ', N'doquyen.jpg'),
(N'Hoa Hải Đường', N'Hoa hải đường thanh nhã, thường có màu trắng, hồng hoặc kem.', N'haiduong.jpg'),
(N'Hoa Mai', N'Hoa mai vàng là biểu tượng đặc trưng của mùa xuân phương Nam.', N'mai.jpg'),
(N'Hoa Tường Vy', N'Hoa tường vy có cánh nhỏ, mọc thành chùm xinh xắn.', N'tuongvy.jpg');
GO

-- ============================================================
-- BẢNG 2: CÂU HỎI QUIZ (Bài tập 2)
-- ============================================================
CREATE TABLE quiz_questions (
    id INT IDENTITY(1,1) PRIMARY KEY,
    question NVARCHAR(MAX) NOT NULL,
    option_a NVARCHAR(500),
    option_b NVARCHAR(500),
    option_c NVARCHAR(500),
    option_d NVARCHAR(500),
    option_e NVARCHAR(500),
    answer NVARCHAR(20) NOT NULL,
    created_at DATETIME DEFAULT GETDATE()
);
GO

-- Thêm dữ liệu mẫu cho quiz
INSERT INTO quiz_questions (question, option_a, option_b, option_c, option_d, answer) VALUES
(N'Thành phần nào sau đây KHÔNG phải là một thành phần giao diện người dùng (UI) trong Android?', N'TextView', N'Button', N'Service', N'ImageView', N'C'),
(N'Layout nào thường được sử dụng để sắp xếp các thành phần UI theo chiều dọc hoặc chiều ngang?', N'RelativeLayout', N'LinearLayout', N'FrameLayout', N'ConstraintLayout', N'B'),
(N'Intent trong Android được sử dụng để làm gì?', N'Hiển thị thông báo cho người dùng', N'Lưu trữ dữ liệu', N'Khởi chạy Activity', N'Xử lý sự kiện chạm', N'C'),
(N'Vòng đời của một Activity bắt đầu bằng phương thức nào?', N'onStart()', N'onResume()', N'onCreate()', N'onPause()', N'C'),
(N'Để xử lý sự kiện click chuột cho một Button, bạn cần sử dụng phương thức nào?', N'onClick()', N'onTouch()', N'onLongClick()', N'onFocusChange()', N'A');
GO

-- ============================================================
-- BẢNG 3: DANH SÁCH SINH VIÊN (Bài tập 3)
-- ============================================================
CREATE TABLE students (
    id INT IDENTITY(1,1) PRIMARY KEY,
    username NVARCHAR(50) NOT NULL,
    password NVARCHAR(100),
    lastname NVARCHAR(100),
    firstname NVARCHAR(100),
    city NVARCHAR(100),
    email NVARCHAR(150),
    course1 NVARCHAR(100),
    created_at DATETIME DEFAULT GETDATE()
);
GO

-- Thêm dữ liệu mẫu
INSERT INTO students (username, password, lastname, firstname, city, email, course1) VALUES
(N'2351160500', N'cse@485A', N'Đinh Thị Lan', N'Anh', N'65HTTT', N'2351160500@e.tlu.edu.vn', N'CSE485. 202401'),
(N'2351160501', N'cse@485A', N'Đỗ Quang Nam', N'Anh', N'65HTTT', N'2351160501@e.tlu.edu.vn', N'CSE485.202401'),
(N'2351160502', N'cse@485A', N'Nguyễn Thái', N'Anh', N'65HTTT', N'2351160502@e.tlu.edu.vn', N'CSE485.202401'),
(N'2351160503', N'cse@485A', N'Tạ Thị Ngọc', N'Anh', N'65HTTT', N'2351160503@e.tlu.edu.vn', N'CSE485.202401'),
(N'2351160504', N'cse@485A', N'Trần Mai Ngọc', N'Anh', N'65HTTT', N'2351160504@e.tlu.edu.vn', N'CSE485.202401');
GO

-- ============================================================
-- KIỂM TRA DỮ LIỆU
-- ============================================================
SELECT * FROM flowers;
SELECT * FROM quiz_questions;
SELECT * FROM students;
GO