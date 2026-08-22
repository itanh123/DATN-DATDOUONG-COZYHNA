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
            <form id="profile-form" action="{{ route('customer.profile.update') }}" method="POST" enctype="multipart/form-data" novalidate onsubmit="return validateProfileForm(event)">
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
                            <label class="block text-[11px] font-semibold text-on-surface-variant uppercase tracking-wide mb-1.5">Tên đầy đủ <span class="text-error">*</span></label>
                            <input name="full_name" type="text" required
                                   value="{{ old('full_name', $user->full_name ?? '') }}"
                                   class="premium-input" placeholder="Nguyễn Văn A"/>
                            <div class="error-msg text-error text-[11px] mt-1 hidden">Vui lòng nhập tên đầy đủ</div>
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-on-surface-variant uppercase tracking-wide mb-1.5">Tên đăng nhập</label>
                            <input type="text" value="{{ $user->username }}"
                                   class="premium-input opacity-60 cursor-not-allowed" readonly/>
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-on-surface-variant uppercase tracking-wide mb-1.5">Email <span class="text-error">*</span></label>
                            <input name="email" type="email" required
                                   value="{{ old('email', $user->email ?? '') }}"
                                   class="premium-input" placeholder="email@example.com"/>
                            <div class="error-msg text-error text-[11px] mt-1 hidden">Email không hợp lệ</div>
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-on-surface-variant uppercase tracking-wide mb-1.5">Số điện thoại <span class="text-error">*</span></label>
                            <input name="phone" type="tel" required pattern="(84|0[3|5|7|8|9])[0-9]{8}"
                                   value="{{ old('phone', $user->phone ?? '') }}"
                                   class="premium-input" placeholder="09xxxxxxxx"/>
                            <div class="error-msg text-error text-[11px] mt-1 hidden">Số điện thoại không hợp lệ</div>
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-on-surface-variant uppercase tracking-wide mb-1.5">Ngày sinh</label>
                            <input name="birthday" type="date"
                                   value="{{ old('birthday', $user->birthday ? \Carbon\Carbon::parse($user->birthday)->format('Y-m-d') : '') }}"
                                   class="premium-input"/>
                            <div class="error-msg text-error text-[11px] mt-1 hidden">Ngày sinh không hợp lệ</div>
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
                                <p class="text-[14px] font-bold text-on-surface flex items-center gap-2">
                                    {{ $addr->receiver_name ?? $user->name }}
                                    @if($addr->receiver_phone || $user->phone)
                                        <span class="text-on-surface-variant/50 text-[10px]">|</span>
                                        <span class="text-[13px] font-medium text-on-surface-variant">{{ $addr->receiver_phone ?? $user->phone }}</span>
                                    @endif
                                </p>
                                <p class="text-[14px] font-medium text-on-surface mt-1">{{ $addr->address }}</p>
                                <p class="text-[12px] text-on-surface-variant mt-0.5">{{ $addr->ward }}, {{ $addr->district }}, {{ $addr->province }}</p>
                                @if($addr->note)
                                <p class="text-[12px] text-on-surface-variant/70 mt-1 italic">Ghi chú: {{ $addr->note }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="mt-4 pt-3 border-t border-outline-variant/30 flex justify-end gap-2">
                            <button onclick="editAddress({{ $addr->id }}, '{{ htmlspecialchars($addr->receiver_name ?? $user->name, ENT_QUOTES) }}', '{{ htmlspecialchars($addr->receiver_phone ?? $user->phone, ENT_QUOTES) }}', '{{ $addr->province }}', '{{ $addr->district }}', '{{ $addr->ward }}', '{{ htmlspecialchars($addr->address, ENT_QUOTES) }}', '{{ htmlspecialchars($addr->note ?? '', ENT_QUOTES) }}', {{ $addr->is_default ? 'true' : 'false' }})" class="px-3 py-1.5 rounded-lg text-[12px] font-semibold text-on-surface hover:bg-surface-container transition-colors flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[16px]">edit</span>
                                Sửa
                            </button>
                            <form action="{{ route('customer.address.delete.post', $addr->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa địa chỉ này?');">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 rounded-lg text-[12px] font-semibold text-error hover:bg-error-container hover:text-on-error-container transition-colors flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[16px]">delete</span>
                                    Xóa
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
            <h3 id="address-modal-title" class="text-[18px] font-bold text-on-surface">Thêm địa chỉ mới</h3>
            <button onclick="closeAddressModal()" class="w-8 h-8 rounded-full hover:bg-surface flex items-center justify-center text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <form id="address-form" action="{{ route('customer.address.store') }}" method="POST" class="space-y-4" novalidate onsubmit="return validateAddressForm(event)">
            @csrf
            <input type="hidden" name="_method" id="address-method" value="POST">
            <div class="grid grid-cols-1 gap-4">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-semibold text-on-surface-variant uppercase tracking-wide mb-1.5">Tên người nhận <span class="text-error">*</span></label>
                        <input id="receiver_name_input" name="receiver_name" type="text" required class="premium-input" placeholder="Họ và tên"/>
                        <div class="error-msg text-error text-[11px] mt-1 hidden">Vui lòng nhập tên người nhận</div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-on-surface-variant uppercase tracking-wide mb-1.5">Số điện thoại <span class="text-error">*</span></label>
                        <input id="receiver_phone_input" name="receiver_phone" type="tel" required pattern="(84|0[3|5|7|8|9])[0-9]{8}" class="premium-input" placeholder="VD: 0912345678"/>
                        <div class="error-msg text-error text-[11px] mt-1 hidden">Số điện thoại không hợp lệ</div>
                    </div>
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-on-surface-variant uppercase tracking-wide mb-1.5">Tỉnh / Thành phố <span class="text-error">*</span></label>
                    <select id="province_select" name="province" required class="no-choices premium-input bg-white cursor-pointer">
                        <option value="">Chọn Tỉnh/Thành phố</option>
                    </select>
                    <div class="error-msg text-error text-[11px] mt-1 hidden">Vui lòng chọn Tỉnh/Thành phố</div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-semibold text-on-surface-variant uppercase tracking-wide mb-1.5">Quận / Huyện <span class="text-error">*</span></label>
                        <select id="district_select" name="district" required disabled class="no-choices premium-input bg-white cursor-pointer disabled:bg-gray-100 disabled:cursor-not-allowed">
                            <option value="">Chọn Quận/Huyện</option>
                        </select>
                        <div class="error-msg text-error text-[11px] mt-1 hidden">Vui lòng chọn Quận/Huyện</div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-on-surface-variant uppercase tracking-wide mb-1.5">Phường / Xã <span class="text-error">*</span></label>
                        <select id="ward_select" name="ward" required disabled class="no-choices premium-input bg-white cursor-pointer disabled:bg-gray-100 disabled:cursor-not-allowed">
                            <option value="">Chọn Phường/Xã</option>
                        </select>
                        <div class="error-msg text-error text-[11px] mt-1 hidden">Vui lòng chọn Phường/Xã</div>
                    </div>
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-on-surface-variant uppercase tracking-wide mb-1.5">Địa chỉ cụ thể <span class="text-error">*</span></label>
                    <input id="address_input" name="address" type="text" required class="premium-input" placeholder="Số nhà, tên đường..."/>
                    <div class="error-msg text-error text-[11px] mt-1 hidden">Vui lòng nhập địa chỉ cụ thể</div>
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-on-surface-variant uppercase tracking-wide mb-1.5">Ghi chú (tùy chọn)</label>
                    <input id="note_input" name="note" type="text" class="premium-input" placeholder="VD: Cổng màu xanh, tầng 3"/>
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
    document.getElementById('address-modal-title').innerText = 'Thêm địa chỉ mới';
    document.getElementById('address-form').action = '{{ route("customer.address.store") }}';
    document.getElementById('address-method').value = 'POST';
    
    document.getElementById('address-form').reset();
    
    // Clear validation UI
    const inputs = document.getElementById('address-form').querySelectorAll('input, select');
    inputs.forEach(input => {
        input.classList.remove('border-error');
        const errorMsg = input.nextElementSibling;
        if (errorMsg && errorMsg.classList.contains('error-msg')) {
            errorMsg.classList.add('hidden');
        }
    });
    
    // Suggest user's name and phone
    document.getElementById('receiver_name_input').value = '{{ htmlspecialchars($user->name, ENT_QUOTES) }}';
    document.getElementById('receiver_phone_input').value = '{{ htmlspecialchars($user->phone ?? "", ENT_QUOTES) }}';
    
    document.getElementById('district_select').innerHTML = '<option value="">Chọn Quận/Huyện</option>';
    document.getElementById('district_select').disabled = true;
    document.getElementById('ward_select').innerHTML = '<option value="">Chọn Phường/Xã</option>';
    document.getElementById('ward_select').disabled = true;

    // Force Ninh Binh and load districts
    const pSelect = document.getElementById('province_select');
    if (pSelect) {
        if (pSelect.tomselect) pSelect.tomselect.destroy();
        pSelect.innerHTML = '<option value="Tỉnh Ninh Bình" data-code="37" selected>Tỉnh Ninh Bình</option>';
        pSelect.dispatchEvent(new Event('change'));
    }

    document.getElementById('address-modal').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function editAddress(id, name, phone, province, district, ward, address, note, is_default) {
    document.getElementById('address-modal-title').innerText = 'Sửa địa chỉ';
    document.getElementById('address-form').action = '/customer/address/' + id;
    document.getElementById('address-method').value = 'PUT';
    
    // Clear validation UI
    const inputs = document.getElementById('address-form').querySelectorAll('input, select');
    inputs.forEach(input => {
        input.classList.remove('border-error');
        const errorMsg = input.nextElementSibling;
        if (errorMsg && errorMsg.classList.contains('error-msg')) {
            errorMsg.classList.add('hidden');
        }
    });
    
    // Set values
    document.getElementById('receiver_name_input').value = name;
    document.getElementById('receiver_phone_input').value = phone;
    document.getElementById('address_input').value = address;
    document.getElementById('note_input').value = note;
    document.getElementById('default-addr-check').checked = is_default;
    
    // Set selects (Force Ninh Binh)
    const pSelect = document.getElementById('province_select');
    if (pSelect.tomselect) pSelect.tomselect.destroy();
    pSelect.innerHTML = '<option value="Tỉnh Ninh Bình" data-code="37" selected>Tỉnh Ninh Bình</option>';
    // We don't dispatch change immediately because district/ward are set manually below.
    // However, if we need districts loaded for TomSelect, we should fetch them.
    // Since district and ward are injected below, it works visually.
    
    // To allow user to change district/ward later, we must trigger change.
    pSelect.dispatchEvent(new Event('change'));
    
    const dSelect = document.getElementById('district_select');
    dSelect.innerHTML = `<option value="${district}" selected>${district}</option>`;
    dSelect.disabled = false;
    
    const wSelect = document.getElementById('ward_select');
    wSelect.innerHTML = `<option value="${ward}" selected>${ward}</option>`;
    wSelect.disabled = false;

    document.getElementById('address-modal').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeAddressModal() {
    document.getElementById('address-modal').classList.remove('open');
    document.body.style.overflow = '';
}

async function validateProfileForm(event) {
    const form = event.target;
    event.preventDefault(); // Always prevent default for AJAX
    
    // 1. Client-side validation
    let isValid = form.checkValidity();
    
    // Clear previous UI errors
    const inputs = form.querySelectorAll('input, select');
    inputs.forEach(input => {
        const errorMsg = input.parentElement.querySelector('.error-msg');
        if (errorMsg) {
            errorMsg.classList.add('hidden');
            input.classList.remove('border-error');
            if (input.tagName === 'SELECT' && input.nextElementSibling && input.nextElementSibling.classList.contains('ts-wrapper')) {
                input.nextElementSibling.querySelector('.ts-control').classList.remove('!border-error');
            }
        }
    });

    if (!isValid) {
        // Show HTML5 validation errors
        inputs.forEach(input => {
            const errorMsg = input.parentElement.querySelector('.error-msg');
            if (errorMsg && !input.validity.valid) {
                errorMsg.classList.remove('hidden');
                if (input.tagName === 'SELECT' && input.nextElementSibling && input.nextElementSibling.classList.contains('ts-wrapper')) {
                    input.nextElementSibling.querySelector('.ts-control').classList.add('!border-error');
                } else {
                    input.classList.add('border-error');
                }
                
                input.addEventListener('input', function() {
                    if (this.validity.valid) {
                        errorMsg.classList.add('hidden');
                        this.classList.remove('border-error');
                        if (this.tagName === 'SELECT' && this.nextElementSibling && this.nextElementSibling.classList.contains('ts-wrapper')) {
                            this.nextElementSibling.querySelector('.ts-control').classList.remove('!border-error');
                        }
                    }
                }, { once: true });
                
                input.addEventListener('change', function() {
                    if (this.validity.valid) {
                        errorMsg.classList.add('hidden');
                        this.classList.remove('border-error');
                        if (this.tagName === 'SELECT' && this.nextElementSibling && this.nextElementSibling.classList.contains('ts-wrapper')) {
                            this.nextElementSibling.querySelector('.ts-control').classList.remove('!border-error');
                        }
                    }
                }, { once: true });
            }
        });
        return false;
    }

    // 2. AJAX Submission
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalBtnHtml = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="material-symbols-outlined animate-spin">refresh</span> Đang xử lý...';

    try {
        const formData = new FormData(form);
        const url = form.action;

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value
            },
            body: formData
        });

        if (response.ok) {
            // Success! Reload to show updated profile
            window.location.reload();
        } else if (response.status === 422) {
            // Validation Error
            const data = await response.json();
            const errors = data.errors;
            
            for (let field in errors) {
                // Find input by name
                const input = form.querySelector(`[name="${field}"]`);
                if (input) {
                    const errorMsg = input.parentElement.querySelector('.error-msg');
                    if (errorMsg) {
                        errorMsg.textContent = errors[field][0]; // Update text to server message
                        errorMsg.classList.remove('hidden');
                        
                        if (input.tagName === 'SELECT' && input.nextElementSibling && input.nextElementSibling.classList.contains('ts-wrapper')) {
                            input.nextElementSibling.querySelector('.ts-control').classList.add('!border-error');
                        } else {
                            input.classList.add('border-error');
                        }
                    }
                }
            }
        } else {
            console.error("Lỗi server");
        }
    } catch (error) {
        console.error(error);
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnHtml;
    }

    return false;
}

async function validateAddressForm(event) {
    const form = event.target;
    event.preventDefault(); // Always prevent default for AJAX
    
    // 1. Client-side validation
    let isValid = form.checkValidity();
    
    // Clear previous UI errors
    const inputs = form.querySelectorAll('input, select');
    inputs.forEach(input => {
        const errorMsg = input.parentElement.querySelector('.error-msg');
        if (errorMsg) {
            errorMsg.classList.add('hidden');
            input.classList.remove('border-error');
            if (input.tagName === 'SELECT' && input.nextElementSibling && input.nextElementSibling.classList.contains('ts-wrapper')) {
                input.nextElementSibling.querySelector('.ts-control').classList.remove('!border-error');
            }
        }
    });

    if (!isValid) {
        // Show HTML5 validation errors
        inputs.forEach(input => {
            const errorMsg = input.parentElement.querySelector('.error-msg');
            if (errorMsg && !input.validity.valid) {
                errorMsg.classList.remove('hidden');
                // You can keep default messages or let HTML5 handle it. Since we have static messages, they show up.
                if (input.tagName === 'SELECT' && input.nextElementSibling && input.nextElementSibling.classList.contains('ts-wrapper')) {
                    input.nextElementSibling.querySelector('.ts-control').classList.add('!border-error');
                } else {
                    input.classList.add('border-error');
                }
                
                input.addEventListener('input', function() {
                    if (this.validity.valid) {
                        errorMsg.classList.add('hidden');
                        this.classList.remove('border-error');
                        if (this.tagName === 'SELECT' && this.nextElementSibling && this.nextElementSibling.classList.contains('ts-wrapper')) {
                            this.nextElementSibling.querySelector('.ts-control').classList.remove('!border-error');
                        }
                    }
                }, { once: true });
                
                input.addEventListener('change', function() {
                    if (this.validity.valid) {
                        errorMsg.classList.add('hidden');
                        this.classList.remove('border-error');
                        if (this.tagName === 'SELECT' && this.nextElementSibling && this.nextElementSibling.classList.contains('ts-wrapper')) {
                            this.nextElementSibling.querySelector('.ts-control').classList.remove('!border-error');
                        }
                    }
                }, { once: true });
            }
        });
        return false;
    }

    // 2. AJAX Submission
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalBtnHtml = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="material-symbols-outlined animate-spin">refresh</span> Đang xử lý...';

    try {
        const formData = new FormData(form);
        const url = form.action;
        // Append _method if PUT
        const methodInput = document.getElementById('address-method');
        const submitMethod = methodInput ? methodInput.value : 'POST';

        const response = await fetch(url, {
            method: 'POST', // always POST for FormData, Laravel uses _method
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value
            },
            body: formData
        });

        if (response.ok) {
            // Success! Reload to show updated addresses
            window.location.reload();
        } else if (response.status === 422) {
            // Validation Error
            const data = await response.json();
            const errors = data.errors;
            
            for (let field in errors) {
                // Find input by name
                const input = form.querySelector(`[name="${field}"]`);
                if (input) {
                    const errorMsg = input.parentElement.querySelector('.error-msg');
                    if (errorMsg) {
                        errorMsg.textContent = errors[field][0]; // Update text to server message
                        errorMsg.classList.remove('hidden');
                        
                        if (input.tagName === 'SELECT' && input.nextElementSibling && input.nextElementSibling.classList.contains('ts-wrapper')) {
                            input.nextElementSibling.querySelector('.ts-control').classList.add('!border-error');
                        } else {
                            input.classList.add('border-error');
                        }
                    }
                }
            }
        } else {
            console.error("Lỗi server");
        }
    } catch (error) {
        console.error(error);
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnHtml;
    }

    return false;
}

document.addEventListener('DOMContentLoaded', function() {
    const selectProvince = document.getElementById('province_select');
    const selectDistrict = document.getElementById('district_select');
    const selectWard = document.getElementById('ward_select');
    
    if (selectProvince) {
        // Force Ninh Binh as province
        selectProvince.innerHTML = '<option value="Tỉnh Ninh Bình" data-code="37" selected>Tỉnh Ninh Bình</option>';
        selectProvince.classList.add('pointer-events-none', 'bg-surface-variant', 'text-on-surface-variant', 'opacity-80');
        
        selectProvince.addEventListener('change', function() {
            selectDistrict.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
            selectWard.innerHTML = '<option value="">Chọn Phường/Xã</option>';
            selectDistrict.disabled = true;
            selectWard.disabled = true;
            
            const selectedOption = this.options[this.selectedIndex];
            
            if (this.value) {
                selectDistrict.disabled = false;
                selectDistrict.options[0].text = 'Đang tải...';
                
                Promise.all([
                    fetch(`https://esgoo.net/api-tinhthanh/2/35.htm`).then(r => r.json()),
                    fetch(`https://esgoo.net/api-tinhthanh/2/36.htm`).then(r => r.json()),
                    fetch(`https://esgoo.net/api-tinhthanh/2/37.htm`).then(r => r.json())
                ]).then(results => {
                    selectDistrict.options[0].text = 'Chọn Quận/Huyện';
                    let allDistricts = [];
                    results.forEach(res => {
                        if (res.error === 0) {
                            allDistricts = allDistricts.concat(res.data);
                        }
                    });
                    
                    // Sort alphabetically
                    allDistricts.sort((a, b) => a.full_name.localeCompare(b.full_name));
                    
                    allDistricts.forEach(d => {
                        const option = document.createElement('option');
                        option.value = d.full_name;
                        option.text = d.full_name;
                        option.dataset.code = d.id;
                        selectDistrict.appendChild(option);
                    });
                }).catch(err => {
                    console.error('Lỗi khi tải quận/huyện:', err);
                    selectDistrict.options[0].text = 'Lỗi tải dữ liệu';
                });
            }
        });
        
        // Trigger fetch districts for Ninh Binh
        selectProvince.dispatchEvent(new Event('change'));

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
</script>
@endpush
