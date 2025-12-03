<?php
require_once 'config/Database.php';
require_once 'models/Enrollment.php';
require_once 'models/Course.php';
require_once 'models/Lesson.php';
require_once 'models/Material.php';

/**
 * StudentController - Chức năng dành cho Học viên
 */
class StudentController {
    private $db;
    private $enrollment;
    private $course;
    private $lesson;
    private $material;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->enrollment = new Enrollment($this->db);
        $this->course = new Course($this->db);
        $this->lesson = new Lesson($this->db);
        $this->material = new Material($this->db);
        
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Kiểm tra quyền học viên
        $this->kiemTraQuyen();
    }
    
    /**
     * Kiểm tra quyền truy cập
     */
    private function kiemTraQuyen() {
        if(!isset($_SESSION['user_id']) || $_SESSION['role'] > 0) {
            $_SESSION['error'] = "Vui lòng đăng nhập với tài khoản học viên!";
            header("Location: index.php?controller=auth&action=login");
            exit();
        }
    }
    
    /**
     * Dashboard học viên
     */
    public function dashboard() {
        $student_id = $_SESSION['user_id'];
        
        // Lấy khóa học đang học
        $active_courses = $this->enrollment->getByStudent($student_id, 'active');
        
        // Lấy khóa học đã hoàn thành
        $completed_courses = $this->enrollment->getByStudent($student_id, 'completed');
        
        // Thống kê
        $total_enrolled = count($this->enrollment->getByStudent($student_id));
        $total_completed = count($completed_courses);
        $total_active = count($active_courses);
        
        include 'views/student/dashboard.php';
    }
    
    /**
     * Danh sách khóa học đã đăng ký
     */
    public function khoaHocCuaToi() {
        $student_id = $_SESSION['user_id'];
        $status = isset($_GET['status']) ? $_GET['status'] : null;
        
        $enrollments = $this->enrollment->getByStudent($student_id, $status);
        
        include 'views/student/my_courses.php';
    }
    
    /**
     * Xem tiến độ khóa học
     */
    public function tienDoKhoaHoc() {
        $enrollment_id = isset($_GET['enrollment_id']) ? intval($_GET['enrollment_id']) : 0;
        
        if($enrollment_id <= 0) {
            $_SESSION['error'] = "Không tìm thấy khóa học!";
            header("Location: index.php?controller=student&action=my_courses");
            return;
        }
        
        // Lấy thông tin enrollment
        $enrollment = $this->enrollment->getById($enrollment_id);
        
        // Kiểm tra quyền
        if(!$enrollment || $enrollment['student_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = "Bạn không có quyền xem khóa học này!";
            header("Location: index.php?controller=student&action=my_courses");
            return;
        }
        
        // Lấy danh sách bài học kèm tiến độ
        $lessons = $this->lesson->layTheoKhoaHocVaHocVien($enrollment['course_id'], $enrollment_id);
        
        // Lấy thông tin khóa học
        $course = $this->course->getById($enrollment['course_id']);
        
        include 'views/student/course_progress.php';
    }
    
    /**
     * Xem bài học
     */
    public function xemBaiHoc() {
        $lesson_id = isset($_GET['lesson_id']) ? intval($_GET['lesson_id']) : 0;
        $enrollment_id = isset($_GET['enrollment_id']) ? intval($_GET['enrollment_id']) : 0;
        
        if($lesson_id <= 0 || $enrollment_id <= 0) {
            $_SESSION['error'] = "Thông tin không hợp lệ!";
            header("Location: index.php?controller=student&action=my_courses");
            return;
        }
        
        // Kiểm tra quyền
        $enrollment = $this->enrollment->getById($enrollment_id);
        if(!$enrollment || $enrollment['student_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = "Bạn không có quyền xem bài học này!";
            header("Location: index.php?controller=student&action=my_courses");
            return;
        }
        
        // Lấy thông tin bài học
        $lesson = $this->lesson->layTheoId($lesson_id);
        
        // Lấy tài liệu của bài học
        $materials = $this->material->layTheoBaiHoc($lesson_id);
        
        // Lấy danh sách tất cả bài học của khóa
        $all_lessons = $this->lesson->layTheoKhoaHocVaHocVien($enrollment['course_id'], $enrollment_id);
        
        include 'views/student/lesson_view.php';
    }
    
    /**
     * Đánh dấu bài học đã hoàn thành
     */
    public function hoanThanhBaiHoc() {
        $lesson_id = isset($_POST['lesson_id']) ? intval($_POST['lesson_id']) : 0;
        $enrollment_id = isset($_POST['enrollment_id']) ? intval($_POST['enrollment_id']) : 0;
        
        if($lesson_id <= 0 || $enrollment_id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Thông tin không hợp lệ!']);
            return;
        }
        
        // Kiểm tra quyền
        $enrollment = $this->enrollment->getById($enrollment_id);
        if(!$enrollment || $enrollment['student_id'] != $_SESSION['user_id']) {
            echo json_encode(['success' => false, 'message' => 'Bạn không có quyền!']);
            return;
        }
        
        // Đánh dấu hoàn thành
        if($this->lesson->danhDauHoanThanh($lesson_id, $enrollment_id)) {
            // Cập nhật tiến độ enrollment
            $progress = $this->enrollment->calculateProgress($enrollment_id);
            
            echo json_encode([
                'success' => true, 
                'message' => 'Đã hoàn thành bài học!',
                'progress' => $progress
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra!']);
        }
    }
    
    /**
     * Đăng ký khóa học
     */
    public function dangKyKhoaHoc() {
        $course_id = isset($_POST['course_id']) ? intval($_POST['course_id']) : 0;
        
        if($course_id <= 0) {
            $_SESSION['error'] = "Khóa học không tồn tại!";
            header("Location: index.php?controller=course&action=list");
            return;
        }
        
        // Kiểm tra khóa học tồn tại
        $course = $this->course->getById($course_id);
        if(!$course) {
            $_SESSION['error'] = "Khóa học không tồn tại!";
            header("Location: index.php?controller=course&action=list");
            return;
        }
        
        // Đăng ký
        $this->enrollment->course_id = $course_id;
        $this->enrollment->student_id = $_SESSION['user_id'];
        
        if($this->enrollment->enroll()) {
            $_SESSION['success'] = "Đăng ký khóa học thành công!";
        } else {
            $_SESSION['error'] = "Bạn đã đăng ký khóa học này rồi!";
        }
        
        header("Location: index.php?controller=course&action=detail&id=" . $course_id);
    }
    
    /**
     * Hủy đăng ký khóa học
     */
    public function huyDangKy() {
        $course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;
        
        if($course_id <= 0) {
            $_SESSION['error'] = "Thông tin không hợp lệ!";
            header("Location: index.php?controller=student&action=my_courses");
            return;
        }
        
        if($this->enrollment->unenroll($course_id, $_SESSION['user_id'])) {
            $_SESSION['success'] = "Đã hủy đăng ký khóa học!";
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra!";
        }
        
        header("Location: index.php?controller=student&action=my_courses");
    }
}
?>