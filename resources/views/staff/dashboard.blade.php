@extends('layouts.admin')

@section('title', 'Bảng điều khiển')

@section('content')
<main class="pt-24 pb-12 px-gutter md:ml-[280px] min-h-screen">
<!-- Welcome Header & Shift Timer -->
<section class="mb-xl">
<div class="flex flex-col md:flex-row md:items-end justify-between gap-lg">
<div>
<h2 class="font-headline-lg text-headline-lg text-on-background mb-base">Morning, Elena!</h2>
<p class="font-body-lg text-body-lg text-on-surface-variant">Your station is ready. Let's make some great coffee today.</p>
</div>
<div class="glass-card rounded-2xl p-lg flex items-center gap-lg shadow-sm border border-outline-variant/20">
<div class="flex flex-col">
<span class="font-label-sm text-label-sm text-primary uppercase tracking-wider">Clocked In Since</span>
<span class="font-headline-md text-headline-md text-on-background font-bold" id="shift-timer">03:42:15</span>
</div>
<button class="px-lg py-sm bg-error text-on-error rounded-full font-label-md text-label-md shadow-sm hover:opacity-90 active:scale-95 transition-all">
                        Clock Out
                    </button>
</div>
</div>
</section>
<!-- Bento Grid Content -->
<div class="grid grid-cols-1 md:grid-cols-12 gap-lg">
<!-- My Shift Today -->
<div class="md:col-span-8 glass-card rounded-2xl p-lg flex flex-col gap-lg shadow-sm">
<div class="flex justify-between items-center">
<div class="flex items-center gap-sm">
<div class="w-10 h-10 bg-primary-container/20 rounded-full flex items-center justify-center text-primary">
<span class="material-symbols-outlined">schedule</span>
</div>
<h3 class="font-title-lg text-title-lg text-on-surface">My Shift Today</h3>
</div>
<span class="bg-secondary-container text-on-secondary-container px-md py-xs rounded-full font-label-sm text-label-sm">Lead Barista</span>
</div>
<div class="flex flex-col gap-md">
<div class="flex justify-between font-label-md text-label-md text-on-surface-variant">
<span>8:00 AM - 4:00 PM</span>
<span>4h 15m remaining</span>
</div>
<div class="w-full h-3 bg-surface-container rounded-full overflow-hidden">
<div class="h-full bg-primary-container w-[48%] rounded-full relative">
<div class="absolute inset-0 bg-white/20 animate-pulse"></div>
</div>
</div>
</div>
<div class="grid grid-cols-3 gap-md mt-base">
<div class="bg-surface-container-low p-md rounded-xl">
<p class="font-label-sm text-label-sm text-on-surface-variant mb-xs">Next Break</p>
<p class="font-title-lg text-title-lg text-on-background">12:30 PM</p>
</div>
<div class="bg-surface-container-low p-md rounded-xl">
<p class="font-label-sm text-label-sm text-on-surface-variant mb-xs">Station</p>
<p class="font-title-lg text-title-lg text-on-background">Espresso Bar</p>
</div>
<div class="bg-surface-container-low p-md rounded-xl">
<p class="font-label-sm text-label-sm text-on-surface-variant mb-xs">Overtime</p>
<p class="font-title-lg text-title-lg text-on-background">0.0h</p>
</div>
</div>
</div>
<!-- Team on Shift -->
<div class="md:col-span-4 glass-card rounded-2xl p-lg flex flex-col gap-lg shadow-sm">
<div class="flex items-center gap-sm">
<div class="w-10 h-10 bg-primary-container/20 rounded-full flex items-center justify-center text-primary">
<span class="material-symbols-outlined">group</span>
</div>
<h3 class="font-title-lg text-title-lg text-on-surface">Team on Shift</h3>
</div>
<div class="flex flex-col gap-md">
<div class="flex items-center justify-between p-sm hover:bg-surface-container-low rounded-xl transition-colors">
<div class="flex items-center gap-md">
<div class="relative">
<div class="w-10 h-10 rounded-full overflow-hidden">
<img class="w-full h-full object-cover" data-alt="A portrait of a male café worker with a friendly expression, wearing a dark green staff shirt, clean-shaven, in a soft natural light café setting." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAlXA9gZJNdguPD4pgEaQzT34DOA3h88gEXXCE7zt8TF85knWz9btqM_i9VGY1VN_ZyA3xPWIdeS2JxgSaIvKY7t4LeH-qfOGQEaL2uUfFhaDjlPYOjpKctfvZy3oHKViM3pu1eL1ZQXk7hzg6bdPVcvKgNkhqxX3AAg9TKl91FDCq2RRaxE2PVR9492_XwJ5OQhAVshfbsiFC1ryu-IdfaxS6MZ1l_gfQrFF7CsIQrO94SoSnCQWDKPoVNNxvOJPBpl3kclmvK"/>
</div>
<span class="absolute bottom-0 right-0 w-3 h-3 bg-secondary rounded-full border-2 border-white"></span>
</div>
<div>
<p class="font-label-md text-label-md text-on-surface">Marcus Chen</p>
<p class="text-xs text-on-surface-variant">Floor Manager</p>
</div>
</div>
<span class="text-xs font-label-sm text-secondary px-sm py-0.5 bg-secondary-container/30 rounded-full">On Floor</span>
</div>
<div class="flex items-center justify-between p-sm hover:bg-surface-container-low rounded-xl transition-colors">
<div class="flex items-center gap-md">
<div class="relative">
<div class="w-10 h-10 rounded-full overflow-hidden">
<img class="w-full h-full object-cover" data-alt="A portrait of a female barista with curly hair, wearing a barista apron, standing in a brightly lit coffee shop environment." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAF95bDgbVWRRQYSXo6lCqwhhhU_N-J7KCegkGoslyeWs77LUIcLUdSs-Tc31ARFCxwoCrPp2O3LT8UZXeLEUesnsB7ArNLc-jFsifduTBg_m4ETHVg_cldsCN0FmZ6i9GIrxj_y4ajN1LwTrOpnquhiQ9-rO9pkO0iyx7e4UkaMr78kFWl8pZs9V11ceRsVoXtGVbHgEzrGnmNuSdL4Wh4XaPVEZ-heFKP8MvtAkg6-R-SDSPugg2cNBrno5wx0uSTrBajtoKT"/>
</div>
<span class="absolute bottom-0 right-0 w-3 h-3 bg-tertiary-container rounded-full border-2 border-white"></span>
</div>
<div>
<p class="font-label-md text-label-md text-on-surface">Sofia Lopez</p>
<p class="text-xs text-on-surface-variant">Junior Barista</p>
</div>
</div>
<span class="text-xs font-label-sm text-tertiary px-sm py-0.5 bg-tertiary-container/20 rounded-full">On Break</span>
</div>
<div class="flex items-center justify-between p-sm hover:bg-surface-container-low rounded-xl transition-colors">
<div class="flex items-center gap-md">
<div class="relative">
<div class="w-10 h-10 rounded-full overflow-hidden">
<img class="w-full h-full object-cover" data-alt="A headshot of a friendly young man with glasses working as a barista, wearing a green branded shirt, in a minimalist aesthetic cafe." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDRwqskKF08wgWDlMCByvg4-_71Er2oX7q-cmayhwvekeM2koh-vDVGARt2wXMKJeIlKPgBAfRmAQ3Msc61zz33M7_9RHBAlDhL8CnXNJl3zqOybK1j217Mnto6g3_IW1aZl0iibg1KwfK3DkLwTK0O_CfzH6VtXBNZLRCj9P4bLdNsPj0IB9BOJbfxy4dYPpbjEaVRJJSdwwHevKxvUKctG-vpzDZ9Y1mcXiQDNoZWZVECVwbgTGWIDRqXkggi1o0USXbTz3h7"/>
</div>
<span class="absolute bottom-0 right-0 w-3 h-3 bg-secondary rounded-full border-2 border-white"></span>
</div>
<div>
<p class="font-label-md text-label-md text-on-surface">James Wilson</p>
<p class="text-xs text-on-surface-variant">Delivery Specialist</p>
</div>
</div>
<span class="text-xs font-label-sm text-secondary px-sm py-0.5 bg-secondary-container/30 rounded-full">On Floor</span>
</div>
</div>
</div>
<!-- Hoạt động Tasks Checklist -->
<div class="md:col-span-7 glass-card rounded-2xl p-lg flex flex-col gap-lg shadow-sm">
<div class="flex justify-between items-center">
<div class="flex items-center gap-sm">
<div class="w-10 h-10 bg-primary-container/20 rounded-full flex items-center justify-center text-primary">
<span class="material-symbols-outlined">checklist</span>
</div>
<h3 class="font-title-lg text-title-lg text-on-surface">Hoạt động Tasks</h3>
</div>
<span class="text-label-md font-label-md text-on-surface-variant">2 / 6 Hoàn thành</span>
</div>
<div class="flex flex-col gap-base">
<label class="group flex items-center gap-md p-md hover:bg-surface-container-low rounded-xl transition-all cursor-pointer">
<input checked="" class="w-5 h-5 rounded border-outline text-primary focus:ring-primary" type="checkbox"/>
<span class="font-body-md text-body-md text-on-surface-variant line-through">Sanitize Espresso Machine</span>
<span class="ml-auto text-xs text-on-surface-variant opacity-0 group-hover:opacity-100">8:15 AM</span>
</label>
<label class="group flex items-center gap-md p-md hover:bg-surface-container-low rounded-xl transition-all cursor-pointer">
<input checked="" class="w-5 h-5 rounded border-outline text-primary focus:ring-primary" type="checkbox"/>
<span class="font-body-md text-body-md text-on-surface-variant line-through">Check Delivery Queue</span>
<span class="ml-auto text-xs text-on-surface-variant opacity-0 group-hover:opacity-100">8:30 AM</span>
</label>
<label class="group flex items-center gap-md p-md hover:bg-surface-container-low rounded-xl transition-all cursor-pointer">
<input class="w-5 h-5 rounded border-outline text-primary focus:ring-primary" type="checkbox"/>
<span class="font-body-md text-body-md text-on-surface">Restock Oat Milk</span>
<span class="ml-auto text-xs text-primary font-bold">Priority</span>
</label>
<label class="group flex items-center gap-md p-md hover:bg-surface-container-low rounded-xl transition-all cursor-pointer">
<input class="w-5 h-5 rounded border-outline text-primary focus:ring-primary" type="checkbox"/>
<span class="font-body-md text-body-md text-on-surface">Calibrate Grinders</span>
<span class="ml-auto text-xs text-on-surface-variant">Next Slot</span>
</label>
<label class="group flex items-center gap-md p-md hover:bg-surface-container-low rounded-xl transition-all cursor-pointer">
<input class="w-5 h-5 rounded border-outline text-primary focus:ring-primary" type="checkbox"/>
<span class="font-body-md text-body-md text-on-surface">Clean Seating Area</span>
<span class="ml-auto text-xs text-on-surface-variant">Scheduled</span>
</label>
</div>
</div>
<!-- Performance & Announcements -->
<div class="md:col-span-5 flex flex-col gap-lg">
<!-- Personal Performance -->
<div class="glass-card rounded-2xl p-lg flex flex-col gap-md shadow-sm">
<div class="flex items-center gap-sm mb-base">
<div class="w-8 h-8 bg-primary-container/20 rounded-full flex items-center justify-center text-primary">
<span class="material-symbols-outlined text-sm">trending_up</span>
</div>
<h3 class="font-title-lg text-title-lg text-on-surface">Performance</h3>
</div>
<div class="grid grid-cols-2 gap-md">
<div class="flex flex-col gap-xs">
<span class="text-label-md font-label-md text-on-surface-variant">Đơn hàng Hoàn thành</span>
<div class="flex items-end gap-sm">
<span class="font-headline-md text-headline-md font-bold">142</span>
<span class="text-xs text-secondary flex items-center mb-1">
<span class="material-symbols-outlined text-xs">arrow_upward</span> 12%
                                </span>
</div>
<div class="w-full h-1 bg-surface-container rounded-full overflow-hidden">
<div class="h-full bg-primary w-[70%]"></div>
</div>
</div>
<div class="flex flex-col gap-xs">
<span class="text-label-md font-label-md text-on-surface-variant">Avg. Rating</span>
<div class="flex items-end gap-sm">
<span class="font-headline-md text-headline-md font-bold">4.9</span>
<span class="text-xs text-secondary flex items-center mb-1">
<span class="material-symbols-outlined text-xs">star</span> +0.2
                                </span>
</div>
<div class="w-full h-1 bg-surface-container rounded-full overflow-hidden">
<div class="h-full bg-secondary-container w-[95%]"></div>
</div>
</div>
</div>
</div>
<!-- Announcements Feed -->
<div class="glass-card rounded-2xl p-lg flex flex-col gap-md shadow-sm flex-1">
<div class="flex items-center gap-sm mb-base">
<div class="w-8 h-8 bg-tertiary-container/20 rounded-full flex items-center justify-center text-tertiary">
<span class="material-symbols-outlined text-sm">campaign</span>
</div>
<h3 class="font-title-lg text-title-lg text-on-surface">Store Updates</h3>
</div>
<div class="space-y-md">
<div class="p-sm bg-tertiary-fixed/10 border-l-4 border-tertiary rounded-r-lg">
<p class="font-label-md text-label-md text-on-tertiary-fixed mb-xs">Seasonal Launch</p>
<p class="text-body-md text-on-surface-variant">New seasonal syrup arriving tomorrow. Training session at 3:00 PM.</p>
</div>
<div class="p-sm bg-surface-container-low rounded-lg">
<p class="font-label-md text-label-md text-on-surface mb-xs">Maintenance Notice</p>
<p class="text-body-md text-on-surface-variant">Dishwasher in Back Room 2 is under repair. Please use station 1.</p>
</div>
<div class="p-sm bg-surface-container-low rounded-lg">
<p class="font-label-md text-label-md text-on-surface mb-xs">Schedule Update</p>
<p class="text-body-md text-on-surface-variant">Next week's schedule is now live on the portal.</p>
</div>
</div>
</div>
</div>
</div>
</main>
@endsection

@push('scripts')
<script>

        // Simple timer logic
        let hours = 3, minutes = 42, seconds = 15;
        const timerElement = document.getElementById('shift-timer');
        
        setInterval(() => {
            seconds++;
            if (seconds >= 60) {
                seconds = 0;
                minutes++;
            }
            if (minutes >= 60) {
                minutes = 0;
                hours++;
            }
            
            const h = hours.toString().padStart(2, '0');
            const m = minutes.toString().padStart(2, '0');
            const s = seconds.toString().padStart(2, '0');
            timerElement.textContent = `${h}:${m}:${s}`;
        }, 1000);

        // Micro-interactions for buttons
        document.querySelectorAll('button').forEach(btn => {
            btn.addEventListener('click', function() {
                this.classList.add('scale-95');
                setTimeout(() => this.classList.remove('scale-95'), 150);
            });
        });
    
</script>
@endpush
