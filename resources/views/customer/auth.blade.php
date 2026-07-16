@extends('layouts.customer')

@section('title', 'Auth')

@section('content')
<main class="min-h-screen flex items-stretch">
<!-- Left Side: Lifestyle Imagery (Web Only) -->
<section class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-primary-container">

<div class="absolute inset-0 z-10 bg-gradient-to-br from-primary/40 to-transparent"></div>
<!-- Hero Image Container -->
<div class="relative z-20 w-full h-full flex flex-col justify-between p-2xl text-on-primary">
<div>
<h1 class="font-display-lg text-display-lg tracking-tight mb-md">CozyHNA</h1>
<p class="font-headline-md text-headline-md max-w-md opacity-90">Experience the art of artisanal brewing, delivered with organic vitality.</p>
</div>
<div class="relative w-full aspect-[4/3] rounded-xl shadow-2xl overflow-hidden transform hover:scale-[1.02] transition-transform duration-500">
<div class="absolute inset-0 bg-cover bg-center" data-alt="A high-quality lifestyle photograph of a steaming cup of organic matcha latte and a rustic ceramic teapot on a light oak wood table. Soft morning sunlight streams through a nearby window, illuminating the delicate steam and the vibrant green of the tea. The scene is clean, minimal, and premium, using a palette of natural greens, soft whites, and warm wood tones to convey freshness and professional quality." style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuB88XIx9DuZ-3tBGD0O3FcJxJ0w1nvOdGTdIcpLa30Efw6guthGbjOjHWVeG9WbBKb0ZmCCMmnRI7YhEZ9AoLWEUmfKMR81AzXwqVS6fXnZVHMzhBNf3Hkf80wBK90EsM3NH2mJBk_cdB9R4QNaUDAhts--Dn2s26Jii5Fs2e_e0CJ7zuYG4L4hobBIs-YxC51zF01fQ6Bx2WHhib6i0t_54uQhCuM--HH8who0imT3WPyg2Wc21fttm1taLoHpv4brg3yUyeMJ')">
</div>
</div>
<div class="flex items-center gap-md">
<div class="flex -space-x-4">
<div class="w-10 h-10 rounded-full border-2 border-white bg-surface shadow-sm overflow-hidden">
<img class="w-full h-full object-cover" data-alt="Close up profile picture of a smiling young professional woman with glasses, brightly lit in a modern office setting, representing a happy CozyHNA customer." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCkAlxwlT-aTW2dd-tJbAvRdFIgo4qeuEm0dxtA5BUPhpLqyOI2frCwHWyAIZuVqzskhusc8WXH7paAHyiiPoJ-Ox0LL5a06RHucW6FH-EvdEBwvfkhbmSqissScQK70Y5YMqd7ELTqIZL341rUqytGHKhVBJqo-mpy_faN5CpmbdgS20AoLG712h0xqnIEKkVjxy5mAoKj4RQSXFF4Hm9G1I-EWVRQHzFeCuO-z_Um0M_ztQ0bMS0vI7YYP2XUYiwtwo5FT6uZ"/>
</div>
<div class="w-10 h-10 rounded-full border-2 border-white bg-surface shadow-sm overflow-hidden">
<img class="w-full h-full object-cover" data-alt="Portrait of a cheerful barista in a clean green apron, holding a fresh cup of coffee, representing the friendly service of CozyHNA." src="https://lh3.googleusercontent.com/aida-public/AB6AXuChSk_HS1Q-bEcncDPyL1KqMM6hGXvNvfdCY2nFWS2rES_nBug7BKvFCAwtQnG6LGq5-2YYM7--C-CGQOjRks3ayWpre1ZZHXCUxoxEWMKXhKMV0VuSpcT7Q7r18qml8_3BpOyQXEEqVZPfpCJ44mbaDkf6Ijaoj9JGOPmKsPHpAmQp1t48QydohTsjpkHZaqJGJ-ozvW2Gu7wODb3SeP6UQqtOzFF7BYRIq2usXmubQB7U0gwdwUChVUnpqarm-JqjKgHmpP8C"/>
</div>
</div>
<p class="font-label-md text-label-md">Join over 10,000+ beverage enthusiasts.</p>
</div>
</div>
</section>
<!-- Right Side: Auth Form -->
<section class="w-full lg:w-1/2 bg-surface flex items-center justify-center p-md md:p-xl relative">
<!-- Mobile Logo -->
<div class="absolute top-lg left-lg lg:hidden">
<span class="font-title-lg text-title-lg font-bold text-primary">CozyHNA</span>
</div>
<div class="w-full max-w-md">
<!-- Toggle Tabs -->
<div class="flex bg-surface-container-low p-base rounded-xl mb-xl border border-outline-variant/20">
<button class="flex-1 py-sm font-label-md text-label-md rounded-lg transition-all duration-300 bg-surface shadow-sm text-primary" id="loginTab" onclick="toggleAuth('login')">
                        Login
                    </button>
<button class="flex-1 py-sm font-label-md text-label-md rounded-lg transition-all duration-300 text-on-surface-variant hover:text-on-surface" id="signupTab" onclick="toggleAuth('signup')">
                        Create Account
                    </button>
</div>
<div class="form-transition" id="authContent">
<header class="mb-lg">
<h2 class="font-headline-lg text-headline-lg mb-xs" id="authHeading">Welcome Back</h2>
<p class="font-body-md text-body-md text-on-surface-variant" id="authSubtext">Please enter your details to access your account.</p>
</header>
<!-- Social Login -->
<div class="mb-lg">
<a href="/auth/google" class="flex w-full items-center justify-center gap-xs border border-outline-variant rounded-xl py-sm hover:bg-surface-container transition-colors active:scale-95 duration-200">
<svg class="w-5 h-5" viewbox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"></path><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"></path><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"></path><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"></path><path d="M1 1 23 23" fill="none"></path></svg>
<span class="font-label-md text-label-md">Đăng nhập bằng Google</span>
</a>
</div>
<div class="relative flex items-center justify-center mb-lg">
<hr class="w-full border-outline-variant/30"/>
<span class="absolute bg-surface px-md font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest">or email</span>
</div>
<!-- Main Form -->
<form id="authForm" action="/login" method="POST" class="space-y-md">
@csrf
@if($errors->any())
    <div class="p-3 bg-error-container text-on-error-container rounded-lg text-label-md">
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="hidden transform transition-all duration-300 space-y-md" id="signupFields">
    <div>
        <label class="block font-label-md text-label-md text-on-surface-variant mb-xs ml-base">Full Name</label>
        <div class="relative">
            <span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-on-surface-variant" style="font-size: 20px;">person</span>
            <input name="username" class="w-full pl-11 pr-md py-sm rounded-xl border border-outline-variant bg-surface-container-lowest focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all font-body-md" placeholder="John Doe" type="text"/>
        </div>
    </div>
    <div>
        <label class="block font-label-md text-label-md text-on-surface-variant mb-xs ml-base">Phone Number</label>
        <div class="relative">
            <span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-on-surface-variant" style="font-size: 20px;">phone</span>
            <input name="phone" class="w-full pl-11 pr-md py-sm rounded-xl border border-outline-variant bg-surface-container-lowest focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all font-body-md" placeholder="0901234567" type="tel"/>
        </div>
    </div>
</div>
<div>
<label class="block font-label-md text-label-md text-on-surface-variant mb-xs ml-base">Email Address</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-on-surface-variant" style="font-size: 20px;">mail</span>
<input name="email" class="w-full pl-11 pr-md py-sm rounded-xl border border-outline-variant bg-surface-container-lowest focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all font-body-md" placeholder="name@example.com" type="email" required/>
</div>
</div>
<div>
<div class="flex justify-between items-center mb-xs ml-base mr-base">
<label class="block font-label-md text-label-md text-on-surface-variant">Mật khẩu</label>
<a class="font-label-sm text-label-sm text-primary hover:underline transition-opacity" href="#" id="forgotPass">Forgot password?</a>
</div>
<div class="relative">
<span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-on-surface-variant" style="font-size: 20px;">lock</span>
<input name="password" class="w-full pl-11 pr-11 py-sm rounded-xl border border-outline-variant bg-surface-container-lowest focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all font-body-md" id="passwordInput" placeholder="••••••••" type="password" required/>
<button class="absolute right-md top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary transition-colors" onclick="toggleMật khẩuVisibility()" type="button">
<span class="material-symbols-outlined" id="passIcon" style="font-size: 20px;">visibility</span>
</button>
</div>
</div>
<div class="hidden items-center gap-sm ml-base" id="termsCheck">
<input class="w-5 h-5 rounded border-outline-variant text-primary focus:ring-primary" type="checkbox"/>
<label class="font-label-sm text-label-sm text-on-surface-variant">I agree to the <a class="text-primary hover:underline" href="#">Terms of Service</a> and <a class="text-primary hover:underline" href="#">Privacy Policy</a>.</label>
</div>
<button type="submit" class="w-full py-md rounded-xl bg-primary-container text-on-primary-container font-headline-md text-headline-md shadow-md hover:shadow-lg active:scale-[0.98] transition-all flex items-center justify-center gap-sm mt-xl" id="submitBtn">
<span>Đăng Nhập</span>
<span class="material-symbols-outlined">arrow_forward</span>
</button>
</form>
<footer class="mt-xl text-center">
<p class="font-body-md text-body-md text-on-surface-variant" id="toggleHint">
                            Don't have an account? 
                            <button class="text-primary font-bold hover:underline" onclick="toggleAuth('signup')">Sign up for free</button>
</p>
</footer>
</div>
</div>
<!-- Footer Links -->
<div class="absolute bottom-lg left-1/2 -translate-x-1/2 flex gap-lg opacity-40 hover:opacity-100 transition-opacity whitespace-nowrap">
<a class="font-label-sm text-label-sm hover:text-primary" href="#">Help Center</a>
<a class="font-label-sm text-label-sm hover:text-primary" href="#">Legal</a>
<a class="font-label-sm text-label-sm hover:text-primary" href="#">Contact</a>
</div>
</section>
</main>
@endsection

@push('scripts')
<script>

        function toggleAuth(mode) {
            const loginTab = document.getElementById('loginTab');
            const signupTab = document.getElementById('signupTab');
            const heading = document.getElementById('authHeading');
            const subtext = document.getElementById('authSubtext');
            const signupFields = document.getElementById('signupFields');
            const termsCheck = document.getElementById('termsCheck');
            const submitBtn = document.getElementById('submitBtn');
            const toggleHint = document.getElementById('toggleHint');
            const forgotPass = document.getElementById('forgotPass');
            const authForm = document.getElementById('authForm');

            if (mode === 'signup') {
                // Style Tabs
                loginTab.classList.remove('bg-surface', 'shadow-sm', 'text-primary');
                loginTab.classList.add('text-on-surface-variant');
                signupTab.classList.add('bg-surface', 'shadow-sm', 'text-primary');
                signupTab.classList.remove('text-on-surface-variant');

                // Content change
                heading.innerText = 'Create Account';
                subtext.innerText = 'Join the CozyHNA community for exclusive rewards.';
                signupFields.classList.remove('hidden');
                signupFields.classList.add('block');
                termsCheck.classList.remove('hidden');
                termsCheck.classList.add('flex');
                forgotPass.classList.add('opacity-0', 'pointer-events-none');
                submitBtn.querySelector('span:first-child').innerText = 'Get Started';
                toggleHint.innerHTML = 'Already have an account? <button type="button" onclick="toggleAuth(\'login\')" class="text-primary font-bold hover:underline">Log in here</button>';
                
                authForm.action = '/register';
            } else {
                // Style Tabs
                signupTab.classList.remove('bg-surface', 'shadow-sm', 'text-primary');
                signupTab.classList.add('text-on-surface-variant');
                loginTab.classList.add('bg-surface', 'shadow-sm', 'text-primary');
                loginTab.classList.remove('text-on-surface-variant');

                // Content change
                heading.innerText = 'Welcome Back';
                subtext.innerText = 'Please enter your details to access your account.';
                signupFields.classList.add('hidden');
                signupFields.classList.remove('block');
                termsCheck.classList.add('hidden');
                termsCheck.classList.remove('flex');
                forgotPass.classList.remove('opacity-0', 'pointer-events-none');
                submitBtn.querySelector('span:first-child').innerText = 'Đăng Nhập';
                toggleHint.innerHTML = "Don't have an account? <button type=\"button\" onclick=\"toggleAuth('signup')\" class=\"text-primary font-bold hover:underline\">Sign up for free</button>";

                authForm.action = '/login';
            }
        }

        function toggleMật khẩuVisibility() {
            const input = document.getElementById('passwordInput');
            const icon = document.getElementById('passIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerText = 'visibility_off';
            } else {
                input.type = 'password';
                icon.innerText = 'visibility';
            }
        }

        // Loading state on submit
        document.getElementById('authForm').addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            const originalContent = btn.innerHTML;
            btn.innerHTML = '<span class="material-symbols-outlined animate-spin">progress_activity</span>';
            // Không disable btn ngay lập tức để form vẫn submit được
        });

        document.addEventListener('DOMContentLoaded', function() {
            @if(old('username') || old('phone'))
                toggleAuth('signup');
            @else
                toggleAuth('login');
            @endif
        });
    
</script>
@endpush
