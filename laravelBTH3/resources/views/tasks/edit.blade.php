@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning">
                    <h4 class="mb-0">✏️ Sửa Task</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="title" class="form-label">Tiêu đề: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                id="title" name="title" value="{{ old('title', $task->title) }}" required
                                placeholder="Nhập tiêu đề task">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả: <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                id="description" name="description" rows="3" required
                                placeholder="Nhập mô tả ngắn gọn">{{ old('description', $task->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="long_description" class="form-label">Mô tả chi tiết:</label>
                            <textarea class="form-control" id="long_description" name="long_description" rows="5"
                                placeholder="Nhập mô tả chi tiết (không bắt buộc)">{{ old('long_description', $task->long_description) }}</textarea>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="completed" name="completed"
                                {{ old('completed', $task->completed) ? 'checked' : '' }}>
                            <label class="form-check-label" for="completed">✅ Đã hoàn thành</label>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">💾 Cập nhật Task</button>
                            <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-info">👁️ Xem chi tiết</a>
                            <a href="{{ route('tasks.index') }}" class="btn btn-secondary">↩️ Quay lại</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
