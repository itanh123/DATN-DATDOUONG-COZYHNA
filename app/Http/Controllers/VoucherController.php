<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::orderBy('created_at', 'desc')->paginate(10);
        
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
            'discount_value' => 'required|numeric|min:0',
            'minimum_order' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $data = $request->all();
        $data['status'] = $request->has('status');

        Voucher::create($data);

        return redirect('/admin/voucher')->with('success', 'Voucher created successfully.');
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
            'discount_value' => 'required|numeric|min:0',
            'minimum_order' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $data = $request->all();
        $data['status'] = $request->has('status');

        $voucher->update($data);

        return redirect('/admin/voucher')->with('success', 'Voucher updated successfully.');
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();
        return redirect('/admin/voucher')->with('success', 'Voucher deleted successfully.');
    }
}
