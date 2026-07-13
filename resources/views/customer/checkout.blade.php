@extends('layouts.customer')

@section('title', 'Thanh Toán')

@section('content')
<main class="mt-24 pb-24 max-w-container-max mx-auto px-4 md:px-lg">
<div class="mb-xl">
<h1 class="font-headline-lg text-headline-lg text-on-background">Thanh Toán</h1>
<p class="font-body-md text-body-md text-on-surface-variant">Complete your order and we'll start brewing.</p>
</div>
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
<button class="text-primary font-label-md text-label-md hover:underline decoration-2 underline-offset-4 transition-all" onclick="toggleModal()">Change</button>
</div>
<div class="p-md rounded-lg bg-surface-container-low border border-outline-variant/30">
@if(count($addresses) > 0)
    @php $defaultAddress = $addresses[0]; @endphp
    <p class="font-body-lg text-body-lg font-medium">{{ $defaultAddress->receiver_name }} - {{ $defaultAddress->receiver_phone }}</p>
    <p class="font-body-md text-body-md text-on-surface-variant">{{ $defaultAddress->address }}</p>
    <p class="font-body-md text-body-md text-on-surface-variant">{{ $defaultAddress->ward }}, {{ $defaultAddress->district }}, {{ $defaultAddress->province }}</p>
@else
    <p class="font-body-lg text-body-lg font-medium">{{ $user->username }}</p>
    <p class="font-body-md text-body-md text-on-surface-variant text-primary italic cursor-pointer">Vui lòng thêm địa chỉ giao hàng</p>
@endif
</div>
</section>
<!-- Delivery Method -->
<section class="bg-surface-container-lowest rounded-xl p-lg custom-shadow">
<h2 class="font-title-lg text-title-lg flex items-center gap-xs mb-md">
<span class="material-symbols-outlined text-primary">local_shipping</span>
                        Delivery Method
                    </h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-md">
<label class="cursor-pointer group">
<input checked="" class="hidden peer" name="delivery" type="radio"/>
<div class="p-md rounded-xl border border-outline-variant/30 peer-checked:border-primary peer-checked:bg-primary/5 group-hover:bg-surface-container-high transition-all">
<div class="flex items-center justify-between">
<span class="font-body-lg text-body-lg font-semibold">Standard</span>
<span class="font-body-lg text-body-lg text-primary">0 VNĐ</span>
</div>
<p class="font-label-md text-label-md text-on-surface-variant mt-xs">Estimated: 15-25 mins</p>
</div>
</label>
<label class="cursor-pointer group">
<input class="hidden peer" name="delivery" type="radio"/>
<div class="p-md rounded-xl border border-outline-variant/30 peer-checked:border-primary peer-checked:bg-primary/5 group-hover:bg-surface-container-high transition-all">
<div class="flex items-center justify-between">
<span class="font-body-lg text-body-lg font-semibold">Express</span>
<span class="font-body-lg text-body-lg text-primary">0 VNĐ</span>
</div>
<p class="font-label-md text-label-md text-on-surface-variant mt-xs">Estimated: 8-12 mins</p>
</div>
</label>
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
<input checked="" class="w-5 h-5 text-primary border-outline focus:ring-primary" name="payment" type="radio"/>
<span class="material-symbols-outlined text-on-surface-variant">credit_card</span>
<div class="flex-grow">
<span class="font-body-lg text-body-lg font-medium">Credit Card</span>
<p class="font-label-md text-label-md text-on-surface-variant">Visa ending in 4242</p>
</div>
<span class="material-symbols-outlined text-on-surface-variant">chevron_right</span>
</label>
<label class="flex items-center gap-md p-md rounded-xl border border-outline-variant/30 cursor-pointer hover:bg-surface-container-high transition-all has-[:checked]:border-primary has-[:checked]:bg-primary/5">
<input class="w-5 h-5 text-primary border-outline focus:ring-primary" name="payment" type="radio"/>
<span class="material-symbols-outlined text-on-surface-variant">branding_watermark</span>
<span class="font-body-lg text-body-lg font-medium flex-grow">Apple Pay</span>
<span class="material-symbols-outlined text-on-surface-variant">chevron_right</span>
</label>
<label class="flex items-center gap-md p-md rounded-xl border border-outline-variant/30 cursor-pointer hover:bg-surface-container-high transition-all has-[:checked]:border-primary has-[:checked]:bg-primary/5">
<input class="w-5 h-5 text-primary border-outline focus:ring-primary" name="payment" type="radio"/>
<span class="material-symbols-outlined text-on-surface-variant">payments</span>
<span class="font-body-lg text-body-lg font-medium flex-grow">Cash on Delivery</span>
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
<span>0 VNĐ</span>
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
<span class="text-primary font-bold">{{ number_format($finalTotal, 0, ',', '.') }} VNĐ</span>
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
<button class="w-full py-md bg-primary-container text-on-primary-container font-headline-md text-headline-md rounded-xl hover:shadow-lg active:scale-[0.98] transition-all flex items-center justify-center gap-sm">
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
@endpush
