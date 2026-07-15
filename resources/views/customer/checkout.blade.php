@extends('layouts.customer')

@section('title', 'Thanh Toán')

@section('content')
<main class="mt-24 pb-24 max-w-container-max mx-auto px-4 md:px-lg">
<div class="mb-xl">
<h1 class="font-headline-lg text-headline-lg text-on-background">Thanh Toán</h1>
<p class="font-body-md text-body-md text-on-surface-variant">Complete your order and we'll start brewing.</p>
</div>
<form id="placeOrderForm" action="/customer/checkout/place-order" method="POST">
    @csrf
    @if(isset($defaultAddress))
        <input type="hidden" name="address_id" value="{{ $defaultAddress->id }}">
    @endif
</form>
<div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter items-start">
<!-- Left Column: Order Details -->
<div class="lg:col-span-8 space-y-lg">
<!-- Your Cart Giâytion -->
<section class="bg-surface-container-lowest rounded-xl p-lg custom-shadow">
<div class="flex items-center justify-between mb-md">
<h2 class="font-title-lg text-title-lg flex items-center gap-xs">
<span class="material-symbols-outlined text-primary">shopping_basket</span>
                            Your Cart
                        </h2>
<span class="font-label-md text-label-md text-on-surface-variant">{{ count($cartItems) }} items</span>
</div>
<div class="divide-y divide-outline-variant/20">
@forelse($cartItems as $item)
<!-- Item -->
<div class="py-md flex items-center gap-md">
<div class="w-20 h-20 rounded-lg overflow-hidden flex-shrink-0">
<img class="w-full h-full object-cover" src="{{ $item->product_image }}" alt="{{ $item->product_name }}"/>
</div>
<div class="flex-grow">
<h3 class="font-body-lg text-body-lg font-semibold">{{ $item->product_name }}</h3>
<p class="font-label-md text-label-md text-on-surface-variant">Size: {{ $item->size_name }}</p>
</div>
<div class="text-right">
<p class="font-body-lg text-body-lg font-bold text-primary">{{ number_format($item->price, 0, ',', '.') }} VNĐ</p>
<div class="flex items-center gap-xs mt-xs">
<button onclick="updateCartItemQuantity({{ $item->cart_item_id }}, 'decrease')" class="w-6 h-6 flex items-center justify-center rounded-full border border-outline-variant hover:bg-surface-container transition-colors">
<span class="material-symbols-outlined text-[16px]">remove</span>
</button>
<span id="qty_{{ $item->cart_item_id }}" class="font-body-md text-body-md w-4 text-center">{{ $item->quantity }}</span>
<button onclick="updateCartItemQuantity({{ $item->cart_item_id }}, 'increase')" class="w-6 h-6 flex items-center justify-center rounded-full border border-outline-variant hover:bg-surface-container transition-colors">
<span class="material-symbols-outlined text-[16px]">add</span>
</button>
</div>
</div>
</div>
@empty
<div class="py-md text-center text-on-surface-variant">Giỏ hàng của bạn đang trống.</div>
@endforelse
</div>
</section>
<!-- Địa Chỉ Giao Hàng -->
<section class="bg-surface-container-lowest rounded-xl p-lg custom-shadow">
<div class="flex items-center justify-between mb-md">
<h2 class="font-title-lg text-title-lg flex items-center gap-xs">
<span class="material-symbols-outlined text-primary">location_on</span>
                            Địa Chỉ Giao Hàng
                        </h2>
</div>
<div class="space-y-sm">
    @php 
        $defaultAddress = count($addresses) > 0 ? $addresses[0] : null; 
        $fullAddress = $defaultAddress ? $defaultAddress->address . ', ' . $defaultAddress->ward . ', ' . $defaultAddress->district . ', ' . $defaultAddress->province : '';
    @endphp
    <input type="text" name="receiver_name" form="placeOrderForm" placeholder="Tên người nhận" required class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-md py-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all placeholder:text-on-surface-variant/50" value="{{ $defaultAddress ? $defaultAddress->receiver_name : $user->username }}" />
    
    <input type="text" name="receiver_phone" form="placeOrderForm" placeholder="Số điện thoại" required class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-md py-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all placeholder:text-on-surface-variant/50" value="{{ $defaultAddress ? $defaultAddress->receiver_phone : '' }}" />
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-md">
        <select id="provinceSelect" class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-md py-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all text-on-surface">
            <option value="">Chọn Tỉnh/Thành</option>
        </select>
        <select id="districtSelect" class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-md py-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all text-on-surface" disabled>
            <option value="">Chọn Quận/Huyện</option>
        </select>
        <select id="wardSelect" class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-md py-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all text-on-surface" disabled>
            <option value="">Chọn Phường/Xã</option>
        </select>
    </div>
    <input type="text" id="specificAddress" placeholder="Số nhà, Tên đường, Tổ dân phố..." class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-md py-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all placeholder:text-on-surface-variant/50" />
    <input type="hidden" id="shippingAddress" name="shipping_address" form="placeOrderForm" value="{{ $fullAddress }}" />
    
    <input type="hidden" id="distance_km" name="distance_km" form="placeOrderForm" value="0">
    <input type="hidden" id="calculated_shipping_fee" name="shipping_fee" form="placeOrderForm" value="0">
    
    <div id="distanceInfo" class="hidden mt-sm p-sm bg-primary/10 rounded-lg text-primary font-medium text-sm flex items-center gap-xs">
        <span class="material-symbols-outlined text-[20px]">directions_car</span>
        <span>Khoảng cách: <strong id="distanceText">0 km</strong> - Phí ship: <strong id="feeText">0 VNĐ</strong></span>
    </div>
    <div id="distanceError" class="hidden mt-sm p-sm bg-error/10 rounded-lg text-error font-medium text-sm flex items-center gap-xs">
        <span class="material-symbols-outlined text-[20px]">error</span>
        <span>Khoảng cách vượt quá 10km. Cửa hàng không hỗ trợ giao đơn hàng này.</span>
    </div>
</div>
</section>
<!-- Ghi chú -->
<section class="bg-surface-container-lowest rounded-xl p-lg custom-shadow">
    <h2 class="font-title-lg text-title-lg flex items-center gap-xs mb-md">
        <span class="material-symbols-outlined text-primary">edit_note</span>
        Ghi Chú Đơn Hàng
    </h2>
    <div class="space-y-sm">
        <textarea name="note" id="orderNote" form="placeOrderForm" class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-md py-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary min-h-[100px]" placeholder="Ghi chú thêm về đơn hàng của bạn (ví dụ: giao giờ hành chính, nhiều đá...)"></textarea>
    </div>
</section>
<!-- Phương Thức Thanh Toán -->
<section class="bg-surface-container-lowest rounded-xl p-lg custom-shadow">
<h2 class="font-title-lg text-title-lg flex items-center gap-xs mb-md">
<span class="material-symbols-outlined text-primary">payments</span>
                        Phương Thức Thanh Toán
                    </h2>
<div class="space-y-sm">
<label class="flex items-center gap-md p-md rounded-xl border border-outline-variant/30 cursor-pointer hover:bg-surface-container-high transition-all has-[:checked]:border-primary has-[:checked]:bg-primary/5">
<input checked="" class="w-5 h-5 text-primary border-outline focus:ring-primary" name="payment" type="radio" value="cash" form="placeOrderForm"/>
<span class="material-symbols-outlined text-on-surface-variant">payments</span>
<div class="flex-grow">
<span class="font-body-lg text-body-lg font-medium">Thanh toán khi nhận hàng (COD)</span>
<p class="font-label-md text-label-md text-on-surface-variant">Thanh toán bằng tiền mặt khi nhận được hàng</p>
</div>
<span class="material-symbols-outlined text-on-surface-variant">chevron_right</span>
</label>
<label class="flex items-center gap-md p-md rounded-xl border border-outline-variant/30 cursor-pointer hover:bg-surface-container-high transition-all has-[:checked]:border-primary has-[:checked]:bg-primary/5">
<input class="w-5 h-5 text-primary border-outline focus:ring-primary" name="payment" type="radio" value="vnpay" form="placeOrderForm"/>
<span class="material-symbols-outlined text-on-surface-variant">account_balance</span>
<div class="flex-grow">
<span class="font-body-lg text-body-lg font-medium">Thanh toán qua VNPay</span>
<p class="font-label-md text-label-md text-on-surface-variant">Thanh toán trực tuyến an toàn qua cổng VNPay</p>
</div>
<span class="material-symbols-outlined text-on-surface-variant">chevron_right</span>
</label>
</div>
</section>
</div>
<!-- Right Column: Sticky Summary -->
<aside class="lg:col-span-4 lg:sticky lg:top-24 space-y-md">
<div class="bg-surface-container-lowest rounded-xl p-lg custom-shadow border border-outline-variant/10">
<h2 class="font-title-lg text-title-lg mb-md">Tóm Tắt Đơn Hàng</h2>
<div class="space-y-sm mb-lg">
<div class="flex justify-between font-body-md text-body-md text-on-surface-variant">
<span>Tạm tính</span>
<span>{{ number_format($cartTotal, 0, ',', '.') }} VNĐ</span>
</div>
<div class="flex justify-between font-body-md text-body-md text-on-surface-variant">
<span>Phí giao hàng</span>
<span id="summaryShippingFee">0 VNĐ</span>
</div>
@if($appliedVoucher)
<div class="flex justify-between font-body-md text-body-md text-primary">
<span>Giảm giá ({{ $appliedVoucher['code'] }})</span>
<span>-{{ number_format($discountAmount, 0, ',', '.') }} VNĐ</span>
</div>
@endif
<div class="pt-sm border-t border-outline-variant/20">
<div class="flex justify-between font-headline-md text-headline-md text-on-background">
<span>Tổng cộng</span>
<span class="text-primary font-bold" id="summaryTotal">{{ number_format($finalTotal, 0, ',', '.') }} VNĐ</span>
</div>
</div>
</div>
<!-- Voucher Field -->
<div class="mb-lg">
    <form action="/customer/checkout/apply-voucher" method="POST" id="voucherForm">
        @csrf
        <div class="relative mb-xs" id="voucherContainer">
            <input name="code" id="voucherInput" value="{{ old('code', $appliedVoucher ? $appliedVoucher['code'] : '') }}" class="w-full bg-surface-container-low border {{ session('error') ? 'border-error' : 'border-outline-variant' }} rounded-lg px-md py-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all placeholder:text-on-surface-variant/50" placeholder="Mã giảm giá" type="text" autocomplete="off" onclick="toggleVoucherDropdown(true)"/>
            <button type="submit" class="absolute right-2 top-1.5 px-3 py-1 bg-primary text-on-primary rounded-md font-label-md text-label-md hover:bg-primary-container transition-colors z-10">Áp dụng</button>
            
            <!-- Dropdown -->
            <div id="voucherDropdown" class="absolute left-0 right-0 top-full mt-1 bg-surface rounded-xl shadow-lg border border-outline-variant/20 overflow-hidden z-50 hidden max-h-64 overflow-y-auto">
                @if(isset($availableVouchers) && $availableVouchers->count() > 0)
                    <div class="p-2 flex flex-col gap-2 bg-surface-container-lowest">
                    @foreach($availableVouchers as $v)
                        @php
                            $isDisabled = $cartTotal < $v->minimum_order;
                            $discountStr = $v->discount_type == 'percent' ? $v->discount_value . '%' : number_format($v->discount_value, 0, ',', '.') . ' VNĐ';
                            if ($v->maximum_discount) {
                                $discountStr .= ' (Tối đa ' . number_format($v->maximum_discount, 0, ',', '.') . ' VNĐ)';
                            }
                        @endphp
                        <div class="p-3 rounded-xl border {{ $isDisabled ? 'border-outline-variant/30 bg-surface-container/30 opacity-60 cursor-not-allowed' : 'border-primary/20 bg-primary/5 cursor-pointer hover:bg-primary/10' }} transition-colors"
                             @if(!$isDisabled) onclick="selectVoucher('{{ $v->code }}')" @endif>
                            <div class="flex justify-between items-start mb-1">
                                <span class="font-bold text-primary">{{ $v->code }}</span>
                                <span class="text-[10px] font-bold bg-primary text-white px-2 py-0.5 rounded-full whitespace-nowrap ml-2">-{{ $discountStr }}</span>
                            </div>
                            <p class="text-[11px] text-on-surface-variant">Đơn tối thiểu: {{ number_format($v->minimum_order, 0, ',', '.') }} VNĐ</p>
                        </div>
                    @endforeach
                    </div>
                @else
                    <div class="p-4 text-center text-label-md text-on-surface-variant bg-surface-container-lowest">
                        Hiện không có mã giảm giá nào.
                    </div>
                @endif
            </div>
        </div>
        @if(session('error'))
            <p class="text-error text-label-sm">{{ session('error') }}</p>
        @endif
        @if(session('success'))
            <p class="text-primary text-label-sm">{{ session('success') }}</p>
        @endif
    </form>
</div>
<!-- Đặt Hàng CTA -->
<button type="submit" id="placeOrderBtn" form="placeOrderForm" class="w-full py-md bg-primary-container text-on-primary-container font-headline-md text-headline-md rounded-xl hover:shadow-lg active:scale-[0.98] transition-all flex items-center justify-center gap-sm disabled:opacity-50 disabled:cursor-not-allowed">
                        Đặt Hàng
                        <span class="material-symbols-outlined">arrow_forward</span>
</button>
<p class="mt-md text-center font-label-md text-label-md text-on-surface-variant">
                        By placing your order, you agree to CozyHNA's <br/> <a class="underline" href="#">Terms of Service</a>
</p>
</div>
<!-- Eco-Friendly Badge -->
<div class="p-md rounded-xl bg-secondary-container/20 flex items-center gap-md border border-secondary-container/30">
<span class="material-symbols-outlined text-secondary" style="font-variation-settings: 'FILL' 1;">eco</span>
<div>
<p class="font-label-md text-label-md font-bold text-secondary">Sustainable Giao hàng</p>
<p class="font-label-sm text-label-sm text-on-secondary-container">Your order will be delivered using 100% compostable packaging.</p>
</div>
</div>
</aside>
</div>
</main>
@endsection

@push('scripts')
<script>

        function toggleModal() {
            const modal = document.getElementById('addressModal');
            const content = modal.firstElementChild;
            if (modal.classList.contains('opacity-0')) {
                modal.classList.remove('opacity-0', 'pointer-events-none');
                content.classList.remove('translate-y-4');
                document.body.style.overflow = 'hidden';
            } else {
                modal.classList.add('opacity-0', 'pointer-events-none');
                content.classList.add('translate-y-4');
                document.body.style.overflow = 'auto';
            }
        }

        async function updateCartItemQuantity(cartItemId, action) {
            try {
                const response = await fetch('/cart/update-quantity', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ cart_item_id: cartItemId, action: action })
                });
                
                const data = await response.json();
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || 'Lỗi cập nhật số lượng');
                }
            } catch(e) {
                console.error(e);
                alert('Lỗi kết nối mạng');
            }
        }

        function toggleVoucherDropdown(show) {
            const dropdown = document.getElementById('voucherDropdown');
            if (show) {
                dropdown.classList.remove('hidden');
            } else {
                dropdown.classList.add('hidden');
            }
        }

        function selectVoucher(code) {
            document.getElementById('voucherInput').value = code;
            toggleVoucherDropdown(false);
            document.getElementById('voucherForm').submit();
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const container = document.getElementById('voucherContainer');
            if (container && !container.contains(event.target)) {
                toggleVoucherDropdown(false);
            }
        });
    
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const provinceSelect = document.getElementById('provinceSelect');
        const districtSelect = document.getElementById('districtSelect');
        const wardSelect = document.getElementById('wardSelect');
        const specificAddress = document.getElementById('specificAddress');
        const shippingAddressInput = document.getElementById('shippingAddress');
        const orderNote = document.getElementById('orderNote');
        
        let savedProvince = sessionStorage.getItem('checkout_province');
        let savedDistrict = sessionStorage.getItem('checkout_district');
        let savedWard = sessionStorage.getItem('checkout_ward');
        
        specificAddress.value = sessionStorage.getItem('checkout_specific') || specificAddress.value;
        if (orderNote) {
            orderNote.value = sessionStorage.getItem('checkout_note') || orderNote.value;
            orderNote.addEventListener('input', () => sessionStorage.setItem('checkout_note', orderNote.value));
        }
        const rName = document.querySelector('input[name="receiver_name"]');
        if(rName) {
            rName.value = sessionStorage.getItem('checkout_name') || rName.value;
            rName.addEventListener('input', () => sessionStorage.setItem('checkout_name', rName.value));
        }
        const rPhone = document.querySelector('input[name="receiver_phone"]');
        if(rPhone) {
            rPhone.value = sessionStorage.getItem('checkout_phone') || rPhone.value;
            rPhone.addEventListener('input', () => sessionStorage.setItem('checkout_phone', rPhone.value));
        }
        
        fetch('https://provinces.open-api.vn/api/p/')
            .then(response => response.json())
            .then(data => {
                data.forEach(p => {
                    const option = document.createElement('option');
                    option.value = p.code;
                    option.textContent = p.name;
                    provinceSelect.appendChild(option);
                });
                if (savedProvince) {
                    provinceSelect.value = savedProvince;
                    provinceSelect.dispatchEvent(new Event('change'));
                }
            });
            
        provinceSelect.addEventListener('change', function() {
            sessionStorage.setItem('checkout_province', this.value);
            districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
            wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
            districtSelect.disabled = true;
            wardSelect.disabled = true;
            
            if (this.value) {
                fetch(`https://provinces.open-api.vn/api/p/${this.value}?depth=2`)
                    .then(response => response.json())
                    .then(data => {
                        data.districts.forEach(d => {
                            const option = document.createElement('option');
                            option.value = d.code;
                            option.textContent = d.name;
                            districtSelect.appendChild(option);
                        });
                        districtSelect.disabled = false;
                        if (savedDistrict && provinceSelect.value === savedProvince) {
                            districtSelect.value = savedDistrict;
                            districtSelect.dispatchEvent(new Event('change'));
                        }
                    });
            }
            updateCombinedAddress();
        });
        
        districtSelect.addEventListener('change', function() {
            sessionStorage.setItem('checkout_district', this.value);
            wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
            wardSelect.disabled = true;
            
            if (this.value) {
                fetch(`https://provinces.open-api.vn/api/d/${this.value}?depth=2`)
                    .then(response => response.json())
                    .then(data => {
                        data.wards.forEach(w => {
                            const option = document.createElement('option');
                            option.value = w.code;
                            option.textContent = w.name;
                            wardSelect.appendChild(option);
                        });
                        wardSelect.disabled = false;
                        if (savedWard && districtSelect.value === savedDistrict) {
                            wardSelect.value = savedWard;
                            wardSelect.dispatchEvent(new Event('change'));
                            
                            savedWard = null;
                            savedDistrict = null;
                            savedProvince = null;
                        }
                    });
            }
            updateCombinedAddress();
        });
        
        wardSelect.addEventListener('change', function() {
            sessionStorage.setItem('checkout_ward', this.value);
            updateCombinedAddress();
        });
        
        specificAddress.addEventListener('input', function() {
            sessionStorage.setItem('checkout_specific', this.value);
            updateCombinedAddress();
        });
        
        let timeout = null;
        function updateCombinedAddress() {
            let addressParts = [];
            if (specificAddress.value.trim() !== '') {
                addressParts.push(specificAddress.value.trim());
            }
            if (wardSelect.selectedIndex > 0) {
                addressParts.push(wardSelect.options[wardSelect.selectedIndex].text);
            }
            if (districtSelect.selectedIndex > 0) {
                addressParts.push(districtSelect.options[districtSelect.selectedIndex].text);
            }
            if (provinceSelect.selectedIndex > 0) {
                addressParts.push(provinceSelect.options[provinceSelect.selectedIndex].text);
            }
            
            const fullAddress = addressParts.join(', ');
            shippingAddressInput.value = fullAddress;
            
            if (addressParts.length >= 3) {
                clearTimeout(timeout);
                timeout = setTimeout(() => calculateDistance(fullAddress), 1000);
            }
        }

        const storeLat = 20.6390143;
        const storeLon = 105.9188582;
        
        async function calculateDistance(destinationAddress) {
            try {
                let parts = destinationAddress.split(',').map(s => s.trim());
                let geocodeData = null;
                
                // 1. Geocode destination using Nominatim with Fallback
                while (parts.length > 0) {
                    const query = parts.join(', ');
                    const encodedAddress = encodeURIComponent(query);
                    const geocodeRes = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodedAddress}&countrycodes=vn&limit=1`);
                    const data = await geocodeRes.json();
                    
                    if (data && data.length > 0) {
                        geocodeData = data;
                        break;
                    }
                    
                    // Fallback: remove the most specific part (the first one) and try again
                    parts.shift();
                }
                
                if (!geocodeData) {
                    console.error('Could not find destination coordinates');
                    document.getElementById('distanceText').innerText = "N/A km";
                    document.getElementById('feeText').innerText = "Không xác định";
                    document.getElementById('distanceInfo').classList.remove('hidden');
                    return;
                }
                
                const destLat = geocodeData[0].lat;
                const destLon = geocodeData[0].lon;
                
                // 2. Calculate distance using OSRM
                const osrmRes = await fetch(`https://router.project-osrm.org/route/v1/driving/${storeLon},${storeLat};${destLon},${destLat}?overview=false`);
                const osrmData = await osrmRes.json();
                
                if (osrmData.code !== 'Ok' || !osrmData.routes || osrmData.routes.length === 0) {
                    console.error('Error calculating route');
                    return;
                }
                
                const distanceValue = osrmData.routes[0].distance; // in meters
                const distanceKm = (distanceValue / 1000).toFixed(1);
                
                if (parseFloat(distanceKm) > 10) {
                    document.getElementById('distance_km').value = distanceKm;
                    document.getElementById('calculated_shipping_fee').value = 0;
                    
                    document.getElementById('distanceText').innerText = distanceKm + " km";
                    document.getElementById('feeText').innerText = "Không hỗ trợ giao";
                    document.getElementById('distanceInfo').classList.remove('hidden');
                    document.getElementById('distanceError').classList.remove('hidden');
                    
                    document.getElementById('summaryShippingFee').innerText = "0 VNĐ";
                    const baseTotal = {{ $finalTotal }};
                    document.getElementById('summaryTotal').innerText = new Intl.NumberFormat('vi-VN').format(baseTotal) + " VNĐ";
                    
                    document.getElementById('placeOrderBtn').disabled = true;
                } else {
                    const fee = Math.ceil(distanceKm * 5000); // 5000 VND per km
                    
                    document.getElementById('distance_km').value = distanceKm;
                    document.getElementById('calculated_shipping_fee').value = fee;
                    
                    document.getElementById('distanceText').innerText = distanceKm + " km";
                    document.getElementById('feeText').innerText = new Intl.NumberFormat('vi-VN').format(fee) + " VNĐ";
                    document.getElementById('distanceInfo').classList.remove('hidden');
                    document.getElementById('distanceError').classList.add('hidden');
                    
                    document.getElementById('summaryShippingFee').innerText = new Intl.NumberFormat('vi-VN').format(fee) + " VNĐ";
                    
                    const baseTotal = {{ $finalTotal }};
                    const newTotal = baseTotal + fee;
                    document.getElementById('summaryTotal').innerText = new Intl.NumberFormat('vi-VN').format(newTotal) + " VNĐ";
                    
                    document.getElementById('placeOrderBtn').disabled = false;
                }
                
            } catch (error) {
                console.error("Routing error:", error);
            }
        }
    });
</script>
@endpush
