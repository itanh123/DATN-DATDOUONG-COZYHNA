<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::orderBy('id', 'desc');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->get();
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

    public function toggleStatus(User $user)
    {
        if ($user->role && $user->role->code === 'admin') {
            return back()->withErrors(['error' => 'Không thể thao tác khóa/mở khóa tài khoản Quản trị viên!']);
        }

        $user->status = !$user->status;
        $user->save();

        $action = $user->status ? 'Mở khóa' : 'Khóa';
        return back()->with('success', "Đã $action tài khoản {$user->username} thành công!");
    }
}
