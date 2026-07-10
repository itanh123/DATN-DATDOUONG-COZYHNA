@extends('layouts.admin')

@section('title', 'Báo cáo')

@section('content')
<main class="ml-[280px] pt-24 px-gutter pb-xl min-h-screen">
<!-- Page Title & Ngày Filter -->
<section class="flex flex-col md:flex-row md:items-end justify-between mb-xl gap-md">
<div>
<h3 class="font-headline-lg text-headline-lg text-on-surface">Báo cáo &amp; Analytics</h3>
<p class="font-body-md text-body-md text-on-surface-variant">Comprehensive breakdown of your beverage store performance.</p>
</div>
<div class="flex items-center gap-sm">
<div class="bg-surface-container-lowest border border-outline-variant rounded-lg px-md py-sm flex items-center gap-xs text-body-md shadow-sm">
<span class="material-symbols-outlined text-primary text-body-lg">date_range</span>
<span class="font-medium">Oct 1 - Oct 31, 2024</span>
</div>
<button class="bg-primary-container text-on-primary-container px-lg py-sm rounded-lg font-label-md text-label-md flex items-center gap-xs hover:shadow-md transition-all">
<span class="material-symbols-outlined text-body-lg">download</span>
                    Export Report
                </button>
</div>
</section>
<!-- Executive Summary Cards -->
<section class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-lg mb-xl">
<!-- Tổng cộng Doanh thu -->
<div class="bg-surface-container-lowest p-lg rounded-xl border border-outline-variant shadow-sm hover:shadow-md transition-shadow">
<div class="flex justify-between items-start mb-sm">
<p class="font-label-md text-label-md text-on-surface-variant">Tổng cộng Doanh thu</p>
<span class="p-xs bg-primary-container/10 text-primary rounded-lg material-symbols-outlined">payments</span>
</div>
<div class="flex items-baseline gap-xs">
<h4 class="font-headline-md text-headline-md text-on-surface">$48,250.00</h4>
</div>
<div class="flex items-center gap-xs mt-xs text-primary">
<span class="material-symbols-outlined text-[18px]">trending_up</span>
<span class="font-label-md text-label-md">+12.5% vs last month</span>
</div>
</div>
<!-- Average Order Value -->
<div class="bg-surface-container-lowest p-lg rounded-xl border border-outline-variant shadow-sm hover:shadow-md transition-shadow">
<div class="flex justify-between items-start mb-sm">
<p class="font-label-md text-label-md text-on-surface-variant">Avg. Order Value</p>
<span class="p-xs bg-secondary-container/20 text-secondary rounded-lg material-symbols-outlined">shopping_bag</span>
</div>
<div class="flex items-baseline gap-xs">
<h4 class="font-headline-md text-headline-md text-on-surface">$26.20</h4>
</div>
<div class="flex items-center gap-xs mt-xs text-primary">
<span class="material-symbols-outlined text-[18px]">trending_up</span>
<span class="font-label-md text-label-md">+3.1%</span>
</div>
</div>
<!-- Khách hàng Retention -->
<div class="bg-surface-container-lowest p-lg rounded-xl border border-outline-variant shadow-sm hover:shadow-md transition-shadow">
<div class="flex justify-between items-start mb-sm">
<p class="font-label-md text-label-md text-on-surface-variant">Retention Rate</p>
<span class="p-xs bg-tertiary-container/10 text-tertiary rounded-lg material-symbols-outlined">group</span>
</div>
<div class="flex items-baseline gap-xs">
<h4 class="font-headline-md text-headline-md text-on-surface">68%</h4>
</div>
<div class="flex items-center gap-xs mt-xs text-primary">
<span class="material-symbols-outlined text-[18px]">trending_up</span>
<span class="font-label-md text-label-md">+5.0%</span>
</div>
</div>
<!-- Gross Margin -->
<div class="bg-surface-container-lowest p-lg rounded-xl border border-outline-variant shadow-sm hover:shadow-md transition-shadow">
<div class="flex justify-between items-start mb-sm">
<p class="font-label-md text-label-md text-on-surface-variant">Gross Margin</p>
<span class="p-xs bg-surface-variant/30 text-on-surface-variant rounded-lg material-symbols-outlined">bar_chart_4_bars</span>
</div>
<div class="flex items-baseline gap-xs">
<h4 class="font-headline-md text-headline-md text-on-surface">42%</h4>
</div>
<div class="flex items-center gap-xs mt-xs text-on-surface-variant">
<span class="material-symbols-outlined text-[18px]">horizontal_rule</span>
<span class="font-label-md text-label-md">Stable performance</span>
</div>
</div>
</section>
<!-- Bento Grid Visualizations -->
<div class="grid grid-cols-12 gap-lg mb-xl">
<!-- Sales Trend (Large Chart) -->
<div class="col-span-12 lg:col-span-8 bg-surface-container-lowest rounded-xl border border-outline-variant p-lg shadow-sm">
<div class="flex justify-between items-center mb-xl">
<h5 class="font-title-lg text-title-lg text-on-surface">Daily Sales Trend</h5>
<div class="flex items-center gap-xs">
<span class="w-3 h-3 rounded-full bg-primary"></span>
<span class="font-label-sm text-label-sm text-on-surface-variant">Current Period</span>
</div>
</div>
<!-- Simple SVG Data Viz Mockup -->
<div class="relative h-[280px] w-full mt-lg">
<svg class="w-full h-full" viewbox="0 0 800 200">
<defs>
<lineargradient id="chartGradient" x1="0" x2="0" y1="0" y2="1">
<stop offset="0%" stop-color="#4caf50" stop-opacity="0.2"></stop>
<stop offset="100%" stop-color="#4caf50" stop-opacity="0"></stop>
</lineargradient>
</defs>
<!-- Grid Lines -->
<line stroke="#e2e8f0" stroke-width="1" x1="0" x2="800" y1="0" y2="0"></line>
<line stroke="#e2e8f0" stroke-width="1" x1="0" x2="800" y1="50" y2="50"></line>
<line stroke="#e2e8f0" stroke-width="1" x1="0" x2="800" y1="100" y2="100"></line>
<line stroke="#e2e8f0" stroke-width="1" x1="0" x2="800" y1="150" y2="150"></line>
<line stroke="#e2e8f0" stroke-width="1" x1="0" x2="800" y1="200" y2="200"></line>
<!-- Area -->
<path d="M0,200 L0,140 C100,160 200,80 300,100 C400,120 500,40 600,60 C700,80 800,20 800,20 L800,200 Z" fill="url(#chartGradient)"></path>
<!-- Line -->
<path d="M0,140 C100,160 200,80 300,100 C400,120 500,40 600,60 C700,80 800,20" fill="none" stroke="#4caf50" stroke-linecap="round" stroke-width="3"></path>
<!-- Data Points -->
<circle cx="300" cy="100" fill="white" r="4" stroke="#4caf50" stroke-width="2"></circle>
<circle cx="600" cy="60" fill="white" r="4" stroke="#4caf50" stroke-width="2"></circle>
</svg>
<div class="flex justify-between mt-sm font-label-sm text-label-sm text-on-surface-variant">
<span>Oct 1</span>
<span>Oct 8</span>
<span>Oct 15</span>
<span>Oct 22</span>
<span>Oct 31</span>
</div>
</div>
</div>
<!-- Category Distribution (Medium Chart) -->
<div class="col-span-12 lg:col-span-4 bg-surface-container-lowest rounded-xl border border-outline-variant p-lg shadow-sm flex flex-col">
<h5 class="font-title-lg text-title-lg text-on-surface mb-xl">Category Mix</h5>
<div class="flex-grow flex items-center justify-center relative py-md">
<svg class="w-48 h-48 -rotate-90" viewbox="0 0 100 100">
<circle cx="50" cy="50" fill="none" r="40" stroke="#e2e8f0" stroke-width="12"></circle>
<circle cx="50" cy="50" fill="none" r="40" stroke="#4caf50" stroke-dasharray="113.1 251.3" stroke-width="12"></circle> <!-- 45% Coffee -->
<circle cx="50" cy="50" fill="none" r="40" stroke="#b9f474" stroke-dasharray="75.4 251.3" stroke-dashoffset="-113.1" stroke-width="12"></circle> <!-- 30% Dairy -->
<circle cx="50" cy="50" fill="none" r="40" stroke="#fabd00" stroke-dasharray="37.7 251.3" stroke-dashoffset="-188.5" stroke-width="12"></circle> <!-- 15% Snacks -->
<circle cx="50" cy="50" fill="none" r="40" stroke="#3f4a3c" stroke-dasharray="25.1 251.3" stroke-dashoffset="-226.2" stroke-width="12"></circle> <!-- 10% Merch -->
</svg>
<div class="absolute text-center">
<p class="font-headline-md text-headline-md text-on-surface">100%</p>
<p class="font-label-sm text-label-sm text-on-surface-variant">Tổng cộng Volume</p>
</div>
</div>
<div class="space-y-xs mt-md">
<div class="flex justify-between items-center text-body-md">
<div class="flex items-center gap-xs">
<span class="w-2 h-2 rounded-full bg-primary"></span>
<span>Coffee</span>
</div>
<span class="font-semibold">45%</span>
</div>
<div class="flex justify-between items-center text-body-md">
<div class="flex items-center gap-xs">
<span class="w-2 h-2 rounded-full bg-secondary"></span>
<span>Dairy</span>
</div>
<span class="font-semibold">30%</span>
</div>
<div class="flex justify-between items-center text-body-md">
<div class="flex items-center gap-xs">
<span class="w-2 h-2 rounded-full bg-tertiary-fixed-dim"></span>
<span>Snacks</span>
</div>
<span class="font-semibold">15%</span>
</div>
</div>
</div>
</div>
<div class="grid grid-cols-12 gap-lg">
<!-- Product Performance Table -->
<div class="col-span-12 xl:col-span-8 bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden">
<div class="p-lg border-b border-outline-variant flex justify-between items-center">
<h5 class="font-title-lg text-title-lg text-on-surface">Top 5 Selling Sản phẩm</h5>
<button class="text-primary font-label-md text-label-md hover:underline">Xem Tất Cả Sản phẩm</button>
</div>
<div class="overflow-x-auto">
<table class="w-full text-left">
<thead class="bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase tracking-wider">
<tr>
<th class="px-lg py-md">Product Name</th>
<th class="px-lg py-md text-right">Units Sold</th>
<th class="px-lg py-md text-right">Doanh thu</th>
<th class="px-lg py-md text-right">Growth</th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant">
<tr class="hover:bg-surface-container-low/50 transition-colors">
<td class="px-lg py-md">
<div class="flex items-center gap-sm">
<div class="w-10 h-10 rounded-lg bg-surface-variant flex items-center justify-center">
<span class="material-symbols-outlined text-primary">coffee</span>
</div>
<span class="font-medium text-body-md">Ethiopian Arabica</span>
</div>
</td>
<td class="px-lg py-md text-right font-body-md">842</td>
<td class="px-lg py-md text-right font-body-md font-semibold">$12,630</td>
<td class="px-lg py-md text-right">
<span class="text-primary text-label-md">+8.4%</span>
</td>
</tr>
<tr class="hover:bg-surface-container-low/50 transition-colors">
<td class="px-lg py-md">
<div class="flex items-center gap-sm">
<div class="w-10 h-10 rounded-lg bg-surface-variant flex items-center justify-center">
<span class="material-symbols-outlined text-primary">eco</span>
</div>
<span class="font-medium text-body-md">Organic Oat Milk</span>
</div>
</td>
<td class="px-lg py-md text-right font-body-md">620</td>
<td class="px-lg py-md text-right font-body-md font-semibold">$4,340</td>
<td class="px-lg py-md text-right">
<span class="text-primary text-label-md">+12.1%</span>
</td>
</tr>
<tr class="hover:bg-surface-container-low/50 transition-colors">
<td class="px-lg py-md">
<div class="flex items-center gap-sm">
<div class="w-10 h-10 rounded-lg bg-surface-variant flex items-center justify-center">
<span class="material-symbols-outlined text-primary">local_cafe</span>
</div>
<span class="font-medium text-body-md">Cold Brew Concentrate</span>
</div>
</td>
<td class="px-lg py-md text-right font-body-md">415</td>
<td class="px-lg py-md text-right font-body-md font-semibold">$8,300</td>
<td class="px-lg py-md text-right">
<span class="text-on-surface-variant text-label-md">-1.2%</span>
</td>
</tr>
<tr class="hover:bg-surface-container-low/50 transition-colors">
<td class="px-lg py-md">
<div class="flex items-center gap-sm">
<div class="w-10 h-10 rounded-lg bg-surface-variant flex items-center justify-center">
<span class="material-symbols-outlined text-primary">nutrition</span>
</div>
<span class="font-medium text-body-md">Premium Matcha Powder</span>
</div>
</td>
<td class="px-lg py-md text-right font-body-md">380</td>
<td class="px-lg py-md text-right font-body-md font-semibold">$9,500</td>
<td class="px-lg py-md text-right">
<span class="text-primary text-label-md">+5.7%</span>
</td>
</tr>
<tr class="hover:bg-surface-container-low/50 transition-colors">
<td class="px-lg py-md">
<div class="flex items-center gap-sm">
<div class="w-10 h-10 rounded-lg bg-surface-variant flex items-center justify-center">
<span class="material-symbols-outlined text-primary">spa</span>
</div>
<span class="font-medium text-body-md">Reusable Bamboo Cup</span>
</div>
</td>
<td class="px-lg py-md text-right font-body-md">210</td>
<td class="px-lg py-md text-right font-body-md font-semibold">$3,150</td>
<td class="px-lg py-md text-right">
<span class="text-primary text-label-md">+15.0%</span>
</td>
</tr>
</tbody>
</table>
</div>
</div>
<!-- Operational Insights Sidebar -->
<div class="col-span-12 xl:col-span-4 space-y-lg">
<!-- Busiest Hours Widget -->
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-lg shadow-sm">
<h5 class="font-title-lg text-title-lg text-on-surface mb-lg">Busiest Hours</h5>
<div class="flex items-end justify-between h-32 gap-xs px-xs">
<!-- Simulated peak hour bars -->
<div class="flex flex-col items-center gap-xs flex-1">
<div class="w-full bg-surface-variant rounded-t-sm h-[40%]"></div>
<span class="text-[10px] text-on-surface-variant">8am</span>
</div>
<div class="flex flex-col items-center gap-xs flex-1">
<div class="w-full bg-primary/60 rounded-t-sm h-[90%] chart-bar"></div>
<span class="text-[10px] font-bold text-primary">10am</span>
</div>
<div class="flex flex-col items-center gap-xs flex-1">
<div class="w-full bg-surface-variant rounded-t-sm h-[60%]"></div>
<span class="text-[10px] text-on-surface-variant">12pm</span>
</div>
<div class="flex flex-col items-center gap-xs flex-1">
<div class="w-full bg-surface-variant rounded-t-sm h-[45%]"></div>
<span class="text-[10px] text-on-surface-variant">2pm</span>
</div>
<div class="flex flex-col items-center gap-xs flex-1">
<div class="w-full bg-primary/60 rounded-t-sm h-[85%] chart-bar"></div>
<span class="text-[10px] font-bold text-primary">4pm</span>
</div>
<div class="flex flex-col items-center gap-xs flex-1">
<div class="w-full bg-surface-variant rounded-t-sm h-[30%]"></div>
<span class="text-[10px] text-on-surface-variant">6pm</span>
</div>
</div>
<p class="font-body-md text-body-md text-on-surface-variant mt-lg italic">
                        Staff optimization: Increase shifts between 10am-11am for peak efficiency.
                    </p>
</div>
<!-- Popular Upsells Widget -->
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-lg shadow-sm">
<h5 class="font-title-lg text-title-lg text-on-surface mb-lg">Popular Upsells</h5>
<div class="space-y-md">
<div class="flex items-center justify-between p-sm rounded-lg bg-surface-container-low border border-outline-variant/30">
<div class="flex items-center gap-sm">
<div class="bg-white p-xs rounded shadow-xs">
<span class="material-symbols-outlined text-primary text-[20px]">bakery_dining</span>
</div>
<div>
<p class="font-label-md text-label-md text-on-surface">Protein Cookie</p>
<p class="text-[10px] text-on-surface-variant">Added to 24% of orders</p>
</div>
</div>
<span class="material-symbols-outlined text-secondary">trending_up</span>
</div>
<div class="flex items-center justify-between p-sm rounded-lg bg-surface-container-low border border-outline-variant/30">
<div class="flex items-center gap-sm">
<div class="bg-white p-xs rounded shadow-xs">
<span class="material-symbols-outlined text-primary text-[20px]">add_moderator</span>
</div>
<div>
<p class="font-label-md text-label-md text-on-surface">Immunity Boost</p>
<p class="text-[10px] text-on-surface-variant">Added to 18% of beverages</p>
</div>
</div>
<span class="material-symbols-outlined text-secondary">trending_up</span>
</div>
</div>
</div>
</div>
</div>
</main>
@endsection

@push('scripts')
<script>

        // Simple micro-interactions
        document.querySelectorAll('a, button').forEach(el => {
            el.addEventListener('mousedown', () => {
                el.style.transform = 'scale(0.97)';
            });
            el.addEventListener('mouseup', () => {
                el.style.transform = '';
            });
            el.addEventListener('mouseleave', () => {
                el.style.transform = '';
            });
        });

        // Dynamic chart value simulation
        const bars = document.querySelectorAll('.chart-bar');
        bars.forEach((bar, index) => {
            bar.style.animationDelay = `${index * 0.1}s`;
        });
    
</script>
@endpush
