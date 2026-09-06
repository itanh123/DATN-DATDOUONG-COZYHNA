@extends('layouts.admin')

@section('title', 'Báo cáo')

@section('content')
<main class="md:ml-[280px] pt-24 px-gutter pb-xl min-h-screen">
<!-- Page Title & Ngày Filter -->
<section class="flex flex-col md:flex-row md:items-end justify-between mb-xl gap-md">
<div>
<h3 class="font-headline-lg text-headline-lg text-on-surface">Báo cáo &amp; Analytics</h3>
<p class="font-body-md text-body-md text-on-surface-variant">{{ __('Comprehensive breakdown of your beverage store performance.') }}</p>
</div>
<div class="flex items-center gap-sm">
    <form method="GET" action="/admin/reports" class="flex flex-wrap items-center gap-2 m-0 w-full sm:w-auto">
        <select name="period" onchange="if(this.value !== 'custom') this.form.submit(); else { document.getElementById('custom-date-fields').classList.remove('hidden'); }" class="px-3 py-2 border border-outline-variant rounded-lg text-body-md focus:ring-0 focus:border-primary bg-surface-container-lowest cursor-pointer shadow-sm font-medium">
            <option value="all" {{ ($period ?? __('all')) == 'all' ? 'selected' : '' }}>Tất cả thời gian</option>
            <option value="today" {{ ($period ?? __('all')) == 'today' ? 'selected' : '' }}>Hôm nay</option>
            <option value="week" {{ ($period ?? __('all')) == 'week' ? 'selected' : '' }}>Tuần này</option>
            <option value="month" {{ ($period ?? __('all')) == 'month' ? 'selected' : '' }}>Tháng này</option>
            <option value="year" {{ ($period ?? __('all')) == 'year' ? 'selected' : '' }}>Năm nay</option>
            <option value="custom" {{ ($period ?? __('all')) == 'custom' ? 'selected' : '' }}>Tùy chỉnh</option>
        </select>
        
        <div id="custom-date-fields" class="flex flex-wrap items-center gap-2 {{ ($period ?? __('all')) == 'custom' ? '' : 'hidden' }}">
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="px-3 py-2 border border-outline-variant rounded-lg text-body-md focus:ring-0 focus:border-primary bg-surface-container-lowest max-w-[140px] shadow-sm">
            <span class="text-on-surface-variant">-</span>
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="px-3 py-2 border border-outline-variant rounded-lg text-body-md focus:ring-0 focus:border-primary bg-surface-container-lowest max-w-[140px] shadow-sm">
            <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg text-label-md font-medium hover:opacity-90 whitespace-nowrap shadow-sm">Lọc</button>
        </div>
    </form>
<button class="bg-primary-container text-on-primary-container px-lg py-sm rounded-lg font-label-md text-label-md flex items-center gap-xs hover:shadow-md transition-all">
<span class="material-symbols-outlined text-body-lg">download</span>{{ __('Export Report') }}</button>
</div>
</section>
<!-- Executive Summary Cards -->
<section class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-lg mb-xl">
<!-- Tổng cộng Doanh thu -->
<a href="/admin/orders" class="block bg-surface-container-lowest p-lg rounded-xl border border-outline-variant shadow-sm hover:shadow-md hover:border-primary transition-all">
<div class="flex justify-between items-start mb-sm">
<p class="font-label-md text-label-md text-on-surface-variant">Tổng cộng Doanh thu</p>
<span class="p-xs bg-primary-container/10 text-primary rounded-lg material-symbols-outlined">payments</span>
</div>
<div class="flex items-baseline gap-xs">
<h4 class="font-headline-md text-headline-md text-on-surface">{{ number_format($totalRevenue) }}đ</h4>
</div>
<div class="flex items-center gap-xs mt-xs text-primary">
<span class="font-label-md text-label-md">Đã hoàn thành</span>
</div>
</a>
<!-- Average Order Value -->
<div class="bg-surface-container-lowest p-lg rounded-xl border border-outline-variant shadow-sm hover:shadow-md transition-shadow">
<div class="flex justify-between items-start mb-sm">
<p class="font-label-md text-label-md text-on-surface-variant">Giá trị ĐH Trung bình</p>
<span class="p-xs bg-secondary-container/20 text-secondary rounded-lg material-symbols-outlined">shopping_bag</span>
</div>
<div class="flex items-baseline gap-xs">
<h4 class="font-headline-md text-headline-md text-on-surface">{{ number_format($avgOrderValue) }}đ</h4>
</div>
<div class="flex items-center gap-xs mt-xs text-primary">
<span class="font-label-md text-label-md">Từ {{ number_format($totalOrders) }} đơn hàng</span>
</div>
</div>
<!-- Sản phẩm đã bán -->
<a href="{{ request()->fullUrlWithQuery(['limit' => 'all']) }}#products-table" class="block bg-surface-container-lowest p-lg rounded-xl border border-outline-variant shadow-sm hover:shadow-md hover:border-primary transition-all">
<div class="flex justify-between items-start mb-sm">
<p class="font-label-md text-label-md text-on-surface-variant">Sản phẩm đã bán</p>
<span class="p-xs bg-tertiary-container/10 text-tertiary rounded-lg material-symbols-outlined">local_cafe</span>
</div>
<div class="flex items-baseline gap-xs">
<h4 class="font-headline-md text-headline-md text-on-surface">{{ number_format($totalItemsSold) }}</h4>
</div>
<div class="flex items-center gap-xs mt-xs text-primary">
<span class="font-label-md text-label-md">Xem chi tiết</span>
</div>
</a>
<!-- Gross Margin -->
<div class="bg-surface-container-lowest p-lg rounded-xl border border-outline-variant shadow-sm hover:shadow-md transition-shadow">
<div class="flex justify-between items-start mb-sm">
<p class="font-label-md text-label-md text-on-surface-variant">Tổng Khách hàng</p>
<span class="p-xs bg-surface-variant/30 text-on-surface-variant rounded-lg material-symbols-outlined">group</span>
</div>
<div class="flex items-baseline gap-xs">
<h4 class="font-headline-md text-headline-md text-on-surface">{{ number_format($uniqueCustomers) }}</h4>
</div>
<div class="flex items-center gap-xs mt-xs text-on-surface-variant">
<span class="font-label-md text-label-md">Khách hàng duy nhất</span>
</div>
</div>
</section>
<!-- Bento Grid Visualizations -->
<div class="grid grid-cols-12 gap-lg mb-xl">
<!-- Sales Trend (Large Chart) -->
<div class="col-span-12 lg:col-span-8 bg-surface-container-lowest rounded-xl border border-outline-variant p-lg shadow-sm">
<div class="flex justify-between items-center mb-xl">
<h5 class="font-title-lg text-title-lg text-on-surface">{{ __('Daily Sales Trend') }}</h5>
<div class="flex items-center gap-xs">
<span class="w-3 h-3 rounded-full bg-primary"></span>
<span class="font-label-sm text-label-sm text-on-surface-variant">{{ __('Current Period') }}</span>
</div>
</div>
<div class="relative h-[280px] w-full mt-lg">
    <canvas id="revenueChart"></canvas>
</div>
</div>
<!-- Category Distribution (Medium Chart) -->
<div class="col-span-12 lg:col-span-4 bg-surface-container-lowest rounded-xl border border-outline-variant p-lg shadow-sm flex flex-col">
<h5 class="font-title-lg text-title-lg text-on-surface mb-xl">{{ __('Category Mix') }}</h5>
<div class="flex-grow flex items-center justify-center relative py-md h-[280px]">
    <canvas id="categoryChart"></canvas>
</div>
</div>
</div>
<div class="grid grid-cols-12 gap-lg">
<!-- Product Performance Table -->
<div id="products-table" class="col-span-12 xl:col-span-8 bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden scroll-mt-24">
<div class="p-lg border-b border-outline-variant flex flex-col sm:flex-row justify-between sm:items-center gap-4">
<div class="flex flex-wrap items-center gap-4">
    <h5 class="font-title-lg text-title-lg text-on-surface whitespace-nowrap">
        {{ request('limit') == 'all' ? 'Tất cả sản phẩm đã bán' : 'Top 5 Sản phẩm bán chạy' }}
    </h5>
    @if(request('limit') == 'all')
        <a href="{{ request()->fullUrlWithQuery(['limit' => null]) }}" class="text-primary text-label-md hover:underline whitespace-nowrap bg-primary/10 px-3 py-1.5 rounded-lg font-medium">{{ __('Xem Top 5') }}</a>
    @else
        <a href="{{ request()->fullUrlWithQuery(['limit' => 'all']) }}" class="text-primary text-label-md hover:underline whitespace-nowrap bg-primary/10 px-3 py-1.5 rounded-lg font-medium">Xem tất cả sản phẩm đã bán</a>
    @endif
</div>

</div>
<div class="overflow-x-auto">
<table class="w-full text-left">
<thead class="bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase tracking-wider">
<tr>
<th class="px-lg py-md">Tên Sản Phẩm</th>
<th class="px-lg py-md text-right">{{ __('Units Sold') }}</th>
<th class="px-lg py-md text-right">{{ __('Doanh thu') }}</th>
<th class="px-lg py-md text-right">{{ __('Growth') }}</th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant">
    @forelse($topProducts as $product)
    <tr class="hover:bg-surface-container-low/50 transition-colors">
        <td class="px-lg py-md">
            <div class="flex items-center gap-sm">
                <div class="w-10 h-10 rounded-lg bg-surface-variant flex items-center justify-center overflow-hidden">
                    @if($product->image)
                        <img src="{{ $product->image }}" class="w-full h-full object-cover">
                    @else
                        <span class="material-symbols-outlined text-primary">local_cafe</span>
                    @endif
                </div>
                <span class="font-medium text-body-md">{{ $product->name }}</span>
            </div>
        </td>
        <td class="px-lg py-md text-right font-body-md">{{ number_format($product->total_sold) }}</td>
        <td class="px-lg py-md text-right font-body-md font-semibold">{{ number_format($product->total_revenue) }}đ</td>
        <td class="px-lg py-md text-right">
            @if(request('limit') != 'all' || $loop->iteration <= 5)
                <span class="inline-flex items-center gap-1 text-error text-label-md font-bold bg-error/10 px-2 py-1 rounded-full whitespace-nowrap">
                    <span class="material-symbols-outlined text-[16px]">local_fire_department</span>{{ __('HOT') }}</span>
            @endif
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="4" class="px-lg py-md text-center text-on-surface-variant italic">Chưa có dữ liệu bán hàng.</td>
    </tr>
    @endforelse
</tbody>
</table>
</div>
</div>
<!-- Operational Insights Sidebar -->
<div class="col-span-12 xl:col-span-4 space-y-lg">
<!-- Busiest Hours Widget -->
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-lg shadow-sm">
<h5 class="font-title-lg text-title-lg text-on-surface mb-lg">{{ __('Busiest Hours') }}</h5>
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
<p class="font-body-md text-body-md text-on-surface-variant mt-lg italic">{{ __('Staff optimization: Increase shifts between 10am-11am for peak efficiency.') }}</p>
</div>
<!-- Popular Upsells Widget -->
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-lg shadow-sm">
<h5 class="font-title-lg text-title-lg text-on-surface mb-lg">{{ __('Popular Upsells') }}</h5>
<div class="space-y-md">
<div class="flex items-center justify-between p-sm rounded-lg bg-surface-container-low border border-outline-variant/30">
<div class="flex items-center gap-sm">
<div class="bg-white p-xs rounded shadow-xs">
<span class="material-symbols-outlined text-primary text-[20px]">bakery_dining</span>
</div>
<div>
<p class="font-label-md text-label-md text-on-surface">{{ __('Protein Cookie') }}</p>
<p class="text-[10px] text-on-surface-variant">{{ __('Added to 24% of orders') }}</p>
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
<p class="font-label-md text-label-md text-on-surface">{{ __('Immunity Boost') }}</p>
<p class="text-[10px] text-on-surface-variant">{{ __('Added to 18% of beverages') }}</p>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const chartLabels = {!! json_encode($chartLabels) !!};
        const chartValues = {!! json_encode($chartValues) !!};
        
        const ctxRev = document.getElementById('revenueChart');
        if(ctxRev) {
            new Chart(ctxRev, {
                type: 'line',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Doanh thu',
                        data: chartValues,
                        borderColor: '#4caf50',
                        backgroundColor: 'rgba(76, 175, 80, 0.2)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        }

        const catLabels = {!! json_encode($catLabels) !!};
        const catData = {!! json_encode($catData) !!};
        const ctxCat = document.getElementById('categoryChart');
        if(ctxCat) {
            new Chart(ctxCat, {
                type: 'doughnut',
                data: {
                    labels: catLabels,
                    datasets: [{
                        data: catData,
                        backgroundColor: [
                            '#4caf50', '#b9f474', '#fabd00', '#3f4a3c', '#8b5cf6', '#ec4899', '#f97316'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right'
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
