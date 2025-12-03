<?php
require_once 'config/Database.php';
require_once 'models/User.php';

/**
 * AuthController - Xử lý đăng nhập, đăng ký, đăng xuất
 */
class AuthController {
    private $db;
    private $user;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
        
        // Khởi tạo session nếu chưa có
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Hiển thị trang đăng nhập
     */
    public function hienThiDangNhap() {
        // Nếu đã đăng nhập rồi thì chuyển hướng
        if($this->kiemTraDangNhap()) {
            $this->chuyenHuongTheoRole();
            return;
        }
        
        include 'views/auth/login.php';
    }
    
    /**
     * Xử lý đăng nhập
     */
    public function xuLyDangNhap() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = trim($_POST['username']);
            $password = $_POST['password'];
            
            // Validate
            if(empty($username) || empty($password)) {
                $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin!";
                header("Location: index.php?controller=auth&action=login");
                return;
            }
            
            // Đăng nhập
            if($this->user->login($username, $password)) {
                // Lưu thông tin vào session
                $_SESSION['user_id'] = $this->user->id;
                $_SESSION['username'] = $this->user->username;
                $_SESSION['fullname'] = $this->user->fullname;
                $_SESSION['email'] = $this->user->email;
                $_SESSION['role'] = $this->user->role;
                $_SESSION['avatar'] = $this->user->avatar;
                
                $_SESSION['success'] = "Đăng nhập thành công!";
                
                // Chuyển hướng theo role
                $this->chuyenHuongTheoRole();
            } else {
                $_SESSION['error'] = "Tên đăng nhập hoặc mật khẩu không đúng!";
                header("Location: index.php?controller=auth&action=login");
            }
        } else {
            header("Location: index.php?controller=auth&action=login");
        }
    }
    
    /**
     * Hiển thị trang đăng ký
     */
    public function hienThiDangKy() {
        if($this->kiemTraDangNhap()) {
            $this->chuyenHuongTheoRole();
            return;
        }
        
        include 'views/auth/register.php';
    }
    
    /**
     * Xử lý đăng ký
     */
    public function xuLyDangKy() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            $fullname = trim($_POST['fullname']);
            $role = isset($_POST['role']) ? intval($_POST['role']) : 0; // Mặc định là học viên
            
            // Validate
            $errors = [];
            
            if(empty($username)) {
                $errors[] = "Vui lòng nhập tên đăng nhập!";
            } elseif(strlen($username) < 3) {
                $errors[] = "Tên đăng nhập phải có ít nhất 3 ký tự!";
            } elseif($this->user->usernameExists($username)) {
                $errors[] = "Tên đăng nhập đã tồn tại!";
            }
            
            if(empty($email)) {
                $errors[] = "Vui lòng nhập email!";
            } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Email không hợp lệ!";
            } elseif($this->user->emailExists($email)) {
                $errors[] = "Email đã được sử dụng!";
            }
            
            if(empty($password)) {
                $errors[] = "Vui lòng nhập mật khẩu!";
            } elseif(strlen($password) < 6) {
                $errors[] = "Mật khẩu phải có ít nhất 6 ký tự!";
            }
            
            if($password !== $confirm_password) {
                $errors[] = "Mật khẩu xác nhận không khớp!";
            }
            
            if(empty($fullname)) {
                $errors[] = "Vui lòng nhập họ tên!";
            }
            
            if(!empty($errors)) {
                $_SESSION['errors'] = $errors;
                $_SESSION['old'] = $_POST;
                header("Location: index.php?controller=auth&action=register");
                return;
            }
            
            // Đăng ký
            $this->user->username = $username;
            $this->user->email = $email;
            $this->user->password = $password;
            $this->user->fullname = $fullname;
            $this->user->role = $role;
            
            if($this->user->register()) {
                $_SESSION['success'] = "Đăng ký thành công! Vui lòng đăng nhập.";
                header("Location: index.php?controller=auth&action=login");
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra, vui lòng thử lại!";
                header("Location: index.php?controller=auth&action=register");
            }
        } else {
            header("Location: index.php?controller=auth&action=register");
        }
    }
    
    /**
     * Đăng xuất
     */
    public function dangXuat() {
        session_destroy();
        header("Location: index.php");
        exit();
    }
    
    /**
     * Kiểm tra đã đăng nhập chưa
     */
    public function kiemTraDangNhap() {
        return isset($_SESSION['user_id']);
    }
    
    /**
     * Kiểm tra quyền theo role
     */
    public function kiemTraQuyen($required_role) {
        if(!$this->kiemTraDangNhap()) {
            $_SESSION['error'] = "Vui lòng đăng nhập!";
            header("Location: index.php?controller=auth&action=login");
            exit();
        }
        
        if($_SESSION['role'] < $required_role) {
            $_SESSION['error'] = "Bạn không có quyền truy cập!";
            header("Location: index.php");
            exit();
        }
        
        return true;
    }
    
    /**
     * Chuyển hướng theo role
     */
    private function chuyenHuongTheoRole() {
        switch($_SESSION['role']) {
            case 2: // Admin
                header("Location: index.php?controller=admin&action=dashboard");
                break;
            case 1: // Giảng viên
                header("Location: index.php?controller=instructor&action=dashboard");
                break;
            default: // Học viên
                header("Location: index.php?controller=student&action=dashboard");
                break;
        }
        exit();
    }
}
?>