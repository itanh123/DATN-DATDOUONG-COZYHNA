<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class DeliverySettingController extends Controller
{
    public function index()
    {
        $settings = [
            'store_province' => Setting::get('store_province', ''),
            'store_district' => Setting::get('store_district', ''),
            'store_ward' => Setting::get('store_ward', ''),
            'store_specific_address' => Setting::get('store_specific_address', ''),
            'fee_per_km' => Setting::get('fee_per_km', 0),
            'max_delivery_radius' => Setting::get('max_delivery_radius', 0),
            'min_order_amount' => Setting::get('min_order_amount', 0),
            'store_lat' => Setting::get('store_lat', ''),
            'store_lon' => Setting::get('store_lon', ''),
            'shipping_tiers' => Setting::get('shipping_tiers', '[{"max_km": 3, "fee": 15000}, {"max_km": 5, "fee": 20000}, {"max_km": 10, "fee": 30000}, {"max_km": 15, "fee": 40000}]'),
        ];

        return view('admin.delivery_settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'store_province' => 'required|string|max:255',
            'store_district' => 'required|string|max:255',
            'store_ward' => 'required|string|max:255',
            'store_specific_address' => 'required|string|max:255',
            'fee_per_km' => 'required|numeric|min:0',
            'max_delivery_radius' => 'required|numeric|min:0',
            'min_order_amount' => 'required|numeric|min:0',
            'store_lat' => 'nullable|string',
            'store_lon' => 'nullable|string',
            'shipping_tiers' => 'nullable|string', // JSON string from frontend
        ]);

        $fullAddress = $request->store_specific_address . ', ' . $request->store_ward . ', ' . $request->store_district . ', ' . $request->store_province;

        Setting::updateOrCreate(['key' => 'store_province'], ['value' => $request->store_province]);
        Setting::updateOrCreate(['key' => 'store_district'], ['value' => $request->store_district]);
        Setting::updateOrCreate(['key' => 'store_ward'], ['value' => $request->store_ward]);
        Setting::updateOrCreate(['key' => 'store_specific_address'], ['value' => $request->store_specific_address]);
        Setting::updateOrCreate(['key' => 'store_address'], ['value' => $fullAddress]);
        
        Setting::updateOrCreate(['key' => 'fee_per_km'], ['value' => $request->fee_per_km]);
        Setting::updateOrCreate(['key' => 'max_delivery_radius'], ['value' => $request->max_delivery_radius]);
        Setting::updateOrCreate(['key' => 'min_order_amount'], ['value' => $request->min_order_amount]);
        
        // Validate JSON before saving
        if ($request->filled('shipping_tiers')) {
            $decoded = json_decode($request->shipping_tiers, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                Setting::updateOrCreate(['key' => 'shipping_tiers'], ['value' => $request->shipping_tiers]);
            }
        }
        
        if ($request->filled('store_lat') && $request->filled('store_lon')) {
            Setting::updateOrCreate(['key' => 'store_lat'], ['value' => $request->store_lat]);
            Setting::updateOrCreate(['key' => 'store_lon'], ['value' => $request->store_lon]);
        } else {
            Setting::whereIn('key', ['store_lat', 'store_lon'])->delete();
        }

        return redirect()->back()->with('success', __('Đã cập nhật cài đặt giao hàng thành công!'));
    }
}
