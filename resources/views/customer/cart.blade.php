@extends('layouts.customer')

@section('title', 'Giỏ hàng của bạn')

@push('styles')
<style>
    /* Premium background */
    .cart-bg {
        background: linear-gradient(135deg, #f8f9ff 0%, #e8f5e9 50%, #f0f7ff 100%);
        min-height: 100vh;
    }

    /* Premium card */
    .premium-card {
        background: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.8);
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.04);
        transition: box-shadow 0.3s ease;
    }
    .premium-card:hover {
        box-shadow: 0 8px 32px rgba(0, 110, 28, 0.08);
    }

    /* Cart item row */
    .cart-item-row {
        transition: background-color 0.2s ease, transform 0.2s ease;
    }
    .cart-item-row:hover {
        background-color: rgba(0, 110, 28, 0.02);
    }

    /* Green gradient button */
    .btn-primary {
        background: linear-gradient(135deg, #006e1c, #3e6a00);
        color: white;
        padding: 12px 24px;
        border-radius: 9999px;
        font-weight: 600;
        font-size: 15px;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(0, 110, 28, 0.3);
    }
    .btn-primary:hover:not(:disabled) {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(0, 110, 28, 0.4);
    }
    .btn-primary:active:not(:disabled) {
        transform: scale(0.98);
    }
    .btn-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        background: #9ca3af;
        box-shadow: none;
    }

    /* Edit variant button */
    .btn-edit-variant {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 8px;
        border-radius: 9999px;
        background: rgba(0, 110, 28, 0.1);
        color: #006e1c;
        font-size: 11px;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    .btn-edit-variant:hover {
        background: #006e1c;
        color: white;
    }

    /* Custom Checkbox */
    .custom-checkbox {
        appearance: none;
        width: 22px;
        height: 22px;
        border: 2px solid rgba(190, 202, 185, 0.8);
        border-radius: 6px;
        cursor: pointer;
        position: relative;
        transition: all 0.2s ease;
        background: white;
    }
    .custom-checkbox:checked {
        background-color: #006e1c;
        border-color: #006e1c;
        background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M12.207 4.793a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L6.5 9.086l4.293-4.293a1 1 0 011.414 0z'/%3E%3C/svg%3E") !important;
        background-size: 100% 100%;
        background-position: center;
        background-repeat: no-repeat;
    }

    /* Modal */
    .modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.4);
        backdrop-filter: blur(4px);
        z-index: 999;
        display: none;
        align-items: flex-end; /* Bottom sheet on mobile */
        justify-content: center;
    }
    @media (min-width: 768px) {
        .modal-backdrop { align-items: center; }
    }
    .modal-backdrop.open {
        display: flex;
        animation: fade-in 0.2s ease;
    }
    .modal-box {
        background: white;
        border-radius: 20px 20px 0 0;
        width: 100%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 -10px 40px rgba(0,0,0,0.1);
        transform: translateY(100%);
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @media (min-width: 768px) {
        .modal-box {
            border-radius: 24px;
            transform: scale(0.95);
        }
    }
    .modal-backdrop.open .modal-box {
        transform: translateY(0);
    }
    @media (min-width: 768px) {
        .modal-backdrop.open .modal-box { transform: scale(1); }
    }
    @keyframes fade-in { from { opacity: 0; } to { opacity: 1; } }

    /* Radio variants */
    .variant-radio:checked + label {
        background: rgba(0, 110, 28, 0.1);
        border-color: #006e1c;
        color: #006e1c;
    }
    .topping-checkbox:checked + label {
        background: rgba(0, 110, 28, 0.1);
        border-color: #006e1c;
    }
    
    /* Hide number spinners */
    .quantity-input::-webkit-inner-spin-button,
    .quantity-input::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .quantity-input {
        -moz-appearance: textfield;
    }
</style>
@endpush

@section('content')
<div class="pt-20 pb-24 cart-bg">
    <main class="max-w-6xl mx-auto px-4 md:px-lg pb-24 md:pb-8">
        
        <div class="mb-8">
            <h1 class="text-[28px] font-bold text-on-surface">Giỏ hàng của bạn</h1>
            <p class="text-[14px] text-on-surface-variant mt-1">Kiểm tra lại các món đồ uống tuyệt vời trước khi thanh toán.</p>
        </div>

        @if($cartItems->isEmpty())
            <div class="text-center py-20 bg-white/50 backdrop-blur-md rounded-3xl border border-white">
                <div class="w-24 h-24 bg-surface rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="material-symbols-outlined text-[48px] text-primary/40">shopping_bag</span>
                </div>
                <h2 class="text-[20px] font-bold text-on-surface">Giỏ hàng đang trống</h2>
                <p class="text-[14px] text-on-surface-variant mt-2 mb-8">Bạn chưa chọn món nào. Hãy xem qua thực đơn của chúng tôi nhé!</p>
                <a href="/" class="btn-primary inline-flex">Khám phá Thực đơn</a>
            </div>
        @else
            <div class="flex flex-col lg:flex-row gap-6 items-start">
                
                {{-- LEFT: Cart Items --}}
                <div class="lg:w-2/3 w-full space-y-4">
                    
                    {{-- Select All Header --}}
                    <div class="premium-card rounded-2xl p-4 flex items-center justify-between">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" id="selectAll" class="custom-checkbox" checked>
                            <span class="text-[14px] font-semibold text-on-surface">Chọn tất cả ({{ $cartItems->count() }} món)</span>
                        </label>
                    </div>

                    {{-- Items List --}}
                    <div class="premium-card rounded-2xl overflow-hidden divide-y divide-outline-variant/15">
                        @foreach($cartItems as $item)
                            @php
                                $product = $item->productSize->product ?? $item->product;
                                $size    = $item->productSize->size ?? null;
                                $price   = $item->unit_price;
                                $itemTotal = $price * $item->quantity;
                                $productSizes = $product->productSizes ?? collect();
                                $productToppings = $product->toppings ?? collect();
                            @endphp
                            <div class="p-4 md:p-5 flex gap-4 cart-item-row relative" 
                                 data-id="{{ $item->id }}" 
                                 data-product-id="{{ $product->id ?? '' }}"
                                 data-price="{{ $price }}" 
                                 data-quantity="{{ $item->quantity }}"
                                 data-server-quantity="{{ $item->quantity }}"
                                 data-size-id="{{ $item->product_size_id }}"
                                 data-topping-ids="{{ json_encode(array_column($item->toppings, 'id')) }}"
                                 data-sizes="{{ json_encode($productSizes->map(fn($s) => ['id'=>$s->id, 'name'=>$s->size->name, 'price'=>$s->selling_price])) }}"
                                 data-toppings="{{ json_encode($allToppings->map(fn($t) => ['id'=>$t->id, 'name'=>$t->name, 'price'=>$t->price])) }}">
                                
                                <div class="pt-2">
                                    <input type="checkbox" name="selected_items[]" value="{{ $item->id }}" class="item-checkbox custom-checkbox" checked>
                                </div>

                                <a href="/customer/product_detail?id={{ $product->id ?? '' }}" class="w-20 h-20 md:w-24 md:h-24 rounded-2xl overflow-hidden flex-shrink-0 border border-outline-variant/20 hover:opacity-90 transition-opacity bg-white">
                                    @if($product && $product->image)
                                        <img class="w-full h-full object-cover" src="{{ str_starts_with($product->image, 'http') ? $product->image : asset($product->image) }}" alt=""/>
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-surface">
                                            <span class="material-symbols-outlined text-outline-variant/50 text-[32px]">local_cafe</span>
                                        </div>
                                    @endif
                                </a>

                                <div class="flex-1 min-w-0 flex flex-col justify-between">
                                    <div>
                                        <div class="flex justify-between items-start gap-2">
                                            <a href="/customer/product_detail?id={{ $product->id ?? '' }}" class="text-[16px] font-bold text-on-surface hover:text-primary transition-colors truncate">
                                                {{ $product->name ?? 'Sản phẩm' }}
                                            </a>
                                            {{-- Delete Button (Desktop) --}}
                                            <button type="button" class="btn-remove hidden md:block text-error hover:bg-error-container p-1.5 rounded-lg transition-colors" data-id="{{ $item->id }}">
                                                <span class="material-symbols-outlined text-[20px]">delete</span>
                                            </button>
                                        </div>

                                        {{-- Variant info & Edit Button --}}
                                        <div class="mt-1 flex items-start gap-2 flex-wrap">
                                            <div class="text-[12px] text-on-surface-variant flex flex-wrap items-center gap-1.5">
                                                <span>Size: <span class="font-semibold">{{ $size->name ?? 'Mặc định' }}</span></span>
                                                @if(!empty($item->toppings))
                                                    <span class="mx-0.5">•</span>
                                                    @foreach($item->toppings as $topping)
                                                        <span class="inline-flex items-center gap-0.5 bg-surface-variant/50 text-on-surface-variant px-1.5 py-0.5 rounded text-[11px] border border-outline-variant/30">
                                                            {{ $topping['name'] }}
                                                            <button type="button" class="hover:text-error transition-colors flex items-center justify-center" title="Xóa topping" onclick="removeTopping('{{ $item->id }}', {{ $topping['id'] }}, this.closest('.cart-item-row'))">
                                                                <span class="material-symbols-outlined text-[13px]">close</span>
                                                            </button>
                                                        </span>
                                                    @endforeach
                                                @endif
                                            </div>
                                            <button type="button" class="btn-edit-variant" onclick="openVariantModal(this.closest('.cart-item-row'))">
                                                <span class="material-symbols-outlined text-[13px]">edit</span> Thay đổi
                                            </button>
                                        </div>
                                    </div>

                                    <div class="flex items-end justify-between mt-3">
                                        <div class="text-[15px] font-bold text-primary item-total-display">
                                            {{ number_format($itemTotal, 0, ',', '.') }} đ
                                        </div>

                                        <div class="flex items-center gap-3">
                                            {{-- Quantity Controls --}}
                                            <div class="flex items-center bg-white border border-outline-variant/40 rounded-full h-8 overflow-hidden shadow-sm">
                                                <button type="button" class="btn-decrease w-8 h-full flex items-center justify-center hover:bg-surface text-on-surface-variant transition-colors" data-id="{{ $item->id }}">
                                                    <span class="material-symbols-outlined text-[16px]">remove</span>
                                                </button>
                                                <input type="number" min="1" class="w-10 text-center text-[13px] font-bold quantity-input bg-transparent outline-none border-none focus:ring-0 p-0 m-0" value="{{ $item->quantity }}" data-id="{{ $item->id }}">
                                                <button type="button" class="btn-increase w-8 h-full flex items-center justify-center hover:bg-surface text-on-surface-variant transition-colors" data-id="{{ $item->id }}">
                                                    <span class="material-symbols-outlined text-[16px]">add</span>
                                                </button>
                                            </div>
                                            
                                            {{-- Delete Button (Mobile) --}}
                                            <button type="button" class="btn-remove md:hidden text-error bg-error-container/50 hover:bg-error-container p-1.5 rounded-full transition-colors" data-id="{{ $item->id }}">
                                                <span class="material-symbols-outlined text-[18px]">delete</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- RIGHT: Summary --}}
                <div class="lg:w-1/3 w-full sticky top-24">
                    <div class="premium-card rounded-2xl p-6">
                        <h2 class="text-[18px] font-bold text-on-surface mb-5">Tóm tắt đơn hàng</h2>
                        
                        <div class="space-y-3 text-[14px]">
                            <div class="flex justify-between items-center">
                                <span class="text-on-surface-variant">Tạm tính (<span id="selectedCountDisplay">{{ $cartItems->count() }}</span> món)</span>
                                <span class="font-semibold text-on-surface" id="subtotalDisplay">{{ number_format($subtotal, 0, ',', '.') }} đ</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-on-surface-variant">Phí giao hàng</span>
                                <span class="text-on-surface-variant text-[12px] italic">Tính ở bước sau</span>
                            </div>
                        </div>

                        <div class="my-5 border-t border-outline-variant/20 border-dashed"></div>
                        
                        <div id="discountContainer" class="flex justify-between items-center mb-3 {{ (!isset($discountAmount) || $discountAmount <= 0) ? 'hidden' : '' }}">
                            <span class="text-[14px] text-error">Giảm giá (Voucher)</span>
                            <span class="font-semibold text-error" id="discountDisplay">-{{ number_format($discountAmount ?? 0, 0, ',', '.') }} đ</span>
                        </div>

                        <div class="mb-5 relative">
                            <div class="flex gap-2">
                                <input type="text" id="voucherCode" name="voucher_code" class="flex-1 bg-surface border border-outline-variant rounded-lg px-3 py-2 text-[14px] text-on-surface focus:outline-none focus:border-primary transition-colors" placeholder="Nhập mã giảm giá" value="{{ $appliedVoucher ? $appliedVoucher['code'] : '' }}" {{ $appliedVoucher ? 'readonly' : '' }}>
                                @if($appliedVoucher)
                                    <button type="button" id="btnRemoveVoucher" class="bg-error text-on-error px-3 py-2 rounded-lg text-[14px] font-semibold hover:bg-error/90 transition-all shrink-0 whitespace-nowrap">Gỡ mã</button>
                                @else
                                    <button type="button" id="btnApplyVoucher" class="bg-primary text-on-primary px-3 py-2 rounded-lg text-[14px] font-semibold hover:bg-primary/90 transition-all shrink-0 whitespace-nowrap">Áp dụng</button>
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

                            <p id="voucherMessage" class="text-[12px] hidden mt-1"></p>
                        </div>

                        <div class="flex justify-between items-end mb-6" id="finalTotalContainer">
                            <span class="text-[14px] font-semibold text-on-surface">Tổng cộng</span>
                            <span class="text-[24px] font-bold text-primary leading-none" id="totalDisplay">{{ number_format(max(0, $subtotal - ($discountAmount ?? 0)), 0, ',', '.') }} đ</span>
                        </div>
                        
                        <button type="button" id="btnCheckout" class="btn-primary w-full flex items-center justify-center gap-2">
                            Tiến hành thanh toán
                            <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                        </button>
                    </div>
                </div>

            </div>
        @endif
    </main>
</div>

{{-- ====== VARIANT EDIT MODAL ====== --}}
<div id="variant-modal" class="modal-backdrop" onclick="if(event.target===this)closeVariantModal()">
    <div class="modal-box flex flex-col">
        <div class="p-5 border-b border-outline-variant/15 flex justify-between items-center sticky top-0 bg-white z-10 rounded-t-2xl md:rounded-t-[24px]">
            <h3 class="text-[18px] font-bold text-on-surface">Tùy chỉnh đồ uống</h3>
            <button onclick="closeVariantModal()" class="w-8 h-8 rounded-full bg-surface hover:bg-surface-variant flex items-center justify-center text-on-surface-variant transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        
        <div class="p-5 overflow-y-auto space-y-6 flex-1">
            <input type="hidden" id="vm-cart-id">
            
            {{-- Size Section --}}
            <div>
                <h4 class="text-[14px] font-bold text-on-surface mb-3 flex items-center justify-between">
                    Chọn Size <span class="text-[11px] font-normal text-on-surface-variant bg-surface px-2 py-0.5 rounded">Bắt buộc</span>
                </h4>
                <div class="grid grid-cols-2 gap-3" id="vm-sizes-container">
                    <!-- Injected via JS -->
                </div>
            </div>

            {{-- Topping Section --}}
            <div>
                <h4 class="text-[14px] font-bold text-on-surface mb-3 flex items-center justify-between">
                    Thêm Topping <span class="text-[11px] font-normal text-on-surface-variant bg-surface px-2 py-0.5 rounded">Tùy chọn</span>
                </h4>
                <div class="space-y-2" id="vm-toppings-container">
                    <!-- Injected via JS -->
                </div>
            </div>
        </div>

        <div class="p-5 border-t border-outline-variant/15 sticky bottom-0 bg-white md:rounded-b-[24px]">
            <div class="flex items-center justify-between mb-4">
                <span class="text-[13px] text-on-surface-variant">Tạm tính tùy chỉnh:</span>
                <span class="text-[18px] font-bold text-primary" id="vm-price-display">0 đ</span>
            </div>
            <button type="button" onclick="saveVariantChanges()" class="btn-primary w-full" id="vm-save-btn">
                Cập nhật giỏ hàng
            </button>
        </div>
    </div>
</div>

<!-- Custom Confirm Delete Modal -->
<div id="delete-modal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-black/40 backdrop-blur-sm transition-opacity opacity-0 duration-300">
    <div class="bg-surface rounded-3xl p-6 w-[90%] max-w-sm shadow-2xl transform scale-95 transition-transform duration-300">
        <div class="flex items-center gap-3 text-error mb-4">
            <span class="material-symbols-outlined text-[32px]">warning</span>
            <h3 class="text-[18px] font-bold text-on-surface">Xóa sản phẩm?</h3>
        </div>
        <p class="text-[14px] text-on-surface-variant mb-6">Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng không?</p>
        <div class="flex justify-end gap-3">
            <button type="button" onclick="closeDeleteModal()" class="px-5 py-2.5 rounded-xl text-on-surface-variant bg-surface-variant/50 hover:bg-surface-variant transition-colors font-semibold text-[14px]">Hủy</button>
            <button type="button" id="btn-confirm-delete" class="px-5 py-2.5 rounded-xl bg-error text-white hover:opacity-90 transition-opacity font-semibold shadow-sm text-[14px]">Xóa</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const csrfToken = '{{ csrf_token() }}';
    const formatMoney = (amount) => new Intl.NumberFormat('vi-VN').format(amount) + ' đ';
    
    function showToast(message, type = 'error') {
        const existingToast = document.getElementById('global-toast');
        if (existingToast) existingToast.remove();
        
        const isError = type === 'error';
        const bgClass = isError ? 'bg-error' : 'bg-primary';
        const textClass = isError ? 'text-on-error' : 'text-on-primary';
        const icon = isError ? 'error' : 'check_circle';
        
        const toast = document.createElement('div');
        toast.id = 'global-toast';
        toast.className = 'fixed top-24 left-1/2 -translate-x-1/2 z-[100] min-w-[320px] shadow-2xl rounded-xl overflow-hidden transition-all duration-500 transform translate-y-0 opacity-100';
        toast.innerHTML = `
            <div class="${bgClass} ${textClass} px-lg py-md flex items-center gap-md">
                <span class="material-symbols-outlined">${icon}</span>
                <span class="font-body-md flex-1">${message}</span>
                <button onclick="this.closest('#global-toast').remove()" class="hover:opacity-70 active:scale-95 transition-transform"><span class="material-symbols-outlined">close</span></button>
            </div>
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            const el = document.getElementById('global-toast');
            if (el) {
                el.classList.remove('translate-y-0', 'opacity-100');
                el.classList.add('-translate-y-4', 'opacity-0');
                setTimeout(() => el.remove(), 500);
            }
        }, 8000);
    }
    
    // UI Elements
    const checkboxes = document.querySelectorAll('.item-checkbox');
    const appliedVoucherDetails = @json(isset($appliedVoucher) ? \App\Models\Voucher::find($appliedVoucher['id']) : null);
    const selectAll = document.getElementById('selectAll');
    
    // === CART TOTALS LOGIC ===
    function updateTotals() {
        try {
            let total = 0;
            let count = 0;
            const checkboxes = document.querySelectorAll('.item-checkbox');
            
            checkboxes.forEach(cb => {
                if (cb.checked) {
                    const row = cb.closest('.cart-item-row');
                    if (row) {
                        const price = parseFloat(row.getAttribute('data-price') || 0);
                        let quantity = 1;
                        
                        // Always read from the input directly to avoid any state desync
                        const qtyInput = row.querySelector('.quantity-input');
                        if (qtyInput) {
                            quantity = parseInt(qtyInput.value) || 1;
                        } else {
                            quantity = parseInt(row.getAttribute('data-quantity') || 1);
                        }
                        
                        total += (price * quantity);
                        count += quantity;
                    }
                }
            });
            
            // Update Right Side Elements
            const subtotalDisplay = document.querySelector('#subtotalDisplay');
            if (subtotalDisplay) subtotalDisplay.innerText = formatMoney(total);
            
            const selectedCountDisplay = document.querySelector('#selectedCountDisplay');
            if (selectedCountDisplay) selectedCountDisplay.innerText = count;
            
            let discount = 0;
            if (typeof appliedVoucherDetails !== 'undefined' && appliedVoucherDetails) {
                if (!appliedVoucherDetails.minimum_order || total >= appliedVoucherDetails.minimum_order) {
                    if (appliedVoucherDetails.discount_type === 'percent') {
                        discount = (total * appliedVoucherDetails.discount_value) / 100;
                        if (appliedVoucherDetails.maximum_discount) { // Fixed property name
                            discount = Math.min(discount, appliedVoucherDetails.maximum_discount);
                        }
                    } else {
                        discount = appliedVoucherDetails.discount_value;
                    }
                }
            }
            
            const totalDisplay = document.querySelector('#totalDisplay');
            if (totalDisplay) {
                totalDisplay.innerText = formatMoney(Math.max(0, total - discount));
            }

            const discountDisplay = document.querySelector('#discountDisplay');
            const discountContainer = document.querySelector('#discountContainer');
            
            if (discountDisplay && discountContainer) {
                if (discount > 0) {
                    discountDisplay.innerText = '-' + formatMoney(discount);
                    discountContainer.classList.remove('hidden');
                } else {
                    discountContainer.classList.add('hidden');
                }
            }
            
            const selectAllCheckbox = document.querySelector('#selectAll');
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = (count > 0 && count === checkboxes.length);
            }
            
            const btnCheckout = document.querySelector('#btnCheckout');
            if (btnCheckout) {
                btnCheckout.disabled = (count === 0);
            }
        } catch (e) {
            console.error('Update Totals Error:', e);
            showToast('Lỗi cập nhật tổng tiền: ' + e.message, 'error');
        }
    }

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateTotals();
        });
    }

    checkboxes.forEach(cb => cb.addEventListener('change', updateTotals));

    // === QUANTITY UPDATE LOGIC ===
    function updateRowUI(newQty, row) {
        row.setAttribute('data-quantity', newQty);
        row.dataset.quantity = newQty;
        
        const qtyInput = row.querySelector('.quantity-input');
        if (qtyInput) {
            qtyInput.value = newQty;
        }
        
        const price = parseFloat(row.getAttribute('data-price') || 0);
        const totalDisplay = row.querySelector('.item-total-display');
        if (totalDisplay) {
            totalDisplay.innerText = formatMoney(price * newQty);
        }
        
        updateTotals();
    }

    async function updateCartQty(id, newQty, row) {
        if (isNaN(newQty) || newQty < 1 || !row) return;
        
        const originalQty = parseInt(row.getAttribute('data-server-quantity') || row.dataset.quantity || 1);
        
        // Optimistic UI Update - force execution immediately
        updateRowUI(newQty, row);
        
        try {
            const res = await fetch(`{{ url('/cart/update') }}/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ quantity: newQty })
            });
            const data = await res.json();
            if (data.success) {
                // Đảm bảo đồng bộ hoàn toàn với máy chủ bằng cách tải lại trang
                window.location.reload(); 
            } else {
                if (data.error) showToast(data.error, 'error');
                updateRowUI(originalQty, row);
            }
        } catch (e) { 
            console.error('Fetch Error:', e);
            updateRowUI(originalQty, row);
        }
    }

    document.addEventListener('click', function(e) {
        // btn-increase
        const btnInc = e.target.closest('.btn-increase');
        if (btnInc) {
            e.preventDefault();
            const row = btnInc.closest('.cart-item-row');
            let currentQty = parseInt(row.dataset.quantity);
            if (isNaN(currentQty)) currentQty = 1;
            updateCartQty(btnInc.dataset.id, currentQty + 1, row);
            return;
        }
        
        // btn-decrease
        const btnDec = e.target.closest('.btn-decrease');
        if (btnDec) {
            e.preventDefault();
            const row = btnDec.closest('.cart-item-row');
            let qty = parseInt(row.dataset.quantity);
            if (isNaN(qty)) qty = 2;
            
            if (qty > 1) {
                updateCartQty(btnDec.dataset.id, qty - 1, row);
            } else {
                removeCartItem(btnDec.dataset.id, row);
            }
            return;
        }
    });

    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('quantity-input')) {
            const row = e.target.closest('.cart-item-row');
            let qty = parseInt(e.target.value);
            if (!isNaN(qty) && qty >= 1) {
                updateRowUI(qty, row);
            }
        }
    });

    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('quantity-input')) {
            const row = e.target.closest('.cart-item-row');
            let qty = parseInt(e.target.value);
            if (isNaN(qty) || qty < 1) {
                removeCartItem(row.dataset.id, row);
            } else {
                updateCartQty(row.dataset.id, qty, row);
            }
        }
    });

    // === CUSTOM DELETE MODAL ===
    let itemToDeleteId = null;
    let itemToDeleteRow = null;
    const deleteModal = document.getElementById('delete-modal');
    
    function showDeleteModal(id, row) {
        itemToDeleteId = id;
        itemToDeleteRow = row;
        deleteModal.classList.remove('hidden');
        setTimeout(() => {
            deleteModal.classList.remove('opacity-0');
            deleteModal.children[0].classList.remove('scale-95');
        }, 10);
    }
    
    function closeDeleteModal() {
        deleteModal.classList.add('opacity-0');
        deleteModal.children[0].classList.add('scale-95');
        setTimeout(() => {
            deleteModal.classList.add('hidden');
        }, 300);
        
        if (itemToDeleteRow) {
            const qtyInput = itemToDeleteRow.querySelector('.quantity-input');
            if (qtyInput && parseInt(qtyInput.value) < 1) {
                qtyInput.value = itemToDeleteRow.dataset.quantity || 1;
            }
        }
    }
    
    document.getElementById('btn-confirm-delete').addEventListener('click', async function() {
        if (!itemToDeleteId || !itemToDeleteRow) return;
        const id = itemToDeleteId;
        const row = itemToDeleteRow;
        this.innerHTML = '<span class="material-symbols-outlined animate-spin">refresh</span>...';
        this.disabled = true;
        
        try {
            const res = await fetch(`/cart/remove/${id}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken }
            });
            const data = await res.json();
            if (data.success) {
                row.remove();
                if (document.querySelectorAll('.cart-item-row').length === 0) {
                    window.location.reload();
                } else {
                    updateTotals();
                    const badge = document.getElementById('cart-badge');
                    if(badge) {
                        const currentCount = parseInt(badge.textContent || 0);
                        badge.textContent = Math.max(0, currentCount - 1);
                        if (currentCount <= 1) badge.classList.add('hidden');
                    }
                }
            }
        } catch (e) { console.error(e); }
        finally {
            this.innerHTML = 'Xóa';
            this.disabled = false;
            closeDeleteModal();
        }
    });

    // === REMOVE LOGIC ===
    function removeCartItem(id, row) {
        showDeleteModal(id, row);
    }

    document.querySelectorAll('.btn-remove').forEach(btn => {
        btn.addEventListener('click', function() {
            removeCartItem(this.dataset.id, this.closest('.cart-item-row'));
        });
    });

    // === CHECKOUT ===
    if (btnCheckout) {
        btnCheckout.addEventListener('click', async function() {
            const selectedIds = Array.from(document.querySelectorAll('.item-checkbox:checked')).map(cb => cb.value);
            if (selectedIds.length === 0) return;

            const originalHtml = this.innerHTML;
            this.innerHTML = '<span class="material-symbols-outlined animate-spin">refresh</span> Đang xử lý...';
            this.disabled = true;

            try {
                const res = await fetch('/customer/checkout/init', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ selected_items: selectedIds })
                });
                const data = await res.json();
                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    showToast(data.error || 'Lỗi thanh toán', 'error');
                    this.innerHTML = originalHtml;
                    this.disabled = false;
                }
            } catch (e) {
                this.innerHTML = originalHtml;
                this.disabled = false;
            }
        });
    }

    // === VARIANT MODAL LOGIC ===
    const modal = document.getElementById('variant-modal');
    let currentModalSizes = [];
    let currentModalToppings = [];

    function openVariantModal(row) {
        const id = row.dataset.id;
        const sizeId = parseInt(row.dataset.sizeId);
        const toppingIds = JSON.parse(row.dataset.toppingIds || '[]');
        currentModalSizes = JSON.parse(row.dataset.sizes || '[]');
        currentModalToppings = JSON.parse(row.dataset.toppings || '[]');

        document.getElementById('vm-cart-id').value = id;

        // Render Sizes
        const sizeContainer = document.getElementById('vm-sizes-container');
        sizeContainer.innerHTML = currentModalSizes.map((s, idx) => `
            <div>
                <input type="radio" name="vm_size" id="vmsize_${s.id}" value="${s.id}" class="sr-only variant-radio" ${s.id === sizeId || (!sizeId && idx===0) ? 'checked' : ''} onchange="calcModalPrice()">
                <label for="vmsize_${s.id}" class="block border border-outline-variant/40 rounded-xl p-3 cursor-pointer transition-colors text-center">
                    <div class="text-[14px] font-bold text-on-surface">${s.name}</div>
                    <div class="text-[12px] text-primary font-semibold mt-1">${formatMoney(s.price)}</div>
                </label>
            </div>
        `).join('');

        // Render Toppings
        const toppingContainer = document.getElementById('vm-toppings-container');
        if (currentModalToppings.length === 0) {
            toppingContainer.innerHTML = `<p class="text-[13px] text-on-surface-variant italic">Không có topping cho sản phẩm này.</p>`;
        } else {
            toppingContainer.innerHTML = currentModalToppings.map(t => `
                <div class="relative">
                    <input type="checkbox" name="vm_topping" id="vmtop_${t.id}" value="${t.id}" class="sr-only topping-checkbox" ${toppingIds.includes(t.id) ? 'checked' : ''} onchange="calcModalPrice()">
                    <label for="vmtop_${t.id}" class="flex items-center justify-between border border-outline-variant/40 rounded-xl p-3 cursor-pointer transition-colors">
                        <span class="text-[14px] font-medium text-on-surface">${t.name}</span>
                        <span class="text-[13px] text-primary">+${formatMoney(t.price)}</span>
                    </label>
                </div>
            `).join('');
        }

        calcModalPrice();
        modal.classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    function closeVariantModal() {
        modal.classList.remove('open');
        document.body.style.overflow = '';
    }

    function calcModalPrice() {
        let total = 0;
        const checkedSize = document.querySelector('input[name="vm_size"]:checked');
        if (checkedSize) {
            const sizeObj = currentModalSizes.find(s => s.id == checkedSize.value);
            if (sizeObj) total += parseFloat(sizeObj.price);
        }
        
        document.querySelectorAll('input[name="vm_topping"]:checked').forEach(cb => {
            const topObj = currentModalToppings.find(t => t.id == cb.value);
            if (topObj) total += parseFloat(topObj.price);
        });

        document.getElementById('vm-price-display').textContent = formatMoney(total);
    }

    async function saveVariantChanges() {
        const id = document.getElementById('vm-cart-id').value;
        const sizeInput = document.querySelector('input[name="vm_size"]:checked');
        const sizeId = sizeInput ? sizeInput.value : null;
        
        const toppingIds = Array.from(document.querySelectorAll('input[name="vm_topping"]:checked')).map(cb => cb.value);

        const btn = document.getElementById('vm-save-btn');
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<span class="material-symbols-outlined animate-spin">refresh</span> Đang xử lý...';
        btn.disabled = true;

        try {
            const res = await fetch(`/cart/update-variant/${id}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ product_size_id: sizeId, topping_ids: toppingIds })
            });
            const data = await res.json();
            if (data.success) {
                window.location.reload(); // Reload to cleanly refresh cart state and IDs
            } else {
                showToast(data.error, 'error');
                btn.innerHTML = originalHtml;
                btn.disabled = false;
            }
        } catch (e) {
            console.error(e);
            showToast('Lỗi kết nối.', 'error');
            btn.innerHTML = originalHtml;
            btn.disabled = false;
        }
    }

    // === QUICK REMOVE TOPPING LOGIC ===
    async function removeTopping(cartItemId, toppingIdToRemove, rowElement) {
        if (!confirm('Bạn muốn bỏ topping này?')) return;
        
        // Find the current size and toppings from the row's dataset
        const sizeId = rowElement.dataset.sizeId;
        let currentToppingIds = JSON.parse(rowElement.dataset.toppingIds || '[]');
        
        // Remove the specific topping
        currentToppingIds = currentToppingIds.filter(id => id != toppingIdToRemove);

        try {
            const res = await fetch(`/cart/update-variant/${cartItemId}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ product_size_id: sizeId, topping_ids: currentToppingIds })
            });
            const data = await res.json();
            if (data.success) {
                window.location.reload(); // Reload to reflect changes
            } else {
                showToast(data.error, 'error');
            }
        } catch (e) {
            console.error(e);
            showToast('Lỗi kết nối.', 'error');
        }
    }

    // Voucher Logic Handlers
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

            let currentSubtotal = 0;
            checkboxes.forEach(cb => {
                if (cb.checked) {
                    const row = cb.closest('.cart-item-row');
                    currentSubtotal += parseFloat(row.dataset.price) * parseInt(row.dataset.quantity);
                }
            });

            btnApplyVoucher.disabled = true;
            btnApplyVoucher.textContent = '...';

            try {
                const res = await fetch('{{ route("vouchers.apply") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
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
                await fetch('{{ route("vouchers.remove") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }
                });
                window.location.reload();
            } catch (err) {
                window.location.reload();
            }
        });
    }

    const voucherDropdown = document.getElementById('voucherDropdown');
    const voucherItems = document.querySelectorAll('.voucher-item');

    if (inputVoucherCode && voucherDropdown) {
        inputVoucherCode.addEventListener('focus', () => {
            voucherDropdown.classList.remove('hidden');
        });

        document.addEventListener('click', (e) => {
            if (!inputVoucherCode.contains(e.target) && !voucherDropdown.contains(e.target)) {
                voucherDropdown.classList.add('hidden');
            }
        });

        voucherItems.forEach(item => {
            item.addEventListener('click', () => {
                inputVoucherCode.value = item.dataset.code;
                voucherDropdown.classList.add('hidden');
                if (btnApplyVoucher) btnApplyVoucher.click();
            });
        });
    }

    updateTotals();
</script>
@endpush
