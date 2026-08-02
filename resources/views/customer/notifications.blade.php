@extends('layouts.customer')

@section('title', 'Notifications')

@section('content')
<main class="pt-24 pb-12 px-8 max-w-[1280px] mx-auto min-h-screen">
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
<!-- Left Side: Notifications List (8 columns) -->
<div class="lg:col-span-8 space-y-6">
<!-- Header Area -->
<div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
<div>
<h1 class="font-headline-lg text-headline-lg text-on-surface">Notifications</h1>
<p class="text-on-surface-variant font-body-md text-body-md mt-1">Stay updated with your orders and exclusive rewards.</p>
</div>
<button class="flex items-center gap-2 px-4 py-2 text-primary hover:bg-primary/5 transition-colors rounded-lg font-label-md text-label-md">
<span class="material-symbols-outlined text-sm" data-icon="done_all">done_all</span>
                        Mark all as read
                    </button>
</div>
<!-- Tabs Filter -->
<div class="flex border-b border-outline-variant gap-8 overflow-x-auto pb-px custom-scrollbar">
<button class="pb-3 border-b-2 border-primary text-primary font-label-md text-label-md whitespace-nowrap">All</button>
<button class="pb-3 border-b-2 border-transparent text-on-surface-variant hover:text-primary transition-colors font-label-md text-label-md whitespace-nowrap">Đơn hàng</button>
<button class="pb-3 border-b-2 border-transparent text-on-surface-variant hover:text-primary transition-colors font-label-md text-label-md whitespace-nowrap">Offers</button>
<button class="pb-3 border-b-2 border-transparent text-on-surface-variant hover:text-primary transition-colors font-label-md text-label-md whitespace-nowrap">Account</button>
</div>
<!-- Notification Cards -->
<div class="space-y-4">
@forelse($notifications as $notification)
<div class="group relative {{ $notification->is_read ? 'bg-white/60 opacity-80' : 'bg-white shadow-[0px_4px_6px_-1px_rgba(0,0,0,0.05)]' }} rounded-xl p-4 border border-outline-variant hover:opacity-100 hover:shadow-md transition-all flex gap-4">
    @if(!$notification->is_read)
    <div class="absolute top-4 right-4 flex items-center gap-2">
        <span class="w-2 h-2 rounded-full bg-primary-container"></span>
    </div>
    @endif
    <div class="flex-shrink-0 w-12 h-12 {{ $notification->type == 'system' ? 'bg-tertiary-container/10 text-tertiary' : 'bg-primary-container/10 text-primary' }} flex items-center justify-center rounded-full">
        <span class="material-symbols-outlined">{{ $notification->type == 'system' ? 'campaign' : 'notifications' }}</span>
    </div>
    <div class="flex-grow">
        <div class="flex justify-between items-start mb-1">
            <h3 class="font-title-lg text-body-lg font-bold text-on-surface">{{ $notification->title }}</h3>
            <span class="text-on-surface-variant font-label-sm text-label-sm">{{ $notification->created_at->diffForHumans() }}</span>
        </div>
        <p class="text-on-surface-variant font-body-md text-body-md mb-2">{{ $notification->content }}</p>
    </div>
</div>
@empty
<div class="text-center py-8">
    <p class="text-on-surface-variant">Bạn chưa có thông báo nào.</p>
</div>
@endforelse
</div>
</div>
<!-- Right Side: Preferences (4 columns) -->
<div class="lg:col-span-4 space-y-6">
<div class="bg-white rounded-2xl border border-outline-variant p-6 sticky top-24 shadow-[0px_4px_6px_-1px_rgba(0,0,0,0.05)]">
<div class="flex items-center gap-2 mb-6">
<span class="material-symbols-outlined text-primary" data-icon="settings">settings</span>
<h2 class="font-title-lg text-title-lg text-on-surface">Preferences</h2>
</div>
<!-- Channels -->
<div class="space-y-6">
<div>
<h3 class="font-label-sm text-label-sm uppercase tracking-wider text-on-surface-variant mb-4">Notification Channels</h3>
<div class="space-y-4">
<div class="flex items-center justify-between">
<span class="font-body-md text-body-md">Email Notifications</span>
<label class="relative inline-flex items-center cursor-pointer">
<input checked="" class="sr-only peer" type="checkbox"/>
<div class="w-11 h-6 bg-outline-variant peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-container"></div>
</label>
</div>
<div class="flex items-center justify-between">
<span class="font-body-md text-body-md">Push Notifications</span>
<label class="relative inline-flex items-center cursor-pointer">
<input checked="" class="sr-only peer" type="checkbox"/>
<div class="w-11 h-6 bg-outline-variant peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-container"></div>
</label>
</div>
<div class="flex items-center justify-between">
<span class="font-body-md text-body-md">SMS Alerts</span>
<label class="relative inline-flex items-center cursor-pointer">
<input class="sr-only peer" type="checkbox"/>
<div class="w-11 h-6 bg-outline-variant peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-container"></div>
</label>
</div>
</div>
</div>
<hr class="border-outline-variant"/>
<!-- Categories -->
<div>
<h3 class="font-label-sm text-label-sm uppercase tracking-wider text-on-surface-variant mb-4">Topic Preferences</h3>
<div class="space-y-4">
<div class="flex items-center justify-between">
<span class="font-body-md text-body-md">Order Updates</span>
<input checked="" class="rounded border-outline-variant text-primary focus:ring-primary h-5 w-5" type="checkbox"/>
</div>
<div class="flex items-center justify-between">
<span class="font-body-md text-body-md">Khuyến mãi</span>
<input checked="" class="rounded border-outline-variant text-primary focus:ring-primary h-5 w-5" type="checkbox"/>
</div>
<div class="flex items-center justify-between">
<span class="font-body-md text-body-md">Loyalty Rewards</span>
<input checked="" class="rounded border-outline-variant text-primary focus:ring-primary h-5 w-5" type="checkbox"/>
</div>
<div class="flex items-center justify-between">
<span class="font-body-md text-body-md">Account Giâyurity</span>
<input checked="" class="rounded border-outline-variant text-primary focus:ring-primary h-5 w-5" disabled="" type="checkbox"/>
</div>
</div>
</div>
<button class="w-full py-3 bg-surface-container hover:bg-surface-container-high transition-colors text-primary font-bold rounded-xl active:scale-[0.98] mt-4">
                            Lưu Thay Đổi
                        </button>
</div>
</div>
<!-- Promotional Card -->
<div class="relative overflow-hidden bg-primary-container rounded-2xl p-6 text-white group">

<div class="relative z-10">
<h4 class="font-title-lg text-title-lg mb-2">Exclusive Deal</h4>
<p class="font-body-md text-body-md opacity-90 mb-4">Refer a friend and both get 50% off your next order.</p>
<button class="px-6 py-2 bg-white text-primary font-bold rounded-full text-label-md active:scale-95 transition-transform">
                            Invite Friends
                        </button>
</div>
</div>
</div>
</div>
</main>
@endsection

@push('scripts')
<script>

        // Tab Filtering Logic (Simulated)
        const tabs = document.querySelectorAll('.tabs-filter button');
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => {
                    t.classList.remove('border-primary', 'text-primary');
                    t.classList.add('border-transparent', 'text-on-surface-variant');
                });
                tab.classList.add('border-primary', 'text-primary');
                tab.classList.remove('border-transparent', 'text-on-surface-variant');
            });
        });

        // Simple animation on scroll
        const observerOptions = {
            threshold: 0.1
        };
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('opacity-100', 'translate-y-0');
                    entry.target.classList.remove('opacity-0', 'translate-y-4');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.group').forEach(el => {
            el.classList.add('transition-all', 'duration-500', 'opacity-0', 'translate-y-4');
            observer.observe(el);
        });
    
</script>
@endpush
