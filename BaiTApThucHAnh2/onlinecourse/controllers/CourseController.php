<?php
require_once 'config/Database.php';
require_once 'models/Course.php';
require_once 'models/Category.php';
require_once 'models/Enrollment.php';
require_once 'models/Lesson.php';

/**
 * CourseController - Quản lý khóa học
 */
class CourseController {
    private $db;
    private $course;
    private $category;
    private $enrollment;
    private $lesson;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->course = new Course($this->db);
        $this->category = new Category($this->db);
        $this->enrollment = new Enrollment($this->db);
        $this->lesson = new Lesson($this->db);
        
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Hiển thị danh sách khóa học (trang chủ)
     */
    public function danhSach() {
        $category_id = isset($_GET['category']) ? intval($_GET['category']) : null;
        $level = isset($_GET['level']) ? $_GET['level'] : null;
        $search = isset($_GET['search']) ? trim($_GET['search']) : null;
        
        // Lấy danh sách khóa học
        $courses = $this->course->getAll($category_id, $level, $search, 'approved');
        
        // Lấy danh sách danh mục
        $categories = $this->category->layTatCa();
        
        include 'views/courses/index.php';
    }
    
    /**
     * Hiển thị chi tiết khóa học
     */
    public function chiTiet() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if($id <= 0) {
            $_SESSION['error'] = "Khóa học không tồn tại!";
            header("Location: index.php?controller=course&action=list");
            return;
        }
        
        // Lấy thông tin khóa học
        $course = $this->course->getById($id);
        
        if(!$course) {
            $_SESSION['error'] = "Khóa học không tồn tại!";
            header("Location: index.php?controller=course&action=list");
            return;
        }
        
        // Lấy danh sách bài học
        $lessons = $this->lesson->layTheoKhoaHoc($id);
        
        // Kiểm tra đã đăng ký chưa
        $is_enrolled = false;
        if(isset($_SESSION['user_id'])) {
            $is_enrolled = $this->enrollment->isEnrolled($id, $_SESSION['user_id']);
        }
        
        include 'views/courses/detail.php';
    }
    
    /**
     * Tìm kiếm khóa học
     */
    public function timKiem() {
        $search = isset($_GET['q']) ? trim($_GET['q']) : '';
        $category_id = isset($_GET['category']) ? intval($_GET['category']) : null;
        $level = isset($_GET['level']) ? $_GET['level'] : null;
        
        $courses = $this->course->getAll($category_id, $level, $search, 'approved');
        $categories = $this->category->layTatCa();
        
        include 'views/courses/search.php';
    }
    
    /**
     * Tạo khóa học mới (Giảng viên)
     */
    public function tao() {
        // Kiểm tra quyền
        if(!isset($_SESSION['user_id']) || $_SESSION['role'] < 1) {
            $_SESSION['error'] = "Bạn không có quyền tạo khóa học!";
            header("Location: index.php");
            return;
        }
        
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->xuLyTao();
        } else {
            $categories = $this->category->layTatCa();
            include 'views/instructor/course/create.php';
        }
    }
    
    /**
     * Xử lý tạo khóa học
     */
    private function xuLyTao() {
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $category_id = intval($_POST['category_id']);
        $price = floatval($_POST['price']);
        $duration_weeks = intval($_POST['duration_weeks']);
        $level = $_POST['level'];
        
        // Validate
        $errors = [];
        
        if(empty($title)) {
            $errors[] = "Vui lòng nhập tên khóa học!";
        }
        
        if($category_id <= 0) {
            $errors[] = "Vui lòng chọn danh mục!";
        }
        
        if(!in_array($level, ['Beginner', 'Intermediate', 'Advanced'])) {
            $errors[] = "Cấp độ không hợp lệ!";
        }
        
        // Xử lý upload ảnh
        $image = 'default-course.jpg';
        if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $upload_result = $this->uploadAnhKhoaHoc($_FILES['image']);
            if($upload_result['success']) {
                $image = $upload_result['filename'];
            } else {
                $errors[] = $upload_result['message'];
            }
        }
        
        if(!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            header("Location: index.php?controller=course&action=create");
            return;
        }
        
        // Tạo khóa học
        $this->course->title = $title;
        $this->course->description = $description;
        $this->course->instructor_id = $_SESSION['user_id'];
        $this->course->category_id = $category_id;
        $this->course->price = $price;
        $this->course->duration_weeks = $duration_weeks;
        $this->course->level = $level;
        $this->course->image = $image;
        $this->course->status = $_SESSION['role'] >= 2 ? 'approved' : 'pending'; // Admin tự duyệt
        
        if($this->course->create()) {
            $_SESSION['success'] = "Tạo khóa học thành công!";
            header("Location: index.php?controller=instructor&action=courses");
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra, vui lòng thử lại!";
            header("Location: index.php?controller=course&action=create");
        }
    }
    
    /**
     * Chỉnh sửa khóa học
     */
    public function chinhSua() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if($id <= 0) {
            $_SESSION['error'] = "Khóa học không tồn tại!";
            header("Location: index.php?controller=instructor&action=courses");
            return;
        }
        
        $course = $this->course->getById($id);
        
        // Kiểm tra quyền
        if(!$course || ($course['instructor_id'] != $_SESSION['user_id'] && $_SESSION['role'] < 2)) {
            $_SESSION['error'] = "Bạn không có quyền chỉnh sửa khóa học này!";
            header("Location: index.php?controller=instructor&action=courses");
            return;
        }
        
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->xuLyChinhSua($id);
        } else {
            $categories = $this->category->layTatCa();
            include 'views/instructor/course/edit.php';
        }
    }
    
    /**
     * Xử lý chỉnh sửa khóa học
     */
    private function xuLyChinhSua($id) {
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $category_id = intval($_POST['category_id']);
        $price = floatval($_POST['price']);
        $duration_weeks = intval($_POST['duration_weeks']);
        $level = $_POST['level'];
        
        $course = $this->course->getById($id);
        $image = $course['image'];
        
        // Xử lý upload ảnh mới
        if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $upload_result = $this->uploadAnhKhoaHoc($_FILES['image']);
            if($upload_result['success']) {
                // Xóa ảnh cũ
                if($image != 'default-course.jpg' && file_exists('assets/uploads/courses/' . $image)) {
                    unlink('assets/uploads/courses/' . $image);
                }
                $image = $upload_result['filename'];
            }
        }
        
        // Cập nhật
        $this->course->id = $id;
        $this->course->title = $title;
        $this->course->description = $description;
        $this->course->category_id = $category_id;
        $this->course->price = $price;
        $this->course->duration_weeks = $duration_weeks;
        $this->course->level = $level;
        $this->course->image = $image;
        
        if($this->course->update()) {
            $_SESSION['success'] = "Cập nhật khóa học thành công!";
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra, vui lòng thử lại!";
        }
        
        header("Location: index.php?controller=course&action=edit&id=" . $id);
    }
    
    /**
     * Xóa khóa học
     */
    public function xoa() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        $course = $this->course->getById($id);
        
        // Kiểm tra quyền
        if(!$course || ($course['instructor_id'] != $_SESSION['user_id'] && $_SESSION['role'] < 2)) {
            $_SESSION['error'] = "Bạn không có quyền xóa khóa học này!";
            header("Location: index.php?controller=instructor&action=courses");
            return;
        }
        
        if($this->course->delete($id)) {
            // Xóa ảnh
            if($course['image'] != 'default-course.jpg' && file_exists('assets/uploads/courses/' . $course['image'])) {
                unlink('assets/uploads/courses/' . $course['image']);
            }
            $_SESSION['success'] = "Xóa khóa học thành công!";
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra, vui lòng thử lại!";
        }
        
        header("Location: index.php?controller=instructor&action=courses");
    }
    
    /**
     * Upload ảnh khóa học
     */
    private function uploadAnhKhoaHoc($file) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        $max_size = 5 * 1024 * 1024; // 5MB
        
        if(!in_array($file['type'], $allowed_types)) {
            return ['success' => false, 'message' => 'Chỉ chấp nhận file ảnh (JPG, PNG, GIF)!'];
        }
        
        if($file['size'] > $max_size) {
            return ['success' => false, 'message' => 'Kích thước file tối đa 5MB!'];
        }
        
        $upload_dir = 'assets/uploads/courses/';
        if(!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'course_' . time() . '_' . uniqid() . '.' . $ext;
        $filepath = $upload_dir . $filename;
        
        if(move_uploaded_file($file['tmp_name'], $filepath)) {
            return ['success' => true, 'filename' => $filename];
        }
        
        return ['success' => false, 'message' => 'Upload file thất bại!'];
    }
}
?>