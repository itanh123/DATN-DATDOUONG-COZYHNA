@extends('layouts.customer')

@section('title', 'Đơn hàng')

@section('content')
<main class="pt-24 pb-32 px-4 md:px-lg max-w-container-max mx-auto">
<!-- Header & Tabs -->
<div class="mb-xl flex flex-col md:flex-row md:items-end justify-between gap-md">
<div>
<h1 class="font-headline-lg text-headline-lg text-on-surface">Your Đơn hàng</h1>
<p class="text-on-surface-variant mt-1">Track, manage, and reorder your favorite brews.</p>
</div>
<div class="flex bg-surface-container p-1 rounded-xl w-fit">
<button class="px-xl py-2 rounded-lg font-label-md text-label-md transition-all bg-surface-container-lowest text-primary shadow-sm active-tab-indicator" id="btn-active" onclick="switchTab('active')">Hoạt động Đơn hàng</button>
<button class="px-xl py-2 rounded-lg font-label-md text-label-md transition-all text-on-surface-variant hover:text-primary" id="btn-history" onclick="switchTab('history')">Order History</button>
</div>
</div>
<!-- Hoạt động Đơn hàng Giâytion -->
<section class="space-y-gutter" id="section-active">
<!-- Hoạt động Tracker Bento Card -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter">
<!-- Trạng thái Timeline -->
<div class="lg:col-span-2 bg-white rounded-xl border border-outline-variant/30 shadow-sm p-lg overflow-hidden relative">
<div class="flex justify-between items-start mb-xl">
<div>
<span class="bg-primary/10 text-primary px-3 py-1 rounded-full text-label-sm font-label-sm uppercase tracking-wider mb-2 inline-block">Order #CH-9942</span>
<h2 class="font-title-lg text-title-lg">Your Mocha is on its way!</h2>
</div>
<div class="text-right">
<p class="text-label-sm font-label-sm text-on-surface-variant">ESTIMATED ARRIVAL</p>
<p class="text-headline-md font-headline-md text-primary">12:45 PM</p>
</div>
</div>
<!-- Timeline -->
<div class="relative flex justify-between items-center px-4 py-8">
<div class="absolute top-1/2 left-0 w-full h-1 bg-surface-container -translate-y-1/2 -z-10"></div>
<div class="absolute top-1/2 left-0 w-2/3 h-1 bg-primary -translate-y-1/2 -z-10 transition-all duration-1000"></div>
<div class="flex flex-col items-center gap-xs">
<div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center shadow-md">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">coffee_maker</span>
</div>
<span class="font-label-md text-label-md text-primary font-bold">Brewing</span>
</div>
<div class="flex flex-col items-center gap-xs">
<div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center shadow-md">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">delivery_dining</span>
</div>
<span class="font-label-md text-label-md text-primary font-bold">Delivering</span>
</div>
<div class="flex flex-col items-center gap-xs opacity-40">
<div class="w-10 h-10 rounded-full bg-surface-container text-on-surface-variant flex items-center justify-center">
<span class="material-symbols-outlined">check_circle</span>
</div>
<span class="font-label-md text-label-md text-on-surface-variant">Arrived</span>
</div>
</div>
</div>
<!-- Map Snippet -->
<div class="bg-white rounded-xl border border-outline-variant/30 shadow-sm overflow-hidden relative min-h-[300px]">
<div class="absolute inset-0 z-0">
<div class="w-full h-full grayscale opacity-80" data-alt="A stylized minimalist digital map of a city neighborhood showing green parks and soft grey streets in a modern light mode aesthetic. A green delivery icon is positioned near a modern building landmark." data-location="Seattle, WA" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuA3myf70XeZSB4tIiAoCmY2zHBaM0HUUaDReSv6TmpuNUPwovMFvdJkV8eLD0sAbOE_-VoE5BrnVzuF0w8IWvRWD7aN76waa6isv9E4gWHaPIWu1GMh-USzkoJiZp4nUQxWxQIReQMGlx5gi4Zy797PXZU8ES0-IR2zyAGFXH-2aBEya7pS0gVKsb3iqU7RX9FMXbM7_Nl7bMLl44mbxOB9bW7pq1U79j-WmWNDFQvM0Ma7O5Rq_T4QzJi4bEhrcf__JJtdeD9m')"></div>
</div>
<div class="absolute bottom-md left-md right-md bg-white/90 glass-panel p-md rounded-lg flex items-center gap-md shadow-lg border border-white/20">
<div class="w-10 h-10 rounded-full overflow-hidden border-2 border-primary">
<img class="w-full h-full object-cover" data-alt="A professional headshot of a friendly delivery person wearing a green CozyHNA polo shirt, smiling against a warm blurred cafe background. Natural lighting and organic tones." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDkht3XPk1IZSgiS0ARLlfkGxucLIko_FijHpcYjc590Ss-JRV6CJM9q2XWX0DGl-ZDW_FtZhVhgmjaoZkS0WmGo1wd6zh5kWiuR9_Dug1PwAEGyklt9IK3JEh31YNqPGg6uaasoi2FQ0gtx7b0wLGs5yIvZRRsSkUogqG74rn4DG8QRHUYa2SIhCt-KefeR6iHFhlf_OgbDZm_WfUigKyC6nBBvHBrag861kehGlkLjGJ_MwsGIi6DwSt9drafQjeAzs6UYa1B"/>
</div>
<div>
<p class="font-label-sm text-label-sm text-on-surface-variant uppercase">Your Courier</p>
<p class="font-label-md text-label-md font-bold">James Miller</p>
</div>
<button class="ml-auto bg-primary text-white p-2 rounded-full material-symbols-outlined shadow-md active:scale-95 transition-transform">call</button>
</div>
</div>
</div>
<!-- Order Details Panel -->
<div class="bg-white rounded-xl border border-outline-variant/30 shadow-sm overflow-hidden">
<div class="p-lg border-b border-outline-variant/30 flex justify-between items-center">
<h3 class="font-title-lg text-title-lg">Order Details</h3>
<button class="flex items-center gap-xs text-primary font-label-md text-label-md border border-primary/20 px-md py-2 rounded-lg hover:bg-primary/5 transition-colors">
<span class="material-symbols-outlined text-[18px]">download</span>
                        Download Invoice
                    </button>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-xl p-lg">
<div class="space-y-md">
<div class="flex gap-md">
<div class="w-16 h-16 rounded-lg overflow-hidden flex-shrink-0">
<img class="w-full h-full object-cover" data-alt="A delicious overhead view of a hot mocha coffee with artisan latte art heart in a ceramic white mug, surrounded by roasted coffee beans on a clean light wood surface." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCQun6YAlfYG5yieYNh-kSyDizLHgrQ1lT3tfJ0K9AA9fj1zAmVoUvBXPyyM6SNKWteX-_BFIXIKOu0j7N5SEyO2RYyXHehvYh5-1Bqhv_Y60tRgKX9jwFS0Z7kGabYle-mFHeSpLBmypaRm4dnDq5zKfjAbWU-Ht855fyum-YRNrHQvEeGpTqvUcfZm_FyuU48zjNntQhjbeMunu-6oRNsEgXNZQd861PNlJO8umIMgiVuj9C-m5uqKL6K32xzjkTFRXNNzP7O"/>
</div>
<div class="flex-grow">
<div class="flex justify-between">
<p class="font-bold">Organic Mocha Latte</p>
<p class="text-primary font-bold">$5.50</p>
</div>
<p class="text-on-surface-variant text-body-md">Large • Oat Milk • Extra Hot</p>
</div>
</div>
<div class="flex gap-md">
<div class="w-16 h-16 rounded-lg overflow-hidden flex-shrink-0">
<img class="w-full h-full object-cover" data-alt="A freshly baked butter croissant with golden flaky layers on a minimal white porcelain plate, shot in soft natural morning light." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDTbhM0o80-oB9Cle0tWy7kzWHnANMkKUP5HlajF-C-uWFE2MgxTboTJBgKfHxm_VAqPoTZdEm80nlAP74nXQ9R9pbHLROM79Tk7lCiEphKAggw4Nih2NgaN4BHNPpey6h2fDiule4wYEpsQ404V5wGOTtCVkiZB0_bLb-P7rx9Ymbms1k_n1ASl38QXROceKHNlZpmdxc4wCLX9RaSLYv8hqjyi7V_aa5Mm2jayNzyQeROERsdGkRnJqqVECP2gDnTXM1ZKAIq"/>
</div>
<div class="flex-grow">
<div class="flex justify-between">
<p class="font-bold">Butter Croissant</p>
<p class="text-primary font-bold">$3.75</p>
</div>
<p class="text-on-surface-variant text-body-md">Warm</p>
</div>
</div>
<div class="pt-md border-t border-outline-variant/30">
<div class="flex justify-between text-body-md text-on-surface-variant">
<span>Tạm tính</span>
<span>$9.25</span>
</div>
<div class="flex justify-between text-body-md text-on-surface-variant">
<span>Delivery Fee</span>
<span>$1.50</span>
</div>
<div class="flex justify-between font-bold text-title-lg mt-xs text-primary">
<span>Tổng cộng</span>
<span>$10.75</span>
</div>
</div>
</div>
<div class="bg-surface-container-low rounded-xl p-lg space-y-md">
<div>
<p class="text-label-sm font-label-sm text-on-surface-variant uppercase mb-1">Địa Chỉ Giao Hàng</p>
<p class="text-body-md">123 Pine St, Apartment 4B<br/>Seattle, WA 98101</p>
</div>
<div>
<p class="text-label-sm font-label-sm text-on-surface-variant uppercase mb-1">Phương Thức Thanh Toán</p>
<div class="flex items-center gap-xs">
<span class="material-symbols-outlined text-primary">credit_card</span>
<p class="text-body-md">Apple Pay •••• 9924</p>
</div>
</div>
<div class="bg-white p-md rounded-lg border border-outline-variant/20">
<p class="text-label-sm font-label-sm text-on-surface-variant uppercase mb-1">Note to courier</p>
<p class="text-body-md italic">"Please leave at the front door, thank you!"</p>
</div>
</div>
</div>
</div>
</section>
<!-- History Giâytion (Hidden by default) -->
<section class="hidden space-y-md" id="section-history">
<!-- Order Card 1 -->
<div class="bg-white rounded-xl border border-outline-variant/30 shadow-sm p-lg flex flex-col md:flex-row md:items-center gap-lg hover:shadow-md transition-shadow">
<div class="flex flex-col gap-xs min-w-[140px]">
<p class="text-label-sm font-label-sm text-on-surface-variant">OCT 24, 2023</p>
<p class="font-bold text-body-lg">#CH-8812</p>
<span class="bg-green-100 text-secondary px-2 py-0.5 rounded text-label-sm w-fit font-bold">COMPLETED</span>
</div>
<div class="flex -space-x-4 items-center flex-grow">
<div class="w-12 h-12 rounded-full border-4 border-white overflow-hidden shadow-sm">
<img class="w-full h-full object-cover" data-alt="Macro close up of a vanilla bean iced latte with condensation on the glass, soft studio lighting, organic feel." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBFT9nRR5lfprCcMI7YmM3lvz_Z4HQX7ASv9PL9YVpV6-1hU-i8nnaRtx8JrWEvis9IlbN9R4PD19X9K6Y9eNdU1yxJ5tK_1AuxoHMx1gjB_vy0tGN8HMR9GQX99R4MafDPrkQnzjQxJ6UtVOmWvwlPO7F5x9RLjAkPZUbAdKIxLzmRhslnHuD4vadPqZi_A_SL8uMytabgw3hNJlIZd2mNuAbbnghWMkeSHuOXlvdMUmSc23Upp_le1-zvfae_B-67nUUgYp71"/>
</div>
<div class="w-12 h-12 rounded-full border-4 border-white overflow-hidden shadow-sm">
<img class="w-full h-full object-cover" data-alt="A single blueberry muffin with organic crumb topping on a clean neutral background." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCbGTlSweapEGwJQH-s0UdOGSg9b6-QD7ynGNXwXFo1RkYClxdWrTKRAdOvmV-9QBO0-hFn1uM7y1TgZA8uK1VuibvSncxFXQrtFkFfTtZVJTvN0ilE4QrX1rTHa6pKoYjlGKMNVCVaBZ1f4Ar5C9tG3lVP4fo1bQ3zWd1oJxpKqdnfV-XOcUNeiD9qDggJ8MrumRCNEuuVXJmqly1eA1ZnRgcn13d7yerLenQebCoXnNDNe0KNrBcjMgvJtcJdOt-Z4YkBxkpi"/>
</div>
<div class="pl-8">
<p class="font-bold text-body-md">Iced Vanilla Latte &amp; 1 other</p>
<p class="text-primary font-bold">$12.40</p>
</div>
</div>
<div class="flex gap-md ml-auto">
<button class="text-primary font-label-md text-label-md hover:underline">Rate Order</button>
<button class="bg-primary text-white px-md py-2 rounded-lg font-label-md text-label-md active:scale-95 transition-transform flex items-center gap-xs">
<span class="material-symbols-outlined text-[18px]">reorder</span>
                        Order Again
                    </button>
</div>
</div>
<!-- Order Card 2 -->
<div class="bg-white rounded-xl border border-outline-variant/30 shadow-sm p-lg flex flex-col md:flex-row md:items-center gap-lg hover:shadow-md transition-shadow">
<div class="flex flex-col gap-xs min-w-[140px]">
<p class="text-label-sm font-label-sm text-on-surface-variant">OCT 20, 2023</p>
<p class="font-bold text-body-lg">#CH-7756</p>
<span class="bg-red-100 text-error px-2 py-0.5 rounded text-label-sm w-fit font-bold">CANCELLED</span>
</div>
<div class="flex -space-x-4 items-center flex-grow">
<div class="w-12 h-12 rounded-full border-4 border-white overflow-hidden shadow-sm">
<img class="w-full h-full object-cover" data-alt="A hot organic green tea in a traditional clay cup, steaming softly in a minimalist tranquil environment." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCSpk__gcd-XfddS6JPbEL9zED7mBwDMvUvwZwS--p-I2Y3KutenKHG3oEzn0ogjaNSP9noXFcBRWM0c8ndtCP38v_IOj1odshAUDNmnqkdZeYrfhh6D020dK5Pq18gfEZj6l7ucp_Rag-m7ZlUv4Hf4C1qUzmU6XNmHq_Kc7huBpS3I2UkbEj8oVvFZUFebHb1C6ke41LpnbnOiLBhvcpXF9m_8LxP221k9mA14-AnY2ou10R4HJMocLaDl3bBf_YG2a2Vy3vi"/>
</div>
<div class="pl-8">
<p class="font-bold text-body-md">Matcha Green Tea</p>
<p class="text-primary font-bold">$4.50</p>
</div>
</div>
<div class="flex gap-md ml-auto">
<button class="bg-surface-container text-on-surface-variant px-md py-2 rounded-lg font-label-md text-label-md hover:bg-surface-container-high transition-colors">Details</button>
<button class="bg-primary text-white px-md py-2 rounded-lg font-label-md text-label-md active:scale-95 transition-transform flex items-center gap-xs">
<span class="material-symbols-outlined text-[18px]">reorder</span>
                        Order Again
                    </button>
</div>
</div>
<!-- Empty State (Demonstration - normally hidden if data exists) -->
<div class="hidden flex-col items-center justify-center py-2xl text-center space-y-md" id="empty-state">
<div class="w-64 h-64 opacity-80">
<img class="w-full h-full object-contain" data-alt="A minimalist line art illustration of a cozy empty cafe chair next to a small wooden table with a single flower in a vase, using soft green and cream tones." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDAiHoIBnvLL9R1yYfRAqffG-Yh3jChIAi0oWLFcfg60tUfMR39K-BvIaSp_t6ztvCchSQUtok9CkXfXKjzqJb682EPhvt_HPMK3jVo75iquF8X0aqmRKT8cEzx6BH_4DxVIN45wWC8kb9GGh9M_58fmBFDfe08-fOe5u7ajqHi77zoOK8R8mENk4q4fy7TlVzNyGNMj9n9Kvujdj-o-R_kYdx1NlkKg-WKwzjFjhBrMf_No69izMYDA8iLvqOP2lF7DDWvY1A_"/>
</div>
<div>
<h3 class="font-headline-md text-headline-md">No orders yet</h3>
<p class="text-on-surface-variant">Your brewing journey starts here!</p>
</div>
<button class="bg-primary text-white px-2xl py-3 rounded-full font-title-lg text-title-lg shadow-lg hover:shadow-xl transition-shadow active:scale-95">Start Ordering</button>
</div>
</section>
</main>
@endsection

@push('scripts')
<script>

        function switchTab(tab) {
            const activeGiây = document.getElementById('section-active');
            const historyGiây = document.getElementById('section-history');
            const btnHoạt động = document.getElementById('btn-active');
            const btnHistory = document.getElementById('btn-history');

            if (tab === 'active') {
                activeGiây.classList.remove('hidden');
                historyGiây.classList.add('hidden');
                
                btnHoạt động.classList.add('bg-surface-container-lowest', 'text-primary', 'shadow-sm', 'active-tab-indicator');
                btnHoạt động.classList.remove('text-on-surface-variant');
                
                btnHistory.classList.remove('bg-surface-container-lowest', 'text-primary', 'shadow-sm', 'active-tab-indicator');
                btnHistory.classList.add('text-on-surface-variant');
            } else {
                activeGiây.classList.add('hidden');
                historyGiây.classList.remove('hidden');
                
                btnHistory.classList.add('bg-surface-container-lowest', 'text-primary', 'shadow-sm', 'active-tab-indicator');
                btnHistory.classList.remove('text-on-surface-variant');
                
                btnHoạt động.classList.remove('bg-surface-container-lowest', 'text-primary', 'shadow-sm', 'active-tab-indicator');
                btnHoạt động.classList.add('text-on-surface-variant');
            }
        }

        // Micro-interaction for buttons
        document.querySelectorAll('button').forEach(btn => {
            btn.addEventListener('mousedown', () => btn.classList.add('scale-95'));
            btn.addEventListener('mouseup', () => btn.classList.remove('scale-95'));
            btn.addEventListener('mouseleave', () => btn.classList.remove('scale-95'));
        });
    
</script>
@endpush
