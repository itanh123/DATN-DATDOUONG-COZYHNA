@extends('layouts.customer')

@section('title', 'Auth')

@section('content')
<main class="min-h-screen flex items-center justify-center relative bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&q=80');">
    <!-- Overlay -->
    <div class="absolute inset-0 bg-black/30"></div>

    <!-- Glass Card -->
    <div class="relative z-10 w-full max-w-[420px] mx-4 p-8 rounded-3xl backdrop-blur-md bg-white/20 border border-white/30 shadow-2xl">
        <div class="form-transition" id="authContent">
            <h2 class="text-3xl font-bold text-white mb-2" id="authHeading">Login</h2>
            <p class="text-white/90 text-sm mb-8" id="authSubtext">Welcome back please login to your account</p>

            <form id="authForm" action="/login" method="POST" class="space-y-4">
                @csrf
                @if($errors->any())
                    <div class="p-3 bg-red-500/50 border border-red-500/50 text-white rounded-lg text-sm mb-4">
                        <ul class="list-disc pl-5">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="hidden transform transition-all duration-300 space-y-4" id="signupFields">
                    <div>
                        <div class="relative">
                            <input name="username" class="w-full bg-transparent border border-white/50 rounded-xl px-4 py-3 text-white placeholder-white/80 focus:outline-none focus:border-white focus:ring-1 focus:ring-white transition-all" placeholder="Full Name" type="text"/>
                            <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-white/80">person</span>
                        </div>
                    </div>
                    <div>
                        <div class="relative">
                            <input name="phone" class="w-full bg-transparent border border-white/50 rounded-xl px-4 py-3 text-white placeholder-white/80 focus:outline-none focus:border-white focus:ring-1 focus:ring-white transition-all" placeholder="Phone Number" type="tel"/>
                            <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-white/80">phone</span>
                        </div>
                    </div>
                </div>
                
                <div>
                    <div class="relative">
                        <input name="email" class="w-full bg-transparent border border-white/50 rounded-xl px-4 py-3 text-white placeholder-white/80 focus:outline-none focus:border-white focus:ring-1 focus:ring-white transition-all" placeholder="Email Address" type="email" required/>
                        <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-white/80">mail</span>
                    </div>
                </div>
                
                <div>
                    <div class="relative">
                        <input name="password" class="w-full bg-transparent border border-white/50 rounded-xl px-4 py-3 text-white placeholder-white/80 focus:outline-none focus:border-white focus:ring-1 focus:ring-white transition-all" id="passwordInput" placeholder="Password" type="password" required/>
                        <button class="absolute right-4 top-1/2 -translate-y-1/2 text-white/80 hover:text-white transition-colors" onclick="togglePasswordVisibility()" type="button">
                            <span class="material-symbols-outlined" id="passIcon">visibility_off</span>
                        </button>
                    </div>
                </div>
                
                <div class="flex justify-between items-center mt-2">
                    <div class="flex items-center gap-2" id="termsCheck">
                        <input class="w-4 h-4 rounded border-white/50 bg-transparent text-[#39b54a] focus:ring-[#39b54a]" type="checkbox" name="remember"/>
                        <label class="text-sm text-white/90" id="termsLabel">Remember me</label>
                    </div>
                    <a class="text-sm text-white/90 hover:underline transition-opacity" href="/forgot-password" id="forgotPass">Forgot password?</a>
                </div>

                <div class="mt-4" id="googleLogin">
                    <a href="/auth/google" class="flex w-full items-center justify-center gap-2 border border-white/50 rounded-xl py-2 hover:bg-white/10 transition-colors text-white text-sm">
                        <svg class="w-5 h-5" viewbox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"></path><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"></path><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"></path><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"></path><path d="M1 1 23 23" fill="none"></path></svg>
                        <span>Google Login</span>
                    </a>
                </div>

                <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-[#8cc63f] to-[#39b54a] text-white font-bold text-lg shadow-lg hover:shadow-xl active:scale-[0.98] transition-all mt-6" id="submitBtn">
                    <span>Login</span>
                </button>
            </form>
            
            <div class="mt-6 text-center text-sm text-white/90" id="toggleHint">
                Don't have an account? <button type="button" onclick="toggleAuth('signup')" class="font-bold text-white hover:underline">Signup</button>
            </div>
            
            <div class="mt-6 text-center text-xs text-white/70">
                Created by <span class="font-bold text-white/90">CozyHNA</span>
            </div>
        </div>
    </div>
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
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeOtpModal()" class="px-5 py-2 rounded-xl text-[13px] font-semibold text-on-surface-variant hover:bg-surface-container-high transition-colors">Hủy</button>
                <button type="submit" id="btn-verify-otp" class="px-5 py-2 rounded-xl bg-primary text-on-primary font-title-lg active:scale-95 transition-transform">Xác nhận</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>

        function toggleAuth(mode) {
            const heading = document.getElementById('authHeading');
            const subtext = document.getElementById('authSubtext');
            const signupFields = document.getElementById('signupFields');
            const termsLabel = document.getElementById('termsLabel');
            const submitBtn = document.getElementById('submitBtn');
            const toggleHint = document.getElementById('toggleHint');
            const forgotPass = document.getElementById('forgotPass');
            const authForm = document.getElementById('authForm');
            const googleLogin = document.getElementById('googleLogin');

            if (mode === 'signup') {
                heading.innerText = 'Signup';
                subtext.innerText = 'Join us today for exclusive rewards.';
                signupFields.classList.remove('hidden');
                signupFields.classList.add('block');
                forgotPass.classList.add('hidden');
                if (termsLabel) termsLabel.innerText = 'I agree to the Terms of Service';
                if (googleLogin) googleLogin.classList.add('hidden');
                submitBtn.querySelector('span:first-child').innerText = 'Signup';
                toggleHint.innerHTML = 'Already have an account? <button type="button" onclick="toggleAuth(\'login\')" class="font-bold text-white hover:underline">Login</button>';
                
                authForm.action = '/register';
            } else {
                heading.innerText = 'Login';
                subtext.innerText = 'Welcome back please login to your account';
                signupFields.classList.add('hidden');
                signupFields.classList.remove('block');
                forgotPass.classList.remove('hidden');
                if (termsLabel) termsLabel.innerText = 'Remember me';
                if (googleLogin) googleLogin.classList.remove('hidden');
                submitBtn.querySelector('span:first-child').innerText = 'Login';
                toggleHint.innerHTML = "Don't have an account? <button type=\"button\" onclick=\"toggleAuth('signup')\" class=\"font-bold text-white hover:underline\">Signup</button>";

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

        function openOtpModal(email) {
            currentSignupEmail = email;
            document.getElementById('otp-email-display').textContent = email;
            document.getElementById('otpModal').classList.remove('hidden');
            document.getElementById('otp-alert').classList.add('hidden');
            document.getElementById('otp-input').value = '';
        }

        function closeOtpModal() {
            document.getElementById('otpModal').classList.add('hidden');
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
