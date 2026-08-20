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
        <div class="grid grid-cols-3 md:grid-cols-4 gap-lg">
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
            
            if (!h || !m || !s) return;
            
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
