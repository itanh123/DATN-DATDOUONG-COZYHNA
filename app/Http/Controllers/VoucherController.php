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
        $products = \App\Models\Product::where('status', 1)->get();
        return view('admin.add_voucher', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:vouchers',
            'name' => 'required|string|max:255',
            'discount_type' => 'required|in:percent,fixed',
            'discount_target' => 'required|in:order,shipping,product',
            'discount_value' => 'required|numeric|min:0',
            'minimum_order' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'product_ids' => 'nullable|array',
        ]);

        $data = $request->except(['product_ids']);
        $data['status'] = $request->has('status');
        $data['is_hot'] = $request->has('is_hot');

        $voucher = Voucher::create($data);

        if ($request->discount_target === 'product' && $request->has('product_ids')) {
            $voucher->products()->sync($request->product_ids);
        }

        return redirect('/admin/voucher')->with('success', 'Voucher created successfully.');
    }

    public function edit(Voucher $voucher)
    {
        $products = \App\Models\Product::where('status', 1)->get();
        $selectedProductIds = $voucher->products()->pluck('products.id')->toArray();
        return view('admin.edit_voucher', compact('voucher', 'products', 'selectedProductIds'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:vouchers,code,' . $voucher->id,
            'name' => 'required|string|max:255',
            'discount_type' => 'required|in:percent,fixed',
            'discount_target' => 'required|in:order,shipping,product',
            'discount_value' => 'required|numeric|min:0',
            'minimum_order' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'product_ids' => 'nullable|array',
        ]);

        $data = $request->except(['product_ids']);
        $data['status'] = $request->has('status');
        $data['is_hot'] = $request->has('is_hot');

        // Lấy ngày hết hạn cũ để so sánh
        $oldEndDate = $voucher->end_date ? \Carbon\Carbon::parse($voucher->end_date)->format('Y-m-d') : null;

        $voucher->update($data);

        // Kiểm tra nếu hạn sử dụng bị thay đổi
        $newEndDate = $voucher->end_date ? \Carbon\Carbon::parse($voucher->end_date)->format('Y-m-d') : null;
        if ($oldEndDate !== $newEndDate) {
            $userIds = \Illuminate\Support\Facades\DB::table('user_vouchers')
                ->where('voucher_id', $voucher->id)
                ->pluck('user_id');
            
            $dateStr = $newEndDate ? \Carbon\Carbon::parse($newEndDate)->format('d/m/Y') : 'Không giới hạn';
            foreach ($userIds as $userId) {
                \App\Models\Notification::create([
                    'user_id' => $userId,
                    'title' => 'Cập nhật hạn sử dụng mã giảm giá',
                    'content' => 'Mã giảm giá "' . $voucher->name . '" (' . $voucher->code . ') vừa được cập nhật hạn sử dụng mới: ' . $dateStr . '. Hãy sử dụng ngay nhé!',
                    'type' => 'system', // Dùng system hoặc voucher nếu có
                    'is_read' => false,
                ]);
            }
        }

        if ($request->discount_target === 'product' && $request->has('product_ids')) {
            $voucher->products()->sync($request->product_ids);
        } else {
            $voucher->products()->detach();
        }

        return redirect('/admin/voucher')->with('success', 'Voucher updated successfully.');
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();
        return redirect('/admin/voucher')->with('success', 'Voucher deleted successfully.');
    }
}
