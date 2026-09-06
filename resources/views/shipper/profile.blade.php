@extends('layouts.admin')

@section('title', 'Profile')

@section('content')
<main class="ml-[280px] pt-16 min-h-screen px-gutter pb-xl">
@if(session('success'))
    <div class="bg-green-100 text-green-800 p-4 rounded-lg mb-4 mt-4">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="bg-red-100 text-red-800 p-4 rounded-lg mb-4 mt-4">
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<!-- Profile Header Giâytion -->
<section class="mt-xl mb-lg">
<div class="relative w-full h-48 rounded-2xl overflow-hidden mb-[-4rem]">
<div class="absolute inset-0 organic-gradient opacity-90"></div>

</div>
<div class="relative px-lg flex flex-col md:flex-row items-end gap-md">
<div class="w-32 h-32 rounded-3xl border-4 border-background bg-white overflow-hidden shadow-lg">
<img class="w-full h-full object-cover" data-alt="Marcus Chen close-up portrait, focusing on his professional and approachable demeanor. The photo is well-lit with soft key light and a clean architectural background. He wears a subtle smile, reflecting a healthy and positive lifestyle, aligned with the brand's premium organic values." src="https://lh3.googleusercontent.com/aida-public/AB6AXuB_-crgW0ZkRc5paY1wQJN4w9wYu6y9qUHPGsBr2_9eAYFCrxunbVodpsrpoxeuLF9dWNcDdZhk53mym1vsQ8E6zMoT2lwdIugn9-snFHeM-2uCTTSgUGEhAJc6BNPkOH-uAyCtqCEQzfNMqwammZtHxSSRcxvrQwbHPMH3QaRwkTW__ylM9FYBAv1P3S8pGXl0_J-oZL9Ns__tt3XBFS0eiZemH_yJ3bPeEuSrEJJh3KNdSfw1p9LuKgeRMWY7_1OPp734qy1M"/>
</div>
<div class="flex-1 pb-2">
<div class="flex items-center gap-xs">
<h1 class="font-headline-lg text-headline-lg">{{ __('Marcus Chen') }}</h1>
<span class="bg-secondary-container text-on-secondary-container px-3 py-1 rounded-full text-label-md flex items-center gap-1">
<span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                            Hoạt động
                        </span>
</div>
<p class="text-on-surface-variant font-body-lg">{{ __('Senior Logistics Specialist • ID: COZY-9920') }}</p>
</div>
<div class="pb-2">
<button onclick="document.getElementById('editShipperModal').classList.remove('hidden')" class="bg-surface-container-highest text-on-surface border border-outline-variant px-md py-2 rounded-xl font-title-lg flex items-center gap-2 hover:bg-surface-container-high transition-colors">
<span class="material-symbols-outlined text-[20px]" data-icon="edit">edit</span>
                        Sửa Profile
                    </button>
</div>
</div>
</section>
<!-- Bento Grid Layout for Stats & Cards -->
<div class="grid grid-cols-1 md:grid-cols-12 gap-lg mt-12">
<!-- Performance Stats (Bento Span 8) -->
<div class="md:col-span-8 grid grid-cols-1 sm:grid-cols-3 gap-md">
<div class="glass-card p-lg rounded-2xl flex flex-col items-center text-center">
<span class="material-symbols-outlined text-primary bg-primary-container p-3 rounded-full mb-md" data-icon="check_circle">check_circle</span>
<p class="text-on-surface-variant text-label-md uppercase tracking-wider">{{ __('Lifetime Deliveries') }}</p>
<h3 class="text-headline-lg font-headline-lg mt-xs">1,284</h3>
<div class="text-primary text-label-sm mt-2 flex items-center gap-1">
<span class="material-symbols-outlined text-[14px]" data-icon="trending_up">trending_up</span> 12% vs last month
                    </div>
</div>
<div class="glass-card p-lg rounded-2xl flex flex-col items-center text-center">
<span class="material-symbols-outlined text-tertiary bg-tertiary-container p-3 rounded-full mb-md" data-icon="star">star</span>
<p class="text-on-surface-variant text-label-md uppercase tracking-wider">{{ __('Average Rating') }}</p>
<h3 class="text-headline-lg font-headline-lg mt-xs">4.95</h3>
<p class="text-on-surface-variant text-label-sm mt-2">{{ __('Based on 840 reviews') }}</p>
</div>
<div class="glass-card p-lg rounded-2xl flex flex-col items-center text-center">
<span class="material-symbols-outlined text-secondary bg-secondary-container p-3 rounded-full mb-md" data-icon="schedule">schedule</span>
<p class="text-on-surface-variant text-label-md uppercase tracking-wider">{{ __('On-Time Rate') }}</p>
<h3 class="text-headline-lg font-headline-lg mt-xs">98%</h3>
<div class="text-secondary text-label-sm mt-2 flex items-center gap-1">
<span class="material-symbols-outlined text-[14px]" data-icon="bolt">bolt</span>{{ __('Elite Performer') }}</div>
</div>
</div>
<!-- Digital Wallet (Bento Span 4) -->
<div class="md:col-span-4 glass-card rounded-2xl flex flex-col overflow-hidden">
<div class="organic-gradient p-lg text-on-primary">
<div class="flex justify-between items-start mb-md">
<p class="text-label-md opacity-80 uppercase tracking-widest">{{ __('Available Balance') }}</p>
<span class="material-symbols-outlined" data-icon="account_balance_wallet">account_balance_wallet</span>
</div>
<h2 class="text-display-lg font-display-lg">$2,450.80</h2>
<button class="mt-md w-full bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white py-2 rounded-lg font-title-lg transition-colors">{{ __('Payout Now') }}</button>
</div>
<div class="p-lg">
<h4 class="font-title-lg text-title-lg mb-md">{{ __('Recent Transactions') }}</h4>
<div class="space-y-md">
<div class="flex justify-between items-center">
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded-full bg-surface-container-high flex items-center justify-center">
<span class="material-symbols-outlined text-[18px] text-primary" data-icon="add">add</span>
</div>
<div>
<p class="text-body-md font-bold">{{ __('Delivery Earnings') }}</p>
<p class="text-label-sm text-on-surface-variant">{{ __('Oct 24, 2023') }}</p>
</div>
</div>
<span class="font-title-lg text-primary">+$142.50</span>
</div>
<div class="flex justify-between items-center">
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded-full bg-surface-container-high flex items-center justify-center">
<span class="material-symbols-outlined text-[18px] text-secondary" data-icon="card_giftcard">card_giftcard</span>
</div>
<div>
<p class="text-body-md font-bold">{{ __('Tip - Route 82') }}</p>
<p class="text-label-sm text-on-surface-variant">{{ __('Oct 23, 2023') }}</p>
</div>
</div>
<span class="font-title-lg text-primary">+$25.00</span>
</div>
</div>
</div>
</div>
<!-- Vehicle Information (Bento Span 6) -->
<div class="md:col-span-6 glass-card rounded-2xl p-lg">
<div class="flex justify-between items-start mb-lg">
<div>
<h4 class="font-title-lg text-title-lg">{{ __('Assigned Vehicle') }}</h4>
<p class="text-on-surface-variant text-body-md">{{ $shipper->vehicle_type ?? __('Chưa cập nhật loại xe') }}</p>
</div>
<span class="bg-primary-container text-on-primary-container px-3 py-1 rounded-full text-label-sm">{{ __('In Good Standing') }}</span>
</div>
<div class="relative w-full h-48 rounded-xl overflow-hidden mb-lg">
<img class="w-full h-full object-cover" data-alt="A modern, sleek electric delivery van in a clean white and forest green livery parked in a sunlit urban charging station. The van features the 'CozyHNA' logo on the side. The scene is bright and professional, emphasizing eco-friendly logistics and modern technology." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDkrInxMgFfQ7ostrznlj2Duzh_aNhddnMzKrVBeXgjcGDmAltUuJVZ58xcn3vbzdeKN2jqLS_WwXWapWgB9SVbesp_XDVzscnh9nac1oPwdw2pZ3AVISu0FD-55v6UB7_Y772BtZEl-luPU2VsxF04YJe0o6SOnz32omnvLPBLqNRwTo5_V991o-ADmhDs5OjqQyFyRxEvxvxUWiySh4igrp4QSR7xqFCP9SJBnegS9XZvn9ajXCg8R4W3RjBZMydGL9mh80uE"/>
<div class="absolute bottom-4 left-4 bg-black/50 backdrop-blur-md px-4 py-2 rounded-lg text-white">
<p class="text-label-sm opacity-80">{{ __('Plate Number') }}</p>
<p class="font-bold tracking-widest">{{ $shipper->license_plate ?? __('N/A') }}</p>
</div>
</div>
<div class="grid grid-cols-2 gap-lg">
<div>
<p class="text-label-md text-on-surface-variant">{{ __('Last Maintenance') }}</p>
<p class="font-body-lg font-bold">{{ __('Sep 12, 2023') }}</p>
</div>
<div>
<p class="text-label-md text-on-surface-variant">{{ __('Battery Health') }}</p>
<div class="flex items-center gap-2">
<div class="flex-1 h-2 bg-surface-container-highest rounded-full overflow-hidden">
<div class="h-full bg-primary w-[94%]"></div>
</div>
<span class="text-label-sm font-bold">94%</span>
</div>
</div>
</div>
</div>
<!-- Documents & Compliance (Bento Span 6) -->
<div class="md:col-span-6 glass-card rounded-2xl p-lg flex flex-col">
<h4 class="font-title-lg text-title-lg mb-lg">Documents &amp; Compliance</h4>
<div class="flex-1 space-y-md">
<div class="p-md bg-surface-container-low rounded-xl flex items-center justify-between">
<div class="flex items-center gap-4">
<span class="material-symbols-outlined text-primary" data-icon="badge">badge</span>
<div>
<p class="font-body-md font-bold">Driver's License (Class B)</p>
<p class="text-label-sm text-on-surface-variant">{{ __('Expires: Dec 2025') }}</p>
</div>
</div>
<div class="flex items-center gap-1 text-primary">
<span class="material-symbols-outlined text-[18px]" data-icon="verified">verified</span>
<span class="text-label-sm">{{ __('Verified') }}</span>
</div>
</div>
<div class="p-md bg-surface-container-low rounded-xl flex items-center justify-between">
<div class="flex items-center gap-4">
<span class="material-symbols-outlined text-primary" data-icon="description">Mô tả</span>
<div>
<p class="font-body-md font-bold">{{ __('Vehicle Insurance') }}</p>
<p class="text-label-sm text-on-surface-variant">{{ __('Expires: Jun 2024') }}</p>
</div>
</div>
<div class="flex items-center gap-1 text-primary">
<span class="material-symbols-outlined text-[18px]" data-icon="verified">verified</span>
<span class="text-label-sm">{{ __('Verified') }}</span>
</div>
</div>
<div class="p-md bg-surface-container-low rounded-xl flex items-center justify-between">
<div class="flex items-center gap-4">
<span class="material-symbols-outlined text-primary" data-icon="medical_services">medical_services</span>
<div>
<p class="font-body-md font-bold">{{ __('Health Certification') }}</p>
<p class="text-label-sm text-on-surface-variant">Current &amp; Compliant</p>
</div>
</div>
<div class="flex items-center gap-1 text-primary">
<span class="material-symbols-outlined text-[18px]" data-icon="verified">verified</span>
<span class="text-label-sm">{{ __('Verified') }}</span>
</div>
</div>
</div>
<button class="mt-lg text-primary font-title-lg flex items-center gap-2 self-start hover:underline">{{ __('Upload New Document') }}<span class="material-symbols-outlined text-[18px]" data-icon="upload">upload</span>
</button>
</div>
<!-- Settings & Preferences (Full Width Span) -->
<div class="md:col-span-12 glass-card rounded-2xl p-lg">
<h4 class="font-title-lg text-title-lg mb-lg">{{ __('Personal Settings') }}</h4>
<div class="grid grid-cols-1 md:grid-cols-2 gap-2xl">
<div>
<h5 class="text-label-md font-bold uppercase tracking-wider text-on-surface-variant mb-md">{{ __('Notification Preferences') }}</h5>
<div class="space-y-lg">
<div class="flex items-center justify-between">
<div>
<p class="font-body-md font-bold">{{ __('Push Notifications') }}</p>
<p class="text-label-sm text-on-surface-variant">{{ __('Receive new delivery alerts') }}</p>
</div>
<label class="relative inline-flex items-center cursor-pointer">
<input checked="" class="sr-only peer" type="checkbox"/>
<div class="w-11 h-6 bg-surface-container-highest peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-outline-variant after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
</label>
</div>
<div class="flex items-center justify-between">
<div>
<p class="font-body-md font-bold">{{ __('SMS Updates') }}</p>
<p class="text-label-sm text-on-surface-variant">{{ __('Emergency route changes only') }}</p>
</div>
<label class="relative inline-flex items-center cursor-pointer">
<input class="sr-only peer" type="checkbox"/>
<div class="w-11 h-6 bg-surface-container-highest peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-outline-variant after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
</label>
</div>
</div>
</div>
<div>
<h5 class="text-label-md font-bold uppercase tracking-wider text-on-surface-variant mb-md">{{ __('Emergency Contact') }}</h5>
<div class="space-y-md">
<div class="grid grid-cols-2 gap-md">
<div>
<label class="block text-label-sm text-on-surface-variant mb-1">{{ __('Name') }}</label>
<input class="w-full bg-surface-container-low border border-outline-variant rounded-lg p-2 text-body-md focus:ring-primary focus:border-primary" type="text" value="Sarah Chen"/>
</div>
<div>
<label class="block text-label-sm text-on-surface-variant mb-1">{{ __('Relationship') }}</label>
<input class="w-full bg-surface-container-low border border-outline-variant rounded-lg p-2 text-body-md focus:ring-primary focus:border-primary" type="text" value="Spouse"/>
</div>
</div>
<div>
<label class="block text-label-sm text-on-surface-variant mb-1">Số điện thoại Number</label>
<input class="w-full bg-surface-container-low border border-outline-variant rounded-lg p-2 text-body-md focus:ring-primary focus:border-primary" type="tel" value="+1 (555) 012-3456"/>
</div>
</div>
</div>
</div>
<div class="mt-xl pt-xl border-t border-outline-variant flex justify-end gap-md">
<button class="px-lg py-2 rounded-xl text-on-surface-variant hover:bg-surface-container-low transition-colors">{{ __('Discard Changes') }}</button>
<button class="px-lg py-2 rounded-xl bg-primary text-on-primary font-title-lg active:scale-95 transition-transform">{{ __('Save Preferences') }}</button>
</div>
</div>
</div>

<!-- Password Change Section (Added for OTP update) -->
<div class="md:col-span-12 glass-card rounded-2xl p-lg mt-lg">
    <h4 class="font-title-lg text-title-lg mb-lg">Bảo mật tài khoản</h4>
    @if(Auth::user()->google_id ?? session('user_id') ? \App\Models\User::find(session('user_id'))->google_id : false)
        <div class="p-4 bg-surface-container-low rounded-xl border border-outline-variant/30 flex items-center gap-3">
            <img src="https://www.gstatic.com/images/branding/product/1x/gxg_48dp.png" class="w-8 h-8" alt="Google">
            <div>
                <p class="font-semibold text-on-surface">Tài khoản Google</p>
                <p class="text-[12px] text-on-surface-variant">Tài khoản này được xác thực qua Google. Bạn không thể đổi mật khẩu tại đây.</p>
            </div>
        </div>
    @else
    <form id="change-password-form" onsubmit="submitChangePassword(event)" class="relative">
        @csrf
        <div id="pwd-alert" class="hidden mb-4 p-3 rounded-lg text-label-md"></div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-md max-w-2xl">
            <div class="md:col-span-2">
                <label class="block text-label-sm text-on-surface-variant mb-1">Mật khẩu hiện tại</label>
                <input name="current_password" type="password" required
                       class="w-full bg-surface-container-low border border-outline-variant rounded-lg p-2 text-body-md focus:ring-primary focus:border-primary" placeholder="••••••••"/>
            </div>
            <div>
                <label class="block text-label-sm text-on-surface-variant mb-1">Mật khẩu mới</label>
                <input name="new_password" type="password" required minlength="6"
                       class="w-full bg-surface-container-low border border-outline-variant rounded-lg p-2 text-body-md focus:ring-primary focus:border-primary" placeholder="Tối thiểu 6 ký tự"/>
            </div>
            <div>
                <label class="block text-label-sm text-on-surface-variant mb-1">Nhập lại mật khẩu mới</label>
                <input name="new_password_confirmation" type="password" required minlength="6"
                       class="w-full bg-surface-container-low border border-outline-variant rounded-lg p-2 text-body-md focus:ring-primary focus:border-primary" placeholder="••••••••"/>
            </div>
        </div>
        <div class="mt-md">
            <button type="submit" id="btn-save-pwd" class="px-lg py-2 rounded-xl bg-primary text-on-primary font-title-lg active:scale-95 transition-transform">Đổi mật khẩu</button>
        </div>
    </form>
    @endif
</div>

</div>
</div>

<!-- OTP Modal -->
<div id="otpModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center">
    <div class="bg-surface rounded-2xl w-full max-w-sm overflow-hidden shadow-2xl p-6 relative">
        <div class="flex justify-between items-center mb-5">
            <h3 class="text-[18px] font-bold text-on-surface">Xác nhận đổi mật khẩu</h3>
            <button onclick="closeOtpModal()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-surface-container-high text-on-surface-variant transition-colors">
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
                       class="w-full bg-surface-container-low border border-outline-variant rounded-lg p-2 text-center text-body-lg tracking-widest font-bold focus:ring-primary focus:border-primary" placeholder="123456"/>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeOtpModal()" class="px-5 py-2 rounded-xl text-[13px] font-semibold text-on-surface-variant hover:bg-surface-container-high transition-colors">Hủy</button>
                <button type="submit" id="btn-verify-otp" class="px-5 py-2 rounded-xl bg-primary text-on-primary font-title-lg active:scale-95 transition-transform">Xác nhận</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Shipper Profile -->
<div id="editShipperModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center">
    <div class="bg-surface rounded-2xl w-full max-w-lg overflow-hidden shadow-2xl">
        <div class="p-lg border-b border-outline-variant/30 flex justify-between items-center">
            <h3 class="font-headline-sm text-on-surface">Sửa thông tin hồ sơ</h3>
            <button onclick="document.getElementById('editShipperModal').classList.add('hidden')" class="text-on-surface-variant hover:text-on-surface">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form action="{{ route('shipper.profile.update') }}" method="POST" class="p-lg space-y-md">
            @csrf
            <div>
                <label class="font-label-sm text-on-surface-variant block mb-1">Tên hiển thị (Username)</label>
                <input name="username" type="text" value="{{ $shipper->user->username }}" required class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-md py-2 focus:ring-primary focus:border-primary" />
            </div>
            <div>
                <label class="font-label-sm text-on-surface-variant block mb-1">Loại xe</label>
                <input name="vehicle_type" type="text" value="{{ $shipper->vehicle_type }}" class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-md py-2 focus:ring-primary focus:border-primary" />
            </div>
            <div>
                <label class="font-label-sm text-on-surface-variant block mb-1">Biển số xe</label>
                <input name="license_plate" type="text" value="{{ $shipper->license_plate }}" class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-md py-2 focus:ring-primary focus:border-primary" />
            </div>
            <div class="flex justify-end gap-md pt-md border-t border-outline-variant/30">
                <button type="button" onclick="document.getElementById('editShipperModal').classList.add('hidden')" class="px-lg py-2 rounded-full font-label-md bg-surface-container-high text-on-surface hover:bg-surface-container-highest">Hủy</button>
                <button type="submit" class="px-lg py-2 rounded-full font-label-md bg-primary text-on-primary hover:opacity-90">Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>
</main>
@endsection

@push('scripts')
<script>

        // Micro-interaction: Hover effects on cards
        document.querySelectorAll('.glass-card').forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-4px)';
                card.style.boxShadow = '0px 20px 25px -5px rgba(0,0,0,0.1)';
                card.style.transition = 'all 0.3s ease';
            });
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0px)';
                card.style.boxShadow = '0px 4px 6px -1px rgba(0,0,0,0.05)';
            });
        });
    
</script>
<script>
// Modal OTP functions
function openOtpModal(email) {
    document.getElementById('otp-email-display').textContent = email;
    document.getElementById('otpModal').classList.remove('hidden');
    document.getElementById('otp-alert').classList.add('hidden');
    document.getElementById('otp-input').value = '';
}

function closeOtpModal() {
    document.getElementById('otpModal').classList.add('hidden');
}

// AJAX submit change password
async function submitChangePassword(e) {
    e.preventDefault();
    const form = e.target;
    const btn = document.getElementById('btn-save-pwd');
    const alertBox = document.getElementById('pwd-alert');
    const formData = new FormData(form);

    btn.disabled = true;
    btn.innerHTML = '<span class="material-symbols-outlined animate-spin" style="font-size:18px;vertical-align:middle;">refresh</span> Đang xử lý...';
    alertBox.classList.add('hidden');

    try {
        const res = await fetch('{{ route("profile.password") }}', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: formData
        });
        const data = await res.json();
        
        btn.disabled = false;
        btn.innerHTML = 'Đổi mật khẩu';

        if (!res.ok) {
            alertBox.textContent = data.error || data.message || 'Có lỗi xảy ra.';
            alertBox.className = 'mb-4 p-3 rounded-lg text-label-md block bg-error-container text-on-error-container';
        } else {
            if(data.require_otp) {
                openOtpModal(data.email);
            } else {
                alertBox.textContent = data.message;
                alertBox.className = 'mb-4 p-3 rounded-lg text-label-md block bg-primary-container text-on-primary-container';
                form.reset();
            }
        }
    } catch (err) {
        btn.disabled = false;
        btn.innerHTML = 'Đổi mật khẩu';
        alertBox.textContent = 'Lỗi kết nối máy chủ.';
        alertBox.className = 'mb-4 p-3 rounded-lg text-label-md block bg-error-container text-on-error-container';
    }
}

// AJAX submit verify OTP
async function submitVerifyOtp(e) {
    e.preventDefault();
    const form = e.target;
    const btn = document.getElementById('btn-verify-otp');
    const alertBox = document.getElementById('otp-alert');
    
    btn.disabled = true;
    btn.innerHTML = 'Đang xử lý...';
    alertBox.classList.add('hidden');

    try {
        const res = await fetch('{{ route("profile.password.verify") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({ otp: document.getElementById('otp-input').value })
        });
        const data = await res.json();
        
        btn.disabled = false;
        btn.innerHTML = 'Xác nhận';

        if (!res.ok) {
            alertBox.textContent = data.error || data.message || 'Có lỗi xảy ra.';
            alertBox.classList.remove('hidden');
        } else {
            closeOtpModal();
            const pwdAlert = document.getElementById('pwd-alert');
            pwdAlert.textContent = data.message;
            pwdAlert.className = 'mb-4 p-3 rounded-lg text-label-md block bg-primary-container text-on-primary-container';
            document.getElementById('change-password-form').reset();
        }
    } catch (err) {
        btn.disabled = false;
        btn.innerHTML = 'Xác nhận';
        alertBox.textContent = 'Lỗi kết nối máy chủ.';
        alertBox.classList.remove('hidden');
    }
}
</script>
@endpush
