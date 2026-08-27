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
    .avatar-ring.silver { background: conic-gradient(#757f9a 0%, #d7dde8 40%, #ffffff 60%, #757f9a 100%); }
    .avatar-ring.gold { background: conic-gradient(#ffd700 0%, #ffeb3b 40%, #fff8e1 60%, #ff8f00 100%); }
    .avatar-ring.diamond { background: conic-gradient(#00e5ff 0%, #84ffff 40%, #e0f7fa 60%, #18ffff 100%); }
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
    .membership-card.silver { background: linear-gradient(135deg, #757f9a 0%, #b2bcc9 50%, #757f9a 100%); }
    .membership-card.gold { background: linear-gradient(135deg, #ffd700 0%, #ff8f00 50%, #ff6f00 100%); }
    .membership-card.diamond { background: linear-gradient(135deg, #00e5ff 0%, #00b0ff 50%, #0091ea 100%); }
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

@php
    $profile = $user->customerProfile;
    $dynamicRank = $profile ? $profile->dynamic_rank : ['level' => 'Member', 'monthly_spent' => 0];
    $level = $dynamicRank['level'];
    $monthlySpent = $dynamicRank['monthly_spent'];
    $levelClass = strtolower($level);
    
    // Ánh xạ lại tên hạng sang Tiếng Việt
    $rankLabels = [
        'Member' => 'Thành viên',
        'Silver' => 'Bạc',
        'Gold' => 'Vàng',
        'Diamond' => 'Kim Cương',
    ];
    $rankName = $rankLabels[$level] ?? 'Thành viên';
@endphp

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
                <div class="avatar-ring {{ $levelClass }} mb-3">
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
                    {{ $rankName }}
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
                <button id="tab-btn-vouchers" onclick="switchTab('vouchers')" class="tab-item w-full text-left">
                    <span class="tab-icon material-symbols-outlined text-[20px]">local_activity</span>
                    <span>Mã giảm giá</span>
                </button>
                <div class="border-t border-outline-variant/30 my-2 mx-2"></div>
                <a href="/logout" class="tab-item w-full text-left !text-error hover:!bg-error-container/50">
                    <span class="tab-icon material-symbols-outlined text-[20px] !text-error !bg-error/10">logout</span>
                    <span>Đăng xuất</span>
                </a>
            </nav>

            {{-- Quick stats --}}
            @if($user->customerProfile)
            <div class="mt-6 pt-5 border-t border-outline-variant/20 grid grid-cols-2 gap-3">
                <div class="bg-surface rounded-xl p-3 text-center">
                    <div class="text-[14px] font-bold text-primary truncate" title="{{ number_format($monthlySpent) }}₫">{{ number_format($monthlySpent) }}₫</div>
                    <div class="text-[10px] text-on-surface-variant uppercase tracking-wide font-semibold mt-0.5">Tháng này</div>
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
                
                @if($user->google_id)
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
                            <label class="block text-[11px] font-semibold text-on-surface-variant uppercase tracking-wide mb-1.5">Nhập lại mật khẩu mới</label>
                            <div class="relative">
                                <input name="new_password_confirmation" type="password" id="conf-pw" required
                                       class="premium-input pr-12" placeholder="••••••••"/>
                                <button type="button" onclick="togglePw('conf-pw', this)"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary transition-colors">
                                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6">
                        <button type="submit" id="btn-save-pwd" class="btn-primary w-auto">Lưu mật khẩu</button>
                    </div>
                </form>
                @endif
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
                $totalOrders = $profile?->total_orders ?? 0;
                $totalSpent = $profile?->total_spent ?? 0;

                $levels = [
                    'Member'   => ['min' => 0,        'max' => 1000000,   'next' => 'Silver'],
                    'Silver'   => ['min' => 1000000,  'max' => 6000000,   'next' => 'Gold'],
                    'Gold'     => ['min' => 6000000,  'max' => 10000000,  'next' => 'Diamond'],
                    'Diamond'  => ['min' => 10000000, 'max' => 10000000,  'next' => null],
                ];
                $currentLevel = $levels[$level] ?? $levels['Member'];
                $nextLevel = $currentLevel['next'];
                $progressPct = $nextLevel
                    ? min(100, round(($monthlySpent - $currentLevel['min']) / max(1, $currentLevel['max'] - $currentLevel['min']) * 100))
                    : 100;
                $pointsToNext = $nextLevel ? max(0, $currentLevel['max'] - $monthlySpent) : 0;
                $nextLevelName = $nextLevel ? ($rankLabels[$nextLevel] ?? $nextLevel) : null;
            @endphp

            {{-- Main card --}}
            <div class="membership-card {{ $levelClass }} rounded-2xl p-6 text-white relative z-10">
                <div class="flex justify-between items-start relative z-10">
                    <div>
                        <p class="text-[11px] uppercase tracking-widest text-white/70 font-semibold mb-1">Hạng hiện tại</p>
                        <h1 class="text-[36px] font-extrabold leading-none tracking-tight">{{ $rankName }}</h1>
                        <p class="text-[13px] text-white/70 mt-2">{{ $user->full_name ?? $user->username }}</p>
                    </div>
                    <div class="text-right">
                        <div class="bg-white/15 backdrop-blur rounded-2xl px-5 py-4 border border-white/20 text-center">
                            <div class="text-[20px] font-extrabold leading-none">{{ number_format($monthlySpent) }}₫</div>
                            <div class="text-[10px] uppercase tracking-widest text-white/70 mt-1">Chi tiêu tháng này</div>
                        </div>
                    </div>
                </div>

                @if($nextLevel)
                <div class="mt-6 relative z-10">
                    <div class="flex justify-between text-[12px] mb-2">
                        <span class="text-white/80">Tiến độ đến {{ $nextLevelName }}</span>
                        <span class="font-bold">{{ $progressPct }}%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" data-width="{{ $progressPct }}%"></div>
                    </div>
                    <p class="text-[12px] text-white/70 mt-2">Cần thêm {{ number_format($pointsToNext) }}₫ trong tháng này để lên hạng {{ $nextLevelName }}</p>
                </div>
                @else
                <div class="mt-6 relative z-10 flex items-center gap-2 text-white/80 text-[13px]">
                    <span class="material-symbols-outlined text-[18px]">star</span>
                    Bạn đang ở hạng cao nhất!
                </div>
                @endif
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-2 gap-4">
                @foreach([
                    ['local_cafe', number_format($totalOrders), 'Tổng số Đơn hàng'],
                    ['payments', number_format($totalSpent, 0, ',', '.') . '₫', 'Tổng Chi tiêu'],
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

        {{-- ====== VOUCHERS TAB ====== --}}
        <section id="content-vouchers" class="hidden space-y-5">
            <div class="premium-card rounded-2xl p-6">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h1 class="text-[22px] font-bold text-on-surface">Mã giảm giá của tôi</h1>
                        <p class="text-[13px] text-on-surface-variant mt-0.5">Quản lý và sử dụng các voucher bạn đã lưu</p>
                    </div>
                </div>

                @if($vouchers->isEmpty())
                    <div class="bg-surface rounded-3xl p-12 text-center shadow-sm border border-outline-variant/30 flex flex-col items-center justify-center">
                        <div class="w-24 h-24 bg-surface-container rounded-full flex items-center justify-center mb-6">
                            <span class="material-symbols-outlined text-[48px] text-primary">discount</span>
                        </div>
                        <h3 class="text-[18px] font-bold text-on-surface mb-2">Chưa có mã giảm giá nào</h3>
                        <p class="text-[13px] text-on-surface-variant mb-6">Bạn chưa lưu mã giảm giá nào. Hãy quay lại trang chủ để tìm kiếm các ưu đãi mới nhất nhé!</p>
                        <a href="/" class="px-6 py-3 bg-primary text-on-primary rounded-xl text-[14px] font-bold hover:bg-primary/90 transition-colors active:scale-95 flex items-center gap-2 inline-flex">
                            <span class="material-symbols-outlined">home</span> Khám phá ngay
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($vouchers as $voucher)
                            <div class="bg-surface rounded-3xl p-5 shadow-sm border border-outline-variant/30 relative overflow-hidden group hover:shadow-md transition-shadow">
                                <!-- Decorative circle -->
                                <div class="absolute -right-6 -top-6 w-24 h-24 bg-primary/5 rounded-full z-0 group-hover:scale-110 transition-transform"></div>
                                
                                <div class="relative z-10">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                                            <span class="material-symbols-outlined text-[20px]">confirmation_number</span>
                                        </div>
                                        @if($voucher->pivot->is_used)
                                            <span class="px-3 py-1 bg-surface-container text-on-surface-variant text-[11px] font-bold rounded-full">Đã dùng</span>
                                        @else
                                            <span class="px-3 py-1 bg-success/10 text-success text-[11px] font-bold rounded-full">Khả dụng</span>
                                        @endif
                                    </div>
                                    
                                    <h3 class="text-[15px] font-bold text-on-surface mb-1.5">{{ $voucher->name }}</h3>
                                    <p class="text-[12px] text-on-surface-variant mb-2 line-clamp-2 h-8">{{ $voucher->description }}</p>
                                    
                                    <div class="mb-3 space-y-1 text-[12px] text-on-surface-variant bg-surface-container-lowest p-2.5 rounded-xl">
                                        <div class="flex justify-between items-center">
                                            <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">sell</span> Giảm:</span>
                                            <span class="font-bold text-primary">
                                                @if($voucher->discount_type === 'percent')
                                                    {{ $voucher->discount_value }}% 
                                                    @if($voucher->maximum_discount)
                                                        (Tối đa {{ number_format($voucher->maximum_discount, 0, ',', '.') }}đ)
                                                    @endif
                                                @else
                                                    {{ number_format($voucher->discount_value, 0, ',', '.') }}đ
                                                @endif
                                            </span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">shopping_bag</span> Đơn tối thiểu:</span>
                                            <span class="font-bold text-on-surface">{{ number_format($voucher->minimum_order, 0, ',', '.') }}đ</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">schedule</span> HSD:</span>
                                            <span class="font-bold {{ \Carbon\Carbon::parse($voucher->end_date)->isPast() ? 'text-error' : 'text-on-surface' }}">
                                                {{ \Carbon\Carbon::parse($voucher->end_date)->format('d/m/Y H:i') }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center justify-between bg-surface-container-lowest border border-outline-variant/50 rounded-xl p-2.5">
                                        <div>
                                            <div class="text-[10px] text-on-surface-variant mb-0.5">Mã Code</div>
                                            <div class="font-mono font-bold text-primary text-[14px]">{{ $voucher->code }}</div>
                                        </div>
                                        <button onclick="copyVoucher('{{ $voucher->code }}', this)" class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-surface-container transition-colors text-primary" title="Copy mã">
                                            <span class="material-symbols-outlined text-[16px]">content_copy</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
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
                    <select id="province_select" name="province" required class="no-choices premium-input bg-white cursor-pointer">
                        <option value="">Chọn Tỉnh/Thành phố</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-semibold text-on-surface-variant uppercase tracking-wide mb-1.5">Quận / Huyện <span class="text-error">*</span></label>
                        <select id="district_select" name="district" required disabled class="no-choices premium-input bg-white cursor-pointer disabled:bg-gray-100 disabled:cursor-not-allowed">
                            <option value="">Chọn Quận/Huyện</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-on-surface-variant uppercase tracking-wide mb-1.5">Phường / Xã <span class="text-error">*</span></label>
                        <select id="ward_select" name="ward" required disabled class="no-choices premium-input bg-white cursor-pointer disabled:bg-gray-100 disabled:cursor-not-allowed">
                            <option value="">Chọn Phường/Xã</option>
                        </select>
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

<!-- OTP Modal -->
<div id="otpModal" class="modal-backdrop">
    <div class="modal-box">
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
                       class="premium-input text-center tracking-widest font-bold" placeholder="123456"/>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeOtpModal()" class="px-5 py-2 rounded-xl text-[13px] font-semibold text-on-surface-variant hover:bg-surface-container-high transition-colors">Hủy</button>
                <button type="submit" id="btn-verify-otp" class="btn-primary">Xác nhận</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
const TABS = ['profile', 'addresses', 'security', 'membership', 'vouchers'];

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

// Modal OTP functions
function openOtpModal(email) {
    document.getElementById('otp-email-display').textContent = email;
    document.getElementById('otpModal').classList.add('open');
    document.getElementById('otp-alert').classList.add('hidden');
    document.getElementById('otp-input').value = '';
}

function closeOtpModal() {
    document.getElementById('otpModal').classList.remove('open');
}

// AJAX submit change password
async function submitChangePassword(e) {
    e.preventDefault();
    const form = e.target;
    const btn = document.getElementById('btn-save-pwd');
    const alertBox = document.getElementById('pwd-alert');
    const formData = new FormData(form);

    btn.disabled = true;
    btn.innerHTML = '<span class="material-symbols-outlined animate-spin">refresh</span> Đang xử lý...';
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
        btn.innerHTML = 'Lưu mật khẩu';

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
        btn.innerHTML = 'Lưu mật khẩu';
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

// Avatar preview and compression
function compressImage(file, maxSize, callback) {
    const reader = new FileReader();
    reader.onload = function(e) {
        const img = new Image();
        img.onload = function() {
            // max dimensions for avatar
            const MAX_WIDTH = 800;
            const MAX_HEIGHT = 800;
            let width = img.width;
            let height = img.height;

            if (width > height) {
                if (width > MAX_WIDTH) {
                    height *= MAX_WIDTH / width;
                    width = MAX_WIDTH;
                }
            } else {
                if (height > MAX_HEIGHT) {
                    width *= MAX_HEIGHT / height;
                    height = MAX_HEIGHT;
                }
            }

            const canvas = document.createElement('canvas');
            canvas.width = width;
            canvas.height = height;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0, width, height);
            
            let quality = 0.9;
            function tryCompress() {
                canvas.toBlob(function(blob) {
                    if (blob.size > maxSize && quality > 0.1) {
                        quality -= 0.1;
                        tryCompress();
                    } else {
                        // create a new File from the compressed blob
                        const newFile = new File([blob], file.name.replace(/\.[^/.]+$/, "") + ".webp", {
                            type: 'image/webp',
                            lastModified: Date.now()
                        });
                        callback(newFile);
                    }
                }, 'image/webp', quality);
            }
            tryCompress();
        };
        img.src = e.target.result;
    };
    reader.readAsDataURL(file);
}

function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const MAX_SIZE = 2 * 1024 * 1024; // 2MB
        
        // Show loading state
        const originalOverlayHTML = document.querySelector('.avatar-upload-overlay').innerHTML;
        document.querySelector('.avatar-upload-overlay').innerHTML = '<span class="material-symbols-outlined animate-spin text-[22px]">progress_activity</span><span class="text-[10px] font-semibold mt-1">Đang xử lý</span>';
        
        if (file.size > MAX_SIZE) {
            compressImage(file, MAX_SIZE, function(compressedFile) {
                const url = URL.createObjectURL(compressedFile);
                document.getElementById('avatar-preview').src = url;
                document.getElementById('sidebar-avatar').src = url;
                
                // Replace file in input
                const dt = new DataTransfer();
                dt.items.add(compressedFile);
                input.files = dt.files;
                
                document.querySelector('.avatar-upload-overlay').innerHTML = originalOverlayHTML;
            });
        } else {
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('avatar-preview').src = e.target.result;
                document.getElementById('sidebar-avatar').src = e.target.result;
                document.querySelector('.avatar-upload-overlay').innerHTML = originalOverlayHTML;
            };
            reader.readAsDataURL(file);
        }
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

document.addEventListener('DOMContentLoaded', function() {
    const selectProvince = document.getElementById('province_select');
    const selectDistrict = document.getElementById('district_select');
    const selectWard = document.getElementById('ward_select');
    
    if (selectProvince) {
        fetch('https://esgoo.net/api-tinhthanh/1/0.htm')
            .then(res => res.json())
            .then(data => {
                if (data.error === 0) {
                    data.data.forEach(p => {
                        const option = document.createElement('option');
                        option.value = p.full_name;
                        option.text = p.full_name;
                        option.dataset.code = p.id;
                        selectProvince.appendChild(option);
                    });
                    
                    // Khóa cứng tỉnh Hà Nam
                    const targetProvince = "Hà Nam";
                    const haNamOption = Array.from(selectProvince.options).find(opt => opt.text.includes(targetProvince));
                    
                    if (haNamOption) {
                        selectProvince.value = haNamOption.value;
                        selectProvince.style.pointerEvents = 'none';
                        selectProvince.style.backgroundColor = '#f3f4f6';
                        
                        // Trigger change event to load district
                        selectProvince.dispatchEvent(new Event('change'));
                    }
                }
            })
            .catch(err => console.error('Lỗi khi tải tỉnh/thành phố:', err));

        selectProvince.addEventListener('change', function() {
            selectDistrict.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
            selectWard.innerHTML = '<option value="">Chọn Phường/Xã</option>';
            selectDistrict.disabled = true;
            selectWard.disabled = true;
            
            const selectedOption = this.options[this.selectedIndex];
            
            if (this.value && selectedOption && selectedOption.dataset.code) {
                selectDistrict.disabled = false;
                selectDistrict.options[0].text = 'Đang tải...';
                
                fetch(`https://esgoo.net/api-tinhthanh/2/${selectedOption.dataset.code}.htm`)
                    .then(res => res.json())
                    .then(data => {
                        selectDistrict.options[0].text = 'Chọn Quận/Huyện';
                        if (data.error === 0) {
                            data.data.forEach(d => {
                                const option = document.createElement('option');
                                option.value = d.full_name;
                                option.text = d.full_name;
                                option.dataset.code = d.id;
                                selectDistrict.appendChild(option);
                            });
                        }
                    })
                    .catch(err => {
                        selectDistrict.options[0].text = 'Lỗi kết nối';
                    });
            }
        });

        selectDistrict.addEventListener('change', function() {
            selectWard.innerHTML = '<option value="">Chọn Phường/Xã</option>';
            selectWard.disabled = true;
            
            const selectedOption = this.options[this.selectedIndex];
            
            if (this.value && selectedOption && selectedOption.dataset.code) {
                selectWard.disabled = false;
                selectWard.options[0].text = 'Đang tải...';
                
                fetch(`https://esgoo.net/api-tinhthanh/3/${selectedOption.dataset.code}.htm`)
                    .then(res => res.json())
                    .then(data => {
                        selectWard.options[0].text = 'Chọn Phường/Xã';
                        if (data.error === 0) {
                            data.data.forEach(w => {
                                const option = document.createElement('option');
                                option.value = w.full_name;
                                option.text = w.full_name;
                                selectWard.appendChild(option);
                            });
                        }
                    })
                    .catch(err => {
                        selectWard.options[0].text = 'Lỗi kết nối';
                    });
            }
        });
    }
});

function copyVoucher(code, btn) {
    navigator.clipboard.writeText(code);
    const icon = btn.querySelector('.material-symbols-outlined');
    const originalText = icon.innerHTML;
    
    icon.innerHTML = 'check';
    icon.classList.remove('text-primary');
    icon.classList.add('text-success');
    
    setTimeout(() => {
        icon.innerHTML = originalText;
        icon.classList.add('text-primary');
        icon.classList.remove('text-success');
    }, 2000);
}
</script>
@endpush
