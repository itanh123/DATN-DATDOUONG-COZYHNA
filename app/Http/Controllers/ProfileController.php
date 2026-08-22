<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CustomerProfile;
use App\Models\CustomerAddress;
use App\Models\ShipperProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use App\Mail\OtpMail;

class ProfileController extends Controller
{
    public function customerAccount()
    {
        $userId = session('user_id');
        if (!$userId) return redirect('/login');

        $user = User::with(['customerProfile.addresses'])->find($userId);
        return view('customer.account', compact('user'));
    }

    public function shipperProfile()
    {
        $userId = session('user_id');
        if (!$userId) return redirect('/login');

        $shipper = ShipperProfile::with('user')->where('user_id', $userId)->first();
        if (!$shipper) return redirect('/login');

        return view('shipper.profile', compact('shipper'));
    }

    public function updateCustomer(Request $request)
    {
        $userId = session('user_id');
        if (!$userId) return redirect('/login');

        $request->validate([
            'full_name' => 'nullable|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $userId,
            'phone'     => ['nullable', 'string', 'max:20', 'regex:/^(84|0[3|5|7|8|9])[0-9]{8}$/', 'unique:users,phone,' . $userId],
            'birthday'  => 'nullable|date',
            'gender'    => 'nullable|in:male,female,other',
            'avatar'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'phone.regex' => 'Số điện thoại không hợp lệ.',
        ]);

        $user = User::find($userId);

        // Handle avatar upload
        $avatarPath = $user->avatar;
        if ($request->hasFile('avatar')) {
            // Delete old avatar if it's not an external URL
            if ($avatarPath && str_starts_with($avatarPath, 'avatars/')) {
                Storage::disk('public')->delete($avatarPath);
            }
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update([
            'full_name' => $request->full_name,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'birthday'  => $request->birthday,
            'gender'    => $request->gender,
            'avatar'    => $avatarPath,
        ]);

        // Ensure a customer profile exists (create if first time)
        CustomerProfile::firstOrCreate(['user_id' => $userId]);

        if ($request->wantsJson()) {
            session()->flash('success', 'Cập nhật hồ sơ thành công!');
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Cập nhật hồ sơ thành công!');
    }

    public function updateShipper(Request $request)
    {
        $userId = session('user_id');
        if (!$userId) return redirect('/login');

        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,'.$userId,
            'vehicle_type' => 'nullable|string|max:100',
            'license_plate' => 'nullable|string|max:20',
        ]);

        $user = User::find($userId);
        $user->update(['username' => $request->username]);

        ShipperProfile::updateOrCreate(
            ['user_id' => $userId],
            [
                'vehicle_type' => $request->vehicle_type,
                'license_plate' => $request->license_plate,
            ]
        );

        return back()->with('success', 'Cập nhật hồ sơ thành công!');
    }

    public function updatePassword(Request $request)
    {
        $userId = session('user_id');
        if (!$userId) return response()->json(['error' => 'Không có quyền truy cập.'], 401);

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = User::find($userId);
        
        if ($user->google_id) {
            return response()->json(['error' => 'Tài khoản đăng nhập bằng Google không thể đổi mật khẩu.'], 403);
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['error' => 'Mật khẩu hiện tại không đúng.'], 400);
        }

        $email = $user->email;

        // Rate limit send OTP
        if (RateLimiter::tooManyAttempts('send-otp-profile:'.$email, 1)) {
            return response()->json(['error' => 'Vui lòng đợi 1 phút trước khi gửi lại mã.'], 429);
        }
        RateLimiter::hit('send-otp-profile:'.$email, 60);

        $otp = (string) rand(100000, 999999);
        $pendingPassword = Hash::make($request->new_password);
        
        Cache::put('change_password_otp_'.$email, [
            'otp' => $otp,
            'password' => $pendingPassword
        ], now()->addMinutes(10));

        RateLimiter::clear('verify-otp-profile:'.$email);

        Mail::to($email)->queue(new OtpMail($otp, 'thay đổi mật khẩu'));

        return response()->json([
            'message' => 'Mã xác nhận đã được gửi đến email của bạn.',
            'require_otp' => true,
            'email' => $email
        ]);
    }

    public function verifyPasswordOtp(Request $request)
    {
        $userId = session('user_id');
        if (!$userId) return response()->json(['error' => 'Không có quyền truy cập.'], 401);

        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $user = User::find($userId);
        $email = $user->email;

        if (RateLimiter::tooManyAttempts('verify-otp-profile:'.$email, 5)) {
            return response()->json(['error' => 'Bạn đã nhập sai quá nhiều lần. Vui lòng thử lại sau 30 phút.'], 429);
        }

        $cached = Cache::get('change_password_otp_'.$email);
        if (!$cached) {
            return response()->json(['error' => 'Mã OTP đã hết hạn hoặc không tồn tại. Vui lòng thử lại.'], 400);
        }

        if ($cached['otp'] !== $request->otp) {
            RateLimiter::hit('verify-otp-profile:'.$email, 1800);
            $retriesLeft = RateLimiter::retriesLeft('verify-otp-profile:'.$email, 5);
            return response()->json(['error' => 'Mã OTP không đúng. Bạn còn '.$retriesLeft.' lần thử.'], 400);
        }

        $user->update(['password' => $cached['password']]);

        Cache::forget('change_password_otp_'.$email);
        RateLimiter::clear('verify-otp-profile:'.$email);

        return response()->json(['message' => 'Đổi mật khẩu thành công!']);
    }

    public function storeAddress(Request $request)
    {
        $userId = session('user_id');
        if (!$userId) return response()->json(['error' => 'Unauthorized'], 401);

        $request->validate([
            'receiver_name'  => 'required|string|max:100',
            'receiver_phone' => ['required', 'string', 'regex:/^(84|0[3|5|7|8|9])[0-9]{8}$/'],
            'province' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'ward'     => 'required|string|max:100',
            'address'  => 'required|string',
        ], [
            'receiver_phone.regex' => 'Số điện thoại không hợp lệ.',
        ]);

        $profile = CustomerProfile::firstOrCreate(['user_id' => $userId]);

        // If is_default, unset existing defaults
        if ($request->boolean('is_default')) {
            CustomerAddress::where('customer_id', $profile->id)->update(['is_default' => false]);
        }

        $address = CustomerAddress::create([
            'customer_id'    => $profile->id,
            'receiver_name'  => $request->receiver_name,
            'receiver_phone' => $request->receiver_phone,
            'province'       => $request->province,
            'district'       => $request->district,
            'ward'           => $request->ward,
            'address'        => $request->address,
            'note'           => $request->note,
            'is_default'     => $request->boolean('is_default'),
        ]);

        if ($request->wantsJson()) {
            session()->flash('success', 'Thêm địa chỉ thành công!');
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Thêm địa chỉ thành công!');
    }

    public function updateAddress(Request $request, $id)
    {
        $userId = session('user_id');
        if (!$userId) return response()->json(['error' => 'Unauthorized'], 401);

        $request->validate([
            'receiver_name'  => 'required|string|max:100',
            'receiver_phone' => ['required', 'string', 'regex:/^(84|0[3|5|7|8|9])[0-9]{8}$/'],
            'province' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'ward'     => 'required|string|max:100',
            'address'  => 'required|string',
        ], [
            'receiver_phone.regex' => 'Số điện thoại không hợp lệ.',
        ]);

        $profile = CustomerProfile::where('user_id', $userId)->first();
        if (!$profile) return back()->with('error', 'Không tìm thấy hồ sơ.');

        $addressModel = CustomerAddress::where('id', $id)->where('customer_id', $profile->id)->first();
        if (!$addressModel) return back()->with('error', 'Không tìm thấy địa chỉ.');

        // If is_default, unset existing defaults
        if ($request->boolean('is_default')) {
            CustomerAddress::where('customer_id', $profile->id)->update(['is_default' => false]);
        }

        $addressModel->update([
            'receiver_name'  => $request->receiver_name,
            'receiver_phone' => $request->receiver_phone,
            'province'    => $request->province,
            'district'    => $request->district,
            'ward'        => $request->ward,
            'address'     => $request->address,
            'note'        => $request->note,
            'is_default'  => $request->boolean('is_default'),
        ]);

        if ($request->wantsJson()) {
            session()->flash('success', 'Cập nhật địa chỉ thành công!');
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Cập nhật địa chỉ thành công!');
    }

    public function deleteAddress(Request $request, $addressId)
    {
        $userId = session('user_id');
        if (!$userId) return response()->json(['error' => 'Unauthorized'], 401);

        $profile = CustomerProfile::where('user_id', $userId)->first();
        if (!$profile) return back()->with('error', 'Không tìm thấy hồ sơ.');

        CustomerAddress::where('id', $addressId)
            ->where('customer_id', $profile->id)
            ->delete();

        return back()->with('success', 'Đã xóa địa chỉ.');
    }
}
