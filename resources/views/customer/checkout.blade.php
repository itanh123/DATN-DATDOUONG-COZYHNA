@extends('layouts.customer')

@section('title', 'Thanh Toán')

@section('content')
<main class="mt-24 pb-24 max-w-container-max mx-auto px-4 md:px-lg">

{{-- Flash messages --}}
@if(session('error'))
    <div class="mb-md p-md bg-red-100 text-red-700 rounded-xl font-body-md">{{ session('error') }}</div>
@endif

<div class="mb-xl">
    <h1 class="font-headline-lg text-headline-lg text-on-background">Thanh Toán</h1>
    <p class="font-body-md text-body-md text-on-surface-variant">Kiểm tra đơn hàng và hoàn tất thanh toán.</p>
</div>

@if($cartItems->isEmpty())
    <div class="text-center py-2xl">
        <span class="material-symbols-outlined text-[80px] text-outline-variant">shopping_cart</span>
        <h2 class="font-headline-md text-headline-md text-on-surface mt-md">Giỏ hàng trống</h2>
        <p class="text-on-surface-variant font-body-md mt-xs mb-xl">Hãy thêm đồ uống vào giỏ hàng để tiếp tục nhé!</p>
        <a href="/" class="bg-primary text-white px-xl py-md rounded-xl font-bold hover:bg-primary/90 transition-all">Xem thực đơn</a>
    </div>
@else
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
                    $product = $item->productSize->product ?? null;
                    $size    = $item->productSize->size ?? null;
                    $price   = $item->productSize->selling_price ?? 0;
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
                        <p class="font-label-md text-label-md text-on-surface-variant">{{ $size->name ?? '' }}</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="font-body-lg text-body-lg font-bold text-primary">{{ number_format($price * $item->quantity, 0, ',', '.') }} đ</p>
                        <p class="font-label-md text-label-md text-on-surface-variant">SL: {{ $item->quantity }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-md text-right">
                <a href="/" class="text-primary font-label-md hover:underline">+ Thêm sản phẩm</a>
            </div>
        </section>

        {{-- Delivery Info Form --}}
        <section class="bg-surface-container-lowest rounded-xl p-lg shadow-sm border border-outline-variant/10">
            <h2 class="font-title-lg text-title-lg flex items-center gap-xs mb-md">
                <span class="material-symbols-outlined text-primary">location_on</span>
                Thông tin giao hàng
            </h2>

            @php $defaultAddr = $addresses->firstWhere('is_default', true) ?? $addresses->first(); @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                <div>
                    <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-xs block">Tên người nhận *</label>
                    <input type="text" name="receiver_name" required
                        value="{{ old('receiver_name', optional($defaultAddr)->receiver_name ?? '') }}"
                        class="w-full p-md rounded-xl bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 text-body-md transition-colors"
                        placeholder="Nhập tên người nhận"/>
                </div>
                <div>
                    <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-xs block">Số điện thoại *</label>
                    <input type="text" name="receiver_phone" required
                        value="{{ old('receiver_phone', optional($defaultAddr)->receiver_phone ?? '') }}"
                        class="w-full p-md rounded-xl bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 text-body-md transition-colors"
                        placeholder="Số điện thoại"/>
                </div>
            </div>
            <div class="mt-md">
                <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-xs block">Địa chỉ giao hàng *</label>
                <input type="text" name="address" required
                    value="{{ old('address', optional($defaultAddr)->address ?? '') }}"
                    class="w-full p-md rounded-xl bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 text-body-md transition-colors"
                    placeholder="Số nhà, tên đường, phường, quận, thành phố"/>
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
                    <input checked name="payment_method" type="radio" value="cash" class="w-5 h-5 text-primary border-outline focus:ring-primary"/>
                    <span class="material-symbols-outlined text-on-surface-variant">payments</span>
                    <div class="flex-grow">
                        <span class="font-body-lg text-body-lg font-medium">Tiền mặt khi nhận hàng (COD)</span>
                        <p class="font-label-md text-label-md text-on-surface-variant">Thanh toán bằng tiền mặt khi nhận đơn</p>
                    </div>
                </label>
                <label class="flex items-center gap-md p-md rounded-xl border border-outline-variant/30 cursor-pointer hover:bg-surface-container-high transition-all has-[:checked]:border-primary has-[:checked]:bg-primary/5 opacity-60">
                    <input disabled name="payment_method" type="radio" value="momo" class="w-5 h-5 text-primary border-outline focus:ring-primary"/>
                    <span class="material-symbols-outlined text-on-surface-variant">phone_iphone</span>
                    <div class="flex-grow">
                        <span class="font-body-lg text-body-lg font-medium">MoMo</span>
                    </div>
                    <span class="text-xs px-2 py-1 bg-gray-100 rounded-full text-gray-500 font-label-sm">Sắp có</span>
                </label>
                <label class="flex items-center gap-md p-md rounded-xl border border-outline-variant/30 cursor-pointer hover:bg-surface-container-high transition-all has-[:checked]:border-primary has-[:checked]:bg-primary/5 opacity-60">
                    <input disabled name="payment_method" type="radio" value="vnpay" class="w-5 h-5 text-primary border-outline focus:ring-primary"/>
                    <span class="material-symbols-outlined text-on-surface-variant">credit_card</span>
                    <div class="flex-grow">
                        <span class="font-body-lg text-body-lg font-medium">VNPay</span>
                    </div>
                    <span class="text-xs px-2 py-1 bg-gray-100 rounded-full text-gray-500 font-label-sm">Sắp có</span>
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
                    <span>Phí giao hàng</span>
                    <span>{{ number_format($deliveryFee, 0, ',', '.') }} đ</span>
                </div>
                <div class="flex justify-between font-body-md text-body-md text-on-surface-variant">
                    <span>Thuế VAT (8%)</span>
                    <span>{{ number_format($tax, 0, ',', '.') }} đ</span>
                </div>
                <div class="pt-sm border-t border-outline-variant/20">
                    <div class="flex justify-between font-headline-md text-headline-md text-on-background">
                        <span>Tổng cộng</span>
                        <span class="text-primary">{{ number_format($total, 0, ',', '.') }} đ</span>
                    </div>
                </div>
            </div>

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
@endif
</main>
@endsection
