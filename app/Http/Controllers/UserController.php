<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('id', 'desc')->get();
        $roles = DB::table('roles')->get();

        // Map role to user
        foreach ($users as $user) {
            $user->role = $roles->firstWhere('id', $user->role_id);
        }

        $staffUsers = $users->filter(function ($user) {
            return $user->role && in_array($user->role->code, ['admin', 'staff', 'shipper']);
        });

        $customerUsers = $users->filter(function ($user) {
            return !$user->role || !in_array($user->role->code, ['admin', 'staff', 'shipper']);
        });

        // Filter based on logged-in user role
        $roleCode = session('role_code');
        $userId = session('user_id');

        if ($roleCode === 'staff') {
            // Staff can only see themselves in staff list, and all customers
            $staffUsers = $staffUsers->filter(function ($user) use ($userId) {
                return $user->id == $userId;
            });
        } elseif ($roleCode === 'shipper') {
            // Shipper can only see themselves, no customers
            $staffUsers = $staffUsers->filter(function ($user) use ($userId) {
                return $user->id == $userId;
            });
            $customerUsers = collect(); // empty collection
        }
        // If 'admin', they see everything, so no filter needed.

        return view('admin.users', compact('staffUsers', 'customerUsers', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:6'],
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        User::create([
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'password' => \Illuminate\Support\Facades\Hash::make($request->input('password')),
            'role_id' => $request->input('role_id'),
            'status' => true,
        ]);

        return back()->with('success', 'Đã tạo người dùng mới thành công!');
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->update([
            'role_id' => $request->input('role_id')
        ]);

        return back()->with('success', 'Cập nhật quyền hạn thành công!');
    }

    public function updatePassword(Request $request, User $user)
    {
        if (!check_permission('change_own_password')) {
            return back()->withErrors(['error' => 'Bạn chưa được cấp quyền đổi mật khẩu cá nhân!']);
        }

        // Only allow if logged in user is the same as the target user
        if ($user->id != session('user_id')) {
            return back()->withErrors(['error' => 'Bạn không có quyền đổi mật khẩu của người khác!']);
        }

        $request->validate([
            'new_password' => 'required|string|min:6',
        ]);

        $user->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->input('new_password'))
        ]);

        return back()->with('success', 'Đổi mật khẩu thành công!');
    }

    public function toggleStatus(Request $request, User $user)
    {
        // Only admin can lock/unlock accounts
        if (session('role_code') !== 'admin') {
            return back()->withErrors(['error' => 'Chỉ Admin mới có quyền khóa tài khoản!']);
        }

        if ($user->id == session('user_id')) {
            return back()->withErrors(['error' => 'Bạn không thể tự khóa tài khoản của chính mình!']);
        }

        $user->update([
            'status' => !$user->status
        ]);

        $action = $user->status ? 'Mở khóa' : 'Khóa';
        return back()->with('success', "Đã $action tài khoản thành công!");
    }

    public function toggleRestriction(Request $request, User $user)
    {
        // Both Admin and Staff can restrict accounts
        if (!in_array(session('role_code'), ['admin', 'staff'])) {
            return back()->withErrors(['error' => 'Bạn không có quyền thực hiện thao tác này!']);
        }

        // Only allow restricting customers
        if ($user->role && in_array($user->role->code, ['admin', 'staff', 'shipper'])) {
            return back()->withErrors(['error' => 'Chỉ có thể hạn chế tài khoản Khách hàng!']);
        }

        $user->update([
            'is_restricted' => !$user->is_restricted
        ]);

        $action = $user->is_restricted ? 'Hạn chế' : 'Bỏ hạn chế';
        return back()->with('success', "Đã $action tài khoản thành công!");
    }
}
