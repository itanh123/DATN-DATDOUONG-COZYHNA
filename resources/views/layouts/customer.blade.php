<!DOCTYPE html>
<html class="light" lang="en">
<head>
<meta charset="utf-8"/>
<meta name="csrf-token" content="{{ csrf_token() }}"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>CozyHNA | @yield('title', 'Premium Beverages')</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9ff;
        }
        
        /* Ghi đè giao diện Choices.js cho khớp với hệ thống */
        .choices {
            margin-bottom: 0 !important;
        }
        .choices__inner {
            background-color: #ffffff !important;
            border: 1px solid rgba(190, 202, 185, 0.8) !important;
            border-radius: 0.5rem !important;
            min-height: 40px !important;
            padding: 4px 12px !important;
            display: flex;
            align-items: center;
            font-size: 14px !important;
            color: #0b1c30 !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        .choices.is-focused .choices__inner {
            border-color: #006e1c !important;
            box-shadow: 0 0 0 2px rgba(0, 110, 28, 0.2) !important;
        }
        .choices[data-type*="select-one"]::after {
            border: none !important;
            content: "" !important;
            height: 20px !important;
            width: 20px !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236f7a6b' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e") !important;
            background-position: center !important;
            background-repeat: no-repeat !important;
            background-size: contain !important;
            right: 10px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            margin-top: 0 !important;
            position: absolute !important;
            pointer-events: none;
        }
        .choices.is-open[data-type*="select-one"]::after {
            transform: translateY(-50%) rotate(180deg) !important;
        }
        .choices__list--single {
            padding: 0 !important;
        }
        .choices__list--dropdown {
            border-radius: 0.5rem !important;
            border: 1px solid rgba(190, 202, 185, 0.8) !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
            z-index: 100 !important;
            margin-top: 4px !important;
        }
        .choices__list--dropdown .choices__item {
            font-size: 14px !important;
            padding: 10px 12px !important;
            color: #0b1c30 !important;
        }
        .choices__list--dropdown .choices__item--selectable.is-highlighted {
            background-color: #f8f9ff !important;
            color: #006e1c !important;
        }
</style>
@stack('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "error-container": "#ffdad6",
                        "outline-variant": "#becab9",
                        "on-primary-fixed": "#002204",
                        "on-primary-fixed-variant": "#005313",
                        "primary-fixed": "#94f990",
                        "error": "#ba1a1a",
                        "surface-tint": "#006e1c",
                        "surface-container-lowest": "#ffffff",
                        "secondary": "#3e6a00",
                        "on-surface": "#0b1c30",
                        "secondary-container": "#b9f474",
                        "surface": "#f8f9ff",
                        "on-secondary-container": "#437000",
                        "surface-container-high": "#dce9ff",
                        "background": "#f8f9ff",
                        "on-secondary-fixed-variant": "#2e4f00",
                        "on-secondary-fixed": "#0f2000",
                        "inverse-on-surface": "#eaf1ff",
                        "on-primary-container": "#003c0b",
                        "tertiary-fixed": "#ffdf9e",
                        "secondary-fixed": "#b9f474",
                        "surface-variant": "#d3e4fe",
                        "primary": "#006e1c",
                        "on-tertiary": "#ffffff",
                        "surface-bright": "#f8f9ff",
                        "tertiary-container": "#c49400",
                        "tertiary": "#785900",
                        "surface-container": "#e5eeff",
                        "inverse-surface": "#213145",
                        "on-surface-variant": "#3f4a3c",
                        "tertiary-fixed-dim": "#fabd00",
                        "on-tertiary-fixed-variant": "#5b4300",
                        "on-error": "#ffffff",
                        "on-tertiary-fixed": "#261a00",
                        "on-secondary": "#ffffff",
                        "surface-container-highest": "#d3e4fe",
                        "primary-fixed-dim": "#78dc77",
                        "surface-dim": "#cbdbf5",
                        "on-background": "#0b1c30",
                        "surface-container-low": "#eff4ff",
                        "on-error-container": "#93000a",
                        "on-tertiary-container": "#433000",
                        "primary-container": "#4caf50",
                        "on-primary": "#ffffff",
                        "inverse-primary": "#78dc77",
                        "outline": "#6f7a6b",
                        "secondary-fixed-dim": "#9ed75b"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "lg": "24px",
                        "2xl": "48px",
                        "md": "16px",
                        "container-max": "1280px",
                        "xl": "32px",
                        "base": "4px",
                        "sm": "12px",
                        "xs": "8px",
                        "gutter": "24px"
                    },
                    "fontFamily": {
                        "body-lg": ["Inter"],
                        "title-lg": ["Inter"],
                        "headline-lg": ["Inter"],
                        "label-md": ["Inter"],
                        "label-sm": ["Inter"],
                        "headline-md": ["Inter"],
                        "display-lg": ["Inter"],
                        "body-md": ["Inter"],
                        "headline-lg-mobile": ["Inter"]
                    },
                    "fontSize": {
                        "body-lg": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
                        "title-lg": ["20px", {"lineHeight": "28px", "fontWeight": "600"}],
                        "headline-lg": ["32px", {"lineHeight": "40px", "letterSpacing": "-0.01em", "fontWeight": "600"}],
                        "label-md": ["12px", {"lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "500"}],
                        "label-sm": ["11px", {"lineHeight": "14px", "fontWeight": "600"}],
                        "headline-md": ["24px", {"lineHeight": "32px", "fontWeight": "600"}],
                        "display-lg": ["48px", {"lineHeight": "56px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                        "body-md": ["14px", {"lineHeight": "20px", "fontWeight": "400"}],
                        "headline-lg-mobile": ["24px", {"lineHeight": "32px", "fontWeight": "600"}]
                    }
                },
            },
        }
</script>
<style>
    @keyframes slideInRightFadeOut {
        0% { transform: translateX(100%); opacity: 0; }
        10% { transform: translateX(0); opacity: 1; }
        80% { transform: translateX(0); opacity: 1; }
        100% { transform: translateX(-20px); opacity: 0; pointer-events: none; }
    }
    .animate-slide-in-right-once {
        animation: slideInRightFadeOut 5s ease-out forwards;
    }
</style>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function customConfirm(event, element, message) {
        event.preventDefault();
        Swal.fire({
            title: 'Xác nhận',
            text: message || "Bạn có chắc chắn muốn thực hiện hành động này?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#006e1c', // primary
            cancelButtonColor: '#6f7a6b', // outline
            confirmButtonText: 'OK',
            cancelButtonText: 'Huỷ',
            customClass: {
                popup: 'rounded-3xl',
                confirmButton: 'rounded-xl px-6 py-2',
                cancelButton: 'rounded-xl px-6 py-2'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                let form = element.closest('form');
                if (form) {
                    form.submit();
                } else if (element.tagName.toLowerCase() === 'form') {
                    element.submit();
                } else if (element.tagName.toLowerCase() === 'a') {
                    window.location.href = element.href;
                }
            }
        });
    }
</script>
</head>
<body class="bg-surface text-on-surface">
<!-- Top Navigation Bar -->
<header id="main-header" class="fixed top-0 w-full h-16 bg-surface/80 dark:bg-surface-dim/80 backdrop-blur-md border-b border-outline-variant/30 z-50 left-0 right-0 shadow-sm transition-transform duration-300">
<div class="w-full max-w-container-max mx-auto h-full flex justify-between items-center px-4 md:px-lg">
<div class="flex items-center gap-xl relative">
    @php
        $showGreeting = !session()->has('greeting_shown');
        if ($showGreeting) {
            session()->put('greeting_shown', true);
        }

        $hour = (int) now()->timezone('Asia/Ho_Chi_Minh')->format('H');
        $timeStr = '';
        $icon = '';
        if ($hour >= 5 && $hour < 12) {
            $timeStr = 'buổi sáng';
            $icon = 'routine';
        } elseif ($hour >= 12 && $hour < 18) {
            $timeStr = 'buổi chiều';
            $icon = 'wb_sunny';
        } else {
            $timeStr = 'buổi tối';
            $icon = 'dark_mode';
        }
        
        $greeting = 'Chào mừng ' . $timeStr . '!';
        if (session()->has('user_id')) {
            $userGreeting = \App\Models\User::find(session('user_id'));
            if ($userGreeting) {
                $name = $userGreeting->name ?: $userGreeting->username;
                $greeting = 'Chào ' . $timeStr . ', ' . mb_convert_case($name, MB_CASE_TITLE, "UTF-8") . '!';
            }
        } elseif (session('is_table_order') && session('table_name')) {
            $greeting = 'Chào mừng ' . $timeStr . ', Bàn ' . session('table_name') . '!';
        }
    @endphp
    
    @if($showGreeting)
    <!-- Greeting Box -->
    <div class="hidden md:flex shrink-0 items-center">
        <div class="animate-slide-in-right-once flex items-center gap-1 text-primary whitespace-nowrap font-label-md bg-surface-container-low px-4 py-2 rounded-full border border-primary/20 shadow-sm">
            <span class="material-symbols-outlined text-[18px]">{{ $icon }}</span>
            <span>{{ $greeting }}</span>
        </div>
    </div>
    @endif

<img src="{{ asset('images/logo.png') }}" alt="CozyHNA Logo" class="h-10 object-contain shrink-0">
<nav class="hidden md:flex gap-lg">
<a class="font-body-lg text-body-lg {{ request()->is('/') ? 'text-primary border-b-2 border-primary pb-1' : 'text-on-surface-variant hover:text-primary transition-colors' }}" href="/">Thực đơn</a>
@if(!session('is_table_order'))
<a class="font-body-lg text-body-lg {{ request()->is('customer/orders') ? 'text-primary border-b-2 border-primary pb-1' : 'text-on-surface-variant hover:text-primary transition-colors' }}" href="/customer/orders">Đơn hàng</a>
<a class="font-body-lg text-body-lg {{ request()->is('customer/favorites') ? 'text-primary border-b-2 border-primary pb-1' : 'text-on-surface-variant hover:text-primary transition-colors' }}" href="/customer/favorites">Yêu thích</a>
@endif
<a class="font-body-lg text-body-lg {{ request()->is('customer/contact') ? 'text-primary border-b-2 border-primary pb-1' : 'text-on-surface-variant hover:text-primary transition-colors' }}" href="/customer/contact">Giới thiệu</a>
</nav>
</div>
<div class="flex items-center gap-md">
<form action="/" method="GET" class="hidden md:flex items-center bg-surface-container-low px-4 py-2 rounded-full border border-outline-variant/20 m-0">
<span class="material-symbols-outlined text-outline text-[20px]">search</span>
<input name="search" value="{{ request('search') }}" class="bg-transparent border-none focus:ring-0 text-body-md w-48 ml-2" placeholder="Tìm kiếm đồ uống..." type="text"/>
</form>
@if(session()->has('user_id') || session('is_table_order'))
<a href="/customer/cart" class="relative flex items-center justify-center text-primary p-2 hover:bg-surface-container-low rounded-full transition-colors active:scale-95" title="Giỏ hàng">
    <span class="material-symbols-outlined" data-icon="shopping_cart">shopping_cart</span>
    <span id="cart-badge" class="absolute -top-1 -right-1 bg-error text-white text-[10px] font-bold w-5 h-5 flex items-center justify-center rounded-full" style="display: none;">0</span>
</a>
@endif
@if(!session('is_table_order'))
@if(session()->has('user_id'))
    <a href="/customer/favorites" class="relative flex items-center justify-center text-primary p-2 hover:bg-surface-container-low rounded-full transition-colors active:scale-95" title="Yêu thích">
        <span class="material-symbols-outlined" data-icon="favorite">favorite</span>
        <span id="favorite-badge" class="absolute -top-1 -right-1 bg-error text-white text-[10px] font-bold w-5 h-5 flex items-center justify-center rounded-full" style="display: none;">0</span>
    </a>
    
    @if(isset($userGreeting) && $userGreeting->avatar)
        @php
            $avatarUrl = str_starts_with($userGreeting->avatar, 'http') ? $userGreeting->avatar : asset('storage/' . $userGreeting->avatar);
        @endphp
        <a href="/customer/account" class="relative flex items-center justify-center p-1 rounded-full transition-transform active:scale-95 hover:opacity-80" title="Tài khoản">
            <img src="{{ $avatarUrl }}" alt="Avatar" class="w-8 h-8 rounded-full object-cover border border-primary/30 shadow-sm">
        </a>
    @else
        <a href="/customer/account" class="material-symbols-outlined text-primary p-2 hover:bg-surface-container-low rounded-full transition-colors active:scale-95" data-icon="account_circle" title="Tài khoản">account_circle</a>
    @endif
    
    <a href="/logout" class="material-symbols-outlined text-error p-2 hover:bg-error-container rounded-full transition-colors active:scale-95" data-icon="logout" title="Đăng xuất">logout</a>

@else
    <a href="/login" class="material-symbols-outlined text-primary p-2 hover:bg-surface-container-low rounded-full transition-colors active:scale-95" data-icon="account_circle" title="Đăng nhập">account_circle</a>
@endif
@else
    <a href="/" class="material-symbols-outlined text-primary p-2 hover:bg-surface-container-low rounded-full transition-colors active:scale-95" data-icon="table_restaurant" title="Đang ở bàn {{ session('table_name') }}">table_restaurant</a>
@endif
    <button onclick="document.getElementById('mobile-menu').classList.toggle('hidden'); document.getElementById('mobile-menu').classList.toggle('flex');" class="md:hidden material-symbols-outlined text-primary p-2 hover:bg-surface-container-low rounded-full transition-colors active:scale-95" data-icon="menu">menu</button>
</div>
</div>
<!-- Mobile Dropdown Menu -->
<div id="mobile-menu" class="hidden md:hidden absolute top-16 left-0 w-full bg-surface border-b border-outline-variant/30 shadow-md flex-col px-4 py-4 gap-4 z-40">
    <a class="font-body-lg text-body-lg {{ request()->is('/') ? 'text-primary font-bold' : 'text-on-surface-variant' }}" href="/">Thực đơn</a>
    <a class="font-body-lg text-body-lg {{ request()->is('customer/contact') ? 'text-primary font-bold' : 'text-on-surface-variant' }}" href="/customer/contact">Giới thiệu & Tải App</a>
</div>
</header>

<!-- Global Flash Messages -->
@if(session('error') || session('success'))
    <div id="global-toast" class="fixed top-24 left-1/2 -translate-x-1/2 z-[100] min-w-[320px] shadow-2xl rounded-xl overflow-hidden transition-all duration-500 transform translate-y-0 opacity-100">
        @if(session('error'))
            <div class="bg-error text-on-error px-lg py-md flex items-center gap-md">
                <span class="material-symbols-outlined">error</span>
                <span class="font-body-md flex-1">{{ session('error') }}</span>
                <button onclick="document.getElementById('global-toast').remove()" class="hover:opacity-70 active:scale-95 transition-transform"><span class="material-symbols-outlined">close</span></button>
            </div>
        @endif
        @if(session('success'))
            <div class="bg-primary text-on-primary px-lg py-md flex items-center gap-md">
                <span class="material-symbols-outlined">check_circle</span>
                <span class="font-body-md flex-1">{{ session('success') }}</span>
                <button onclick="document.getElementById('global-toast').remove()" class="hover:opacity-70 active:scale-95 transition-transform"><span class="material-symbols-outlined">close</span></button>
            </div>
        @endif
    </div>
    <script>
        setTimeout(() => {
            const toast = document.getElementById('global-toast');
            if (toast) {
                toast.classList.remove('translate-y-0', 'opacity-100');
                toast.classList.add('-translate-y-4', 'opacity-0');
                setTimeout(() => toast.remove(), 500);
            }
        }, 8000);
    </script>
@endif

@yield('content')

<!-- Mobile Bottom Navigation -->
<nav class="md:hidden fixed bottom-0 left-0 w-full z-50 bg-surface flex justify-around items-center px-4 py-2 pb-safe rounded-t-xl shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] border-t border-outline-variant/10">
<a href="/" class="flex flex-col items-center justify-center bg-primary-container text-on-primary-container rounded-2xl px-4 py-1 active:scale-90 transition-transform">
<span class="material-symbols-outlined" data-icon="home">home</span>
<span class="font-label-sm text-label-sm">Trang chủ</span>
</a>
@if(session()->has('user_id') || session('is_table_order'))
<a href="/customer/cart" class="flex flex-col items-center justify-center text-on-surface-variant hover:text-primary active:scale-90 transition-transform">
<span class="material-symbols-outlined" data-icon="local_cafe">local_cafe</span>
<span class="font-label-sm text-label-sm">Đặt hàng</span>
</a>
@endif
@if(!session('is_table_order'))
<a href="/customer/orders" class="flex flex-col items-center justify-center text-on-surface-variant hover:text-primary active:scale-90 transition-transform">
<span class="material-symbols-outlined" data-icon="history">history</span>
<span class="font-label-sm text-label-sm">Lịch sử</span>
</a>
<a href="/customer/favorites" class="flex flex-col items-center justify-center text-on-surface-variant hover:text-primary active:scale-90 transition-transform">
<span class="material-symbols-outlined" data-icon="favorite">favorite</span>
<span class="font-label-sm text-label-sm">Yêu thích</span>
</a>
<a href="/customer/account" class="flex flex-col items-center justify-center text-on-surface-variant hover:text-primary active:scale-90 transition-transform">
<span class="material-symbols-outlined" data-icon="person">person</span>
<span class="font-label-sm text-label-sm">Hồ sơ</span>
</a>
@endif
</nav>
<!-- Footer (Desktop) -->
<footer class="hidden md:block bg-surface-container-low border-t border-outline-variant/30 py-2xl mt-auto">
<div class="max-w-container-max mx-auto px-lg grid grid-cols-4 gap-2xl">
<div class="col-span-1">
<img src="{{ asset('images/logo.png') }}" alt="CozyHNA Logo" class="h-12 object-contain mb-md block">
<p class="text-on-surface-variant text-body-md mb-xl">Tạo nên những khoảnh khắc tuyệt vời trong từng ngụm trà. Hãy tham gia cộng đồng người yêu thích đồ uống của chúng tôi.</p>
</div>
<div>
<h4 class="font-bold mb-lg">Công ty</h4>
<ul class="space-y-md text-on-surface-variant text-body-md">
<li><a class="hover:text-primary" href="/customer/contact">Về chúng tôi</a></li>
<li><a class="hover:text-primary" href="#">Tuyển dụng</a></li>
<li><a class="hover:text-primary" href="#">Nguồn gốc</a></li>
<li><a class="hover:text-primary" href="#">Báo chí</a></li>
</ul>
</div>
<div>
<h4 class="font-bold mb-lg">Hỗ trợ</h4>
<ul class="space-y-md text-on-surface-variant text-body-md">
<li><a class="hover:text-primary" href="#">Trung tâm trợ giúp</a></li>
<li><a class="hover:text-primary" href="#">Giao hàng</a></li>
<li><a class="hover:text-primary" href="/customer/contact">Liên hệ</a></li>
<li><a class="hover:text-primary" href="#">Trợ năng</a></li>
</ul>
</div>
<div>
<h4 class="font-bold mb-lg">Đăng ký nhận tin</h4>
<p class="text-on-surface-variant text-body-md mb-lg">Nhận tin tức và ưu đãi mới nhất qua email.</p>
<div class="flex gap-base">
<input class="bg-white border border-outline-variant/30 rounded-xl px-4 py-2 flex-1 focus:ring-primary focus:border-primary" placeholder="Địa chỉ email" type="email"/>
<button class="bg-primary text-white px-lg py-2 rounded-xl font-bold active:scale-95 transition-transform">Đăng ký</button>
</div>
</div>
</div>
<div class="max-w-container-max mx-auto px-lg mt-2xl pt-xl border-t border-outline-variant/10 text-center text-label-md text-on-surface-variant">
            © 2024 CozyHNA. Bảo lưu mọi quyền.
        </div>
</footer>

<script>
    window.serverCartCount = {{ $cartItemCount ?? 0 }};

    function updateCartBadge() {
        const badge = document.getElementById('cart-badge');
        if (badge) {
            if (window.serverCartCount > 0) {
                badge.innerText = window.serverCartCount;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        }
    }

    document.addEventListener('DOMContentLoaded', updateCartBadge);
</script>

@include('partials.product_drawer')
@stack('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selects = document.querySelectorAll('select:not(.no-choices)');
        selects.forEach(select => {
            new Choices(select, {
                searchEnabled: false,
                itemSelectText: '',
                shouldSort: false
            });
        });
        
        @if(session()->has('user_id') && !session('is_table_order'))
        @php
            $user = \App\Models\User::find(session('user_id'));
            $favoriteIds = [];
            if ($user) {
                $favoriteIds = $user->favoriteProducts()->pluck('product_id')->toArray();
            }
        @endphp
        window.favoriteProductIds = @json($favoriteIds);
        @else
        window.favoriteProductIds = [];
        @endif

        function updateFavoriteBadge() {
            const fbadge = document.getElementById('favorite-badge');
            if (fbadge) {
                if (window.favoriteProductIds.length > 0) {
                    fbadge.innerText = window.favoriteProductIds.length;
                    fbadge.style.display = 'flex';
                } else {
                    fbadge.style.display = 'none';
                }
            }
        }
        updateFavoriteBadge();

        // Initialize favorite icons
        window.favoriteProductIds.forEach(id => {
            document.querySelectorAll('.favorite-icon-' + id).forEach(icon => {
                icon.style.fontVariationSettings = "'FILL' 1";
                icon.classList.add('text-error');
            });
        });

        document.querySelectorAll('.btn-favorite-toggle').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                @if(!session()->has('user_id'))
                    window.location.href = '/login';
                    return;
                @endif

                const productId = this.getAttribute('data-product-id');
                const pIdInt = parseInt(productId);
                const icons = document.querySelectorAll('.favorite-icon-' + productId);

                fetch(`/favorites/toggle/${productId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'added') {
                        if (!window.favoriteProductIds.includes(pIdInt)) {
                            window.favoriteProductIds.push(pIdInt);
                        }
                        icons.forEach(icon => {
                            icon.style.fontVariationSettings = "'FILL' 1";
                            icon.classList.add('text-error');
                        });
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: 'Thành công',
                                text: 'Đã thêm vào danh sách yêu thích',
                                icon: 'success',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            });
                        }
                    } else if (data.status === 'removed') {
                        window.favoriteProductIds = window.favoriteProductIds.filter(id => id !== pIdInt);
                        icons.forEach(icon => {
                            icon.style.fontVariationSettings = "'FILL' 0";
                            icon.classList.remove('text-error');
                        });
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: 'Thành công',
                                text: 'Đã xóa khỏi danh sách yêu thích',
                                icon: 'info',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            });
                        }
                    }
                    updateFavoriteBadge();
                })
                .catch(error => console.error('Error toggling favorite:', error));
            });
        });
    });
</script>
@if(session('is_table_order'))
<button onclick="callStaff()" class="fixed bottom-24 right-4 z-50 bg-error text-white px-4 py-3 rounded-full shadow-lg flex items-center gap-2 hover:bg-error/90 transition-all active:scale-95">
    <span class="material-symbols-outlined">notifications_active</span>
    <span class="font-bold">Gọi nhân viên</span>
</button>
<script>
function callStaff() {
    fetch('/table/call-staff', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    }).then(r => r.json()).then(res => {
        if(res.success) {
            alert('Đã gửi yêu cầu gọi nhân viên. Vui lòng đợi trong giây lát!');
        } else {
            alert('Có lỗi xảy ra hoặc bạn chưa được phép gọi lại.');
        }
    }).catch(e => alert('Lỗi kết nối.'));
}
</script>
@endif

@include('components.ai-chat-widget')

@if(session('is_table_order') && session('table_login_time'))
<script>
    (function() {
        const loginTime = {{ session('table_login_time') }};
        const serverCurrentTime = {{ now()->timestamp }};
        const timeoutSeconds = 7200; // 120 minutes
        
        const elapsed = serverCurrentTime - loginTime;
        let remainingSeconds = timeoutSeconds - elapsed;
        
        if (remainingSeconds <= 0) {
            window.location.reload();
        } else {
            setTimeout(() => {
                window.location.reload();
            }, remainingSeconds * 1000);
        }
    })();
</script>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let lastScrollY = window.scrollY;
        const header = document.getElementById('main-header');
        
        window.addEventListener('scroll', () => {
            const currentScrollY = window.scrollY;
            
            // Ẩn khi scroll xuống hơn 100px, hiện khi cuộn lên
            if (currentScrollY > lastScrollY && currentScrollY > 100) {
                header.style.transform = 'translateY(-100%)';
            } else {
                header.style.transform = 'translateY(0)';
            }
            
            lastScrollY = currentScrollY;
        }, { passive: true });
    });
</script>
</body>
</html>
