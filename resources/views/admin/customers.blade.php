@extends('layouts.admin')

@section('title', 'Khách hàng')

@section('content')
<main class="ml-[280px] flex-1 h-screen flex flex-col bg-surface-container-lowest">
<!-- Top App Bar -->
<header class="h-16 flex items-center justify-between px-gutter bg-surface/80 backdrop-blur-md border-b border-outline-variant sticky top-0 z-40">
<div class="flex items-center gap-md bg-surface-container-low px-md py-xs rounded-full border border-outline-variant focus-within:ring-2 focus-within:ring-primary transition-all w-96">
<span class="material-symbols-outlined text-on-surface-variant">search</span>
<input class="bg-transparent border-none focus:ring-0 text-body-md w-full placeholder:text-outline" placeholder="Search customers, orders, or tier status..." type="text"/>
</div>
<div class="flex items-center gap-md">
<button class="w-10 h-10 flex items-center justify-center text-on-surface-variant hover:bg-surface-container-high rounded-full transition-colors relative">
<span class="material-symbols-outlined">notifications</span>
<span class="absolute top-2 right-2 w-2 h-2 bg-error rounded-full border-2 border-white"></span>
</button>
<button class="w-10 h-10 flex items-center justify-center text-on-surface-variant hover:bg-surface-container-high rounded-full transition-colors">
<span class="material-symbols-outlined">help</span>
</button>
<div class="h-8 w-[1px] bg-outline-variant mx-xs"></div>
<div class="flex items-center gap-sm cursor-pointer hover:bg-surface-container-high p-xs rounded-full transition-all">
<img class="w-8 h-8 rounded-full border border-outline-variant" data-alt="A professional headshot of a friendly female administrator with a clean business background. Her expression is welcoming and organized. The lighting is bright and clear, reflecting a modern corporate aesthetic with subtle organic tones." src="https://lh3.googleusercontent.com/aida-public/AB6AXuD0YJAWWyB6_3Cxc3MfpNAo_RZMQI3vilbh_rSIG2rwhPq_5GcPMwJBUZUGr5cfJwwyJMnnI0Lk5lYtbjGkWg9uAwhp6CP76IsVHJIX5xS2bxveo41n3ofRktcVP3hr1q9Mm4oTYOTtWXwdy7erPtS1t6d8JxGSIjsYpSfKOjBSu7P6AiDjccxXXFeyH5Q18zHoBl8zZJ9CiNRzqIr7w8I4MLxjD1PcSxTArLQW8SDV7TeHfoZvt_-AY8UJoLCx1OsIFeAQjVWO"/>
<div class="hidden lg:block text-left">
<p class="text-label-sm font-bold text-on-surface leading-tight">Admin User</p>
<p class="text-[10px] text-on-surface-variant">Manager</p>
</div>
</div>
</div>
</header>
<!-- Bảng điều khiển Canvas -->
<div class="flex-1 overflow-y-auto p-gutter space-y-lg">
<!-- Page Header & Quick Thao tác -->
<div class="flex flex-col md:flex-row md:items-end justify-between gap-md">
<div>
<h2 class="text-headline-lg font-headline-lg text-on-surface">Khách hàng Directory</h2>
<p class="text-body-lg text-on-surface-variant">Manage relationships, loyalty tiers, and purchasing behavior.</p>
</div>
<div class="flex items-center gap-sm">
<button class="flex items-center gap-xs px-md py-sm bg-surface border border-outline text-on-surface-variant rounded-xl hover:bg-surface-container transition-colors font-body-md">
<span class="material-symbols-outlined text-[18px]">ios_share</span>
                        Export
                    </button>
<button class="flex items-center gap-xs px-md py-sm bg-secondary-container text-on-secondary-container rounded-xl hover:shadow-md transition-all font-body-md">
<span class="material-symbols-outlined text-[18px]">mail</span>
                        Broadcast
                    </button>
<button class="flex items-center gap-xs px-md py-sm bg-primary text-on-primary rounded-xl hover:shadow-lg transition-all font-body-md">
<span class="material-symbols-outlined text-[18px]">person_add</span>
                        Add Khách hàng
                    </button>
</div>
</div>
<!-- Tổng quan Metrics (Bento Style) -->
<section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-md">
<div class="bg-surface border border-outline-variant p-md rounded-2xl shadow-sm hover:shadow-md transition-all group">
<div class="flex justify-between items-start mb-sm">
<div class="w-12 h-12 rounded-xl bg-primary-container/20 flex items-center justify-center text-primary">
<span class="material-symbols-outlined">group</span>
</div>
<span class="text-primary font-bold text-label-sm flex items-center gap-xs bg-primary/10 px-xs py-[2px] rounded-full">
<span class="material-symbols-outlined text-[12px]">trending_up</span> 12%
                        </span>
</div>
<p class="text-label-md text-on-surface-variant uppercase tracking-wider">Tổng cộng Khách hàng</p>
<h3 class="text-display-lg font-display-lg text-on-surface leading-tight mt-xs">12,842</h3>
</div>
<div class="bg-surface border border-outline-variant p-md rounded-2xl shadow-sm hover:shadow-md transition-all group">
<div class="flex justify-between items-start mb-sm">
<div class="w-12 h-12 rounded-xl bg-secondary-container/20 flex items-center justify-center text-secondary">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
</div>
<span class="text-secondary font-bold text-label-sm flex items-center gap-xs bg-secondary/10 px-xs py-[2px] rounded-full">
<span class="material-symbols-outlined text-[12px]">trending_up</span> 8.4%
                        </span>
</div>
<p class="text-label-md text-on-surface-variant uppercase tracking-wider">Hoạt động Loyalty</p>
<h3 class="text-display-lg font-display-lg text-on-surface leading-tight mt-xs">8,391</h3>
</div>
<div class="bg-surface border border-outline-variant p-md rounded-2xl shadow-sm hover:shadow-md transition-all group">
<div class="flex justify-between items-start mb-sm">
<div class="w-12 h-12 rounded-xl bg-tertiary-container/20 flex items-center justify-center text-tertiary">
<span class="material-symbols-outlined">payments</span>
</div>
<span class="text-error font-bold text-label-sm flex items-center gap-xs bg-error/10 px-xs py-[2px] rounded-full">
<span class="material-symbols-outlined text-[12px]">trending_down</span> 2.1%
                        </span>
</div>
<p class="text-label-md text-on-surface-variant uppercase tracking-wider">Avg. LTV</p>
<h3 class="text-display-lg font-display-lg text-on-surface leading-tight mt-xs">$242.50</h3>
</div>
<div class="bg-surface border border-outline-variant p-md rounded-2xl shadow-sm hover:shadow-md transition-all group">
<div class="flex justify-between items-start mb-sm">
<div class="w-12 h-12 rounded-xl bg-surface-container-high flex items-center justify-center text-primary">
<span class="material-symbols-outlined">person_add_alt</span>
</div>
<span class="text-primary font-bold text-label-sm flex items-center gap-xs bg-primary/10 px-xs py-[2px] rounded-full">
                            New
                        </span>
</div>
<p class="text-label-md text-on-surface-variant uppercase tracking-wider">New This Month</p>
<h3 class="text-display-lg font-display-lg text-on-surface leading-tight mt-xs">482</h3>
</div>
</section>
<!-- Table & Details Grid -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-lg h-full">
<!-- Main Table (Left Side) -->
<div class="xl:col-span-2 space-y-md">
<div class="bg-surface rounded-2xl border border-outline-variant shadow-sm overflow-hidden flex flex-col h-[600px]">
<div class="px-lg py-md border-b border-outline-variant flex items-center justify-between">
<h3 class="text-title-lg font-title-lg text-on-surface">Khách hàng Directory</h3>
<div class="flex items-center gap-sm">
<button class="p-xs text-on-surface-variant hover:bg-surface-container-low rounded-lg transition-colors">
<span class="material-symbols-outlined">filter_list</span>
</button>
<button class="p-xs text-on-surface-variant hover:bg-surface-container-low rounded-lg transition-colors">
<span class="material-symbols-outlined">sort</span>
</button>
</div>
</div>
<div class="flex-1 overflow-auto">
<table class="w-full text-left border-collapse">
<thead class="sticky top-0 bg-surface-container-low z-10">
<tr>
<th class="px-lg py-md text-label-sm text-on-surface-variant uppercase tracking-wider">Khách hàng</th>
<th class="px-lg py-md text-label-sm text-on-surface-variant uppercase tracking-wider">Tier</th>
<th class="px-lg py-md text-label-sm text-on-surface-variant uppercase tracking-wider text-center">Đơn hàng</th>
<th class="px-lg py-md text-label-sm text-on-surface-variant uppercase tracking-wider">Tổng cộng Spent</th>
<th class="px-lg py-md text-label-sm text-on-surface-variant uppercase tracking-wider">Last Hoạt động</th>
<th class="px-lg py-md"></th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant">
<!-- Row 1 -->
<tr class="hover:bg-surface-container-low transition-colors cursor-pointer group active-row">
<td class="px-lg py-md">
<div class="flex items-center gap-md">
<img class="w-10 h-10 rounded-full object-cover" data-alt="A portrait of a cheerful man in his early 30s with a neat beard and glasses. He is holding a reusable coffee cup in a brightly lit, organic cafe setting with green plants in the background. High-key lighting, soft focus, professional aesthetic." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCEYvzhk3v203RBkWom6AucCCzkgVngNEbbcTIFzUR7UONTeXjvkYh-5WbOq9DTLU_HGr1ry3uEl5QV88TOzd6jxhNBSQLlq8sdo6NWAydlbEQ1SSdeoW6GnkvlSN2sNtYaJEh9KbrNwQy-whRgKxRFo6yQd2ajkbOoionyyGs_eR4CNAZqcLEOYICsnS8K0YPW-BjlWyLTwmayNdS94zUvb2jzPjHSaOcbzXF_IdvOJ6nRcQi5z9whBt9YXH54tQuMGIzSoDPC"/>
<div>
<p class="text-body-md font-bold text-on-surface">Julian Rivers</p>
<p class="text-label-sm text-on-surface-variant">julian.r@email.com</p>
</div>
</div>
</td>
<td class="px-lg py-md">
<span class="px-sm py-1 bg-tertiary-container/20 text-tertiary-container font-bold text-[10px] rounded-full uppercase border border-tertiary-container/30">Gold</span>
</td>
<td class="px-lg py-md text-center text-body-md font-medium">42</td>
<td class="px-lg py-md text-body-md font-bold text-primary">$1,240.45</td>
<td class="px-lg py-md text-body-md text-on-surface-variant">2h ago</td>
<td class="px-lg py-md text-right">
<button class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors">chevron_right</button>
</td>
</tr>
<!-- Row 2 -->
<tr class="hover:bg-surface-container-low transition-colors cursor-pointer group">
<td class="px-lg py-md">
<div class="flex items-center gap-md">
<img class="w-10 h-10 rounded-full object-cover" data-alt="A portrait of a serene woman with braided hair wearing a beige linen shirt. She is sitting in a sunlit conservatory filled with organic textures and warm light. The image captures a clean, premium, and healthy lifestyle aesthetic." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBiB5IB4s6UoSAs4B0X16kpIlY1YzvaHWb4aL-4yAOJ0iPfDGvTVPWTPa7ptqOHvT5suEXhEU2Tw_WNzxMoM5zGuxHbKAHXIeKHLjjsdiCYAqoFVSBO8rN1QFCskN-6r7f0pzTfyWBdQ9zAmSX19etV55OrX3LO27x9kqGvINmBVi8X8CoyBAlyPWgT-BR3MYCOYFvVy3lecDn7vO-MunDuTNpw5hkInHstNS4CIZP083BsbSXoLum6nER60tOw31NNdalalhhj"/>
<div>
<p class="text-body-md font-bold text-on-surface">Sarah Jennings</p>
<p class="text-label-sm text-on-surface-variant">s.jennings@web.com</p>
</div>
</div>
</td>
<td class="px-lg py-md">
<span class="px-sm py-1 bg-outline-variant/30 text-outline font-bold text-[10px] rounded-full uppercase border border-outline-variant">Silver</span>
</td>
<td class="px-lg py-md text-center text-body-md font-medium">18</td>
<td class="px-lg py-md text-body-md font-bold text-primary">$532.10</td>
<td class="px-lg py-md text-body-md text-on-surface-variant">Yesterday</td>
<td class="px-lg py-md text-right">
<button class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors">chevron_right</button>
</td>
</tr>
<!-- Row 3 -->
<tr class="hover:bg-surface-container-low transition-colors cursor-pointer group">
<td class="px-lg py-md">
<div class="flex items-center gap-md">
<div class="w-10 h-10 rounded-full bg-secondary-container flex items-center justify-center text-on-secondary-container font-bold">MK</div>
<div>
<p class="text-body-md font-bold text-on-surface">Marcus Knight</p>
<p class="text-label-sm text-on-surface-variant">mknight@mail.org</p>
</div>
</div>
</td>
<td class="px-lg py-md">
<span class="px-sm py-1 bg-secondary-container/20 text-secondary font-bold text-[10px] rounded-full uppercase border border-secondary/30">Bronze</span>
</td>
<td class="px-lg py-md text-center text-body-md font-medium">5</td>
<td class="px-lg py-md text-body-md font-bold text-primary">$112.50</td>
<td class="px-lg py-md text-body-md text-on-surface-variant">3 days ago</td>
<td class="px-lg py-md text-right">
<button class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors">chevron_right</button>
</td>
</tr>
<!-- Row 4 -->
<tr class="hover:bg-surface-container-low transition-colors cursor-pointer group">
<td class="px-lg py-md">
<div class="flex items-center gap-md">
<img class="w-10 h-10 rounded-full object-cover" data-alt="A portrait of an elderly gentleman with a kind expression and silver hair, wearing a high-quality green sweater. He is in a clean, modern architecture setting with wood accents and lots of natural light. High-end lifestyle photography." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAuEKx1rPOUiSnyzTPKO0SiLiVN8it1_sY9WfumObGipGmEK2zQIbziKHSZ_BkUr2WoYWeP2_LIanLqR4EMCeGfMQG0Fw-INNtYi4Blf2xK64S-IUiu8FZqxq0Uatz9ihj7GbkNZaC-DFgWcfanDmLXs37PeSSJCyHyjYXIE2dZIFkYG5Cg3E_5FsjBvYVXWv8rYjw4xS1R5EYeVrpcaOyT3pzzaxgkwiZDhHAsNUYGu-0vn73NCXYrdww5oeiwdiJo3gI5sH71"/>
<div>
<p class="text-body-md font-bold text-on-surface">Robert Chen</p>
<p class="text-label-sm text-on-surface-variant">robert.chen@p-mail.com</p>
</div>
</div>
</td>
<td class="px-lg py-md">
<span class="px-sm py-1 bg-tertiary-container/20 text-tertiary-container font-bold text-[10px] rounded-full uppercase border border-tertiary-container/30">Gold</span>
</td>
<td class="px-lg py-md text-center text-body-md font-medium">84</td>
<td class="px-lg py-md text-body-md font-bold text-primary">$2,890.00</td>
<td class="px-lg py-md text-body-md text-on-surface-variant">12h ago</td>
<td class="px-lg py-md text-right">
<button class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors">chevron_right</button>
</td>
</tr>
</tbody>
</table>
</div>
<div class="px-lg py-sm bg-surface-container-low flex justify-between items-center text-label-sm">
<span class="text-on-surface-variant">Showing 4 of 12,842 customers</span>
<div class="flex gap-xs">
<button class="px-sm py-1 border border-outline-variant rounded-md hover:bg-surface transition-colors disabled:opacity-50">Prev</button>
<button class="px-sm py-1 bg-primary text-on-primary rounded-md shadow-sm">Next</button>
</div>
</div>
</div>
</div>
<!-- Right Side Details/Insights -->
<div class="xl:col-span-1 space-y-lg">
<!-- Quick Khách hàng View Sidebar Placeholder -->
<div class="bg-surface rounded-2xl border border-outline-variant shadow-sm p-lg relative overflow-hidden">
<div class="absolute top-0 right-0 p-md">
<button class="material-symbols-outlined text-on-surface-variant hover:text-primary">edit</button>
</div>
<div class="flex flex-col items-center text-center space-y-sm mb-lg">
<div class="relative">
<img class="w-24 h-24 rounded-full border-4 border-surface shadow-lg" data-alt="A portrait of a cheerful man in his early 30s with a neat beard and glasses. He is holding a reusable coffee cup in a brightly lit, organic cafe setting with green plants in the background. High-key lighting, soft focus, professional aesthetic." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDLoFfLKl8IMtj1HzgIb_L1CQYafiuqdqPd2Mp6CEVTuM2w9TPnBrCwdJXBYKCDlrqos07ZJg71FPqW4UeEe9-bUA83T2QJ2GKUDMu3JdnzigaHL_EgtxPZPJcGExkcv3_JfF3W7pWnEqEgI5HqNmPe4HeW1NicnuEHO52HcHk2vKdrcvZDWJ5qrWtwbG5F42Vs7jG6dymOOMPW1YiBqFNQDNVrVvSDLUeIQN1VG6F6ru81zPfavhnyAdCgReqkomdUUwvU456v"/>
<div class="absolute bottom-1 right-1 w-6 h-6 bg-tertiary rounded-full border-2 border-surface flex items-center justify-center">
<span class="material-symbols-outlined text-white text-[14px]" style="font-variation-settings: 'FILL' 1;">star</span>
</div>
</div>
<div>
<h3 class="text-title-lg font-title-lg text-on-surface">Julian Rivers</h3>
<p class="text-body-md text-on-surface-variant">Thành viên từ Jan 2023</p>
</div>
<div class="flex gap-xs">
<span class="bg-primary/10 text-primary px-sm py-1 rounded-full text-label-sm font-bold">Top 5% Spender</span>
<span class="bg-secondary/10 text-secondary px-sm py-1 rounded-full text-label-sm font-bold">Health Conscious</span>
</div>
</div>
<div class="space-y-md">
<h4 class="text-label-sm font-bold uppercase tracking-widest text-on-surface-variant">Recent Activity</h4>
<div class="space-y-sm">
<div class="flex items-start gap-sm">
<div class="w-8 h-8 rounded-full bg-surface-container flex items-center justify-center text-primary">
<span class="material-symbols-outlined text-[16px]">local_cafe</span>
</div>
<div class="flex-1">
<p class="text-body-md font-medium text-on-surface">Ordered 'Organic Matcha Latte'</p>
<p class="text-label-sm text-on-surface-variant">2 hours ago • $6.50</p>
</div>
</div>
<div class="flex items-start gap-sm">
<div class="w-8 h-8 rounded-full bg-surface-container flex items-center justify-center text-tertiary">
<span class="material-symbols-outlined text-[16px]">redeem</span>
</div>
<div class="flex-1">
<p class="text-body-md font-medium text-on-surface">Redeemed 'Free Pastry' reward</p>
<p class="text-label-sm text-on-surface-variant">Yesterday • 500 pts</p>
</div>
</div>
<div class="flex items-start gap-sm">
<div class="w-8 h-8 rounded-full bg-surface-container flex items-center justify-center text-primary">
<span class="material-symbols-outlined text-[16px]">shopping_basket</span>
</div>
<div class="flex-1">
<p class="text-body-md font-medium text-on-surface">Ordered 'Acai Smoothie Bowl'</p>
<p class="text-label-sm text-on-surface-variant">3 days ago • $12.00</p>
</div>
</div>
</div>
<button class="w-full py-sm bg-surface-container hover:bg-surface-container-high rounded-xl text-primary font-bold text-body-md transition-all">View Full Profile</button>
</div>
</div>
<!-- Engagement Tiers Visual -->
<div class="bg-surface rounded-2xl border border-outline-variant shadow-sm p-lg">
<h4 class="text-title-lg font-title-lg text-on-surface mb-md">Tier Distribution</h4>
<div class="space-y-md">
<div class="space-y-xs">
<div class="flex justify-between text-label-sm font-bold">
<span class="text-tertiary-container">Gold (Top Tier)</span>
<span class="text-on-surface">15%</span>
</div>
<div class="w-full bg-surface-container rounded-full h-2">
<div class="bg-tertiary-container h-2 rounded-full" style="width: 15%"></div>
</div>
</div>
<div class="space-y-xs">
<div class="flex justify-between text-label-sm font-bold">
<span class="text-outline">Silver (Standard)</span>
<span class="text-on-surface">35%</span>
</div>
<div class="w-full bg-surface-container rounded-full h-2">
<div class="bg-outline h-2 rounded-full" style="width: 35%"></div>
</div>
</div>
<div class="space-y-xs">
<div class="flex justify-between text-label-sm font-bold">
<span class="text-secondary">Bronze (Entry)</span>
<span class="text-on-surface">50%</span>
</div>
<div class="w-full bg-surface-container rounded-full h-2">
<div class="bg-secondary h-2 rounded-full" style="width: 50%"></div>
</div>
</div>
</div>
<div class="mt-lg pt-lg border-t border-outline-variant">
<div class="flex items-center gap-sm">
<div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary">
<span class="material-symbols-outlined">campaign</span>
</div>
<div>
<p class="text-body-md font-bold text-on-surface">Opportunity Detected</p>
<p class="text-label-sm text-on-surface-variant">1,240 Silver users are close to Gold status.</p>
</div>
</div>
<button class="w-full mt-md py-sm border-2 border-primary/20 text-primary font-bold rounded-xl hover:bg-primary/5 transition-all text-body-md">Create Promotion</button>
</div>
</div>
</div>
</div>
</div>
</main>
@endsection

@push('scripts')
<script>

        // Micro-interaction for table rows
        document.querySelectorAll('tbody tr').forEach(row => {
            row.addEventListener('click', () => {
                document.querySelectorAll('tbody tr').forEach(r => r.classList.remove('bg-surface-container-low', 'border-l-4', 'border-primary'));
                row.classList.add('bg-surface-container-low', 'border-l-4', 'border-primary');
            });
        });

        // Search bar focus effect
        const searchInput = document.querySelector('input[type="text"]');
        searchInput.addEventListener('focus', () => {
            searchInput.parentElement.classList.add('shadow-md');
        });
        searchInput.addEventListener('blur', () => {
            searchInput.parentElement.classList.remove('shadow-md');
        });
    
</script>
@endpush
