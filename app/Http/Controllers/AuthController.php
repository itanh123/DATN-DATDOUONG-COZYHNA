<?php

namespace App\Http\Controllers;

use App\Models\CustomerProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use App\Mail\OtpMail;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('customer.auth');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if ($request->input('captcha_verified') !== '1') {
            return back()->withErrors(['email' => 'Vui lòng xác minh "Tôi không phải là người máy".'])->withInput();
        }

        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return back()->withErrors(['email' => 'Email hoặc mật khẩu không đúng.'])->withInput();
        }

        if (!$user->status) {
            return back()->withErrors(['email' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.'])->withInput();
        }

        $roleCode = DB::table('roles')->where('id', $user->role_id)->value('code');

        if ($roleCode !== 'customer') {
            return back()->withErrors(['email' => 'Tài khoản không được phép đăng nhập tại đây. Vui lòng sử dụng trang đăng nhập quản trị.'])->withInput();
        }

        $request->session()->put('user_id', $user->id);
        $request->session()->put('role_code', $roleCode);

        return redirect('/')->with('success', 'Xin chào ' . ($user->name ?? $user->username) . ', chào mừng bạn quay lại!');
    }

    public function showLoginAdmin()
    {
        return view('admin.login');
    }

    public function loginAdmin(Request $request)
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

        $roleCode = DB::table('roles')->where('id', $user->role_id)->value('code');

        if (!in_array($roleCode, ['admin', 'staff', 'shipper'])) {
            return back()->withErrors(['email' => 'Khách hàng không thể đăng nhập tại đây.'])->withInput();
        }

        $request->session()->put('user_id', $user->id);
        $request->session()->put('role_code', $roleCode);

        if ($roleCode === 'shipper') {
            return redirect('/shipper/delivery_portal');
        }
        if ($roleCode === 'staff') {
            return redirect('/staff/dashboard');
        }

        return redirect('/admin/dashboard');
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

        if ($request->input('captcha_verified') !== '1') {
            return response()->json(['error' => 'Vui lòng xác minh "Tôi không phải là người máy".'], 422);
        }

        $email = $request->input('email');

        // Rate limiting send OTP
        if (RateLimiter::tooManyAttempts('send-otp-register:'.$email, 1)) {
            return response()->json(['error' => 'Vui lòng đợi 1 phút trước khi gửi lại mã.'], 429);
        }
        RateLimiter::hit('send-otp-register:'.$email, 60);

        $otp = (string) rand(100000, 999999);
        
        Cache::put('register_otp_'.$email, [
            'otp' => $otp,
            'username' => $request->input('username'),
            'email' => $email,
            'phone' => $request->input('phone'),
            'password' => Hash::make($request->input('password')),
        ], now()->addMinutes(10));

        RateLimiter::clear('verify-otp-register:'.$email);

        Mail::to($email)->queue(new OtpMail($otp, 'xác nhận đăng ký tài khoản'));

        return response()->json([
            'message' => 'Mã xác nhận đã được gửi đến email của bạn.',
            'require_otp' => true,
            'email' => $email
        ]);
    }

    public function verifyRegistrationOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ]);

        $email = $request->email;

        if (RateLimiter::tooManyAttempts('verify-otp-register:'.$email, 5)) {
            return response()->json(['error' => 'Bạn đã nhập sai quá nhiều lần. Vui lòng thử lại sau 30 phút.'], 429);
        }

        $cachedData = Cache::get('register_otp_'.$email);
        if (!$cachedData) {
            return response()->json(['error' => 'Mã OTP đã hết hạn hoặc không tồn tại. Vui lòng đăng ký lại.'], 400);
        }

        if ($cachedData['otp'] !== $request->otp) {
            RateLimiter::hit('verify-otp-register:'.$email, 1800);
            $retriesLeft = RateLimiter::retriesLeft('verify-otp-register:'.$email, 5);
            return response()->json(['error' => 'Mã OTP không đúng. Bạn còn '.$retriesLeft.' lần thử.'], 400);
        }

        // Tạo tài khoản
        $roleId = DB::table('roles')->where('code', 'customer')->value('id');

        // Double check existance to prevent race conditions
        if (User::where('email', $email)->orWhere('username', $cachedData['username'])->exists()) {
            return response()->json(['error' => 'Email hoặc Tên đăng nhập đã tồn tại.'], 400);
        }

        $user = User::create([
            'username' => $cachedData['username'],
            'email' => $cachedData['email'],
            'phone' => $cachedData['phone'],
            'password' => $cachedData['password'],
            'role_id' => $roleId,
            'status' => true,
        ]);
        
        CustomerProfile::firstOrCreate(['user_id' => $user->id]);

        $request->session()->put('user_id', $user->id);
        $request->session()->put('role_code', 'customer');

        Cache::forget('register_otp_'.$email);
        RateLimiter::clear('verify-otp-register:'.$email);

        $request->session()->flash('success', 'Xin chào ' . $user->username . ', đăng ký thành công!');

        return response()->json(['message' => 'Đăng ký tài khoản thành công!']);
    }

    public function redirectToGoogle()
    {
        return \Laravel\Socialite\Facades\Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = \Laravel\Socialite\Facades\Socialite::driver('google')->stateless()->user();
            
            $user = User::where('google_id', $googleUser->id)->first();
            
            if (!$user) {
                $user = User::where('email', $googleUser->email)->first();
                if ($user) {
                    $user->update(['google_id' => $googleUser->id]);
                } else {
                    $roleId = DB::table('roles')->where('code', 'customer')->value('id');
                    
                    $baseUsername = Str::slug($googleUser->name, '');
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
                        'password' => Hash::make(Str::random(16)),
                        'role_id' => $roleId,
                        'status' => true,
                    ]);
                }
            }

            if (!$user->status) {
                return redirect('/login')->withErrors(['email' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.']);
            }

            $roleCode = DB::table('roles')->where('id', $user->role_id)->value('code');

            if ($roleCode === 'customer') {
                CustomerProfile::firstOrCreate(['user_id' => $user->id]);
            }

            $request->session()->put('user_id', $user->id);
            $request->session()->put('role_code', $roleCode);

            return redirect('/')->with('success', 'Xin chào ' . ($user->name ?? $user->username) . ', chào mừng bạn quay lại!');
        } catch (\Exception $e) {
            Log::error('Google Login Error: ' . $e->getMessage());
            return redirect('/login')->withErrors(['email' => 'Đăng nhập Google thất bại. Lỗi: ' . $e->getMessage()]);
        }
    }

    public function updateProfile(Request $request)
    {
        $userId = session('user_id');
        if (!$userId) return redirect('/login');

        $user = User::findOrFail($userId);

        $rules = [
            'name' => 'nullable|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

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

        if ($request->hasFile('avatar')) {
            if ($user->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        $user->save();

        return redirect('/customer/account')->with('success', 'Cập nhật thông tin thành công!');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $email = $request->email;

        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json(['error' => 'Email không tồn tại trong hệ thống.'], 404);
        }

        if ($user->google_id) {
            return response()->json(['error' => 'Tài khoản này được đăng nhập qua Google. Không thể đặt lại mật khẩu tại đây.'], 403);
        }

        // Rate limiting send OTP (1 request per minute)
        if (RateLimiter::tooManyAttempts('send-otp:'.$email, 1)) {
            return response()->json(['error' => 'Vui lòng đợi 1 phút trước khi gửi lại mã.'], 429);
        }
        RateLimiter::hit('send-otp:'.$email, 60);

        $otp = (string) rand(100000, 999999);
        Cache::put('forgot_password_otp_'.$email, $otp, now()->addMinutes(10));
        
        // Reset verify attempt counter
        RateLimiter::clear('verify-otp:'.$email);

        Mail::to($email)->queue(new OtpMail($otp, 'khôi phục mật khẩu'));

        return response()->json(['message' => 'Mã OTP đã được gửi đến email của bạn.']);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
            'password' => 'required|min:6|confirmed'
        ]);

        $email = $request->email;

        // Check 5 wrong attempts lock for 30 minutes
        if (RateLimiter::tooManyAttempts('verify-otp:'.$email, 5)) {
            return response()->json(['error' => 'Bạn đã nhập sai quá nhiều lần. Vui lòng thử lại sau 30 phút.'], 429);
        }

        $cachedOtp = Cache::get('forgot_password_otp_'.$email);
        if (!$cachedOtp) {
            return response()->json(['error' => 'Mã OTP đã hết hạn hoặc không tồn tại.'], 400);
        }

        if ($cachedOtp !== $request->otp) {
            RateLimiter::hit('verify-otp:'.$email, 1800); // 30 minutes lock
            $retriesLeft = RateLimiter::retriesLeft('verify-otp:'.$email, 5);
            return response()->json(['error' => 'Mã OTP không đúng. Bạn còn '.$retriesLeft.' lần thử.'], 400);
        }

        $user = User::where('email', $email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        Cache::forget('forgot_password_otp_'.$email);
        RateLimiter::clear('verify-otp:'.$email);

        return response()->json(['message' => 'Mật khẩu đã được đặt lại thành công.']);
    }
}
