<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CustomerProfile;
use App\Models\ShipperProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function customerAccount()
    {
        $userId = session('user_id');
        if (!$userId) return redirect('/login');

        $user = User::with('customerProfile')->find($userId);
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
            'email' => 'required|email|unique:users,email,'.$userId,
            'phone' => 'required|string|max:20|unique:users,phone,'.$userId,
            'birthday' => 'nullable|date',
        ]);

        $user = User::find($userId);
        $user->update([
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        CustomerProfile::updateOrCreate(
            ['user_id' => $userId],
            [
                'full_name' => $request->full_name,
                'birthday' => $request->birthday,
            ]
        );

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
}
