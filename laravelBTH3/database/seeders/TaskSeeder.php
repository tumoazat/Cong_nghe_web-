<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tasks = [
            [
                'title' => 'Đi chợ mua rau',
                'description' => 'Mua rau muống, rau cải, cà chua, hành lá',
                'long_description' => 'Nhớ mua rau sạch, rau hữu cơ ở cửa hàng uy tín. Chú ý chọn rau tươi, không bị dập nát.',
                'completed' => false,
            ],
            [
                'title' => 'Hoàn thành báo cáo',
                'description' => 'Hoàn thành báo cáo cuối tháng',
                'long_description' => 'Báo cáo cần bao gồm các số liệu về doanh thu, chi phí, lợi nhuận. Phân tích các yếu tố ảnh hưởng đến kết quả kinh doanh.',
                'completed' => true,
            ],
            [
                'title' => 'Học tiếng Anh',
                'description' => 'Học 30 từ vựng mới',
                'long_description' => null,
                'completed' => false,
            ],
            [
                'title' => 'Tập thể dục',
                'description' => 'Chạy bộ 5km buổi sáng',
                'long_description' => 'Chạy bộ tại công viên gần nhà, nhớ khởi động trước khi chạy.',
                'completed' => false,
            ],
            [
                'title' => 'Đọc sách',
                'description' => 'Đọc 50 trang sách Clean Code',
                'long_description' => 'Ghi chú lại những điểm quan trọng để áp dụng vào dự án thực tế.',
                'completed' => true,
            ],
            [
                'title' => 'Họp nhóm dự án',
                'description' => 'Họp online với team lúc 14h',
                'long_description' => 'Chuẩn bị slide báo cáo tiến độ, danh sách các vấn đề cần thảo luận.',
                'completed' => false,
            ],
            [
                'title' => 'Sửa bug ứng dụng',
                'description' => 'Fix lỗi đăng nhập trên mobile',
                'long_description' => 'Lỗi xảy ra khi người dùng nhập sai mật khẩu 3 lần liên tiếp, ứng dụng bị crash.',
                'completed' => false,
            ],
            [
                'title' => 'Viết unit test',
                'description' => 'Viết test cho module thanh toán',
                'long_description' => 'Coverage tối thiểu 80%, bao gồm test các edge cases.',
                'completed' => true,
            ],
            [
                'title' => 'Review code',
                'description' => 'Review PR của team member',
                'long_description' => null,
                'completed' => false,
            ],
            [
                'title' => 'Cập nhật documentation',
                'description' => 'Cập nhật API docs cho version 2.0',
                'long_description' => 'Thêm các endpoint mới, cập nhật response format cho các endpoint cũ.',
                'completed' => false,
            ],
        ];

        foreach ($tasks as $task) {
            Task::create($task);
        }
    }
}
