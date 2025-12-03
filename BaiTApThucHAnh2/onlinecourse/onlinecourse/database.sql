-- Tạo cơ sở dữ liệu
CREATE DATABASE IF NOT EXISTS onlinecourse CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE onlinecourse;

-- Bảng users - Quản lý người dùng
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    fullname VARCHAR(255) NOT NULL,
    role INT NOT NULL DEFAULT 0 COMMENT '0: học viên, 1: giảng viên, 2: quản trị viên',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email),
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng categories - Quản lý danh mục khóa học
CREATE TABLE IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng courses - Quản lý khóa học
CREATE TABLE IF NOT EXISTS courses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    instructor_id INT NOT NULL,
    category_id INT NOT NULL,
    price DECIMAL(10,2) DEFAULT 0.00,
    duration_weeks INT DEFAULT 0,
    level VARCHAR(50) DEFAULT 'Beginner' COMMENT 'Beginner, Intermediate, Advanced',
    image VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (instructor_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    INDEX idx_instructor (instructor_id),
    INDEX idx_category (category_id),
    INDEX idx_level (level)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng enrollments - Quản lý đăng ký khóa học
CREATE TABLE IF NOT EXISTS enrollments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    course_id INT NOT NULL,
    student_id INT NOT NULL,
    enrolled_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(50) DEFAULT 'active' COMMENT 'active, completed, dropped',
    progress INT DEFAULT 0 COMMENT 'Phần trăm hoàn thành (0-100)',
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_enrollment (course_id, student_id),
    INDEX idx_course (course_id),
    INDEX idx_student (student_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng lessons - Quản lý bài học
CREATE TABLE IF NOT EXISTS lessons (
    id INT PRIMARY KEY AUTO_INCREMENT,
    course_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content LONGTEXT,
    video_url VARCHAR(255),
    `order` INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    INDEX idx_course (course_id),
    INDEX idx_order (`order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng materials - Quản lý tài liệu học tập
CREATE TABLE IF NOT EXISTS materials (
    id INT PRIMARY KEY AUTO_INCREMENT,
    lesson_id INT NOT NULL,
    filename VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_type VARCHAR(50) COMMENT 'pdf, doc, ppt, v.v.',
    uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE,
    INDEX idx_lesson (lesson_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dữ liệu mẫu

-- Thêm quản trị viên mặc định (username: admin, password: admin123)
INSERT INTO users (username, email, password, fullname, role) VALUES
('admin', 'admin@onlinecourse.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Quản trị viên', 2);

-- Thêm giảng viên mẫu (username: giaovien1, password: giaovien123)
INSERT INTO users (username, email, password, fullname, role) VALUES
('giaovien1', 'giaovien1@onlinecourse.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Nguyễn Văn A', 1);

-- Thêm học viên mẫu (username: hocvien1, password: hocvien123)
INSERT INTO users (username, email, password, fullname, role) VALUES
('hocvien1', 'hocvien1@onlinecourse.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Trần Thị B', 0);

-- Thêm danh mục khóa học
INSERT INTO categories (name, description) VALUES
('Lập trình Web', 'Các khóa học về phát triển web'),
('Lập trình Mobile', 'Các khóa học về phát triển ứng dụng di động'),
('Cơ sở dữ liệu', 'Các khóa học về quản lý cơ sở dữ liệu'),
('Khoa học dữ liệu', 'Các khóa học về phân tích và khoa học dữ liệu'),
('An ninh mạng', 'Các khóa học về bảo mật và an ninh thông tin');

-- Thêm khóa học mẫu
INSERT INTO courses (title, description, instructor_id, category_id, price, duration_weeks, level, image) VALUES
('PHP & MySQL từ cơ bản đến nâng cao', 'Khóa học toàn diện về PHP và MySQL cho người mới bắt đầu', 2, 1, 999000, 8, 'Beginner', 'php-mysql.jpg'),
('Lập trình Android với Kotlin', 'Học cách xây dựng ứng dụng Android hiện đại với Kotlin', 2, 2, 1499000, 12, 'Intermediate', 'android-kotlin.jpg');

-- Thêm bài học mẫu
INSERT INTO lessons (course_id, title, content, video_url, `order`) VALUES
(1, 'Giới thiệu về PHP', 'Trong bài học này, chúng ta sẽ tìm hiểu về PHP và cách cài đặt môi trường phát triển.', 'https://youtube.com/example1', 1),
(1, 'Biến và kiểu dữ liệu trong PHP', 'Tìm hiểu về các biến và kiểu dữ liệu cơ bản trong PHP.', 'https://youtube.com/example2', 2);
