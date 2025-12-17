@extends('layouts.app')

@section('title', 'Danh Sách Người Dùng')

@section('content')
<div class="card">
    <div class="card-header bg-white py-3">
        <div class="row align-items-center">
            <div class="col">
                <h4 class="mb-0"><i class="bi bi-list-ul"></i> Danh Sách Người Dùng</h4>
            </div>
            <div class="col-auto">
                <a href="{{ route('users.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Thêm Người Dùng
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th width="10%">Mã Người Dùng</th>
                        <th width="20%">Tên Tài Khoản</th>
                        <th width="30%">Email</th>
                        <th width="15%">Vai Trò</th>
                        <th width="25%" class="text-center">Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td><strong>#{{ $user->id }}</strong></td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->role == 'admin')
                                <span class="badge bg-danger">Quản Trị Viên</span>
                            @elseif($user->role == 'moderator')
                                <span class="badge bg-warning">Người Kiểm Duyệt</span>
                            @else
                                <span class="badge bg-info">Người Dùng</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil-square"></i> Sửa
                            </a>
                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $user->id }}">
                                <i class="bi bi-trash"></i> Xóa
                            </button>

                            <!-- Modal xác nhận xóa -->
                            <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Xác Nhận Xóa</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Bạn có chắc chắn muốn xóa người dùng <strong>{{ $user->username }}</strong> không?</p>
                                            <p class="text-danger"><i class="bi bi-exclamation-triangle"></i> Hành động này không thể hoàn tác!</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Xóa</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="mt-2 text-muted">Chưa có dữ liệu người dùng</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <small class="text-muted">
                    Hiển thị {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} 
                    trong tổng số {{ $users->total() }} bản ghi
                </small>
            </div>
            <div>
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
