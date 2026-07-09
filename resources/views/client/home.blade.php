<!DOCTYPE html>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>CozyHNA | Premium Beverages</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <style>
    .material-symbols-outlined {
        font-variation-settings: 'FILL'0, 'wght'400, 'GRAD'0, 'opsz'24;
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
                    "body-lg": ["16px", {
                        "lineHeight": "24px",
                        "fontWeight": "400"
                    }],
                    "title-lg": ["20px", {
                        "lineHeight": "28px",
                        "fontWeight": "600"
                    }],
                    "headline-lg": ["32px", {
                        "lineHeight": "40px",
                        "letterSpacing": "-0.01em",
                        "fontWeight": "600"
                    }],
                    "label-md": ["12px", {
                        "lineHeight": "16px",
                        "letterSpacing": "0.05em",
                        "fontWeight": "500"
                    }],
                    "label-sm": ["11px", {
                        "lineHeight": "14px",
                        "fontWeight": "600"
                    }],
                    "headline-md": ["24px", {
                        "lineHeight": "32px",
                        "fontWeight": "600"
                    }],
                    "display-lg": ["48px", {
                        "lineHeight": "56px",
                        "letterSpacing": "-0.02em",
                        "fontWeight": "700"
                    }],
                    "body-md": ["14px", {
                        "lineHeight": "20px",
                        "fontWeight": "400"
                    }],
                    "headline-lg-mobile": ["24px", {
                        "lineHeight": "32px",
                        "fontWeight": "600"
                    }]
                }
            },
        },
    }
    </script>
</head>

<body class="bg-surface text-on-surface">
    <!-- Top Navigation Bar -->
    <header
        class="fixed top-0 w-full h-16 bg-surface/80 dark:bg-surface-dim/80 backdrop-blur-md border-b border-outline-variant/30 z-50 flex justify-between items-center px-4 md:px-lg max-w-container-max mx-auto left-0 right-0 shadow-sm">
        <div class="flex items-center gap-xl">
            <span class="font-title-lg text-title-lg font-bold text-primary">CozyHNA</span>
            <nav class="hidden md:flex gap-lg">
                <a class="font-body-lg text-body-lg text-primary border-b-2 border-primary pb-1" href="#">Menu</a>
                <a class="font-body-lg text-body-lg text-on-surface-variant hover:text-primary transition-colors"
                    href="#">Rewards</a>
                <a class="font-body-lg text-body-lg text-on-surface-variant hover:text-primary transition-colors"
                    href="#">Orders</a>
                <a class="font-body-lg text-body-lg text-on-surface-variant hover:text-primary transition-colors"
                    href="#">About</a>
            </nav>
        </div>
        <div class="flex items-center gap-md">
            <div
                class="hidden md:flex items-center bg-surface-container-low px-4 py-2 rounded-full border border-outline-variant/20">
                <span class="material-symbols-outlined text-outline text-[20px]">search</span>
                <input class="bg-transparent border-none focus:ring-0 text-body-md w-48 ml-2"
                    placeholder="Search beverages..." type="text" />
            </div>
            <button
                class="material-symbols-outlined text-primary p-2 hover:bg-surface-container-low rounded-full transition-colors active:scale-95"
                data-icon="shopping_cart">shopping_cart</button>
<a href="/login" class="material-symbols-outlined text-primary p-2 hover:bg-surface-container-low rounded-full transition-colors active:scale-95 inline-flex items-center justify-center" data-icon="account_circle">account_circle</a>
        </div>
    </header>
    <!-- Main Content Canvas -->
    <main class="pt-16 pb-24 md:pb-8">
        <!-- Hero Section -->
        <section class="relative w-full h-[614px] min-h-[500px] overflow-hidden">
            <div class="absolute inset-0 bg-cover bg-center"
                data-alt="A cinematic, high-end commercial shot of a sweating iced matcha latte with fresh mint leaves and a splash of cream, set against a minimalist, bright sunlit cafe background. The lighting is soft and airy, emphasizing the vibrant green of the matcha and the crisp textures of the ice. The overall mood is premium, organic, and refreshing, following a light-mode aesthetic with soft shadows."
                style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDy90FFb5PZrkhFYJeeKRcDSJl531wPXedVkZ9hmN4xw07udE6lMGoir7ffRmX6glTTMgsLn1YKTQZU5QXg_SFrzAgdWw1sfWOybxpCruVI5-xIEHHbWHc89Bnp9lQzw1nlu-QjtEs9dWjdCU_zORiSqE9UgkJeFLlQLTA3kqNSOUquVbkHEEMagyn-DaxONWDCfY6FIEZI48s_-JMTQQJ1K7aWxGYKb9wh_0Rgr394_WKNjEtTlgqrSxcXMBol8tuAaIOjeoA3')">
            </div>
            <div class="absolute inset-0 bg-gradient-to-r from-black/40 to-transparent"></div>
            <div class="relative h-full max-w-container-max mx-auto px-lg flex flex-col justify-center text-white">
                <span
                    class="bg-primary-container text-on-primary-container px-3 py-1 rounded-full text-label-md font-label-md inline-block w-fit mb-md">Summer
                    Specials</span>
                <h1 class="font-display-lg text-display-lg max-w-xl leading-tight mb-md">Elevate Your Morning Ritual.
                </h1>
                <p class="font-body-lg text-body-lg max-w-md mb-xl opacity-90">Experience the purest organic blends,
                    crafted with artisanal precision for the discerning palate.</p>
                <div class="flex gap-md">
                    <button
                        class="bg-primary hover:bg-primary/90 text-white px-xl py-md rounded-xl font-headline-md transition-all shadow-lg active:scale-95">Order
                        Now</button>
                    <button
                        class="bg-white/20 hover:bg-white/30 backdrop-blur-md text-white px-xl py-md rounded-xl font-headline-md transition-all active:scale-95">View
                        Menu</button>
                </div>
            </div>
        </section>
        <div class="max-w-container-max mx-auto px-lg">
            <!-- Category Chips -->
            <div class="flex gap-md overflow-x-auto no-scrollbar py-xl -mx-lg px-lg">
                <button
                    class="bg-primary text-on-primary px-xl py-md rounded-full font-label-md whitespace-nowrap active:scale-95 transition-transform">All
                    Drinks</button>
                <button
                    class="bg-white border border-outline-variant/30 text-on-surface-variant hover:border-primary hover:text-primary px-xl py-md rounded-full font-label-md whitespace-nowrap active:scale-95 transition-transform">Signature
                    Lattes</button>
                <button
                    class="bg-white border border-outline-variant/30 text-on-surface-variant hover:border-primary hover:text-primary px-xl py-md rounded-full font-label-md whitespace-nowrap active:scale-95 transition-transform">Cold
                    Brews</button>
                <button
                    class="bg-white border border-outline-variant/30 text-on-surface-variant hover:border-primary hover:text-primary px-xl py-md rounded-full font-label-md whitespace-nowrap active:scale-95 transition-transform">Organic
                    Teas</button>
                <button
                    class="bg-white border border-outline-variant/30 text-on-surface-variant hover:border-primary hover:text-primary px-xl py-md rounded-full font-label-md whitespace-nowrap active:scale-95 transition-transform">Fruit
                    Refreshers</button>
                <button
                    class="bg-white border border-outline-variant/30 text-on-surface-variant hover:border-primary hover:text-primary px-xl py-md rounded-full font-label-md whitespace-nowrap active:scale-95 transition-transform">Vegan
                    Shakes</button>
            </div>
            <!-- Flash Sale Bento Grid -->
            <section class="mb-2xl">
                <div class="flex justify-between items-end mb-xl">
                    <div>
                        <h2 class="font-headline-lg text-headline-lg text-on-surface">Flash Sale</h2>
                        <p class="text-on-surface-variant font-body-md">Limited time offers ending soon.</p>
                    </div>
                    <div class="flex gap-sm" id="countdown">
                        <div class="bg-error-container text-on-error-container p-2 rounded-lg text-center min-w-[50px]">
                            <div class="font-bold text-lg" id="hours">02</div>
                            <div class="text-[10px] uppercase">Hrs</div>
                        </div>
                        <div class="bg-error-container text-on-error-container p-2 rounded-lg text-center min-w-[50px]">
                            <div class="font-bold text-lg" id="minutes">45</div>
                            <div class="text-[10px] uppercase">Min</div>
                        </div>
                        <div class="bg-error-container text-on-error-container p-2 rounded-lg text-center min-w-[50px]">
                            <div class="font-bold text-lg" id="seconds">12</div>
                            <div class="text-[10px] uppercase">Sec</div>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-lg">
                    <!-- High Focus Sale Item -->
                    <div
                        class="md:col-span-2 bg-secondary-container/20 rounded-2xl p-xl flex flex-col md:flex-row gap-xl items-center border border-secondary-container/30 relative overflow-hidden group">
                        <div class="relative z-10 space-y-md w-full md:w-1/2">
                            <span
                                class="text-secondary font-label-md bg-secondary-container px-3 py-1 rounded-full">-40%
                                Off</span>
                            <h3 class="font-headline-md text-headline-md text-on-surface">Honey Lavender Cold Brew</h3>
                            <p class="text-on-surface-variant font-body-md">Our signature brew with floral notes and
                                natural honey.</p>
                            <div class="flex items-center gap-md">
                                <span class="font-headline-md text-primary">$4.20</span>
                                <span class="text-on-surface-variant/60 line-through text-body-md">$7.00</span>
                            </div>
                            <button
                                class="bg-primary text-white w-full py-md rounded-xl font-bold shadow-md hover:shadow-lg transition-all active:scale-95">Add
                                to Order</button>
                        </div>
                        <div class="w-full md:w-1/2 h-64 bg-cover bg-center rounded-xl shadow-lg transform group-hover:scale-105 transition-transform duration-500"
                            data-alt="A top-down view of a modern cold brew coffee with subtle purple lavender sprigs and golden honey drizzled on the side. The lighting is crisp and bright, highlighting the condensation on the glass. The background is a clean white marble surface with premium aesthetic vibes."
                            style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBwrBj8Hd94YVVHwuv-QqcwzlZNF-qhCApXzR9lE0LWasR3voHzawBFsMChGg_U4w8t5QvkeXGR-EY8KtA7voDr-PVsMQWPmDQyXRjqrlhsXzqVh0T9UGjzFUd6dznUHBCUEfSCZ-P63IKbEm6ZBv-CEdjNe2RM3-j8MIFYnJaE1tIyg5XrLjjO9t8x1-7JniapBfyYIIqPHyp_D_qZDK8DuMKExOpaZ1aXdgTghecYQYqQqsvxNWBiBWWSDDxzyYOjR3dKvgQr')">
                        </div>
                    </div>
                    <!-- Regular Sale Items -->
                    <div
                        class="bg-white rounded-2xl p-md border border-outline-variant/30 hover:shadow-md transition-all flex flex-col">
                        <div class="h-40 bg-cover bg-center rounded-xl mb-md"
                            data-alt="A close-up shot of a vibrant red strawberry hibiscus tea in a tall glass with floating strawberry slices. Minimalist white background, bright lighting, high-end beverage photography style."
                            style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuB-90YbFgV3AT2wRJAa1E9Cs1PFazrggOXNWGYB7Cl4gP48QIXZNIoy2dVy_yYc91V6I5Y8bJOstosJ_C25P7WEvkHr_XbK7n153Tsg8p434_eHAIX-v9mOXHKTEkqwBi_zlaphgVd_vxGOj8_6jHMqNS-lzIcVyNFM-KPEM9IN2UqHjJtIOPoGmLxoI9vrl6x1HjR1MkhN5eWcVOdQrzrig_fUPZihpRM7cvYVJ3x6vtPWj6CPpAezHCHGfE67zqt1Bz3DrtSL')">
                        </div>
                        <h4 class="font-title-lg text-title-lg mb-xs">Strawberry Hibiscus</h4>
                        <p class="text-on-surface-variant text-body-md mb-md">Zesty &amp; caffeine-free</p>
                        <div class="mt-auto flex justify-between items-center">
                            <span class="text-primary font-bold">$3.50</span>
                            <button
                                class="w-10 h-10 bg-primary-container text-on-primary-container rounded-full flex items-center justify-center active:scale-90 transition-transform">
                                <span class="material-symbols-outlined">add</span>
                            </button>
                        </div>
                    </div>
                    <div
                        class="bg-white rounded-2xl p-md border border-outline-variant/30 hover:shadow-md transition-all flex flex-col">
                        <div class="h-40 bg-cover bg-center rounded-xl mb-md"
                            data-alt="A minimalist photograph of a steaming hot white chocolate mocha in a ceramic cup with a delicate leaf-shaped latte art. Soft window lighting, neutral colors, cozy premium atmosphere."
                            style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAbXCmgeyKxE1uow2B5nT-Scs6v42a_jf0NsfDbC6jmyFdiEkAu1rVEw7iTLloMfYLyl7yCT9CanezCGWn_L4t9Anq6ejl4lplbsBTh6cag0tsMYUH96y83SmzIqZ5Vo--RdJOgTCZr_YP1CA6biHh1pnOiB_TyH0mBdtdrS1RYTmKieyiT8BbrPZY7cr6mEqdPPnObLEsxaKcGZtRGvzESPCw3PDv2T025A8i0PzhxTSfijH-HTwZWnl34MIlQzYrFQltrx7w7')">
                        </div>
                        <h4 class="font-title-lg text-title-lg mb-xs">White Mocha</h4>
                        <p class="text-on-surface-variant text-body-md mb-md">Smooth velvety blend</p>
                        <div class="mt-auto flex justify-between items-center">
                            <span class="text-primary font-bold">$4.50</span>
                            <button
                                class="w-10 h-10 bg-primary-container text-on-primary-container rounded-full flex items-center justify-center active:scale-90 transition-transform">
                                <span class="material-symbols-outlined">add</span>
                            </button>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Best Sellers Section -->
            <section class="mb-2xl">
                <div class="flex justify-between items-center mb-xl">
                    <h2 class="font-headline-lg text-headline-lg">Best Sellers</h2>
                    <a class="text-primary font-label-md hover:underline" href="#">View All</a>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-lg">
                    <!-- Product Card -->
                    <div class="group cursor-pointer">
                        <div class="relative aspect-square rounded-2xl overflow-hidden mb-md shadow-sm">
                            <div class="absolute inset-0 bg-cover bg-center transform group-hover:scale-110 transition-transform duration-700"
                                data-alt="A professional beverage photo of a classic iced caramel macchiato with visible layers of milk, coffee, and caramel drizzle. High-key lighting, bright minimalist cafe environment, clean aesthetic."
                                style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuC4-MGYFaDGPZqdeeQfZYeaWt1pKJPcKIhZVUeyUSZg1LWnCRzKlvx2rfvw9wUABmln82J2CbJwqIEr5vfCfeepy3kKHWriezOdTWCLxl-ef6EZZ3E720VAywZkVO5pC5yW8_mkqWpvGaQfnZuiMwKEGAcz_OkbteDT581KojiPYzPeXpB-9Cv0UReacO2oJ6SFFBO2GjXzkh87S8RxACPVEnECZ-siokUbiDIIAHk03Za7HO4hM7RjVZryq-1puNwM55TDK_aS')">
                            </div>
                            <button
                                class="absolute top-3 right-3 w-8 h-8 bg-white/80 backdrop-blur-sm rounded-full flex items-center justify-center text-on-surface hover:text-error transition-colors">
                                <span class="material-symbols-outlined text-[20px]">favorite</span>
                            </button>
                        </div>
                        <h3 class="font-title-lg text-title-lg mb-xs group-hover:text-primary transition-colors">Caramel
                            Macchiato</h3>
                        <div class="flex items-center gap-xs mb-sm">
                            <span class="material-symbols-outlined text-tertiary text-[14px]"
                                style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="text-label-sm">4.9 (1.2k)</span>
                        </div>
                        <span class="font-headline-md text-primary">$5.50</span>
                    </div>
                    <!-- Repeat Product Cards -->
                    <div class="group cursor-pointer">
                        <div class="relative aspect-square rounded-2xl overflow-hidden mb-md shadow-sm">
                            <div class="absolute inset-0 bg-cover bg-center transform group-hover:scale-110 transition-transform duration-700"
                                data-alt="A crisp image of an organic green tea in a clear glass teapot with floating tea leaves. The background is soft and white, highlighting the purity and freshness of the beverage. Premium minimalist style."
                                style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBvH58JTC-E_tzlftX6tVVAz4mJQ_fzNWN0rK3vkFeyTaJXk0t5L7rBTM6hDnPX4bhq6eRCs36s_dILKO2E23QvbYbZtNi3txdgP1egh730jiTnaH4jKXpnl5Bcm3w7gPSzi3YUdzmHVTgk24Ys__IZtVXYsEqlERqbkkxUE18SNh-iX5_KwlQVXozkGXrGnA-xwcGN7lIUtMW5Tz-p87-DUTqLrTxaNn6ErFNMEJDNVJ-i-6g2t7RG7qDmyX7Xf_aNk7WJTDZN')">
                            </div>
                            <button
                                class="absolute top-3 right-3 w-8 h-8 bg-white/80 backdrop-blur-sm rounded-full flex items-center justify-center text-on-surface hover:text-error transition-colors">
                                <span class="material-symbols-outlined text-[20px]">favorite</span>
                            </button>
                        </div>
                        <h3 class="font-title-lg text-title-lg mb-xs group-hover:text-primary transition-colors">Pure
                            Jade Matcha</h3>
                        <div class="flex items-center gap-xs mb-sm">
                            <span class="material-symbols-outlined text-tertiary text-[14px]"
                                style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="text-label-sm">4.8 (850)</span>
                        </div>
                        <span class="font-headline-md text-primary">$6.25</span>
                    </div>
                    <div class="group cursor-pointer">
                        <div class="relative aspect-square rounded-2xl overflow-hidden mb-md shadow-sm">
                            <div class="absolute inset-0 bg-cover bg-center transform group-hover:scale-110 transition-transform duration-700"
                                data-alt="A modern presentation of an avocado smoothie with a swirl of honey and almond flakes on top. Soft, diffused lighting, clean light-mode aesthetic, healthy and organic vibes."
                                style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDheuJmhfv3BMiWiXHpGMP7yhWubVhVii8PRRXsT_CN5rap_qrLDV-RZ0zuS7gkZ_koFIXih8vgZcRRr_77pKe9Dw0hqm-RUt0tSvpgZ-MGkTgIT8hBElEtqPQpAVKMbP2NXmJAR8QrPdeGIEpg4v5LCY_Ej8K6A1TQID_UTg5YzplLrKKNW7AWYM2oY2k9X4GnV8hcgnaJrKXAlDoJE6yEFenocxFsaIhRqhQbX0OjbiD9IGxAh6P6_p8oXU7aC7GBVMOcei2q')">
                            </div>
                            <button
                                class="absolute top-3 right-3 w-8 h-8 bg-white/80 backdrop-blur-sm rounded-full flex items-center justify-center text-on-surface hover:text-error transition-colors">
                                <span class="material-symbols-outlined text-[20px]">favorite</span>
                            </button>
                        </div>
                        <h3 class="font-title-lg text-title-lg mb-xs group-hover:text-primary transition-colors">Avocado
                            Super Green</h3>
                        <div class="flex items-center gap-xs mb-sm">
                            <span class="material-symbols-outlined text-tertiary text-[14px]"
                                style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="text-label-sm">4.7 (2.1k)</span>
                        </div>
                        <span class="font-headline-md text-primary">$7.50</span>
                    </div>
                    <div class="group cursor-pointer">
                        <div class="relative aspect-square rounded-2xl overflow-hidden mb-md shadow-sm">
                            <div class="absolute inset-0 bg-cover bg-center transform group-hover:scale-110 transition-transform duration-700"
                                data-alt="A refreshing lychee and lime sparkling water with fresh mint and ice cubes. The lighting is bright and sunny, showing the effervescence of the drink. Minimalist aesthetic."
                                style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBo2ekGSLNu2uZzO0H9OBrZc6ImhcSsTPhTeC4kTveDJzC6s7OUV8OQt5am-ev5GYU-IyUrzU4zATOs13IVHm549tnNUdBQ3v1hpf2R67eMwZ7v6b7OPJnpeXA1BZCknAOFCEQVS3Cg0NelXxmSOKDmrzNKOJuLaQDvlgn9DOOSvjHnbuiy3yPoC7UUFqnlsfzkyA80wbluoAb6Fpb2QOxmrj5IzzEoLxdaiCjFggqYRZZmvhsfnoVzzYRckvD8q2WfdXoNH_7x')">
                            </div>
                            <button
                                class="absolute top-3 right-3 w-8 h-8 bg-white/80 backdrop-blur-sm rounded-full flex items-center justify-center text-on-surface hover:text-error transition-colors">
                                <span class="material-symbols-outlined text-[20px]">favorite</span>
                            </button>
                        </div>
                        <h3 class="font-title-lg text-title-lg mb-xs group-hover:text-primary transition-colors">Lychee
                            Sparkler</h3>
                        <div class="flex items-center gap-xs mb-sm">
                            <span class="material-symbols-outlined text-tertiary text-[14px]"
                                style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="text-label-sm">4.9 (430)</span>
                        </div>
                        <span class="font-headline-md text-primary">$4.75</span>
                    </div>
                </div>
            </section>
            <!-- Promotion Banner -->
            <section class="mb-2xl">
                <div
                    class="w-full bg-primary h-48 rounded-3xl relative overflow-hidden flex items-center px-2xl group cursor-pointer">
                    <div
                        class="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-white via-transparent to-transparent">
                    </div>
                    <div class="relative z-10 text-white max-w-lg">
                        <h2 class="font-headline-lg text-headline-lg mb-sm">Join CozyHNA Rewards</h2>
                        <p class="font-body-lg opacity-80 mb-md">Earn 2 points for every $1 spent. Get a free drink on
                            your birthday!</p>
                        <button
                            class="bg-white text-primary px-lg py-sm rounded-full font-bold hover:bg-opacity-90 transition-all active:scale-95">Learn
                            More</button>
                    </div>
                    <div class="absolute right-0 top-0 h-full w-1/3 bg-cover bg-center hidden md:block"
                        data-alt="A detailed flat-lay illustration of various digital reward icons, coffee beans, and points symbols in a premium minimalist green and white palette. Cohesive with a modern app design aesthetic."
                        style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAI30wqcewCLnhsqyHaoiuDDthOI6iBLAA5LOrst2bcBInshXFeqaJGp45Z-jZewh-rDo4e55WiVzhKWv089BftQ3DyMlSAM2KcCqNxj17JR4uEYCe0llYmO2pagDn1cI1mBSGB5qRJQl6AHNovE8WJdEXKETt2xg6xbRSQ8Hi8AyBDEkQQt4qtaox6z6ULudJPqyXR8ZRaB8ecHsRJWKVSbSO0o4vdUPmiVzbFwHOWPdogO9ev1wEIUcjw96nsQvQj5DYWFOD0')">
                    </div>
                </div>
            </section>
            <!-- New Drinks & Recommended (Asymmetric Layout) -->
            <section class="mb-2xl grid grid-cols-1 md:grid-cols-12 gap-xl">
                <div class="md:col-span-8">
                    <div class="flex justify-between items-center mb-xl">
                        <h2 class="font-headline-lg text-headline-lg">New Arrivals</h2>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-lg">
                        <div
                            class="bg-white border border-outline-variant/20 rounded-3xl p-lg flex gap-lg hover:shadow-lg transition-all duration-300">
                            <div class="w-32 h-32 flex-shrink-0 bg-cover bg-center rounded-2xl"
                                data-alt="A gourmet iced peach oolong tea with real peach chunks and a sprig of thyme. Bright, clean lighting, white background."
                                style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBa6eHNVlEDo8lB6YZq4FQMKvZSPD8CVhviiiR5H9Fnnn8xproTescX3C-aPke_USIrKFtYcLeHLGh_BIaWEm6eSrl2_RiRzkiCKKNzcy8HwRrFUEq3UeXYmMx9iK-FGdEG9P4JKoo8cI7JAEmjSS0wJvOZtl_S5jwo8fU9nHf1q3NrbpGjVGpxpjOu2gc_e0Ttb_rcGXjp6WyRaKJax-3G6duCUeeoi19zGvSY2P7a9ojFnvIspbWnQ34GKFF8-yyPDx3z7IS9')">
                            </div>
                            <div class="flex flex-col justify-center">
                                <span class="text-primary font-label-md mb-base">Just Launched</span>
                                <h3 class="font-title-lg text-title-lg mb-base">Peach Oolong Cloud</h3>
                                <p class="text-on-surface-variant text-body-md line-clamp-2">Light oolong topped with
                                    creamy peach cold foam.</p>
                                <div class="mt-md flex items-center justify-between">
                                    <span class="font-bold text-primary">$6.75</span>
                                    <button
                                        class="bg-surface-container-high p-2 rounded-full material-symbols-outlined text-[20px] text-primary">add_shopping_cart</button>
                                </div>
                            </div>
                        </div>
                        <div
                            class="bg-white border border-outline-variant/20 rounded-3xl p-lg flex gap-lg hover:shadow-lg transition-all duration-300">
                            <div class="w-32 h-32 flex-shrink-0 bg-cover bg-center rounded-2xl"
                                data-alt="A fancy dark chocolate mocha with a dusting of gold leaf and cocoa powder. Moody yet bright high-end photography."
                                style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDUhWioD4sE3ZRqqZ-KEmEMA9ta8ZgpLGGc5COWB5eyYof7Za1Im4IpN2fokzu-X3xPA7eA5mkjbOWl_juljI4mX-Z3dDilQ_n_6VTHKtqDyhg8upxu9MGUoBDxOlctdpVL1BfX11eLjWlKFt3u0Cx6uxFtWsPRogr4mRQPkLlrwafI1XaHj9nNq580azXRaJiUWjlPCQCl3npd4w_44ldq2sGiWjJCHTx6XSxewVEWGJ5IKBsNNSH_O2_29dklKlzfEwgFbll5')">
                            </div>
                            <div class="flex flex-col justify-center">
                                <span class="text-primary font-label-md mb-base">Premium Choice</span>
                                <h3 class="font-title-lg text-title-lg mb-base">Midnight Truffle Mocha</h3>
                                <p class="text-on-surface-variant text-body-md line-clamp-2">70% single-origin cacao
                                    with a hint of sea salt.</p>
                                <div class="mt-md flex items-center justify-between">
                                    <span class="font-bold text-primary">$7.25</span>
                                    <button
                                        class="bg-surface-container-high p-2 rounded-full material-symbols-outlined text-[20px] text-primary">add_shopping_cart</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="md:col-span-4 bg-surface-container-low rounded-3xl p-xl border border-outline-variant/10">
                    <h2 class="font-title-lg text-title-lg mb-xl">Recommended for You</h2>
                    <div class="space-y-lg">
                        <div class="flex items-center gap-md">
                            <div class="w-16 h-16 rounded-xl bg-cover bg-center"
                                data-alt="Small thumbnail of a coconut milk flat white."
                                style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDo6Z3GxYkI383c2qCRz4jaLK_7_YV2M4F02uFRF3h9s3auPjiai_0J6nPn8Z26mFRqqf9XD6Sph9_4V2GnoCImQHptjiTihtIVFD-E7gqj9zV7EyIx4MTXRCjqC6sJJdebnK3luiyaVL_JXUURKLkt8w0svI4D4BHbXlk55MkXqSS8hDHITlZLFXEmb4cy4do-ckOlcC87u1CVMejkjHvwPnT1ZKP86abwZJ20agtj0wk8bp2VjnNecJQ9ApcTCJfPNRtZ_tVx')">
                            </div>
                            <div class="flex-1">
                                <p class="font-bold text-body-lg">Coconut Flat White</p>
                                <p class="text-label-md text-on-surface-variant">$5.25</p>
                            </div>
                            <button class="material-symbols-outlined text-outline">add</button>
                        </div>
                        <div class="flex items-center gap-md">
                            <div class="w-16 h-16 rounded-xl bg-cover bg-center"
                                data-alt="Small thumbnail of an iced chai latte."
                                style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDIlkSP1DVfOPprBoJq0TN3Z-nPrzotbljnJ0uJlQKsN4peRZWKlqMokR8LULLJy5H-Go5XPKe_ruNMsFw8DEgsWs9ucwm17Gvsj7R60sNXgWZLYy4hF7f6Eta8DuGRHsQwOn4PXEPO1JRYRbEXGXe7XQkErWnpu3pAIsmWNayBbL1ypLu-uvL05VkRpr4vBhIX6E-E6B4ZEnCrVF1VMONnAKAP5WWcwzToxZw3Zt91djAVHY5M65uVQZhlmGqtfwxymQ2RbgHM')">
                            </div>
                            <div class="flex-1">
                                <p class="font-bold text-body-lg">Artisanal Chai Latte</p>
                                <p class="text-label-md text-on-surface-variant">$5.50</p>
                            </div>
                            <button class="material-symbols-outlined text-outline">add</button>
                        </div>
                        <div class="flex items-center gap-md">
                            <div class="w-16 h-16 rounded-xl bg-cover bg-center"
                                data-alt="Small thumbnail of a ginger lemon infusion."
                                style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDpSRl2ijxJyu4iipxiHtdiU608TS1c6lVZNTSs3bSIdlupfKS1TDAU0RzR6TKth0qj0ndwif7xKpG_OiJ4L9ZsGaY73K4ezC9f9EFMda2bbkNrigyyk1PaT9JOieIS-7fO3MzYGLkHy6_hoIgtX3NBJLvKQV-UdyAW9iFvuYInRlUIVO9HvSxEp0Kf_Sv_5DZydvW5sjGJYD8KGt6iwxxlfzWBYyUbc5hBz5Wo2iD86cC5NfnTD9sFO9gO1YJUVE7ziM5DYAcP')">
                            </div>
                            <div class="flex-1">
                                <p class="font-bold text-body-lg">Ginger Lemon Infusion</p>
                                <p class="text-label-md text-on-surface-variant">$4.00</p>
                            </div>
                            <button class="material-symbols-outlined text-outline">add</button>
                        </div>
                    </div>
                    <button
                        class="w-full mt-xl py-md border-2 border-primary/20 rounded-xl text-primary font-bold hover:bg-primary/5 transition-colors">See
                        Personal Mix</button>
                </div>
            </section>
            <!-- Nearby Stores Section -->
            <section class="mb-2xl">
                <div class="flex justify-between items-center mb-xl">
                    <h2 class="font-headline-lg text-headline-lg">Nearby Stores</h2>
                    <button class="flex items-center gap-base text-primary font-label-md">
                        <span class="material-symbols-outlined text-[18px]">my_location</span> Use Current Location
                    </button>
                </div>
                <div class="flex flex-col md:flex-row gap-lg h-[400px]">
                    <div class="w-full md:w-1/3 space-y-md overflow-y-auto pr-md no-scrollbar">
                        <div class="bg-white p-lg rounded-2xl border border-primary shadow-sm ring-1 ring-primary/20">
                            <div class="flex justify-between mb-base">
                                <h3 class="font-bold">CozyHNA - Downtown Core</h3>
                                <span class="text-primary text-label-sm">Open</span>
                            </div>
                            <p class="text-body-md text-on-surface-variant mb-md">124 Premium St, West Side</p>
                            <div class="flex items-center gap-xl text-label-md">
                                <span class="flex items-center gap-xs"><span
                                        class="material-symbols-outlined text-[16px]">directions_walk</span> 5
                                    min</span>
                                <span class="flex items-center gap-xs"><span
                                        class="material-symbols-outlined text-[16px]">bolt</span> Ready in 8 min</span>
                            </div>
                        </div>
                        <div
                            class="bg-white p-lg rounded-2xl border border-outline-variant/30 hover:border-primary/50 transition-colors">
                            <div class="flex justify-between mb-base">
                                <h3 class="font-bold">CozyHNA - Garden Plaza</h3>
                                <span class="text-primary text-label-sm">Open</span>
                            </div>
                            <p class="text-body-md text-on-surface-variant mb-md">45 Green Blvd, South District</p>
                            <div class="flex items-center gap-xl text-label-md">
                                <span class="flex items-center gap-xs"><span
                                        class="material-symbols-outlined text-[16px]">directions_car</span> 12
                                    min</span>
                                <span class="flex items-center gap-xs"><span
                                        class="material-symbols-outlined text-[16px]">bolt</span> Ready in 15 min</span>
                            </div>
                        </div>
                        <div
                            class="bg-white p-lg rounded-2xl border border-outline-variant/30 hover:border-primary/50 transition-colors opacity-70">
                            <div class="flex justify-between mb-base">
                                <h3 class="font-bold">CozyHNA - Waterfront</h3>
                                <span class="text-error text-label-sm">Closing Soon</span>
                            </div>
                            <p class="text-body-md text-on-surface-variant mb-md">88 Harbor Way, East Pier</p>
                            <div class="flex items-center gap-xl text-label-md">
                                <span class="flex items-center gap-xs"><span
                                        class="material-symbols-outlined text-[16px]">directions_walk</span> 20
                                    min</span>
                                <span class="flex items-center gap-xs"><span
                                        class="material-symbols-outlined text-[16px]">bolt</span> Ready in 5 min</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex-1 bg-surface-container-high rounded-3xl overflow-hidden relative">
                        <div class="absolute inset-0 bg-cover bg-center"
                            data-alt="A minimalist and stylish stylized map of a metropolitan area with custom brand pins indicating store locations. The map uses a light color palette with soft greens and neutral grays."
                            data-location="New York City"
                            style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuD9ePNddLwvVZrdsw2eXQrgRLdf_4SSHRbq8oDsZiXu8Id_DdgBY64wFaO3c-GzkS_iXZLTxB242pPdNwM9D2FIbWVrucRiGM2Oa8josFaiX5SGA1lqZrB7JE7XKtbWN6eqe9k1uK2u9yf5F93IDjE26B9dZW5bRZT919ZqPQWb4jzA-0I88j2fQx0FJirslDcNbYLksxbf_dCQyHfrBjtEMLUbbuwjg44JMJQHMHjWr1x9B_f1kpV9_9TY8fFm1rGE-TxxX23X')">
                        </div>
                        <div class="absolute bottom-4 right-4 flex flex-col gap-sm">
                            <button
                                class="w-10 h-10 bg-white shadow-md rounded-full flex items-center justify-center text-on-surface active:scale-90 transition-transform">
                                <span class="material-symbols-outlined">add</span>
                            </button>
                            <button
                                class="w-10 h-10 bg-white shadow-md rounded-full flex items-center justify-center text-on-surface active:scale-90 transition-transform">
                                <span class="material-symbols-outlined">remove</span>
                            </button>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Customer Reviews Carousel -->
            <section class="mb-2xl pb-xl">
                <h2 class="font-headline-lg text-headline-lg text-center mb-2xl">From Our Community</h2>
                <div class="flex gap-lg overflow-x-auto no-scrollbar -mx-lg px-lg">
                    <div class="min-w-[300px] bg-white p-xl rounded-3xl border border-outline-variant/20 shadow-sm">
                        <div class="flex items-center gap-md mb-lg">
                            <div class="w-12 h-12 rounded-full bg-cover bg-center"
                                data-alt="Profile photo of a professional woman in her 30s smiling warmly."
                                style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCqDJBMSdyDepeMrePkCzFRuQlsUAaF5JqE8nN-PkWHpElIbsRlVYA5g2cMAap5afA72qQv4mefYFylUSHLa9T_bNSs1lSAqji25WTUWXyyqJ6TZ6e-Mp__5Q1O_pjXf61jB8hyB2ftOTG09yZkhtAMG0MHcldyYWWxqpETK6PBjt8EOsZbmm8CBi86WbZ_gpmqtxWDz_f3WwvlTtE6ntkk9oOMKmU1UPN_UKndg3n-rwk6w2QeUtp2_0si2uCZJ34hfm3ym8Jd')">
                            </div>
                            <div>
                                <p class="font-bold">Sarah Jenkins</p>
                                <div class="flex text-tertiary">
                                    <span class="material-symbols-outlined text-[16px]"
                                        style="font-variation-settings: 'FILL' 1;">star</span>
                                    <span class="material-symbols-outlined text-[16px]"
                                        style="font-variation-settings: 'FILL' 1;">star</span>
                                    <span class="material-symbols-outlined text-[16px]"
                                        style="font-variation-settings: 'FILL' 1;">star</span>
                                    <span class="material-symbols-outlined text-[16px]"
                                        style="font-variation-settings: 'FILL' 1;">star</span>
                                    <span class="material-symbols-outlined text-[16px]"
                                        style="font-variation-settings: 'FILL' 1;">star</span>
                                </div>
                            </div>
                        </div>
                        <p class="text-body-lg italic text-on-surface-variant">"The Honey Lavender Cold Brew is
                            life-changing. It's the highlight of my morning commute!"</p>
                    </div>
                    <div class="min-w-[300px] bg-white p-xl rounded-3xl border border-outline-variant/20 shadow-sm">
                        <div class="flex items-center gap-md mb-lg">
                            <div class="w-12 h-12 rounded-full bg-cover bg-center"
                                data-alt="Profile photo of a young male creative professional with glasses."
                                style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBcnfh2xr2UaEragR_HGrXCX0ihQKAKnV62VrP_u2FAZqLz6D7MWobvHUClJyJ1g3VlDh3d4CFS9Io6BFqsXgYfxlxfRKS0_SqSkT7RISjbyKK-GYjogBLN3zGMCsaTQR0B6LH4e7ZaPxxWQ83BiGZWQ17Y9iypeYDMi7RJCysHmbSiQAWBftIoxoD984EPGVIMqeNvveWw0VQGr9AFGModg46ZxsNSyRMTIOS9X8u6huGhQHluLmEtkuufRn7JHTxyB1i-AYh_')">
                            </div>
                            <div>
                                <p class="font-bold">Mark Thompson</p>
                                <div class="flex text-tertiary">
                                    <span class="material-symbols-outlined text-[16px]"
                                        style="font-variation-settings: 'FILL' 1;">star</span>
                                    <span class="material-symbols-outlined text-[16px]"
                                        style="font-variation-settings: 'FILL' 1;">star</span>
                                    <span class="material-symbols-outlined text-[16px]"
                                        style="font-variation-settings: 'FILL' 1;">star</span>
                                    <span class="material-symbols-outlined text-[16px]"
                                        style="font-variation-settings: 'FILL' 1;">star</span>
                                    <span class="material-symbols-outlined text-[16px]"
                                        style="font-variation-settings: 'FILL' 1;">star</span>
                                </div>
                            </div>
                        </div>
                        <p class="text-body-lg italic text-on-surface-variant">"Incredible interface and even better
                            coffee. The loyalty program is actually worth it."</p>
                    </div>
                    <div class="min-w-[300px] bg-white p-xl rounded-3xl border border-outline-variant/20 shadow-sm">
                        <div class="flex items-center gap-md mb-lg">
                            <div class="w-12 h-12 rounded-full bg-cover bg-center"
                                data-alt="Profile photo of a stylish elderly woman with grey hair and a bright smile."
                                style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAjz3WkQwhsh3Uw5mdykPnktriiVKaDIsbonUDd90YPCreD_BuAtNOSQGQAdb48lyO3ySxt28XbyEYFDd4Vbm4tsS6pLbVd2wBI43cFDhwOGhjdd_F8OCRDZBFLaMW8JZPCHyy6KYLMXLyJCcd5r-D5UeWMRbnQ5l3cmGLM8a6nM0lixPzI3rittha8M_99Tm4Pz6qkYKp8iQxXGLcKaj4RoV4QMxfYJgeYy5cr3u3Tq6r58SnRx-F-Rbo-BHSd0OCVPAx75vmE')">
                            </div>
                            <div>
                                <p class="font-bold">Elena Rodriguez</p>
                                <div class="flex text-tertiary">
                                    <span class="material-symbols-outlined text-[16px]"
                                        style="font-variation-settings: 'FILL' 1;">star</span>
                                    <span class="material-symbols-outlined text-[16px]"
                                        style="font-variation-settings: 'FILL' 1;">star</span>
                                    <span class="material-symbols-outlined text-[16px]"
                                        style="font-variation-settings: 'FILL' 1;">star</span>
                                    <span class="material-symbols-outlined text-[16px]"
                                        style="font-variation-settings: 'FILL' 1;">star</span>
                                    <span class="material-symbols-outlined text-[16px]">star</span>
                                </div>
                            </div>
                        </div>
                        <p class="text-body-lg italic text-on-surface-variant">"I love that they have so many vegan
                            options that don't compromise on taste."</p>
                    </div>
                </div>
            </section>
        </div>
    </main>
    <!-- Mobile Bottom Navigation -->
    <nav
        class="md:hidden fixed bottom-0 left-0 w-full z-50 bg-surface flex justify-around items-center px-4 py-2 pb-safe rounded-t-xl shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] border-t border-outline-variant/10">
        <button
            class="flex flex-col items-center justify-center bg-primary-container text-on-primary-container rounded-2xl px-4 py-1 active:scale-90 transition-transform">
            <span class="material-symbols-outlined" data-icon="home">home</span>
            <span class="font-label-sm text-label-sm">Home</span>
        </button>
        <button
            class="flex flex-col items-center justify-center text-on-surface-variant hover:text-primary active:scale-90 transition-transform">
            <span class="material-symbols-outlined" data-icon="local_cafe">local_cafe</span>
            <span class="font-label-sm text-label-sm">Order</span>
        </button>
        <button
            class="flex flex-col items-center justify-center text-on-surface-variant hover:text-primary active:scale-90 transition-transform">
            <span class="material-symbols-outlined" data-icon="history">history</span>
            <span class="font-label-sm text-label-sm">History</span>
        </button>
        <button
            class="flex flex-col items-center justify-center text-on-surface-variant hover:text-primary active:scale-90 transition-transform">
            <span class="material-symbols-outlined" data-icon="person">person</span>
            <span class="font-label-sm text-label-sm">Profile</span>
        </button>
    </nav>
    <!-- Footer (Desktop) -->
    <footer class="hidden md:block bg-surface-container-low border-t border-outline-variant/30 py-2xl">
        <div class="max-w-container-max mx-auto px-lg grid grid-cols-4 gap-2xl">
            <div class="col-span-1">
                <span class="font-title-lg text-title-lg font-bold text-primary mb-md block">CozyHNA</span>
                <p class="text-on-surface-variant text-body-md mb-xl">Crafting premium moments, one sip at a time. Join
                    our community of beverage enthusiasts.</p>
                <div class="flex gap-md">
                    <button
                        class="w-10 h-10 bg-white rounded-full flex items-center justify-center border border-outline-variant/30 hover:border-primary transition-colors">
                        <img alt="Instagram" class="w-5 h-5"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuBYN0XK03Y-SVCZ9wZT7iIEazlsqvpfvK6XaWiHnKbtPVh517AaBUSZnyorG_HLLXMLcE2hwM2hUJOYtSukG4JhDVCjm-awKXffrqqdIDfNdShiOxQAcAm7blEW9Ow5oRrwJ0gdM2E7SYEJuI4NqessfsHCAEdFKYnTrWbxpo5Mt0mfU3rCp2a8pQE61rl3xPKnz9wHP2Rik_6famwGIoUZxrkxFhDJWfl5vRrF4jJnJZEVGZwTOXH53sb1YKcRM-PQ7SlXD9ic" />
                    </button>
                    <button
                        class="w-10 h-10 bg-white rounded-full flex items-center justify-center border border-outline-variant/30 hover:border-primary transition-colors">
                        <img alt="X" class="w-5 h-5"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuBxrW09Rdd_t773Xn3rKQ-_wQdy0ukb7GVcdgJI1xaL2mXJkt3xC-PJwXugYnB0FpVPDCdZLdue4CCjkXjUqlPVs-27ju560PiI63b7grQ03uW6zQ_42xzO3P8W5sP7NVfGCT5eRB1PqTBGztKor-EkV6Ms-I3LPwjMOSFRd1vzey1Lqifqnwxb_GE06T8B_PRmCTIYM9nm6B-u5jDV4loFO_u3yMNIEiBMge0gDr6o-WSggYNu_7-0fTSvXkX29QfnIJnqes1X" />
                    </button>
                    <button
                        class="w-10 h-10 bg-white rounded-full flex items-center justify-center border border-outline-variant/30 hover:border-primary transition-colors">
                        <img alt="Facebook" class="w-5 h-5"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuASdxPgzvWgEatHBLznAKrfDEDF4fdbxRrZAfFXpHnqmBy1hkYEWn7BGjlnYmARK4zxWm5AgtT-cAOSuFNjqgbhjyxHoXuDmc09fKR6e-aOwULbIYAP39N5klsyfFoUFV8a7ZSzCxQmDQIGIank90eMewqMxNG5rXowfJ-1oFhJuLHTPHzQwtKygwZO0B2kDhJgODD8dKdjD1UgL2lkDDbpJdjx7Xf4He3Ia3ir8GklZBSb0P3MLfZjszdgBOmQef_DOouWzhrH" />
                    </button>
                </div>
            </div>
            <div>
                <h4 class="font-bold mb-lg">Company</h4>
                <ul class="space-y-md text-on-surface-variant text-body-md">
                    <li><a class="hover:text-primary" href="#">About Us</a></li>
                    <li><a class="hover:text-primary" href="#">Careers</a></li>
                    <li><a class="hover:text-primary" href="#">Sourcing</a></li>
                    <li><a class="hover:text-primary" href="#">Press</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold mb-lg">Support</h4>
                <ul class="space-y-md text-on-surface-variant text-body-md">
                    <li><a class="hover:text-primary" href="#">Help Center</a></li>
                    <li><a class="hover:text-primary" href="#">Delivery</a></li>
                    <li><a class="hover:text-primary" href="#">Contact Us</a></li>
                    <li><a class="hover:text-primary" href="#">Accessibility</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold mb-lg">Newsletter</h4>
                <p class="text-on-surface-variant text-body-md mb-lg">Get the latest news and offers delivered to your
                    inbox.</p>
                <div class="flex gap-base">
                    <input
                        class="bg-white border border-outline-variant/30 rounded-xl px-4 py-2 flex-1 focus:ring-primary focus:border-primary"
                        placeholder="Email address" type="email" />
                    <button
                        class="bg-primary text-white px-lg py-2 rounded-xl font-bold active:scale-95 transition-transform">Join</button>
                </div>
            </div>
        </div>
        <div
            class="max-w-container-max mx-auto px-lg mt-2xl pt-xl border-t border-outline-variant/10 text-center text-label-md text-on-surface-variant">
            © 2024 CozyHNA. All rights reserved.
        </div>
    </footer>
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
</body>

</html>