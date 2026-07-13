@extends('layouts.admin')

@section('title', 'Delivery Portal')

@section('content')
<main class="md:ml-[280px] min-h-screen pb-2xl md:pb-lg">
<!-- Header / Stats Bar -->
<header class="sticky top-0 z-30 bg-surface/80 backdrop-blur-md px-lg py-md border-b border-outline-variant/30 flex justify-between items-center">
<div>
<h2 class="font-headline-md text-headline-md text-on-surface">Delivery Board</h2>
<p class="font-body-md text-body-md text-on-surface-variant">Manage your active routes and earnings</p>
</div>
<div class="hidden lg:flex items-center gap-lg">
<div class="text-right">
<p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Today's Doanh thu</p>
<p class="font-headline-md text-headline-md text-primary">$124.50</p>
</div>
<div class="h-10 w-[1px] bg-outline-variant/30"></div>
<div class="text-right">
<p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Deliveries</p>
<p class="font-headline-md text-headline-md text-secondary">12</p>
</div>
<div class="flex items-center justify-center h-12 w-12 rounded-full bg-surface-container-highest">
<span class="material-symbols-outlined text-primary">account_circle</span>
</div>
</div>
</header>
<div class="max-w-container-max mx-auto p-md md:p-lg space-y-lg">
<!-- Quick Stats Cards (Bento style) -->
<section class="grid grid-cols-1 md:grid-cols-4 gap-md">
<div class="md:col-span-1 glass-card p-md rounded-xl flex items-center gap-md">
<div class="h-12 w-12 rounded-lg bg-primary/10 flex items-center justify-center">
<span class="material-symbols-outlined text-primary">speed</span>
</div>
<div>
<p class="font-label-md text-label-md text-on-surface-variant">Rating</p>
<p class="font-title-lg text-title-lg">4.92</p>
</div>
</div>
<div class="md:col-span-1 glass-card p-md rounded-xl flex items-center gap-md">
<div class="h-12 w-12 rounded-lg bg-secondary/10 flex items-center justify-center">
<span class="material-symbols-outlined text-secondary">trending_up</span>
</div>
<div>
<p class="font-label-md text-label-md text-on-surface-variant">Acceptance</p>
<p class="font-title-lg text-title-lg">98%</p>
</div>
</div>
<div class="md:col-span-2 glass-card p-md rounded-xl bg-gradient-to-br from-primary-container to-secondary-container/20">
<div class="flex justify-between items-start">
<div>
<p class="font-label-md text-label-md text-on-primary-container">Weekly Target</p>
<p class="font-headline-md text-headline-md text-on-primary-container">$482.00 / $600.00</p>
</div>
<span class="material-symbols-outlined text-on-primary-container">stars</span>
</div>
<div class="mt-md w-full bg-white/30 h-2 rounded-full overflow-hidden">
<div class="bg-primary h-full w-[80%] rounded-full"></div>
</div>
</div>
</section>
<!-- Main Tab Interface -->
<div class="flex flex-col gap-lg">
<div class="flex items-center gap-md border-b border-outline-variant/30">
<button class="px-lg py-md font-label-md text-label-md transition-all border-b-2 border-primary text-primary font-bold" id="tab-available" onclick="switchTab('available')">
                        Available (4)
                    </button>
<button class="px-lg py-md font-label-md text-label-md text-on-surface-variant hover:text-primary transition-all" id="tab-active" onclick="switchTab('active')">
                        Hoạt động (1)
                    </button>
<button class="px-lg py-md font-label-md text-label-md text-on-surface-variant hover:text-primary transition-all" id="tab-history" onclick="switchTab('history')">
                        History
                    </button>
</div>
<!-- Tab Content: Available -->
<div class="grid grid-cols-1 xl:grid-cols-2 gap-lg animate-in fade-in duration-500" id="content-available">
<!-- Available Order Card 1 -->
<div class="glass-card rounded-xl p-md flex flex-col md:flex-row gap-md hover:shadow-lg transition-all group">
<div class="w-full md:w-32 h-32 rounded-lg overflow-hidden shrink-0">
<img class="w-full h-full object-cover" data-alt="A professional studio photo of a fresh organic green juice in a glass bottle with kale and cucumber in the background, bright morning lighting, premium health brand aesthetic." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCE6_okJ6toD2ezQ0m0KTVDUYHhZG2ZUjKLZOBpf0FjEgqtwthidpd9ATDjtKubfu561PvSMKJ238MeQuAP8j0JI9WGb-R1FalVmX_4oVm9EncfA4IrPp0mdZEG1qAXMnGnTliy114RR5kf0aHxME9pgLiP-AOZcrssvRlOwxiGPCUusK-ubeVnzV3aBWe96cgTrsJS2nAX1mtzEj3iBXdFUNodsxawCV-fMxHcxW7s5e3DySjHOJdiwK82RtzTAT5hR57TrIqX"/>
</div>
<div class="flex-1 flex flex-col justify-between">
<div>
<div class="flex justify-between items-start">
<h3 class="font-title-lg text-title-lg text-on-surface">CozyHNA - Fresh Juices</h3>
<span class="bg-secondary-container text-on-secondary-container px-xs py-[2px] rounded-full font-label-sm text-label-sm">$12.50</span>
</div>
<p class="font-body-md text-body-md text-on-surface-variant flex items-center gap-xs mt-xs">
<span class="material-symbols-outlined text-[18px]">location_on</span> 2.4 miles away • Downtown Hub
                                </p>
</div>
<div class="mt-md flex items-center justify-between">
<span class="font-label-md text-label-md text-primary">Pickup in 5 mins</span>
<button class="bg-primary text-on-primary px-lg py-sm rounded-xl font-label-md text-label-md active:scale-95 transition-transform">Accept Order</button>
</div>
</div>
</div>
<!-- Available Order Card 2 -->
<div class="glass-card rounded-xl p-md flex flex-col md:flex-row gap-md hover:shadow-lg transition-all">
<div class="w-full md:w-32 h-32 rounded-lg overflow-hidden shrink-0">
<img class="w-full h-full object-cover" data-alt="Close-up of a premium latte in a white ceramic cup with intricate latte art, soft warm lighting, minimalist cafe table setting with wooden textures." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCgJ9DlNxKB79uMPzjw_jflQZou8Ny3Ay1lg_mSi8nrmcnVAGrYwZSUAgiDvoEWTjM-wJR5xEwpSsoUjUBmRzKL_ENFSrgK9wGCEz5mzNKF_EthF8ooG5IoZyTpQtDAUzyJbVYPi1x310OFdP_nDkRGYsoVy-w74Hysx-yYw9-7D-P4Gg3u02PZGdz5E1pYIN3cHtjC0eAGfWk5ZvKoEECRYcK6BVzbEi2yMfrXd7oPLzYPYqGpTCVlupiWW7fa8EjMyYFkulnE"/>
</div>
<div class="flex-1 flex flex-col justify-between">
<div>
<div class="flex justify-between items-start">
<h3 class="font-title-lg text-title-lg text-on-surface">Artisan Coffee Co.</h3>
<span class="bg-secondary-container text-on-secondary-container px-xs py-[2px] rounded-full font-label-sm text-label-sm">$8.75</span>
</div>
<p class="font-body-md text-body-md text-on-surface-variant flex items-center gap-xs mt-xs">
<span class="material-symbols-outlined text-[18px]">location_on</span> 1.8 miles away • North Plaza
                                </p>
</div>
<div class="mt-md flex items-center justify-between">
<span class="font-label-md text-label-md text-primary">Pickup in 12 mins</span>
<button class="bg-primary text-on-primary px-lg py-sm rounded-xl font-label-md text-label-md active:scale-95 transition-transform">Accept Order</button>
</div>
</div>
</div>
</div>
<!-- Tab Content: Hoạt động (Hidden by default) -->
<div class="hidden grid grid-cols-1 lg:grid-cols-3 gap-lg animate-in fade-in duration-500" id="content-active">
<div class="lg:col-span-2 space-y-lg">
<div class="relative w-full aspect-video md:aspect-[21/9] rounded-2xl overflow-hidden shadow-sm border border-outline-variant/30">
<!-- Map View Placeholder -->
<div class="w-full h-full bg-surface-container-high relative">
<div class="absolute inset-0 grayscale opacity-40" data-alt="A clean, minimalist vector map illustration of a city grid with organic green park areas and a subtle navigation route line highlighted in emerald green." data-location="Seattle" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBizh-c7zxebFhp04dt73LTT80RXR1xu-af7ISNe6ZbT4lG12MdVEdAZPGcYNrEvPKSj34R1rpdRZhtSHfFexBmimnJw7IWP3plNfcEHx6buYlkUl1wod9e1vA3WSlye2cQmTdJ5vrmB2pTxFZpqGRpr_5wzl5C0GUiq5Be5M7ZweyVNh_tRq1TPfiOPhaQvT6BzjUsnXpCl3hmQjmFFL2s-2UXy2cJTBitEe1PUW1P9bC6d0A0kngrKdBQzFdNqpHpjUHU1G3k')"></div>
<div class="absolute inset-0 flex items-center justify-center">
<div class="bg-white p-xs rounded-full shadow-lg animate-bounce">
<span class="material-symbols-outlined text-primary text-display-lg" style="font-variation-settings: 'FILL' 1;">location_on</span>
</div>
</div>
<div class="absolute bottom-md left-md bg-white/90 backdrop-blur p-md rounded-xl shadow-md border border-outline-variant/30">
<p class="font-label-sm text-label-sm text-on-surface-variant uppercase">Destination</p>
<p class="font-title-lg text-title-lg">1204 Pine St, Apartment 4B</p>
<div class="flex items-center gap-md mt-sm">
<span class="bg-primary/10 text-primary px-xs py-[2px] rounded text-label-md">8 mins away</span>
<span class="text-on-surface-variant text-label-md">0.8 miles</span>
</div>
</div>
</div>
</div>
<!-- Hoạt động Task Card -->
<div class="glass-card rounded-2xl p-lg flex flex-col md:flex-row gap-lg items-center">
<div class="h-16 w-16 rounded-full overflow-hidden border-4 border-primary/20">
<img class="w-full h-full object-cover" data-alt="Portrait of a friendly customer service representative with a warm smile, wearing professional modern attire, clean bright background, minimalist professional headshot style." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAA_8pbja5DyqUjjQT-bH7Rjnby8uc6JRS573aN6m9N6_zWQacb_JRnfR-ft8bt7xTDsJrUkiq1uD3cZcIQWQ5ZUYmnjGtCn72Nu82-AU6BXSev6tn0U51fD7RgkiTMFcqt6jylt2LFNCennIHKjilMZtk7kDleCyEFvh6KyLzeLFu5elMpeCBoZA1IJPPEWYNoNjyINCX4_Uw66_T9vtdMNbE6Cfo6KU61HV28cEjfIG8uoybyX0mnl16JAARE8cMpjhaL-dud"/>
</div>
<div class="flex-1 text-center md:text-left">
<p class="font-label-md text-label-md text-on-surface-variant">Delivering to</p>
<h3 class="font-headline-md text-headline-md">Sarah Jenkins</h3>
<p class="font-body-md text-body-md text-on-surface-variant">Order #9928 • 3 items</p>
</div>
<div class="flex gap-md w-full md:w-auto">
<button class="flex-1 md:flex-none border border-outline px-lg py-sm rounded-xl font-label-md text-label-md hover:bg-surface-container transition-colors">
<span class="material-symbols-outlined align-middle mr-xs">chat</span> Contact
                                </button>
<button class="flex-1 md:flex-none bg-primary text-on-primary px-lg py-sm rounded-xl font-label-md text-label-md active:scale-95 transition-transform">
                                    Complete Delivery
                                </button>
</div>
</div>
</div>
<div class="lg:col-span-1 space-y-md">
<div class="glass-card rounded-2xl p-md">
<h4 class="font-title-lg text-title-lg mb-md">Order Details</h4>
<ul class="space-y-sm">
<li class="flex justify-between items-center py-xs border-b border-outline-variant/10">
<span class="text-on-surface-variant">Matcha Latte (Cold)</span>
<span class="font-label-md">x1</span>
</li>
<li class="flex justify-between items-center py-xs border-b border-outline-variant/10">
<span class="text-on-surface-variant">Green Booster Juice</span>
<span class="font-label-md">x2</span>
</li>
<li class="flex justify-between items-center py-xs">
<span class="text-on-surface-variant">Paper Straws Pack</span>
<span class="font-label-md">x1</span>
</li>
</ul>
<div class="mt-md bg-surface-container-low p-md rounded-xl">
<p class="font-label-sm text-label-sm text-on-surface-variant mb-xs">Khách hàng Note:</p>
<p class="text-body-md italic">"Please leave at the front door and ring the bell. Thank you!"</p>
</div>
</div>
</div>
</div>
<!-- Tab Content: History (Hidden by default) -->
<div class="hidden animate-in fade-in duration-500" id="content-history">
<div class="glass-card rounded-2xl overflow-hidden">
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-surface-container-low border-b border-outline-variant/30">
<th class="px-lg py-md font-label-sm text-label-sm text-on-surface-variant uppercase">Order ID</th>
<th class="px-lg py-md font-label-sm text-label-sm text-on-surface-variant uppercase">Ngày</th>
<th class="px-lg py-md font-label-sm text-label-sm text-on-surface-variant uppercase">Distance</th>
<th class="px-lg py-md font-label-sm text-label-sm text-on-surface-variant uppercase">Earning</th>
<th class="px-lg py-md font-label-sm text-label-sm text-on-surface-variant uppercase">Trạng thái</th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant/10">
<tr class="hover:bg-surface-container-lowest transition-colors">
<td class="px-lg py-md font-label-md">#9925</td>
<td class="px-lg py-md text-on-surface-variant text-body-md">Oct 24, 2:15 PM</td>
<td class="px-lg py-md text-on-surface-variant text-body-md">3.2 mi</td>
<td class="px-lg py-md font-bold text-primary">$15.20</td>
<td class="px-lg py-md">
<span class="bg-primary/10 text-primary px-xs py-[2px] rounded-full text-label-sm">Delivered</span>
</td>
</tr>
<tr class="hover:bg-surface-container-lowest transition-colors">
<td class="px-lg py-md font-label-md">#9920</td>
<td class="px-lg py-md text-on-surface-variant text-body-md">Oct 24, 1:30 PM</td>
<td class="px-lg py-md text-on-surface-variant text-body-md">1.5 mi</td>
<td class="px-lg py-md font-bold text-primary">$9.45</td>
<td class="px-lg py-md">
<span class="bg-primary/10 text-primary px-xs py-[2px] rounded-full text-label-sm">Delivered</span>
</td>
</tr>
<tr class="hover:bg-surface-container-lowest transition-colors">
<td class="px-lg py-md font-label-md">#9918</td>
<td class="px-lg py-md text-on-surface-variant text-body-md">Oct 24, 12:45 PM</td>
<td class="px-lg py-md text-on-surface-variant text-body-md">4.0 mi</td>
<td class="px-lg py-md font-bold text-primary">$18.00</td>
<td class="px-lg py-md">
<span class="bg-primary/10 text-primary px-xs py-[2px] rounded-full text-label-sm">Delivered</span>
</td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
</div>
</main>
@endsection

@push('scripts')
<script>

        function switchTab(tabId) {
            // Hide all content
            document.getElementById('content-available').classList.add('hidden');
            document.getElementById('content-active').classList.add('hidden');
            document.getElementById('content-history').classList.add('hidden');

            // Reset all buttons
            const buttons = ['available', 'active', 'history'];
            buttons.forEach(id => {
                const btn = document.getElementById('tab-' + id);
                btn.classList.remove('border-b-2', 'border-primary', 'text-primary', 'font-bold');
                btn.classList.add('text-on-surface-variant');
            });

            // Show target content
            document.getElementById('content-' + tabId).classList.remove('hidden');
            
            // Set active button style
            const activeBtn = document.getElementById('tab-' + tabId);
            activeBtn.classList.add('border-b-2', 'border-primary', 'text-primary', 'font-bold');
            activeBtn.classList.remove('text-on-surface-variant');
        }

        // Initialize state
        window.onload = () => {
            switchTab('available');
        };
    
</script>
@endpush
