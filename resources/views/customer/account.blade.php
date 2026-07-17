@extends('layouts.customer')

@section('title', 'Account')

@section('content')
<main class="flex-grow pt-24 pb-24 md:pb-8 p-md md:p-xl bg-background w-full">
<!-- Profile Tab Content -->
<section class="max-w-4xl mx-auto space-y-xl" id="content-profile">
    <form method="POST" action="/customer/account/update" enctype="multipart/form-data">
        @csrf
        <div class="mb-lg">
            <h1 class="font-headline-lg text-on-surface">Thông tin cá nhân</h1>
        </div>
        
        @if(session('success'))
            <div class="bg-primary-container text-on-primary-container p-md rounded-lg mb-lg">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="bg-error-container text-on-error-container p-md rounded-lg mb-lg">
                <ul class="list-disc ml-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-lg">
            <!-- Avatar Card -->
            <div class="glass-card p-lg rounded-xl flex flex-col items-center text-center">
                <div class="relative w-32 h-32 mb-md">
                    <img id="avatar-preview" class="w-full h-full object-cover rounded-full border-4 border-primary/10" src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name ?: $user->username).'&background=random&size=128' }}" />
                    <label class="absolute bottom-0 right-0 bg-primary text-on-primary p-2 rounded-full shadow-lg cursor-pointer hover:bg-on-primary-fixed-variant transition-colors">
                        <span class="material-symbols-outlined text-sm">edit</span>
                        <input type="file" name="avatar" class="hidden" accept="image/*" onchange="document.getElementById('avatar-preview').src = window.URL.createObjectURL(this.files[0])" />
                    </label>
                </div>
                <h3 class="font-title-lg">{{ $user->name ?: $user->username }}</h3>
                <p class="font-body-md text-on-surface-variant">Thành viên từ {{ $user->created_at->format('M Y') }}</p>
                @if($user->google_id)
                    <p class="font-label-sm mt-2 text-primary bg-primary/10 px-2 py-1 rounded-md inline-block">Đăng nhập bằng Google</p>
                @endif
            </div>

            <!-- Details Card -->
            <div class="md:col-span-2 glass-card p-lg rounded-xl space-y-md">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                    <div class="space-y-base">
                        <label class="font-label-sm text-on-surface-variant ml-1">Họ và Tên</label>
                        <input name="name" class="w-full bg-surface border border-outline-variant/50 rounded-lg px-md py-2 text-body-md focus:ring-primary focus:border-primary" type="text" value="{{ old('name', $user->name) }}"/>
                    </div>
                    <div class="space-y-base">
                        <label class="font-label-sm text-on-surface-variant ml-1">Địa chỉ Email</label>
                        <input name="email" class="w-full bg-surface border border-outline-variant/50 rounded-lg px-md py-2 text-body-md focus:ring-primary focus:border-primary {{ $user->google_id ? 'opacity-70 cursor-not-allowed' : '' }}" type="email" value="{{ old('email', $user->email) }}" {{ $user->google_id ? 'readonly' : '' }}/>
                    </div>
                    <div class="space-y-base">
                        <label class="font-label-sm text-on-surface-variant ml-1">Số điện thoại</label>
                        <input name="phone" class="w-full bg-surface border border-outline-variant/50 rounded-lg px-md py-2 text-body-md focus:ring-primary focus:border-primary" type="tel" value="{{ old('phone', $user->phone) }}"/>
                    </div>
                    <div class="space-y-base">
                        <label class="font-label-sm text-on-surface-variant ml-1">Tên đăng nhập</label>
                        <input name="username" class="w-full bg-surface border border-outline-variant/50 rounded-lg px-md py-2 text-body-md focus:ring-primary focus:border-primary" type="text" value="{{ old('username', $user->username) }}"/>
                    </div>
                </div>
            </div>
        </div>

        <!-- Security Section -->
        <div class="glass-card p-lg rounded-xl mt-lg">
            <h3 class="font-title-lg mb-md flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">security</span>
                    Bảo mật &amp; Đăng nhập
                </div>
                <a href="/logout" class="bg-error/10 text-error px-md py-2 rounded-full font-label-md hover:bg-error hover:text-white transition-colors flex items-center gap-1">
                    <span class="material-symbols-outlined text-[18px]">logout</span>
                    Đăng xuất
                </a>
            </h3>
            
            @if(!$user->google_id)
            <div class="p-md bg-surface-container-low rounded-lg space-y-md">
                <div class="flex items-center gap-md mb-2">
                    <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined">password</span>
                    </div>
                    <div>
                        <p class="font-body-md font-bold">Đổi Mật Khẩu</p>
                        <p class="font-label-md text-on-surface-variant">Để trống nếu không muốn đổi</p>
                    </div>
                </div>
                <input name="new_password" class="w-full max-w-md bg-surface border border-outline-variant/50 rounded-lg px-md py-2 text-body-md focus:ring-primary focus:border-primary" type="password" placeholder="Nhập mật khẩu mới..."/>
            </div>
            @else
            <div class="p-md bg-surface-container-low rounded-lg flex items-center gap-md">
                <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined">g_translate</span>
                </div>
                <div>
                    <p class="font-body-md font-bold">Mật khẩu được quản lý bởi Google</p>
                    <p class="font-label-md text-on-surface-variant">Tài khoản của bạn đăng nhập thông qua Google nên không cần thiết lập mật khẩu ở đây.</p>
                </div>
            </div>
            @endif
        </div>

        <div class="mt-xl flex justify-end">
            <button type="submit" class="bg-primary text-on-primary px-xl py-3 rounded-full font-title-lg hover:opacity-90 transition-opacity active:scale-95 shadow-md flex items-center gap-2">
                <span class="material-symbols-outlined">save</span>
                Lưu Thay Đổi
            </button>
        </div>
    </form>
</section>
<!-- Addresses Tab Content (Hidden by default) -->
<section class="hidden max-w-4xl mx-auto space-y-xl" id="content-addresses">
<div class="flex items-center justify-between">
<h1 class="font-headline-lg text-on-surface">Saved Addresses</h1>
<button class="bg-primary text-on-primary px-lg py-2 rounded-full font-label-md flex items-center gap-2">
<span class="material-symbols-outlined text-sm">add</span> Thêm Mới
                    </button>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-lg">
<!-- Address Card 1 -->
<div class="glass-card p-lg rounded-xl border-2 border-primary/20 relative">
<div class="absolute top-4 right-4 flex gap-base">
<button class="p-1.5 hover:bg-surface-variant rounded-full text-on-surface-variant transition-colors">
<span class="material-symbols-outlined text-sm">edit</span>
</button>
<button class="p-1.5 hover:bg-error-container rounded-full text-error transition-colors">
<span class="material-symbols-outlined text-sm">delete</span>
</button>
</div>
<div class="flex items-start gap-md">
<div class="p-2 bg-primary/10 text-primary rounded-lg">
<span class="material-symbols-outlined">home</span>
</div>
<div class="space-y-base">
<p class="font-title-lg">Home</p>
<p class="font-body-md text-on-surface-variant">123 Green Valley Road,<br/>Springfield, OR 97477</p>
<div class="inline-block bg-primary/10 text-primary text-[10px] font-bold uppercase tracking-widest px-2 py-0.5 rounded">Default</div>
</div>
</div>
</div>
<!-- Address Card 2 -->
<div class="glass-card p-lg rounded-xl hover:shadow-md transition-shadow relative">
<div class="absolute top-4 right-4 flex gap-base">
<button class="p-1.5 hover:bg-surface-variant rounded-full text-on-surface-variant transition-colors">
<span class="material-symbols-outlined text-sm">edit</span>
</button>
<button class="p-1.5 hover:bg-error-container rounded-full text-error transition-colors">
<span class="material-symbols-outlined text-sm">delete</span>
</button>
</div>
<div class="flex items-start gap-md">
<div class="p-2 bg-secondary/10 text-secondary rounded-lg">
<span class="material-symbols-outlined">work</span>
</div>
<div class="space-y-base">
<p class="font-title-lg">Office</p>
<p class="font-body-md text-on-surface-variant">Tech Plaza North, Suite 400<br/>Portland, OR 97204</p>
</div>
</div>
</div>
</div>
<div class="rounded-xl overflow-hidden h-64 shadow-sm grayscale opacity-80">
<div class="w-full h-full bg-cover bg-center" data-location="Oregon" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuD8VEXSABKTih9q9Ks6a_nkcxCy_FXge-o1pUcklkI3rbqxXD5ytq6wQlzmMGF-Nw8nPALdUbiFkMUbnTvW_IiUIAw_RcuIGEme6aSwNJijTjNZHCooR6aqLrcqG3rcxRkFFDm-a4rGHdryV7wysBaKLhm-DzUNvs7pKDW7Wevm4bJoJeMf13m3Da5B0CVItifXztAXIO0wYAHUNYjG0bkAgd50R-2nmUb3YXqupUUMvZQJqd146rLj029LrNd3YtnBq_1Xv0dL')"></div>
</div>
</section>
<!-- Vouchers Tab Content (Hidden by default) -->
<section class="hidden max-w-4xl mx-auto space-y-xl" id="content-vouchers">
<div class="flex items-center justify-between">
<h1 class="font-headline-lg text-on-surface">Rewards &amp; Vouchers</h1>
<div class="flex gap-md">
<input class="bg-surface border-outline-variant/30 rounded-full px-md text-body-md w-48" placeholder="Enter promo code" type="text"/>
<button class="bg-secondary text-on-secondary px-lg py-2 rounded-full font-label-md">Redeem</button>
</div>
</div>
<div class="space-y-md">
<!-- Voucher 1 -->
<div class="flex overflow-hidden rounded-xl border border-outline-variant/20 shadow-sm">
<div class="w-32 bg-primary flex flex-col items-center justify-center text-on-primary p-md relative">
<div class="absolute -right-2 top-0 bottom-0 w-4 flex flex-col justify-between py-1">
<div class="w-2 h-2 rounded-full bg-background -mr-1"></div>
<div class="w-2 h-2 rounded-full bg-background -mr-1"></div>
<div class="w-2 h-2 rounded-full bg-background -mr-1"></div>
<div class="w-2 h-2 rounded-full bg-background -mr-1"></div>
</div>
<span class="font-display-lg">20<span class="text-title-lg">%</span></span>
<span class="font-label-sm uppercase tracking-widest opacity-80">Off</span>
</div>
<div class="flex-grow bg-white p-lg flex justify-between items-center">
<div>
<h4 class="font-title-lg text-on-surface">Organic Morning Special</h4>
<p class="font-body-md text-on-surface-variant">Applicable to all cold brews before 11 AM.</p>
<p class="font-label-md text-primary mt-2">Expires in 3 days</p>
</div>
<button class="bg-primary/10 text-primary px-lg py-2 rounded-full font-label-md hover:bg-primary hover:text-on-primary transition-all">Apply Now</button>
</div>
</div>
<!-- Voucher 2 -->
<div class="flex overflow-hidden rounded-xl border border-outline-variant/20 shadow-sm opacity-60 grayscale">
<div class="w-32 bg-on-surface-variant flex flex-col items-center justify-center text-on-primary p-md relative">
<div class="absolute -right-2 top-0 bottom-0 w-4 flex flex-col justify-between py-1">
<div class="w-2 h-2 rounded-full bg-background -mr-1"></div>
<div class="w-2 h-2 rounded-full bg-background -mr-1"></div>
</div>
<span class="font-display-lg text-title-lg">BOGO</span>
</div>
<div class="flex-grow bg-white p-lg flex justify-between items-center">
<div>
<h4 class="font-title-lg text-on-surface">Welcome Reward</h4>
<p class="font-body-md text-on-surface-variant">Buy one matcha latte, get one free.</p>
<p class="font-label-md text-on-surface-variant mt-2">Expired Dec 1, 2023</p>
</div>
<span class="font-label-sm text-on-surface-variant border border-outline-variant px-md py-1 rounded">Used</span>
</div>
</div>
</div>
</section>
<!-- Notifications Tab Content (Hidden by default) -->
<section class="hidden max-w-4xl mx-auto space-y-xl" id="content-notifications">
<h1 class="font-headline-lg text-on-surface">Notification Settings</h1>
<div class="glass-card rounded-xl divide-y divide-outline-variant/20">
<div class="p-lg flex items-center justify-between">
<div>
<h4 class="font-title-lg">Push Notifications</h4>
<p class="font-body-md text-on-surface-variant">Receive alerts about order status and delivery updates.</p>
</div>
<div class="relative inline-flex items-center cursor-pointer">
<input checked="" class="sr-only peer" type="checkbox"/>
<div class="w-11 h-6 bg-outline-variant rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
</div>
</div>
<div class="p-lg flex items-center justify-between">
<div>
<h4 class="font-title-lg">Email Marketing</h4>
<p class="font-body-md text-on-surface-variant">Weekly newsletters, seasonal launches, and exclusive deals.</p>
</div>
<div class="relative inline-flex items-center cursor-pointer">
<input class="sr-only peer" type="checkbox"/>
<div class="w-11 h-6 bg-outline-variant rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
</div>
</div>
<div class="p-lg flex items-center justify-between">
<div>
<h4 class="font-title-lg">SMS Alerts</h4>
<p class="font-body-md text-on-surface-variant">Quick texts for ready-for-pickup orders only.</p>
</div>
<div class="relative inline-flex items-center cursor-pointer">
<input checked="" class="sr-only peer" type="checkbox"/>
<div class="w-11 h-6 bg-outline-variant rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
</div>
</div>
</div>
</section>
<!-- Membership Tab Content (Hidden by default) -->
<section class="hidden max-w-4xl mx-auto space-y-xl" id="content-membership">
<div class="glass-card rounded-2xl overflow-hidden p-0">
<div class="bg-gradient-to-r from-primary to-primary-container p-xl text-on-primary">
<div class="flex justify-between items-start">
<div>
<h4 class="font-label-sm uppercase tracking-widest mb-base">Current Level</h4>
<h1 class="font-display-lg leading-none">Gold Tier</h1>
</div>
<div class="p-4 bg-white/20 backdrop-blur rounded-xl border border-white/30 text-center">
<span class="block font-display-lg leading-none">8,550</span>
<span class="font-label-sm uppercase tracking-widest">Available Points</span>
</div>
</div>
</div>
<div class="p-xl bg-white space-y-lg">
<div>
<div class="flex justify-between font-label-md mb-2">
<span class="text-on-surface-variant">Progress to Platinum</span>
<span class="text-primary font-bold">75%</span>
</div>
<div class="w-full bg-surface-container-low h-3 rounded-full overflow-hidden">
<div class="bg-primary h-full rounded-full transition-all duration-1000 ease-out" style="width: 75%"></div>
</div>
<p class="font-body-md text-on-surface-variant mt-md">You need 2,450 more points to unlock free express delivery and private events.</p>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-md">
<div class="p-md rounded-xl bg-surface-container-lowest border border-outline-variant/10 text-center space-y-base">
<span class="material-symbols-outlined text-primary text-3xl">local_cafe</span>
<h5 class="font-title-lg">Free Upsize</h5>
<p class="font-label-md text-on-surface-variant">On any morning order</p>
</div>
<div class="p-md rounded-xl bg-surface-container-lowest border border-outline-variant/10 text-center space-y-base">
<span class="material-symbols-outlined text-primary text-3xl">event</span>
<h5 class="font-title-lg">Early Access</h5>
<p class="font-label-md text-on-surface-variant">To seasonal menu drops</p>
</div>
<div class="p-md rounded-xl bg-surface-container-lowest border border-outline-variant/10 text-center space-y-base">
<span class="material-symbols-outlined text-primary text-3xl">cake</span>
<h5 class="font-title-lg">Birthday Treat</h5>
<p class="font-label-md text-on-surface-variant">Complimentary pastry</p>
</div>
</div>
</div>
</div>
</section>
</main>
@endsection

@push('scripts')
<script>

        function switchTab(tabId) {
            // Hide all content sections
            const sections = ['profile', 'addresses', 'vouchers', 'notifications', 'membership'];
            sections.forEach(id => {
                document.getElementById(`content-${id}`).classList.add('hidden');
                const navItem = document.getElementById(`nav-${id}`);
                if (navItem) {
                    navItem.classList.remove('active-tab');
                    navItem.classList.add('text-on-surface-variant');
                }
            });

            // Show active content
            document.getElementById(`content-${tabId}`).classList.remove('hidden');
            
            // Highlight active nav item
            const activeNav = document.getElementById(`nav-${tabId}`);
            if (activeNav) {
                activeNav.classList.add('active-tab');
                activeNav.classList.remove('text-on-surface-variant');
            }

            // Scroll to top of content
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Phúti animation for the membership bar on load
        window.addEventListener('DOMContentLoaded', () => {
            const progressBars = document.querySelectorAll('.bg-primary.h-full');
            progressBars.forEach(bar => {
                const targetWidth = bar.style.width;
                bar.style.width = '0%';
                setTimeout(() => {
                    bar.style.width = targetWidth;
                }, 300);
            });
        });
    
</script>
@endpush
