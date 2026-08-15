<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CustomerProfile;
use App\Models\CustomerAddress;
use App\Models\ShipperProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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
            'phone'     => 'nullable|string|max:20|unique:users,phone,' . $userId,
            'birthday'  => 'nullable|date',
            'gender'    => 'nullable|in:male,female,other',
            'avatar'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
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
        if (!$userId) return back()->with('error', 'Không có quyền truy cập.');

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = User::find($userId);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Mật khẩu hiện tại không đúng.');
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Đổi mật khẩu thành công!');
    }

    public function storeAddress(Request $request)
    {
        $userId = session('user_id');
        if (!$userId) return response()->json(['error' => 'Unauthorized'], 401);

        $request->validate([
            'province' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'ward'     => 'required|string|max:100',
            'address'  => 'required|string',
        ]);

        $profile = CustomerProfile::firstOrCreate(['user_id' => $userId]);

        // If is_default, unset existing defaults
        if ($request->boolean('is_default')) {
            CustomerAddress::where('customer_id', $profile->id)->update(['is_default' => false]);
        }

        $address = CustomerAddress::create([
            'customer_id' => $profile->id,
            'province'    => $request->province,
            'district'    => $request->district,
            'ward'        => $request->ward,
            'address'     => $request->address,
            'note'        => $request->note,
            'is_default'  => $request->boolean('is_default'),
        ]);

        return back()->with('success', 'Thêm địa chỉ thành công!');
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
