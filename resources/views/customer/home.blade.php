@extends('layouts.customer')

@section('title', 'Home')

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
</main>
@push('scripts')
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
    
</script>
@endpush
