<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>@yield('title', 'CozyHNA Admin')</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet" />

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
            }
        },
    }
    </script>

    <style>
    .material-symbols-outlined {
        font-variation-settings: 'FILL'0, 'wght'400, 'GRAD'0, 'opsz'24;
    }

    body {
        font-family: 'Inter', sans-serif;
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(226, 232, 240, 0.8);
    }
    </style>
</head>

<body class="bg-background text-on-surface">
    <!-- SideNavBar (Shared Component) -->
    <aside
        class="fixed left-0 top-0 h-full w-[280px] bg-surface border-r border-outline-variant/30 shadow-md flex flex-col py-lg px-md z-40 hidden md:flex">
        <div class="mb-2xl px-sm">
            <h1 class="font-headline-md text-headline-md font-bold text-primary">CozyHNA</h1>
            <p class="font-label-md text-label-md text-on-surface-variant">Management Portal</p>
        </div>
        <nav class="flex-1 flex flex-col gap-xs">
            <a href="/admin/dashboard"
                class="flex items-center gap-sm px-md py-sm rounded-lg transition-all
{{ request()->is('admin/dashboard') ? 'bg-secondary-container/30 text-secondary border-l-4 border-primary font-bold' : 'text-on-surface-variant hover:bg-surface-container-high' }}">
                <span class="material-symbols-outlined">dashboard</span>
                <span class="font-label-md text-label-md">Dashboard</span>
            </a>

            <a href="/admin/orders"
                class="flex items-center gap-sm px-md py-sm rounded-lg transition-all
{{ request()->is('admin/orders*') ? 'bg-secondary-container/30 text-secondary border-l-4 border-primary font-bold' : 'text-on-surface-variant hover:bg-surface-container-high' }}">
                <span class="material-symbols-outlined">receipt_long</span>
                <span class="font-label-md text-label-md">Orders</span>
            </a>

            <a href="/admin/product"
                class="flex items-center gap-sm px-md py-sm rounded-lg transition-all
{{ request()->is('admin/product*') ? 'bg-secondary-container/30 text-secondary border-l-4 border-primary font-bold' : 'text-on-surface-variant hover:bg-surface-container-high' }}">
                <span class="material-symbols-outlined">inventory_2</span>
                <span class="font-label-md text-label-md">Products</span>
            </a>

            <a href="/admin/inventory"
                class="flex items-center gap-sm px-md py-sm rounded-lg transition-all
{{ request()->is('admin/inventory*') ? 'bg-secondary-container/30 text-secondary border-l-4 border-primary font-bold' : 'text-on-surface-variant hover:bg-surface-container-high' }}">
                <span class="material-symbols-outlined">inventory</span>
                <span class="font-label-md text-label-md">Inventory</span>
            </a>

            <a href="/admin/customers"
                class="flex items-center gap-sm px-md py-sm rounded-lg transition-all
{{ request()->is('admin/customers*') ? 'bg-secondary-container/30 text-secondary border-l-4 border-primary font-bold' : 'text-on-surface-variant hover:bg-surface-container-high' }}">
                <span class="material-symbols-outlined">group</span>
                <span class="font-label-md text-label-md">Customers</span>
            </a>

            <a href="/admin/shippers"
                class="flex items-center gap-sm px-md py-sm rounded-lg transition-all
{{ request()->is('admin/shippers*') ? 'bg-secondary-container/30 text-secondary border-l-4 border-primary font-bold' : 'text-on-surface-variant hover:bg-surface-container-high' }}">
                <span class="material-symbols-outlined">local_shipping</span>
                <span class="font-label-md text-label-md">Shippers</span>
            </a>

            <a href="/admin/settings"
                class="flex items-center gap-sm px-md py-sm rounded-lg transition-all
{{ request()->is('admin/settings*') ? 'bg-secondary-container/30 text-secondary border-l-4 border-primary font-bold' : 'text-on-surface-variant hover:bg-surface-container-high' }}">
                <span class="material-symbols-outlined">settings</span>
                <span class="font-label-md text-label-md">Settings</span>
            </a>
        </nav>

        <button
            class="mt-md w-full py-sm bg-primary text-on-primary rounded-xl font-bold flex items-center justify-center gap-xs hover:opacity-90 active:scale-95 transition-all">
            <span class="material-symbols-outlined" data-icon="add">add</span>
            New Order
        </button>

        <div class="mt-auto pt-lg border-t border-outline-variant/30 flex flex-col gap-xs">
            <a class="flex items-center gap-sm px-md py-sm text-on-surface-variant hover:bg-surface-container transition-all"
                href="#">
                <span class="material-symbols-outlined" data-icon="help_outline">help_outline</span>
                <span class="font-label-md text-label-md">Support</span>
            </a>

            <a class="flex items-center gap-sm px-md py-sm text-on-surface-variant hover:bg-surface-container transition-all"
                href="/logout">
                <span class="material-symbols-outlined" data-icon="logout">logout</span>
                <span class="font-label-md text-label-md">Logout</span>
            </a>
        </div>
    </aside>

    <!-- Main Content Canvas -->
    <main class="md:ml-[280px] min-h-screen p-md md:p-xl pb-2xl">
        @yield('content')
    </main>

    <!-- BottomNavBar (Shared Component for Mobile) -->
    <nav
        class="fixed bottom-0 left-0 w-full z-50 flex justify-around items-center px-4 py-2 pb-safe bg-surface shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] rounded-t-xl md:hidden">
        <a class="flex flex-col items-center justify-center bg-primary-container text-on-primary-container rounded-2xl px-4 py-1 transition-transform active:scale-90"
            href="/admin/dashboard">
            <span class="material-symbols-outlined" data-icon="home">home</span>
            <span class="font-label-sm text-label-sm">Home</span>
        </a>
        <a class="flex flex-col items-center justify-center text-on-surface-variant hover:text-primary transition-colors"
            href="#">
            <span class="material-symbols-outlined" data-icon="local_cafe">local_cafe</span>
            <span class="font-label-sm text-label-sm">Order</span>
        </a>
        <a class="flex flex-col items-center justify-center text-on-surface-variant hover:text-primary transition-colors"
            href="#">
            <span class="material-symbols-outlined" data-icon="history">history</span>
            <span class="font-label-sm text-label-sm">History</span>
        </a>
        <a class="flex flex-col items-center justify-center text-on-surface-variant hover:text-primary transition-colors"
            href="#">
            <span class="material-symbols-outlined" data-icon="person">person</span>
            <span class="font-label-sm text-label-sm">Profile</span>
        </a>
    </nav>
</body>

</html>