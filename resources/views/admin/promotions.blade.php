@extends('layouts.admin')

@section('title', 'Khuyến mãi')

@section('content')
<main class="ml-[280px] pt-16 min-h-screen">
<div class="p-xl space-y-xl max-w-[1600px] mx-auto">
<!-- Hero Metrics Row -->
<section class="bento-grid">
<div class="bg-white p-lg rounded-2xl border border-outline-variant shadow-sm hover:shadow-md transition-shadow group">
<div class="flex justify-between items-start mb-sm">
<div class="p-sm bg-primary/10 rounded-xl text-primary">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">campaign</span>
</div>
<span class="text-primary font-bold text-xs bg-primary/10 px-2 py-1 rounded-full">+2 new</span>
</div>
<p class="text-on-surface-variant font-label-md text-label-md">Hoạt động Khuyến mãi</p>
<h3 class="font-headline-lg text-headline-lg text-on-surface">12 Live</h3>
</div>
<div class="bg-white p-lg rounded-2xl border border-outline-variant shadow-sm hover:shadow-md transition-shadow">
<div class="flex justify-between items-start mb-sm">
<div class="p-sm bg-secondary/10 rounded-xl text-secondary">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">confirmation_number</span>
</div>
<span class="text-secondary font-bold text-xs bg-secondary/10 px-2 py-1 rounded-full">82% fill</span>
</div>
<p class="text-on-surface-variant font-label-md text-label-md">Tổng cộng Redemptions</p>
<h3 class="font-headline-lg text-headline-lg text-on-surface">4,280</h3>
</div>
<div class="bg-white p-lg rounded-2xl border border-outline-variant shadow-sm hover:shadow-md transition-shadow">
<div class="flex justify-between items-start mb-sm">
<div class="p-sm bg-tertiary-container/10 rounded-xl text-tertiary-container">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">trending_up</span>
</div>
<span class="text-tertiary-container font-bold text-xs bg-tertiary-container/10 px-2 py-1 rounded-full">Goal met</span>
</div>
<p class="text-on-surface-variant font-label-md text-label-md">Doanh thu Impact</p>
<h3 class="font-headline-lg text-headline-lg text-on-surface">+15%</h3>
</div>
<div class="bg-white p-lg rounded-2xl border border-outline-variant shadow-sm hover:shadow-md transition-shadow">
<div class="flex justify-between items-start mb-sm">
<div class="p-sm bg-primary-container/10 rounded-xl text-primary-container">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">loyalty</span>
</div>
<span class="text-primary-container font-bold text-xs bg-primary-container/10 px-2 py-1 rounded-full">Peak cycle</span>
</div>
<p class="text-on-surface-variant font-label-md text-label-md">Loyalty Points Issued</p>
<h3 class="font-headline-lg text-headline-lg text-on-surface">250K pts</h3>
</div>
</section>
<!-- Content Area Layout -->
<div class="flex flex-col lg:flex-row gap-xl">
<!-- Main Khuyến mãi Table -->
<section class="flex-grow bg-white rounded-2xl border border-outline-variant shadow-sm overflow-hidden flex flex-col">
<div class="p-lg border-b border-outline-variant flex justify-between items-center bg-surface-container-low/50">
<h2 class="font-headline-md text-headline-md text-on-surface">Hoạt động &amp; Upcoming Khuyến mãi</h2>
<div class="flex gap-sm">
<button class="p-2 border border-outline rounded-lg hover:bg-surface transition-colors flex items-center gap-xs text-on-surface-variant font-label-md">
<span class="material-symbols-outlined text-[20px]">filter_list</span> Filter
                            </button>
<button class="p-2 border border-outline rounded-lg hover:bg-surface transition-colors flex items-center gap-xs text-on-surface-variant font-label-md">
<span class="material-symbols-outlined text-[20px]">download</span> Export
                            </button>
</div>
</div>
<div class="overflow-x-auto custom-scrollbar">
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-surface/50 border-b border-outline-variant">
<th class="px-lg py-md font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Promotion Name</th>
<th class="px-lg py-md font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Type</th>
<th class="px-lg py-md font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Trạng thái</th>
<th class="px-lg py-md font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Performance</th>
<th class="px-lg py-md font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Ngày Range</th>
<th class="px-lg py-md font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider text-right">Thao tác</th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant/50">
<!-- Promotion Row 1 -->
<tr class="hover:bg-surface transition-colors group">
<td class="px-lg py-md">
<div class="flex items-center gap-md">
<div class="w-12 h-12 rounded-xl bg-orange-100 flex items-center justify-center overflow-hidden">
<img class="w-full h-full object-cover" data-alt="A macro photograph of a steaming pumpkin spice latte with a dusting of cinnamon and a single orange leaf on the wooden table. Warm, autumn-themed aesthetic with a cozy coffee shop ambiance and soft glowing lights in the background." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCfodya_9SFKj-zQbrRHEnU8YCcuAGHWFK7R5tQzZYjOUq3KCaEQXGP8bTFikkClz7T-XYI9v0fTg_v8a9Hm8FT2qKHuE9-RZPHmYq4P57gFJdX_2FOErDoSK_CPatUsP5SSnUMx8K4KyyejOCxZBvebJF01bAuna_l-ZWJMNr_t53kSnOvJUqXZpz-Ygv_VaeJzlzZmGhrM4OTLzsE2uo6UqyTaotYXc-5T4NYfYZSNqhSZpDD9UJVLbkS5GDuy6rrEJpT8k9o"/>
</div>
<div>
<p class="font-body-lg text-body-lg text-on-surface font-semibold">Seasonal Pumpkin Spice 20% Off</p>
<p class="text-xs text-on-surface-variant">Code: PUMPKIN24</p>
</div>
</div>
</td>
<td class="px-lg py-md">
<span class="px-3 py-1 bg-secondary-container/30 text-secondary font-label-sm text-xs rounded-full">Seasonal Campaign</span>
</td>
<td class="px-lg py-md">
<div class="flex items-center gap-xs text-primary font-medium">
<span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
<span class="text-sm">Hoạt động</span>
</div>
</td>
<td class="px-lg py-md">
<div class="space-y-1">
<div class="flex justify-between text-xs font-medium">
<span>1,240 redemptions</span>
<span class="text-primary">12.4% conv.</span>
</div>
<div class="w-32 h-1.5 bg-outline-variant/30 rounded-full overflow-hidden">
<div class="h-full bg-primary rounded-full w-[75%]"></div>
</div>
</div>
</td>
<td class="px-lg py-md text-sm text-on-surface-variant">
                                        Oct 01 - Nov 30
                                    </td>
<td class="px-lg py-md text-right">
<div class="flex justify-end gap-xs opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-2 hover:bg-surface-container-high rounded-lg text-primary"><span class="material-symbols-outlined">edit</span></button>
<button class="p-2 hover:bg-surface-container-high rounded-lg text-on-surface-variant"><span class="material-symbols-outlined">pause_circle</span></button>
<button class="p-2 hover:bg-surface-container-high rounded-lg text-on-surface-variant"><span class="material-symbols-outlined">bar_chart</span></button>
</div>
</td>
</tr>
<!-- Promotion Row 2 -->
<tr class="hover:bg-surface transition-colors group">
<td class="px-lg py-md">
<div class="flex items-center gap-md">
<div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center overflow-hidden">
<img class="w-full h-full object-cover" data-alt="A minimalist and fresh overhead shot of two glasses of chilled matcha green tea on a clean white marble surface. Bright, high-key lighting creates a vibrant and healthy aesthetic with fresh green tea leaves scattered artistically around the glasses." src="https://lh3.googleusercontent.com/aida-public/AB6AXuA8k97JfuC9hOvjn77OU0RiBNncT4YDBnocatqrSiICq0jhGuZQj-3HFN-EXWXpaFXes3X05d1rCUWWRYAD4auFaEqNFmdcssYxzixCM14JTqWrOroJuPItbg3lKPFzDA2Ss7w2tHMlC5_1CXLVnZkXh1MJe4nPY9KMfPZJ8l1-fcDRTnWrmvlfZeiGZs59R7Gk5O-QdOMF-75mkHkT2nuu6caijwlJP9hILnYDZp9ifygcTNpJCTm3l_DO561GxpAlFmKURiET"/>
</div>
<div>
<p class="font-body-lg text-body-lg text-on-surface font-semibold">First-Time User BOGO</p>
<p class="text-xs text-on-surface-variant">Auto-applied at checkout</p>
</div>
</div>
</td>
<td class="px-lg py-md">
<span class="px-3 py-1 bg-primary-container/20 text-on-primary-container font-label-sm text-xs rounded-full">BOGO</span>
</td>
<td class="px-lg py-md">
<div class="flex items-center gap-xs text-primary font-medium">
<span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
<span class="text-sm">Hoạt động</span>
</div>
</td>
<td class="px-lg py-md">
<div class="space-y-1">
<div class="flex justify-between text-xs font-medium">
<span>840 redemptions</span>
<span class="text-primary">18.2% conv.</span>
</div>
<div class="w-32 h-1.5 bg-outline-variant/30 rounded-full overflow-hidden">
<div class="h-full bg-primary rounded-full w-[45%]"></div>
</div>
</div>
</td>
<td class="px-lg py-md text-sm text-on-surface-variant">
                                        Always On
                                    </td>
<td class="px-lg py-md text-right">
<div class="flex justify-end gap-xs opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-2 hover:bg-surface-container-high rounded-lg text-primary"><span class="material-symbols-outlined">edit</span></button>
<button class="p-2 hover:bg-surface-container-high rounded-lg text-on-surface-variant"><span class="material-symbols-outlined">pause_circle</span></button>
<button class="p-2 hover:bg-surface-container-high rounded-lg text-on-surface-variant"><span class="material-symbols-outlined">bar_chart</span></button>
</div>
</td>
</tr>
<!-- Promotion Row 3 -->
<tr class="hover:bg-surface transition-colors group">
<td class="px-lg py-md">
<div class="flex items-center gap-md">
<div class="w-12 h-12 rounded-xl bg-tertiary-fixed-dim/20 flex items-center justify-center overflow-hidden">
<img class="w-full h-full object-cover" data-alt="A stylized illustration of a gold loyalty card glowing with light against a dark, premium background. Radiant geometric sparks and light-mode organic shapes suggest a high-value membership experience. The color palette is rich gold, deep forest green, and creamy white." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAXRNdG2VhsOMO6Ylm-emp625KAxDI8D9wGMSWsiO-TvW0xSSdC66xy_gcZmyjR5mY-uLgXaLfQxLo_UKepEHjva9hYLa2JXYwJUD1mg-SIpeCQaRPJ0c-PGAIWcuaO17eaRmq4JFG6r1k3WShaVj2inZjhGRnKJzHO4c730cleVYb6X4T9_XY7uMe1GgXtugy4fYs4CzikbIAH9tJOZYibKpA-uyVnE0j4nn19nMPAEXf1xYnaZ0kFdhpTvGTf4XEfmPpTdBv_"/>
</div>
<div>
<p class="font-body-lg text-body-lg text-on-surface font-semibold">Loyalty Member Double Points Weekend</p>
<p class="text-xs text-on-surface-variant">Members only event</p>
</div>
</div>
</td>
<td class="px-lg py-md">
<span class="px-3 py-1 bg-tertiary-container/20 text-tertiary-container font-label-sm text-xs rounded-full">Loyalty Reward</span>
</td>
<td class="px-lg py-md">
<div class="flex items-center gap-xs text-tertiary font-medium">
<span class="w-2 h-2 rounded-full bg-tertiary"></span>
<span class="text-sm">Scheduled</span>
</div>
</td>
<td class="px-lg py-md">
<div class="space-y-1">
<div class="flex justify-between text-xs font-medium">
<span>-</span>
<span class="text-on-surface-variant">-</span>
</div>
<div class="w-32 h-1.5 bg-outline-variant/30 rounded-full overflow-hidden">
<div class="h-full bg-outline w-0"></div>
</div>
</div>
</td>
<td class="px-lg py-md text-sm text-on-surface-variant">
                                        Nov 15 - Nov 17
                                    </td>
<td class="px-lg py-md text-right">
<div class="flex justify-end gap-xs opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-2 hover:bg-surface-container-high rounded-lg text-primary"><span class="material-symbols-outlined">edit</span></button>
<button class="p-2 hover:bg-surface-container-high rounded-lg text-on-surface-variant"><span class="material-symbols-outlined">delete</span></button>
<button class="p-2 hover:bg-surface-container-high rounded-lg text-on-surface-variant"><span class="material-symbols-outlined">visibility</span></button>
</div>
</td>
</tr>
</tbody>
</table>
</div>
<div class="p-md bg-surface/30 border-t border-outline-variant flex justify-between items-center px-lg">
<p class="text-xs text-on-surface-variant">Showing 1-3 of 12 active promotions</p>
<div class="flex gap-1">
<button class="w-8 h-8 flex items-center justify-center rounded bg-white border border-outline-variant text-on-surface-variant hover:bg-surface transition-colors"><span class="material-symbols-outlined">chevron_left</span></button>
<button class="w-8 h-8 flex items-center justify-center rounded bg-primary text-white">1</button>
<button class="w-8 h-8 flex items-center justify-center rounded bg-white border border-outline-variant text-on-surface-variant hover:bg-surface transition-colors">2</button>
<button class="w-8 h-8 flex items-center justify-center rounded bg-white border border-outline-variant text-on-surface-variant hover:bg-surface transition-colors"><span class="material-symbols-outlined">chevron_right</span></button>
</div>
</div>
</section>
<!-- Side Panel/Widgets -->
<aside class="w-full lg:w-[360px] space-y-xl">
<!-- Quick Create Widget -->
<div class="bg-white p-lg rounded-2xl border border-outline-variant shadow-sm">
<h4 class="font-title-lg text-title-lg text-on-surface mb-md">Quick Create</h4>
<div class="space-y-sm">
<button class="w-full flex items-center justify-between p-md bg-surface-container-low hover:bg-surface-container-high rounded-xl transition-all group">
<div class="flex items-center gap-md">
<div class="p-2 bg-primary/10 rounded-lg text-primary">
<span class="material-symbols-outlined">confirmation_number</span>
</div>
<span class="font-body-md text-body-md text-on-surface font-semibold">New Giảm giá Code</span>
</div>
<span class="material-symbols-outlined text-outline group-hover:translate-x-1 transition-transform">arrow_forward</span>
</button>
<button class="w-full flex items-center justify-between p-md bg-surface-container-low hover:bg-surface-container-high rounded-xl transition-all group">
<div class="flex items-center gap-md">
<div class="p-2 bg-tertiary-container/10 rounded-lg text-tertiary-container">
<span class="material-symbols-outlined">military_tech</span>
</div>
<span class="font-body-md text-body-md text-on-surface font-semibold">New Loyalty Reward</span>
</div>
<span class="material-symbols-outlined text-outline group-hover:translate-x-1 transition-transform">arrow_forward</span>
</button>
<button class="w-full flex items-center justify-between p-md bg-surface-container-low hover:bg-surface-container-high rounded-xl transition-all group">
<div class="flex items-center gap-md">
<div class="p-2 bg-secondary/10 rounded-lg text-secondary">
<span class="material-symbols-outlined">celebration</span>
</div>
<span class="font-body-md text-body-md text-on-surface font-semibold">New Seasonal Campaign</span>
</div>
<span class="material-symbols-outlined text-outline group-hover:translate-x-1 transition-transform">arrow_forward</span>
</button>
</div>
</div>
<!-- Top Performing Leaderboard -->
<div class="bg-white p-lg rounded-2xl border border-outline-variant shadow-sm">
<div class="flex justify-between items-center mb-md">
<h4 class="font-title-lg text-title-lg text-on-surface">Top Performing</h4>
<span class="text-xs text-on-surface-variant font-medium">Monthly</span>
</div>
<div class="space-y-md">
<div class="flex items-center gap-md">
<span class="font-headline-md text-headline-md text-outline-variant font-extrabold w-8 text-center">01</span>
<div class="flex-grow">
<p class="font-body-md text-body-md text-on-surface font-semibold">Refer-a-Friend $5 Off</p>
<p class="text-xs text-primary font-medium">+24% revenue lift</p>
</div>
<div class="text-right">
<span class="text-xs font-bold text-on-surface">2.4k</span>
<p class="text-[10px] text-on-surface-variant uppercase">Uses</p>
</div>
</div>
<div class="w-full h-px bg-outline-variant/30"></div>
<div class="flex items-center gap-md">
<span class="font-headline-md text-headline-md text-outline-variant font-extrabold w-8 text-center">02</span>
<div class="flex-grow">
<p class="font-body-md text-body-md text-on-surface font-semibold">Summer Refresher 15%</p>
<p class="text-xs text-primary font-medium">+18% conversion</p>
</div>
<div class="text-right">
<span class="text-xs font-bold text-on-surface">1.8k</span>
<p class="text-[10px] text-on-surface-variant uppercase">Uses</p>
</div>
</div>
<div class="w-full h-px bg-outline-variant/30"></div>
<div class="flex items-center gap-md">
<span class="font-headline-md text-headline-md text-outline-variant font-extrabold w-8 text-center">03</span>
<div class="flex-grow">
<p class="font-body-md text-body-md text-on-surface font-semibold">Late Night Happy Hour</p>
<p class="text-xs text-primary font-medium">+12% traffic boost</p>
</div>
<div class="text-right">
<span class="text-xs font-bold text-on-surface">1.1k</span>
<p class="text-[10px] text-on-surface-variant uppercase">Uses</p>
</div>
</div>
</div>
<button class="w-full mt-lg text-primary font-label-md text-label-md py-sm border border-primary/20 rounded-lg hover:bg-primary/5 transition-colors">
                            View Full Leaderboard
                        </button>
</div>
<!-- System Trạng thái Info -->
<div class="p-lg bg-primary-container/10 rounded-2xl border border-primary-container/20">
<div class="flex gap-sm">
<span class="material-symbols-outlined text-primary-container">info</span>
<div class="space-y-xs">
<p class="text-on-primary-container font-semibold text-sm">Campaign Optimization Tip</p>
<p class="text-xs text-on-surface-variant leading-relaxed">Khuyến mãi with codes containing "PUMPKIN" are seeing 15% higher engagement on weekends. Consider extending the "Seasonal Pumpkin Spice" promo.</p>
</div>
</div>
</div>
</aside>
</div>
</div>
</main>
@endsection

@push('scripts')
<script>

        // Simple interactivity for demonstration
        document.querySelectorAll('button, a').forEach(el => {
            el.addEventListener('mousedown', () => {
                el.classList.add('scale-[0.98]');
            });
            el.addEventListener('mouseup', () => {
                el.classList.remove('scale-[0.98]');
            });
        });

        // Search highlight interaction
        const searchInput = document.querySelector('input');
        searchInput.addEventListener('focus', () => {
            searchInput.parentElement.classList.add('ring-2', 'ring-primary/50');
        });
        searchInput.addEventListener('blur', () => {
            searchInput.parentElement.classList.remove('ring-2', 'ring-primary/50');
        });
    
</script>
@endpush
