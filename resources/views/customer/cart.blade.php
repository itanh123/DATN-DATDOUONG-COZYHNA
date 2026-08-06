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
                    
                    <div class="flex justify-between items-center mb-sm">
                        <span class="font-body-md text-on-surface-variant">Tổng tiền (<span id="selectedCountDisplay">{{ $cartItems->count() }}</span> sản phẩm)</span>
                        <span class="font-title-md font-bold text-on-surface" id="subtotalDisplay">{{ number_format($subtotal, 0, ',', '.') }} đ</span>
                    </div>

                    <div id="discountContainer" class="flex justify-between items-center mb-md pb-md border-b border-outline-variant/20 {{ (!isset($discountAmount) || $discountAmount <= 0) ? 'hidden' : '' }}">
                        <span class="font-body-md text-error">Giảm giá (Voucher)</span>
                        <span class="font-title-md font-bold text-error" id="discountDisplay">-{{ number_format($discountAmount ?? 0, 0, ',', '.') }} đ</span>
                    </div>
                    <div class="flex justify-between items-center mb-md {{ (!isset($discountAmount) || $discountAmount <= 0) ? 'pb-md border-b border-outline-variant/20' : '' }}" id="finalTotalContainer">
                        <span class="font-body-md font-bold text-on-surface-variant">Tạm tính</span>
                        <span class="font-title-lg font-bold text-primary" id="finalTotalDisplay">{{ number_format(max(0, $subtotal - ($discountAmount ?? 0)), 0, ',', '.') }} đ</span>
                    </div>

                    <div class="mb-md relative">
                        <div class="flex gap-2 mb-2">
                            <input type="text" id="voucherCode" name="voucher_code" class="flex-1 bg-surface border border-outline-variant rounded-lg px-4 py-2 font-body-sm text-on-surface focus:outline-none focus:border-primary transition-colors" placeholder="Nhập mã giảm giá" value="{{ $appliedVoucher ? $appliedVoucher['code'] : '' }}" {{ $appliedVoucher ? 'readonly' : '' }}>
                            @if($appliedVoucher)
                                <button type="button" id="btnRemoveVoucher" class="bg-error text-on-error px-3 py-2 rounded-lg font-label-md hover:bg-error/90 transition-all shrink-0">Gỡ mã</button>
                            @else
                                <button type="button" id="btnApplyVoucher" class="bg-primary text-on-primary px-3 py-2 rounded-lg font-label-md hover:bg-primary/90 transition-all shrink-0">Áp dụng</button>
                            @endif
                        </div>
                        
                        <!-- Voucher Dropdown -->
                        @if(isset($availableVouchers) && $availableVouchers->count() > 0 && !$appliedVoucher)
                        <div id="voucherDropdown" class="absolute z-[100] w-full bg-white border border-outline-variant/30 rounded-lg shadow-xl hidden max-h-[250px] overflow-y-auto mt-1 left-0">
                            <div class="p-2 text-xs font-bold text-on-surface-variant bg-surface-container-lowest sticky top-0 border-b border-outline-variant/30">Mã giảm giá khả dụng</div>
                            @foreach($availableVouchers as $voucher)
                                @php
                                    $isEligible = $subtotal >= ($voucher->minimum_order ?? 0);
                                @endphp
                                <div class="p-3 border-b border-outline-variant/10 hover:bg-primary/5 transition-colors flex justify-between items-center {{ $isEligible ? 'voucher-item cursor-pointer' : 'opacity-60 cursor-not-allowed' }}" data-code="{{ $voucher->code }}">
                                    <div class="flex-1">
                                        <div class="font-bold text-[13px] text-primary mb-0.5">{{ $voucher->code }}</div>
                                        <div class="text-[11px] text-on-surface-variant leading-tight">Giảm {{ $voucher->discount_type == 'percent' ? $voucher->discount_value.'%' : number_format($voucher->discount_value, 0, ',', '.').'đ' }} 
                                        @if($voucher->minimum_order) <br>Đơn tối thiểu {{ number_format($voucher->minimum_order, 0, ',', '.') }}đ @endif
                                        </div>
                                    </div>
                                    @if(!$isEligible)
                                        <span class="text-[10px] bg-surface-container-high text-on-surface-variant px-1.5 py-0.5 rounded font-medium ml-2 shrink-0">Chưa đạt ĐK</span>
                                    @else
                                        <span class="text-[10px] bg-primary-container text-primary px-1.5 py-0.5 rounded font-bold ml-2 shrink-0 border border-primary/20">Dùng ngay</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        @endif

                        <p id="voucherMessage" class="text-sm hidden mt-1"></p>
                    </div>
                    
                    <p class="font-label-sm text-on-surface-variant mb-md text-center">Phí vận chuyển sẽ được tính ở bước thanh toán.</p>
                    
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
    
    const appliedVoucherDetails = @json(isset($appliedVoucher) ? \App\Models\Voucher::find($appliedVoucher['id']) : null);

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
        
        let discount = 0;
        if (appliedVoucherDetails) {
            if (appliedVoucherDetails.minimum_order && total < appliedVoucherDetails.minimum_order) {
                discount = 0; 
            } else {
                if (appliedVoucherDetails.discount_type === 'percent') {
                    discount = (total * appliedVoucherDetails.discount_value) / 100;
                    if (appliedVoucherDetails.maximum_discount && discount > appliedVoucherDetails.maximum_discount) {
                        discount = appliedVoucherDetails.maximum_discount;
                    }
                } else {
                    discount = appliedVoucherDetails.discount_value;
                }
                if (discount > total) discount = total;
            }
        }
        
        const finalTotalDisplay = document.getElementById('finalTotalDisplay');
        if (finalTotalDisplay) {
            finalTotalDisplay.textContent = formatMoney(Math.max(0, total - discount));
        }

        const discountDisplay = document.getElementById('discountDisplay');
        const discountContainer = document.getElementById('discountContainer');
        const finalTotalContainer = document.getElementById('finalTotalContainer');
        
        if (discountDisplay && discountContainer) {
            if (discount > 0) {
                discountDisplay.textContent = '-' + formatMoney(discount);
                discountContainer.classList.remove('hidden');
                finalTotalContainer.classList.remove('pb-md', 'border-b', 'border-outline-variant/20');
            } else {
                discountContainer.classList.add('hidden');
                finalTotalContainer.classList.add('pb-md', 'border-b', 'border-outline-variant/20');
            }
        }

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
                
                window.serverCartCount = data.cart_item_count;
                if (typeof updateCartBadge === 'function') updateCartBadge();
                
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

    const removeCartItem = async (id, row) => {
        try {
            const res = await fetch(`/cart/remove/${id}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken }
            });
            const data = await res.json();
            if (data.success) {
                row.remove();
                
                window.serverCartCount = data.cart_item_count;
                if (typeof updateCartBadge === 'function') updateCartBadge();
                
                const remainingCheckboxes = document.querySelectorAll('.item-checkbox');
                if (remainingCheckboxes.length === 0) {
                    window.location.reload();
                } else {
                    updateTotals();
                }
            }
        } catch (e) {
            console.error(e);
        }
    };

    document.querySelectorAll('.btn-decrease').forEach(btn => {
        btn.addEventListener('click', function() {
            const row = this.closest('.cart-item-row');
            let currentQty = parseInt(row.dataset.quantity);
            if (currentQty > 1) {
                updateCartQty(this.dataset.id, currentQty - 1, row);
            } else {
                if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng không?')) {
                    removeCartItem(this.dataset.id, row);
                }
            }
        });
    });

    // Xóa item
    document.querySelectorAll('.btn-remove').forEach(btn => {
        btn.addEventListener('click', function() {
            if (!confirm('Bạn có chắc chắn muốn xóa món này khỏi giỏ hàng?')) return;
            removeCartItem(this.dataset.id, this.closest('.cart-item-row'));
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
                    if (data.is_table_order) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = data.redirect;
                        const csrf = document.createElement('input');
                        csrf.type = 'hidden';
                        csrf.name = '_token';
                        csrf.value = csrfToken;
                        form.appendChild(csrf);
                        document.body.appendChild(form);
                        form.submit();
                    } else {
                        window.location.href = data.redirect;
                    }
                } else {
                    alert(data.error || 'Có lỗi xảy ra');
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
    const btnApplyVoucher = document.getElementById('btnApplyVoucher');
    const btnRemoveVoucher = document.getElementById('btnRemoveVoucher');
    const inputVoucherCode = document.getElementById('voucherCode');
    const voucherMessage = document.getElementById('voucherMessage');

    function showVoucherMessage(text, isError) {
        if (!voucherMessage) return;
        voucherMessage.textContent = text;
        voucherMessage.classList.remove('hidden', 'text-error', 'text-primary');
        voucherMessage.classList.add(isError ? 'text-error' : 'text-primary');
    }

    if (btnApplyVoucher) {
        btnApplyVoucher.addEventListener('click', async () => {
            const code = inputVoucherCode.value.trim();
            if (!code) {
                showVoucherMessage('Vui lòng nhập mã giảm giá', true);
                return;
            }

            // Get current subtotal of selected items
            let currentSubtotal = 0;
            checkboxes.forEach(cb => {
                if (cb.checked) {
                    const row = cb.closest('.cart-item-row');
                    const price = parseFloat(row.dataset.price);
                    const quantity = parseInt(row.dataset.quantity);
                    currentSubtotal += price * quantity;
                }
            });

            btnApplyVoucher.disabled = true;
            btnApplyVoucher.textContent = '...';

            try {
                const res = await fetch('{{ route("vouchers.apply") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ voucher_code: code, subtotal: currentSubtotal })
                });
                const data = await res.json();
                
                if (data.success) {
                    window.location.reload();
                } else {
                    showVoucherMessage(data.message, true);
                    btnApplyVoucher.disabled = false;
                    btnApplyVoucher.textContent = 'Áp dụng';
                }
            } catch (err) {
                showVoucherMessage('Có lỗi xảy ra, vui lòng thử lại', true);
                btnApplyVoucher.disabled = false;
                btnApplyVoucher.textContent = 'Áp dụng';
            }
        });
    }

    if (btnRemoveVoucher) {
        btnRemoveVoucher.addEventListener('click', async () => {
            btnRemoveVoucher.disabled = true;
            btnRemoveVoucher.textContent = '...';
            try {
                const res = await fetch('{{ route("vouchers.remove") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });
                window.location.reload();
            } catch (err) {
                window.location.reload();
            }
        });
    }

    // Voucher Dropdown Logic
    const voucherInput = document.getElementById('voucherCode');
    const voucherDropdown = document.getElementById('voucherDropdown');
    const voucherItems = document.querySelectorAll('.voucher-item');

    if (voucherInput && voucherDropdown) {
        voucherInput.addEventListener('focus', () => {
            voucherDropdown.classList.remove('hidden');
        });

        // Hide when clicking outside
        document.addEventListener('click', (e) => {
            if (!voucherInput.contains(e.target) && !voucherDropdown.contains(e.target)) {
                voucherDropdown.classList.add('hidden');
            }
        });

        voucherItems.forEach(item => {
            item.addEventListener('click', () => {
                const code = item.dataset.code;
                voucherInput.value = code;
                voucherDropdown.classList.add('hidden');
                
                // Automatically apply if Apply button exists
                if (btnApplyVoucher) {
                    btnApplyVoucher.click();
                }
            });
        });
    }
});
</script>
@endsection
