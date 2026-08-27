@extends('layouts.customer')

@section('title', 'Auth')

@section('content')
<style>
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-6px); }
        75% { transform: translateX(6px); }
    }
    .animate-shake {
        animation: shake 0.3s ease-in-out;
    }
    .captcha-item.selected .select-overlay {
        display: flex !important;
    }
</style>
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
<a class="font-label-sm text-label-sm text-primary hover:underline transition-opacity" href="/forgot-password" id="forgotPass">Forgot password?</a>
</div>
<div class="relative">
<span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-on-surface-variant" style="font-size: 20px;">lock</span>
<input name="password" class="w-full pl-11 pr-11 py-sm rounded-xl border border-outline-variant bg-surface-container-lowest focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all font-body-md" id="passwordInput" placeholder="••••••••" type="password" required/>
<button class="absolute right-md top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary transition-colors" onclick="togglePasswordVisibility()" type="button">
<span class="material-symbols-outlined" id="passIcon" style="font-size: 20px;">visibility</span>
</button>
    </div>
</div>
<!-- Custom Captcha Widget -->
<div class="mt-xs mb-sm border border-outline-variant bg-surface-container-low rounded-xl p-md flex items-center justify-between shadow-sm select-none cursor-pointer hover:bg-surface-container-high transition-colors" id="captchaWidget" onclick="triggerCaptcha()">
    <div class="flex items-center gap-md">
        <div class="relative w-7 h-7 flex items-center justify-center border-2 border-outline-variant rounded bg-surface-container-lowest transition-all" id="captchaBox">
            <span class="material-symbols-outlined text-[20px] text-green-600 font-bold hidden" id="captchaTick">check</span>
            <div class="w-5 h-5 border-[3px] border-primary border-t-transparent rounded-full animate-spin hidden" id="captchaLoading"></div>
        </div>
        <span class="text-sm font-medium text-on-surface">Tôi không phải là người máy</span>
    </div>
    <div class="flex flex-col items-center opacity-70">
        <svg class="w-6 h-6 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="3" width="18" height="18" rx="2" />
            <path d="M12 8v8M8 12h8" />
        </svg>
        <span class="text-[9px] text-on-surface-variant font-bold mt-0.5">reCAPTCHA</span>
    </div>
</div>
<input type="hidden" name="captcha_verified" id="captchaVerifiedInput" value="0" />

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

<!-- OTP Modal for Registration -->
<div id="otpModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center">
    <div class="bg-surface rounded-2xl w-full max-w-sm overflow-hidden shadow-2xl p-6 relative">
        <div class="flex justify-between items-center mb-5">
            <h3 class="text-[18px] font-bold text-on-surface">Xác nhận đăng ký</h3>
            <button onclick="closeOtpModal()" type="button" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-surface-container-high text-on-surface-variant transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <p class="text-[13px] text-on-surface-variant mb-4">Vui lòng nhập mã OTP 6 số vừa được gửi đến email <strong id="otp-email-display"></strong>.</p>
        <div id="otp-alert" class="hidden mb-4 p-3 rounded-lg text-label-md bg-error-container text-on-error-container"></div>
        <form id="verify-otp-form" onsubmit="submitVerifyOtp(event)">
            @csrf
            <div class="mb-4">
                <label class="block text-[11px] font-semibold text-on-surface-variant uppercase tracking-wide mb-1.5">Mã OTP</label>
                <input name="otp" type="text" id="otp-input" required maxlength="6"
                       class="w-full bg-surface-container-lowest border border-outline-variant rounded-xl p-3 text-center text-body-lg tracking-widest font-bold focus:ring-primary focus:border-primary outline-none" placeholder="123456"/>
            </div>
            <div class="mb-5 text-center text-[13px]">
                <span class="text-on-surface-variant">Chưa nhận được mã?</span>
                <button type="button" id="btn-resend-otp" onclick="resendOtp()" disabled class="text-primary font-bold ml-1 disabled:opacity-50 disabled:cursor-not-allowed">
                    Gửi lại mã (60s)
                </button>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeOtpModal()" class="px-5 py-2 rounded-xl text-[13px] font-semibold text-on-surface-variant hover:bg-surface-container-high transition-colors">Hủy</button>
                <button type="submit" id="btn-verify-otp" class="px-5 py-2 rounded-xl bg-primary text-on-primary font-title-lg active:scale-95 transition-transform">Xác nhận</button>
            </div>
        </form>
    </div>
</div>

<!-- Captcha Image Challenge Modal -->
<div id="captchaModal" class="hidden fixed inset-0 bg-black/65 backdrop-blur-sm z-[100] flex items-center justify-center p-4">
    <div class="bg-surface rounded-2xl w-full max-w-sm overflow-hidden shadow-2xl border border-outline-variant flex flex-col">
        <!-- Header -->
        <div class="bg-primary text-on-primary p-4 pb-5">
            <p class="text-[11px] uppercase font-bold tracking-wider opacity-90 mb-1">Xác minh bảo mật</p>
            <h3 class="text-[17px] font-bold">Chọn tất cả hình ảnh có chứa:</h3>
            <h2 class="text-3xl font-extrabold mt-1 text-yellow-300 drop-shadow-md" id="captchaTargetTitle">Ly Trà Sữa</h2>
        </div>
        
        <!-- Grid Content -->
        <div class="p-4 flex-1 bg-surface-container-lowest">
            <div id="captchaError" class="hidden mb-3 p-2 bg-error-container text-on-error-container text-xs rounded-lg text-center font-semibold">
                Vui lòng thử lại. Lựa chọn của bạn chưa chính xác.
            </div>
            <div class="grid grid-cols-3 gap-2" id="captchaGrid">
                <!-- 9 Images will be dynamically populated here -->
            </div>
        </div>
        
        <!-- Footer -->
        <div class="border-t border-outline-variant p-3 bg-surface-container-low flex justify-between items-center">
            <div class="flex gap-2">
                <button type="button" onclick="refreshCaptcha()" class="w-10 h-10 flex items-center justify-center rounded-xl hover:bg-surface-container-high text-on-surface-variant transition-colors" title="Đổi câu hỏi khác">
                    <span class="material-symbols-outlined text-[20px]">refresh</span>
                </button>
                <button type="button" onclick="helpCaptcha()" class="w-10 h-10 flex items-center justify-center rounded-xl hover:bg-surface-container-high text-on-surface-variant transition-colors" title="Trợ giúp">
                    <span class="material-symbols-outlined text-[20px]">help_outline</span>
                </button>
            </div>
            <div class="flex gap-2">
                <button type="button" onclick="closeCaptchaModal()" class="px-4 py-2 rounded-xl text-sm font-bold text-on-surface-variant hover:bg-surface-container-high transition-colors">HỦY</button>
                <button type="button" onclick="verifyCaptchaSelection()" class="px-5 py-2 rounded-xl bg-primary text-on-primary text-sm font-bold shadow-md hover:shadow-lg active:scale-95 transition-all">XÁC MINH</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
        const CAPTCHA_CHALLENGES = [
            {
                id: 'bubble_tea',
                title: 'Ly Trà Sữa',
                correct: [
                    '{{ asset("images/captcha/ts1.jpg") }}',
                    '{{ asset("images/captcha/ts2.jpg") }}',
                    '{{ asset("images/captcha/ts3.jpg") }}'
                ],
                incorrect: [
                    '{{ asset("images/captcha/cp1.jpg") }}',
                    '{{ asset("images/captcha/cp2.jpg") }}',
                    '{{ asset("images/captcha/cp3.jpg") }}',
                    '{{ asset("images/captcha/bn1.jpg") }}',
                    '{{ asset("images/captcha/bn2.jpg") }}',
                    '{{ asset("images/captcha/bn3.jpg") }}'
                ]
            },
            {
                id: 'coffee',
                title: 'Tách Cà Phê',
                correct: [
                    '{{ asset("images/captcha/cp1.jpg") }}',
                    '{{ asset("images/captcha/cp2.jpg") }}',
                    '{{ asset("images/captcha/cp3.jpg") }}'
                ],
                incorrect: [
                    '{{ asset("images/captcha/ts1.jpg") }}',
                    '{{ asset("images/captcha/ts2.jpg") }}',
                    '{{ asset("images/captcha/ts3.jpg") }}',
                    '{{ asset("images/captcha/bn1.jpg") }}',
                    '{{ asset("images/captcha/bn2.jpg") }}',
                    '{{ asset("images/captcha/bn3.jpg") }}'
                ]
            },
            {
                id: 'cake',
                title: 'Chiếc Bánh Ngọt',
                correct: [
                    '{{ asset("images/captcha/bn1.jpg") }}',
                    '{{ asset("images/captcha/bn2.jpg") }}',
                    '{{ asset("images/captcha/bn3.jpg") }}'
                ],
                incorrect: [
                    '{{ asset("images/captcha/ts1.jpg") }}',
                    '{{ asset("images/captcha/ts2.jpg") }}',
                    '{{ asset("images/captcha/ts3.jpg") }}',
                    '{{ asset("images/captcha/cp1.jpg") }}',
                    '{{ asset("images/captcha/cp2.jpg") }}',
                    '{{ asset("images/captcha/cp3.jpg") }}'
                ]
            }
        ];

        let isCaptchaVerified = false;
        let currentCaptchaChallenge = null;
        let currentCaptchaItems = [];
        let selectedImageIndices = [];

        function triggerCaptcha() {
            if (isCaptchaVerified) return;
            const loading = document.getElementById('captchaLoading');
            const widget = document.getElementById('captchaWidget');
            loading.classList.remove('hidden');
            widget.classList.remove('border-outline-variant');
            widget.classList.add('border-primary');
            
            setTimeout(() => {
                openCaptchaModal();
            }, 500);
        }

        function openCaptchaModal() {
            const loading = document.getElementById('captchaLoading');
            loading.classList.add('hidden');
            
            const randIndex = Math.floor(Math.random() * CAPTCHA_CHALLENGES.length);
            currentCaptchaChallenge = CAPTCHA_CHALLENGES[randIndex];
            
            document.getElementById('captchaTargetTitle').textContent = currentCaptchaChallenge.title;
            
            let items = [];
            currentCaptchaChallenge.correct.forEach(url => items.push({url, isCorrect: true}));
            currentCaptchaChallenge.incorrect.forEach(url => items.push({url, isCorrect: false}));
            
            // Shuffle
            for (let i = items.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [items[i], items[j]] = [items[j], items[i]];
            }
            
            currentCaptchaItems = items;
            selectedImageIndices = [];
            
            const grid = document.getElementById('captchaGrid');
            grid.innerHTML = '';
            items.forEach((item, index) => {
                const div = document.createElement('div');
                div.className = 'captcha-item relative aspect-square cursor-pointer overflow-hidden rounded-xl border-2 border-transparent hover:scale-[1.02] transition-transform select-none bg-surface-container-high';
                div.onclick = () => toggleSelectImage(index, div);
                div.innerHTML = `
                    <img src="${item.url}" class="w-full h-full object-cover pointer-events-none" />
                    <div class="absolute inset-0 bg-primary/30 border-[4px] border-primary hidden justify-center items-center select-overlay pointer-events-none">
                        <span class="material-symbols-outlined text-white bg-primary rounded-full p-0.5 text-[24px]">check</span>
                    </div>
                `;
                grid.appendChild(div);
            });
            
            document.getElementById('captchaError').classList.add('hidden');
            document.getElementById('captchaModal').classList.remove('hidden');
        }

        function toggleSelectImage(index, el) {
            if (selectedImageIndices.includes(index)) {
                selectedImageIndices = selectedImageIndices.filter(i => i !== index);
                el.classList.remove('selected');
            } else {
                selectedImageIndices.push(index);
                el.classList.add('selected');
            }
        }

        function verifyCaptchaSelection() {
            let isCorrectSelection = true;
            let correctCount = 0;
            
            currentCaptchaItems.forEach((item, index) => {
                const isSelected = selectedImageIndices.includes(index);
                if (item.isCorrect) correctCount++;
                if (item.isCorrect && !isSelected) isCorrectSelection = false;
                if (!item.isCorrect && isSelected) isCorrectSelection = false;
            });
            
            if (isCorrectSelection && selectedImageIndices.length === correctCount) {
                // Success
                isCaptchaVerified = true;
                document.getElementById('captchaVerifiedInput').value = '1';
                
                const widget = document.getElementById('captchaWidget');
                const box = document.getElementById('captchaBox');
                const tick = document.getElementById('captchaTick');
                
                widget.classList.remove('cursor-pointer', 'hover:bg-surface-container-high');
                widget.onclick = null;
                
                box.classList.remove('bg-surface-container-lowest', 'border-outline-variant');
                box.classList.add('border-green-600', 'bg-green-50');
                tick.classList.remove('hidden');
                
                const errorDiv = document.getElementById('register-errors');
                if (errorDiv) errorDiv.remove();
                
                closeCaptchaModal();
            } else {
                // Fail
                document.getElementById('captchaError').classList.remove('hidden');
                setTimeout(() => {
                    refreshCaptcha();
                }, 1200);
            }
        }

        function refreshCaptcha() {
            document.getElementById('captchaError').classList.add('hidden');
            openCaptchaModal();
        }

        function closeCaptchaModal() {
            document.getElementById('captchaModal').classList.add('hidden');
            document.getElementById('captchaLoading').classList.add('hidden');
            document.getElementById('captchaWidget').classList.remove('border-primary');
            document.getElementById('captchaWidget').classList.add('border-outline-variant');
        }

        function helpCaptcha() {
            alert('Vui lòng chọn tất cả các hình ảnh chứa đối tượng được yêu cầu ở tiêu đề. Khi hoàn tất hãy nhấn Xác Minh.');
        }

        function toggleAuth(mode) {
            const loginTab = document.getElementById('loginTab');
            const signupTab = document.getElementById('signupTab');
            const heading = document.getElementById('authHeading');
            const subtext = document.getElementById('authSubtext');
            const signupFields = document.getElementById('signupFields');
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
                forgotPass.classList.remove('opacity-0', 'pointer-events-none');
                submitBtn.querySelector('span:first-child').innerText = 'Đăng Nhập';
                toggleHint.innerHTML = "Don't have an account? <button type=\"button\" onclick=\"toggleAuth('signup')\" class=\"text-primary font-bold hover:underline\">Sign up for free</button>";

                authForm.action = '/login';
            }
        }

        function togglePasswordVisibility() {
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

        // Loading state and AJAX for signup
        document.getElementById('authForm').addEventListener('submit', async function(e) {
            const form = e.target;
            
            // Check captcha verification first!
            const captchaVerified = document.getElementById('captchaVerifiedInput').value;
            if (captchaVerified !== '1') {
                e.preventDefault();
                const widget = document.getElementById('captchaWidget');
                widget.classList.add('animate-shake', 'border-error-custom');
                setTimeout(() => {
                    widget.classList.remove('animate-shake', 'border-error-custom');
                }, 400);
                
                let registerErrors = document.getElementById('register-errors');
                if (!registerErrors) {
                    registerErrors = document.createElement('div');
                    registerErrors.id = 'register-errors';
                    registerErrors.className = 'p-3 bg-error-container text-on-error-container rounded-lg text-label-md mb-4';
                    form.insertBefore(registerErrors, form.firstChild);
                }
                registerErrors.innerHTML = 'Vui lòng xác minh "Tôi không phải là người máy".';
                return;
            }
            
            if (form.action.endsWith('/register')) {
                e.preventDefault();
                const btn = document.getElementById('submitBtn');
                const originalContent = btn.innerHTML;
                btn.innerHTML = '<span class="material-symbols-outlined animate-spin">refresh</span> Đang xử lý...';
                btn.disabled = true;

                const formData = new FormData(form);
                
                // Clear previous errors
                const existingErrors = document.getElementById('register-errors');
                if (existingErrors) existingErrors.remove();

                try {
                    const res = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        },
                        body: formData
                    });
                    const data = await res.json();
                    
                    btn.disabled = false;
                    btn.innerHTML = originalContent;

                    if (!res.ok) {
                        let errorMsg = data.error || data.message || 'Có lỗi xảy ra.';
                        if (data.errors) { // validation errors
                            errorMsg = Object.values(data.errors).flat().join('<br>');
                        }
                        
                        const errorDiv = document.createElement('div');
                        errorDiv.id = 'register-errors';
                        errorDiv.className = 'p-3 bg-error-container text-on-error-container rounded-lg text-label-md mb-4';
                        errorDiv.innerHTML = errorMsg;
                        form.insertBefore(errorDiv, form.firstChild);
                    } else {
                        if(data.require_otp) {
                            openOtpModal(data.email);
                        } else {
                            window.location.href = '/';
                        }
                    }
                } catch (err) {
                    btn.disabled = false;
                    btn.innerHTML = originalContent;
                    alert('Lỗi kết nối máy chủ.');
                }
            } else {
                // Normal login submit
                const btn = document.getElementById('submitBtn');
                btn.innerHTML = '<span class="material-symbols-outlined animate-spin">progress_activity</span>';
            }
        });

        let currentSignupEmail = '';
        let otpCountdownInterval = null;

        function startOtpCountdown() {
            let timeLeft = 60;
            const resendBtn = document.getElementById('btn-resend-otp');
            resendBtn.disabled = true;
            resendBtn.innerHTML = `Gửi lại mã (${timeLeft}s)`;
            
            if (otpCountdownInterval) clearInterval(otpCountdownInterval);
            
            otpCountdownInterval = setInterval(() => {
                timeLeft--;
                if (timeLeft <= 0) {
                    clearInterval(otpCountdownInterval);
                    resendBtn.disabled = false;
                    resendBtn.innerHTML = 'Gửi lại mã';
                } else {
                    resendBtn.innerHTML = `Gửi lại mã (${timeLeft}s)`;
                }
            }, 1000);
        }

        async function resendOtp() {
            const resendBtn = document.getElementById('btn-resend-otp');
            resendBtn.disabled = true;
            resendBtn.innerHTML = '<span class="material-symbols-outlined animate-spin text-[16px] align-middle">refresh</span> Đang gửi...';
            
            const form = document.getElementById('authForm');
            const formData = new FormData(form);
            
            try {
                const res = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: formData
                });
                const data = await res.json();
                if (res.ok) {
                    const alertBox = document.getElementById('otp-alert');
                    alertBox.textContent = 'Mã OTP mới đã được gửi!';
                    alertBox.className = 'mb-4 p-3 rounded-lg text-label-md bg-primary-container text-on-primary-container block';
                    startOtpCountdown();
                } else {
                    resendBtn.disabled = false;
                    resendBtn.innerHTML = 'Gửi lại mã';
                    const alertBox = document.getElementById('otp-alert');
                    alertBox.textContent = data.error || data.message || 'Lỗi gửi lại mã.';
                    alertBox.className = 'mb-4 p-3 rounded-lg text-label-md bg-error-container text-on-error-container block';
                }
            } catch (err) {
                resendBtn.disabled = false;
                resendBtn.innerHTML = 'Gửi lại mã';
            }
        }

        function openOtpModal(email) {
            currentSignupEmail = email;
            document.getElementById('otp-email-display').textContent = email;
            document.getElementById('otpModal').classList.remove('hidden');
            document.getElementById('otp-alert').classList.add('hidden');
            document.getElementById('otp-input').value = '';
            startOtpCountdown();
        }

        function closeOtpModal() {
            document.getElementById('otpModal').classList.add('hidden');
            if (otpCountdownInterval) clearInterval(otpCountdownInterval);
        }

        async function submitVerifyOtp(e) {
            e.preventDefault();
            const btn = document.getElementById('btn-verify-otp');
            const alertBox = document.getElementById('otp-alert');
            const otp = document.getElementById('otp-input').value;
            
            btn.disabled = true;
            btn.innerHTML = 'Đang xử lý...';
            alertBox.classList.add('hidden');

            try {
                const res = await fetch('{{ route("register.verify") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({ email: currentSignupEmail, otp: otp })
                });
                const data = await res.json();
                
                if (!res.ok) {
                    btn.disabled = false;
                    btn.innerHTML = 'Xác nhận';
                    alertBox.textContent = data.error || data.message || 'Có lỗi xảy ra.';
                    alertBox.classList.remove('hidden');
                } else {
                    alertBox.textContent = 'Đăng ký thành công! Đang chuyển hướng...';
                    alertBox.className = 'mb-4 p-3 rounded-lg text-label-md bg-primary-container text-on-primary-container block';
                    setTimeout(() => {
                        window.location.href = '/';
                    }, 1000);
                }
            } catch (err) {
                btn.disabled = false;
                btn.innerHTML = 'Xác nhận';
                alertBox.textContent = 'Lỗi kết nối máy chủ.';
                alertBox.classList.remove('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            @if(old('username') || old('phone'))
                toggleAuth('signup');
            @else
                toggleAuth('login');
            @endif
        });
    
</script>
@endpush
