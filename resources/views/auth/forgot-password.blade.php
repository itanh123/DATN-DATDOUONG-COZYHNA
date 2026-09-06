@extends('layouts.customer')

@section('title', 'Quên mật khẩu')

@section('content')
<main class="min-h-screen flex items-stretch">
    <!-- Left Side: Lifestyle Imagery (Web Only) -->
    <section class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-primary-container">
        <div class="absolute inset-0 z-10 bg-gradient-to-br from-primary/40 to-transparent"></div>
        <div class="relative z-20 w-full h-full flex flex-col justify-center p-2xl text-on-primary text-center">
            <h1 class="font-display-lg text-display-lg tracking-tight mb-md">{{ __('CozyHNA') }}</h1>
            <p class="font-headline-md text-headline-md opacity-90">Khôi phục quyền truy cập vào tài khoản của bạn.</p>
        </div>
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuB88XIx9DuZ-3tBGD0O3FcJxJ0w1nvOdGTdIcpLa30Efw6guthGbjOjHWVeG9WbBKb0ZmCCMmnRI7YhEZ9AoLWEUmfKMR81AzXwqVS6fXnZVHMzhBNf3Hkf80wBK90EsM3NH2mJBk_cdB9R4QNaUDAhts--Dn2s26Jii5Fs2e_e0CJ7zuYG4L4hobBIs-YxC51zF01fQ6Bx2WHhib6i0t_54uQhCuM--HH8who0imT3WPyg2Wc21fttm1taLoHpv4brg3yUyeMJ'); opacity: 0.5;"></div>
    </section>

    <!-- Right Side: Forgot Password Form -->
    <section class="w-full lg:w-1/2 bg-surface flex flex-col items-center justify-center p-md md:p-xl relative">
        <div class="absolute top-lg left-lg">
            <a href="/login" class="flex items-center gap-2 text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined">arrow_back</span> Trở về đăng nhập
            </a>
        </div>
        
        <div class="w-full max-w-md" id="app-container">
            <header class="mb-xl text-center">
                <h2 class="font-headline-lg text-headline-lg mb-xs">Quên mật khẩu?</h2>
                <p class="font-body-md text-body-md text-on-surface-variant" id="subtext">Nhập email của bạn để nhận mã xác nhận OTP.</p>
            </header>

            <div id="alert-container" class="hidden mb-md p-3 rounded-lg text-label-md"></div>

            <!-- Step 1: Request OTP -->
            <form id="step-1-form" class="space-y-md" onsubmit="sendOtp(event)">
                @csrf
                <div>
                    <label class="block font-label-md text-label-md text-on-surface-variant mb-xs ml-base">{{ __('Email Address') }}</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-on-surface-variant" style="font-size: 20px;">mail</span>
                        <input id="email-input" name="email" class="w-full pl-11 pr-md py-sm rounded-xl border border-outline-variant bg-surface-container-lowest focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all font-body-md" placeholder="name@example.com" type="email" required/>
                    </div>
                </div>
                <button type="submit" id="btn-send-otp" class="w-full py-md rounded-xl bg-primary text-on-primary font-headline-md text-headline-md shadow-md hover:shadow-lg active:scale-[0.98] transition-all flex items-center justify-center gap-sm mt-xl">
                    <span>Gửi mã OTP</span>
                </button>
            </form>

            <!-- Step 2: Verify OTP and Reset Password -->
            <form id="step-2-form" class="hidden space-y-md" onsubmit="resetPassword(event)">
                @csrf
                <div>
                    <label class="block font-label-md text-label-md text-on-surface-variant mb-xs ml-base">Mã OTP (6 chữ số)</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-on-surface-variant" style="font-size: 20px;">dialpad</span>
                        <input id="otp-input" name="otp" class="w-full pl-11 pr-md py-sm rounded-xl border border-outline-variant bg-surface-container-lowest focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all font-body-md tracking-widest text-center text-lg" placeholder="123456" type="text" maxlength="6" required/>
                    </div>
                </div>
                <div>
                    <label class="block font-label-md text-label-md text-on-surface-variant mb-xs ml-base">Mật khẩu mới</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-on-surface-variant" style="font-size: 20px;">lock</span>
                        <input id="password-input" name="password" class="w-full pl-11 pr-md py-sm rounded-xl border border-outline-variant bg-surface-container-lowest focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all font-body-md" placeholder="••••••••" type="password" required minlength="6"/>
                    </div>
                </div>
                <div>
                    <label class="block font-label-md text-label-md text-on-surface-variant mb-xs ml-base">Nhập lại mật khẩu mới</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-on-surface-variant" style="font-size: 20px;">lock</span>
                        <input id="password-confirm-input" name="password_confirmation" class="w-full pl-11 pr-md py-sm rounded-xl border border-outline-variant bg-surface-container-lowest focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all font-body-md" placeholder="••••••••" type="password" required minlength="6"/>
                    </div>
                </div>
                <button type="submit" id="btn-reset" class="w-full py-md rounded-xl bg-primary text-on-primary font-headline-md text-headline-md shadow-md hover:shadow-lg active:scale-[0.98] transition-all flex items-center justify-center gap-sm mt-xl">
                    <span>Đặt lại mật khẩu</span>
                </button>
                <div class="text-center mt-md">
                    <button type="button" onclick="sendOtp(null, true)" class="text-label-sm text-primary hover:underline">Gửi lại mã OTP</button>
                </div>
            </form>

        </div>
    </section>
</main>
@endsection

@push('scripts')
<script>
    function showAlert(msg, type = 'error') {
        const container = document.getElementById('alert-container');
        container.textContent = msg;
        container.className = `mb-md p-3 rounded-lg text-label-md block ${type === 'error' ? 'bg-error-container text-on-error-container' : 'bg-primary-container text-on-primary-container'}`;
    }

    async function sendOtp(e, resend = false) {
        if(e) e.preventDefault();
        const email = document.getElementById('email-input').value;
        if (!email) {
            showAlert('Vui lòng nhập email.');
            return;
        }

        const btn = document.getElementById('btn-send-otp');
        if(!resend) {
            btn.innerHTML = '<span class="material-symbols-outlined animate-spin">refresh</span> Đang xử lý...';
            btn.disabled = true;
        }
        
        try {
            const res = await fetch('/forgot-password/send-otp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({ email })
            });
            const data = await res.json();
            
            if (!res.ok) {
                showAlert(data.error || 'Có lỗi xảy ra.');
                if(!resend) {
                    btn.innerHTML = '<span>Gửi mã OTP</span>';
                    btn.disabled = false;
                }
            } else {
                showAlert(data.message, 'success');
                if(!resend) {
                    document.getElementById('step-1-form').classList.add('hidden');
                    document.getElementById('step-2-form').classList.remove('hidden');
                    document.getElementById('subtext').textContent = `Mã OTP đã được gửi đến ${email}. Mã có hiệu lực 10 phút.`;
                }
            }
        } catch (err) {
            showAlert('Không thể kết nối đến máy chủ.');
            if(!resend) {
                btn.innerHTML = '<span>Gửi mã OTP</span>';
                btn.disabled = false;
            }
        }
    }

    async function resetPassword(e) {
        e.preventDefault();
        const email = document.getElementById('email-input').value;
        const otp = document.getElementById('otp-input').value;
        const password = document.getElementById('password-input').value;
        const password_confirmation = document.getElementById('password-confirm-input').value;

        if (password !== password_confirmation) {
            showAlert('Mật khẩu nhập lại không khớp.');
            return;
        }

        const btn = document.getElementById('btn-reset');
        btn.innerHTML = '<span class="material-symbols-outlined animate-spin">refresh</span> Đang xử lý...';
        btn.disabled = true;

        try {
            const res = await fetch('/forgot-password/reset', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({ email, otp, password, password_confirmation })
            });
            const data = await res.json();

            if (!res.ok) {
                showAlert(data.error || data.message || 'Có lỗi xảy ra.');
                btn.innerHTML = '<span>Đặt lại mật khẩu</span>';
                btn.disabled = false;
            } else {
                showAlert('Đổi mật khẩu thành công! Chuyển hướng tới đăng nhập...', 'success');
                setTimeout(() => window.location.href = '/login', 2000);
            }
        } catch (err) {
            showAlert('Không thể kết nối đến máy chủ.');
            btn.innerHTML = '<span>Đặt lại mật khẩu</span>';
            btn.disabled = false;
        }
    }
</script>
@endpush
