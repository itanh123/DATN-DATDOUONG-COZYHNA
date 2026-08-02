@extends('layouts.customer')

@push('styles')
<style>
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;  /* IE and Edge */
    scrollbar-width: none;  /* Firefox */
}

/* Kiểu dáng viền đứt cho voucher */
.voucher-card {
    border-radius: 8px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    overflow: hidden;
    display: flex;
    position: relative;
}

.voucher-left {
    border-right: 2px dashed rgba(255, 255, 255, 0.5);
    position: relative;
}

/* Nửa hình tròn khoét viền cho cảm giác giống tem vé */
.voucher-divider::before, .voucher-divider::after {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    background-color: #f8f9ff;
    border-radius: 50%;
    left: -11px;
    z-index: 10;
    box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
}
.voucher-divider::before { top: -10px; box-shadow: inset 0 -2px 2px rgba(0,0,0,0.05); }
.voucher-divider::after { bottom: -10px; box-shadow: inset 0 2px 2px rgba(0,0,0,0.05); }

/* Hiệu ứng thanh tiến độ */
.progress-bar-fill {
    transition: width 1s ease-in-out;
}
</style>
@endpush

@section('content')
<main class="pt-24 pb-24 md:pb-8">
    <div class="max-w-container-max mx-auto px-lg">
        
        <div class="mb-xl text-center">
            <h1 class="font-display-md text-display-md text-on-surface mb-2">Kho Voucher Đặc Quyền</h1>
            <p class="text-body-lg text-on-surface-variant max-w-2xl mx-auto">Thu thập mã giảm giá và tiết kiệm tối đa cho những thức uống yêu thích của bạn.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-lg">
            @forelse($vouchers as $index => $v)
                @php
                    $bgClass = 'from-[#006e1c] to-[#3e6a00]'; // Default green
                    $icon = 'receipt_long';
                    $targetLabel = 'Đơn Hàng';

                    if ($v->discount_target == 'shipping') {
                        $bgClass = 'from-[#eab308] to-[#ca8a04]'; // Yellow
                        $icon = 'local_shipping';
                        $targetLabel = 'Vận Chuyển';
                    } elseif ($v->discount_target == 'product') {
                        $bgClass = 'from-[#0284c7] to-[#0369a1]'; // Blue
                        $icon = 'category';
                        $targetLabel = 'Sản Phẩm';
                    }

                    $percentUsed = $v->quantity > 0 ? round(($v->used / $v->quantity) * 100) : 100;
                    $isSaved = in_array($v->id, $savedVoucherIds);
                @endphp
                <div class="voucher-card w-full shrink-0 relative">
                    @if($v->is_hot)
                        <div class="absolute -top-2 -left-2 bg-error text-white text-[10px] font-bold px-2 py-1 rounded-br-lg rounded-tl-lg shadow flex items-center z-20">
                            <span class="material-symbols-outlined text-[12px] mr-1">local_fire_department</span> HOT
                        </div>
                    @endif
                    <div class="voucher-left w-[110px] flex flex-col justify-center items-center p-4 text-center shrink-0 bg-gradient-to-br {{ $bgClass }} text-white relative">
                        <span class="material-symbols-outlined text-[20px] mb-1 opacity-80">{{ $icon }}</span>
                        @if($v->discount_type === 'percent')
                            <span class="font-bold text-2xl leading-none mb-1">{{ $v->discount_value }}%</span>
                            <span class="font-bold text-[10px] uppercase opacity-90">{{ $targetLabel }}</span>
                        @else
                            <span class="font-bold text-2xl leading-none mb-1">{{ number_format($v->discount_value/1000, 0) }}K</span>
                            <span class="font-bold text-[10px] uppercase opacity-90">{{ $targetLabel }}</span>
                        @endif
                    </div>
                    <div class="voucher-divider w-[2px]"></div>
                    <div class="p-4 flex-1 flex flex-col justify-between bg-white">
                        <div>
                            <h3 class="font-bold text-on-surface text-base mb-1 line-clamp-1" title="{{ $v->name }}">{{ $v->name }}</h3>
                            
                            @if($v->discount_target == 'shipping')
                                <p class="text-[11px] font-semibold text-[#ca8a04] mb-1">Giảm phí vận chuyển</p>
                            @elseif($v->discount_target == 'product')
                                @php
                                    $productNames = $v->products->pluck('name')->implode(', ');
                                @endphp
                                <p class="text-[11px] font-semibold text-[#0284c7] mb-1 line-clamp-1" title="{{ $productNames }}">
                                    Áp dụng: {{ $productNames ?: 'Tất cả' }}
                                </p>
                            @endif

                            <p class="text-xs text-on-surface-variant mb-2">Đơn từ {{ number_format($v->minimum_order, 0, ',', '.') }}đ. HSD: {{ $v->end_date ? $v->end_date->format('d/m') : 'Không hạn' }} <button onclick="showVoucherDetails({{ $v->id }})" class="text-primary hover:underline font-semibold ml-1">Chi tiết</button></p>
                        </div>
                        <div class="flex items-center justify-between mt-2 gap-2">
                            <div class="flex-1">
                                <div class="w-full bg-surface-container h-1.5 rounded-full overflow-hidden">
                                    <div class="bg-error h-full rounded-full progress-bar-fill" style="width: {{ $percentUsed }}%;"></div>
                                </div>
                                <p class="text-[10px] text-error mt-1 font-bold">Đã dùng {{ $percentUsed }}%</p>
                            </div>
                            
                            @if($isSaved)
                                <button disabled class="bg-outline-variant text-on-surface-variant text-xs font-bold py-1.5 px-3 rounded-full whitespace-nowrap cursor-not-allowed">
                                    Đã Lưu
                                </button>
                            @else
                                <button onclick="saveVoucher({{ $v->id }}, this)" class="bg-primary hover:bg-primary/90 text-white text-xs font-bold py-1.5 px-3 rounded-full transition-transform active:scale-95 shadow-sm whitespace-nowrap">
                                    Lưu Ngay
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-on-surface-variant italic w-full text-center py-4 col-span-full">Hiện tại chưa có mã khuyến mãi nào.</div>
            @endforelse
        </div>
    </div>
    
    <!-- Voucher Details Modal -->
    <div id="voucherModal" class="fixed inset-0 z-[100] hidden bg-black/50 items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-md overflow-hidden shadow-2xl transform transition-all scale-95 opacity-0" id="voucherModalContent">
            <div class="bg-primary/10 p-4 border-b border-outline-variant flex justify-between items-center">
                <h3 class="font-title-lg text-title-lg text-on-surface font-bold">Chi tiết Mã Khuyến Mãi</h3>
                <button onclick="closeVoucherModal()" class="text-on-surface-variant hover:text-error transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div id="modalVoucherName" class="font-headline-sm text-headline-sm text-primary font-bold"></div>
                
                <div class="bg-surface-container-lowest p-4 rounded-xl border border-outline-variant/50 space-y-3 text-body-md">
                    <div class="flex justify-between border-b border-outline-variant/30 pb-2">
                        <span class="text-on-surface-variant">Mã Code:</span>
                        <span id="modalVoucherCode" class="font-mono font-bold text-primary"></span>
                    </div>
                    <div class="flex justify-between border-b border-outline-variant/30 pb-2">
                        <span class="text-on-surface-variant">Thời gian:</span>
                        <span id="modalVoucherDate" class="font-bold text-on-surface text-right max-w-[200px]"></span>
                    </div>
                    <div class="flex justify-between border-b border-outline-variant/30 pb-2">
                        <span class="text-on-surface-variant">Loại mã:</span>
                        <span id="modalVoucherTarget" class="font-bold text-on-surface"></span>
                    </div>
                    <div class="flex justify-between border-b border-outline-variant/30 pb-2">
                        <span class="text-on-surface-variant">Đơn tối thiểu:</span>
                        <span id="modalVoucherMinOrder" class="font-bold text-on-surface"></span>
                    </div>
                    <div class="flex justify-between border-b border-outline-variant/30 pb-2">
                        <span class="text-on-surface-variant">Mức giảm:</span>
                        <span id="modalVoucherDiscount" class="font-bold text-error"></span>
                    </div>
                    <div class="flex justify-between pb-2 hidden" id="modalVoucherMaxDiscountRow">
                        <span class="text-on-surface-variant">Giảm tối đa:</span>
                        <span id="modalVoucherMaxDiscount" class="font-bold text-on-surface"></span>
                    </div>
                </div>

                <div>
                    <h4 class="font-label-lg text-label-lg text-on-surface mb-1">Mô tả chi tiết:</h4>
                    <p id="modalVoucherDesc" class="text-body-md text-on-surface-variant whitespace-pre-line"></p>
                </div>
                
                <div id="modalVoucherProductsContainer" class="hidden">
                    <h4 class="font-label-lg text-label-lg text-on-surface mb-1">Sản phẩm áp dụng:</h4>
                    <div id="modalVoucherProducts" class="flex flex-wrap gap-1 mt-2"></div>
                </div>
            </div>
            <div class="p-4 border-t border-outline-variant bg-surface-container-low text-right">
                <button onclick="closeVoucherModal()" class="px-4 py-2 bg-primary text-on-primary rounded-lg font-label-md hover:bg-primary/90 transition-colors">Đóng</button>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    const vouchersData = @json($vouchers);
    
    function showVoucherDetails(id) {
        const voucher = vouchersData.find(v => v.id == id);
        if(!voucher) return;
        
        document.getElementById('modalVoucherName').innerText = voucher.name;
        document.getElementById('modalVoucherCode').innerText = voucher.code;
        
        const targetLabel = voucher.discount_target === 'shipping' ? 'Giảm phí vận chuyển' : (voucher.discount_target === 'product' ? 'Giảm giá sản phẩm' : 'Giảm toàn đơn hàng');
        document.getElementById('modalVoucherTarget').innerText = targetLabel;
        
        let dateStr = 'Không thời hạn';
        if (voucher.start_date && voucher.end_date) {
            dateStr = new Date(voucher.start_date).toLocaleDateString('vi-VN') + ' - ' + new Date(voucher.end_date).toLocaleDateString('vi-VN');
        } else if (voucher.end_date) {
            dateStr = 'HSD: ' + new Date(voucher.end_date).toLocaleDateString('vi-VN');
        }
        document.getElementById('modalVoucherDate').innerText = dateStr;
        
        document.getElementById('modalVoucherMinOrder').innerText = Number(voucher.minimum_order).toLocaleString('vi-VN') + ' VNĐ';
        
        let discountStr = voucher.discount_type === 'percent' 
            ? voucher.discount_value + '%' 
            : Number(voucher.discount_value).toLocaleString('vi-VN') + ' VNĐ';
        document.getElementById('modalVoucherDiscount').innerText = discountStr;
        
        const maxDiscountRow = document.getElementById('modalVoucherMaxDiscountRow');
        if (voucher.discount_type === 'percent' && voucher.maximum_discount > 0) {
            maxDiscountRow.classList.remove('hidden');
            document.getElementById('modalVoucherMaxDiscount').innerText = Number(voucher.maximum_discount).toLocaleString('vi-VN') + ' VNĐ';
        } else {
            maxDiscountRow.classList.add('hidden');
        }

        document.getElementById('modalVoucherDesc').innerText = voucher.description || 'Không có mô tả chi tiết.';
        
        const productsContainer = document.getElementById('modalVoucherProductsContainer');
        const productsList = document.getElementById('modalVoucherProducts');
        if (voucher.discount_target === 'product' && voucher.products && voucher.products.length > 0) {
            productsContainer.classList.remove('hidden');
            productsList.innerHTML = voucher.products.map(p => `<span class="px-2 py-1 bg-tertiary-container text-on-tertiary-container text-xs rounded-lg">${p.name}</span>`).join('');
        } else {
            productsContainer.classList.add('hidden');
        }
        
        const modal = document.getElementById('voucherModal');
        const modalContent = document.getElementById('voucherModalContent');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }
    
    function closeVoucherModal() {
        const modal = document.getElementById('voucherModal');
        const modalContent = document.getElementById('voucherModalContent');
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 200);
    }
    
    // Hàm xử lý lưu voucher
    function saveVoucher(voucherId, btn) {
        btn.classList.add('opacity-50', 'pointer-events-none');
        btn.innerText = 'Đang lưu...';

        fetch('/vouchers/save', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ voucher_id: voucherId })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                btn.innerText = 'Đã Lưu';
                btn.classList.replace('bg-primary', 'bg-outline-variant');
                btn.classList.replace('text-white', 'text-on-surface-variant');
                btn.disabled = true;
                btn.classList.add('cursor-not-allowed');
            } else {
                alert(data.message);
                btn.classList.remove('opacity-50', 'pointer-events-none');
                btn.innerText = 'Lưu Ngay';
            }
        })
        .catch(err => {
            console.error(err);
            alert('Có lỗi xảy ra, vui lòng thử lại!');
            btn.classList.remove('opacity-50', 'pointer-events-none');
            btn.innerText = 'Lưu Ngay';
        });
    }
</script>
@endpush
