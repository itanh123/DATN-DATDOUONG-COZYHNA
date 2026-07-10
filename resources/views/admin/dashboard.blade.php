@extends('layouts.admin')

@section('title', 'Bảng điều khiển')

@section('content')
<main class="md:ml-[280px] min-h-screen p-md md:p-xl pb-2xl">
<!-- Header -->
<header class="flex justify-between items-center mb-xl">
<div>
<h2 class="font-headline-lg text-headline-lg text-on-surface">Bảng điều khiển Tổng quan</h2>
<p class="text-on-surface-variant font-body-md text-body-md">Welcome back, Admin. Here's what's happening today.</p>
</div>
<div class="flex items-center gap-md">
<div class="relative hidden sm:block">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline" data-icon="search">search</span>
<input class="pl-10 pr-4 py-2 bg-surface-container-low border border-outline-variant/30 rounded-full text-body-md focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all w-64" placeholder="Search data..." type="text"/>
</div>
<div class="flex items-center gap-sm bg-surface-container px-3 py-2 rounded-full border border-outline-variant/10">
<div class="w-8 h-8 rounded-full overflow-hidden bg-primary-container flex items-center justify-center text-on-primary-container">
<span class="material-symbols-outlined" data-icon="person">person</span>
</div>
<span class="font-label-md text-label-md hidden lg:block">Alex Johnson</span>
</div>
</div>
</header>
<!-- Stats Grid (Bento Style) -->
<section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-md mb-xl">
<div class="glass-card p-lg rounded-2xl shadow-sm hover:shadow-md transition-shadow group">
<div class="flex justify-between items-start mb-sm">
<div class="p-xs bg-primary-container/20 rounded-lg text-primary">
<span class="material-symbols-outlined" data-icon="payments">payments</span>
</div>
<span class="text-primary font-label-sm text-label-sm flex items-center gap-1">
                        +12.5% <span class="material-symbols-outlined !text-[14px]" data-icon="trending_up">trending_up</span>
</span>
</div>
<p class="text-on-surface-variant font-label-md text-label-md uppercase tracking-wider mb-xs">Doanh thu</p>
<h3 class="font-headline-md text-headline-md">$42,908.00</h3>
</div>
<div class="glass-card p-lg rounded-2xl shadow-sm hover:shadow-md transition-shadow">
<div class="flex justify-between items-start mb-sm">
<div class="p-xs bg-secondary-container/20 rounded-lg text-secondary">
<span class="material-symbols-outlined" data-icon="shopping_bag">shopping_bag</span>
</div>
<span class="text-primary font-label-sm text-label-sm flex items-center gap-1">
                        +8.2% <span class="material-symbols-outlined !text-[14px]" data-icon="trending_up">trending_up</span>
</span>
</div>
<p class="text-on-surface-variant font-label-md text-label-md uppercase tracking-wider mb-xs">Tổng cộng Đơn hàng</p>
<h3 class="font-headline-md text-headline-md">1,284</h3>
</div>
<div class="glass-card p-lg rounded-2xl shadow-sm hover:shadow-md transition-shadow">
<div class="flex justify-between items-start mb-sm">
<div class="p-xs bg-tertiary-container/20 rounded-lg text-tertiary">
<span class="material-symbols-outlined" data-icon="person_add">person_add</span>
</div>
<span class="text-error font-label-sm text-label-sm flex items-center gap-1">
                        -2.4% <span class="material-symbols-outlined !text-[14px]" data-icon="trending_down">trending_down</span>
</span>
</div>
<p class="text-on-surface-variant font-label-md text-label-md uppercase tracking-wider mb-xs">New Khách hàng</p>
<h3 class="font-headline-md text-headline-md">156</h3>
</div>
<div class="glass-card p-lg rounded-2xl shadow-sm hover:shadow-md transition-shadow">
<div class="flex justify-between items-start mb-sm">
<div class="p-xs bg-outline-variant/20 rounded-lg text-outline">
<span class="material-symbols-outlined" data-icon="inventory_2">inventory_2</span>
</div>
<span class="text-tertiary font-label-sm text-label-sm">Critical: 4 items</span>
</div>
<p class="text-on-surface-variant font-label-md text-label-md uppercase tracking-wider mb-xs">Kho hàng Trạng thái</p>
<h3 class="font-headline-md text-headline-md">94%</h3>
</div>
</section>
<!-- Main Charts & Insights Giâytion -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-xl mb-xl">
<!-- Doanh thu Trend Chart Placeholder -->
<div class="lg:col-span-2 glass-card p-xl rounded-3xl relative overflow-hidden flex flex-col">
<div class="flex justify-between items-center mb-lg relative z-10">
<h3 class="font-title-lg text-title-lg">Doanh thu Growth</h3>
<select class="bg-surface-container-low border-none rounded-lg text-label-md outline-none px-3 py-1 ring-1 ring-outline-variant/20">
<option>Last 7 Days</option>
<option>Last 30 Days</option>
<option>Current Year</option>
</select>
</div>
<div class="flex-1 w-full h-[300px] relative">
<!-- Simple SVG Chart Representation for Visual Appeal -->
<svg class="w-full h-full" viewbox="0 0 800 300">
<defs>
<lineargradient id="gradient" x1="0%" x2="0%" y1="0%" y2="100%">
<stop offset="0%" style="stop-color:#4CAF50;stop-opacity:0.2"></stop>
<stop offset="100%" style="stop-color:#4CAF50;stop-opacity:0"></stop>
</lineargradient>
</defs>
<path d="M0,250 Q100,220 200,240 T400,150 T600,100 T800,50 L800,300 L0,300 Z" fill="url(#gradient)"></path>
<path d="M0,250 Q100,220 200,240 T400,150 T600,100 T800,50" fill="none" stroke="#006e1c" stroke-linecap="round" stroke-width="4"></path>
<circle cx="200" cy="240" fill="#006e1c" r="6"></circle>
<circle cx="400" cy="150" fill="#006e1c" r="6"></circle>
<circle cx="600" cy="100" fill="#006e1c" r="6"></circle>
</svg>
<!-- Y-axis indicators -->
<div class="absolute left-0 top-0 h-full flex flex-col justify-between text-label-sm text-outline-variant/60 pointer-events-none">
<span>$50k</span><span>$40k</span><span>$30k</span><span>$20k</span><span>$10k</span><span>0</span>
</div>
</div>
</div>
<!-- Sản Phẩm Hàng Đầu List -->
<div class="glass-card p-xl rounded-3xl flex flex-col">
<h3 class="font-title-lg text-title-lg mb-lg">Top Performing Sản phẩm</h3>
<div class="flex flex-col gap-md flex-1">
<div class="flex items-center gap-md p-sm hover:bg-surface-container-high rounded-2xl transition-colors cursor-pointer">
<div class="w-12 h-12 rounded-xl overflow-hidden bg-surface-container">
<img class="w-full h-full object-cover" data-alt="A premium close-up shot of a signature latte with elaborate microfoam art in a ceramic cup, soft daylight lighting, high-end cafe atmosphere." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAqFSsMDEnsW6roUiScwrODr33gBOAVKl0JMsAuEHx3X0aCtZ7oDvNF8A2NtJdK4dPpMNTtQVZVF0-s6B4zKb8gfu4-c27-BahoE5-bvPup0ZU7K07jErBgpESi_WIUQukp7uRxckZchc-VOungu5yNR-0oR9nzJTOTgB68jGoMoSuN-USkISpewvOj7pEs-yB3lWJAl5t7VOuwq4Oz-ObOn-jkTTe5gNrRBUr1u_VVgP_qU6u_g533ZiGA6OO105o6JLWn-oS2"/>
</div>
<div class="flex-1">
<h4 class="font-body-lg text-body-lg font-bold">Lavender Oat Latte</h4>
<p class="text-on-surface-variant text-label-md">342 sold this week</p>
</div>
<span class="text-primary font-bold">+$4.2k</span>
</div>
<div class="flex items-center gap-md p-sm hover:bg-surface-container-high rounded-2xl transition-colors cursor-pointer">
<div class="w-12 h-12 rounded-xl overflow-hidden bg-surface-container">
<img class="w-full h-full object-cover" data-alt="Vibrant fresh matcha tea in a glass bowl with bamboo whisk nearby, minimal bright clean lighting, organic aesthetic." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAJ6_03PSMOGLJ9hQkAOf32PjUAiH7W6SpLnlwGLIFuG4SM94P3iiPag28yYlCcediTTFo60xdt_FI0Em29ynEzt_eo9aLHNTAh-MIRKffZ_WBP0UFOzYrXmXctssSYBAdBwhkFWx4fiu9Sz-Mssy-2KuHFi8UtNeRzgawAKjt4UzR6RPIyhLDyX_J67hjyuvXqYG-X3Lb1WhHbF95-HMnxA-zkl3JxAM2zRUJJYpTkimqmiio8o2UxHlfohOzRFKQw1CqOmFeO"/>
</div>
<div class="flex-1">
<h4 class="font-body-lg text-body-lg font-bold">Ceremonial Matcha</h4>
<p class="text-on-surface-variant text-label-md">290 sold this week</p>
</div>
<span class="text-primary font-bold">+$3.8k</span>
</div>
<div class="flex items-center gap-md p-sm hover:bg-surface-container-high rounded-2xl transition-colors cursor-pointer">
<div class="w-12 h-12 rounded-xl overflow-hidden bg-surface-container">
<img class="w-full h-full object-cover" data-alt="A moist gourmet dark chocolate croissant on a rustic plate, warm cafe lighting, textures of pastry flake." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBA4sIvetqLl-VOFM4UBs0O-l0bvZHTjWEl8JB6sBpOncxoim5JOKqnw0XGbvZ7he2Dd1Z2CApAx0aMvlPOAkYIFCO38bHhlPHAR_a3-3xJyi8xOiQUh5HfxF2xC6J-AySQOXqZbdTRaeIIYO9luq3hRpXwFK3kU1R9EK_6DfSq0Y9Yx7pX9SQmqRTfZuLu4sMWJPGhBG9SYnsuf46rkVDbKlvsXXe-bpoUUQDQdHdpMYKn4zNU6FmXrNK4R6WbCEgy5VLa3qkM"/>
</div>
<div class="flex-1">
<h4 class="font-body-lg text-body-lg font-bold">Dark Choco Croissant</h4>
<p class="text-on-surface-variant text-label-md">185 sold this week</p>
</div>
<span class="text-primary font-bold">+$1.5k</span>
</div>
</div>
<button class="mt-lg w-full py-2 text-primary font-label-md border border-primary/20 rounded-xl hover:bg-primary/5 transition-colors">Xem Tất Cả Sản phẩm</button>
</div>
</div>
<!-- Đơn Hàng Gần Đây Table -->
<section class="glass-card rounded-3xl overflow-hidden mb-xl shadow-sm">
<div class="px-xl py-lg flex justify-between items-center bg-surface-container-low/50">
<h3 class="font-title-lg text-title-lg">Đơn Hàng Gần Đây</h3>
<button class="flex items-center gap-2 text-primary font-label-md">
                    Filter <span class="material-symbols-outlined !text-lg" data-icon="filter_list">filter_list</span>
</button>
</div>
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-surface-container/30 border-b border-outline-variant/20">
<th class="px-xl py-md font-label-sm text-label-sm uppercase tracking-widest text-on-surface-variant">Order ID</th>
<th class="px-xl py-md font-label-sm text-label-sm uppercase tracking-widest text-on-surface-variant">Khách hàng</th>
<th class="px-xl py-md font-label-sm text-label-sm uppercase tracking-widest text-on-surface-variant">Trạng thái</th>
<th class="px-xl py-md font-label-sm text-label-sm uppercase tracking-widest text-on-surface-variant">Tổng cộng</th>
<th class="px-xl py-md font-label-sm text-label-sm uppercase tracking-widest text-on-surface-variant">Time</th>
<th class="px-xl py-md font-label-sm text-label-sm uppercase tracking-widest text-on-surface-variant">Action</th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant/10">
<tr class="hover:bg-surface-container-low transition-colors group">
<td class="px-xl py-md font-body-md text-body-md font-bold">#ORD-2841</td>
<td class="px-xl py-md">
<div class="flex items-center gap-xs">
<span class="material-symbols-outlined !text-lg text-outline" data-icon="account_circle">account_circle</span>
<span class="font-body-md text-body-md">Sarah Miller</span>
</div>
</td>
<td class="px-xl py-md">
<span class="px-3 py-1 bg-primary/10 text-primary rounded-full text-label-sm font-bold">Delivered</span>
</td>
<td class="px-xl py-md font-body-md text-body-md">$18.50</td>
<td class="px-xl py-md font-body-md text-body-md text-on-surface-variant">12 mins ago</td>
<td class="px-xl py-md">
<button class="p-xs text-outline hover:text-primary transition-colors">
<span class="material-symbols-outlined" data-icon="more_vert">more_vert</span>
</button>
</td>
</tr>
<tr class="hover:bg-surface-container-low transition-colors group">
<td class="px-xl py-md font-body-md text-body-md font-bold">#ORD-2840</td>
<td class="px-xl py-md">
<div class="flex items-center gap-xs">
<span class="material-symbols-outlined !text-lg text-outline" data-icon="account_circle">account_circle</span>
<span class="font-body-md text-body-md">John Doe</span>
</div>
</td>
<td class="px-xl py-md">
<span class="px-3 py-1 bg-tertiary/10 text-tertiary rounded-full text-label-sm font-bold">Preparing</span>
</td>
<td class="px-xl py-md font-body-md text-body-md">$24.00</td>
<td class="px-xl py-md font-body-md text-body-md text-on-surface-variant">18 mins ago</td>
<td class="px-xl py-md">
<button class="p-xs text-outline hover:text-primary transition-colors">
<span class="material-symbols-outlined" data-icon="more_vert">more_vert</span>
</button>
</td>
</tr>
<tr class="hover:bg-surface-container-low transition-colors group">
<td class="px-xl py-md font-body-md text-body-md font-bold">#ORD-2839</td>
<td class="px-xl py-md">
<div class="flex items-center gap-xs">
<span class="material-symbols-outlined !text-lg text-outline" data-icon="account_circle">account_circle</span>
<span class="font-body-md text-body-md">Emma Wilson</span>
</div>
</td>
<td class="px-xl py-md">
<span class="px-3 py-1 bg-secondary/10 text-secondary rounded-full text-label-sm font-bold">Chờ xử lý</span>
</td>
<td class="px-xl py-md font-body-md text-body-md">$9.20</td>
<td class="px-xl py-md font-body-md text-body-md text-on-surface-variant">25 mins ago</td>
<td class="px-xl py-md">
<button class="p-xs text-outline hover:text-primary transition-colors">
<span class="material-symbols-outlined" data-icon="more_vert">more_vert</span>
</button>
</td>
</tr>
</tbody>
</table>
</div>
<div class="p-lg flex justify-center border-t border-outline-variant/10">
<button class="font-label-md text-label-md text-outline hover:text-primary transition-colors flex items-center gap-1">
                    View Complete Transaction History <span class="material-symbols-outlined !text-sm" data-icon="chevron_right">chevron_right</span>
</button>
</div>
</section>
</main>
@endsection

@push('scripts')
<script>

        // Simple Micro-interaction: Update time and simulate real-time stats
        document.addEventListener('DOMContentLoaded', () => {
            const revenueDisplay = document.querySelector('h3.font-headline-md');
            if(revenueDisplay) {
                let currentVal = 42908;
                setInterval(() => {
                    const change = (Math.random() * 5).toFixed(2);
                    currentVal += parseFloat(change);
                    revenueDisplay.textContent = `$${currentVal.toLocaleString(undefined, {minimumFractionDigits: 2})}`;
                }, 5000);
            }
        });
    
</script>
@endpush
