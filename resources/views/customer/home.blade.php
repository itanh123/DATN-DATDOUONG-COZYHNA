@extends('layouts.customer')

@section('title', 'Home')

@push('styles')
<style>
/* VOUCHER STYLES */
.voucher-card {
    position: relative;
    background: #fff;
    filter: drop-shadow(0 4px 6px rgba(0,0,0,0.05));
    border-radius: 12px;
    display: flex;
    overflow: hidden;
}
.voucher-left {
    background: linear-gradient(135deg, #006e1c 0%, #3e6a00 100%);
    color: white;
    position: relative;
}
.voucher-divider {
    position: relative;
    border-left: 2px dashed #e5e7eb;
    background-color: #fff;
}
/* Khoét lỗ trên/dưới ở phần divider */
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
<main class="pt-16 pb-24 md:pb-8">
<!-- Hero Giâytion -->
<section class="relative w-full h-[614px] min-h-[500px] overflow-hidden">
<div class="absolute inset-0 bg-cover bg-center" data-alt="A cinematic, high-end commercial shot of a sweating iced matcha latte with fresh mint leaves and a splash of cream, set against a minimalist, bright sunlit cafe background. The lighting is soft and airy, emphasizing the vibrant green of the matcha and the crisp textures of the ice. The overall mood is premium, organic, and refreshing, following a light-mode aesthetic with soft shadows." style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDy90FFb5PZrkhFYJeeKRcDSJl531wPXedVkZ9hmN4xw07udE6lMGoir7ffRmX6glTTMgsLn1YKTQZU5QXg_SFrzAgdWw1sfWOybxpCruVI5-xIEHHbWHc89Bnp9lQzw1nlu-QjtEs9dWjdCU_zORiSqE9UgkJeFLlQLTA3kqNSOUquVbkHEEMagyn-DaxONWDCfY6FIEZI48s_-JMTQQJ1K7aWxGYKb9wh_0Rgr394_WKNjEtTlgqrSxcXMBol8tuAaIOjeoA3')">
</div>
<div class="absolute inset-0 bg-gradient-to-r from-black/40 to-transparent"></div>
<div class="relative h-full max-w-container-max mx-auto px-lg flex flex-col justify-center text-white">
<span class="bg-primary-container text-on-primary-container px-3 py-1 rounded-full text-label-md font-label-md inline-block w-fit mb-md">Đặc biệt Mùa Hè</span>
<h1 class="font-display-lg text-display-lg max-w-xl leading-tight mb-md">Nâng Tầm Thói Quen Buổi Sáng Của Bạn.</h1>
<p class="font-body-lg text-body-lg max-w-md mb-xl opacity-90">Trải nghiệm những hương vị hữu cơ tinh khiết nhất, được chế tác tỉ mỉ dành cho những người sành điệu.</p>
<div class="flex gap-md">
<button class="bg-primary hover:bg-primary/90 text-white px-xl py-md rounded-xl font-headline-md transition-all shadow-lg active:scale-95">Đặt Hàng Ngay</button>
<button class="bg-white/20 hover:bg-white/30 backdrop-blur-md text-white px-xl py-md rounded-xl font-headline-md transition-all active:scale-95">Xem Thực Đơn</button>
</div>
</div>
</section>
<div class="max-w-container-max mx-auto px-lg">
<!-- Category Chips -->
<div class="flex gap-md overflow-x-auto no-scrollbar py-xl -mx-lg px-lg">
<a href="/" class="{{ !$isFiltered ? 'bg-primary text-on-primary' : 'bg-white border border-outline-variant/30 text-on-surface-variant hover:border-primary hover:text-primary' }} px-xl py-md rounded-full font-label-md whitespace-nowrap active:scale-95 transition-transform">Tất Cả</a>
@foreach($categories as $cat)
<a href="/?category_id={{ $cat->id }}" class="{{ request('category_id') == $cat->id ? 'bg-primary text-on-primary' : 'bg-white border border-outline-variant/30 text-on-surface-variant hover:border-primary hover:text-primary' }} px-xl py-md rounded-full font-label-md whitespace-nowrap active:scale-95 transition-transform">{{ $cat->name }}</a>
@endforeach
</div>

<!-- Vouchers Section (Mã Khuyến Mãi) -->
<section class="mb-2xl">
    <div class="flex justify-between items-center mb-xl">
        <h2 class="font-headline-lg text-headline-lg text-on-surface">Mã Khuyến Mãi Bùng Nổ</h2>
        <a class="text-primary font-label-md hover:underline" href="/customer/vouchers">Xem tất cả</a>
    </div>

    <div class="flex gap-md overflow-x-auto no-scrollbar pb-4 -mx-lg px-lg snap-x">
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
            <div class="voucher-card min-w-[320px] max-w-[350px] snap-center shrink-0">
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
            <div class="text-on-surface-variant italic w-full text-center py-4">Hiện tại chưa có mã khuyến mãi nào.</div>
        @endforelse

        <!-- Dynamic Marketing Banner (Beverage Themed) -->
        <a href="/customer/vouchers" class="min-w-[280px] max-w-[320px] snap-center shrink-0 rounded-2xl bg-gradient-to-br from-amber-400 via-orange-500 to-rose-500 relative overflow-hidden flex flex-col justify-center items-center text-center p-6 group hover:shadow-xl hover:shadow-rose-500/30 transition-all duration-500 border border-white/20">
            <!-- Glassmorphism overlay -->
            <div class="absolute inset-0 bg-white/10 backdrop-blur-[2px]"></div>
            
            <!-- Floating Bubbles Animation -->
            <div class="absolute -bottom-4 left-4 w-6 h-6 rounded-full border-2 border-white/30 group-hover:-translate-y-24 group-hover:opacity-0 opacity-80 transition-all duration-[2000ms] ease-in"></div>
            <div class="absolute -bottom-8 left-1/2 w-4 h-4 rounded-full border border-white/40 group-hover:-translate-y-32 group-hover:opacity-0 opacity-60 transition-all duration-[2500ms] ease-in delay-100"></div>
            <div class="absolute -bottom-2 right-8 w-8 h-8 rounded-full border-2 border-white/20 group-hover:-translate-y-20 group-hover:opacity-0 opacity-90 transition-all duration-[1800ms] ease-in delay-75"></div>
            
            <div class="relative z-10 flex flex-col items-center space-y-3">
                <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl rotate-3 group-hover:-rotate-12 transition-transform duration-500 flex items-center justify-center border border-white/30 mb-1 shadow-inner">
                    <span class="material-symbols-outlined text-white text-[36px] drop-shadow-md">local_cafe</span>
                </div>
                
                <div>
                    <h3 class="font-display-sm text-display-sm text-white font-black uppercase tracking-wider mb-1 drop-shadow-sm">Giải Khát<br>Cực Đã</h3>
                    <p class="text-white/90 text-[13px] font-medium px-2 leading-tight">Uống thả ga không lo về giá với kho Voucher siêu hời!</p>
                </div>
                
                <div class="mt-4 inline-flex items-center gap-2 bg-white text-rose-600 px-6 py-2.5 rounded-full font-bold text-sm shadow-lg group-hover:bg-rose-50 group-hover:scale-105 transition-all overflow-hidden relative">
                    <div class="absolute inset-0 w-full h-full bg-rose-100/50 -translate-x-full group-hover:translate-x-full transition-transform duration-[800ms] ease-in-out"></div>
                    <span class="relative z-10">Săn Mã Ngay</span>
                    <span class="material-symbols-outlined text-[18px] relative z-10 group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform">arrow_outward</span>
                </div>
            </div>
        </a>
    </div>
</section>

<!-- Danh Mục Sản Phẩm -->
@php
    $groupedProducts = collect($products ?? [])->groupBy(function($item) {
        return $item->category ? $item->category->id . '|' . $item->category->name : '0|Khác';
    });
@endphp

@forelse($groupedProducts as $categoryKey => $categoryProducts)
    @php
        list($catId, $categoryName) = explode('|', $categoryKey);
        $displayProducts = $isFiltered ? $categoryProducts : $categoryProducts->take(4);
    @endphp
    <section class="mb-2xl">
        <div class="flex justify-between items-end mb-xl border-b border-outline-variant/30 pb-sm">
            <h3 class="font-headline-lg text-headline-lg">{{ $categoryName }}</h3>
            @if(!$isFiltered && $categoryProducts->count() > 4)
            <a class="text-primary font-label-md hover:underline" href="/?category_id={{ $catId }}">Xem Tất Cả {{ $categoryName }}</a>
            @endif
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-lg">
            @foreach($displayProducts as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </section>
@empty
    <section class="mb-2xl">
        <div class="flex justify-between items-center mb-xl">
            <h2 class="font-headline-lg text-headline-lg">Thực Đơn</h2>
        </div>
        <p class="text-on-surface-variant font-body-md col-span-full">Chưa có sản phẩm nào.</p>
    </section>
@endforelse

<!-- Cửa Hàng Gần Nhất Giâytion -->
<section class="mb-2xl">
<div class="flex justify-between items-center mb-xl">
<h2 class="font-headline-lg text-headline-lg">Địa Chỉ Quán</h2>
<button class="flex items-center gap-base text-primary font-label-md">
<span class="material-symbols-outlined text-[18px]">my_location</span> Vị Trí Hiện Tại
                    </button>
</div>
<div class="flex flex-col md:flex-row gap-lg h-[400px]">
<div class="w-full h-full bg-surface-container-high rounded-3xl overflow-hidden relative">
<iframe 
    src="https://maps.google.com/maps?q=cổng%20khu%20công%20nghiệp%20đồng%20văn%203%20tổ%20dân%20phó%20SaLao%20phường%20Đồng%20Văn%20,%20Duy%20Tiên,%20Hà%20Nam&t=&z=15&ie=UTF8&iwloc=&output=embed" 
    width="100%" 
    height="100%" 
    style="border:0;" 
    allowfullscreen="" 
    loading="lazy" 
    referrerpolicy="no-referrer-when-downgrade">
</iframe>
</div>
</div>
</section>
<!-- Khách hàng Reviews Carousel -->
<section class="mb-2xl pb-xl">
<h2 class="font-headline-lg text-headline-lg text-center mb-2xl">Cộng Đồng Của Chúng Tôi</h2>
<div class="flex gap-lg overflow-x-auto no-scrollbar -mx-lg px-lg">
<div class="min-w-[300px] bg-white p-xl rounded-3xl border border-outline-variant/20 shadow-sm">
<div class="flex items-center gap-md mb-lg">
<div class="w-12 h-12 rounded-full bg-cover bg-center" data-alt="Profile photo of a professional woman in her 30s smiling warmly." style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCqDJBMSdyDepeMrePkCzFRuQlsUAaF5JqE8nN-PkWHpElIbsRlVYA5g2cMAap5afA72qQv4mefYFylUSHLa9T_bNSs1lSAqji25WTUWXyyqJ6TZ6e-Mp__5Q1O_pjXf61jB8hyB2ftOTG09yZkhtAMG0MHcldyYWWxqpETK6PBjt8EOsZbmm8CBi86WbZ_gpmqtxWDz_f3WwvlTtE6ntkk9oOMKmU1UPN_UKndg3n-rwk6w2QeUtp2_0si2uCZJ34hfm3ym8Jd')"></div>
<div>
<p class="font-bold">Sarah Jenkins</p>
<div class="flex text-tertiary">
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
</div>
</div>
</div>
<p class="text-body-lg italic text-on-surface-variant">"The Cold Brew Mật Ong Hoa Oải Hương is life-changing. It's the highlight of my morning commute!"</p>
</div>
<div class="min-w-[300px] bg-white p-xl rounded-3xl border border-outline-variant/20 shadow-sm">
<div class="flex items-center gap-md mb-lg">
<div class="w-12 h-12 rounded-full bg-cover bg-center" data-alt="Profile photo of a young male creative professional with glasses." style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBcnfh2xr2UaEragR_HGrXCX0ihQKAKnV62VrP_u2FAZqLz6D7MWobvHUClJyJ1g3VlDh3d4CFS9Io6BFqsXgYfxlxfRKS0_SqSkT7RISjbyKK-GYjogBLN3zGMCsaTQR0B6LH4e7ZaPxxWQ83BiGZWQ17Y9iypeYDMi7RJCysHmbSiQAWBftIoxoD984EPGVIMqeNvveWw0VQGr9AFGModg46ZxsNSyRMTIOS9X8u6huGhQHluLmEtkuufRn7JHTxyB1i-AYh_')"></div>
<div>
<p class="font-bold">Mark Thompson</p>
<div class="flex text-tertiary">
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
</div>
</div>
</div>
<p class="text-body-lg italic text-on-surface-variant">"Incredible interface and even better coffee. The loyalty program is actually worth it."</p>
</div>
<div class="min-w-[300px] bg-white p-xl rounded-3xl border border-outline-variant/20 shadow-sm">
<div class="flex items-center gap-md mb-lg">
<div class="w-12 h-12 rounded-full bg-cover bg-center" data-alt="Profile photo of a stylish elderly woman with grey hair and a bright smile." style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAjz3WkQwhsh3Uw5mdykPnktriiVKaDIsbonUDd90YPCreD_BuAtNOSQGQAdb48lyO3ySxt28XbyEYFDd4Vbm4tsS6pLbVd2wBI43cFDhwOGhjdd_F8OCRDZBFLaMW8JZPCHyy6KYLMXLyJCcd5r-D5UeWMRbnQ5l3cmGLM8a6nM0lixPzI3rittha8M_99Tm4Pz6qkYKp8iQxXGLcKaj4RoV4QMxfYJgeYy5cr3u3Tq6r58SnRx-F-Rbo-BHSd0OCVPAx75vmE')"></div>
<div>
<p class="font-bold">Elena Rodriguez</p>
<div class="flex text-tertiary">
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]">star</span>
</div>
</div>
</div>
<p class="text-body-lg italic text-on-surface-variant">"I love that they have so many vegan options that don't compromise on taste."</p>
</div>
</div>
</section>
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
</script>
<script>
    function addStaticToCart(event, name, price) {
        event.stopPropagation(); // Prevent drawer from opening
        const cart = getCart();
        
        // Mock a product ID for static items
        const staticId = 'static_' + name.replace(/\s+/g, '').toLowerCase();
        
        const existingItemIndex = cart.findIndex(item => item.product.id === staticId);
        
        if (existingItemIndex > -1) {
            cart[existingItemIndex].quantity += 1;
        } else {
            cart.push({
                product: { id: staticId, name: name },
                size: null,
                price: price,
                quantity: 1
            });
        }
        
        saveCart(cart);
        // alert('Đã thêm ' + name + ' vào giỏ hàng!');
    }
</script>
@endpush
@endsection

@push('scripts')
<script>

        // Countdown Timer Logic
        function updateTimer() {
            const h = document.getElementById('hours');
            const m = document.getElementById('minutes');
            const s = document.getElementById('seconds');
            
            let hours = parseInt(h.innerText);
            let mins = parseInt(m.innerText);
            let secs = parseInt(s.innerText);
            
            if (secs > 0) {
                secs--;
            } else {
                if (mins > 0) {
                    mins--;
                    secs = 59;
                } else {
                    if (hours > 0) {
                        hours--;
                        mins = 59;
                        secs = 59;
                    }
                }
            }
            
            h.innerText = hours.toString().padStart(2, '0');
            m.innerText = mins.toString().padStart(2, '0');
            s.innerText = secs.toString().padStart(2, '0');
        }
        setInterval(updateTimer, 1000);

        // Simple smooth scroll for category chips
        document.querySelectorAll('button').forEach(btn => {
            btn.addEventListener('click', function() {
                this.classList.add('scale-95');
                setTimeout(() => this.classList.remove('scale-95'), 100);
            });
        });

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
