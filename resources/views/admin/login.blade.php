<!DOCTYPE html>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>CozyHNA - Admin Portal</title>
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <!-- Shared Components JSON Logic + Design System Integration -->
    <script id="tailwind-config">
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                "colors": {
                    "tertiary-container": "#c49400",
                    "surface-container-low": "#eff4ff",
                    "error-container": "#ffdad6",
                    "on-tertiary-container": "#433000",
                    "inverse-on-surface": "#eaf1ff",
                    "error": "#ba1a1a",
                    "on-primary-container": "#003c0b",
                    "primary-fixed": "#94f990",
                    "on-secondary-fixed": "#0f2000",
                    "tertiary-fixed": "#ffdf9e",
                    "surface-container-lowest": "#ffffff",
                    "on-tertiary-fixed": "#261a00",
                    "on-tertiary": "#ffffff",
                    "primary-container": "#4caf50",
                    "on-tertiary-fixed-variant": "#5b4300",
                    "on-background": "#0b1c30",
                    "secondary-container": "#b9f474",
                    "primary": "#006e1c",
                    "on-surface": "#0b1c30",
                    "on-secondary-fixed-variant": "#2e4f00",
                    "background": "#f8f9ff",
                    "surface-dim": "#cbdbf5",
                    "secondary-fixed-dim": "#9ed75b",
                    "secondary": "#3e6a00",
                    "on-surface-variant": "#3f4a3c",
                    "on-primary": "#ffffff",
                    "surface-bright": "#f8f9ff",
                    "surface-variant": "#d3e4fe",
                    "on-primary-fixed-variant": "#005313",
                    "on-secondary-container": "#437000",
                    "on-error-container": "#93000a",
                    "surface-container": "#e5eeff",
                    "inverse-primary": "#78dc77",
                    "primary-fixed-dim": "#78dc77",
                    "on-secondary": "#ffffff",
                    "surface-tint": "#006e1c",
                    "surface-container-highest": "#d3e4fe",
                    "surface": "#f8f9ff",
                    "tertiary-fixed-dim": "#fabd00",
                    "secondary-fixed": "#b9f474",
                    "outline-variant": "#becab9",
                    "tertiary": "#785900",
                    "outline": "#6f7a6b",
                    "on-primary-fixed": "#002204",
                    "inverse-surface": "#213145",
                    "surface-container-high": "#dce9ff",
                    "on-error": "#ffffff"
                },
                "borderRadius": {
                    "DEFAULT": "0.25rem",
                    "lg": "0.5rem",
                    "xl": "0.75rem",
                    "full": "9999px"
                },
                "spacing": {
                    "xs": "8px",
                    "sm": "12px",
                    "2xl": "48px",
                    "md": "16px",
                    "gutter": "24px",
                    "xl": "32px",
                    "container-max": "1280px",
                    "base": "4px",
                    "lg": "24px"
                },
                "fontFamily": {
                    "body-lg": ["Inter"],
                    "title-lg": ["Inter"],
                    "headline-lg-mobile": ["Inter"],
                    "body-md": ["Inter"],
                    "label-sm": ["Inter"],
                    "display-lg": ["Inter"],
                    "headline-md": ["Inter"],
                    "headline-lg": ["Inter"],
                    "label-md": ["Inter"]
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
                    "headline-lg-mobile": ["24px", {
                        "lineHeight": "32px",
                        "fontWeight": "600"
                    }],
                    "body-md": ["14px", {
                        "lineHeight": "20px",
                        "fontWeight": "400"
                    }],
                    "label-sm": ["11px", {
                        "lineHeight": "14px",
                        "fontWeight": "600"
                    }],
                    "display-lg": ["48px", {
                        "lineHeight": "56px",
                        "letterSpacing": "-0.02em",
                        "fontWeight": "700"
                    }],
                    "headline-md": ["24px", {
                        "lineHeight": "32px",
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
                    }]
                }
            },
        },
    }
    </script>
    <style>
    .material-symbols-outlined {
        font-variation-settings: 'FILL'0, 'wght'400, 'GRAD'0, 'opsz'24;
    }

    .form-transition {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(12px);
    }

    /* Hiding scrollbar for aesthetic purposes on this focused screen */
    body::-webkit-scrollbar {
        display: none;
    }

    body {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    </style>
</head>

<body class="bg-background text-on-background font-body-lg overflow-hidden">
    <!-- Authentication Screen: Split Layout -->
    <main class="min-h-screen flex items-stretch">
        <!-- Left Side: Lifestyle Imagery (Web Only) -->
        <section class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-primary-container">

            <div class="absolute inset-0 z-10 bg-gradient-to-br from-primary/40 to-transparent"></div>
            <!-- Hero Image Container -->
            <div class="relative z-20 w-full h-full flex flex-col justify-between p-2xl text-on-primary">
                <div>
                    <h1 class="font-display-lg text-display-lg tracking-tight mb-md">CozyHNA Portal</h1>
                    <p class="font-headline-md text-headline-md max-w-md opacity-90">Internal management and operations portal.</p>
                </div>
                <div
                    class="relative w-full aspect-[4/3] rounded-xl shadow-2xl overflow-hidden transform hover:scale-[1.02] transition-transform duration-500">
                    <div class="absolute inset-0 bg-cover bg-center"
                        style="background-image: url('https://images.unsplash.com/photo-1556740749-887f6717d7e4?auto=format&fit=crop&q=80')">
                    </div>
                </div>
            </div>
        </section>
        <!-- Right Side: Auth Form -->
        <section class="w-full lg:w-1/2 bg-surface flex items-center justify-center p-md md:p-xl relative">
            <div class="absolute top-lg left-lg lg:hidden">
                <span class="font-title-lg text-title-lg font-bold text-primary">CozyHNA Portal</span>
            </div>
            <div class="w-full max-w-md">
                <div class="form-transition" id="authContent">
                    <header class="mb-lg">
                        <h2 class="font-headline-lg text-headline-lg mb-xs">Admin Sign In</h2>
                        <p class="font-body-md text-body-md text-on-surface-variant">Please enter your
                            credentials to access the management portal.</p>
                    </header>
                    @if($errors->any())
                        <div class="mb-md p-sm rounded-lg bg-error-container text-on-error-container font-body-md border border-error/20">
                            <ul class="list-disc pl-5">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <!-- Main Form -->
                    <form id="authForm" class="space-y-md" method="POST" action="/login/admin">
                        @csrf
                        <div>
                            <label class="block font-label-md text-label-md text-on-surface-variant mb-xs ml-base">Email
                                Address</label>
                            <div class="relative">
                                <span
                                    class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-on-surface-variant"
                                    style="font-size: 20px;">mail</span>
                                <input
                                    class="w-full pl-11 pr-md py-sm rounded-xl border border-outline-variant bg-surface-container-lowest focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all font-body-md"
                                    placeholder="admin@example.com" type="email" name="email" value="{{ old('email') }}" required />
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between items-center mb-xs ml-base mr-base">
                                <label
                                    class="block font-label-md text-label-md text-on-surface-variant">Password</label>
                            </div>
                            <div class="relative">
                                <span
                                    class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-on-surface-variant"
                                    style="font-size: 20px;">lock</span>
                                <input id="passwordInput"
                                    class="w-full pl-11 pr-11 py-sm rounded-xl border border-outline-variant bg-surface-container-lowest focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all font-body-md"
                                    type="password" name="password" required />
                                <button
                                    class="absolute right-md top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary transition-colors"
                                    onclick="togglePasswordVisibility()" type="button">
                                    <span class="material-symbols-outlined" id="passIcon"
                                        style="font-size: 20px;">visibility</span>
                                </button>
                            </div>
                        </div>
                        <button
                            class="w-full py-md rounded-xl bg-primary-container text-on-primary-container font-headline-md text-headline-md shadow-md hover:shadow-lg active:scale-[0.98] transition-all flex items-center justify-center gap-sm mt-xl"
                            id="submitBtn">
                            <span>Sign In Securely</span>
                            <span class="material-symbols-outlined">shield</span>
                        </button>
                    </form>
                </div>
            </div>
            <!-- Footer Links -->
            <div
                class="absolute bottom-lg left-1/2 -translate-x-1/2 flex gap-lg opacity-40 hover:opacity-100 transition-opacity whitespace-nowrap">
                <a class="font-label-sm text-label-sm hover:text-primary" href="#">Help Center</a>
                <a class="font-label-sm text-label-sm hover:text-primary" href="#">System Status</a>
            </div>
        </section>
    </main>
    <script>
    function togglePasswordVisibility() {
        const input = document.getElementById('passwordInput');
        const icon = document.getElementById('passIcon');

        if (!input) return;

        if (input.type === 'password') {
            input.type = 'text';
            if (icon) icon.innerText = 'visibility_off';
        } else {
            input.type = 'password';
            if (icon) icon.innerText = 'visibility';
        }
    
        return false;
    }

    // Loading state on form submit
    document.querySelector('form').addEventListener('submit', function() {
        const btn = document.getElementById('submitBtn');
        btn.innerHTML = '<span class="material-symbols-outlined animate-spin">progress_activity</span>';
        btn.classList.add('opacity-70', 'pointer-events-none');
    });
    </script>
</body>

</html>
