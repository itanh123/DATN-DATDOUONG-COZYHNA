@extends('layouts.admin')

@section('title', 'Bảng điều khiển')

@section('content')
<main class="ml-[280px] min-h-screen">
<!-- TopAppBar Anchor -->
<header class="fixed top-0 right-0 w-[calc(100%-280px)] h-16 bg-surface/80 dark:bg-surface-dim/80 backdrop-blur-md border-b border-outline-variant dark:border-outline flex justify-between items-center px-gutter z-40">
<div class="flex items-center gap-md">
<h2 class="font-headline-md text-headline-md font-black text-primary dark:text-primary-fixed-dim">Fleet Logistics</h2>
<div class="h-6 w-[1px] bg-outline-variant mx-sm"></div>
<div class="flex items-center gap-xs text-on-surface-variant">
<span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">location_on</span>
<span class="font-label-md">San Francisco Hub B</span>
</div>
</div>
<div class="flex items-center gap-lg">
<div class="relative hidden lg:block">
<input class="bg-surface-container-low border-none rounded-full px-xl py-2 text-sm w-64 focus:ring-2 focus:ring-primary/20" placeholder="Search orders..." type="text"/>
<span class="material-symbols-outlined absolute right-3 top-2 text-on-surface-variant">search</span>
</div>
<div class="flex items-center gap-md">
<button class="material-symbols-outlined p-2 hover:bg-surface-container-high rounded-full transition-colors relative">
                        notifications
                        <span class="absolute top-2 right-2 w-2 h-2 bg-error rounded-full border-2 border-surface"></span>
</button>
<button class="material-symbols-outlined p-2 hover:bg-surface-container-high rounded-full transition-colors text-error">
                        emergency_home
                    </button>
</div>
</div>
</header>
<!-- Bảng điều khiển Canvas -->
<div class="pt-24 pb-xl px-gutter max-w-container-max mx-auto">
<!-- Quick Trạng thái Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-lg mb-xl">
<div class="glass-card p-lg rounded-xl flex items-center gap-md shadow-sm border border-outline-variant/30 hover:shadow-md transition-shadow">
<div class="p-md bg-primary/10 text-primary rounded-lg">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">task_alt</span>
</div>
<div>
<p class="font-label-sm text-on-surface-variant">Hoàn thành Today</p>
<h3 class="font-headline-lg text-headline-lg text-primary">14</h3>
</div>
</div>
<div class="glass-card p-lg rounded-xl flex items-center gap-md shadow-sm border border-outline-variant/30 hover:shadow-md transition-shadow">
<div class="p-md bg-secondary-container text-secondary rounded-lg">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">timer</span>
</div>
<div>
<p class="font-label-sm text-on-surface-variant">Hoạt động Road Time</p>
<h3 class="font-headline-lg text-headline-lg text-on-surface">6h 12m</h3>
</div>
</div>
<div class="glass-card p-lg rounded-xl flex items-center gap-md shadow-sm border border-outline-variant/30 hover:shadow-md transition-shadow">
<div class="p-md bg-tertiary-container/20 text-tertiary rounded-lg">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
</div>
<div>
<p class="font-label-sm text-on-surface-variant">Rating</p>
<h3 class="font-headline-lg text-headline-lg text-on-surface">4.95</h3>
</div>
</div>
<div class="glass-card p-lg rounded-xl flex items-center gap-md shadow-sm border border-outline-variant/30 hover:shadow-md transition-shadow">
<div class="p-md bg-primary-container text-on-primary-container rounded-lg">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">payments</span>
</div>
<div>
<p class="font-label-sm text-on-surface-variant">Est. Earnings</p>
<h3 class="font-headline-lg text-headline-lg text-on-primary-container">$312.40</h3>
</div>
</div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-xl">
<!-- Left Column: Hoạt động Route & Queue -->
<div class="lg:col-span-2 space-y-xl">
<!-- Hoạt động Route Map Giâytion -->
<section class="glass-card rounded-2xl overflow-hidden border border-outline-variant/50">
<div class="p-lg flex justify-between items-center bg-white/50 border-b border-outline-variant/20">
<div>
<h3 class="font-title-lg text-title-lg text-on-surface flex items-center gap-sm">
<span class="material-symbols-outlined text-primary">map</span>
                                    Hoạt động Route: Route SF-North
                                </h3>
<p class="font-body-md text-on-surface-variant">Next Stop: 1280 Mission St. (2.4 miles)</p>
</div>
<div class="flex gap-sm">
<button class="px-md py-xs bg-surface-container rounded-lg font-label-md hover:bg-surface-container-high transition-colors">Re-center</button>
<button class="px-md py-xs bg-primary text-white rounded-lg font-label-md flex items-center gap-xs active:scale-95 transition-transform">
<span class="material-symbols-outlined text-[18px]">navigation</span>
                                    Navigate
                                </button>
</div>
</div>
<div class="h-[400px] bg-surface-container relative">
<div class="absolute inset-0 grayscale opacity-60 mix-blend-multiply" data-alt="A sophisticated, high-contrast dark-mode styled topographic map of San Francisco showing clean street layouts and nautical elements. The aesthetic is professional and modern, featuring thin white route lines and vibrant green marker nodes representing logistics waypoints against a charcoal and navy background." data-location="San Francisco" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCCNTjOwIXsfvFnlZFGlpqCVIoKoBUXa2l7I1gzlBdbXe3WkOViHSQHx0bGzMIfUONeWfmlt-mxB3tV3IehkQ4qY8egjYe2KXc5OScfnRQensPEf76cDcifqlEAUG9pk0Y9IUuiIIVpUZCgF-Lh83n13TYt_Xg_GXdYgPZfXfS_J1TZWgGVpehKSIl7poehMeNhMiGJAZ12QkgngZzfp5OLd3l92eV7G3YSClDzoOSfT0_FXfqFMsvo7J8qbEXRAKaSDbhIZqJi')"></div>
<!-- Custom Map Overlay -->
<div class="absolute inset-0 p-xl flex flex-col justify-center items-center">
<!-- Simulated Route Visualization -->
<svg class="w-full h-full opacity-80" viewbox="0 0 800 400">
<path class="animate-dash" d="M100 300 Q 200 100 400 200 T 700 150" fill="none" stroke="#006e1c" stroke-dasharray="10 5" stroke-width="6"></path>
<circle class="animate-pulse" cx="100" cy="300" fill="#006e1c" r="10"></circle>
<circle cx="400" cy="200" fill="#4caf50" r="8"></circle>
<circle cx="700" cy="150" fill="#ba1a1a" r="12"></circle>
</svg>
</div>
<!-- Float Card on Map -->
<div class="absolute bottom-md left-md right-md lg:left-lg lg:w-80">
<div class="bg-white/90 backdrop-blur-md p-md rounded-xl shadow-xl border border-white">
<div class="flex items-center gap-md mb-sm">
<div class="w-10 h-10 bg-primary/20 rounded-full flex items-center justify-center">
<span class="material-symbols-outlined text-primary">local_shipping</span>
</div>
<div>
<p class="font-label-sm text-xs text-on-surface-variant">CURRENT POSITION</p>
<p class="font-body-md font-bold">Transit to Stop 4/8</p>
</div>
</div>
<div class="w-full bg-surface-container rounded-full h-1.5 mb-xs">
<div class="bg-primary h-full rounded-full w-3/5"></div>
</div>
<p class="text-[10px] text-right font-label-md text-primary">60% of Route Complete</p>
</div>
</div>
</div>
</section>
<!-- Delivery Queue -->
<section class="glass-card rounded-2xl p-lg border border-outline-variant/50">
<h3 class="font-title-lg text-title-lg mb-lg flex items-center gap-sm">
<span class="material-symbols-outlined text-primary">view_list</span>
                            Upcoming Delivery Queue
                        </h3>
<div class="space-y-sm">
<!-- Queue Item 1 -->
<div class="flex flex-col md:flex-row items-start md:items-center justify-between p-md border-b border-outline-variant/20 hover:bg-surface-container-low rounded-lg transition-colors group">
<div class="flex items-center gap-md">
<div class="w-12 h-12 bg-surface-container rounded-lg flex items-center justify-center font-bold text-primary group-hover:bg-primary-container group-hover:text-on-primary-container transition-colors">
                                        4
                                    </div>
<div>
<p class="font-title-lg text-sm">Organic Wellness Spa</p>
<p class="font-body-md text-xs text-on-surface-variant">1280 Mission St, Suite 400</p>
</div>
</div>
<div class="flex items-center gap-xl mt-md md:mt-0">
<div class="text-right">
<p class="font-label-sm text-[10px] uppercase text-on-surface-variant">Items</p>
<p class="font-body-md text-sm">24x Phúteral Water, 12x Kombucha</p>
</div>
<div class="text-right min-w-[100px]">
<p class="font-label-sm text-[10px] uppercase text-on-surface-variant">ETA</p>
<p class="font-body-md text-sm font-bold text-primary">14:45 PM</p>
</div>
<button class="material-symbols-outlined text-on-surface-variant hover:text-primary transition-colors">chevron_right</button>
</div>
</div>
<!-- Queue Item 2 -->
<div class="flex flex-col md:flex-row items-start md:items-center justify-between p-md border-b border-outline-variant/20 hover:bg-surface-container-low rounded-lg transition-colors group">
<div class="flex items-center gap-md">
<div class="w-12 h-12 bg-surface-container rounded-lg flex items-center justify-center font-bold text-on-surface-variant">
                                        5
                                    </div>
<div>
<p class="font-title-lg text-sm">The Daily Grind Cafe</p>
<p class="font-body-md text-xs text-on-surface-variant">892 Market Street</p>
</div>
</div>
<div class="flex items-center gap-xl mt-md md:mt-0">
<div class="text-right">
<p class="font-label-sm text-[10px] uppercase text-on-surface-variant">Items</p>
<p class="font-body-md text-sm">4x Espresso Cases, 2x Oat Milk</p>
</div>
<div class="text-right min-w-[100px]">
<p class="font-label-sm text-[10px] uppercase text-on-surface-variant">ETA</p>
<p class="font-body-md text-sm font-bold">15:10 PM</p>
</div>
<button class="material-symbols-outlined text-on-surface-variant hover:text-primary transition-colors">chevron_right</button>
</div>
</div>
<!-- Queue Item 3 -->
<div class="flex flex-col md:flex-row items-start md:items-center justify-between p-md hover:bg-surface-container-low rounded-lg transition-colors group">
<div class="flex items-center gap-md">
<div class="w-12 h-12 bg-surface-container rounded-lg flex items-center justify-center font-bold text-on-surface-variant">
                                        6
                                    </div>
<div>
<p class="font-title-lg text-sm">TechTower Lobby Bar</p>
<p class="font-body-md text-xs text-on-surface-variant">101 California St.</p>
</div>
</div>
<div class="flex items-center gap-xl mt-md md:mt-0">
<div class="text-right">
<p class="font-label-sm text-[10px] uppercase text-on-surface-variant">Items</p>
<p class="font-body-md text-sm">48x Premium Sparkling</p>
</div>
<div class="text-right min-w-[100px]">
<p class="font-label-sm text-[10px] uppercase text-on-surface-variant">ETA</p>
<p class="font-body-md text-sm font-bold">15:35 PM</p>
</div>
<button class="material-symbols-outlined text-on-surface-variant hover:text-primary transition-colors">chevron_right</button>
</div>
</div>
</div>
</section>
</div>
<!-- Right Column: Sidebar/Widgets -->
<div class="space-y-xl">
<!-- Shift Control Widget -->
<section class="bg-primary text-white rounded-2xl p-xl shadow-lg relative overflow-hidden">
<!-- Subtle Background Pattern -->
<div class="absolute -right-10 -bottom-10 opacity-10">
<span class="material-symbols-outlined text-[180px]">local_shipping</span>
</div>
<div class="relative z-10">
<h4 class="font-label-md text-white/70 uppercase tracking-widest mb-md">Shift Control</h4>
<div class="flex items-center justify-between mb-lg">
<div>
<p class="font-display-lg text-display-lg leading-tight" id="shift-timer">06:12:45</p>
<p class="font-label-sm text-white/80">Time on Road</p>
</div>
<div class="bg-white/20 p-sm rounded-lg backdrop-blur-md">
<span class="material-symbols-outlined animate-pulse" style="font-variation-settings: 'FILL' 1;">sensors</span>
</div>
</div>
<button class="w-full bg-white text-primary font-headline-md py-lg rounded-xl flex items-center justify-center gap-md hover:shadow-xl active:scale-95 transition-all" id="shift-toggle">
<span class="material-symbols-outlined">stop_circle</span>
                                End Shift
                            </button>
<p class="text-center mt-md text-xs text-white/60">Shift started at 08:30 AM • Hub SF-B</p>
</div>
</section>
<!-- Performance Snapshot -->
<section class="glass-card rounded-2xl p-lg border border-outline-variant/50">
<div class="flex justify-between items-center mb-lg">
<h4 class="font-title-lg text-sm">Weekly Performance</h4>
<span class="text-primary font-bold text-xs flex items-center gap-xs">
<span class="material-symbols-outlined text-sm">trending_up</span>
                                +12%
                            </span>
</div>
<div class="h-32 flex items-end gap-2 px-sm">
<div class="flex-grow bg-surface-container rounded-t-md hover:bg-secondary transition-all" style="height: 60%"></div>
<div class="flex-grow bg-surface-container rounded-t-md hover:bg-secondary transition-all" style="height: 45%"></div>
<div class="flex-grow bg-surface-container rounded-t-md hover:bg-secondary transition-all" style="height: 80%"></div>
<div class="flex-grow bg-surface-container rounded-t-md hover:bg-secondary transition-all" style="height: 55%"></div>
<div class="flex-grow bg-surface-container rounded-t-md hover:bg-secondary transition-all" style="height: 90%"></div>
<div class="flex-grow bg-primary rounded-t-md" style="height: 70%"></div>
<div class="flex-grow bg-surface-container-high rounded-t-md border-2 border-dashed border-primary/30" style="height: 30%"></div>
</div>
<div class="flex justify-between mt-sm px-sm">
<span class="text-[10px] text-on-surface-variant">Mon</span>
<span class="text-[10px] text-on-surface-variant">Tue</span>
<span class="text-[10px] text-on-surface-variant">Wed</span>
<span class="text-[10px] text-on-surface-variant">Thu</span>
<span class="text-[10px] text-on-surface-variant">Fri</span>
<span class="text-[10px] font-bold text-primary">Sat</span>
<span class="text-[10px] text-on-surface-variant">Sun</span>
</div>
</section>
<!-- Safety & Vehicle Checklist -->
<section class="glass-card rounded-2xl p-lg border border-outline-variant/50">
<h4 class="font-title-lg text-sm mb-lg">Vehicle Integrity</h4>
<div class="space-y-md">
<div class="flex items-center justify-between p-sm bg-surface rounded-lg">
<div class="flex items-center gap-md">
<span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">tire_repair</span>
<span class="font-body-md text-sm">Tire Pressure</span>
</div>
<span class="font-label-sm text-primary">Optimal</span>
</div>
<div class="flex items-center justify-between p-sm bg-surface rounded-lg">
<div class="flex items-center gap-md">
<span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">battery_charging_full</span>
<span class="font-body-md text-sm">Battery Health</span>
</div>
<span class="font-label-sm text-primary">94%</span>
</div>
<div class="flex items-center justify-between p-sm bg-surface rounded-lg">
<div class="flex items-center gap-md">
<span class="material-symbols-outlined text-tertiary" style="font-variation-settings: 'FILL' 1;">minor_crash</span>
<span class="font-body-md text-sm">Brake Pads</span>
</div>
<span class="font-label-sm text-tertiary">Service Soon</span>
</div>
</div>
<button class="w-full mt-lg border-2 border-outline-variant text-on-surface-variant font-label-md py-md rounded-xl hover:bg-surface-container transition-colors">
                            Full Safety Report
                        </button>
</section>
</div>
</div>
</div>
</main>
@endsection

@push('scripts')
<script>

        // Micro-interactions for the Shift Button
        const shiftBtn = document.getElementById('shift-toggle');
        let isHoạt động = true;
        
        shiftBtn.addEventListener('click', () => {
            isHoạt động = !isHoạt động;
            if(!isHoạt động) {
                shiftBtn.innerHTML = '<span class="material-symbols-outlined">play_circle</span> Start Shift';
                shiftBtn.classList.remove('bg-white', 'text-primary');
                shiftBtn.classList.add('bg-secondary-fixed', 'text-on-secondary-fixed');
                document.getElementById('shift-timer').classList.add('opacity-50');
            } else {
                shiftBtn.innerHTML = '<span class="material-symbols-outlined">stop_circle</span> End Shift';
                shiftBtn.classList.add('bg-white', 'text-primary');
                shiftBtn.classList.remove('bg-secondary-fixed', 'text-on-secondary-fixed');
                document.getElementById('shift-timer').classList.remove('opacity-50');
            }
        });

        // Simple Timer simulation
        let seconds = 22365; // Matches 06:12:45
        setInterval(() => {
            if(isHoạt động) {
                seconds++;
                const h = Math.floor(seconds / 3600).toString().padStart(2, '0');
                const m = Math.floor((seconds % 3600) / 60).toString().padStart(2, '0');
                const s = (seconds % 60).toString().padStart(2, '0');
                document.getElementById('shift-timer').innerText = `${h}:${m}:${s}`;
            }
        }, 1000);
    
</script>
@endpush
