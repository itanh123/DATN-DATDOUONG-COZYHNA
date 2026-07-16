@extends('layouts.admin')

@section('title', 'Nhân viên')

@section('content')
<main class="ml-[280px] pt-16 min-h-screen p-8 bg-background">
<div class="max-w-[1400px] mx-auto space-y-8">
<!-- Staff Tổng quan Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
<div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant shadow-sm hover:shadow-md transition-shadow">
<div class="flex items-center justify-between mb-4">
<div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
<span class="material-symbols-outlined">groups</span>
</div>
<span class="text-secondary font-label-md flex items-center gap-1">
<span class="material-symbols-outlined text-[16px]">trending_up</span> +2
                        </span>
</div>
<p class="text-on-surface-variant font-label-md">Tổng cộng Nhân viên</p>
<h3 class="text-headline-lg font-headline-lg text-on-surface">24</h3>
</div>
<div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant shadow-sm">
<div class="flex items-center justify-between mb-4">
<div class="w-12 h-12 rounded-lg bg-secondary/10 flex items-center justify-center text-secondary">
<span class="material-symbols-outlined">timer</span>
</div>
<div class="flex h-2 w-2 rounded-full bg-primary animate-pulse"></div>
</div>
<p class="text-on-surface-variant font-label-md">On Duty Now</p>
<h3 class="text-headline-lg font-headline-lg text-on-surface">8</h3>
</div>
<div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant shadow-sm">
<div class="flex items-center justify-between mb-4">
<div class="w-12 h-12 rounded-lg bg-tertiary-fixed-dim/10 flex items-center justify-center text-tertiary">
<span class="material-symbols-outlined">star</span>
</div>
</div>
<p class="text-on-surface-variant font-label-md">Avg Performance</p>
<h3 class="text-headline-lg font-headline-lg text-on-surface">4.8<span class="text-title-lg text-on-surface-variant">/5.0</span></h3>
</div>
<div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant shadow-sm">
<div class="flex items-center justify-between mb-4">
<div class="w-12 h-12 rounded-lg bg-primary-container/20 flex items-center justify-center text-primary">
<span class="material-symbols-outlined">calendar_today</span>
</div>
</div>
<p class="text-on-surface-variant font-label-md">Upcoming Today</p>
<h3 class="text-headline-lg font-headline-lg text-on-surface">12</h3>
</div>
</div>
<!-- Bảng điều khiển Layout: Table + Sidebar -->
<div class="flex flex-col lg:flex-row gap-8">
<!-- Staff Directory Table Container -->
<div class="flex-1 bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-sm overflow-hidden">
<div class="p-6 border-b border-outline-variant flex justify-between items-center">
<div>
<h4 class="font-title-lg text-title-lg text-on-surface">Staff Directory</h4>
<p class="font-label-md text-on-surface-variant">Manage and track your beverage service team</p>
</div>
<button class="flex items-center gap-2 px-4 py-2 bg-surface-container-low border border-outline-variant rounded-lg font-label-md text-on-surface hover:bg-surface-container-high transition-colors">
<span class="material-symbols-outlined text-[18px]">filter_list</span> Filter
                        </button>
</div>
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-surface-container-low/50">
<th class="px-6 py-4 font-label-sm text-on-surface-variant uppercase tracking-wider">Employee</th>
<th class="px-6 py-4 font-label-sm text-on-surface-variant uppercase tracking-wider">Role</th>
<th class="px-6 py-4 font-label-sm text-on-surface-variant uppercase tracking-wider">Trạng thái</th>
<th class="px-6 py-4 font-label-sm text-on-surface-variant uppercase tracking-wider">Performance</th>
<th class="px-6 py-4 font-label-sm text-on-surface-variant uppercase tracking-wider">Shift</th>
<th class="px-6 py-4 font-label-sm text-on-surface-variant uppercase tracking-wider text-right">Thao tác</th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant">
<!-- Row 1 -->
<tr class="hover:bg-surface-container-low/30 transition-colors group">
<td class="px-6 py-4">
<div class="flex items-center gap-3">
<img class="w-10 h-10 rounded-full object-cover" data-alt="A portrait of a cheerful female barista with a warm smile, wearing a green apron and a black t-shirt in a modern, light-filled cafe setting. High-quality lighting highlights the texture of the organic coffee environment. The overall feel is premium, professional, and aligned with a health-conscious brand identity." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBMtdwqXLxXVBl1qy9K2Gs6DtPgLhDNocaBFYxy384uTiNtC3TLBdFRVd8RWkD2WDBkA4SXub2rAT70rDXVUbFdxQjTPAuv7MHyN8nfMe2MvC1teRkMKUmM_UoOfasV8LLS0MN3lGRGjONLmW2cLC0XZBykdbo3dALiMjWJaxY80djtZObtuhYxtCyhFGnR9IIgpGKqpKhjl6MhtV-XNyruy856KMH1depIBm_3p6ipJq7wfpPaMd5CJVixB-o9XRJMV0bjOy55"/>
<div>
<p class="font-body-md text-on-surface font-semibold">Sarah Jenkins</p>
<p class="text-label-md text-on-surface-variant">sarah.j@cozyhna.com</p>
</div>
</div>
</td>
<td class="px-6 py-4 text-body-md text-on-surface">Lead Barista</td>
<td class="px-6 py-4">
<span class="px-3 py-1 rounded-full bg-primary/10 text-primary text-[11px] font-bold uppercase tracking-wider">On Shift</span>
</td>
<td class="px-6 py-4">
<div class="flex items-center gap-1">
<span class="material-symbols-outlined text-[18px] text-tertiary" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="font-body-md font-bold text-on-surface">4.9</span>
</div>
</td>
<td class="px-6 py-4 text-body-md text-on-surface-variant">07:00 - 15:00</td>
<td class="px-6 py-4 text-right">
<button class="p-2 rounded-full hover:bg-surface-container-high text-on-surface-variant opacity-0 group-hover:opacity-100 transition-all">
<span class="material-symbols-outlined">more_vert</span>
</button>
</td>
</tr>
<!-- Row 2 -->
<tr class="hover:bg-surface-container-low/30 transition-colors group">
<td class="px-6 py-4">
<div class="flex items-center gap-3">
<img class="w-10 h-10 rounded-full object-cover" data-alt="A portrait of a focused male kitchen manager in a pristine, modern stainless steel kitchen. He is wearing a minimalist grey chef's coat and has a professional, calm expression. The lighting is soft and bright, emphasizing a clean and hygienic environment. Premium aesthetic with a focus on operational excellence and organic materials." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAHLoddOKh3oILQyGZXAujkGS0rvt0-90jALEkn2Ic3zMs-jKy8tJ_lrmG9TIwDg46EPQNT7HKX3xc4KGmGLaHCeVjxCOXmIXAe3pIWrr-vv0ErjlfkH_Z_mdxLG2bq5x70aX1krqIa3VVyCgMS22DwEVCMFFUKGQLEqfY2MlogV-R4jSSX7ovMSblnW_uemEDMdF7i6HE4mnR5JS0Sq2-JWzDgf_2-XxwccAeC-HMzS7rzbTQ4uCOWkXsoRFgG8nYklMrzz_8T"/>
<div>
<p class="font-body-md text-on-surface font-semibold">Mark Thompson</p>
<p class="text-label-md text-on-surface-variant">mark.t@cozyhna.com</p>
</div>
</div>
</td>
<td class="px-6 py-4 text-body-md text-on-surface">Kitchen Manager</td>
<td class="px-6 py-4">
<span class="px-3 py-1 rounded-full bg-outline-variant/30 text-on-surface-variant text-[11px] font-bold uppercase tracking-wider">Off Duty</span>
</td>
<td class="px-6 py-4">
<div class="flex items-center gap-1">
<span class="material-symbols-outlined text-[18px] text-tertiary" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="font-body-md font-bold text-on-surface">4.7</span>
</div>
</td>
<td class="px-6 py-4 text-body-md text-on-surface-variant">10:00 - 18:00</td>
<td class="px-6 py-4 text-right">
<button class="p-2 rounded-full hover:bg-surface-container-high text-on-surface-variant opacity-0 group-hover:opacity-100 transition-all">
<span class="material-symbols-outlined">more_vert</span>
</button>
</td>
</tr>
<!-- Row 3 -->
<tr class="hover:bg-surface-container-low/30 transition-colors group">
<td class="px-6 py-4">
<div class="flex items-center gap-3">
<img class="w-10 h-10 rounded-full object-cover" data-alt="A portrait of an energetic young delivery rider in a premium green branded uniform jacket, holding a modern delivery bag. He is outdoors in a bright, clean city environment with lush greenery in the background. The lighting is natural and optimistic. Phútimalist flat style photography reflecting speed, health, and reliability." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBxxNzaTdaUt4qzDH_yAV9BJmPUdphQ-fHmBxxQWs0v3S7LE2wIrFWTXmwNPiDOWw7WgWwzmocopTcUa_4KwSXChHJrmS4MxkYFLHmMorhlV9DUq_tEDaI_4qH-KpY3SwzWG6CcMths-RpharY5yAwfhqDXR8YZEG4jEZEflOT2Xm1YKjhOiv_tLoK1sc8zqmsp1MFFQUrKpw57pwfvf8J4cXh-6IAU57uraNW6kXBT_hkj2lTQGmqA9BZ8guqaRf9H9A3U10gc"/>
<div>
<p class="font-body-md text-on-surface font-semibold">David Chen</p>
<p class="text-label-md text-on-surface-variant">david.c@cozyhna.com</p>
</div>
</div>
</td>
<td class="px-6 py-4 text-body-md text-on-surface">Delivery Associate</td>
<td class="px-6 py-4">
<span class="px-3 py-1 rounded-full bg-tertiary-container/20 text-tertiary text-[11px] font-bold uppercase tracking-wider">On Leave</span>
</td>
<td class="px-6 py-4">
<div class="flex items-center gap-1">
<span class="material-symbols-outlined text-[18px] text-tertiary" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="font-body-md font-bold text-on-surface">4.5</span>
</div>
</td>
<td class="px-6 py-4 text-body-md text-on-surface-variant">N/A</td>
<td class="px-6 py-4 text-right">
<button class="p-2 rounded-full hover:bg-surface-container-high text-on-surface-variant opacity-0 group-hover:opacity-100 transition-all">
<span class="material-symbols-outlined">more_vert</span>
</button>
</td>
</tr>
<!-- Row 4 -->
<tr class="hover:bg-surface-container-low/30 transition-colors group">
<td class="px-6 py-4">
<div class="flex items-center gap-3">
<img class="w-10 h-10 rounded-full object-cover" data-alt="A professional portrait of a female barista with a creative and friendly vibe, wearing a neat apron and a headband. She is standing in a premium coffee studio with natural oak textures and soft greenery. Bright, airy lighting creates a modern, high-end light-mode aesthetic. Focus on fresh, approachable professionalism." src="https://lh3.googleusercontent.com/aida-public/AB6AXuC0SetmvVN2JRTfPpKyeVvFNasstq0XxwMoUb3e7--U6JF0nEKB0ZNbQ4oTUOebpHxx4qgzS91_quMVt1Jynd8sEVCdJXGjTvXxPCQL-0OOvfG-TdodX87dfaox1IunS2UUoNu23k-2K43pMIpbP-IJcCcVmZIsgklOYWT_S_itkJY92Abii05yNCLSz1rMG33rGpYs8KrVwo4HcnSGU_MnRP-xlW7NoDIyxmCuPpiMnibcQWt_j6KVER7I5BIjO87zpW5ogZAI"/>
<div>
<p class="font-body-md text-on-surface font-semibold">Elena Rodriguez</p>
<p class="text-label-md text-on-surface-variant">elena.r@cozyhna.com</p>
</div>
</div>
</td>
<td class="px-6 py-4 text-body-md text-on-surface">Junior Barista</td>
<td class="px-6 py-4">
<span class="px-3 py-1 rounded-full bg-primary/10 text-primary text-[11px] font-bold uppercase tracking-wider">On Shift</span>
</td>
<td class="px-6 py-4">
<div class="flex items-center gap-1">
<span class="material-symbols-outlined text-[18px] text-tertiary" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="font-body-md font-bold text-on-surface">4.8</span>
</div>
</td>
<td class="px-6 py-4 text-body-md text-on-surface-variant">08:00 - 16:00</td>
<td class="px-6 py-4 text-right">
<button class="p-2 rounded-full hover:bg-surface-container-high text-on-surface-variant opacity-0 group-hover:opacity-100 transition-all">
<span class="material-symbols-outlined">more_vert</span>
</button>
</td>
</tr>
</tbody>
</table>
</div>
<div class="p-6 border-t border-outline-variant flex justify-between items-center bg-surface-container-low/20">
<p class="text-label-md text-on-surface-variant">Showing 1-10 of 24 staff members</p>
<div class="flex gap-2">
<button class="px-3 py-1 border border-outline-variant rounded-md hover:bg-surface-container-high transition-colors text-on-surface-variant">Previous</button>
<button class="px-3 py-1 bg-primary text-on-primary rounded-md shadow-sm">1</button>
<button class="px-3 py-1 border border-outline-variant rounded-md hover:bg-surface-container-high transition-colors text-on-surface-variant">2</button>
<button class="px-3 py-1 border border-outline-variant rounded-md hover:bg-surface-container-high transition-colors text-on-surface-variant">Next</button>
</div>
</div>
</div>
<!-- Management Sidebar -->
<div class="w-full lg:w-[320px] space-y-6">
<!-- Quick Thao tác -->
<div class="bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant shadow-sm">
<h4 class="font-title-lg text-title-lg text-on-surface mb-6">Quick Thao tác</h4>
<div class="space-y-3">
<button class="w-full flex items-center justify-between p-4 bg-primary text-on-primary rounded-xl hover:bg-primary/90 transition-all active:scale-[0.98] shadow-sm">
<span class="font-label-md">Thêm Mới Staff</span>
<span class="material-symbols-outlined">person_add</span>
</button>
<button class="w-full flex items-center justify-between p-4 bg-surface-container-low text-on-surface rounded-xl border border-outline-variant hover:bg-surface-container-high transition-all active:scale-[0.98]">
<span class="font-label-md">Schedule Shift</span>
<span class="material-symbols-outlined">schedule</span>
</button>
<button class="w-full flex items-center justify-between p-4 bg-surface-container-low text-on-surface rounded-xl border border-outline-variant hover:bg-surface-container-high transition-all active:scale-[0.98]">
<span class="font-label-md">Export Payroll</span>
<span class="material-symbols-outlined">payments</span>
</button>
</div>
</div>
<!-- Today's Schedule Phúti-view -->
<div class="bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant shadow-sm relative overflow-hidden">
<div class="absolute top-0 right-0 w-24 h-24 bg-primary/5 rounded-full -mr-8 -mt-8"></div>
<h4 class="font-title-lg text-title-lg text-on-surface mb-6">Coming Up Next</h4>
<div class="space-y-6 relative">
<!-- Arrival 1 -->
<div class="flex gap-4">
<div class="flex flex-col items-center">
<div class="w-2.5 h-2.5 rounded-full bg-primary ring-4 ring-primary/10"></div>
<div class="w-0.5 flex-1 bg-outline-variant my-1"></div>
</div>
<div class="pb-4">
<p class="font-label-sm text-primary uppercase">12:30 PM</p>
<p class="font-body-md text-on-surface font-semibold">James Wilson</p>
<p class="text-label-md text-on-surface-variant">Barista Shift (Late)</p>
</div>
</div>
<!-- Arrival 2 -->
<div class="flex gap-4">
<div class="flex flex-col items-center">
<div class="w-2.5 h-2.5 rounded-full bg-outline-variant"></div>
<div class="w-0.5 flex-1 bg-outline-variant my-1"></div>
</div>
<div class="pb-4">
<p class="font-label-sm text-on-surface-variant uppercase">02:00 PM</p>
<p class="font-body-md text-on-surface font-semibold">Sophia L.</p>
<p class="text-label-md text-on-surface-variant">Kitchen Prep</p>
</div>
</div>
<!-- Arrival 3 -->
<div class="flex gap-4">
<div class="flex flex-col items-center">
<div class="w-2.5 h-2.5 rounded-full bg-outline-variant"></div>
</div>
<div>
<p class="font-label-sm text-on-surface-variant uppercase">04:30 PM</p>
<p class="font-body-md text-on-surface font-semibold">Ryan Park</p>
<p class="text-label-md text-on-surface-variant">Delivery Night Team</p>
</div>
</div>
</div>
<button class="w-full mt-6 py-2 text-primary font-label-md hover:underline">View Full Schedule</button>
</div>
<!-- Performance Insight Card -->
<div class="glass-card p-6 rounded-2xl border-primary/20 shadow-lg relative">
<div class="absolute inset-0 bg-primary/5 -z-10"></div>
<h4 class="font-title-lg text-title-lg text-primary mb-2">Team Insight</h4>
<p class="text-body-md text-on-primary-container leading-relaxed">
                            Team productivity is up <span class="font-bold">14%</span> this week. Sarah Jenkins has maintained a perfect 5.0 rating for 12 consecutive shifts.
                        </p>
<div class="mt-4 flex -space-x-2">
<img alt="User avatar" class="w-8 h-8 rounded-full border-2 border-surface-container-lowest" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBe5QFnnKWtTCxfeiRZG4toz82SvH_DEcVlT8fnyAAIdxNiKib2KrVDA2czloYYKTCzMkRFTXtr9deGnpqezCdXqNxbl1zjLebSMLp_dcsiEMNKhbMb9dSdoeWupbqbx9eiFFXsgbHdCujZ2_U6bhQauHNhIxvcyDOlEoUMHdb5nkRSPUqQOPz4rfCmJj-kIW9TDzX30przToZyTw2SxMx5vbTjBcRuyrjPeTpA38NettRJ_t88ZaMttyKeNJGjsNGjCddHZB36"/>
<img alt="User avatar" class="w-8 h-8 rounded-full border-2 border-surface-container-lowest" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBO3Xtqjo7yI7O_1h7y0-RXbh2mnrBrOLOhEx3uCtZyCLaKMWeI3bi3GcuYrEOxQGsd6q--NFNasshuAFvH9Z1_2lX4dlZEqnybfTe3Tlwi_VrTVjojpsDw1gbBGux-_nbPhREsmvdMo_LkgwQN0Ffboc0hn4-J6esuvFLziYNbH1_dP7-0thLRLnBHPUxqK9cJbaT1sbjE6suijaRtxj1Vbj0IPfZReMhqizQQtLuC9itHhHajAWJAPVAto962sAAPlNLuEsyX"/>
<img alt="User avatar" class="w-8 h-8 rounded-full border-2 border-surface-container-lowest" src="https://lh3.googleusercontent.com/aida-public/AB6AXuALUBR_Zzgl5dG7bgeKd3azTzlQjFM_zLZUBbbN0HstHSv6d9RSI469B7Njfi0O_2mQkDlOVj8seyR9rJmLOB_YdITwUhROhMv-L77eqCYrgpTdmKyPduXXgZgyFvylsOZXKcNi12u0H2SK6nmnHtX39nMJTCrgrtQIoL6MgBWWbhbzIEQjb3Z9rXz64uSYg2zm8DtzSD1yWSKunoSR5kvlO490CJmPA-sKmAgo441Sw6AWGSo2Rhd6utcSVTwCjyQGDa5tBF05"/>
<div class="w-8 h-8 rounded-full bg-primary/20 flex items-center justify-center text-[10px] font-bold text-primary border-2 border-surface-container-lowest">+18</div>
</div>
</div>
</div>
</div>
</div>
</main>
@endsection

@push('scripts')
<script>

        // Simple search interactivity
        const searchInput = document.querySelector('input[type="text"]');
        searchInput.addEventListener('focus', () => {
            searchInput.parentElement.classList.add('ring-2', 'ring-primary');
        });
        searchInput.addEventListener('blur', () => {
            searchInput.parentElement.classList.remove('ring-2', 'ring-primary');
        });

        // Hover effects on cards
        document.querySelectorAll('.bg-surface-container-lowest').forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-2px)';
                card.style.transition = 'transform 0.2s ease-out';
            });
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0px)';
            });
        });
    
</script>
@endpush
