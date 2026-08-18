<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index(Request $request)
    {
        $query = Voucher::orderBy('created_at', 'desc');
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }
        
        $vouchers = $query->paginate(10)->withQueryString();
        
        $totalVouchers = Voucher::count();
        $activeVouchers = Voucher::where('status', true)->count();
        $totalRedeemed = Voucher::sum('used');
        $revenueImpact = 0; // Temporarily 0 or calculate from orders if needed

        return view('admin.voucher', compact('vouchers', 'totalVouchers', 'activeVouchers', 'totalRedeemed', 'revenueImpact'));
    }

    public function create()
    {
        return view('admin.add_voucher');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:vouchers',
            'name' => 'required|string|max:255',
            'discount_type' => 'required|in:percent,fixed',
            'discount_value' => [
                'required',
                'numeric',
                'min:0',
                'max:99999999',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->discount_type === 'percent' && $value > 100) {
                        $fail('Mức giảm phần trăm không được lớn hơn 100.');
                    }
                },
            ],
            'minimum_order' => 'required|numeric|min:0|max:99999999',
            'quantity' => 'required|integer|min:1',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $data = $request->all();
        $data['status'] = $request->has('status');
        $data['is_one_time_use'] = $request->has('is_one_time_use');

        Voucher::create($data);

        return redirect('/admin/voucher')->with('success', 'Tạo voucher thành công.');
    }

    public function edit(Voucher $voucher)
    {
        return view('admin.edit_voucher', compact('voucher'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:vouchers,code,' . $voucher->id,
            'name' => 'required|string|max:255',
            'discount_type' => 'required|in:percent,fixed',
            'discount_value' => [
                'required',
                'numeric',
                'min:0',
                'max:99999999',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->discount_type === 'percent' && $value > 100) {
                        $fail('Mức giảm phần trăm không được lớn hơn 100.');
                    }
                },
            ],
            'minimum_order' => 'required|numeric|min:0|max:99999999',
            'quantity' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $data = $request->all();
        $data['status'] = $request->has('status');
        $data['is_one_time_use'] = $request->has('is_one_time_use');

        $voucher->update($data);

        return redirect('/admin/voucher')->with('success', 'Cập nhật voucher thành công.');
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();
        return redirect('/admin/voucher')->with('success', 'Xóa voucher thành công.');
    }
}
