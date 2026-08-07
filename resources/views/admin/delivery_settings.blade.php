@extends('layouts.admin')

@section('title', 'Quản lý Giao hàng')

@section('content')
<main class="ml-[280px] pt-16 min-h-screen">
    <div class="p-xl space-y-xl max-w-[800px] mx-auto">
        <div class="flex items-center justify-between mb-lg">
            <h1 class="text-on-surface font-display-sm text-display-sm">Thiết lập Giao hàng</h1>
        </div>

        @if(session('success'))
        <div class="p-md bg-green-100 text-green-800 rounded-xl mb-md">
            {{ session('success') }}
        </div>
        @endif

        <form action="/admin/delivery-settings" method="POST" class="bg-white p-lg rounded-2xl border border-outline-variant shadow-sm space-y-md">
            @csrf
            
            <div class="space-y-md">
                <label class="block font-label-md text-label-md text-on-surface-variant mb-xs">Địa chỉ cửa hàng *</label>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-md">
                    <div>
                        <select id="province_select" name="store_province" required class="w-full p-md rounded-xl bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 text-body-md transition-colors" data-selected="{{ old('store_province', $settings['store_province'] ?? '') }}">
                            <option value="">Chọn Tỉnh/Thành phố</option>
                        </select>
                    </div>
                    <div>
                        <select id="district_select" name="store_district" required disabled class="w-full p-md rounded-xl bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 text-body-md transition-colors" data-selected="{{ old('store_district', $settings['store_district'] ?? '') }}">
                            <option value="">Chọn Quận/Huyện</option>
                        </select>
                    </div>
                    <div>
                        <select id="ward_select" name="store_ward" required disabled class="w-full p-md rounded-xl bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 text-body-md transition-colors" data-selected="{{ old('store_ward', $settings['store_ward'] ?? '') }}">
                            <option value="">Chọn Phường/Xã</option>
                        </select>
                    </div>
                </div>
                
                <input type="text" name="store_specific_address" value="{{ old('store_specific_address', $settings['store_specific_address'] ?? '') }}" required
                       class="w-full p-md rounded-xl bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 text-body-md mt-sm"
                       placeholder="Ví dụ: Số 123, Đường XYZ">
                <p class="text-xs text-on-surface-variant mt-1">Được dùng làm điểm xuất phát để tính khoảng cách giao hàng nếu không có tọa độ cụ thể.</p>
            </div>

            <div class="space-y-md">
                <div class="flex items-center justify-between mb-xs">
                    <label class="block font-label-md text-label-md text-on-surface-variant">Tọa độ cửa hàng (Tùy chọn nhưng khuyên dùng)</label>
                    <button type="button" onclick="getCurrentLocation()" class="text-primary hover:text-primary/80 font-label-sm text-sm flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px]">my_location</span>
                        Lấy tọa độ hiện tại
                    </button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                    <div>
                        <input type="text" id="store_lat" name="store_lat" value="{{ old('store_lat', $settings['store_lat'] ?? '') }}" 
                               class="w-full p-md rounded-xl bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 text-body-md"
                               placeholder="Vĩ độ (Latitude) - VD: 20.5592">
                    </div>
                    <div>
                        <input type="text" id="store_lon" name="store_lon" value="{{ old('store_lon', $settings['store_lon'] ?? '') }}" 
                               class="w-full p-md rounded-xl bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 text-body-md"
                               placeholder="Kinh độ (Longitude) - VD: 105.8824">
                    </div>
                </div>
                <p class="text-xs text-on-surface-variant mt-1">Hệ thống bản đồ miễn phí có thể không tìm chính xác địa chỉ xã/huyện của bạn, dẫn đến sai số lớn khi tính phí ship. Việc cung cấp tọa độ này sẽ giúp tính phí ship chính xác tuyệt đối.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                <div>
                    <label class="block font-label-md text-label-md text-on-surface-variant mb-xs">Phí giao hàng / km (VNĐ) *</label>
                    <input type="number" name="fee_per_km" value="{{ old('fee_per_km', $settings['fee_per_km'] ?? 0) }}" required min="0"
                           class="w-full p-md rounded-xl bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 text-body-md"
                           placeholder="Ví dụ: 5000">
                    @error('fee_per_km')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                
                <div>
                    <label class="block font-label-md text-label-md text-on-surface-variant mb-xs">Bán kính tối đa (km) *</label>
                    <input type="number" name="max_delivery_radius" value="{{ old('max_delivery_radius', $settings['max_delivery_radius'] ?? 0) }}" required min="0"
                           class="w-full p-md rounded-xl bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 text-body-md"
                           placeholder="Ví dụ: 10">
                    <p class="text-xs text-on-surface-variant mt-1">Hệ thống sẽ từ chối đơn xa hơn mức này.</p>
                    @error('max_delivery_radius')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block font-label-md text-label-md text-on-surface-variant mb-xs">Đơn hàng tối thiểu để giao (VNĐ) *</label>
                <input type="number" name="min_order_amount" value="{{ old('min_order_amount', $settings['min_order_amount'] ?? 0) }}" required min="0"
                       class="w-full p-md rounded-xl bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 text-body-md"
                       placeholder="Ví dụ: 300000">
                <p class="text-xs text-on-surface-variant mt-1">Đơn hàng phải có tổng tiền bằng hoặc lớn hơn mức này mới được đặt.</p>
                @error('min_order_amount')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="pt-md border-t border-outline-variant">
                <button type="submit" class="px-xl py-md bg-primary text-on-primary font-label-lg text-label-lg rounded-full hover:bg-primary/90 transition-colors">
                    Lưu cài đặt
                </button>
            </div>
        </form>
    </div>
</main>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<style>
    .ts-control {
        min-height: 48px;
        padding: 10px 12px;
        border-radius: 0.75rem;
        border: 1px solid rgba(121, 116, 126, 0.3);
        background-color: #f7f2fa;
    }
    .ts-control.focus {
        border-color: #6750a4;
        box-shadow: none;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tsOptions = {
        create: false,
        sortField: { field: "text", direction: "asc" },
        placeholder: 'Chọn...',
        maxOptions: null
    };

    const tsProvince = new TomSelect('#province_select', tsOptions);
    const tsDistrict = new TomSelect('#district_select', tsOptions);
    const tsWard = new TomSelect('#ward_select', tsOptions);

    const initialProvince = document.getElementById('province_select').getAttribute('data-selected');
    const initialDistrict = document.getElementById('district_select').getAttribute('data-selected');
    const initialWard = document.getElementById('ward_select').getAttribute('data-selected');

    // Load Provinces
    fetch('https://provinces.open-api.vn/api/p/')
        .then(res => res.json())
        .then(data => {
            const options = data.map(p => ({value: p.name, text: p.name, code: p.code}));
            tsProvince.addOptions(options);
            tsProvince.refreshOptions(false);
            
            if (initialProvince) {
                tsProvince.setValue(initialProvince);
            }
        });

    tsProvince.on('change', function(value) {
        tsDistrict.clearOptions();
        tsDistrict.clear();
        tsWard.clearOptions();
        tsWard.clear();
        tsWard.disable();
        
        if (value) {
            const option = tsProvince.options[value];
            if (option && option.code) {
                tsDistrict.enable();
                tsDistrict.addOption({value: '', text: 'Đang tải...'});
                fetch(`https://provinces.open-api.vn/api/p/${option.code}?depth=2`)
                    .then(res => res.json())
                    .then(data => {
                        tsDistrict.clearOptions();
                        const options = data.districts.map(d => ({value: d.name, text: d.name, code: d.code}));
                        tsDistrict.addOptions(options);
                        tsDistrict.refreshOptions(false);
                        
                        if (initialDistrict && tsProvince.getValue() === initialProvince) {
                            tsDistrict.setValue(initialDistrict);
                        }
                    });
            } else {
                tsDistrict.disable();
            }
        } else {
            tsDistrict.disable();
        }
    });

    tsDistrict.on('change', function(value) {
        tsWard.clearOptions();
        tsWard.clear();
        
        if (value) {
            const option = tsDistrict.options[value];
            if (option && option.code) {
                tsWard.enable();
                tsWard.addOption({value: '', text: 'Đang tải...'});
                fetch(`https://provinces.open-api.vn/api/d/${option.code}?depth=2`)
                    .then(res => res.json())
                    .then(data => {
                        tsWard.clearOptions();
                        const options = data.wards.map(w => ({value: w.name, text: w.name, code: w.code}));
                        tsWard.addOptions(options);
                        tsWard.refreshOptions(false);
                        
                        if (initialWard && tsDistrict.getValue() === initialDistrict) {
                            tsWard.setValue(initialWard);
                        }
                    });
            } else {
                tsWard.disable();
            }
        } else {
            tsWard.disable();
        }
    });

    function getCurrentLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lon = position.coords.longitude;
                document.getElementById('store_lat').value = lat;
                document.getElementById('store_lon').value = lon;
            }, function() {
                alert("Không thể lấy vị trí của bạn.");
            });
        } else {
            alert("Trình duyệt của bạn không hỗ trợ lấy vị trí.");
        }
    }
});
</script>
@endpush
@endsection
