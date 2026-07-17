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
            return back()->withErrors(['email' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.'])->withInput();
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

    public function redirectToGoogle()
    {
        return \Laravel\Socialite\Facades\Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = \Laravel\Socialite\Facades\Socialite::driver('google')->user();
            
            $user = User::where('google_id', $googleUser->id)->first();
            
            if (!$user) {
                $user = User::where('email', $googleUser->email)->first();
                if ($user) {
                    $user->update(['google_id' => $googleUser->id]);
                } else {
                    $roleId = DB::table('roles')->where('code', 'customer')->value('id');
                    
                    // Generate unique username
                    $baseUsername = \Illuminate\Support\Str::slug($googleUser->name, '');
                    $username = $baseUsername;
                    $counter = 1;
                    while (User::where('username', $username)->exists()) {
                        $username = $baseUsername . $counter;
                        $counter++;
                    }

                    $user = User::create([
                        'name' => $googleUser->name,
                        'username' => $username,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'password' => Hash::make(\Illuminate\Support\Str::random(16)),
                        'role_id' => $roleId,
                        'status' => true,
                    ]);
                }
            }

            if (!$user->status) {
                return redirect('/login')->withErrors(['email' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.']);
            }

            $roleCode = DB::table('roles')->where('id', $user->role_id)->value('code');

            $request->session()->put('user_id', $user->id);
            $request->session()->put('role_code', $roleCode);

            return redirect('/');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Google Login Error: ' . $e->getMessage());
            return redirect('/login')->withErrors(['email' => 'Đăng nhập Google thất bại. Lỗi: ' . $e->getMessage()]);
        }
    }

    public function updateProfile(Request $request)
    {
        $userId = session('user_id');
        if (!$userId) return redirect('/login');

        $user = User::findOrFail($userId);

        // Validation rules based on whether user logged in with Google or Email
        $rules = [
            'name' => 'nullable|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // If email user, allow changing email and password
        if (!$user->google_id) {
            $rules['email'] = 'required|email|max:255|unique:users,email,' . $user->id;
            $rules['new_password'] = 'nullable|string|min:6';
        }

        $request->validate($rules);

        $user->name = $request->input('name');
        $user->username = $request->input('username');
        $user->phone = $request->input('phone');

        if (!$user->google_id) {
            $user->email = $request->input('email');
            if ($request->filled('new_password')) {
                $user->password = Hash::make($request->input('new_password'));
            }
        }

        // Handle Avatar Upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        $user->save();

        return redirect('/customer/account')->with('success', 'Cập nhật thông tin thành công!');
    }
}

