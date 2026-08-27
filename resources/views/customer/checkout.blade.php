@extends('layouts.customer')

@section('title', 'Thanh Toán')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    #map { height: 250px; width: 100%; border-radius: 0.75rem; z-index: 10; margin-bottom: 0.5rem; }
    
    /* Selected voucher item */
    .voucher-item:has(input[type="radio"]:checked) {
        background-color: rgba(0, 110, 28, 0.05);
        border-color: #006e1c;
    }
</style>
@endpush

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
    .ts-dropdown .ts-dropdown-content {
        max-height: 250px !important;
        overflow-y: auto !important;
        overflow-x: hidden !important;
    }
</style>

<main class="mt-24 pb-24 max-w-container-max mx-auto px-4 md:px-lg">

{{-- Flash messages --}}
@if(session('error'))
    <div class="mb-md p-md bg-red-100 text-red-700 rounded-xl font-body-md">{{ session('error') }}</div>
@endif
@if(session('success'))
    <div class="mb-md p-md bg-green-100 text-green-700 rounded-xl font-body-md">{{ session('success') }}</div>
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
                <div class="flex items-center gap-2">
                    <div class="flex-1">
                        <select id="saved_address_select" class="no-choices w-full p-md rounded-xl bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 text-body-md transition-colors">
                            <option value="">-- Nhập địa chỉ mới --</option>
                            @foreach($addresses as $addr)
                                <option value="{{ $addr->id }}" 
                                        data-name="{{ $addr->receiver_name }}" 
                                        data-phone="{{ $addr->receiver_phone }}"
                                        data-province="{{ $addr->province }}"
                                        data-district="{{ $addr->district }}"
                                        data-ward="{{ $addr->ward }}"
                                        data-address="{{ $addr->address }}"
                                        data-lat="{{ $addr->latitude }}"
                                        data-lon="{{ $addr->longitude }}"
                                        {{ ($defaultAddr && $defaultAddr->id == $addr->id) ? 'selected' : '' }}>{{ $addr->receiver_name }} - {{ $addr->receiver_phone }} ({{ $addr->address }}, {{ $addr->ward }}, {{ $addr->district }}, {{ $addr->province }})</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" id="btn_show_manual_form" class="px-4 py-2 bg-primary text-on-primary rounded-xl font-label-md hover:bg-primary/90 transition-colors whitespace-nowrap">
                        + Thêm địa chỉ
                    </button>
                </div>
            </div>
            @endif

            <div id="manual_address_form" @if(count($addresses) > 0 && $defaultAddr) style="display: none;" @endif>

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
                    <select id="province_select" name="province" required class="no-choices w-full p-md rounded-xl bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 text-body-md transition-colors">
                        <option value="">Chọn Tỉnh/Thành phố</option>
                    </select>
                </div>
                <div>
                    <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-xs block">Xã/Phường *</label>
                    <select id="district_select" name="district" required disabled class="no-choices w-full p-md rounded-xl bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 text-body-md transition-colors">
                        <option value="">Chọn Xã/Phường</option>
                    </select>
                </div>
                <div>
                    <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-xs block">Thôn/Xóm/Tổ dân phố *</label>
                    <select id="ward_select" name="ward" required disabled class="no-choices w-full p-md rounded-xl bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 text-body-md transition-colors">
                        <option value="">Chọn Thôn/Xóm/Tổ dân phố</option>
                    </select>
                </div>
            </div>

            <div class="mt-md">
                <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-xs block">Địa chỉ cụ thể *</label>
                <div class="flex gap-2">
                    <input type="text" id="specific_address" name="address" required
                        value="{{ old('address', optional($defaultAddr)->address ?? '') }}"
                        class="flex-1 p-md rounded-xl bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 text-body-md transition-colors"
                        placeholder="Số nhà, ngõ, mô tả vị trí để shipper đọc..."/>
                    <button type="button" id="btn_find_location" class="px-4 bg-secondary text-on-secondary rounded-xl hover:bg-secondary/90 transition-colors flex items-center justify-center whitespace-nowrap" title="Tìm trên bản đồ">
                        <span class="material-symbols-outlined mr-1">search</span> Tìm
                    </button>
                    <button type="button" id="btn_current_location" class="px-4 bg-surface-container-high text-on-surface rounded-xl hover:bg-surface-variant transition-colors flex items-center justify-center whitespace-nowrap border border-outline-variant/30" title="Lấy vị trí hiện tại">
                        <span class="material-symbols-outlined">my_location</span>
                    </button>
                </div>
            </div>

            <div class="mt-md" id="map_container" style="display: none;">
                <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-xs flex items-center gap-1">
                    Xác nhận vị trí trên bản đồ <span class="text-error">*</span>
                </label>
                <p class="text-xs text-on-surface-variant mb-2" id="map_helper_text">Vui lòng kéo ghim (marker) đến chính xác vị trí nhận hàng của bạn.</p>
                <div id="map" class="w-full border border-outline-variant shadow-inner"></div>
                <input type="hidden" name="delivery_latitude" id="input_delivery_lat" value="">
                <input type="hidden" name="delivery_longitude" id="input_delivery_lon" value="">
            </div>

            <div class="mt-sm flex items-center gap-xs">
                <input type="checkbox" id="save_address" name="set_default" value="1" class="w-4 h-4 text-primary border-outline focus:ring-primary rounded">
                <label for="save_address" class="font-label-md text-label-md text-on-surface-variant cursor-pointer select-none">Đặt làm mặc định</label>
            </div>

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
                    <input checked name="payment_method" type="radio" value="vnpay" class="w-5 h-5 text-primary border-outline focus:ring-primary"/>
                    <span class="material-symbols-outlined text-blue-600">account_balance</span>
                    <div class="flex-grow">
                        <span class="font-body-lg text-body-lg font-medium">VNPAY / Thẻ ATM</span>
                        <p class="font-label-md text-label-md text-on-surface-variant">Thanh toán trực tuyến an toàn qua Cổng VNPAY</p>
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
            </div>
        </section>
    </div>

    {{-- Right Column: Sticky Summary --}}
    <aside class="lg:col-span-4 lg:sticky lg:top-24 space-y-md">
        
        {{-- Voucher --}}
        <div class="bg-surface-container-lowest rounded-xl p-lg shadow-sm border border-outline-variant/10">
            <h2 class="font-title-lg text-title-lg mb-sm flex items-center gap-xs">
                <span class="material-symbols-outlined text-primary">local_activity</span>
                Khuyến mãi
            </h2>
            
            <div class="space-y-3">
                <!-- Voucher Sản Phẩm -->
                <div class="border border-outline-variant/30 rounded-lg p-3">
                    <p class="font-label-md text-label-md text-on-surface-variant mb-2 uppercase tracking-wider">Mã giảm giá sản phẩm</p>
                    @if(session('applied_voucher'))
                        <div class="bg-success-container text-on-success-container p-sm rounded-lg flex items-center justify-between">
                            <div>
                                <p class="font-bold text-sm">Mã: {{ session('applied_voucher')['code'] }}</p>
                                <p class="text-xs">Giảm: -{{ number_format(session('applied_voucher')['discount_amount'], 0, ',', '.') }} đ</p>
                            </div>
                            <form action="{{ route('vouchers.remove') }}" method="POST">
                                @csrf
                                <input type="hidden" name="target" value="product">
                                <button type="submit" class="text-error hover:text-error/80 p-1 bg-white/20 rounded-full" title="Bỏ mã">
                                    <span class="material-symbols-outlined text-[16px]">close</span>
                                </button>
                            </form>
                        </div>
                    @else
                        <button type="button" onclick="openVoucherModal('product')" class="w-full py-2 bg-primary/10 text-primary font-bold rounded-lg hover:bg-primary/20 transition-colors flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">add_circle</span> Chọn hoặc nhập mã
                        </button>
                    @endif
                </div>

                <!-- Voucher Phí Ship -->
                <div class="border border-outline-variant/30 rounded-lg p-3">
                    <p class="font-label-md text-label-md text-on-surface-variant mb-2 uppercase tracking-wider">Mã miễn phí vận chuyển</p>
                    @if(session('applied_shipping_voucher'))
                        <div class="bg-success-container text-on-success-container p-sm rounded-lg flex items-center justify-between">
                            <div>
                                <p class="font-bold text-sm">Mã: {{ session('applied_shipping_voucher')['code'] }}</p>
                                <p class="text-xs">Giảm phí ship: -{{ number_format($shippingDiscountAmount ?? 0, 0, ',', '.') }} đ</p>
                            </div>
                            <form action="{{ route('vouchers.remove') }}" method="POST">
                                @csrf
                                <input type="hidden" name="target" value="shipping">
                                <button type="submit" class="text-error hover:text-error/80 p-1 bg-white/20 rounded-full" title="Bỏ mã">
                                    <span class="material-symbols-outlined text-[16px]">close</span>
                                </button>
                            </form>
                        </div>
                    @else
                        <button type="button" onclick="openVoucherModal('shipping')" class="w-full py-2 bg-primary/10 text-primary font-bold rounded-lg hover:bg-primary/20 transition-colors flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">add_circle</span> Chọn hoặc nhập mã freeship
                        </button>
                    @endif
                </div>
            </div>
        </div>

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
                    <span>Giảm giá sản phẩm</span>
                    <span id="display_discount">-{{ number_format($discountAmount, 0, ',', '.') }} đ</span>
                </div>
                @endif
                @if(isset($shippingDiscountAmount) && $shippingDiscountAmount > 0)
                <div class="flex justify-between font-body-md text-body-md text-error">
                    <span>Giảm phí vận chuyển</span>
                    <span id="display_shipping_discount">-{{ number_format($shippingDiscountAmount, 0, ',', '.') }} đ</span>
                </div>
                @endif
                <div class="pt-sm border-t border-outline-variant/20">
                    <div class="flex justify-between font-headline-md text-headline-md text-on-background">
                        <span>Tổng cộng</span>
                        <span class="text-primary" id="display_total">{{ number_format(max(0, $subtotal - ($discountAmount ?? 0) - ($shippingDiscountAmount ?? 0)), 0, ',', '.') }} đ</span>
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

<!-- Voucher Offcanvas Modal -->
<div id="voucherModal" class="fixed inset-0 z-[100] hidden bg-black/50 opacity-0 transition-opacity duration-300">
    <div id="voucherModalContent" class="fixed bottom-0 left-0 right-0 md:left-auto md:w-[450px] md:top-0 md:h-full bg-surface rounded-t-2xl md:rounded-l-2xl md:rounded-tr-none transform translate-y-full md:translate-y-0 md:translate-x-full transition-transform duration-300 flex flex-col max-h-[85vh] md:max-h-full">
        <div class="flex items-center justify-between p-4 border-b border-outline-variant/30">
            <h3 class="font-title-lg text-title-lg font-bold" id="voucherModalTitle">Chọn Mã Giảm Giá</h3>
            <button type="button" onclick="closeVoucherModal()" class="p-2 text-on-surface-variant hover:text-on-surface rounded-full hover:bg-surface-container transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        
        <!-- Manual code entry form -->
        <div class="p-4 border-b border-outline-variant/30">
            <form action="{{ route('vouchers.apply') }}" method="POST" class="flex gap-2" id="manualVoucherForm">
                @csrf
                <input type="hidden" name="shipping_fee" class="shipping_fee_input" value="0">
                <input type="hidden" name="target" id="manualVoucherTarget" value="">
                <input type="text" name="voucher_code" class="flex-1 p-3 rounded-xl bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 text-body-md transition-colors uppercase" placeholder="Nhập mã..." required>
                <button type="submit" class="px-4 bg-primary text-on-primary rounded-xl font-bold hover:bg-primary/90 transition-colors">
                    Áp dụng
                </button>
            </form>
        </div>

        <!-- Available vouchers form -->
        <div class="flex-1 overflow-y-auto flex flex-col relative custom-scrollbar">
            @if(isset($availableVouchers) && count($availableVouchers) > 0)
                <form action="{{ route('vouchers.apply') }}" method="POST" id="voucherListForm" class="flex flex-col h-full m-0">
                    @csrf
                    <input type="hidden" name="shipping_fee" class="shipping_fee_input" value="0">
                    <input type="hidden" name="target" id="listVoucherTarget" value="">
                    
                    <div id="voucherListContainer" class="space-y-3 p-4 flex-1">
                        @foreach($availableVouchers as $v)
                            <label class="voucher-item cursor-pointer block border border-outline-variant/30 rounded-xl p-4 bg-surface hover:bg-surface-container-lowest transition-colors relative" data-target="{{ $v->target ?? 'product' }}">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1 pr-3">
                                        <p class="font-bold text-lg text-primary mb-1">{{ $v->code }}</p>
                                        <p class="text-sm font-semibold text-on-surface mb-1">{{ $v->name }}</p>
                                        <p class="text-xs text-on-surface-variant mb-2">{{ $v->description }}</p>
                                        <p class="text-xs text-on-surface-variant font-medium">Giảm {{ $v->discount_type == 'percent' ? $v->discount_value.'%' : number_format($v->discount_value, 0, ',', '.').'đ' }}
                                            @if($v->maximum_discount)
                                                (Tối đa {{ number_format($v->maximum_discount, 0, ',', '.') }}đ)
                                            @endif
                                        </p>
                                    </div>
                                    <div class="flex-shrink-0 flex items-center h-full pt-2">
                                        <input type="radio" name="voucher_code" value="{{ $v->code }}" class="w-5 h-5 text-primary border-outline-variant focus:ring-primary focus:ring-offset-0 bg-white cursor-pointer">
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    
                    <div class="p-4 border-t border-outline-variant/30 bg-surface sticky bottom-0" id="applyListVoucherContainer">
                        <button type="submit" class="w-full py-3 bg-primary text-on-primary rounded-xl font-bold text-[15px] hover:bg-primary/90 transition-colors" id="btnApplyListVoucher" disabled>
                            Áp dụng mã đã chọn
                        </button>
                    </div>
                </form>
                <div id="noVoucherMsg" class="hidden text-center py-8 text-on-surface-variant">
                    <span class="material-symbols-outlined text-[48px] mb-2 opacity-50">search_off</span>
                    <p>Không có mã giảm giá phù hợp</p>
                </div>
            @else
                <div class="text-center py-8 text-on-surface-variant">
                    <span class="material-symbols-outlined text-[48px] mb-2 opacity-50">local_activity</span>
                    <p>Bạn chưa lưu mã giảm giá nào.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@turf/turf@6/turf.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // === Toast Notification ===
    function showToast(message, type = 'success') {
        const existingToast = document.getElementById('checkout-toast');
        if (existingToast) existingToast.remove();

        const colors = {
            success: { bg: 'bg-primary', text: 'text-on-primary', icon: 'check_circle' },
            error:   { bg: 'bg-error',   text: 'text-on-error',   icon: 'error' },
            warning: { bg: 'bg-[#7d5800]', text: 'text-white',    icon: 'warning' },
            info:    { bg: 'bg-secondary', text: 'text-on-secondary', icon: 'info' },
        };
        const c = colors[type] || colors.info;

        const toast = document.createElement('div');
        toast.id = 'checkout-toast';
        toast.className = 'fixed top-24 left-1/2 -translate-x-1/2 z-[200] min-w-[320px] max-w-sm shadow-2xl rounded-xl overflow-hidden transition-all duration-500';
        toast.innerHTML = `
            <div class="${c.bg} ${c.text} px-4 py-3 flex items-start gap-3">
                <span class="material-symbols-outlined mt-0.5 flex-shrink-0">${c.icon}</span>
                <span class="text-sm flex-1 leading-snug">${message}</span>
                <button onclick="this.closest('#checkout-toast').remove()" class="hover:opacity-70 flex-shrink-0">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>
            </div>
        `;
        document.body.appendChild(toast);
        setTimeout(() => {
            const el = document.getElementById('checkout-toast');
            if (el) { el.style.opacity = '0'; setTimeout(() => el.remove(), 500); }
        }, 8000);
    }

    const voucherModal = document.getElementById('voucherModal');
    const voucherModalContent = document.getElementById('voucherModalContent');
    const voucherModalTitle = document.getElementById('voucherModalTitle');
    const voucherItems = document.querySelectorAll('.voucher-item');
    const noVoucherMsg = document.getElementById('noVoucherMsg');

    window.openVoucherModal = function(target) {
        voucherModal.classList.remove('hidden');
        // trigger reflow
        void voucherModal.offsetWidth;
        voucherModal.classList.remove('opacity-0');
        
        if(window.innerWidth >= 768) {
            voucherModalContent.style.transform = 'translateX(0)';
        } else {
            voucherModalContent.style.transform = 'translateY(0)';
        }

        voucherModalTitle.textContent = target === 'shipping' ? 'Chọn Mã Miễn Phí Vận Chuyển' : 'Chọn Mã Giảm Giá Sản Phẩm';

        const manualTargetInput = document.getElementById('manualVoucherTarget');
        if (manualTargetInput) manualTargetInput.value = target;

        const listTargetInput = document.getElementById('listVoucherTarget');
        if (listTargetInput) listTargetInput.value = target;

        let hasVisibleVoucher = false;

        // Filter items
        voucherItems.forEach(item => {
            const radio = item.querySelector('input[type="radio"]');
            if (radio) radio.checked = false;
            
            if (item.getAttribute('data-target') === target || (target === 'product' && !item.getAttribute('data-target'))) {
                item.style.display = 'block';
                hasVisibleVoucher = true;
            } else {
                item.style.display = 'none';
            }
        });
        
        const btnApplyList = document.getElementById('btnApplyListVoucher');
        if (btnApplyList) btnApplyList.disabled = true;

        if(noVoucherMsg) {
            noVoucherMsg.style.display = !hasVisibleVoucher ? 'block' : 'none';
        }
        
        const applyListContainer = document.getElementById('applyListVoucherContainer');
        if (applyListContainer) {
            applyListContainer.style.display = hasVisibleVoucher ? 'block' : 'none';
        }
    };

    document.addEventListener('change', function(e) {
        if (e.target.name === 'voucher_code' && e.target.type === 'radio') {
            const btnApplyList = document.getElementById('btnApplyListVoucher');
            if (btnApplyList) {
                btnApplyList.disabled = false;
            }
        }
    });

    window.closeVoucherModal = function() {
        voucherModal.classList.add('opacity-0');
        if(window.innerWidth >= 768) {
            voucherModalContent.style.transform = 'translateX(100%)';
        } else {
            voucherModalContent.style.transform = 'translateY(100%)';
        }
        setTimeout(() => {
            voucherModal.classList.add('hidden');
            voucherModalContent.style.transform = '';
        }, 300);
    };

    // Close on backdrop click
    voucherModal.addEventListener('click', function(e) {
        if(e.target === voucherModal) {
            closeVoucherModal();
        }
    });

    // Initialize TomSelect for all dropdowns
    const tsOptions = {
        create: false,
        sortField: {field: "text", direction: "asc"},
        placeholder: 'Chọn...',
        maxOptions: 500,
        dropdownParent: 'body',
    };
    
    const tsSaved = document.getElementById('saved_address_select') ? new TomSelect('#saved_address_select', {
        create: false, placeholder: '-- Nhập địa chỉ mới --', dropdownParent: 'body'
    }) : null;
    
    const tsProvince = new TomSelect('#province_select', tsOptions);
    const tsDistrict = new TomSelect('#district_select', tsOptions);
    const tsWard = new TomSelect('#ward_select', tsOptions);

    // Local data loading for administrative boundaries
    let localData = [];

    fetch('/data/provinces.json')
        .then(res => res.json())
        .then(data => {
            // Nếu JSON mới là 1 Object (chỉ chứa 1 tỉnh) thay vì Array, tự bọc lại vào Array
            if (!Array.isArray(data)) {
                data = [data];
            }
            localData = data;
            
            // Tìm Ninh Bình với nhiều biến thể tên có thể có trong file JSON
            const ninhBinh = data.find(p =>
                p.Name === 'Ninh Bình' ||
                p.Name === 'Tỉnh Ninh Bình' ||
                p.Name.includes('Ninh B\u00ecnh') ||
                p.Name.includes('Ninh Bình')
            );
            
            if (ninhBinh) {
                tsProvince.addOption({value: ninhBinh.Name, text: ninhBinh.Name, id: ninhBinh.Id});
                tsProvince.setValue(ninhBinh.Name);
                tsProvince.lock();
                populateDistricts(ninhBinh.Name);
            } else {
                // Nếu không tìm thấy, load tất cả tỉnh để debug
                const options = data.map(p => ({value: p.Name, text: p.Name, id: p.Id}));
                tsProvince.addOptions(options);
                console.warn('[Checkout] Không tìm thấy Ninh Bình trong provinces.json. Các tỉnh hiện có:', data.map(p => p.Name));
            }

            // Sau khi data đã load xong, trigger địa chỉ mặc định nếu có
            if (tsSaved) {
                const val = tsSaved.getValue();
                if (val) {
                    setTimeout(() => {
                        tsSaved.trigger('change', val);
                    }, 150);
                }
            }
        });

    function populateDistricts(provinceName) {
        tsDistrict.clearOptions();
        tsDistrict.clear();
        tsWard.clearOptions();
        tsWard.clear();
        tsWard.disable();
        
        const province = localData.find(p => p.Name === provinceName);
        if (province && province.Districts) {
            tsDistrict.enable();
            const options = province.Districts.map(d => ({value: d.Name, text: d.Name, id: d.Id}));
            tsDistrict.addOptions(options);
            tsDistrict.refreshOptions(false);
        } else {
            tsDistrict.disable();
        }
    }

    tsProvince.on('change', function(value) {
        populateDistricts(value);
    });

    tsDistrict.on('change', function(value) {
        tsWard.clearOptions();
        tsWard.clear();
        
        if (value) {
            const provinceName = tsProvince.getValue();
            const province = localData.find(p => p.Name === provinceName);
            if (province) {
                const district = province.Districts.find(d => d.Name === value);
                if (district && district.Wards && district.Wards.length > 0) {
                    tsWard.enable();
                    const options = district.Wards.map(w => ({value: w.Name, text: w.Name, id: w.Id}));
                    tsWard.addOptions(options);
                    tsWard.refreshOptions(false);
                } else {
                    tsWard.disable();
                    document.getElementById('ward_select').removeAttribute('required');
                }
            }
        } else {
            tsWard.disable();
        }
    });

    let polygonLayer = null;
    let currentBoundaryGeoJSON = null;

    tsWard.on('change', async function(value) {
        const provinceName = tsProvince.getValue();
        const communeName = tsDistrict.getValue();   // Xã/Phường mới
        const residentialName = value;               // Thôn/Xóm/Tổ dân phố

        currentBoundaryGeoJSON = null;

        if (polygonLayer && map) {
            map.removeLayer(polygonLayer);
            polygonLayer = null;
        }

        if (!provinceName || !communeName || !residentialName) return;

        // Với dữ liệu mới: district = xã/phường, ward = thôn/TDP.
        // Không gọi API boundary bằng ward nữa vì ward bây giờ không phải cấp xã.
        // Thử tìm luôn vị trí tương đối theo alias GoogleSearch trong JSON.
        try {
            const residential = getSelectedResidentialArea();
            const fallbackCoords = await getCoordinatesWithFallback(
                residentialName,
                communeName,
                provinceName,
                residential
            );

            if (fallbackCoords) {
                initMap(fallbackCoords.lat, fallbackCoords.lon, false);
                await calculateShippingWithCoords();
                document.getElementById('map_helper_text').innerText =
                    'Đã tìm được khu vực tương đối. Vui lòng kéo ghim đến đúng vị trí nhận hàng.';
            }
        } catch (e) {
            console.error('Lỗi xác định vị trí khu dân cư', e);
        }
    });

    // Leaflet Map Integration
    let map = null;
    let marker = null;
    let currentCustomerCoords = null;
    let storeCoords = null;
    
    // Init store coords immediately
    const sl = parseFloat("{{ \App\Models\Setting::get('store_lat', '') }}");
    const slon = parseFloat("{{ \App\Models\Setting::get('store_lon', '') }}");
    if (!isNaN(sl) && !isNaN(slon)) {
        storeCoords = { lat: sl, lon: slon };
    }
    
    // Parse shipping tiers passed from backend
    let shippingTiers = [];
    try {
        shippingTiers = {!! $shippingTiers ?? '[]' !!};
    } catch(e) { console.error('Lỗi parse shipping tiers'); }

    function validateMarkerPosition(lat, lng) {
        return true; // Bỏ qua kiểm tra ranh giới, cho phép ghim tự do
    }

    let isReverseGeocoding = false;

    function fuzzyMatch(target, optionsObj) {
        if (!target) return null;
        const cleanTarget = target.toLowerCase().replace(/^(tỉnh|thành phố|quận|huyện|thị xã|phường|xã|thị trấn)\s+/i, '').trim();
        for (let key in optionsObj) {
            let optName = optionsObj[key].text;
            let cleanOpt = optName.toLowerCase().replace(/^(tỉnh|thành phố|quận|huyện|thị xã|phường|xã|thị trấn)\s+/i, '').trim();
            if (cleanOpt === cleanTarget || cleanOpt.includes(cleanTarget) || cleanTarget.includes(cleanOpt)) {
                return key; 
            }
        }
        return null;
    }

    function getSelectedResidentialArea() {
        const provinceName = tsProvince.getValue();
        const communeName = tsDistrict.getValue();
        const residentialName = tsWard.getValue();

        const province = localData.find(p => p.Name === provinceName);
        if (!province || !province.Districts) return null;

        const commune = province.Districts.find(d => d.Name === communeName);
        if (!commune || !commune.Wards) return null;

        return commune.Wards.find(w => w.Name === residentialName) || null;
    }

    async function reverseGeocode(lat, lng) {
        try {
            isReverseGeocoding = true;
            const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1`;
            const res = await fetch(url, { headers: { 'Accept-Language': 'vi' } });
            const data = await res.json();
            
            if (data && data.address) {
                const addr = data.address;
                const provName = addr.state || addr.city || addr.province;
                const distName = addr.county || addr.city_district || addr.district || addr.borough;
                const wardName = addr.suburb || addr.village || addr.quarter || addr.hamlet || addr.town;
                const roadName = addr.road || '';
                const houseNumber = addr.house_number || '';
                
                const fullStreet = houseNumber ? `${houseNumber} ${roadName}` : roadName;
                if (fullStreet) {
                    document.getElementById('specific_address').value = fullStreet.trim();
                }
                
                // Không tự động ghi đè Tỉnh/Xã-Phường/Thôn-TDP bằng dữ liệu reverse geocode cũ.
                // Nominatim/Google có thể vẫn trả về địa giới trước sáp nhập.
                // Chỉ dùng reverse geocode để gợi ý số nhà/đường phía trên.
            }
        } catch(e) {
            console.error('Lỗi định vị ngược', e);
        } finally {
            isReverseGeocoding = false;
        }
    }

    async function handleMapInteraction(lat, lng, isDrag = false) {
        marker.setLatLng([lat, lng]);
        currentCustomerCoords = { lat: lat, lon: lng };
        document.getElementById('input_delivery_lat').value = lat;
        document.getElementById('input_delivery_lon').value = lng;
        
        // Reverse geocode
        await reverseGeocode(lat, lng);
        
        await calculateShippingWithCoords();
    }

    function initMap(lat, lon, autoFitBoundary = false) {
        document.getElementById('map_container').style.display = 'block';
        if (!map) {
            map = L.map('map').setView([lat, lon], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            marker = L.marker([lat, lon], {draggable: true}).addTo(map);

            marker.on('dragend', function (e) {
                const position = marker.getLatLng();
                handleMapInteraction(position.lat, position.lng, true);
            });
            
            map.on('click', function(e) {
                handleMapInteraction(e.latlng.lat, e.latlng.lng);
            });
        } else {
            if (!autoFitBoundary) {
                map.setView([lat, lon], 15);
            }
            marker.setLatLng([lat, lon]);
        }
        
        // Draw Boundary
        if (currentBoundaryGeoJSON) {
            if (polygonLayer) {
                map.removeLayer(polygonLayer);
            }
            polygonLayer = L.geoJSON(currentBoundaryGeoJSON, {
                style: {
                    color: '#006e1c',
                    weight: 2,
                    opacity: 0.6,
                    fillOpacity: 0.1
                }
            }).addTo(map);
            
            if (autoFitBoundary) {
                map.fitBounds(polygonLayer.getBounds());
            }
        }
        
        // Only set coords if it's valid
        if (validateMarkerPosition(lat, lon)) {
            currentCustomerCoords = { lat: lat, lon: lon };
            document.getElementById('input_delivery_lat').value = lat;
            document.getElementById('input_delivery_lon').value = lon;
        } else {
            showShippingError('Vị trí mặc định nằm ngoài xã/phường. Vui lòng chọn lại trên bản đồ.');
        }
    }

    document.getElementById('btn_find_location').addEventListener('click', async function() {
        const p = tsProvince.getValue();
        const d = tsDistrict.getValue();
        const w = tsWard.getValue();
        // Kiểm tra xem dropdown Thôn/Xóm có option nào không
        const requiresWard = Object.keys(tsWard.options).length > 0;

        // Địa chỉ cụ thể chỉ để shipper đọc, KHÔNG dùng để tìm bản đồ.

        if (!p || !d || (requiresWard && !w)) {
            showShippingWarning('Vui lòng chọn đầy đủ cấp địa chỉ hiện có trước khi tìm vị trí.');
            return;
        }

        const btn = this;
        const originalHtml = btn.innerHTML;
        btn.innerHTML = 'Đang tìm...';
        btn.disabled = true;

        const residential = getSelectedResidentialArea();
        const coords = await getCoordinatesWithFallback(w, d, p, residential);
        if (coords) {
            initMap(coords.lat, coords.lon);
            await calculateShippingWithCoords();
        } else {
            showShippingError('Không thể tìm thấy vị trí. Vui lòng thử lại.');
        }

        btn.innerHTML = originalHtml;
        btn.disabled = false;
    });

    document.getElementById('btn_current_location').addEventListener('click', function() {
        if (!navigator.geolocation) {
            showShippingError('Trình duyệt của bạn không hỗ trợ định vị.');
            return;
        }

        const btn = this;
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<span class="material-symbols-outlined animate-spin">refresh</span>';
        btn.disabled = true;

        navigator.geolocation.getCurrentPosition(
            async function(position) {
                const lat = position.coords.latitude;
                const lon = position.coords.longitude;
                initMap(lat, lon);
                await calculateShippingWithCoords();
                btn.innerHTML = originalHtml;
                btn.disabled = false;
            },
            function(error) {
                showShippingError('Không thể lấy vị trí. Vui lòng cho phép quyền truy cập vị trí trên trình duyệt.');
                btn.innerHTML = originalHtml;
                btn.disabled = false;
            },
            { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
        );
    });

    function cleanAddress(addr) {
        if (!addr) return '';
        return addr.replace(/^(Tỉnh|Thành phố|Huyện|Quận|Thị xã|Xã|Phường|Thị trấn|Thôn|Xóm|Tổ dân phố)\s+/i, '').trim();
    }

    async function geocode(address) {
        try {
            const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}&limit=1&email=contact@cozyhna.com&countrycodes=vn`;
            const response = await fetch(url, { headers: { 'Accept-Language': 'vi' } });
            const data = await response.json();
            if (data && data.length > 0) {
                return { lat: data[0].lat, lon: data[0].lon, address: address };
            }
            return null;
        } catch (e) {
            console.error('Geocode error:', e);
            return null;
        }
    }

    async function getCoordinatesWithFallback(residentialArea, commune, province, residentialObj = null) {
        const cResidential = cleanAddress(residentialArea);
        const cCommune = cleanAddress(commune);
        const cProvince = cleanAddress(province);

        // 1) Ưu tiên tọa độ cố định của Thôn/Xóm/TDP nếu JSON đã có.
        if (residentialObj) {
            const lat = parseFloat(
                residentialObj.Latitude ??
                residentialObj.latitude ??
                residentialObj.lat ??
                ''
            );
            const lon = parseFloat(
                residentialObj.Longitude ??
                residentialObj.longitude ??
                residentialObj.lng ??
                residentialObj.lon ??
                ''
            );

            if (!isNaN(lat) && !isNaN(lon)) {
                document.getElementById('map_helper_text').innerText =
                    'Đã xác định khu vực Thôn/Xóm/Tổ dân phố. Vui lòng kéo ghim đến đúng vị trí nhận hàng.';
                return {
                    lat: lat,
                    lon: lon,
                    source: 'residential_coords'
                };
            }
        }

        // 2) Dùng các alias dành riêng cho bản đồ trong provinces.json.
        const configuredQueries = [];

        if (residentialObj) {
            if (residentialObj.GoogleSearch) {
                configuredQueries.push(residentialObj.GoogleSearch);
            }

            if (Array.isArray(residentialObj.GoogleSearchFallbacks)) {
                configuredQueries.push(...residentialObj.GoogleSearchFallbacks);
            }
        }

        // Xử lý tên tỉnh đặc biệt (do OSM có thể chưa cập nhật ranh giới 2025)
        let provSearch1 = cProvince;
        let provSearch2 = cProvince;
        if (province.includes('Khu vực Hà Nam cũ') || province.includes('Ninh Bình')) {
            provSearch1 = 'Ninh Bình';
            provSearch2 = 'Hà Nam';
        }

        // 3) Fallback bằng tên địa danh.
        const queries = [
            ...configuredQueries
        ];

        if (residentialArea) {
            queries.push(
                `${residentialArea}, ${commune}, ${provSearch1}`,
                `${cResidential}, ${cCommune}, ${provSearch1}`,
                `${residentialArea}, ${commune}, ${provSearch2}`,
                `${cResidential}, ${cCommune}, ${provSearch2}`
            );
        }

        const uniqueQueries = [...new Set(
            queries.map(q => (q || '').trim()).filter(Boolean)
        )];

        for (const query of uniqueQueries) {
            const coords = await geocode(query);
            if (coords) {
                document.getElementById('map_helper_text').innerText =
                    'Đã tìm được khu vực gần đúng của Thôn/Xóm/Tổ dân phố. Vui lòng kiểm tra và kéo ghim đến đúng vị trí nhận hàng.';
                return {
                    ...coords,
                    source: 'geocode',
                    query: query
                };
            }
        }

        // 4) Không tìm được TDP thì mới fallback về xã/phường.
        const communeQueries = [
            `${commune}, ${provSearch1}`,
            `${cCommune}, ${provSearch1}`,
            `${commune}, ${provSearch2}`,
            `${cCommune}, ${provSearch2}`
        ];

        const uniqueCommuneQueries = [...new Set(
            communeQueries.map(q => (q || '').trim()).filter(Boolean)
        )];

        for (const query of uniqueCommuneQueries) {
            const coords = await geocode(query);
            if (coords) {
                document.getElementById('map_helper_text').innerText =
                    'Chưa xác định được chính xác Thôn/Xóm/Tổ dân phố. Bản đồ đang hiển thị Xã/Phường, vui lòng kéo ghim đến đúng vị trí nhận hàng.';
                return {
                    ...coords,
                    source: 'commune_fallback',
                    query: query
                };
            }
        }

        // 5) Cuối cùng mới dùng vị trí cửa hàng / tâm tỉnh.
        if (storeCoords) {
            document.getElementById('map_helper_text').innerText =
                'Không tìm được địa danh tự động. Đang hiển thị vị trí cửa hàng, vui lòng kéo ghim đến đúng nơi nhận.';
            return {
                lat: storeCoords.lat,
                lon: storeCoords.lon,
                source: 'store_fallback'
            };
        }

        document.getElementById('map_helper_text').innerText =
            'Không tìm được địa danh tự động. Vui lòng kéo ghim tới đúng vị trí nhận hàng.';

        return {
            lat: 20.2506,
            lon: 105.9745,
            source: 'province_fallback'
        };
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

    async function calculateShippingWithCoords() {
        if (isCalculating || !currentCustomerCoords) return;
        isCalculating = true;
        
        document.getElementById('display_distance').innerText = 'Đang tính toán...';
        document.getElementById('display_shipping_fee').innerText = '...';

        if (!storeCoords) {
            const storeLat = "{{ $storeLat ?? '' }}";
            const storeLon = "{{ $storeLon ?? '' }}";
            
            if (storeLat && storeLon) {
                storeCoords = { lat: parseFloat(storeLat), lon: parseFloat(storeLon) };
            } else {
                // Tọa độ CozyHNA giả định nếu admin quên set
                storeCoords = { lat: 21.0285, lon: 105.8542 }; 
            }
        }

        let distanceKm = null;
        if (storeCoords && currentCustomerCoords) {
            const distance = await getDistanceOSRM(storeCoords.lon, storeCoords.lat, currentCustomerCoords.lon, currentCustomerCoords.lat);
            if (distance !== null) {
                distanceKm = parseFloat(distance.toFixed(1));
            }
        }
        
        isCalculating = false;
        
        const baseFee = {{ $baseFee ?? 15000 }};
        const feePerKm = {{ $feePerKm ?? 5000 }};
        const maxRadius = {{ $maxRadius ?? 0 }};

        if (distanceKm === null) {
            showShippingWarning('Không thể tính khoảng cách tự động. Phí ship tạm tính là ' + new Intl.NumberFormat('vi-VN').format(baseFee) + 'đ.');
            updateCheckoutUI(0, baseFee, 'Không xác định', new Intl.NumberFormat('vi-VN').format(baseFee) + ' đ *');
            return;
        }

        if (maxRadius > 0 && distanceKm > maxRadius) {
            showShippingError(`Khoảng cách giao hàng (${distanceKm} km) vượt quá giới hạn (${maxRadius} km).`);
            updateCheckoutUI(0, 0, distanceKm + ' km ❌', 'Ngoài vùng giao hàng');
            return;
        }

        // Tính phí ship theo bảng giá (Tiered Pricing)
        let shippingFee = null;
        
        if (shippingTiers.length > 0) {
            for (let i = 0; i < shippingTiers.length; i++) {
                if (distanceKm <= shippingTiers[i].max_km) {
                    shippingFee = shippingTiers[i].fee;
                    break;
                }
            }
            if (shippingFee === null) {
                // Vượt mốc cao nhất
                const highestTier = shippingTiers[shippingTiers.length - 1];
                const extraKm = distanceKm - highestTier.max_km;
                shippingFee = highestTier.fee + Math.round(extraKm * feePerKm);
            }
        } else {
            // Cũ (không có bảng giá)
            shippingFee = Math.max(feePerKm > 0 ? distanceKm * feePerKm : baseFee, baseFee);
        }

        shippingFee = Math.round(shippingFee / 1000) * 1000;
        
        updateCheckoutUI(distanceKm, shippingFee, distanceKm + ' km', new Intl.NumberFormat('vi-VN').format(shippingFee) + ' đ');
    }

    function updateCheckoutUI(distanceKm, fee, distText, feeText) {
        document.getElementById('input_distance_km').value = distanceKm;
        document.getElementById('input_shipping_fee').value = fee;
        document.querySelectorAll('.shipping_fee_input').forEach(el => el.value = fee);
        document.getElementById('display_distance').innerText = distText;
        document.getElementById('display_shipping_fee').innerText = feeText;
        updateTotal(fee);
    }

    function showShippingWarning(msg) {
        showToast(msg, 'warning');
    }

    function showShippingError(msg) {
        showToast(msg, 'error');
    }

    function updateTotal(shippingFee) {
        const subtotal = {{ $subtotal ?? 0 }};
        const discount = {{ $discountAmount ?? 0 }};
        const shippingDiscount = {{ $shippingDiscountAmount ?? 0 }};
        let total = subtotal - discount + shippingFee - shippingDiscount;
        if (total < 0) total = 0;
        document.getElementById('display_total').innerText = new Intl.NumberFormat('vi-VN').format(total) + ' đ';
    }

    if (tsSaved) {
        tsSaved.on('change', function(value) {
            const el = tsSaved.getItem(value);
            const rawOption = tsSaved.options[value];
            
            const manualForm = document.getElementById('manual_address_form');
            if (manualForm) {
                if (!value || value === "") {
                    manualForm.style.display = 'block';
                } else {
                    manualForm.style.display = 'none';
                }
            }
            
            if (!value || value === "") {
                document.getElementById('receiver_name').value = '';
                document.getElementById('receiver_phone').value = '';
                document.getElementById('specific_address').value = '';
                // Không xóa tỉnh - giữ nguyên Ninh Bình/Hà Nam cũ mặc định
                tsDistrict.clearOptions(); tsDistrict.clear(); tsDistrict.disable();
                tsWard.clearOptions(); tsWard.clear(); tsWard.disable();
                document.getElementById('map_container').style.display = 'none';
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
                
                const dName = originalOption.dataset.district;
                const wName = originalOption.dataset.ward;
                
                // Tỉnh luôn là Hà Nam cũ (Tỉnh Ninh Bình) và đã được lock - không gọi setValue để tránh bị clear
                // Chỉ cần populate districts từ giá trị hiện tại của tsProvince
                const currentProvince = tsProvince.getValue();
                if (currentProvince) {
                    populateDistricts(currentProvince);
                }
                
                const lat = originalOption.dataset.lat;
                const lon = originalOption.dataset.lon;
                
                // wait for district to load
                setTimeout(() => {
                    tsDistrict.setValue(dName, true);
                    setTimeout(() => {
                        tsWard.setValue(wName, true);
                        if (lat && lon) {
                            initMap(parseFloat(lat), parseFloat(lon));
                            calculateShippingWithCoords();
                            document.getElementById('map_helper_text').innerText = 'Đã tải vị trí đã lưu.';
                        }
                    }, 500); // Wait for ward load
                }, 500); // Wait for district load
            }
        });
        
        const btnShowManual = document.getElementById('btn_show_manual_form');
        if (btnShowManual) {
            btnShowManual.addEventListener('click', function() {
                tsSaved.setValue('');
            });
        }
    }
});
</script>
@endpush
@endsection