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
<span class="font-label-md text-label-md hidden lg:block">{{ session('username') ?? 'Admin' }}</span>
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
<span class="{{ $revenueChange >= 0 ? 'text-primary' : 'text-error' }} font-label-sm text-label-sm flex items-center gap-1">
                        {{ $revenueChange >= 0 ? '+' : '' }}{{ number_format($revenueChange, 1) }}% <span class="material-symbols-outlined !text-[14px]" data-icon="{{ $revenueChange >= 0 ? 'trending_up' : 'trending_down' }}">{{ $revenueChange >= 0 ? 'trending_up' : 'trending_down' }}</span>
</span>
</div>
<p class="text-on-surface-variant font-label-md text-label-md uppercase tracking-wider mb-xs">Doanh thu</p>
<h3 class="font-headline-md text-headline-md">{{ number_format($revenue, 0, ',', '.') }} VNĐ</h3>
</div>
<div class="glass-card p-lg rounded-2xl shadow-sm hover:shadow-md transition-shadow">
<div class="flex justify-between items-start mb-sm">
<div class="p-xs bg-secondary-container/20 rounded-lg text-secondary">
<span class="material-symbols-outlined" data-icon="shopping_bag">shopping_bag</span>
</div>
<span class="{{ $ordersChange >= 0 ? 'text-primary' : 'text-error' }} font-label-sm text-label-sm flex items-center gap-1">
                        {{ $ordersChange >= 0 ? '+' : '' }}{{ number_format($ordersChange, 1) }}% <span class="material-symbols-outlined !text-[14px]" data-icon="{{ $ordersChange >= 0 ? 'trending_up' : 'trending_down' }}">{{ $ordersChange >= 0 ? 'trending_up' : 'trending_down' }}</span>
</span>
</div>
<p class="text-on-surface-variant font-label-md text-label-md uppercase tracking-wider mb-xs">Tổng cộng Đơn hàng</p>
<h3 class="font-headline-md text-headline-md">{{ number_format($totalOrders) }}</h3>
</div>
<div class="glass-card p-lg rounded-2xl shadow-sm hover:shadow-md transition-shadow">
<div class="flex justify-between items-start mb-sm">
<div class="p-xs bg-tertiary-container/20 rounded-lg text-tertiary">
<span class="material-symbols-outlined" data-icon="person_add">person_add</span>
</div>
<span class="{{ $customersChange >= 0 ? 'text-primary' : 'text-error' }} font-label-sm text-label-sm flex items-center gap-1">
                        {{ $customersChange >= 0 ? '+' : '' }}{{ number_format($customersChange, 1) }}% <span class="material-symbols-outlined !text-[14px]" data-icon="{{ $customersChange >= 0 ? 'trending_up' : 'trending_down' }}">{{ $customersChange >= 0 ? 'trending_up' : 'trending_down' }}</span>
</span>
</div>
<p class="text-on-surface-variant font-label-md text-label-md uppercase tracking-wider mb-xs">New Khách hàng</p>
<h3 class="font-headline-md text-headline-md">{{ number_format($newCustomers) }}</h3>
</div>
<div class="glass-card p-lg rounded-2xl shadow-sm hover:shadow-md transition-shadow">
<div class="flex justify-between items-start mb-sm">
<div class="p-xs bg-outline-variant/20 rounded-lg text-outline">
<span class="material-symbols-outlined" data-icon="inventory_2">inventory_2</span>
</div>
<span class="text-tertiary font-label-sm text-label-sm">Tổng: {{ $totalProductsDb }}</span>
</div>
<p class="text-on-surface-variant font-label-md text-label-md uppercase tracking-wider mb-xs">Sản phẩm đang bán</p>
<h3 class="font-headline-md text-headline-md">{{ number_format($activeProductsPercent, 0) }}%</h3>
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
<canvas id="revenueChart" class="w-full h-full"></canvas>
</div>
</div>
<!-- Sản Phẩm Hàng Đầu List -->
<div class="glass-card p-xl rounded-3xl flex flex-col">
<h3 class="font-title-lg text-title-lg mb-lg">Top Performing Sản phẩm</h3>
<div class="flex flex-col gap-md flex-1">
@forelse($topProducts as $product)
<div class="flex items-center gap-md p-sm hover:bg-surface-container-high rounded-2xl transition-colors cursor-pointer">
<div class="w-12 h-12 rounded-xl overflow-hidden bg-surface-container">
<img class="w-full h-full object-cover" src="{{ $product->image }}"/>
</div>
<div class="flex-1">
<h4 class="font-body-lg text-body-lg font-bold">{{ $product->name }}</h4>
<p class="text-on-surface-variant text-label-md">{{ $product->total_sold }} sold</p>
</div>
<span class="text-primary font-bold">+{{ number_format($product->total_revenue, 0, ',', '.') }} VNĐ</span>
</div>
@empty
<div class="p-sm text-center text-on-surface-variant">No data available</div>
@endforelse
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
@forelse($recentOrders as $order)
<tr class="hover:bg-surface-container-low transition-colors group">
<td class="px-xl py-md font-body-md text-body-md font-bold">#{{ $order->order_code }}</td>
<td class="px-xl py-md">
<div class="flex items-center gap-xs">
<span class="material-symbols-outlined !text-lg text-outline" data-icon="account_circle">account_circle</span>
<span class="font-body-md text-body-md">{{ $order->customer_name }}</span>
</div>
</td>
<td class="px-xl py-md">
<span class="px-3 py-1 
    {{ $order->status === 'completed' ? 'bg-primary/10 text-primary' : 
       ($order->status === 'pending' ? 'bg-secondary/10 text-secondary' : 
       'bg-tertiary/10 text-tertiary') }} 
    rounded-full text-label-sm font-bold capitalize">
    {{ $order->status }}
</span>
</td>
<td class="px-xl py-md font-body-md text-body-md">{{ number_format($order->total_amount, 0, ',', '.') }} VNĐ</td>
<td class="px-xl py-md font-body-md text-body-md text-on-surface-variant">{{ \Carbon\Carbon::parse($order->created_at)->diffForHumans() }}</td>
<td class="px-xl py-md">
<a href="/admin/orders?status={{ $order->status }}" class="p-xs text-outline hover:text-primary transition-colors inline-block">
<span class="material-symbols-outlined" data-icon="visibility">visibility</span>
</a>
</td>
</tr>
@empty
<tr>
<td colspan="6" class="px-xl py-md text-center text-on-surface-variant">No recent orders</td>
</tr>
@endforelse
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        
        // Gradient for line chart
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(76, 175, 80, 0.2)');
        gradient.addColorStop(1, 'rgba(76, 175, 80, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Doanh thu',
                    data: {!! json_encode($chartData) !!},
                    borderColor: '#006e1c',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#006e1c',
                    pointBorderColor: '#ffffff',
                    pointRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value);
                            },
                            color: 'rgba(63, 74, 60, 0.6)'
                        },
                        grid: {
                            display: false,
                        },
                        border: { display: false }
                    },
                    x: {
                        ticks: {
                            color: 'rgba(63, 74, 60, 0.6)'
                        },
                        grid: {
                            display: false
                        },
                        border: { display: false }
                    }
                }
            }
        });
    });
</script>
@endpush
