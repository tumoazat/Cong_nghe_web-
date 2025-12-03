<?php
$tiêu_đề = "Chỉnh sửa khóa học - Hệ thống Quản lý Khóa học Online";
require_once 'views/layouts/header.php';
?>

<div class="container">
    <div class="dashboard">
        <?php require_once 'views/layouts/sidebar.php'; ?>
        
        <div class="content">
            <h1>Chỉnh sửa khóa học</h1>
            
            <?php if ($khóa_học): ?>
                <form method="POST" action="index.php?controller=instructor&action=edit_course&id=<?php echo $khóa_học['id']; ?>">
                    <div class="form-group">
                        <label for="title">Tên khóa học:</label>
                        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($khóa_học['title']); ?>" required class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Mô tả:</label>
                        <textarea id="description" name="description" rows="4" required class="form-control"><?php echo htmlspecialchars($khóa_học['description']); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="category_id">Danh mục:</label>
                        <select id="category_id" name="category_id" required class="form-control">
                            <?php if (!empty($danh_sách_danh_mục)): ?>
                                <?php foreach ($danh_sách_danh_mục as $danh_mục): ?>
                                    <option value="<?php echo $danh_mục['id']; ?>" <?php echo ($khóa_học['category_id'] == $danh_mục['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($danh_mục['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="price">Giá (VNĐ):</label>
                        <input type="number" id="price" name="price" min="0" value="<?php echo $khóa_học['price']; ?>" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="duration_weeks">Thời lượng (tuần):</label>
                        <input type="number" id="duration_weeks" name="duration_weeks" min="1" value="<?php echo $khóa_học['duration_weeks']; ?>" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="level">Trình độ:</label>
                        <select id="level" name="level" class="form-control">
                            <option value="Beginner" <?php echo ($khóa_học['level'] == 'Beginner') ? 'selected' : ''; ?>>Beginner</option>
                            <option value="Intermediate" <?php echo ($khóa_học['level'] == 'Intermediate') ? 'selected' : ''; ?>>Intermediate</option>
                            <option value="Advanced" <?php echo ($khóa_học['level'] == 'Advanced') ? 'selected' : ''; ?>>Advanced</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="image">Hình ảnh (tên file):</label>
                        <input type="text" id="image" name="image" value="<?php echo htmlspecialchars($khóa_học['image']); ?>" placeholder="example.jpg" class="form-control">
                    </div>
                    
                    <button type="submit" class="btn btn-success">Cập nhật</button>
                    <a href="index.php?controller=instructor&action=my_courses" class="btn btn-secondary">Hủy</a>
                </form>
            <?php else: ?>
                <p>Không tìm thấy khóa học.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
