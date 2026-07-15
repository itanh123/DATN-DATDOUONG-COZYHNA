<!DOCTYPE html>
<html class="light" lang="en">
<head>
<meta charset="utf-8"/>
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
        @stack('styles')
</style>
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
</head>
<body class="bg-surface text-on-surface">
<!-- Top Navigation Bar -->
<header class="fixed top-0 w-full h-16 bg-surface/80 dark:bg-surface-dim/80 backdrop-blur-md border-b border-outline-variant/30 z-50 flex justify-between items-center px-4 md:px-lg max-w-container-max mx-auto left-0 right-0 shadow-sm">
<div class="flex items-center gap-xl">
<span class="font-title-lg text-title-lg font-bold text-primary">CozyHNA</span>
<nav class="hidden md:flex gap-lg">
<a class="font-body-lg text-body-lg {{ request()->is('/') ? 'text-primary border-b-2 border-primary pb-1' : 'text-on-surface-variant hover:text-primary transition-colors' }}" href="/">Thực đơn</a>
<a class="font-body-lg text-body-lg {{ request()->is('customer/account') ? 'text-primary border-b-2 border-primary pb-1' : 'text-on-surface-variant hover:text-primary transition-colors' }}" href="/customer/account">Ưu đãi</a>
<a class="font-body-lg text-body-lg {{ request()->is('customer/orders') ? 'text-primary border-b-2 border-primary pb-1' : 'text-on-surface-variant hover:text-primary transition-colors' }}" href="/customer/orders">Đơn hàng</a>
<a class="font-body-lg text-body-lg {{ request()->is('customer/contact') ? 'text-primary border-b-2 border-primary pb-1' : 'text-on-surface-variant hover:text-primary transition-colors' }}" href="/customer/contact">Giới thiệu</a>
</nav>
</div>
<div class="flex items-center gap-md">
<div class="hidden md:flex items-center bg-surface-container-low px-4 py-2 rounded-full border border-outline-variant/20">
<span class="material-symbols-outlined text-outline text-[20px]">search</span>
<input class="bg-transparent border-none focus:ring-0 text-body-md w-48 ml-2" placeholder="Tìm kiếm đồ uống..." type="text"/>
</div>
<a href="/customer/checkout" class="relative flex items-center justify-center text-primary p-2 hover:bg-surface-container-low rounded-full transition-colors active:scale-95">
    <span class="material-symbols-outlined" data-icon="shopping_cart">shopping_cart</span>
    <span id="cart-badge" class="absolute top-0 right-0 bg-error text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full" style="display: none;">0</span>
</a>
<a href="/login" class="material-symbols-outlined text-primary p-2 hover:bg-surface-container-low rounded-full transition-colors active:scale-95" data-icon="account_circle">account_circle</a>
</div>
</header>

@yield('content')

<!-- Mobile Bottom Navigation -->
<nav class="md:hidden fixed bottom-0 left-0 w-full z-50 bg-surface flex justify-around items-center px-4 py-2 pb-safe rounded-t-xl shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] border-t border-outline-variant/10">
<a href="/" class="flex flex-col items-center justify-center bg-primary-container text-on-primary-container rounded-2xl px-4 py-1 active:scale-90 transition-transform">
<span class="material-symbols-outlined" data-icon="home">home</span>
<span class="font-label-sm text-label-sm">Trang chủ</span>
</a>
<a href="/customer/checkout" class="flex flex-col items-center justify-center text-on-surface-variant hover:text-primary active:scale-90 transition-transform">
<span class="material-symbols-outlined" data-icon="local_cafe">local_cafe</span>
<span class="font-label-sm text-label-sm">Đặt hàng</span>
</a>
<a href="/customer/orders" class="flex flex-col items-center justify-center text-on-surface-variant hover:text-primary active:scale-90 transition-transform">
<span class="material-symbols-outlined" data-icon="history">history</span>
<span class="font-label-sm text-label-sm">Lịch sử</span>
</a>
<a href="/customer/account" class="flex flex-col items-center justify-center text-on-surface-variant hover:text-primary active:scale-90 transition-transform">
<span class="material-symbols-outlined" data-icon="person">person</span>
<span class="font-label-sm text-label-sm">Hồ sơ</span>
</a>
</nav>
<!-- Footer (Desktop) -->
<footer class="hidden md:block bg-surface-container-low border-t border-outline-variant/30 py-2xl mt-auto">
<div class="max-w-container-max mx-auto px-lg grid grid-cols-4 gap-2xl">
<div class="col-span-1">
<span class="font-title-lg text-title-lg font-bold text-primary mb-md block">CozyHNA</span>
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
</body>
</html>
