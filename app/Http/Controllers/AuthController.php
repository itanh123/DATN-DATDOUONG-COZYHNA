<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('client.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return back()->withErrors(['email' => 'Email hoặc mật khẩu không đúng.'])->withInput();
        }

        if (!$user->status) {
            return back()->withErrors(['email' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ Admin.'])->withInput();
        }

        // role_id được lưu trong users table
        $roleCode = DB::table('roles')->where('id', $user->role_id)->value('code');

        // lưu session đơn giản
        $request->session()->put('user_id', $user->id);
        $request->session()->put('role_code', $roleCode);

        if (in_array($roleCode, ['admin', 'staff', 'shipper'])) {
            return redirect('/admin/dashboard');
        }

        return redirect('/');
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['user_id', 'role_code']);
        return redirect('/');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $roleId = DB::table('roles')->where('code', 'customer')->value('id');

        $user = User::create([
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'password' => Hash::make($request->input('password')),
            'role_id' => $roleId,
            'status' => true,
        ]);

        $request->session()->put('user_id', $user->id);
        $request->session()->put('role_code', 'customer');

        return redirect('/');
    }
}

