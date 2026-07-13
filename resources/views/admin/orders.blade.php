@extends('layouts.admin')

@section('title', 'Đơn hàng')

@section('content')
<main class="md:ml-[280px] min-h-screen p-lg md:p-xl">
<!-- Header -->
<header class="flex flex-col md:flex-row md:items-center justify-between mb-2xl gap-md">
<div>
<h2 class="font-headline-lg text-headline-lg text-on-surface">Đơn hàng Management</h2>
<p class="font-body-md text-on-surface-variant">Real-time status of all active beverage preparations and deliveries.</p>
</div>
<div class="flex items-center gap-md">
<div class="relative">
<span class="absolute inset-y-0 left-0 pl-md flex items-center text-on-surface-variant">
<span class="material-symbols-outlined">search</span>
</span>
<input class="pl-xl pr-md py-sm w-64 rounded-xl border border-outline-variant bg-surface-container-lowest focus:ring-2 focus:ring-primary focus:border-transparent outline-none font-body-md transition-all" placeholder="Search orders, customers..." type="text"/>
</div>
<button class="bg-surface-container-high text-on-surface px-md py-sm rounded-xl font-semibold flex items-center gap-xs hover:bg-surface-container-highest transition-colors active:scale-95">
<span class="material-symbols-outlined">notifications</span>
</button>
<div class="w-10 h-10 rounded-full overflow-hidden border-2 border-primary-container">
<img class="w-full h-full object-cover" data-alt="A professional headshot of a female administrative manager in a bright, modern office setting. She has a friendly expression, wearing a minimalist green blazer that complements the brand's organic aesthetic. The lighting is soft and even, highlighting her professional yet approachable persona against a blurred background of a clean, high-end cafe interior." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDXlDLJ2yF0soe0mzltKzkTVHyYdX54qGr8mqf1nHvvsWFSe1UW888DtGmXI3K0wetDs2ypdD3KL3eC1UCpXS53gyVeoc7bxsZ4eCVu-2RPrvmQ2rUyorVzaqx1b-3mSDkH1SJlOlqQGEeWbrz7BGNHiyJgj_2H2MxjdBsznnOdoy8ozs3_KpfIBCZ2jK-aQdmhjIkZSAabNrRHV1Igp3WIbZltmu_XD92gBWFP2jg1SI5RV8O5HfHSRMmQtoYHOih9BSSVcugO"/>
</div>
</div>
</header>
<!-- High-Level Stats Tổng quan -->
<section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-lg mb-2xl">
<!-- Stat Card 1 -->
<div class="bg-surface-container-lowest p-lg rounded-2xl shadow-sm border border-outline-variant/30 flex items-start justify-between">
<div>
<p class="font-label-md text-on-surface-variant uppercase tracking-wider mb-xs">Tổng cộng Đơn hàng Today</p>
<h3 class="font-headline-lg text-headline-lg text-on-surface">184</h3>
<div class="flex items-center gap-xs mt-xs text-secondary font-semibold">
<span class="material-symbols-outlined text-sm">trending_up</span>
<span class="text-xs">+12.5% vs yesterday</span>
</div>
</div>
<div class="p-md bg-primary-fixed rounded-xl text-on-primary-fixed">
<span class="material-symbols-outlined">shopping_bag</span>
</div>
</div>
<!-- Stat Card 2 -->
<div class="bg-surface-container-lowest p-lg rounded-2xl shadow-sm border border-outline-variant/30 flex items-start justify-between">
<div>
<p class="font-label-md text-on-surface-variant uppercase tracking-wider mb-xs">Chờ xử lý Prep</p>
<h3 class="font-headline-lg text-headline-lg text-on-surface">14</h3>
<div class="flex items-center gap-xs mt-xs text-tertiary font-semibold">
<span class="material-symbols-outlined text-sm">hourglass_empty</span>
<span class="text-xs">Action required</span>
</div>
</div>
<div class="p-md bg-tertiary-fixed rounded-xl text-on-tertiary-fixed">
<span class="material-symbols-outlined">coffee_maker</span>
</div>
</div>
<!-- Stat Card 3 -->
<div class="bg-surface-container-lowest p-lg rounded-2xl shadow-sm border border-outline-variant/30 flex items-start justify-between">
<div>
<p class="font-label-md text-on-surface-variant uppercase tracking-wider mb-xs">Out for Delivery</p>
<h3 class="font-headline-lg text-headline-lg text-on-surface">28</h3>
<div class="flex items-center gap-xs mt-xs text-on-surface-variant font-semibold">
<span class="material-symbols-outlined text-sm">local_shipping</span>
<span class="text-xs">Moving steadily</span>
</div>
</div>
<div class="p-md bg-secondary-container rounded-xl text-on-secondary-container">
<span class="material-symbols-outlined">delivery_dining</span>
</div>
</div>
<!-- Stat Card 4 -->
<div class="bg-surface-container-lowest p-lg rounded-2xl shadow-sm border border-outline-variant/30 flex items-start justify-between">
<div>
<p class="font-label-md text-on-surface-variant uppercase tracking-wider mb-xs">Avg. Fulfillment</p>
<h3 class="font-headline-lg text-headline-lg text-on-surface">14m 20s</h3>
<div class="flex items-center gap-xs mt-xs text-secondary font-semibold">
<span class="material-symbols-outlined text-sm">arrow_downward</span>
<span class="text-xs">-2m vs last hour</span>
</div>
</div>
<div class="p-md bg-surface-container-highest rounded-xl text-on-surface">
<span class="material-symbols-outlined">schedule</span>
</div>
</div>
</section>
<!-- Filters & Tools -->
<section class="flex flex-col lg:flex-row items-center justify-between gap-md mb-lg">
<div class="flex flex-wrap items-center gap-sm">
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex items-center px-md py-sm">
<span class="material-symbols-outlined text-on-surface-variant mr-xs">filter_list</span>
<select class="bg-transparent border-none focus:ring-0 font-body-md text-on-surface p-0 cursor-pointer">
<option>Trạng thái: All Đơn hàng</option>
<option>Chờ xử lý</option>
<option>Brewing</option>
<option>Out for Delivery</option>
<option>Hoàn thành</option>
</select>
</div>
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex items-center px-md py-sm">
<span class="material-symbols-outlined text-on-surface-variant mr-xs">calendar_today</span>
<span class="font-body-md text-on-surface">Oct 24, 2024</span>
</div>
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex items-center px-md py-sm">
<span class="material-symbols-outlined text-on-surface-variant mr-xs">handyman</span>
<select class="bg-transparent border-none focus:ring-0 font-body-md text-on-surface p-0 cursor-pointer">
<option>Method: All</option>
<option>Pickup</option>
<option>Delivery</option>
</select>
</div>
</div>
<div class="flex items-center gap-sm">
<button class="bg-surface-container-lowest border border-outline-variant text-on-surface px-md py-sm rounded-xl font-semibold flex items-center gap-xs hover:bg-surface-container-low transition-colors">
<span class="material-symbols-outlined">file_download</span>
                    Export CSV
                </button>
<button class="bg-primary text-on-primary px-lg py-sm rounded-xl font-semibold flex items-center gap-xs hover:bg-opacity-90 transition-opacity shadow-md">
<span class="material-symbols-outlined">refresh</span>
                    Refresh Data
                </button>
</div>
</section>
<!-- Đơn hàng Table -->
<section class="bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden">
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead class="bg-surface-container-low border-b border-outline-variant/30">
<tr>
<th class="px-lg py-md font-label-sm text-on-surface-variant uppercase tracking-wider">Order ID</th>
<th class="px-lg py-md font-label-sm text-on-surface-variant uppercase tracking-wider">Khách hàng</th>
<th class="px-lg py-md font-label-sm text-on-surface-variant uppercase tracking-wider">Items Summary</th>
<th class="px-lg py-md font-label-sm text-on-surface-variant uppercase tracking-wider">Tổng cộng</th>
<th class="px-lg py-md font-label-sm text-on-surface-variant uppercase tracking-wider">Trạng thái</th>
<th class="px-lg py-md font-label-sm text-on-surface-variant uppercase tracking-wider">Elapsed</th>
<th class="px-lg py-md font-label-sm text-on-surface-variant uppercase tracking-wider text-right">Thao tác</th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant/20">
<!-- Order Row 1 -->
<tr class="order-row transition-colors">
<td class="px-lg py-lg font-body-md font-semibold text-primary">#CH-8821</td>
<td class="px-lg py-lg">
<div class="flex items-center gap-sm">
<div class="w-8 h-8 rounded-full bg-secondary-container flex items-center justify-center text-on-secondary-container font-bold text-xs">JD</div>
<span class="font-body-md font-medium">Jane Doe</span>
</div>
</td>
<td class="px-lg py-lg">
<span class="font-body-md text-on-surface-variant">2x Oat Latte, 1x Matcha Cookie</span>
</td>
<td class="px-lg py-lg font-body-md font-semibold">$22.50</td>
<td class="px-lg py-lg">
<span class="inline-flex items-center gap-xs px-md py-1 rounded-full bg-tertiary-container/20 text-tertiary-container font-semibold text-xs border border-tertiary-container/30">
<span class="w-2 h-2 rounded-full bg-tertiary"></span>
                                    Brewing
                                </span>
</td>
<td class="px-lg py-lg font-body-md text-on-surface-variant">04:12</td>
<td class="px-lg py-lg text-right space-x-2">
<button class="p-2 rounded-lg hover:bg-surface-container-high transition-colors" title="View Details">
<span class="material-symbols-outlined text-primary">visibility</span>
</button>
<button class="p-2 rounded-lg hover:bg-surface-container-high transition-colors" title="Update Trạng thái">
<span class="material-symbols-outlined text-secondary">edit_square</span>
</button>
<button class="p-2 rounded-lg hover:bg-surface-container-high transition-colors" title="Print Label">
<span class="material-symbols-outlined text-on-surface-variant">print</span>
</button>
</td>
</tr>
<!-- Order Row 2 -->
<tr class="order-row transition-colors">
<td class="px-lg py-lg font-body-md font-semibold text-primary">#CH-8820</td>
<td class="px-lg py-lg">
<div class="flex items-center gap-sm">
<div class="w-8 h-8 rounded-full bg-primary-container flex items-center justify-center text-on-primary-container font-bold text-xs">MS</div>
<span class="font-body-md font-medium">Michael Smith</span>
</div>
</td>
<td class="px-lg py-lg">
<span class="font-body-md text-on-surface-variant">1x Cold Brew, 1x Espresso</span>
</td>
<td class="px-lg py-lg font-body-md font-semibold">$12.00</td>
<td class="px-lg py-lg">
<span class="inline-flex items-center gap-xs px-md py-1 rounded-full bg-secondary-container text-on-secondary-container font-semibold text-xs border border-secondary/20">
<span class="w-2 h-2 rounded-full bg-secondary"></span>
                                    Out for Delivery
                                </span>
</td>
<td class="px-lg py-lg font-body-md text-on-surface-variant">12:45</td>
<td class="px-lg py-lg text-right space-x-2">
<button class="p-2 rounded-lg hover:bg-surface-container-high transition-colors"><span class="material-symbols-outlined text-primary">visibility</span></button>
<button class="p-2 rounded-lg hover:bg-surface-container-high transition-colors"><span class="material-symbols-outlined text-secondary">edit_square</span></button>
<button class="p-2 rounded-lg hover:bg-surface-container-high transition-colors"><span class="material-symbols-outlined text-on-surface-variant">print</span></button>
</td>
</tr>
<!-- Order Row 3 -->
<tr class="order-row transition-colors">
<td class="px-lg py-lg font-body-md font-semibold text-primary">#CH-8819</td>
<td class="px-lg py-lg">
<div class="flex items-center gap-sm">
<div class="w-8 h-8 rounded-full bg-surface-container-highest flex items-center justify-center text-on-surface font-bold text-xs">AK</div>
<span class="font-body-md font-medium">Alice Kim</span>
</div>
</td>
<td class="px-lg py-lg">
<span class="font-body-md text-on-surface-variant">3x Flat White, 2x Avocado Toast</span>
</td>
<td class="px-lg py-lg font-body-md font-semibold">$48.90</td>
<td class="px-lg py-lg">
<span class="inline-flex items-center gap-xs px-md py-1 rounded-full bg-error-container text-on-error-container font-semibold text-xs border border-error/20">
<span class="w-2 h-2 rounded-full bg-error"></span>
                                    Chờ xử lý
                                </span>
</td>
<td class="px-lg py-lg font-body-md text-on-surface-variant">00:45</td>
<td class="px-lg py-lg text-right space-x-2">
<button class="p-2 rounded-lg hover:bg-surface-container-high transition-colors"><span class="material-symbols-outlined text-primary">visibility</span></button>
<button class="p-2 rounded-lg hover:bg-surface-container-high transition-colors"><span class="material-symbols-outlined text-secondary">edit_square</span></button>
<button class="p-2 rounded-lg hover:bg-surface-container-high transition-colors"><span class="material-symbols-outlined text-on-surface-variant">print</span></button>
</td>
</tr>
<!-- Order Row 4 -->
<tr class="order-row transition-colors">
<td class="px-lg py-lg font-body-md font-semibold text-primary">#CH-8818</td>
<td class="px-lg py-lg">
<div class="flex items-center gap-sm">
<div class="w-8 h-8 rounded-full bg-primary-fixed flex items-center justify-center text-on-primary-fixed font-bold text-xs">RW</div>
<span class="font-body-md font-medium">Robert Wilson</span>
</div>
</td>
<td class="px-lg py-lg">
<span class="font-body-md text-on-surface-variant">1x Seasonal Tea Selection</span>
</td>
<td class="px-lg py-lg font-body-md font-semibold">$6.50</td>
<td class="px-lg py-lg">
<span class="inline-flex items-center gap-xs px-md py-1 rounded-full bg-surface-container text-on-surface font-semibold text-xs border border-outline-variant">
<span class="w-2 h-2 rounded-full bg-on-surface"></span>
                                    Hoàn thành
                                </span>
</td>
<td class="px-lg py-lg font-body-md text-on-surface-variant">18:22</td>
<td class="px-lg py-lg text-right space-x-2">
<button class="p-2 rounded-lg hover:bg-surface-container-high transition-colors"><span class="material-symbols-outlined text-primary">visibility</span></button>
<button class="p-2 rounded-lg hover:bg-surface-container-high transition-colors"><span class="material-symbols-outlined text-secondary">edit_square</span></button>
<button class="p-2 rounded-lg hover:bg-surface-container-high transition-colors"><span class="material-symbols-outlined text-on-surface-variant">print</span></button>
</td>
</tr>
</tbody>
</table>
</div>
<!-- Pagination -->
<div class="bg-surface-container-low px-lg py-md flex items-center justify-between border-t border-outline-variant/30">
<p class="font-body-md text-on-surface-variant">Showing <span class="font-semibold text-on-surface">1-4</span> of <span class="font-semibold text-on-surface">128</span> orders</p>
<div class="flex gap-xs">
<button class="w-8 h-8 flex items-center justify-center rounded-lg border border-outline-variant text-on-surface-variant hover:bg-surface-container-high transition-colors"><span class="material-symbols-outlined text-sm">chevron_left</span></button>
<button class="w-8 h-8 flex items-center justify-center rounded-lg bg-primary text-on-primary font-semibold text-sm">1</button>
<button class="w-8 h-8 flex items-center justify-center rounded-lg border border-outline-variant text-on-surface hover:bg-surface-container-high transition-colors">2</button>
<button class="w-8 h-8 flex items-center justify-center rounded-lg border border-outline-variant text-on-surface hover:bg-surface-container-high transition-colors">3</button>
<button class="w-8 h-8 flex items-center justify-center rounded-lg border border-outline-variant text-on-surface-variant hover:bg-surface-container-high transition-colors"><span class="material-symbols-outlined text-sm">chevron_right</span></button>
</div>
</div>
</section>
<!-- Live Trạng thái Sidebar Placeholder (Phúti-Bảng điều khiển) -->
<section class="mt-2xl grid grid-cols-1 lg:grid-cols-3 gap-lg">
<div class="lg:col-span-2 bg-surface-container-low/50 p-xl rounded-2xl border border-dashed border-outline-variant flex flex-col items-center justify-center text-center">
<span class="material-symbols-outlined text-4xl text-primary/30 mb-md">map</span>
<h4 class="font-title-lg text-title-lg text-on-surface mb-xs">Hoạt động Deliveries Map</h4>
<p class="font-body-md text-on-surface-variant max-w-md">Interactive delivery tracking is active. Real-time courier positions are being synced for all orders currently "Out for Delivery".</p>
<div class="mt-lg w-full h-48 bg-surface-container-high rounded-xl overflow-hidden relative">
<div class="w-full h-full bg-cover bg-center grayscale contrast-75 opacity-40" data-location="Seattle" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDfXU-j8CaL9qL0udL80Gbt88FlGnGCpuEtact117SD3S39R3KB-MpWsQGPr08ISNY__dNFQ0WNsESgReZKT48pcAjj6MfSD1S6ARLP41uoWB_MNRR_ErH0G4McYxidj3V54_zmzYZXpDOg0V09ZAZDbCMOwDdOsRsrgWESxVBWv1_dMRbPD-6kyYb26tRDvxd_S13AmWqc0qDoKK7TmpZ2EqTHoUQh0R0jTELUgZYnMi63BG4qNBEmH1AEUaf57RkAq7zWcMVH')"></div>
<div class="absolute inset-0 flex items-center justify-center">
<div class="bg-surface-container-lowest shadow-xl px-lg py-md rounded-full border border-primary/20 flex items-center gap-md">
<span class="relative flex h-3 w-3">
<span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
<span class="relative inline-flex rounded-full h-3 w-3 bg-primary"></span>
</span>
<span class="font-body-md font-semibold text-primary">Live Tracking Enabled</span>
</div>
</div>
</div>
</div>
<div class="bg-surface-container-lowest p-lg rounded-2xl shadow-sm border border-outline-variant/30">
<h4 class="font-title-lg text-title-lg text-on-surface mb-lg">Urgent Alerts</h4>
<div class="space-y-md">
<div class="flex gap-md p-md bg-error/5 rounded-xl border-l-4 border-error">
<span class="material-symbols-outlined text-error">priority_high</span>
<div>
<p class="font-body-md font-semibold text-on-surface">Kho hàng Low: Oat Milk</p>
<p class="font-label-md text-on-surface-variant">Only 4 units remaining. Order #CH-8821 might be affected.</p>
</div>
</div>
<div class="flex gap-md p-md bg-secondary/5 rounded-xl border-l-4 border-secondary">
<span class="material-symbols-outlined text-secondary">check_circle</span>
<div>
<p class="font-body-md font-semibold text-on-surface">Peak Hour Strategy</p>
<p class="font-label-md text-on-surface-variant">Preparation flow is optimized for current 15% surge.</p>
</div>
</div>
<div class="flex gap-md p-md bg-surface-container-low rounded-xl">
<span class="material-symbols-outlined text-on-surface-variant">person_search</span>
<div>
<p class="font-body-md font-semibold text-on-surface">Courier Approaching</p>
<p class="font-label-md text-on-surface-variant">Courier #992 is 2 mins away for #CH-8820.</p>
</div>
</div>
</div>
</div>
</section>
</main>
@endsection

@push('scripts')
<script>

        // Simple Micro-interactions
        document.querySelectorAll('.order-row').forEach(row => {
            row.addEventListener('click', () => {
                // Future: Open detail panel
                console.log('Row clicked');
            });
        });

        // Search Bar Focus Effect
        const searchInput = document.querySelector('input[type="text"]');
        searchInput.addEventListener('focus', () => {
            searchInput.parentElement.classList.add('ring-2', 'ring-primary/20');
        });
        searchInput.addEventListener('blur', () => {
            searchInput.parentElement.classList.remove('ring-2', 'ring-primary/20');
        });
    
</script>
@endpush
