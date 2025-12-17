@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0">👁️ Chi tiết Task</h4>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h5 class="text-primary">📌 Tiêu đề:</h5>
                        <p class="fs-4 {{ $task->completed ? 'task-completed' : '' }}">
                            {{ $task->title }}
                        </p>
                    </div>

                    <div class="mb-4">
                        <h5 class="text-primary">📝 Mô tả:</h5>
                        <p class="{{ $task->completed ? 'task-completed' : '' }}">
                            {{ $task->description }}
                        </p>
                    </div>

                    @if($task->long_description)
                        <div class="mb-4">
                            <h5 class="text-primary">📋 Mô tả chi tiết:</h5>
                            <p class="{{ $task->completed ? 'task-completed' : '' }}">
                                {{ $task->long_description }}
                            </p>
                        </div>
                    @endif

                    <div class="mb-4">
                        <h5 class="text-primary">📊 Trạng thái:</h5>
                        @if($task->completed)
                            <span class="badge bg-success fs-6">✅ Hoàn thành</span>
                        @else
                            <span class="badge bg-warning text-dark fs-6">⏳ Chưa hoàn thành</span>
                        @endif
                    </div>

                    <div class="mb-4">
                        <h5 class="text-primary">📅 Thời gian:</h5>
                        <p class="text-muted">
                            <small>
                                Tạo lúc: {{ $task->created_at->format('d/m/Y H:i:s') }}<br>
                                Cập nhật: {{ $task->updated_at->format('d/m/Y H:i:s') }}
                            </small>
                        </p>
                    </div>

                    <hr>

                    <div class="d-flex gap-2">
                        <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-warning">
                            ✏️ Sửa Task
                        </a>
                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST"
                            style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Bạn có chắc chắn muốn xóa task này?')">
                                🗑️ Xóa Task
                            </button>
                        </form>
                        <a href="{{ route('tasks.index') }}" class="btn btn-secondary">
                            ↩️ Quay lại danh sách
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
