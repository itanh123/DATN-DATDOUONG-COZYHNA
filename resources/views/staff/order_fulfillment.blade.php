@extends('layouts.admin')

@section('title', 'Order Fulfillment')

@section('content')
<main class="flex-1 ml-0 md:ml-[280px] h-screen overflow-hidden flex flex-col">
<!-- Header / Today's Stats -->
<header class="h-20 flex items-center justify-between px-xl bg-surface/80 backdrop-blur-md border-b border-outline-variant/30 sticky top-0 z-30">
<div class="flex items-center gap-xl">
<h2 class="font-headline-md text-headline-md text-on-surface">Order Bảng điều khiển</h2>
<div class="hidden lg:flex items-center gap-lg">
<div class="flex flex-col">
<span class="font-label-sm text-label-sm text-on-surface-variant uppercase">Today's Doanh thu</span>
<span class="font-title-lg text-title-lg text-primary">$1,248.50</span>
</div>
<div class="h-8 w-px bg-outline-variant/50"></div>
<div class="flex flex-col">
<span class="font-label-sm text-label-sm text-on-surface-variant uppercase">Avg. Prep Time</span>
<span class="font-title-lg text-title-lg text-secondary">4.2 phút</span>
</div>
<div class="h-8 w-px bg-outline-variant/50"></div>
<div class="flex flex-col">
<span class="font-label-sm text-label-sm text-on-surface-variant uppercase">Efficiency</span>
<span class="font-title-lg text-title-lg text-tertiary">98%</span>
</div>
</div>
</div>
<div class="flex items-center gap-md">
<div class="relative">
<span class="material-symbols-outlined p-base text-on-surface-variant hover:bg-surface-container rounded-full cursor-pointer transition-colors">notifications</span>
<span class="absolute top-1 right-1 w-2 h-2 bg-error rounded-full"></span>
</div>
<div class="flex items-center gap-sm bg-surface-container-low px-sm py-xs rounded-full border border-outline-variant/30">
<div class="w-8 h-8 rounded-full bg-primary-fixed-dim flex items-center justify-center text-on-primary-fixed font-bold">JD</div>
<span class="font-label-md text-label-md hidden sm:block">Jane Doe</span>
</div>
</div>
</header>
<!-- Kanban Board Container -->
<section class="flex-1 p-md md:p-xl overflow-hidden flex flex-col">
<!-- Filters & Tabs (Mobile View) -->
<div class="md:hidden flex overflow-x-auto gap-sm pb-md no-scrollbar">
<button class="bg-primary text-on-primary px-lg py-xs rounded-full whitespace-nowrap font-label-md">Incoming (8)</button>
<button class="bg-surface-container-high text-on-surface-variant px-lg py-xs rounded-full whitespace-nowrap font-label-md">Preparing (4)</button>
<button class="bg-surface-container-high text-on-surface-variant px-lg py-xs rounded-full whitespace-nowrap font-label-md">Ready (12)</button>
</div>
<!-- Kanban Columns -->
<div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-lg h-full overflow-hidden">
<!-- Column: Incoming -->
<div class="flex flex-col h-full bg-surface-container-low/50 rounded-2xl border border-outline-variant/20">
<div class="p-md border-b border-outline-variant/30 flex justify-between items-center">
<div class="flex items-center gap-sm">
<span class="material-symbols-outlined text-primary">inbox</span>
<h3 class="font-title-lg text-title-lg">Incoming</h3>
</div>
<span class="bg-primary-container text-on-primary-container text-label-sm font-bold px-sm py-0.5 rounded-full">8 Đơn hàng</span>
</div>
<div class="flex-1 overflow-y-auto p-md space-y-md custom-scrollbar">
<!-- Order Card -->
<div class="order-card-transition cursor-pointer bg-white dark:bg-surface p-md rounded-xl border border-outline-variant/30 shadow-sm hover:shadow-md" onclick="openOrderDetails('ORD-7241')">
<div class="flex justify-between items-start mb-sm">
<span class="font-bold text-primary">#7241</span>
<span class="text-label-sm text-on-surface-variant">2m ago</span>
</div>
<h4 class="font-title-lg text-title-lg mb-xs">Iced Vanilla Oat Latte</h4>
<p class="text-label-md text-on-surface-variant mb-md">+ Extra Shot, No Ice</p>
<div class="flex justify-between items-center">
<span class="bg-tertiary-container/20 text-tertiary font-label-sm px-sm py-0.5 rounded-full uppercase">Standard</span>
<button class="bg-primary hover:bg-surface-tint text-on-primary text-label-md font-bold px-lg py-xs rounded-lg transition-colors" onclick="event.stopPropagation(); moveToPreparing('7241')">Start Prep</button>
</div>
</div>
<div class="order-card-transition cursor-pointer bg-white dark:bg-surface p-md rounded-xl border border-outline-variant/30 shadow-sm hover:shadow-md border-l-4 border-l-error" onclick="openOrderDetails('ORD-7242')">
<div class="flex justify-between items-start mb-sm">
<span class="font-bold text-error">#7242</span>
<span class="text-label-sm text-on-surface-variant">5m ago</span>
</div>
<h4 class="font-title-lg text-title-lg mb-xs">2x Caramel Macchiato</h4>
<p class="text-label-md text-on-surface-variant mb-md">Delivery Order #402</p>
<div class="flex justify-between items-center">
<span class="bg-error-container text-on-error-container font-label-sm px-sm py-0.5 rounded-full uppercase">Priority</span>
<button class="bg-primary hover:bg-surface-tint text-on-primary text-label-md font-bold px-lg py-xs rounded-lg transition-colors" onclick="event.stopPropagation(); moveToPreparing('7242')">Start Prep</button>
</div>
</div>
<div class="order-card-transition cursor-pointer bg-white dark:bg-surface p-md rounded-xl border border-outline-variant/30 shadow-sm hover:shadow-md" onclick="openOrderDetails('ORD-7243')">
<div class="flex justify-between items-start mb-sm">
<span class="font-bold text-primary">#7243</span>
<span class="text-label-sm text-on-surface-variant">8m ago</span>
</div>
<h4 class="font-title-lg text-title-lg mb-xs">Matcha Green Tea</h4>
<p class="text-label-md text-on-surface-variant mb-md">Dine-in Table 4</p>
<div class="flex justify-between items-center">
<span class="bg-tertiary-container/20 text-tertiary font-label-sm px-sm py-0.5 rounded-full uppercase">Standard</span>
<button class="bg-primary hover:bg-surface-tint text-on-primary text-label-md font-bold px-lg py-xs rounded-lg transition-colors" onclick="event.stopPropagation(); moveToPreparing('7243')">Start Prep</button>
</div>
</div>
</div>
</div>
<!-- Column: Preparing -->
<div class="flex flex-col h-full bg-surface-container-low/50 rounded-2xl border border-outline-variant/20">
<div class="p-md border-b border-outline-variant/30 flex justify-between items-center">
<div class="flex items-center gap-sm">
<span class="material-symbols-outlined text-secondary">local_cafe</span>
<h3 class="font-title-lg text-title-lg">Preparing</h3>
</div>
<span class="bg-secondary-container text-on-secondary-container text-label-sm font-bold px-sm py-0.5 rounded-full">4 Hoạt động</span>
</div>
<div class="flex-1 overflow-y-auto p-md space-y-md custom-scrollbar">
<!-- Preparing Card -->
<div class="order-card-transition cursor-pointer bg-white dark:bg-surface p-md rounded-xl border border-outline-variant/30 shadow-sm hover:shadow-md relative overflow-hidden" onclick="openOrderDetails('ORD-7238')">
<div class="absolute top-0 left-0 h-1 bg-secondary w-2/3"></div>
<div class="flex justify-between items-start mb-sm">
<span class="font-bold text-secondary">#7238</span>
<span class="flex items-center gap-xs text-secondary font-bold text-label-sm">
<span class="material-symbols-outlined text-sm animate-spin" style="font-size: 14px;">autorenew</span>
                                    Preparing...
                                </span>
</div>
<h4 class="font-title-lg text-title-lg mb-xs">Hot Cappuccino</h4>
<p class="text-label-md text-on-surface-variant mb-md">Staff: Marco P.</p>
<div class="flex justify-between items-center">
<span class="text-label-sm text-secondary font-bold">ETA: 1m</span>
<button class="bg-secondary hover:bg-on-secondary-fixed-variant text-on-secondary text-label-md font-bold px-lg py-xs rounded-lg transition-colors" onclick="event.stopPropagation(); markAsReady('7238')">Mark Ready</button>
</div>
</div>
<div class="order-card-transition cursor-pointer bg-white dark:bg-surface p-md rounded-xl border border-outline-variant/30 shadow-sm hover:shadow-md relative overflow-hidden" onclick="openOrderDetails('ORD-7239')">
<div class="absolute top-0 left-0 h-1 bg-secondary w-1/4"></div>
<div class="flex justify-between items-start mb-sm">
<span class="font-bold text-secondary">#7239</span>
<span class="flex items-center gap-xs text-secondary font-bold text-label-sm">
<span class="material-symbols-outlined text-sm animate-spin" style="font-size: 14px;">autorenew</span>
                                    Preparing...
                                </span>
</div>
<h4 class="font-title-lg text-title-lg mb-xs">Flat White + Avocado Toast</h4>
<p class="text-label-md text-on-surface-variant mb-md">Staff: Jane D.</p>
<div class="flex justify-between items-center">
<span class="text-label-sm text-secondary font-bold">ETA: 3m</span>
<button class="bg-secondary hover:bg-on-secondary-fixed-variant text-on-secondary text-label-md font-bold px-lg py-xs rounded-lg transition-colors" onclick="event.stopPropagation(); markAsReady('7239')">Mark Ready</button>
</div>
</div>
</div>
</div>
<!-- Column: Ready -->
<div class="flex flex-col h-full bg-surface-container-low/50 rounded-2xl border border-outline-variant/20">
<div class="p-md border-b border-outline-variant/30 flex justify-between items-center">
<div class="flex items-center gap-sm">
<span class="material-symbols-outlined text-tertiary">check_circle</span>
<h3 class="font-title-lg text-title-lg">Ready to Serve</h3>
</div>
<span class="bg-tertiary-container text-on-tertiary-container text-label-sm font-bold px-sm py-0.5 rounded-full">12 Ready</span>
</div>
<div class="flex-1 overflow-y-auto p-md space-y-md custom-scrollbar opacity-80 hover:opacity-100 transition-opacity">
<!-- Ready Card -->
<div class="order-card-transition cursor-pointer bg-tertiary-container/10 p-md rounded-xl border border-tertiary/20 shadow-sm hover:shadow-md" onclick="openOrderDetails('ORD-7235')">
<div class="flex justify-between items-start mb-sm">
<span class="font-bold text-tertiary">#7235</span>
<span class="flex items-center gap-xs text-tertiary font-bold text-label-sm">
<span class="material-symbols-outlined" style="font-size: 16px;">notifications_active</span>
                                    Call Made
                                </span>
</div>
<h4 class="font-title-lg text-title-lg mb-xs">Signature Mocha</h4>
<p class="text-label-md text-on-surface-variant mb-md">Pickup: Alex R.</p>
<div class="flex justify-between items-center">
<span class="text-label-sm text-on-surface-variant">Ready for 4m</span>
<button class="bg-tertiary hover:bg-on-tertiary-fixed-variant text-on-tertiary text-label-md font-bold px-lg py-xs rounded-lg transition-colors" onclick="event.stopPropagation(); completeOrder('7235')">Complete</button>
</div>
</div>
<div class="order-card-transition cursor-pointer bg-tertiary-container/10 p-md rounded-xl border border-tertiary/20 shadow-sm hover:shadow-md" onclick="openOrderDetails('ORD-7236')">
<div class="flex justify-between items-start mb-sm">
<span class="font-bold text-tertiary">#7236</span>
<span class="text-label-sm text-on-surface-variant">Ready for 1m</span>
</div>
<h4 class="font-title-lg text-title-lg mb-xs">Cold Brew + Bagel</h4>
<p class="text-label-md text-on-surface-variant mb-md">Pickup: Chris M.</p>
<div class="flex justify-between items-center">
<button class="flex items-center gap-xs text-primary font-bold text-label-md px-md py-xs rounded-lg hover:bg-primary/5">
<span class="material-symbols-outlined" style="font-size: 18px;">volume_up</span>
                                    Recall
                                </button>
<button class="bg-tertiary hover:bg-on-tertiary-fixed-variant text-on-tertiary text-label-md font-bold px-lg py-xs rounded-lg transition-colors" onclick="event.stopPropagation(); completeOrder('7236')">Complete</button>
</div>
</div>
</div>
</div>
</div>
</section>
</main>
@endsection

@push('scripts')
<script>

        const slideOver = document.getElementById('order-slide-over');
        const slideOverContent = document.getElementById('slide-over-content');
        const slideOverBackdrop = document.getElementById('slide-over-backdrop');
        const orderIdDisplay = document.getElementById('detail-order-id');
        const primaryAction = document.getElementById('action-primary');

        function openOrderDetails(orderId) {
            orderIdDisplay.innerText = `Order #${orderId.replace('ORD-', '')}`;
            slideOver.classList.remove('invisible');
            slideOver.classList.add('visible');
            slideOverBackdrop.classList.add('opacity-100');
            slideOverContent.classList.remove('translate-x-full');
            slideOverContent.classList.add('translate-x-0');
        }

        function closeOrderDetails() {
            slideOverBackdrop.classList.remove('opacity-100');
            slideOverContent.classList.add('translate-x-full');
            slideOverContent.classList.remove('translate-x-0');
            setTimeout(() => {
                slideOver.classList.add('invisible');
                slideOver.classList.remove('visible');
            }, 300);
        }

        slideOverBackdrop.addEventListener('click', closeOrderDetails);

        function moveToPreparing(id) {
            console.log(`Order ${id} is now being prepared.`);
            // In a real app, this would trigger a state change and re-render
            alert(`Starting preparation for Order #${id}`);
        }

        function markAsReady(id) {
            console.log(`Order ${id} is ready for pickup.`);
            alert(`Order #${id} marked as Ready! Notification sent to customer.`);
        }

        function completeOrder(id) {
            console.log(`Order ${id} completed.`);
            alert(`Order #${id} has been served and closed.`);
        }

        function completeOrder(id) {
            console.log(`Order ${id} completed.`);
            alert(`Order #${id} has been served and closed.`);
        }
    
</script>
@endpush
