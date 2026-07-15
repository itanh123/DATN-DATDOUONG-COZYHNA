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
<h3 class="font-headline-lg text-headline-lg text-on-surface">{{ $totalOrdersToday }}</h3>
<div class="flex items-center gap-xs mt-xs text-secondary font-semibold">
<span class="text-xs">Đơn hàng trong ngày</span>
</div>
</div>
<div class="p-md bg-primary-fixed rounded-xl text-on-primary-fixed">
<span class="material-symbols-outlined">shopping_bag</span>
</div>
</div>
<!-- Stat Card 2 -->
<div class="bg-surface-container-lowest p-lg rounded-2xl shadow-sm border border-outline-variant/30 flex items-start justify-between">
<div>
<p class="font-label-md text-on-surface-variant uppercase tracking-wider mb-xs">Chờ xử lý / Đang chuẩn bị</p>
<h3 class="font-headline-lg text-headline-lg text-on-surface">{{ $pendingPrep }}</h3>
<div class="flex items-center gap-xs mt-xs text-tertiary font-semibold">
<span class="material-symbols-outlined text-sm">hourglass_empty</span>
<span class="text-xs">Cần xử lý</span>
</div>
</div>
<div class="p-md bg-tertiary-fixed rounded-xl text-on-tertiary-fixed">
<span class="material-symbols-outlined">coffee_maker</span>
</div>
</div>
<!-- Stat Card 3 -->
<div class="bg-surface-container-lowest p-lg rounded-2xl shadow-sm border border-outline-variant/30 flex items-start justify-between">
<div>
<p class="font-label-md text-on-surface-variant uppercase tracking-wider mb-xs">Đang Giao Hàng</p>
<h3 class="font-headline-lg text-headline-lg text-on-surface">{{ $outForDelivery }}</h3>
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
<p class="font-label-md text-on-surface-variant uppercase tracking-wider mb-xs">Thời gian hoàn thành TB</p>
<h3 class="font-headline-lg text-headline-lg text-on-surface">{{ $avgFulfillment }}</h3>
<div class="flex items-center gap-xs mt-xs text-secondary font-semibold">
<span class="text-xs">Hôm nay</span>
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
<form action="/admin/orders" method="GET" id="filterForm" class="flex gap-sm w-full">
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex items-center px-md py-sm">
<span class="material-symbols-outlined text-on-surface-variant mr-xs">filter_list</span>
<select name="status" onchange="document.getElementById('filterForm').submit()" class="bg-transparent border-none focus:ring-0 font-body-md text-on-surface p-0 cursor-pointer">
<option value="all" {{ $statusFilter == 'all' ? 'selected' : '' }}>Trạng thái: All Đơn hàng</option>
<option value="pending" {{ $statusFilter == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
<option value="confirmed" {{ $statusFilter == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
<option value="preparing" {{ $statusFilter == 'preparing' ? 'selected' : '' }}>Đang chuẩn bị</option>
<option value="shipping" {{ $statusFilter == 'shipping' ? 'selected' : '' }}>Đang giao</option>
<option value="completed" {{ $statusFilter == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
<option value="cancelled" {{ $statusFilter == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
</select>
</div>
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex items-center px-md py-sm">
<span class="material-symbols-outlined text-on-surface-variant mr-xs">calendar_today</span>
<input type="date" name="date" value="{{ $dateFilter }}" onchange="document.getElementById('filterForm').submit()" class="bg-transparent border-none focus:ring-0 font-body-md text-on-surface p-0 cursor-pointer">
</div>
</form>
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
@forelse($orders as $order)
<tr class="order-row transition-colors">
<td class="px-lg py-lg font-body-md font-semibold text-primary">#{{ $order->order_code }}</td>
<td class="px-lg py-lg">
<div class="flex items-center gap-sm">
<div class="w-8 h-8 rounded-full bg-secondary-container flex items-center justify-center text-on-secondary-container font-bold text-xs">{{ strtoupper(substr($order->customer_name, 0, 2)) }}</div>
<span class="font-body-md font-medium">{{ $order->customer_name }}</span>
</div>
</td>
<td class="px-lg py-lg">
<span class="font-body-md text-on-surface-variant">
@foreach($order->items as $index => $item)
    {{ $item->quantity }}x {{ $item->product_name }}@if(!$loop->last), @endif
@endforeach
</span>
</td>
<td class="px-lg py-lg font-body-md font-semibold">{{ number_format($order->total_amount, 0, ',', '.') }}đ</td>
<td class="px-lg py-lg">
@php
    $statusColor = 'bg-surface-container text-on-surface';
    if($order->status == 'pending' || $order->status == 'confirmed') $statusColor = 'bg-error-container text-on-error-container';
    if($order->status == 'preparing') $statusColor = 'bg-tertiary-container text-on-tertiary-container';
    if($order->status == 'shipping') $statusColor = 'bg-secondary-container text-on-secondary-container';
    if($order->status == 'completed') $statusColor = 'bg-primary-container text-on-primary-container';
    if($order->status == 'cancelled') $statusColor = 'bg-error text-on-error';
@endphp
<span class="inline-flex items-center gap-xs px-md py-1 rounded-full {{ $statusColor }} font-semibold text-xs border border-outline-variant/30">
    {{ ucfirst($order->status) }}
</span>
</td>
<td class="px-lg py-lg font-body-md text-on-surface-variant">{{ \Carbon\Carbon::parse($order->created_at)->diffForHumans() }}</td>
<td class="px-lg py-lg text-right">
    <form action="/admin/orders/{{ $order->id }}/status" method="POST" class="inline-flex items-center gap-2">
        @csrf
        <select name="status" onchange="this.form.submit()" class="text-xs p-1 rounded border border-outline-variant focus:ring-0">
            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
            <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
            <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>Đang chuẩn bị</option>
            <option value="shipping" {{ $order->status == 'shipping' ? 'selected' : '' }}>Đang giao</option>
            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
        </select>
    </form>
</td>
</tr>
@empty
<tr>
    <td colspan="7" class="text-center py-lg text-on-surface-variant">Không có đơn hàng nào</td>
</tr>
@endforelse
</tbody>
</table>
</div>
<!-- Pagination -->
<div class="bg-surface-container-low px-lg py-md flex items-center justify-between border-t border-outline-variant/30">
    <div class="w-full mt-4">
        {{ $orders->appends(request()->query())->links() }}
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
