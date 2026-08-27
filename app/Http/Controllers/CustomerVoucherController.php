<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerVoucherController extends Controller
{
    /**
     * View the list of saved vouchers
     */
    public function myVouchers()
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect('/')->with('error', 'Vui lòng đăng nhập để xem mã giảm giá của bạn.');
        }

        $user = \App\Models\User::find($userId);

        // Get saved vouchers that are active and not expired yet
        $vouchers = $user->savedVouchers()
            ->orderBy('customer_vouchers.created_at', 'desc')
            ->get();

        return view('customer.my_vouchers', compact('vouchers'));
    }

    /**
     * Save a voucher for the authenticated user
     */
    public function saveVoucher(Request $request)
    {
        $userId = session('user_id');
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập để lưu mã giảm giá.'], 401);
        }

        $request->validate([
            'voucher_id' => 'required|exists:vouchers,id',
        ]);

        $user = \App\Models\User::find($userId);
        $voucherId = $request->voucher_id;
        $voucher = \App\Models\Voucher::findOrFail($voucherId);

        if (is_array($voucher->target_audience) && !in_array('all', $voucher->target_audience)) {
            $profile = \App\Models\CustomerProfile::where('user_id', $userId)->first();
            $userRank = $profile ? $profile->dynamic_rank['level'] : 'Member';
            
            if (!in_array($userRank, $voucher->target_audience)) {
                $rankNames = [
                    'Member' => 'Thành viên',
                    'Silver' => 'Hạng Bạc',
                    'Gold' => 'Hạng Vàng',
                    'Diamond' => 'Hạng Kim Cương'
                ];
                
                $allowedRanks = array_map(function($r) use ($rankNames) {
                    return $rankNames[$r] ?? $r;
                }, $voucher->target_audience);
                
                $targetName = implode(', ', $allowedRanks);
                return response()->json(['success' => false, 'message' => "Mã giảm giá này chỉ dành riêng cho khách hàng: $targetName."]);
            }
        }

        // Check if the voucher is already saved
        if ($user->savedVouchers()->where('voucher_id', $voucherId)->exists()) {
            return response()->json(['success' => false, 'message' => 'Bạn đã lưu mã giảm giá này rồi.']);
        }

        // Attach the voucher
        $user->savedVouchers()->attach($voucherId);

        return response()->json(['success' => true, 'message' => 'Đã lưu mã giảm giá thành công!']);
    }
}
