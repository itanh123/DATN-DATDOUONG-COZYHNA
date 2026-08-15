<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if (!check_permission('view_users')) {
            return redirect('/login')->with('error', 'Bạn không có quyền truy cập trang này.');
        }

        $roles = DB::table('roles')->get();

        $query = User::with('role');

        if ($request->has('role_id') && $request->role_id != '') {
            $query->where('role_id', $request->role_id);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $staffUsers = (clone $query)->whereHas('role', function($q) {
            $q->where('code', '!=', 'customer');
        })->get();

        $customerUsers = (clone $query)->whereHas('role', function($q) {
            $q->where('code', 'customer');
        })->get();

        return view('admin.users', compact('staffUsers', 'customerUsers', 'roles'));
    }

    public function store(Request $request)
    {
        if (!check_permission('create_users')) {
            return back()->withErrors(['error' => 'Bạn không có quyền truy cập.']);
        }

        $request->validate([
            'username' => 'required|string|max:50|unique:users,username',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::create([
            'username' => $request->input('username'),
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'password' => Hash::make($request->input('password')),
            'role_id' => $request->input('role_id'),
            'status' => true,
        ]);

        $this->ensureShipperProfile($user);

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

        $this->ensureShipperProfile($user);

        return back()->with('success', 'Cập nhật quyền hạn thành công!');
    }

    public function updatePassword(Request $request, User $user)
    {
        if (!check_permission('change_own_password')) {
            return back()->withErrors(['error' => 'Bạn chưa được cấp quyền đổi mật khẩu cá nhân!']);
        }

        if ($user->id != session('user_id')) {
            return back()->withErrors(['error' => 'Bạn không có quyền đổi mật khẩu của người khác!']);
        }

        $request->validate([
            'new_password' => 'required|string|min:6',
        ]);

        $user->update([
            'password' => Hash::make($request->input('new_password'))
        ]);

        return back()->with('success', 'Đổi mật khẩu thành công!');
    }

    public function toggleStatus(Request $request, User $user)
    {
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
        if (!in_array(session('role_code'), ['admin', 'staff'])) {
            return back()->withErrors(['error' => 'Bạn không có quyền thực hiện thao tác này!']);
        }

        if ($user->role && in_array($user->role->code, ['admin', 'staff', 'shipper'])) {
            return back()->withErrors(['error' => 'Chỉ có thể hạn chế tài khoản Khách hàng!']);
        }

        $user->update([
            'is_restricted' => !$user->is_restricted
        ]);

        $action = $user->is_restricted ? 'Hạn chế' : 'Bỏ hạn chế';
        return back()->with('success', "Đã $action tài khoản thành công!");
    }

    private function ensureShipperProfile(User $user)
    {
        $role = DB::table('roles')->where('id', $user->role_id)->first();
        if ($role && $role->code === 'shipper') {
            \App\Models\ShipperProfile::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'status' => 'OFFLINE'
                ]
            );
        }
    }
}
