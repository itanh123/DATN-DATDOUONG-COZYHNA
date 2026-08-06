@extends('layouts.customer')

@section('title', 'Thanh Toán')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet" />
<style>
    .ts-control {
        min-height: 48px;
        padding: 10px 12px;
        border-radius: 0.75rem;
        border: 1px solid rgba(121, 116, 126, 0.2);
        background-color: rgb(247, 243, 249);
        font-family: inherit;
        font-size: 14px;
    }
    .ts-control > input {
        font-size: 14px;
    }
    .ts-wrapper.single .ts-control:after {
        right: 15px;
    }
</style>

<main class="mt-24 pb-24 max-w-container-max mx-auto px-4 md:px-lg">

{{-- Flash messages --}}
@if(session('error'))
    <div class="mb-md p-md bg-red-100 text-red-700 rounded-xl font-body-md">{{ session('error') }}</div>
@endif

<div class="mb-xl">
    <h1 class="font-headline-lg text-headline-lg text-on-background">Thanh Toán</h1>
    <p class="font-body-md text-body-md text-on-surface-variant">Kiểm tra đơn hàng và hoàn tất thanh toán.</p>
</div>


<form method="POST" action="{{ route('orders.place') }}">
@csrf
<div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter items-start">

    {{-- Left Column --}}
    <div class="lg:col-span-8 space-y-lg">

        {{-- Cart Items --}}
        <section class="bg-surface-container-lowest rounded-xl p-lg shadow-sm border border-outline-variant/10">
            <div class="flex items-center justify-between mb-md">
                <h2 class="font-title-lg text-title-lg flex items-center gap-xs">
                    <span class="material-symbols-outlined text-primary">shopping_basket</span>
                    Giỏ hàng của bạn
                </h2>
                <span class="font-label-md text-label-md text-on-surface-variant">{{ $cartItems->count() }} sản phẩm</span>
            </div>
            <div class="divide-y divide-outline-variant/20">
                @foreach($cartItems as $item)
                @php
                    // Support both sized and no-size products
                    $product = $item->productSize->product ?? $item->product;
                    $size    = $item->productSize->size ?? null;
                    $price   = $item->unit_price;  // always use stored unit_price
                @endphp
                <div class="py-md flex items-center gap-md">
                    <div class="w-20 h-20 rounded-lg overflow-hidden flex-shrink-0 bg-surface-container">
                        @if($product && $product->image)
                            <img class="w-full h-full object-cover" src="{{ $product->image }}" alt="{{ $product->name }}"/>
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-outline-variant text-[36px]">local_cafe</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex-grow">
                        <h3 class="font-body-lg text-body-lg font-semibold">{{ $product->name ?? 'Sản phẩm' }}</h3>
                        <p class="font-label-md text-label-md text-on-surface-variant">Size: {{ $size->name ?? 'Mặc định' }}</p>
                        @if(isset($item->toppings) && count($item->toppings) > 0)
                            <div class="mt-1">
                                @foreach($item->toppings as $topping)
                                    <p class="font-label-sm text-label-sm text-on-surface-variant">+ {{ $topping['name'] }}</p>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="font-body-lg text-body-lg font-bold text-primary">{{ number_format($price * $item->quantity, 0, ',', '.') }} đ</p>
                        <p class="font-label-md text-label-md text-on-surface-variant">SL: {{ $item->quantity }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-md text-right">
                <a href="{{ route('cart.index') }}" class="text-primary font-label-md hover:underline flex items-center justify-end gap-1"><span class="material-symbols-outlined text-[18px]">arrow_back</span> Quay lại giỏ hàng</a>
            </div>
        </section>

        {{-- Delivery Info Form --}}
        <section class="bg-surface-container-lowest rounded-xl p-lg shadow-sm border border-outline-variant/10">
            <h2 class="font-title-lg text-title-lg flex items-center gap-xs mb-md">
                <span class="material-symbols-outlined text-primary">location_on</span>
                Thông tin giao hàng
            </h2>

            @php $defaultAddr = $addresses->firstWhere('is_default', true) ?? $addresses->first(); @endphp

            @if(count($addresses) > 0)
            <div class="mb-md">
                <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-xs block">Chọn địa chỉ đã lưu</label>
                <select id="saved_address_select" class="w-full p-md rounded-xl bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 text-body-md transition-colors">
                    <option value="">-- Nhập địa chỉ mới --</option>
                    @foreach($addresses as $addr)
                        <option value="{{ $addr->id }}" 
                                data-name="{{ $addr->receiver_name }}" 
                                data-phone="{{ $addr->receiver_phone }}"
                                data-province="{{ $addr->province }}"
                                data-district="{{ $addr->district }}"
                                data-ward="{{ $addr->ward }}"
                                data-address="{{ $addr->address }}">{{ $addr->receiver_name }} - {{ $addr->receiver_phone }} ({{ $addr->address }}, {{ $addr->ward }}, {{ $addr->district }}, {{ $addr->province }})</option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                <div>
                    <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-xs block">Tên người nhận *</label>
                    <input type="text" id="receiver_name" name="receiver_name" required
                        value="{{ old('receiver_name', optional($defaultAddr)->receiver_name ?? '') }}"
                        class="w-full p-md rounded-xl bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 text-body-md transition-colors"
                        placeholder="Nhập tên người nhận"/>
                </div>
                <div>
                    <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-xs block">Số điện thoại *</label>
                    <input type="text" id="receiver_phone" name="receiver_phone" required
                        value="{{ old('receiver_phone', optional($defaultAddr)->receiver_phone ?? '') }}"
                        class="w-full p-md rounded-xl bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 text-body-md transition-colors"
                        placeholder="Số điện thoại"/>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-md mt-md">
                <div>
                    <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-xs block">Tỉnh/Thành phố *</label>
                    <select id="province_select" name="province" required class="w-full p-md rounded-xl bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 text-body-md transition-colors">
                        <option value="">Chọn Tỉnh/Thành phố</option>
                    </select>
                </div>
                <div>
                    <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-xs block">Quận/Huyện *</label>
                    <select id="district_select" name="district" required disabled class="w-full p-md rounded-xl bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 text-body-md transition-colors">
                        <option value="">Chọn Quận/Huyện</option>
                    </select>
                </div>
                <div>
                    <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-xs block">Phường/Xã *</label>
                    <select id="ward_select" name="ward" required disabled class="w-full p-md rounded-xl bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 text-body-md transition-colors">
                        <option value="">Chọn Phường/Xã</option>
                    </select>
                </div>
            </div>

            <div class="mt-md">
                <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-xs block">Địa chỉ cụ thể *</label>
                <input type="text" id="specific_address" name="address" required
                    value="{{ old('address', optional($defaultAddr)->address ?? '') }}"
                    class="w-full p-md rounded-xl bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 text-body-md transition-colors"
                    placeholder="Số nhà, thôn, ngõ, ngách..."/>
            </div>

            <div class="mt-sm flex items-center gap-xs">
                <input type="checkbox" id="save_address" name="save_address" value="1" class="w-4 h-4 text-primary border-outline focus:ring-primary rounded">
                <label for="save_address" class="font-label-md text-label-md text-on-surface-variant cursor-pointer select-none">Lưu thông tin giao hàng cho lần sau</label>
            </div>

            <div class="mt-md">
                <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-xs block">Ghi chú (tuỳ chọn)</label>
                <textarea name="note" rows="2"
                    class="w-full p-md rounded-xl bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 text-body-md transition-colors"
                    placeholder="Ví dụ: Giao giờ hành chính, gọi trước khi giao...">{{ old('note') }}</textarea>
            </div>
        </section>

        {{-- Payment Method --}}
        <section class="bg-surface-container-lowest rounded-xl p-lg shadow-sm border border-outline-variant/10">
            <h2 class="font-title-lg text-title-lg flex items-center gap-xs mb-md">
                <span class="material-symbols-outlined text-primary">payments</span>
                Phương thức thanh toán
            </h2>
            <div class="space-y-sm">
                <label class="flex items-center gap-md p-md rounded-xl border border-outline-variant/30 cursor-pointer hover:bg-surface-container-high transition-all has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                    <input checked name="payment_method" type="radio" value="vietqr" class="w-5 h-5 text-primary border-outline focus:ring-primary"/>
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xs flex-shrink-0">
                        <span class="material-symbols-outlined text-lg">qr_code_2</span>
                    </div>
                    <div class="flex-grow">
                        <div class="flex items-center gap-2">
                            <span class="font-body-lg text-body-lg font-bold text-emerald-800">Chuyển khoản VietQR / Ngân hàng</span>
                            <span class="text-[10px] px-2 py-0.5 bg-emerald-600 text-white font-bold rounded-full uppercase tracking-wider">Khuyên dùng</span>
                        </div>
                        <p class="font-label-md text-label-md text-on-surface-variant">Tự động tạo mã VietQR khớp số tiền & nội dung chuyển khoản</p>
                    </div>
                </label>
                <label class="flex items-center gap-md p-md rounded-xl border border-outline-variant/30 cursor-pointer hover:bg-surface-container-high transition-all has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                    <input name="payment_method" type="radio" value="cash" class="w-5 h-5 text-primary border-outline focus:ring-primary"/>
                    <span class="material-symbols-outlined text-on-surface-variant">payments</span>
                    <div class="flex-grow">
                        <span class="font-body-lg text-body-lg font-medium">Tiền mặt khi nhận hàng (COD)</span>
                        <p class="font-label-md text-label-md text-on-surface-variant">Thanh toán bằng tiền mặt khi shipper giao tới</p>
                    </div>
                </label>
                <label class="flex items-center gap-md p-md rounded-xl border border-outline-variant/30 cursor-pointer hover:bg-surface-container-high transition-all has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                    <input name="payment_method" type="radio" value="momo" class="w-5 h-5 text-primary border-outline focus:ring-primary"/>
                    <span class="material-symbols-outlined text-pink-600">phone_iphone</span>
                    <div class="flex-grow">
                        <span class="font-body-lg text-body-lg font-medium">Ví MoMo / QR MoMo</span>
                        <p class="font-label-md text-label-md text-on-surface-variant">Quét mã QR qua ứng dụng Ví MoMo</p>
                    </div>
                </label>
            </div>
        </section>
    </div>

    {{-- Right Column: Sticky Summary --}}
    <aside class="lg:col-span-4 lg:sticky lg:top-24 space-y-md">
        <div class="bg-surface-container-lowest rounded-xl p-lg shadow-sm border border-outline-variant/10">
            <h2 class="font-title-lg text-title-lg mb-md">Tóm tắt đơn hàng</h2>
            <div class="space-y-sm mb-lg">
                <div class="flex justify-between font-body-md text-body-md text-on-surface-variant">
                    <span>Tạm tính</span>
                    <span>{{ number_format($subtotal, 0, ',', '.') }} đ</span>
                </div>
                <div class="flex justify-between font-body-md text-body-md text-on-surface-variant">
                    <span>Khoảng cách</span>
                    <span id="display_distance">0 km</span>
                </div>
                <div class="flex justify-between font-body-md text-body-md text-on-surface-variant">
                    <span>Phí giao hàng</span>
                    <span id="display_shipping_fee">0 đ</span>
                </div>

                @if(isset($discountAmount) && $discountAmount > 0)
                <div class="flex justify-between font-body-md text-body-md text-error">
                    <span>Giảm giá (Voucher)</span>
                    <span id="display_discount">-{{ number_format($discountAmount, 0, ',', '.') }} đ</span>
                </div>
                @endif
                <div class="pt-sm border-t border-outline-variant/20">
                    <div class="flex justify-between font-headline-md text-headline-md text-on-background">
                        <span>Tổng cộng</span>
                        <span class="text-primary" id="display_total">{{ number_format($subtotal - ($discountAmount ?? 0), 0, ',', '.') }} đ</span>
                    </div>
                </div>
            </div>

            <!-- Hidden fields for backend -->
            <input type="hidden" name="distance_km" id="input_distance_km" value="0">
            <input type="hidden" name="shipping_fee" id="input_shipping_fee" value="0">

            <button type="submit" class="w-full py-md bg-primary text-on-primary font-headline-md text-headline-md rounded-xl hover:shadow-lg active:scale-[0.98] transition-all flex items-center justify-center gap-sm">
                Xác nhận đặt hàng
                <span class="material-symbols-outlined">arrow_forward</span>
            </button>
            <p class="mt-md text-center font-label-md text-label-md text-on-surface-variant">
                Bằng cách đặt hàng, bạn đồng ý với <a class="underline" href="#">Điều khoản dịch vụ</a> của CozyHNA.
            </p>
        </div>

        <div class="p-md rounded-xl bg-secondary-container/20 flex items-center gap-md border border-secondary-container/30">
            <span class="material-symbols-outlined text-secondary" style="font-variation-settings: 'FILL' 1;">eco</span>
            <div>
                <p class="font-label-md text-label-md font-bold text-secondary">Giao hàng thân thiện</p>
                <p class="font-label-sm text-label-sm text-on-secondary-container">Đóng gói bằng vật liệu tái chế thân thiện môi trường.</p>
            </div>
        </div>
    </aside>
</div>
</form>

</main>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize TomSelect for all dropdowns
    const tsOptions = {
        create: false,
        sortField: {field: "text", direction: "asc"},
        placeholder: 'Chọn...',
    };
    
    const tsSaved = document.getElementById('saved_address_select') ? new TomSelect('#saved_address_select', {
        create: false, placeholder: '-- Nhập địa chỉ mới --'
    }) : null;
    
    const tsProvince = new TomSelect('#province_select', tsOptions);
    const tsDistrict = new TomSelect('#district_select', tsOptions);
    const tsWard = new TomSelect('#ward_select', tsOptions);

    // Load Provinces
    fetch('https://provinces.open-api.vn/api/p/')
        .then(res => res.json())
        .then(data => {
            const options = data.map(p => ({value: p.name, text: p.name, code: p.code}));
            tsProvince.addOptions(options);
            tsProvince.refreshOptions(false);
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
                    });
            } else {
                tsWard.disable();
            }
        } else {
            tsWard.disable();
        }
        calculateMockShipping();
    });

    tsWard.on('change', function(value) {
        calculateMockShipping();
    });

    // Mock Distance Calculation
    // OSRM Distance Calculation
    let storeCoords = null;

    async function geocode(address) {
        try {
            console.log("Geocoding:", address);
            const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}&limit=1&email=contact@cozyhna.com`;
            const response = await fetch(url, { headers: { 'Accept-Language': 'vi' } });
            const data = await response.json();
            console.log("Nominatim response for", address, ":", data);
            if (data && data.length > 0) {
                return { lat: data[0].lat, lon: data[0].lon };
            }
            return null;
        } catch (e) {
            console.error('Geocode error:', e);
            return null;
        }
    }

    async function getCoordinatesWithFallback(specific, ward, district, province) {
        if (specific) {
            let coords = await geocode(`${specific}, ${ward}, ${district}, ${province}`);
            if (coords) return coords;
        }
        
        let coords = await geocode(`${ward}, ${district}, ${province}`);
        if (coords) return coords;
        
        coords = await geocode(`${district}, ${province}`);
        if (coords) return coords;

        coords = await geocode(`${province}`);
        return coords;
    }

    async function getDistanceOSRM(lon1, lat1, lon2, lat2) {
        try {
            const url = `https://router.project-osrm.org/route/v1/driving/${lon1},${lat1};${lon2},${lat2}?overview=false`;
            const response = await fetch(url);
            const data = await response.json();
            if (data.code === 'Ok' && data.routes.length > 0) {
                return data.routes[0].distance / 1000; // meters to km
            }
        } catch(e) {
            console.error(e);
        }
        return null;
    }

    let isCalculating = false;

    async function calculateMockShipping() {
        if (isCalculating) return;
        
        const p = tsProvince.getValue();
        const d = tsDistrict.getValue();
        const w = tsWard.getValue();
        const specific = document.getElementById('specific_address').value;

        if (p && d && w) {
            isCalculating = true;
            document.getElementById('display_distance').innerText = 'Đang tính toán...';
            document.getElementById('display_shipping_fee').innerText = '...';

            if (!storeCoords) {
                const storeProvince = "{{ $storeProvince ?? 'Hà Nội' }}";
                const storeDistrict = "{{ $storeDistrict ?? '' }}";
                const storeWard = "{{ $storeWard ?? '' }}";
                const storeSpecific = "{{ $storeSpecificAddress ?? '' }}";
                storeCoords = await getCoordinatesWithFallback(storeSpecific, storeWard, storeDistrict, storeProvince);
            }

            const customerCoords = await getCoordinatesWithFallback(specific, w, d, p);
            let distanceKm = null;

            if (storeCoords && customerCoords) {
                const distance = await getDistanceOSRM(storeCoords.lon, storeCoords.lat, customerCoords.lon, customerCoords.lat);
                if (distance !== null) {
                    distanceKm = parseFloat(distance.toFixed(1));
                }
            }
            
            isCalculating = false;
            
            // If failed to calculate, distanceKm remains null
            if (distanceKm === null) {
                console.log({storeCoords, customerCoords, p, d, w});
                if (!storeCoords) {
                    alert("Không thể tính được khoảng cách: Lỗi xác định tọa độ cửa hàng.");
                } else if (!customerCoords) {
                    alert("Không thể tính được khoảng cách: Lỗi xác định tọa độ địa chỉ giao hàng của bạn.");
                } else {
                    alert("Không thể tìm thấy tuyến đường giao thông phù hợp giữa cửa hàng và địa chỉ của bạn.");
                }
                distanceKm = 0; // fallback to 0 after alerting
            }

            const feePerKm = {{ $feePerKm ?? 0 }};
            const maxRadius = {{ $maxRadius ?? 0 }};
            
            let shippingFee = distanceKm * feePerKm;
            
            // Check max radius
            if (maxRadius > 0 && distanceKm > maxRadius) {
                alert(`Khoảng cách giao hàng (${distanceKm}km) vượt quá bán kính cho phép (${maxRadius}km). Vui lòng chọn địa chỉ khác.`);
                shippingFee = 0;
                distanceKm = 0;
            }

            document.getElementById('input_distance_km').value = distanceKm;
            document.getElementById('input_shipping_fee').value = shippingFee;
            
            document.getElementById('display_distance').innerText = distanceKm > 0 ? distanceKm + ' km' : '0 km';
            document.getElementById('display_shipping_fee').innerText = new Intl.NumberFormat('vi-VN').format(shippingFee) + ' đ';
            
            updateTotal(shippingFee);
        } else {
            document.getElementById('input_distance_km').value = 0;
            document.getElementById('input_shipping_fee').value = 0;
            document.getElementById('display_distance').innerText = '0 km';
            document.getElementById('display_shipping_fee').innerText = '0 đ';
            updateTotal(0);
        }
    }

    function updateTotal(shippingFee) {
        const subtotal = {{ $subtotal ?? 0 }};
        const discount = {{ $discountAmount ?? 0 }};
        const total = subtotal - discount + shippingFee;
        document.getElementById('display_total').innerText = new Intl.NumberFormat('vi-VN').format(total) + ' đ';
    }

    if (tsSaved) {
        tsSaved.on('change', function(value) {
            const el = tsSaved.getItem(value);
            const rawOption = tsSaved.options[value];
            
            if (!value || value === "") {
                document.getElementById('receiver_name').value = '';
                document.getElementById('receiver_phone').value = '';
                document.getElementById('specific_address').value = '';
                tsProvince.setValue('');
                return;
            }
            
            // To get original DOM dataset we would need the raw HTML, but TomSelect stores dataset in $option if configured, 
            // actually we can fetch it from the original select element.
            const originalSelect = document.getElementById('saved_address_select');
            const originalOption = originalSelect.querySelector(`option[value="${value}"]`);
            
            if (originalOption) {
                document.getElementById('receiver_name').value = originalOption.dataset.name || '';
                document.getElementById('receiver_phone').value = originalOption.dataset.phone || '';
                document.getElementById('specific_address').value = originalOption.dataset.address || '';
                
                const pName = originalOption.dataset.province;
                const dName = originalOption.dataset.district;
                const wName = originalOption.dataset.ward;
                
                tsProvince.setValue(pName);
                
                // wait for district to load
                setTimeout(() => {
                    tsDistrict.setValue(dName);
                    setTimeout(() => {
                        tsWard.setValue(wName);
                    }, 500); // Wait for ward load
                }, 500); // Wait for district load
            }
        });
    }
});
</script>
@endpush
@endsection
