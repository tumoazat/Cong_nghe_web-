@extends('layouts.app')

@section('title', 'Thêm Người Dùng')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white">
                <h4 class="mb-0"><i class="bi bi-person-plus-fill"></i> Thêm Người Dùng Mới</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="username" class="form-label">Tên Tài Khoản <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" 
                               id="username" name="username" value="{{ old('username') }}" required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Mật Khẩu <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Mật khẩu phải có ít nhất 6 ký tự</small>
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Vai Trò <span class="text-danger">*</span></label>
                        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                            <option value="">-- Chọn vai trò --</option>
                            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Người Dùng</option>
                            <option value="moderator" {{ old('role') == 'moderator' ? 'selected' : '' }}>Người Kiểm Duyệt</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Quản Trị Viên</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Lưu
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Quay Lại
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
