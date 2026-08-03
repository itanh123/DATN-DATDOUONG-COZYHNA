@extends('layouts.customer')

@section('title', 'Tài khoản của tôi')

@push('styles')
<style>
    /* Premium background */
    .account-bg {
        background: linear-gradient(135deg, #f8f9ff 0%, #e8f5e9 50%, #f0f7ff 100%);
        min-height: 100vh;
    }

    /* Glassmorphism sidebar */
    .sidebar-glass {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(0, 110, 28, 0.1);
        box-shadow: 0 8px 32px rgba(0, 110, 28, 0.08);
    }

    /* Premium card */
    .premium-card {
        background: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.8);
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06), 0 1px 4px rgba(0, 0, 0, 0.04);
        transition: box-shadow 0.3s ease, transform 0.2s ease;
    }
    .premium-card:hover {
        box-shadow: 0 8px 40px rgba(0, 110, 28, 0.12);
    }

    /* Avatar ring animation */
    .avatar-ring {
        background: conic-gradient(#006e1c 0%, #4caf50 40%, #a5d6a7 60%, #e8f5e9 100%);
        padding: 3px;
        border-radius: 9999px;
        animation: spin-ring 1.5s ease-out forwards;
    }
    @keyframes spin-ring {
        to { transform: rotate(360deg); }
    }
    .avatar-inner {
        background: white;
        border-radius: 9999px;
        padding: 3px;
    }

    /* Tab nav item */
    .tab-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
        color: #3f4a3c;
        font-weight: 500;
        font-size: 14px;
        border: 1px solid transparent;
    }
    .tab-item:hover {
        background: rgba(0, 110, 28, 0.06);
        color: #006e1c;
    }
    .tab-item.active {
        background: linear-gradient(135deg, rgba(0, 110, 28, 0.12), rgba(76, 175, 80, 0.08));
        color: #006e1c;
        border-color: rgba(0, 110, 28, 0.15);
        font-weight: 600;
    }
    .tab-item.active .tab-icon {
        color: #006e1c;
        background: rgba(0, 110, 28, 0.1);
    }
    .tab-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(63, 74, 60, 0.06);
        color: #3f4a3c;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }

    /* Premium input */
    .premium-input {
        width: 100%;
        background: rgba(248, 249, 255, 0.8);
        border: 1.5px solid rgba(190, 202, 185, 0.5);
        border-radius: 12px;
        padding: 10px 14px;
        font-size: 14px;
        color: #0b1c30;
        transition: all 0.2s ease;
        outline: none;
    }
    .premium-input:focus {
        border-color: #006e1c;
        background: white;
        box-shadow: 0 0 0 3px rgba(0, 110, 28, 0.08);
    }

    /* Green gradient button */
    .btn-primary {
        background: linear-gradient(135deg, #006e1c, #3e6a00);
        color: white;
        padding: 10px 24px;
        border-radius: 9999px;
        font-weight: 600;
        font-size: 14px;
        letter-spacing: 0.02em;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(0, 110, 28, 0.3);
    }
    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(0, 110, 28, 0.4);
    }
    .btn-primary:active {
        transform: scale(0.97);
    }

    /* Alert styles */
    .alert-success {
        background: linear-gradient(135deg, #e8f5e9, #f1f8e9);
        border: 1px solid #a5d6a7;
        color: #1b5e20;
        border-radius: 12px;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        font-weight: 500;
        animation: slide-in 0.4s ease;
    }
    .alert-error {
        background: linear-gradient(135deg, #fce4ec, #ffeef0);
        border: 1px solid #ef9a9a;
        color: #b71c1c;
        border-radius: 12px;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        font-weight: 500;
        animation: slide-in 0.4s ease;
    }
    @keyframes slide-in {
        from { opacity: 0; transform: translateY(-8px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Membership gradient */
    .membership-card {
        background: linear-gradient(135deg, #006e1c 0%, #3e6a00 50%, #005313 100%);
        position: relative;
        overflow: hidden;
    }
    .membership-card::before {
        content: '';
        position: absolute;
        top: -60px;
        right: -60px;
        width: 200px;
        height: 200px;
        background: rgba(255,255,255,0.06);
        border-radius: 9999px;
    }
    .membership-card::after {
        content: '';
        position: absolute;
        bottom: -40px;
        left: -20px;
        width: 150px;
        height: 150px;
        background: rgba(255,255,255,0.04);
        border-radius: 9999px;
    }

    /* Progress bar animation */
    .progress-bar {
        height: 8px;
        background: rgba(255,255,255,0.2);
        border-radius: 9999px;
        overflow: hidden;
    }
    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #78dc77, #a5d6a7);
        border-radius: 9999px;
        width: 0%;
        transition: width 1.2s cubic-bezier(0.16, 1, 0.3, 1);
    }

    /* Address card */
    .address-card {
        border: 2px solid transparent;
        border-radius: 16px;
        padding: 16px;
        background: white;
        transition: all 0.2s ease;
        position: relative;
    }
    .address-card.is-default {
        border-color: rgba(0, 110, 28, 0.3);
        background: rgba(0, 110, 28, 0.02);
    }
    .address-card:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
    }

    /* Modal */
    .modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.4);
        backdrop-filter: blur(4px);
        z-index: 999;
        display: none;
        align-items: center;
        justify-content: center;
    }
    .modal-backdrop.open {
        display: flex;
        animation: fade-in 0.2s ease;
    }
    .modal-box {
        background: white;
        border-radius: 20px;
        padding: 28px;
        width: 100%;
        max-width: 480px;
        box-shadow: 0 24px 60px rgba(0,0,0,0.15);
        animation: scale-in 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes fade-in {
        from { opacity: 0; } to { opacity: 1; }
    }
    @keyframes scale-in {
        from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); }
    }

    /* Avatar upload hover */
    .avatar-upload-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 110, 28, 0.65);
        border-radius: 9999px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.2s ease;
        cursor: pointer;
        color: white;
    }
    .avatar-wrapper:hover .avatar-upload-overlay {
        opacity: 1;
    }

    /* Gender radio */
    .gender-radio input:checked + label {
        background: rgba(0, 110, 28, 0.1);
        border-color: #006e1c;
        color: #006e1c;
        font-weight: 600;
    }
    .gender-label {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border: 1.5px solid rgba(190, 202, 185, 0.6);
        border-radius: 9999px;
        cursor: pointer;
        font-size: 13px;
        transition: all 0.15s ease;
        color: #3f4a3c;
    }
    .gender-label:hover {
        border-color: #006e1c;
        color: #006e1c;
    }
</style>
@endpush

@section('content')
{{-- Global alerts --}}
<div class="pt-20 account-bg">
<div class="max-w-6xl mx-auto px-4 md:px-lg pb-32">

{{-- Alerts --}}
@if(session('success'))
<div class="alert-success mb-4 mt-4">
    <span class="material-symbols-outlined text-[20px]">check_circle</span>
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="alert-error mb-4 mt-4">
    <span class="material-symbols-outlined text-[20px]">error</span>
    {{ session('error') }}
</div>
@endif
@if($errors->any())
<div class="alert-error mb-4 mt-4">
    <span class="material-symbols-outlined text-[20px]">error</span>
    <div>
        @foreach($errors->all() as $error)
        <div>{{ $error }}</div>
        @endforeach
    </div>
</div>
@endif

<div class="flex flex-col md:flex-row gap-6 pt-6">

    {{-- ========== LEFT SIDEBAR ========== --}}
    <aside class="md:w-72 flex-shrink-0">
        <div class="sidebar-glass rounded-2xl p-4 sticky top-24">
            {{-- Profile header in sidebar --}}
            <div class="flex flex-col items-center text-center mb-6 pb-5 border-b border-outline-variant/20">
                <div class="avatar-ring mb-3">
                    <div class="avatar-inner">
                        <img id="sidebar-avatar"
                             src="{{ $user->avatar ? (str_starts_with($user->avatar, 'http') ? $user->avatar : asset('storage/'.$user->avatar)) : 'https://ui-avatars.com/api/?name='.urlencode($user->full_name ?? $user->username).'&background=006e1c&color=fff&size=96' }}"
                             class="w-20 h-20 rounded-full object-cover"
                             alt="Avatar"/>
                    </div>
                </div>
                <h3 class="font-semibold text-[16px] text-on-surface">{{ $user->full_name ?? $user->username }}</h3>
                <p class="text-[12px] text-on-surface-variant mt-0.5">Thành viên từ {{ $user->created_at->format('m/Y') }}</p>
                @if($user->customerProfile)
                <div class="mt-2 inline-flex items-center gap-1 bg-primary/10 text-primary rounded-full px-3 py-0.5 text-[11px] font-bold uppercase tracking-wider">
                    <span class="material-symbols-outlined text-[14px]">workspace_premium</span>
                    {{ $user->customerProfile->membership_level ?? 'Member' }}
                </div>
                @endif
            </div>

            {{-- Nav tabs --}}
            <nav class="space-y-1">
                <button id="tab-btn-profile" onclick="switchTab('profile')" class="tab-item active w-full text-left">
                    <span class="tab-icon material-symbols-outlined text-[20px]">person</span>
                    <span>Thông tin cá nhân</span>
                </button>
                <button id="tab-btn-addresses" onclick="switchTab('addresses')" class="tab-item w-full text-left">
                    <span class="tab-icon material-symbols-outlined text-[20px]">location_on</span>
                    <span>Địa chỉ</span>
                </button>
                <button id="tab-btn-security" onclick="switchTab('security')" class="tab-item w-full text-left">
                    <span class="tab-icon material-symbols-outlined text-[20px]">lock</span>
                    <span>Bảo mật</span>
                </button>
                <button id="tab-btn-membership" onclick="switchTab('membership')" class="tab-item w-full text-left">
                    <span class="tab-icon material-symbols-outlined text-[20px]">workspace_premium</span>
                    <span>Hạng thành viên</span>
                </button>
            </nav>

            {{-- Quick stats --}}
            @if($user->customerProfile)
            <div class="mt-6 pt-5 border-t border-outline-variant/20 grid grid-cols-2 gap-3">
                <div class="bg-surface rounded-xl p-3 text-center">
                    <div class="text-[20px] font-bold text-primary">{{ $user->customerProfile->loyalty_points ?? 0 }}</div>
                    <div class="text-[10px] text-on-surface-variant uppercase tracking-wide font-semibold mt-0.5">Điểm</div>
                </div>
                <div class="bg-surface rounded-xl p-3 text-center">
                    <div class="text-[20px] font-bold text-primary">{{ $user->customerProfile->total_orders ?? 0 }}</div>
                    <div class="text-[10px] text-on-surface-variant uppercase tracking-wide font-semibold mt-0.5">Đơn</div>
                </div>
            </div>
            @endif
        </div>
    </aside>

    {{-- ========== RIGHT CONTENT ========== --}}
    <div class="flex-1 min-w-0">

        {{-- ====== PROFILE TAB ====== --}}
        <section id="content-profile" class="space-y-5">
            <form action="{{ route('customer.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Avatar + header --}}
                <div class="premium-card rounded-2xl p-6">
                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <h1 class="text-[22px] font-bold text-on-surface">Thông tin cá nhân</h1>
                            <p class="text-[13px] text-on-surface-variant mt-0.5">Cập nhật thông tin hồ sơ của bạn</p>
                        </div>
                        <button type="submit" class="btn-primary">
                            <span class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">save</span>
                                Lưu thay đổi
                            </span>
                        </button>
                    </div>

                    {{-- Avatar upload --}}
                    <div class="flex items-center gap-5 mb-6 pb-6 border-b border-outline-variant/15">
                        <div class="relative avatar-wrapper cursor-pointer" onclick="document.getElementById('avatar-input').click()">
                            <img id="avatar-preview"
                                 src="{{ $user->avatar ? (str_starts_with($user->avatar, 'http') ? $user->avatar : asset('storage/'.$user->avatar)) : 'https://ui-avatars.com/api/?name='.urlencode($user->full_name ?? $user->username).'&background=006e1c&color=fff&size=128' }}"
                                 class="w-24 h-24 rounded-full object-cover ring-4 ring-primary/20"
                                 alt="Avatar"/>
                            <div class="avatar-upload-overlay">
                                <span class="material-symbols-outlined text-[22px]">photo_camera</span>
                                <span class="text-[10px] font-semibold mt-1">Thay ảnh</span>
                            </div>
                        </div>
                        <input type="file" id="avatar-input" name="avatar" accept="image/*" class="hidden" onchange="previewAvatar(this)"/>
                        <div>
                            <p class="text-[14px] font-semibold text-on-surface">Ảnh đại diện</p>
                            <p class="text-[12px] text-on-surface-variant mt-1">JPG, PNG, WEBP. Tối đa 2MB</p>
                            <button type="button" onclick="document.getElementById('avatar-input').click()"
                                class="mt-2 text-[12px] text-primary font-semibold hover:underline flex items-center gap-1">
                                <span class="material-symbols-outlined text-[15px]">upload</span> Tải lên ảnh mới
                            </button>
                        </div>
                    </div>

                    {{-- Form fields --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-semibold text-on-surface-variant uppercase tracking-wide mb-1.5">Tên đầy đủ</label>
                            <input name="full_name" type="text"
                                   value="{{ old('full_name', $user->full_name ?? '') }}"
                                   class="premium-input" placeholder="Nguyễn Văn A"/>
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-on-surface-variant uppercase tracking-wide mb-1.5">Tên đăng nhập</label>
                            <input type="text" value="{{ $user->username }}"
                                   class="premium-input opacity-60 cursor-not-allowed" readonly/>
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-on-surface-variant uppercase tracking-wide mb-1.5">Email</label>
                            <input name="email" type="email"
                                   value="{{ old('email', $user->email ?? '') }}"
                                   class="premium-input" placeholder="email@example.com"/>
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-on-surface-variant uppercase tracking-wide mb-1.5">Số điện thoại</label>
                            <input name="phone" type="tel"
                                   value="{{ old('phone', $user->phone ?? '') }}"
                                   class="premium-input" placeholder="09xxxxxxxx"/>
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-on-surface-variant uppercase tracking-wide mb-1.5">Ngày sinh</label>
                            <input name="birthday" type="date"
                                   value="{{ old('birthday', $user->birthday ? \Carbon\Carbon::parse($user->birthday)->format('Y-m-d') : '') }}"
                                   class="premium-input"/>
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-on-surface-variant uppercase tracking-wide mb-1.5">Giới tính</label>
                            <div class="flex gap-2 mt-1 flex-wrap">
                                @foreach(['male' => 'Nam', 'female' => 'Nữ', 'other' => 'Khác'] as $val => $label)
                                <div class="gender-radio">
                                    <input type="radio" name="gender" id="gender-{{ $val }}" value="{{ $val }}" class="sr-only"
                                        {{ old('gender', $user->gender) === $val ? 'checked' : '' }}>
                                    <label for="gender-{{ $val }}" class="gender-label">
                                        <span class="material-symbols-outlined text-[16px]">{{ $val === 'male' ? 'male' : ($val === 'female' ? 'female' : 'transgender') }}</span>
                                        {{ $label }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </section>

        {{-- ====== ADDRESSES TAB ====== --}}
        <section id="content-addresses" class="hidden space-y-5">
            <div class="premium-card rounded-2xl p-6">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h1 class="text-[22px] font-bold text-on-surface">Địa chỉ giao hàng</h1>
                        <p class="text-[13px] text-on-surface-variant mt-0.5">Quản lý các địa chỉ đã lưu của bạn</p>
                    </div>
                    <button onclick="openAddressModal()" class="btn-primary">
                        <span class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">add</span>
                            Thêm địa chỉ
                        </span>
                    </button>
                </div>

                @php
                    $addresses = $user->customerProfile?->addresses ?? collect();
                @endphp

                @if($addresses->isEmpty())
                <div class="text-center py-16">
                    <div class="w-20 h-20 rounded-full bg-surface flex items-center justify-center mx-auto mb-4">
                        <span class="material-symbols-outlined text-[40px] text-on-surface-variant/40">location_off</span>
                    </div>
                    <h3 class="text-[16px] font-semibold text-on-surface">Chưa có địa chỉ nào</h3>
                    <p class="text-[13px] text-on-surface-variant mt-1 mb-5">Thêm địa chỉ để giao hàng nhanh hơn</p>
                    <button onclick="openAddressModal()" class="btn-primary">
                        <span class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">add_location</span>
                            Thêm địa chỉ mới
                        </span>
                    </button>
                </div>
                @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($addresses as $addr)
                    <div class="address-card {{ $addr->is_default ? 'is-default' : '' }}">
                        {{-- Default badge --}}
                        @if($addr->is_default)
                        <div class="absolute top-3 right-3">
                            <span class="bg-primary/10 text-primary text-[10px] font-bold uppercase tracking-widest px-2 py-0.5 rounded-full">Mặc định</span>
                        </div>
                        @endif
                        <div class="flex gap-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 {{ $addr->is_default ? 'bg-primary/10 text-primary' : 'bg-surface text-on-surface-variant' }}">
                                <span class="material-symbols-outlined text-[20px]">{{ $addr->is_default ? 'home' : 'location_on' }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[14px] font-semibold text-on-surface">{{ $addr->address }}</p>
                                <p class="text-[12px] text-on-surface-variant mt-0.5">{{ $addr->ward }}, {{ $addr->district }}, {{ $addr->province }}</p>
                                @if($addr->note)
                                <p class="text-[11px] text-on-surface-variant/60 mt-1 italic">{{ $addr->note }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="flex gap-2 mt-3 pt-3 border-t border-outline-variant/15">
                            <form action="{{ route('customer.address.delete.post', $addr->id) }}" method="POST" class="flex-1"
                                  onsubmit="return confirm('Xóa địa chỉ này?')">
                                @csrf
                                <button type="submit" class="w-full text-[12px] text-error hover:bg-error-container rounded-lg py-1.5 px-3 transition-colors flex items-center gap-1 justify-center">
                                    <span class="material-symbols-outlined text-[15px]">delete</span> Xóa
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </section>

        {{-- ====== SECURITY TAB ====== --}}
        <section id="content-security" class="hidden space-y-5">
            <div class="premium-card rounded-2xl p-6">
                <div class="mb-5">
                    <h1 class="text-[22px] font-bold text-on-surface">Bảo mật tài khoản</h1>
                    <p class="text-[13px] text-on-surface-variant mt-0.5">Đổi mật khẩu để bảo vệ tài khoản</p>
                </div>
                <form action="{{ route('profile.password') }}" method="POST">
                    @csrf
                    <div class="max-w-md space-y-4">
                        <div>
                            <label class="block text-[11px] font-semibold text-on-surface-variant uppercase tracking-wide mb-1.5">Mật khẩu hiện tại</label>
                            <div class="relative">
                                <input name="current_password" type="password" id="cur-pw" required
                                       class="premium-input pr-12" placeholder="••••••••"/>
                                <button type="button" onclick="togglePw('cur-pw', this)"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary transition-colors">
                                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-on-surface-variant uppercase tracking-wide mb-1.5">Mật khẩu mới</label>
                            <div class="relative">
                                <input name="new_password" type="password" id="new-pw" required
                                       class="premium-input pr-12" placeholder="Tối thiểu 6 ký tự"
                                       oninput="checkPasswordStrength(this.value)"/>
                                <button type="button" onclick="togglePw('new-pw', this)"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary transition-colors">
                                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                                </button>
                            </div>
                            {{-- Strength indicator --}}
                            <div class="mt-2 space-y-1" id="pw-strength-container" style="display:none">
                                <div class="flex gap-1">
                                    <div class="h-1 flex-1 rounded-full bg-gray-200 overflow-hidden"><div id="str-1" class="h-full rounded-full transition-all duration-300"></div></div>
                                    <div class="h-1 flex-1 rounded-full bg-gray-200 overflow-hidden"><div id="str-2" class="h-full rounded-full transition-all duration-300"></div></div>
                                    <div class="h-1 flex-1 rounded-full bg-gray-200 overflow-hidden"><div id="str-3" class="h-full rounded-full transition-all duration-300"></div></div>
                                    <div class="h-1 flex-1 rounded-full bg-gray-200 overflow-hidden"><div id="str-4" class="h-full rounded-full transition-all duration-300"></div></div>
                                </div>
                                <p id="pw-strength-text" class="text-[11px] text-on-surface-variant"></p>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-on-surface-variant uppercase tracking-wide mb-1.5">Xác nhận mật khẩu mới</label>
                            <div class="relative">
                                <input name="new_password_confirmation" type="password" id="conf-pw" required
                                       class="premium-input pr-12" placeholder="Nhập lại mật khẩu"/>
                                <button type="button" onclick="togglePw('conf-pw', this)"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary transition-colors">
                                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                                </button>
                            </div>
                        </div>
                        <div class="pt-2">
                            <button type="submit" class="btn-primary">
                                <span class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[18px]">lock_reset</span>
                                    Đổi mật khẩu
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Security tips --}}
            <div class="premium-card rounded-2xl p-6">
                <h3 class="text-[15px] font-semibold text-on-surface mb-3 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px] text-primary">tips_and_updates</span>
                    Mẹo bảo mật
                </h3>
                <ul class="space-y-2">
                    @foreach([
                        ['check_circle', 'Sử dụng mật khẩu dài ít nhất 8 ký tự'],
                        ['check_circle', 'Kết hợp chữ hoa, chữ thường, số và ký tự đặc biệt'],
                        ['check_circle', 'Không dùng cùng mật khẩu cho nhiều tài khoản'],
                        ['check_circle', 'Thay đổi mật khẩu định kỳ mỗi 3–6 tháng'],
                    ] as [$icon, $text])
                    <li class="flex items-center gap-2 text-[13px] text-on-surface-variant">
                        <span class="material-symbols-outlined text-[16px] text-primary">{{ $icon }}</span>
                        {{ $text }}
                    </li>
                    @endforeach
                </ul>
            </div>
        </section>

        {{-- ====== MEMBERSHIP TAB ====== --}}
        <section id="content-membership" class="hidden space-y-5">
            @php
                $profile = $user->customerProfile;
                $level = $profile?->membership_level ?? 'Member';
                $points = $profile?->loyalty_points ?? 0;
                $totalSpent = $profile?->total_spent ?? 0;
                $totalOrders = $profile?->total_orders ?? 0;

                $levels = [
                    'Member'   => ['min' => 0,    'max' => 500,  'next' => 'Silver',   'color' => 'from-slate-400 to-slate-500'],
                    'Silver'   => ['min' => 500,  'max' => 2000, 'next' => 'Gold',     'color' => 'from-slate-400 to-blue-400'],
                    'Gold'     => ['min' => 2000, 'max' => 5000, 'next' => 'Platinum', 'color' => 'from-yellow-500 to-amber-400'],
                    'Platinum' => ['min' => 5000, 'max' => 5000, 'next' => null,       'color' => 'from-purple-500 to-indigo-500'],
                ];
                $currentLevel = $levels[$level] ?? $levels['Member'];
                $nextLevel = $currentLevel['next'];
                $progressPct = $nextLevel
                    ? min(100, round(($points - $currentLevel['min']) / ($currentLevel['max'] - $currentLevel['min']) * 100))
                    : 100;
                $pointsToNext = $nextLevel ? max(0, $currentLevel['max'] - $points) : 0;
            @endphp

            {{-- Main card --}}
            <div class="membership-card rounded-2xl p-6 text-white relative z-10">
                <div class="flex justify-between items-start relative z-10">
                    <div>
                        <p class="text-[11px] uppercase tracking-widest text-white/70 font-semibold mb-1">Hạng hiện tại</p>
                        <h1 class="text-[36px] font-extrabold leading-none tracking-tight">{{ $level }}</h1>
                        <p class="text-[13px] text-white/70 mt-2">{{ $user->full_name ?? $user->username }}</p>
                    </div>
                    <div class="text-right">
                        <div class="bg-white/15 backdrop-blur rounded-2xl px-5 py-4 border border-white/20 text-center">
                            <div class="text-[32px] font-extrabold leading-none">{{ number_format($points) }}</div>
                            <div class="text-[10px] uppercase tracking-widest text-white/70 mt-1">Điểm tích lũy</div>
                        </div>
                    </div>
                </div>

                @if($nextLevel)
                <div class="mt-6 relative z-10">
                    <div class="flex justify-between text-[12px] mb-2">
                        <span class="text-white/80">Tiến độ đến {{ $nextLevel }}</span>
                        <span class="font-bold">{{ $progressPct }}%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" data-width="{{ $progressPct }}%"></div>
                    </div>
                    <p class="text-[12px] text-white/70 mt-2">Cần thêm {{ number_format($pointsToNext) }} điểm để lên hạng {{ $nextLevel }}</p>
                </div>
                @else
                <div class="mt-6 relative z-10 flex items-center gap-2 text-white/80 text-[13px]">
                    <span class="material-symbols-outlined text-[18px]">star</span>
                    Bạn đang ở hạng cao nhất!
                </div>
                @endif
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-3 gap-4">
                @foreach([
                    ['local_cafe', number_format($totalOrders), 'Đơn hàng'],
                    ['payments', number_format($totalSpent, 0, ',', '.') . '₫', 'Chi tiêu'],
                    ['stars', number_format($points), 'Điểm tích lũy'],
                ] as [$icon, $val, $label])
                <div class="premium-card rounded-2xl p-4 text-center">
                    <span class="material-symbols-outlined text-[28px] text-primary mb-2 block">{{ $icon }}</span>
                    <div class="text-[20px] font-bold text-on-surface">{{ $val }}</div>
                    <div class="text-[11px] text-on-surface-variant mt-0.5 uppercase tracking-wide">{{ $label }}</div>
                </div>
                @endforeach
            </div>

            {{-- Benefits by tier --}}
            <div class="premium-card rounded-2xl p-6">
                <h3 class="text-[16px] font-bold text-on-surface mb-4">Quyền lợi theo hạng</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach([
                        ['local_cafe', 'Upsized miễn phí', 'Áp dụng cho đơn buổi sáng'],
                        ['event', 'Early Access', 'Menu theo mùa ra trước'],
                        ['cake', 'Quà sinh nhật', 'Đồ uống miễn phí vào ngày sinh nhật'],
                        ['delivery_dining', 'Ưu tiên giao hàng', 'Lên hàng trước trong khung giờ cao điểm'],
                    ] as [$icon, $title, $desc])
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-surface hover:bg-surface-container transition-colors">
                        <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-[20px] text-primary">{{ $icon }}</span>
                        </div>
                        <div>
                            <p class="text-[13px] font-semibold text-on-surface">{{ $title }}</p>
                            <p class="text-[11px] text-on-surface-variant">{{ $desc }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

    </div>
</div>
</div>
</div>

{{-- ====== ADDRESS MODAL ====== --}}
<div id="address-modal" class="modal-backdrop" onclick="if(event.target===this)closeAddressModal()">
    <div class="modal-box mx-4">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-[18px] font-bold text-on-surface">Thêm địa chỉ mới</h3>
            <button onclick="closeAddressModal()" class="w-8 h-8 rounded-full hover:bg-surface flex items-center justify-center text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <form action="{{ route('customer.address.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-[11px] font-semibold text-on-surface-variant uppercase tracking-wide mb-1.5">Tỉnh / Thành phố <span class="text-error">*</span></label>
                    <input name="province" type="text" required class="premium-input" placeholder="VD: Hà Nội"/>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-semibold text-on-surface-variant uppercase tracking-wide mb-1.5">Quận / Huyện <span class="text-error">*</span></label>
                        <input name="district" type="text" required class="premium-input" placeholder="VD: Cầu Giấy"/>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-on-surface-variant uppercase tracking-wide mb-1.5">Phường / Xã <span class="text-error">*</span></label>
                        <input name="ward" type="text" required class="premium-input" placeholder="VD: Dịch Vọng"/>
                    </div>
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-on-surface-variant uppercase tracking-wide mb-1.5">Địa chỉ cụ thể <span class="text-error">*</span></label>
                    <input name="address" type="text" required class="premium-input" placeholder="Số nhà, tên đường..."/>
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-on-surface-variant uppercase tracking-wide mb-1.5">Ghi chú (tùy chọn)</label>
                    <input name="note" type="text" class="premium-input" placeholder="VD: Cổng màu xanh, tầng 3"/>
                </div>
                <label class="flex items-center gap-3 cursor-pointer select-none">
                    <div class="relative">
                        <input type="checkbox" name="is_default" value="1" class="sr-only peer" id="default-addr-check"/>
                        <div class="w-10 h-6 bg-outline-variant rounded-full peer peer-checked:bg-primary transition-colors"></div>
                        <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform peer-checked:translate-x-4"></div>
                    </div>
                    <span class="text-[13px] font-medium text-on-surface">Đặt làm địa chỉ mặc định</span>
                </label>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeAddressModal()"
                        class="flex-1 py-2.5 border border-outline-variant/50 rounded-full text-[13px] font-semibold text-on-surface hover:bg-surface transition-colors">
                    Hủy
                </button>
                <button type="submit" class="flex-1 btn-primary">Lưu địa chỉ</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
const TABS = ['profile', 'addresses', 'security', 'membership'];

function switchTab(tabId) {
    TABS.forEach(id => {
        document.getElementById(`content-${id}`)?.classList.add('hidden');
        const btn = document.getElementById(`tab-btn-${id}`);
        if (btn) {
            btn.classList.remove('active');
        }
    });

    document.getElementById(`content-${tabId}`)?.classList.remove('hidden');
    const activeBtn = document.getElementById(`tab-btn-${tabId}`);
    if (activeBtn) activeBtn.classList.add('active');

    // Animate progress bars on membership tab
    if (tabId === 'membership') {
        setTimeout(() => {
            document.querySelectorAll('.progress-fill').forEach(bar => {
                bar.style.width = bar.dataset.width || '0%';
            });
        }, 100);
    }
}

// Open tab from query param or hash
window.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    const tab = params.get('tab') || 'profile';
    if (TABS.includes(tab)) switchTab(tab);

    // If there was an error, open the appropriate section
    @if($errors->has('current_password') || $errors->has('new_password'))
    switchTab('security');
    @endif
});

// Avatar preview
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('avatar-preview').src = e.target.result;
            document.getElementById('sidebar-avatar').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Password visibility toggle
function togglePw(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('.material-symbols-outlined');
    if (input.type === 'password') {
        input.type = 'text';
        icon.textContent = 'visibility_off';
    } else {
        input.type = 'password';
        icon.textContent = 'visibility';
    }
}

// Password strength checker
function checkPasswordStrength(value) {
    const container = document.getElementById('pw-strength-container');
    const text = document.getElementById('pw-strength-text');
    container.style.display = value.length > 0 ? 'block' : 'none';

    let score = 0;
    if (value.length >= 6) score++;
    if (value.length >= 10) score++;
    if (/[A-Z]/.test(value) && /[a-z]/.test(value)) score++;
    if (/\d/.test(value) && /[^A-Za-z0-9]/.test(value)) score++;

    const colors = ['bg-red-400', 'bg-orange-400', 'bg-yellow-400', 'bg-green-500'];
    const labels = ['Rất yếu', 'Yếu', 'Trung bình', 'Mạnh'];

    for (let i = 1; i <= 4; i++) {
        const bar = document.getElementById(`str-${i}`);
        bar.className = 'h-full rounded-full transition-all duration-300';
        if (i <= score) bar.classList.add(colors[score - 1] || 'bg-red-400');
        bar.style.width = i <= score ? '100%' : '0%';
    }
    text.textContent = labels[score - 1] || '';
    text.className = `text-[11px] ${score >= 3 ? 'text-green-600' : score >= 2 ? 'text-orange-500' : 'text-red-500'}`;
}

// Address modal
function openAddressModal() {
    document.getElementById('address-modal').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeAddressModal() {
    document.getElementById('address-modal').classList.remove('open');
    document.body.style.overflow = '';
}
</script>
@endpush
