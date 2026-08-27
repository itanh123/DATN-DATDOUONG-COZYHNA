<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CustomerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * API Đăng nhập
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'Email hoặc mật khẩu không chính xác'], 401);
        }

        if (!$user->status) {
            return response()->json(['success' => false, 'message' => 'Tài khoản của bạn đã bị khóa'], 403);
        }

        $roleCode = DB::table('roles')->where('id', $user->role_id)->value('code');
        if ($roleCode !== 'customer') {
            return response()->json(['success' => false, 'message' => 'Tài khoản này không được phép đăng nhập trên App'], 403);
        }

        $token = $user->createToken('MobileAppToken')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Đăng nhập thành công',
            'data' => [
                'user' => $user,
                'token' => $token
            ]
        ]);
    }

    /**
     * API Đăng ký
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:50|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20|unique:users,phone',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $roleId = DB::table('roles')->where('code', 'customer')->value('id');

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role_id' => $roleId,
            'status' => true,
        ]);

        CustomerProfile::firstOrCreate(['user_id' => $user->id]);

        $token = $user->createToken('MobileAppToken')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Đăng ký thành công',
            'data' => [
                'user' => $user,
                'token' => $token
            ]
        ], 201);
    }

    /**
     * API Đăng xuất
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đăng xuất thành công'
        ]);
    }

    /**
     * API Lấy thông tin người dùng đang đăng nhập
     */
    public function me(Request $request)
    {
        $user = clone $request->user();
        $user->load('customerProfile');
        
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    /**
     * API Đăng nhập qua Google từ App
     */
    public function googleLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'google_id' => 'required|string',
            'name' => 'required|string',
        ]);

        $email = $request->email;
        $googleId = $request->google_id;
        $name = $request->name;

        // Tìm user theo google_id hoặc email
        $user = User::where('google_id', $googleId)->first();
        if (!$user) {
            $user = User::where('email', $email)->first();
            if ($user) {
                $user->update(['google_id' => $googleId]);
            } else {
                $roleId = DB::table('roles')->where('code', 'customer')->value('id');
                
                $baseUsername = \Illuminate\Support\Str::slug($name, '');
                $username = $baseUsername;
                $counter = 1;
                while (User::where('username', $username)->exists()) {
                    $username = $baseUsername . $counter;
                    $counter++;
                }

                $user = User::create([
                    'name' => $name,
                    'username' => $username,
                    'email' => $email,
                    'google_id' => $googleId,
                    'password' => Hash::make(\Illuminate\Support\Str::random(16)),
                    'role_id' => $roleId,
                    'status' => true,
                ]);
            }
        }

        if (!$user->status) {
            return response()->json(['success' => false, 'message' => 'Tài khoản của bạn đã bị khóa'], 403);
        }

        $roleCode = DB::table('roles')->where('id', $user->role_id)->value('code');
        if ($roleCode !== 'customer') {
            return response()->json(['success' => false, 'message' => 'Tài khoản này không được phép đăng nhập trên App'], 403);
        }

        CustomerProfile::firstOrCreate(['user_id' => $user->id]);

        $token = $user->createToken('MobileAppToken')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Đăng nhập Google thành công',
            'data' => [
                'user' => $user,
                'token' => $token
            ]
        ]);
    }
}
