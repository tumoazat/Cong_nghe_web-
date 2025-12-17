@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>📋 Danh sách Task</h1>
        <a href="{{ route('tasks.create') }}" class="btn btn-primary">
            ➕ Thêm mới Task
        </a>
    </div>

    @if($tasks->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 5%">ID</th>
                        <th style="width: 20%">Tiêu đề</th>
                        <th style="width: 35%">Mô tả</th>
                        <th style="width: 15%">Trạng thái</th>
                        <th style="width: 25%">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tasks as $task)
                        <tr class="{{ $task->completed ? 'table-success' : '' }}">
                            <td>{{ $task->id }}</td>
                            <td class="{{ $task->completed ? 'task-completed' : '' }}">
                                {{ $task->title }}
                            </td>
                            <td class="{{ $task->completed ? 'task-completed' : '' }}">
                                {{ Str::limit($task->description, 100) }}
                            </td>
                            <td>
                                @if($task->completed)
                                    <span class="badge bg-success">✅ Hoàn thành</span>
                                @else
                                    <span class="badge bg-warning text-dark">⏳ Chưa hoàn thành</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-info btn-sm">
                                    👁️ Xem
                                </a>
                                <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-warning btn-sm">
                                    ✏️ Sửa
                                </a>
                                <form action="{{ route('tasks.destroy', $task->id) }}" method="POST"
                                    style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Bạn có chắc chắn muốn xóa task này?')">
                                        🗑️ Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            <p class="text-muted">
                Tổng số: <strong>{{ $tasks->count() }}</strong> task |
                Hoàn thành: <strong>{{ $tasks->where('completed', true)->count() }}</strong> |
                Chưa hoàn thành: <strong>{{ $tasks->where('completed', false)->count() }}</strong>
            </p>
        </div>
    @else
        <div class="alert alert-info text-center">
            <h4>📭 Chưa có task nào!</h4>
            <p>Hãy thêm task đầu tiên của bạn.</p>
            <a href="{{ route('tasks.create') }}" class="btn btn-primary">➕ Thêm Task mới</a>
        </div>
    @endif
</div>
@endsection
