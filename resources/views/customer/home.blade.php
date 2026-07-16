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
<div class="flex gap-md overflow-x-auto no-scrollbar py-xl -mx-lg px-lg" id="categoryChips">
    <button class="category-chip bg-primary text-on-primary px-xl py-md rounded-full font-label-md whitespace-nowrap active:scale-95 transition-transform" data-category="all">Tất Cả</button>
    @foreach($categories as $category)
        <button class="category-chip bg-white border border-outline-variant/30 text-on-surface-variant hover:border-primary hover:text-primary px-xl py-md rounded-full font-label-md whitespace-nowrap active:scale-95 transition-transform" data-category="{{ $category->name }}">{{ $category->name }}</button>
    @endforeach
</div>
<!-- Khuyến Mãi Khủng Bento Grid -->
<section class="mb-2xl">
<div class="flex justify-between items-end mb-xl">
<div>
<h2 class="font-headline-lg text-headline-lg text-on-surface">Khuyến Mãi Khủng</h2>
<p class="text-on-surface-variant font-body-md">Các ưu đãi giới hạn thời gian sắp kết thúc.</p>
</div>
<div class="flex gap-sm" id="countdown">
<div class="bg-error-container text-on-error-container p-2 rounded-lg text-center min-w-[50px]">
<div class="font-bold text-lg" id="hours">02</div>
<div class="text-[10px] uppercase">Giờ</div>
</div>
<div class="bg-error-container text-on-error-container p-2 rounded-lg text-center min-w-[50px]">
<div class="font-bold text-lg" id="minutes">45</div>
<div class="text-[10px] uppercase">Phút</div>
</div>
<div class="bg-error-container text-on-error-container p-2 rounded-lg text-center min-w-[50px]">
<div class="font-bold text-lg" id="seconds">12</div>
<div class="text-[10px] uppercase">Giây</div>
</div>
</div>
</div>
<div class="grid grid-cols-1 md:grid-cols-4 gap-lg">
<!-- High Focus Sale Item -->
<div class="md:col-span-2 bg-secondary-container/20 rounded-2xl p-xl flex flex-col md:flex-row gap-xl items-center border border-secondary-container/30 relative overflow-hidden group cursor-pointer" onclick="openDrawer()">
<div class="relative z-10 space-y-md w-full md:w-1/2">
<span class="text-secondary font-label-md bg-secondary-container px-3 py-1 rounded-full">Giảm 40%</span>
<h3 class="font-headline-md text-headline-md text-on-surface">Cold Brew Mật Ong Hoa Oải Hương</h3>
<p class="text-on-surface-variant font-body-md">Hương vị đặc trưng của chúng tôi với nốt hương hoa và mật ong tự nhiên.</p>
<div class="flex items-center gap-md">
<span class="font-headline-md text-primary">$4.20</span>
<span class="text-on-surface-variant/60 line-through text-body-md">$7.00</span>
</div>
<button class="bg-primary text-white w-full py-md rounded-xl font-bold shadow-md hover:shadow-lg transition-all active:scale-95">Thêm Vào Giỏ</button>
</div>
<div class="w-full md:w-1/2 h-64 bg-cover bg-center rounded-xl shadow-lg transform group-hover:scale-105 transition-transform duration-500" data-alt="A top-down view of a modern cold brew coffee with subtle purple lavender sprigs and golden honey drizzled on the side. The lighting is crisp and bright, highlighting the condensation on the glass. The background is a clean white marble surface with premium aesthetic vibes." style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBwrBj8Hd94YVVHwuv-QqcwzlZNF-qhCApXzR9lE0LWasR3voHzawBFsMChGg_U4w8t5QvkeXGR-EY8KtA7voDr-PVsMQWPmDQyXRjqrlhsXzqVh0T9UGjzFUd6dznUHBCUEfSCZ-P63IKbEm6ZBv-CEdjNe2RM3-j8MIFYnJaE1tIyg5XrLjjO9t8x1-7JniapBfyYIIqPHyp_D_qZDK8DuMKExOpaZ1aXdgTghecYQYqQqsvxNWBiBWWSDDxzyYOjR3dKvgQr')"></div>
</div>
<!-- Regular Sale Items -->
<div class="bg-white rounded-2xl p-md border border-outline-variant/30 hover:shadow-md transition-all flex flex-col cursor-pointer" onclick="openDrawer()">
<div class="h-40 bg-cover bg-center rounded-xl mb-md" data-alt="A close-up shot of a vibrant red strawberry hibiscus tea in a tall glass with floating strawberry slices. Phútimalist white background, bright lighting, high-end beverage photography style." style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuB-90YbFgV3AT2wRJAa1E9Cs1PFazrggOXNWGYB7Cl4gP48QIXZNIoy2dVy_yYc91V6I5Y8bJOstosJ_C25P7WEvkHr_XbK7n153Tsg8p434_eHAIX-v9mOXHKTEkqwBi_zlaphgVd_vxGOj8_6jHMqNS-lzIcVyNFM-KPEM9IN2UqHjJtIOPoGmLxoI9vrl6x1HjR1MkhN5eWcVOdQrzrig_fUPZihpRM7cvYVJ3x6vtPWj6CPpAezHCHGfE67zqt1Bz3DrtSL')"></div>
<h4 class="font-title-lg text-title-lg mb-xs">Dâu Tây Dâm Bụt</h4>
<p class="text-on-surface-variant text-body-md mb-md">Zesty &amp; caffeine-free</p>
<div class="mt-auto flex justify-between items-center">
<span class="text-primary font-bold">$3.50</span>
<button class="w-10 h-10 bg-primary-container text-on-primary-container rounded-full flex items-center justify-center active:scale-90 transition-transform">
<span class="material-symbols-outlined">add</span>
</button>
</div>
</div>
<div class="bg-white rounded-2xl p-md border border-outline-variant/30 hover:shadow-md transition-all flex flex-col cursor-pointer" onclick="openDrawer()">
<div class="h-40 bg-cover bg-center rounded-xl mb-md" data-alt="A minimalist photograph of a steaming hot white chocolate mocha in a ceramic cup with a delicate leaf-shaped latte art. Soft window lighting, neutral colors, cozy premium atmosphere." style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAbXCmgeyKxE1uow2B5nT-Scs6v42a_jf0NsfDbC6jmyFdiEkAu1rVEw7iTLloMfYLyl7yCT9CanezCGWn_L4t9Anq6ejl4lplbsBTh6cag0tsMYUH96y83SmzIqZ5Vo--RdJOgTCZr_YP1CA6biHh1pnOiB_TyH0mBdtdrS1RYTmKieyiT8BbrPZY7cr6mEqdPPnObLEsxaKcGZtRGvzESPCw3PDv2T025A8i0PzhxTSfijH-HTwZWnl34MIlQzYrFQltrx7w7')"></div>
<h4 class="font-title-lg text-title-lg mb-xs">Mocha Trắng</h4>
<p class="text-on-surface-variant text-body-md mb-md">Sự hòa quyện mềm mịn</p>
<div class="mt-auto flex justify-between items-center">
<span class="text-primary font-bold">$4.50</span>
<button class="w-10 h-10 bg-primary-container text-on-primary-container rounded-full flex items-center justify-center active:scale-90 transition-transform">
<span class="material-symbols-outlined">add</span>
</button>
</div>
</div>
</div>
</section>
<!-- Danh Mục Sản Phẩm -->
@php
    $groupedProducts = collect($products ?? [])->groupBy(function($item) {
        return $item->category ? $item->category->name : 'Khác';
    });
@endphp

@forelse($groupedProducts as $categoryName => $categoryProducts)
    <section class="mb-2xl category-section" data-category="{{ $categoryName }}">
        <div class="flex justify-between items-end mb-xl border-b border-outline-variant/30 pb-sm">
            <h3 class="font-headline-lg text-headline-lg">{{ $categoryName }}</h3>
            <a class="text-primary font-label-md hover:underline" href="#">Xem Tất Cả {{ $categoryName }}</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-lg">
            @foreach($categoryProducts as $product)
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
<!-- Promotion Banner -->
<section class="mb-2xl">
<div class="w-full bg-primary h-48 rounded-3xl relative overflow-hidden flex items-center px-2xl group cursor-pointer">
<div class="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-white via-transparent to-transparent"></div>
<div class="relative z-10 text-white max-w-lg">
<h2 class="font-headline-lg text-headline-lg mb-sm">Tham Gia CozyHNA Rewards</h2>
<p class="font-body-lg opacity-80 mb-md">Tích lũy 2 điểm cho mỗi 1 đô chi tiêu. Nhận đồ uống miễn phí vào sinh nhật!</p>
<button class="bg-white text-primary px-lg py-sm rounded-full font-bold hover:bg-opacity-90 transition-all active:scale-95">Tìm Hiểu Thêm</button>
</div>
<div class="absolute right-0 top-0 h-full w-1/3 bg-cover bg-center hidden md:block" data-alt="A detailed flat-lay illustration of various digital reward icons, coffee beans, and points symbols in a premium minimalist green and white palette. Cohesive with a modern app design aesthetic." style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAI30wqcewCLnhsqyHaoiuDDthOI6iBLAA5LOrst2bcBInshXFeqaJGp45Z-jZewh-rDo4e55WiVzhKWv089BftQ3DyMlSAM2KcCqNxj17JR4uEYCe0llYmO2pagDn1cI1mBSGB5qRJQl6AHNovE8WJdEXKETt2xg6xbRSQ8Hi8AyBDEkQQt4qtaox6z6ULudJPqyXR8ZRaB8ecHsRJWKVSbSO0o4vdUPmiVzbFwHOWPdogO9ev1wEIUcjw96nsQvQj5DYWFOD0')"></div>
</div>
</section>
<!-- New Drinks & Recommended (Asymmetric Layout) -->
<section class="mb-2xl grid grid-cols-1 md:grid-cols-12 gap-xl">
<div class="md:col-span-8">
<div class="flex justify-between items-center mb-xl">
<h2 class="font-headline-lg text-headline-lg">Sản Phẩm Mới</h2>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-lg">
<div class="bg-white border border-outline-variant/20 rounded-3xl p-lg flex gap-lg hover:shadow-lg transition-all duration-300 cursor-pointer" onclick="openDrawer()">
<div class="w-32 h-32 flex-shrink-0 bg-cover bg-center rounded-2xl" data-alt="A gourmet iced peach oolong tea with real peach chunks and a sprig of thyme. Bright, clean lighting, white background." style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBa6eHNVlEDo8lB6YZq4FQMKvZSPD8CVhviiiR5H9Fnnn8xproTescX3C-aPke_USIrKFtYcLeHLGh_BIaWEm6eSrl2_RiRzkiCKKNzcy8HwRrFUEq3UeXYmMx9iK-FGdEG9P4JKoo8cI7JAEmjSS0wJvOZtl_S5jwo8fU9nHf1q3NrbpGjVGpxpjOu2gc_e0Ttb_rcGXjp6WyRaKJax-3G6duCUeeoi19zGvSY2P7a9ojFnvIspbWnQ34GKFF8-yyPDx3z7IS9')"></div>
<div class="flex flex-col justify-center">
<span class="text-primary font-label-md mb-base">Mới Ra Mắt</span>
<h3 class="font-title-lg text-title-lg mb-base">Trà Ô Long Đào Mây</h3>
<p class="text-on-surface-variant text-body-md line-clamp-2">Trà ô long nhẹ nhàng với lớp bọt đào béo ngậy.</p>
<div class="mt-md flex items-center justify-between">
<span class="font-bold text-primary">$6.75</span>
<button onclick="addStaticToCart(event, 'Trà Ô Long Đào Mây', 6.75)" class="bg-surface-container-high p-2 rounded-full material-symbols-outlined text-[20px] text-primary hover:bg-primary hover:text-white transition-colors">add_shopping_cart</button>
</div>
</div>
</div>
<div class="bg-white border border-outline-variant/20 rounded-3xl p-lg flex gap-lg hover:shadow-lg transition-all duration-300 cursor-pointer" onclick="openDrawer()">
<div class="w-32 h-32 flex-shrink-0 bg-cover bg-center rounded-2xl" data-alt="A fancy dark chocolate mocha with a dusting of gold leaf and cocoa powder. Moody yet bright high-end photography." style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDUhWioD4sE3ZRqqZ-KEmEMA9ta8ZgpLGGc5COWB5eyYof7Za1Im4IpN2fokzu-X3xPA7eA5mkjbOWl_juljI4mX-Z3dDilQ_n_6VTHKtqDyhg8upxu9MGUoBDxOlctdpVL1BfX11eLjWlKFt3u0Cx6uxFtWsPRogr4mRQPkLlrwafI1XaHj9nNq580azXRaJiUWjlPCQCl3npd4w_44ldq2sGiWjJCHTx6XSxewVEWGJ5IKBsNNSH_O2_29dklKlzfEwgFbll5')"></div>
<div class="flex flex-col justify-center">
<span class="text-primary font-label-md mb-base">Lựa Chọn Cao Cấp</span>
<h3 class="font-title-lg text-title-lg mb-base">Mocha Truffle Bóng Đêm</h3>
<p class="text-on-surface-variant text-body-md line-clamp-2">Cacao 70% với một chút muối biển.</p>
<div class="mt-md flex items-center justify-between">
<span class="font-bold text-primary">$7.25</span>
<button onclick="addStaticToCart(event, 'Mocha Truffle Bóng Đêm', 7.25)" class="bg-surface-container-high p-2 rounded-full material-symbols-outlined text-[20px] text-primary hover:bg-primary hover:text-white transition-colors">add_shopping_cart</button>
</div>
</div>
</div>
</div>
</div>
<div class="md:col-span-4 bg-surface-container-low rounded-3xl p-xl border border-outline-variant/10">
<h2 class="font-title-lg text-title-lg mb-xl">Gợi Ý Cho Bạn</h2>
<div class="space-y-lg">
<div class="flex items-center gap-md cursor-pointer" onclick="openDrawer()">
<div class="w-16 h-16 rounded-xl bg-cover bg-center" data-alt="Small thumbnail of a coconut milk flat white." style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDo6Z3GxYkI383c2qCRz4jaLK_7_YV2M4F02uFRF3h9s3auPjiai_0J6nPn8Z26mFRqqf9XD6Sph9_4V2GnoCImQHptjiTihtIVFD-E7gqj9zV7EyIx4MTXRCjqC6sJJdebnK3luiyaVL_JXUURKLkt8w0svI4D4BHbXlk55MkXqSS8hDHITlZLFXEmb4cy4do-ckOlcC87u1CVMejkjHvwPnT1ZKP86abwZJ20agtj0wk8bp2VjnNecJQ9ApcTCJfPNRtZ_tVx')"></div>
<div class="flex-1">
<p class="font-bold text-body-lg">Flat White Dừa</p>
<p class="text-label-md text-on-surface-variant">$5.25</p>
</div>
<button class="material-symbols-outlined text-outline">add</button>
</div>
<div class="flex items-center gap-md cursor-pointer" onclick="openDrawer()">
<div class="w-16 h-16 rounded-xl bg-cover bg-center" data-alt="Small thumbnail of an iced chai latte." style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDIlkSP1DVfOPprBoJq0TN3Z-nPrzotbljnJ0uJlQKsN4peRZWKlqMokR8LULLJy5H-Go5XPKe_ruNMsFw8DEgsWs9ucwm17Gvsj7R60sNXgWZLYy4hF7f6Eta8DuGRHsQwOn4PXEPO1JRYRbEXGXe7XQkErWnpu3pAIsmWNayBbL1ypLu-uvL05VkRpr4vBhIX6E-E6B4ZEnCrVF1VMONnAKAP5WWcwzToxZw3Zt91djAVHY5M65uVQZhlmGqtfwxymQ2RbgHM')"></div>
<div class="flex-1">
<p class="font-bold text-body-lg">Chai Latte Nghệ Thuật</p>
<p class="text-label-md text-on-surface-variant">$5.50</p>
</div>
<button class="material-symbols-outlined text-outline">add</button>
</div>
<div class="flex items-center gap-md cursor-pointer" onclick="openDrawer()">
<div class="w-16 h-16 rounded-xl bg-cover bg-center" data-alt="Small thumbnail of a ginger lemon infusion." style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDpSRl2ijxJyu4iipxiHtdiU608TS1c6lVZNTSs3bSIdlupfKS1TDAU0RzR6TKth0qj0ndwif7xKpG_OiJ4L9ZsGaY73K4ezC9f9EFMda2bbkNrigyyk1PaT9JOieIS-7fO3MzYGLkHy6_hoIgtX3NBJLvKQV-UdyAW9iFvuYInRlUIVO9HvSxEp0Kf_Sv_5DZydvW5sjGJYD8KGt6iwxxlfzWBYyUbc5hBz5Wo2iD86cC5NfnTD9sFO9gO1YJUVE7ziM5DYAcP')"></div>
<div class="flex-1">
<p class="font-bold text-body-lg">Trà Gừng Chanh</p>
<p class="text-label-md text-on-surface-variant">$4.00</p>
</div>
<button class="material-symbols-outlined text-outline">add</button>
</div>
</div>
<button class="w-full mt-xl py-md border-2 border-primary/20 rounded-xl text-primary font-bold hover:bg-primary/5 transition-colors">Xem Gợi Ý Riêng</button>
</div>
</section>
<!-- Cửa Hàng Gần Nhất Giâytion -->
<section class="mb-2xl">
<div class="flex justify-between items-center mb-xl">
<h2 class="font-headline-lg text-headline-lg">Cửa Hàng Gần Nhất</h2>
<button class="flex items-center gap-base text-primary font-label-md">
<span class="material-symbols-outlined text-[18px]">my_location</span> Vị Trí Hiện Tại
                    </button>
</div>
<div class="flex flex-col md:flex-row gap-lg h-[400px]">
<div class="w-full md:w-1/3 space-y-md overflow-y-auto pr-md no-scrollbar">
<div class="bg-white p-lg rounded-2xl border border-primary shadow-sm ring-1 ring-primary/20">
<div class="flex justify-between mb-base">
<h3 class="font-bold">CozyHNA - Downtown Core</h3>
<span class="text-primary text-label-sm">Mở Cửa</span>
</div>
<p class="text-body-md text-on-surface-variant mb-md">124 Premium St, West Side</p>
<div class="flex items-center gap-xl text-label-md">
<span class="flex items-center gap-xs"><span class="material-symbols-outlined text-[16px]">directions_walk</span> 5 phút</span>
<span class="flex items-center gap-xs"><span class="material-symbols-outlined text-[16px]">bolt</span> Xong trong 8 phút</span>
</div>
</div>
<div class="bg-white p-lg rounded-2xl border border-outline-variant/30 hover:border-primary/50 transition-colors">
<div class="flex justify-between mb-base">
<h3 class="font-bold">CozyHNA - Garden Plaza</h3>
<span class="text-primary text-label-sm">Mở Cửa</span>
</div>
<p class="text-body-md text-on-surface-variant mb-md">45 Green Blvd, South District</p>
<div class="flex items-center gap-xl text-label-md">
<span class="flex items-center gap-xs"><span class="material-symbols-outlined text-[16px]">directions_car</span> 12 phút</span>
<span class="flex items-center gap-xs"><span class="material-symbols-outlined text-[16px]">bolt</span> Xong trong 15 phút</span>
</div>
</div>
<div class="bg-white p-lg rounded-2xl border border-outline-variant/30 hover:border-primary/50 transition-colors opacity-70">
<div class="flex justify-between mb-base">
<h3 class="font-bold">CozyHNA - Waterfront</h3>
<span class="text-error text-label-sm">Sắp Đóng Cửa</span>
</div>
<p class="text-body-md text-on-surface-variant mb-md">88 Harbor Way, East Pier</p>
<div class="flex items-center gap-xl text-label-md">
<span class="flex items-center gap-xs"><span class="material-symbols-outlined text-[16px]">directions_walk</span> 20 phút</span>
<span class="flex items-center gap-xs"><span class="material-symbols-outlined text-[16px]">bolt</span> Xong trong 5 phút</span>
</div>
</div>
</div>
<div class="flex-1 bg-surface-container-high rounded-3xl overflow-hidden relative">
<div class="absolute inset-0 bg-cover bg-center" data-alt="A minimalist and stylish stylized map of a metropolitan area with custom brand pins indicating store locations. The map uses a light color palette with soft greens and neutral grays." data-location="New York City" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuD9ePNddLwvVZrdsw2eXQrgRLdf_4SSHRbq8oDsZiXu8Id_DdgBY64wFaO3c-GzkS_iXZLTxB242pPdNwM9D2FIbWVrucRiGM2Oa8josFaiX5SGA1lqZrB7JE7XKtbWN6eqe9k1uK2u9yf5F93IDjE26B9dZW5bRZT919ZqPQWb4jzA-0I88j2fQx0FJirslDcNbYLksxbf_dCQyHfrBjtEMLUbbuwjg44JMJQHMHjWr1x9B_f1kpV9_9TY8fFm1rGE-TxxX23X')"></div>
<div class="absolute bottom-4 right-4 flex flex-col gap-sm">
<button class="w-10 h-10 bg-white shadow-md rounded-full flex items-center justify-center text-on-surface active:scale-90 transition-transform">
<span class="material-symbols-outlined">add</span>
</button>
<button class="w-10 h-10 bg-white shadow-md rounded-full flex items-center justify-center text-on-surface active:scale-90 transition-transform">
<span class="material-symbols-outlined">remove</span>
</button>
</div>
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
        alert('Đây là sản phẩm minh họa. Vui lòng chọn các sản phẩm thực tế trong phần Danh Mục bên dưới.');
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

        // Category filtering logic
        const chips = document.querySelectorAll('.category-chip');
        const sections = document.querySelectorAll('.category-section');

        chips.forEach(chip => {
            chip.addEventListener('click', function() {
                // Remove active classes from all chips
                chips.forEach(c => {
                    c.className = 'category-chip bg-white border border-outline-variant/30 text-on-surface-variant hover:border-primary hover:text-primary px-xl py-md rounded-full font-label-md whitespace-nowrap active:scale-95 transition-transform';
                });
                
                // Add active classes to current chip
                this.className = 'category-chip bg-primary text-on-primary px-xl py-md rounded-full font-label-md whitespace-nowrap active:scale-95 transition-transform';

                const targetCategory = this.getAttribute('data-category');

                sections.forEach(section => {
                    const sectionCategory = section.getAttribute('data-category');
                    if (targetCategory === 'all' || sectionCategory === targetCategory) {
                        section.style.display = 'block';
                    } else {
                        section.style.display = 'none';
                    }
                });
            });
        });
    
</script>
@endpush
