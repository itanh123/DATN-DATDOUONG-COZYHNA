@extends('layouts.admin')

@section('title', 'Kho hàng')

@section('content')
<main class="ml-[280px] pt-16 min-h-screen p-gutter bg-background">
<div class="max-w-[1400px] mx-auto space-y-gutter">
<!-- Quick Thao tác & Alerts Giâytion -->
<div class="flex flex-col lg:flex-row gap-gutter">
<!-- Low Stock Alert Banner -->
<div class="flex-1 bg-tertiary-container/10 border border-tertiary/20 rounded-xl p-lg flex items-center justify-between">
<div class="flex items-center gap-md">
<div class="w-12 h-12 rounded-full bg-tertiary text-on-tertiary flex items-center justify-center">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">warning</span>
</div>
<div>
<h4 class="font-title-lg text-tertiary">Kho hàng Alert: Critical Low Stock</h4>
<p class="font-body-md text-on-surface-variant">8 items are below their safety threshold and require immediate restocking.</p>
</div>
</div>
<button class="px-xl py-md bg-tertiary text-on-tertiary rounded-xl font-label-md hover:brightness-110 transition-all">Review Items</button>
</div>
<!-- Action Buttons -->
<div class="flex gap-sm h-fit self-end">
<button class="flex items-center gap-xs px-lg py-md border border-outline rounded-xl font-label-md text-on-surface-variant hover:bg-surface-container-low transition-all">
<span class="material-symbols-outlined">file_download</span>
                        Export CSV
                    </button>
<button class="flex items-center gap-xs px-lg py-md bg-primary text-on-primary rounded-xl font-label-md hover:shadow-lg transition-all">
<span class="material-symbols-outlined">add_box</span>
                        Thêm Mới Item
                    </button>
</div>
</div>
<!-- Kho hàng Tổng quan Cards (Bento Style) -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-gutter">
<div class="bg-surface-container-lowest p-lg rounded-xl border border-outline-variant shadow-sm hover:shadow-md transition-shadow">
<div class="flex items-center justify-between mb-sm">
<span class="font-label-sm text-on-surface-variant uppercase tracking-wider">Tổng cộng SKUs</span>
<span class="material-symbols-outlined text-primary">inventory</span>
</div>
<div class="font-headline-lg text-headline-lg text-on-surface">1,284</div>
<div class="flex items-center gap-xs mt-xs text-secondary font-label-md">
<span class="material-symbols-outlined text-[16px]">trending_up</span>
<span>+12 new this month</span>
</div>
</div>
<div class="bg-surface-container-lowest p-lg rounded-xl border border-outline-variant shadow-sm hover:shadow-md transition-shadow">
<div class="flex items-center justify-between mb-sm">
<span class="font-label-sm text-on-surface-variant uppercase tracking-wider">Low Stock Items</span>
<span class="material-symbols-outlined text-tertiary">error_outline</span>
</div>
<div class="font-headline-lg text-headline-lg text-tertiary">08</div>
<div class="mt-xs">
<span class="px-2 py-0.5 bg-tertiary-container/20 text-tertiary rounded-full font-label-sm">Needs Ordering</span>
</div>
</div>
<div class="bg-surface-container-lowest p-lg rounded-xl border border-outline-variant shadow-sm hover:shadow-md transition-shadow">
<div class="flex items-center justify-between mb-sm">
<span class="font-label-sm text-on-surface-variant uppercase tracking-wider">Out of Stock</span>
<span class="material-symbols-outlined text-error">cancel</span>
</div>
<div class="font-headline-lg text-headline-lg text-error">03</div>
<div class="mt-xs">
<span class="px-2 py-0.5 bg-error-container/20 text-error rounded-full font-label-sm">High Priority</span>
</div>
</div>
<div class="bg-surface-container-lowest p-lg rounded-xl border border-outline-variant shadow-sm hover:shadow-md transition-shadow">
<div class="flex items-center justify-between mb-sm">
<span class="font-label-sm text-on-surface-variant uppercase tracking-wider">Stock Value</span>
<span class="material-symbols-outlined text-secondary">payments</span>
</div>
<div class="font-headline-lg text-headline-lg text-on-surface">$24,850</div>
<div class="flex items-center gap-xs mt-xs text-on-surface-variant font-label-md">
<span>Current inventory valuation</span>
</div>
</div>
</div>
<!-- Main Content Area: Table & Suppliers -->
<div class="flex flex-col xl:flex-row gap-gutter">
<!-- Stock Levels Table -->
<div class="flex-[2] bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm">
<div class="p-lg border-b border-outline-variant flex justify-between items-center bg-surface-bright">
<h3 class="font-title-lg text-on-surface">Detailed Stock Levels</h3>
<div class="flex gap-sm">
<select class="text-body-md border-outline-variant rounded-lg bg-surface focus:ring-primary border-none">
<option>Filter by Category</option>
<option>Coffee</option>
<option>Dairy</option>
<option>Sweeteners</option>
</select>
</div>
</div>
<div class="overflow-x-auto">
<table class="w-full text-left">
<thead class="bg-surface-container-low border-b border-outline-variant">
<tr>
<th class="p-lg font-label-sm text-on-surface-variant uppercase">Item Name</th>
<th class="p-lg font-label-sm text-on-surface-variant uppercase">Category</th>
<th class="p-lg font-label-sm text-on-surface-variant uppercase">SKU</th>
<th class="p-lg font-label-sm text-on-surface-variant uppercase">Stock</th>
<th class="p-lg font-label-sm text-on-surface-variant uppercase">Trạng thái</th>
<th class="p-lg font-label-sm text-on-surface-variant uppercase">Restocked</th>
<th class="p-lg"></th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant/30">
<tr class="hover:bg-surface-container-low/50 transition-colors">
<td class="p-lg">
<div class="flex items-center gap-md">
<div class="w-12 h-12 rounded-lg bg-cover bg-center border border-outline-variant" data-alt="A top-down macro shot of premium, dark-roasted Arabica coffee beans in a rustic ceramic bowl. The lighting is soft and natural, emphasizing the oily texture and deep brown color of the beans. The background is a clean, minimalist wooden surface, evoking a high-end cafe aesthetic consistent with the Organic Vitality brand." style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBxkSY8DckcVl9R2_n5JeXvGHWQaoPpf-KppTlzWNzyzYimteAHLCRbmpoImE6vpPhdePUOC4S8jk1q2foCOGRtuqttE1xP_8pX0PoFC5YRDquUe31wLfO05Y9HQ1OQ_1ebPq7veHn3NgCcZPkJZvmPx6LA8tV3MqD6cc4Mtu_XK3h5dbnd-K-vln4jUL9RCs1GDvScy7-lxh4nAVrgSy5UGcSIdxnpCzNd4FuPasN7O-_RjgxB0SosJu7RMYda6Mrfk0_8abw9')"></div>
<div>
<div class="font-title-lg text-body-lg">Ethiopian Arabica</div>
<div class="font-label-sm text-outline">Dark Roast (Whole)</div>
</div>
</div>
</td>
<td class="p-lg"><span class="font-body-md">Coffee</span></td>
<td class="p-lg"><span class="font-body-md text-outline">COF-ARB-001</span></td>
<td class="p-lg">
<div class="font-title-lg">45</div>
<div class="font-label-sm text-outline">kg</div>
</td>
<td class="p-lg">
<span class="px-sm py-1 bg-secondary-container/20 text-secondary rounded-full font-label-sm border border-secondary/10">In Stock</span>
</td>
<td class="p-lg"><span class="font-body-md">Oct 24, 2023</span></td>
<td class="p-lg">
<button class="p-2 hover:bg-surface-container-high rounded-full"><span class="material-symbols-outlined">more_vert</span></button>
</td>
</tr>
<tr class="hover:bg-surface-container-low/50 transition-colors">
<td class="p-lg">
<div class="flex items-center gap-md">
<div class="w-12 h-12 rounded-lg bg-cover bg-center border border-outline-variant" data-alt="A minimalist photograph of a high-quality organic oat milk carton placed on a clean white countertop next to a small glass of milk. The branding on the carton is simple and modern, reflecting the cafe's premium quality standards. The scene is bright and airy with soft shadows and a professional, fresh atmosphere." style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDggaMDD_1GWwnbg-ZtOv3y_QAOBFDfRJPwUNvxUEtCnTXj4dfCT-fTiJ-WuZW2bmUQXzTEjvSzS_bufNqfGhEabHG8EZx02jBorfh9chPM1Uivwbu-8W_-9Um6c5KQV3ivlwgMw0b5UNJENv1jv02CNmKRZEZzo-SMwgATq9F0CUcE7Rv5kVwDnQ0aGqE6qPNvFQiRKI8SP9xG3mcNmFZ5hZwmkVwft72VzLMdGgZhds0i-o2oSfjQRSCVX1qJGlEsOgKwjYaK')"></div>
<div>
<div class="font-title-lg text-body-lg">Organic Oat Milk</div>
<div class="font-label-sm text-outline">Barista Sửaion</div>
</div>
</div>
</td>
<td class="p-lg"><span class="font-body-md">Dairy</span></td>
<td class="p-lg"><span class="font-body-md text-outline">DAI-OAT-042</span></td>
<td class="p-lg">
<div class="font-title-lg text-tertiary">12</div>
<div class="font-label-sm text-outline">Liters</div>
</td>
<td class="p-lg">
<span class="px-sm py-1 bg-tertiary-container/20 text-tertiary rounded-full font-label-sm border border-tertiary/10">Low Stock</span>
</td>
<td class="p-lg"><span class="font-body-md">Nov 02, 2023</span></td>
<td class="p-lg">
<button class="p-2 hover:bg-surface-container-high rounded-full"><span class="material-symbols-outlined">more_vert</span></button>
</td>
</tr>
<tr class="hover:bg-surface-container-low/50 transition-colors">
<td class="p-lg">
<div class="flex items-center gap-md">
<div class="w-12 h-12 rounded-lg bg-cover bg-center border border-outline-variant" data-alt="A macro shot of artisan vanilla bean pods on a white marble surface, showing the rich, dark texture of the pods. Next to them is a small glass vial of clear agave syrup. The lighting is bright and clean, emphasizing purity and high-quality ingredients for a premium cafe beverage brand." style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuD5VQux3JhQm9Ui1CDhRIo9m14tFv-2HJYHiT9IYgq4wH8G4B4KBo6BnTRbIfEExq14L85rZ0MjKcoZz-3Pa_TZ0-FEzdmJmr6dAlnVXgNsN-vG-WuF3burpjwWbmZTWFRBTGXwBkkrVuWN3MDkrqUr2HUEAs3gA6PDZOTjv-5SQWvsxy_81mljGbdHuq6I8oTPVZs7hyvpPNCKX3QQR7w2N7vlFMOEru4tgj8GFBNKGXbHhUPxZLG5owDax61p1Pp-53Cenj-d')"></div>
<div>
<div class="font-title-lg text-body-lg">Agave Syrup</div>
<div class="font-label-sm text-outline">Organic Raw</div>
</div>
</div>
</td>
<td class="p-lg"><span class="font-body-md">Sweeteners</span></td>
<td class="p-lg"><span class="font-body-md text-outline">SWT-AGV-009</span></td>
<td class="p-lg">
<div class="font-title-lg text-error">0</div>
<div class="font-label-sm text-outline">Bags</div>
</td>
<td class="p-lg">
<span class="px-sm py-1 bg-error-container/20 text-error rounded-full font-label-sm border border-error/10">Out of Stock</span>
</td>
<td class="p-lg"><span class="font-body-md">Oct 15, 2023</span></td>
<td class="p-lg">
<button class="p-2 hover:bg-surface-container-high rounded-full"><span class="material-symbols-outlined">more_vert</span></button>
</td>
</tr>
<tr class="hover:bg-surface-container-low/50 transition-colors">
<td class="p-lg">
<div class="flex items-center gap-md">
<div class="w-12 h-12 rounded-lg bg-cover bg-center border border-outline-variant" data-alt="A stack of minimalist, eco-friendly compostable coffee cups and lids. The cups are a soft earthy tan color with a subtle matte finish. They are arranged neatly against a bright, clean-room background with soft directional light that highlights their eco-conscious design and premium construction quality." style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBSU8N-jtlBWBjD9b6UX7FXkfLKunbpvpdlY1FRC75AG5zPFP9AjnXU4VaBmGFsJN0jQ5-fgQYzS-g2QA_v18-GhJ4ilaX6iKFTz4sG67APuWrUsvqAKHszqfTxOKeDoAfjoGbI1UNIuaZb6C-tkkPiTxQx4jswJ_llo4M6eEzAErS55fldd1TvwI_dPNqHbp2xq-fDiWzYikKZq3sTydceTL4AmV134Mb1lyC0xqYVmvn4lu1yBGwgK_aiOGlYrdnj5Mq1G3lx')"></div>
<div>
<div class="font-title-lg text-body-lg">Compostable Cups 12oz</div>
<div class="font-label-sm text-outline">Eco-Series</div>
</div>
</div>
</td>
<td class="p-lg"><span class="font-body-md">Packaging</span></td>
<td class="p-lg"><span class="font-body-md text-outline">PKG-CUP-112</span></td>
<td class="p-lg">
<div class="font-title-lg">450</div>
<div class="font-label-sm text-outline">Units</div>
</td>
<td class="p-lg">
<span class="px-sm py-1 bg-secondary-container/20 text-secondary rounded-full font-label-sm border border-secondary/10">In Stock</span>
</td>
<td class="p-lg"><span class="font-body-md">Nov 10, 2023</span></td>
<td class="p-lg">
<button class="p-2 hover:bg-surface-container-high rounded-full"><span class="material-symbols-outlined">more_vert</span></button>
</td>
</tr>
</tbody>
</table>
</div>
<div class="p-lg border-t border-outline-variant flex items-center justify-between">
<span class="font-body-md text-on-surface-variant">Showing 1-10 of 1,284 items</span>
<div class="flex gap-xs">
<button class="p-2 rounded-lg border border-outline-variant hover:bg-surface-container-low transition-colors"><span class="material-symbols-outlined">chevron_left</span></button>
<button class="p-2 rounded-lg border border-outline-variant hover:bg-surface-container-low transition-colors"><span class="material-symbols-outlined">chevron_right</span></button>
</div>
</div>
</div>
<!-- Supplier Management Giâytion -->
<div class="flex-1 space-y-gutter">
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-lg shadow-sm">
<div class="flex justify-between items-center mb-lg">
<h3 class="font-title-lg text-on-surface">Hoạt động Suppliers</h3>
<button class="text-primary font-label-md hover:underline">Manage All</button>
</div>
<div class="space-y-md">
<div class="p-md rounded-xl border border-outline-variant hover:border-primary/30 transition-all group cursor-pointer">
<div class="flex justify-between items-start mb-xs">
<span class="font-body-lg font-semibold text-on-surface group-hover:text-primary transition-colors">BeanBound Roasters</span>
<span class="px-2 py-0.5 bg-secondary-container/30 text-secondary-fixed-dim text-[10px] font-bold uppercase rounded">Lead: 3 Days</span>
</div>
<p class="text-body-md text-on-surface-variant mb-md">Specialty Arabica &amp; Robusta</p>
<div class="flex items-center gap-xs text-outline text-label-md">
<span class="material-symbols-outlined text-[14px]">call</span>
<span>+1 (555) 234-8890</span>
</div>
</div>
<div class="p-md rounded-xl border border-outline-variant hover:border-primary/30 transition-all group cursor-pointer">
<div class="flex justify-between items-start mb-xs">
<span class="font-body-lg font-semibold text-on-surface group-hover:text-primary transition-colors">GreenDairy Distribution</span>
<span class="px-2 py-0.5 bg-tertiary-container/30 text-tertiary text-[10px] font-bold uppercase rounded">Lead: 1 Day</span>
</div>
<p class="text-body-md text-on-surface-variant mb-md">Organic Milk &amp; Oat Alternatives</p>
<div class="flex items-center gap-xs text-outline text-label-md">
<span class="material-symbols-outlined text-[14px]">mail</span>
<span>orders@greendairy.co</span>
</div>
</div>
<div class="p-md rounded-xl border border-outline-variant hover:border-primary/30 transition-all group cursor-pointer">
<div class="flex justify-between items-start mb-xs">
<span class="font-body-lg font-semibold text-on-surface group-hover:text-primary transition-colors">EcoPack Solutions</span>
<span class="px-2 py-0.5 bg-secondary-container/30 text-secondary-fixed-dim text-[10px] font-bold uppercase rounded">Lead: 5 Days</span>
</div>
<p class="text-body-md text-on-surface-variant mb-md">Compostable Cups &amp; Packaging</p>
<div class="flex items-center gap-xs text-outline text-label-md">
<span class="material-symbols-outlined text-[14px]">call</span>
<span>+1 (555) 902-1143</span>
</div>
</div>
</div>
</div>
<!-- Generate Purchase Order CTA -->
<div class="relative bg-primary overflow-hidden rounded-xl p-lg text-on-primary">
<div class="absolute -right-8 -bottom-8 opacity-20 transform rotate-12">
<span class="material-symbols-outlined text-[120px]">shopping_cart_checkout</span>
</div>
<div class="relative z-10">
<h4 class="font-headline-md mb-xs">Re-Stock Needed?</h4>
<p class="font-body-md mb-lg opacity-90">Auto-generate purchase orders based on your current low-stock alerts.</p>
<button class="w-full py-md bg-white text-primary rounded-xl font-title-lg hover:bg-surface-container-lowest transition-all shadow-lg active:scale-[0.98]">
                                Generate Smart PO
                            </button>
</div>
</div>
</div>
</div>
</div>
</main>
@endsection

@push('scripts')
<script>

        document.querySelectorAll('tr').forEach(row => {
            row.addEventListener('click', () => {
                // Subtle feedback on selection
                console.log('Row clicked');
            });
        });

        // Search bar focus effect
        const searchInput = document.querySelector('input[type="text"]');
        searchInput.addEventListener('focus', () => {
            searchInput.parentElement.classList.add('ring-2', 'ring-primary/20');
        });
        searchInput.addEventListener('blur', () => {
            searchInput.parentElement.classList.remove('ring-2', 'ring-primary/20');
        });
    
</script>
@endpush
