<?php
require_once 'config/Database.php';
require_once 'models/Course.php';
require_once 'models/Category.php';
require_once 'models/User.php';

/**
 * HomeController - Trang chủ
 */
class HomeController {
    private $db;
    private $course;
    private $category;
    private $user;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->course = new Course($this->db);
        $this->category = new Category($this->db);
        $this->user = new User($this->db);
        
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Trang chủ
     */
    public function index() {
        // Lấy khóa học phổ biến
        $popular_courses = $this->course->getPopular(6);
        
        // Lấy khóa học mới nhất
        $latest_courses = $this->course->getAll(null, null, null, 'approved');
        $latest_courses = array_slice($latest_courses, 0, 6);
        
        // Lấy danh mục
        $categories = $this->category->layTatCa();
        
        // Thống kê
        $total_courses = $this->course->countAll('approved');
        $total_students = $this->user->countByRole(0);
        $total_instructors = $this->user->countByRole(1);
        
        include 'views/home/index.php';
    }
    
    /**
     * Trang giới thiệu
     */
    public function about() {
        include 'views/home/about.php';
    }
    
    /**
     * Trang liên hệ
     */
    public function contact() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->xuLyLienHe();
        } else {
            include 'views/home/contact.php';
        }
    }
    
    /**
     * Xử lý form liên hệ
     */
    private function xuLyLienHe() {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $subject = trim($_POST['subject']);
        $message = trim($_POST['message']);
        
        // Validate
        $errors = [];
        
        if(empty($name)) {
            $errors[] = "Vui lòng nhập tên!";
        }
        
        if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email không hợp lệ!";
        }
        
        if(empty($subject)) {
            $errors[] = "Vui lòng nhập tiêu đề!";
        }
        
        if(empty($message)) {
            $errors[] = "Vui lòng nhập nội dung!";
        }
        
        if(!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            header("Location: index.php?controller=home&action=contact");
            return;
        }
        
        // Gửi email (tùy chỉnh theo cấu hình mail server)
        // Ở đây chỉ hiển thị thông báo thành công
        
        $_SESSION['success'] = "Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất.";
        header("Location: index.php?controller=home&action=contact");
    }
}
?>