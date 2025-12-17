<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Hiển thị danh sách người dùng với phân trang
     */
    public function index()
    {
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Hiển thị form thêm người dùng mới
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Lưu người dùng mới vào database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|unique:users|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,user,moderator',
        ], [
            'username.required' => 'Tên tài khoản không được để trống',
            'username.unique' => 'Tên tài khoản đã tồn tại',
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã tồn tại',
            'password.required' => 'Mật khẩu không được để trống',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'role.required' => 'Vai trò không được để trống',
        ]);

        User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Thêm người dùng thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa người dùng
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Cập nhật thông tin người dùng
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'username' => 'required|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,user,moderator',
        ], [
            'username.required' => 'Tên tài khoản không được để trống',
            'username.unique' => 'Tên tài khoản đã tồn tại',
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã tồn tại',
            'role.required' => 'Vai trò không được để trống',
        ]);

        $user->update([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ]);

        // Cập nhật mật khẩu nếu có
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'min:6',
            ], [
                'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            ]);
            
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('users.index')
            ->with('success', 'Cập nhật người dùng thành công!');
    }

    /**
     * Xóa người dùng
     */
    public function destroy(User $user)
    {
        $user->delete();
        
        return redirect()->route('users.index')
            ->with('success', 'Xóa người dùng thành công!');
    }
}
