<!DOCTYPE html>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>{{ __('CozyHNA - Admin Portal') }}</title>
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
    <main class="min-h-screen flex items-center justify-center relative bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&q=80');">
        <!-- Overlay -->
        <div class="absolute inset-0 bg-black/30"></div>

        <!-- Glass Card -->
        <div class="relative z-10 w-full max-w-[420px] mx-4 p-8 rounded-3xl backdrop-blur-md bg-white/20 border border-white/30 shadow-2xl">
            <div class="form-transition" id="authContent">
                <h2 class="text-3xl font-bold text-white mb-2">{{ __('Admin Login') }}</h2>
                <p class="text-white/90 text-sm mb-8">{{ __('Welcome back please login to your portal') }}</p>

                <form id="authForm" class="space-y-4" method="POST" action="/login/admin">
                    @csrf
                    @if(session('error'))
                        <div class="p-3 bg-red-500/50 border border-red-500/50 text-white rounded-lg text-sm mb-4">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="p-3 bg-red-500/50 border border-red-500/50 text-white rounded-lg text-sm mb-4">
                            <ul class="list-disc pl-5">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div>
                        <div class="relative">
                            <input name="email" class="w-full bg-transparent border border-white/50 rounded-xl px-4 py-3 text-white placeholder-white/80 focus:outline-none focus:border-white focus:ring-1 focus:ring-white transition-all" placeholder="{{ __('Email Address') }}" type="email" value="{{ old('email') }}" required/>
                            <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-white/80">mail</span>
                        </div>
                    </div>
                    
                    <div>
                        <div class="relative">
                            <input name="password" class="w-full bg-transparent border border-white/50 rounded-xl px-4 py-3 text-white placeholder-white/80 focus:outline-none focus:border-white focus:ring-1 focus:ring-white transition-all" id="passwordInput" placeholder="{{ __('Password') }}" type="password" required/>
                            <button class="absolute right-4 top-1/2 -translate-y-1/2 text-white/80 hover:text-white transition-colors" onclick="togglePasswordVisibility()" type="button">
                                <span class="material-symbols-outlined" id="passIcon">visibility_off</span>
                            </button>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center mt-2">
                        <div class="flex items-center gap-2">
                            <input class="w-4 h-4 rounded border-white/50 bg-transparent text-[#39b54a] focus:ring-[#39b54a]" type="checkbox" name="remember"/>
                            <label class="text-sm text-white/90">{{ __('Remember me') }}</label>
                        </div>
                        <a class="text-sm text-white/90 hover:underline transition-opacity" href="/forgot-password">{{ __('Forgot password?') }}</a>
                    </div>

                    <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-[#8cc63f] to-[#39b54a] text-white font-bold text-lg shadow-lg hover:shadow-xl active:scale-[0.98] transition-all mt-6" id="submitBtn">
                        <span>{{ __('Login') }}</span>
                    </button>
                </form>
                
                <div class="mt-8 text-center text-xs text-white/70">{{ __('Created by') }}<span class="font-bold text-white/90">{{ __('CozyHNA') }}</span>
                </div>
            </div>
        </div>
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
