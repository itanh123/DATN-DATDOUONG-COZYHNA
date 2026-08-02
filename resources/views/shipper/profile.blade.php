@extends('layouts.admin')

@section('title', 'Profile')

@section('content')
<main class="ml-[280px] pt-16 min-h-screen px-gutter pb-xl">
<!-- Profile Header Giâytion -->
<section class="mt-xl mb-lg">
<div class="relative w-full h-48 rounded-2xl overflow-hidden mb-[-4rem]">
<div class="absolute inset-0 organic-gradient opacity-90"></div>

</div>
<div class="relative px-lg flex flex-col md:flex-row items-end gap-md">
<div class="w-32 h-32 rounded-3xl border-4 border-background bg-white overflow-hidden shadow-lg">
<img class="w-full h-full object-cover" data-alt="Marcus Chen close-up portrait, focusing on his professional and approachable demeanor. The photo is well-lit with soft key light and a clean architectural background. He wears a subtle smile, reflecting a healthy and positive lifestyle, aligned with the brand's premium organic values." src="https://lh3.googleusercontent.com/aida-public/AB6AXuB_-crgW0ZkRc5paY1wQJN4w9wYu6y9qUHPGsBr2_9eAYFCrxunbVodpsrpoxeuLF9dWNcDdZhk53mym1vsQ8E6zMoT2lwdIugn9-snFHeM-2uCTTSgUGEhAJc6BNPkOH-uAyCtqCEQzfNMqwammZtHxSSRcxvrQwbHPMH3QaRwkTW__ylM9FYBAv1P3S8pGXl0_J-oZL9Ns__tt3XBFS0eiZemH_yJ3bPeEuSrEJJh3KNdSfw1p9LuKgeRMWY7_1OPp734qy1M"/>
</div>
<div class="flex-1 pb-2">
<div class="flex items-center gap-xs">
<h1 class="font-headline-lg text-headline-lg">Marcus Chen</h1>
<span class="bg-secondary-container text-on-secondary-container px-3 py-1 rounded-full text-label-md flex items-center gap-1">
<span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                            Hoạt động
                        </span>
</div>
<p class="text-on-surface-variant font-body-lg">Senior Logistics Specialist • ID: COZY-9920</p>
</div>
<div class="pb-2">
<button class="bg-surface-container-highest text-on-surface border border-outline-variant px-md py-2 rounded-xl font-title-lg flex items-center gap-2 hover:bg-surface-container-high transition-colors">
<span class="material-symbols-outlined text-[20px]" data-icon="edit">edit</span>
                        Sửa Profile
                    </button>
</div>
</div>
</section>
<!-- Bento Grid Layout for Stats & Cards -->
<div class="grid grid-cols-1 md:grid-cols-12 gap-lg mt-12">
<!-- Performance Stats (Bento Span 8) -->
<div class="md:col-span-8 grid grid-cols-1 sm:grid-cols-3 gap-md">
<div class="glass-card p-lg rounded-2xl flex flex-col items-center text-center">
<span class="material-symbols-outlined text-primary bg-primary-container p-3 rounded-full mb-md" data-icon="check_circle">check_circle</span>
<p class="text-on-surface-variant text-label-md uppercase tracking-wider">Lifetime Deliveries</p>
<h3 class="text-headline-lg font-headline-lg mt-xs">1,284</h3>
<div class="text-primary text-label-sm mt-2 flex items-center gap-1">
<span class="material-symbols-outlined text-[14px]" data-icon="trending_up">trending_up</span> 12% vs last month
                    </div>
</div>
<div class="glass-card p-lg rounded-2xl flex flex-col items-center text-center">
<span class="material-symbols-outlined text-tertiary bg-tertiary-container p-3 rounded-full mb-md" data-icon="star">star</span>
<p class="text-on-surface-variant text-label-md uppercase tracking-wider">Average Rating</p>
<h3 class="text-headline-lg font-headline-lg mt-xs">4.95</h3>
<p class="text-on-surface-variant text-label-sm mt-2">Based on 840 reviews</p>
</div>
<div class="glass-card p-lg rounded-2xl flex flex-col items-center text-center">
<span class="material-symbols-outlined text-secondary bg-secondary-container p-3 rounded-full mb-md" data-icon="schedule">schedule</span>
<p class="text-on-surface-variant text-label-md uppercase tracking-wider">On-Time Rate</p>
<h3 class="text-headline-lg font-headline-lg mt-xs">98%</h3>
<div class="text-secondary text-label-sm mt-2 flex items-center gap-1">
<span class="material-symbols-outlined text-[14px]" data-icon="bolt">bolt</span> Elite Performer
                    </div>
</div>
</div>
<!-- Digital Wallet (Bento Span 4) -->
<div class="md:col-span-4 glass-card rounded-2xl flex flex-col overflow-hidden">
<div class="organic-gradient p-lg text-on-primary">
<div class="flex justify-between items-start mb-md">
<p class="text-label-md opacity-80 uppercase tracking-widest">Available Balance</p>
<span class="material-symbols-outlined" data-icon="account_balance_wallet">account_balance_wallet</span>
</div>
<h2 class="text-display-lg font-display-lg">$2,450.80</h2>
<button class="mt-md w-full bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white py-2 rounded-lg font-title-lg transition-colors">
                        Payout Now
                    </button>
</div>
<div class="p-lg">
<h4 class="font-title-lg text-title-lg mb-md">Recent Transactions</h4>
<div class="space-y-md">
<div class="flex justify-between items-center">
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded-full bg-surface-container-high flex items-center justify-center">
<span class="material-symbols-outlined text-[18px] text-primary" data-icon="add">add</span>
</div>
<div>
<p class="text-body-md font-bold">Delivery Earnings</p>
<p class="text-label-sm text-on-surface-variant">Oct 24, 2023</p>
</div>
</div>
<span class="font-title-lg text-primary">+$142.50</span>
</div>
<div class="flex justify-between items-center">
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded-full bg-surface-container-high flex items-center justify-center">
<span class="material-symbols-outlined text-[18px] text-secondary" data-icon="card_giftcard">card_giftcard</span>
</div>
<div>
<p class="text-body-md font-bold">Tip - Route 82</p>
<p class="text-label-sm text-on-surface-variant">Oct 23, 2023</p>
</div>
</div>
<span class="font-title-lg text-primary">+$25.00</span>
</div>
</div>
</div>
</div>
<!-- Vehicle Information (Bento Span 6) -->
<div class="md:col-span-6 glass-card rounded-2xl p-lg">
<div class="flex justify-between items-start mb-lg">
<div>
<h4 class="font-title-lg text-title-lg">Assigned Vehicle</h4>
<p class="text-on-surface-variant text-body-md">2023 Electric Beverage Van</p>
</div>
<span class="bg-primary-container text-on-primary-container px-3 py-1 rounded-full text-label-sm">In Good Standing</span>
</div>
<div class="relative w-full h-48 rounded-xl overflow-hidden mb-lg">
<img class="w-full h-full object-cover" data-alt="A modern, sleek electric delivery van in a clean white and forest green livery parked in a sunlit urban charging station. The van features the 'CozyHNA' logo on the side. The scene is bright and professional, emphasizing eco-friendly logistics and modern technology." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDkrInxMgFfQ7ostrznlj2Duzh_aNhddnMzKrVBeXgjcGDmAltUuJVZ58xcn3vbzdeKN2jqLS_WwXWapWgB9SVbesp_XDVzscnh9nac1oPwdw2pZ3AVISu0FD-55v6UB7_Y772BtZEl-luPU2VsxF04YJe0o6SOnz32omnvLPBLqNRwTo5_V991o-ADmhDs5OjqQyFyRxEvxvxUWiySh4igrp4QSR7xqFCP9SJBnegS9XZvn9ajXCg8R4W3RjBZMydGL9mh80uE"/>
<div class="absolute bottom-4 left-4 bg-black/50 backdrop-blur-md px-4 py-2 rounded-lg text-white">
<p class="text-label-sm opacity-80">Plate Number</p>
<p class="font-bold tracking-widest">BVRG-202</p>
</div>
</div>
<div class="grid grid-cols-2 gap-lg">
<div>
<p class="text-label-md text-on-surface-variant">Last Maintenance</p>
<p class="font-body-lg font-bold">Sep 12, 2023</p>
</div>
<div>
<p class="text-label-md text-on-surface-variant">Battery Health</p>
<div class="flex items-center gap-2">
<div class="flex-1 h-2 bg-surface-container-highest rounded-full overflow-hidden">
<div class="h-full bg-primary w-[94%]"></div>
</div>
<span class="text-label-sm font-bold">94%</span>
</div>
</div>
</div>
</div>
<!-- Documents & Compliance (Bento Span 6) -->
<div class="md:col-span-6 glass-card rounded-2xl p-lg flex flex-col">
<h4 class="font-title-lg text-title-lg mb-lg">Documents &amp; Compliance</h4>
<div class="flex-1 space-y-md">
<div class="p-md bg-surface-container-low rounded-xl flex items-center justify-between">
<div class="flex items-center gap-4">
<span class="material-symbols-outlined text-primary" data-icon="badge">badge</span>
<div>
<p class="font-body-md font-bold">Driver's License (Class B)</p>
<p class="text-label-sm text-on-surface-variant">Expires: Dec 2025</p>
</div>
</div>
<div class="flex items-center gap-1 text-primary">
<span class="material-symbols-outlined text-[18px]" data-icon="verified">verified</span>
<span class="text-label-sm">Verified</span>
</div>
</div>
<div class="p-md bg-surface-container-low rounded-xl flex items-center justify-between">
<div class="flex items-center gap-4">
<span class="material-symbols-outlined text-primary" data-icon="description">description</span>
<div>
<p class="font-body-md font-bold">Vehicle Insurance</p>
<p class="text-label-sm text-on-surface-variant">Expires: Jun 2024</p>
</div>
</div>
<div class="flex items-center gap-1 text-primary">
<span class="material-symbols-outlined text-[18px]" data-icon="verified">verified</span>
<span class="text-label-sm">Verified</span>
</div>
</div>
<div class="p-md bg-surface-container-low rounded-xl flex items-center justify-between">
<div class="flex items-center gap-4">
<span class="material-symbols-outlined text-primary" data-icon="medical_services">medical_services</span>
<div>
<p class="font-body-md font-bold">Health Certification</p>
<p class="text-label-sm text-on-surface-variant">Current &amp; Compliant</p>
</div>
</div>
<div class="flex items-center gap-1 text-primary">
<span class="material-symbols-outlined text-[18px]" data-icon="verified">verified</span>
<span class="text-label-sm">Verified</span>
</div>
</div>
</div>
<button class="mt-lg text-primary font-title-lg flex items-center gap-2 self-start hover:underline">
                    Upload New Document
                    <span class="material-symbols-outlined text-[18px]" data-icon="upload">upload</span>
</button>
</div>
<!-- Settings & Preferences (Full Width Span) -->
<div class="md:col-span-12 glass-card rounded-2xl p-lg">
<h4 class="font-title-lg text-title-lg mb-lg">Personal Settings</h4>
<div class="grid grid-cols-1 md:grid-cols-2 gap-2xl">
<div>
<h5 class="text-label-md font-bold uppercase tracking-wider text-on-surface-variant mb-md">Notification Preferences</h5>
<div class="space-y-lg">
<div class="flex items-center justify-between">
<div>
<p class="font-body-md font-bold">Push Notifications</p>
<p class="text-label-sm text-on-surface-variant">Receive new delivery alerts</p>
</div>
<label class="relative inline-flex items-center cursor-pointer">
<input checked="" class="sr-only peer" type="checkbox"/>
<div class="w-11 h-6 bg-surface-container-highest peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-outline-variant after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
</label>
</div>
<div class="flex items-center justify-between">
<div>
<p class="font-body-md font-bold">SMS Updates</p>
<p class="text-label-sm text-on-surface-variant">Emergency route changes only</p>
</div>
<label class="relative inline-flex items-center cursor-pointer">
<input class="sr-only peer" type="checkbox"/>
<div class="w-11 h-6 bg-surface-container-highest peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-outline-variant after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
</label>
</div>
</div>
</div>
<div>
<h5 class="text-label-md font-bold uppercase tracking-wider text-on-surface-variant mb-md">Emergency Contact</h5>
<div class="space-y-md">
<div class="grid grid-cols-2 gap-md">
<div>
<label class="block text-label-sm text-on-surface-variant mb-1">Name</label>
<input class="w-full bg-surface-container-low border border-outline-variant rounded-lg p-2 text-body-md focus:ring-primary focus:border-primary" type="text" value="Sarah Chen"/>
</div>
<div>
<label class="block text-label-sm text-on-surface-variant mb-1">Relationship</label>
<input class="w-full bg-surface-container-low border border-outline-variant rounded-lg p-2 text-body-md focus:ring-primary focus:border-primary" type="text" value="Spouse"/>
</div>
</div>
<div>
<label class="block text-label-sm text-on-surface-variant mb-1">Số điện thoại Number</label>
<input class="w-full bg-surface-container-low border border-outline-variant rounded-lg p-2 text-body-md focus:ring-primary focus:border-primary" type="tel" value="+1 (555) 012-3456"/>
</div>
</div>
</div>
</div>
<div class="mt-xl pt-xl border-t border-outline-variant flex justify-end gap-md">
<button class="px-lg py-2 rounded-xl text-on-surface-variant hover:bg-surface-container-low transition-colors">Discard Changes</button>
<button class="px-lg py-2 rounded-xl bg-primary text-on-primary font-title-lg active:scale-95 transition-transform">Save Preferences</button>
</div>
</div>
</div>
</main>
@endsection

@push('scripts')
<script>

        // Micro-interaction: Hover effects on cards
        document.querySelectorAll('.glass-card').forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-4px)';
                card.style.boxShadow = '0px 20px 25px -5px rgba(0,0,0,0.1)';
                card.style.transition = 'all 0.3s ease';
            });
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0px)';
                card.style.boxShadow = '0px 4px 6px -1px rgba(0,0,0,0.05)';
            });
        });
    
</script>
@endpush
