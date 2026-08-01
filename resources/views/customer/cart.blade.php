@extends('layouts.customer')

@section('title', 'Giỏ hàng của bạn')

@section('content')
<main class="mt-24 pb-24 max-w-container-max mx-auto px-4 md:px-lg">
    {{-- Flash messages --}}
    @if(session('error'))
        <div class="mb-md p-md bg-red-100 text-red-700 rounded-xl font-body-md">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="mb-md p-md bg-green-100 text-green-700 rounded-xl font-body-md">{{ session('success') }}</div>
    @endif

    <div class="mb-xl">
        <h1 class="font-headline-lg text-headline-lg text-on-background">Giỏ hàng</h1>
        <p class="font-body-md text-body-md text-on-surface-variant">Quản lý các mặt hàng bạn đã chọn.</p>
    </div>

    @if($cartItems->isEmpty())
        <div class="text-center py-2xl">
            <span class="material-symbols-outlined text-[80px] text-outline-variant">shopping_cart</span>
            <h2 class="font-headline-md text-headline-md text-on-surface mt-md">Giỏ hàng trống</h2>
            <p class="text-on-surface-variant font-body-md mt-xs mb-xl">Hãy thêm đồ uống vào giỏ hàng để tiếp tục nhé!</p>
            <a href="/" class="bg-primary text-white px-xl py-md rounded-xl font-bold hover:bg-primary/90 transition-all">Xem thực đơn</a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter items-start">
            {{-- Left Column: Cart Items --}}
            <div class="lg:col-span-8 space-y-md">
                <div class="bg-surface-container-lowest rounded-xl p-md shadow-sm border border-outline-variant/10 flex items-center justify-between">
                    <label class="flex items-center gap-xs cursor-pointer select-none">
                        <input type="checkbox" id="selectAll" class="w-5 h-5 text-primary rounded border-outline-variant focus:ring-primary checked:bg-primary" checked>
                        <span class="font-label-lg font-bold">Chọn tất cả ({{ $cartItems->count() }})</span>
                    </label>
                </div>

                <div class="bg-surface-container-lowest rounded-xl p-lg shadow-sm border border-outline-variant/10 space-y-md divide-y divide-outline-variant/20">
                    @foreach($cartItems as $item)
                        @php
                            $product = $item->productSize->product ?? $item->product;
                            $size    = $item->productSize->size ?? null;
                            $price   = $item->unit_price;
                            $itemTotal = $price * $item->quantity;
                        @endphp
                        <div class="py-md flex items-start gap-md cart-item-row" data-id="{{ $item->id }}" data-price="{{ $price }}" data-quantity="{{ $item->quantity }}">
                            <div class="pt-2">
                                <input type="checkbox" name="selected_items[]" value="{{ $item->id }}" class="item-checkbox w-5 h-5 text-primary rounded border-outline-variant focus:ring-primary checked:bg-primary" checked>
                            </div>
                            
                            <a href="/customer/product_detail?id={{ $product->id ?? '' }}" class="w-24 h-24 rounded-lg overflow-hidden flex-shrink-0 bg-surface-container block hover:opacity-80 transition-opacity">
                                @if($product && $product->image)
                                    <img class="w-full h-full object-cover" src="{{ $product->image }}" alt="{{ $product->name }}"/>
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <span class="material-symbols-outlined text-outline-variant text-[36px]">local_cafe</span>
                                    </div>
                                @endif
                            </a>
                            
                            <div class="flex-grow flex flex-col justify-between min-h-[6rem]">
                                <div>
                                    <a href="/customer/product_detail?id={{ $product->id ?? '' }}" class="font-body-lg text-body-lg font-semibold hover:text-primary transition-colors">{{ $product->name ?? 'Sản phẩm' }}</a>
                                    <p class="font-label-md text-label-md text-on-surface-variant">Size: {{ $size->name ?? 'Mặc định' }}</p>
                                    @if(isset($item->toppings) && count($item->toppings) > 0)
                                        <div class="mt-1">
                                            @foreach($item->toppings as $topping)
                                                <p class="font-label-sm text-label-sm text-on-surface-variant">+ {{ $topping['name'] }}</p>
                                            @endforeach
                                        </div>
                                    @endif
                                    <p class="font-body-lg text-body-lg font-bold text-primary mt-1">{{ number_format($price, 0, ',', '.') }} đ</p>
                                </div>
                                
                                <div class="flex items-center gap-md mt-sm">
                                    <div class="flex items-center bg-surface-container border border-outline-variant/30 rounded-lg overflow-hidden">
                                        <button type="button" class="btn-decrease px-3 py-1 hover:bg-surface-container-high transition-colors material-symbols-outlined text-[20px] text-on-surface-variant" data-id="{{ $item->id }}">-</button>
                                        <span class="px-4 py-1 font-body-md font-semibold bg-surface-container-lowest quantity-display">{{ $item->quantity }}</span>
                                        <button type="button" class="btn-increase px-3 py-1 hover:bg-surface-container-high transition-colors material-symbols-outlined text-[20px] text-on-surface-variant" data-id="{{ $item->id }}">+</button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex flex-col justify-between items-end min-h-[6rem]">
                                <button type="button" class="btn-remove text-error hover:bg-error/10 p-2 rounded-full transition-colors material-symbols-outlined" data-id="{{ $item->id }}" title="Xóa">delete</button>
                                <p class="font-title-md font-bold text-primary item-total-display">{{ number_format($itemTotal, 0, ',', '.') }} đ</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Right Column: Summary & Checkout --}}
            <div class="lg:col-span-4 sticky top-24">
                <div class="bg-surface-container-lowest rounded-xl p-lg shadow-sm border border-outline-variant/10">
                    <h2 class="font-title-lg text-title-lg mb-md">Tóm tắt đơn hàng</h2>
                    
                    <div class="flex justify-between items-center mb-md pb-md border-b border-outline-variant/20">
                        <span class="font-body-md text-on-surface-variant">Tổng tiền (<span id="selectedCountDisplay">{{ $cartItems->count() }}</span> sản phẩm)</span>
                        <span class="font-title-lg font-bold text-primary" id="subtotalDisplay">{{ number_format($subtotal, 0, ',', '.') }} đ</span>
                    </div>
                    
                    <p class="font-label-sm text-on-surface-variant mb-md text-center">Phí vận chuyển và thuế sẽ được tính ở bước thanh toán.</p>
                    
                    <button type="button" id="btnCheckout" class="w-full bg-primary text-white py-md rounded-xl font-bold hover:bg-primary/90 transition-all flex items-center justify-center gap-xs">
                        Tiến hành thanh toán
                        <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const formatMoney = (amount) => new Intl.NumberFormat('vi-VN').format(amount) + ' đ';
    
    const checkboxes = document.querySelectorAll('.item-checkbox');
    const selectAll = document.getElementById('selectAll');
    const subtotalDisplay = document.getElementById('subtotalDisplay');
    const selectedCountDisplay = document.getElementById('selectedCountDisplay');
    const btnCheckout = document.getElementById('btnCheckout');

    // Cập nhật tổng tiền dựa trên checkbox
    function updateTotals() {
        let total = 0;
        let count = 0;
        checkboxes.forEach(cb => {
            if (cb.checked) {
                const row = cb.closest('.cart-item-row');
                const price = parseFloat(row.dataset.price);
                const quantity = parseInt(row.dataset.quantity);
                total += price * quantity;
                count++;
            }
        });
        
        if (subtotalDisplay) subtotalDisplay.textContent = formatMoney(total);
        if (selectedCountDisplay) selectedCountDisplay.textContent = count;
        
        if (selectAll) {
            selectAll.checked = count > 0 && count === checkboxes.length;
        }
        
        if (btnCheckout) {
            btnCheckout.disabled = count === 0;
            btnCheckout.classList.toggle('opacity-50', count === 0);
            btnCheckout.classList.toggle('cursor-not-allowed', count === 0);
        }
    }

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateTotals();
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateTotals);
    });

    // Xử lý tăng giảm số lượng
    const updateCartQty = async (id, newQty, row) => {
        if (newQty < 1) return;
        try {
            const res = await fetch(`/cart/update/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ quantity: newQty })
            });
            const data = await res.json();
            if (data.success) {
                row.dataset.quantity = newQty;
                row.querySelector('.quantity-display').textContent = newQty;
                const price = parseFloat(row.dataset.price);
                row.querySelector('.item-total-display').textContent = formatMoney(price * newQty);
                
                const cartBadge = document.getElementById('cart-badge');
                if(cartBadge) {
                    cartBadge.textContent = data.cart_item_count;
                    cartBadge.classList.remove('hidden');
                }
                
                updateTotals();
            }
        } catch (e) {
            console.error(e);
            alert("Có lỗi xảy ra khi cập nhật số lượng.");
        }
    };

    document.querySelectorAll('.btn-increase').forEach(btn => {
        btn.addEventListener('click', function() {
            const row = this.closest('.cart-item-row');
            let currentQty = parseInt(row.dataset.quantity);
            updateCartQty(this.dataset.id, currentQty + 1, row);
        });
    });

    document.querySelectorAll('.btn-decrease').forEach(btn => {
        btn.addEventListener('click', function() {
            const row = this.closest('.cart-item-row');
            let currentQty = parseInt(row.dataset.quantity);
            if (currentQty > 1) {
                updateCartQty(this.dataset.id, currentQty - 1, row);
            }
        });
    });

    // Xóa item
    document.querySelectorAll('.btn-remove').forEach(btn => {
        btn.addEventListener('click', async function() {
            if (!confirm('Bạn có chắc chắn muốn xóa món này khỏi giỏ hàng?')) return;
            const id = this.dataset.id;
            try {
                const res = await fetch(`/cart/remove/${id}`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken }
                });
                const data = await res.json();
                if (data.success) {
                    const row = this.closest('.cart-item-row');
                    row.remove();
                    
                    const cartBadge = document.getElementById('cart-badge');
                    if(cartBadge) {
                        if (data.cart_item_count > 0) {
                            cartBadge.textContent = data.cart_item_count;
                        } else {
                            cartBadge.classList.add('hidden');
                        }
                    }
                    
                    // Xóa checkbox khỏi danh sách để không tính lỗi
                    const remainingCheckboxes = document.querySelectorAll('.item-checkbox');
                    if (remainingCheckboxes.length === 0) {
                        window.location.reload(); // reload để hiện giỏ hàng trống
                    } else {
                        updateTotals();
                    }
                }
            } catch (e) {
                console.error(e);
            }
        });
    });

    // Xử lý nút Tiến hành thanh toán
    if (btnCheckout) {
        btnCheckout.addEventListener('click', async function() {
            const selectedIds = [];
            document.querySelectorAll('.item-checkbox:checked').forEach(cb => {
                selectedIds.push(cb.value);
            });

            if (selectedIds.length === 0) return;

            // Vô hiệu hóa nút để tránh double click
            const originalText = this.innerHTML;
            this.innerHTML = '<span class="material-symbols-outlined animate-spin text-[20px]">refresh</span> Đang xử lý...';
            this.disabled = true;

            try {
                const res = await fetch('/customer/checkout/init', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ selected_items: selectedIds })
                });
                
                const data = await res.json();
                if (data.success && data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    alert(data.error || 'Có lỗi xảy ra.');
                    this.innerHTML = originalText;
                    this.disabled = false;
                }
            } catch (e) {
                console.error(e);
                alert("Lỗi kết nối.");
                this.innerHTML = originalText;
                this.disabled = false;
            }
        });
    }

    // Init
    updateTotals();
});
</script>
@endsection
