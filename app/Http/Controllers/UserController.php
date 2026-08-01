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

        $user = User::create([
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'password' => \Illuminate\Support\Facades\Hash::make($request->input('password')),
            'role_id' => $request->input('role_id'),
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
