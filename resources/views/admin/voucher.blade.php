@extends('layouts.admin')

@section('title', 'Quản lý Voucher')

@section('content')
<main class="ml-0 md:ml-[280px] pt-16 min-h-screen p-lg bg-surface">
    <div class="max-w-container-max mx-auto space-y-lg">
        <!-- Header -->
        <div class="flex justify-between items-center border-b border-outline-variant/30 pb-4">
            <div>
                <h2 class="font-headline-md text-headline-md text-on-surface">Quản lý Voucher</h2>
                <p class="font-body-md text-on-surface-variant">Quản lý các chương trình khuyến mãi và mã giảm giá</p>
            </div>
            <div class="flex gap-4 items-center">
                @if(session('success'))
                    <div class="bg-primary-container text-on-primary-container px-4 py-2 rounded-lg text-label-md">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="relative hidden lg:block">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                    <input class="pl-10 pr-4 py-2 bg-surface-container-low border-none rounded-full w-64 text-body-md focus:ring-2 focus:ring-primary focus:bg-white transition-all" placeholder="Search promo codes..." type="text" />
                </div>
            </div>
        </div>
            <!-- Summary Stats -->
            <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-lg mb-lg">
                <!-- Stat Card 1 -->
                <div class="bg-surface-container-lowest p-lg rounded-xl border border-outline-variant/30 shadow-sm hover:shadow-md transition-all group">
                    <div class="flex justify-between items-start mb-md">
                        <div class="p-2 bg-primary-container/10 rounded-lg">
                            <span class="material-symbols-outlined text-primary">confirmation_number</span>
                        </div>
                    </div>
                    <h3 class="text-on-surface-variant font-label-md">Tổng số Voucher</h3>
                    <p class="font-headline-lg text-headline-lg mt-xs">{{ number_format($totalVouchers) }}</p>
                </div>
                <!-- Stat Card 2 -->
                <div class="bg-surface-container-lowest p-lg rounded-xl border border-outline-variant/30 shadow-sm hover:shadow-md transition-all group">
                    <div class="flex justify-between items-start mb-md">
                        <div class="p-2 bg-secondary-container/20 rounded-lg">
                            <span class="material-symbols-outlined text-secondary">campaign</span>
                        </div>
                    </div>
                    <h3 class="text-on-surface-variant font-label-md">Đang hoạt động</h3>
                    <p class="font-headline-lg text-headline-lg mt-xs">{{ number_format($activeVouchers) }}</p>
                </div>
                <!-- Stat Card 3 -->
                <div class="bg-surface-container-lowest p-lg rounded-xl border border-outline-variant/30 shadow-sm hover:shadow-md transition-all group">
                    <div class="flex justify-between items-start mb-md">
                        <div class="p-2 bg-tertiary-container/10 rounded-lg">
                            <span class="material-symbols-outlined text-tertiary">check_circle</span>
                        </div>
                    </div>
                    <h3 class="text-on-surface-variant font-label-md">Đã sử dụng</h3>
                    <p class="font-headline-lg text-headline-lg mt-xs">{{ number_format($totalRedeemed) }}</p>
                </div>
                <!-- Stat Card 4 -->
                <div class="bg-surface-container-lowest p-lg rounded-xl border border-outline-variant/30 shadow-sm hover:shadow-md transition-all group">
                    <div class="flex justify-between items-start mb-md">
                        <div class="p-2 bg-primary-container/20 rounded-lg">
                            <span class="material-symbols-outlined text-primary">payments</span>
                        </div>
                    </div>
                    <h3 class="text-on-surface-variant font-label-md">Lượt tiếp cận</h3>
                    <p class="font-headline-lg text-headline-lg mt-xs">{{ number_format($revenueImpact) }}</p>
                </div>
            </section>

            <!-- Table Actions -->
            <section class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-md mb-lg">
                <div class="flex items-center gap-md">
                    <h3 class="font-title-lg text-title-lg text-on-surface">Danh sách Voucher</h3>
                    <span class="px-2 py-0.5 bg-surface-container-high text-on-surface-variant rounded text-label-sm">{{ $totalVouchers }} Kết quả</span>
                </div>
                <div class="flex items-center gap-sm w-full sm:w-auto">
                    <a href="/admin/voucher/add" class="bg-primary text-on-primary px-lg py-sm rounded-xl font-title-lg flex items-center gap-sm active:scale-95 transition-all shadow-lg hover:shadow-primary/20">
                        <span class="material-symbols-outlined">add_circle</span>
                        Tạo Voucher Mới
                    </a>
                </div>
            </section>
            <!-- Vouchers Table -->
            <section
                class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-container-low/50">
                                <th
                                    class="px-lg py-md text-label-sm text-on-surface-variant uppercase tracking-wider font-semibold border-b border-outline-variant/30">
                                    Voucher Name</th>
                                <th
                                    class="px-lg py-md text-label-sm text-on-surface-variant uppercase tracking-wider font-semibold border-b border-outline-variant/30">
                                    Code</th>
                                <th
                                    class="px-lg py-md text-label-sm text-on-surface-variant uppercase tracking-wider font-semibold border-b border-outline-variant/30">
                                    Discount Type</th>
                                <th
                                    class="px-lg py-md text-label-sm text-on-surface-variant uppercase tracking-wider font-semibold border-b border-outline-variant/30">
                                    Status</th>
                                <th
                                    class="px-lg py-md text-label-sm text-on-surface-variant uppercase tracking-wider font-semibold border-b border-outline-variant/30">
                                    Usage</th>
                                <th
                                    class="px-lg py-md text-label-sm text-on-surface-variant uppercase tracking-wider font-semibold border-b border-outline-variant/30">
                                    Expiry Date</th>
                                <th
                                    class="px-lg py-md text-label-sm text-on-surface-variant uppercase tracking-wider font-semibold border-b border-outline-variant/30">
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/20">
                            @forelse($vouchers as $voucher)
                            <tr class="hover:bg-surface-container-low/30 transition-colors group">
                                <td class="px-lg py-lg">
                                    <div class="flex items-center gap-md">
                                        <div class="w-10 h-10 rounded-full bg-primary/5 flex items-center justify-center">
                                            <span class="material-symbols-outlined text-primary">local_offer</span>
                                        </div>
                                        <div>
                                            <p class="font-body-lg text-on-surface font-semibold">{{ $voucher->name }}</p>
                                            <p class="text-label-md text-on-surface-variant">{{ Str::limit($voucher->description, 30) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-lg py-lg">
                                    <code class="px-sm py-1 bg-surface-container-high rounded text-primary font-mono font-bold">{{ $voucher->code }}</code>
                                </td>
                                <td class="px-lg py-lg">
                                    <span class="text-body-md text-on-surface-variant">
                                        @if($voucher->discount_type == 'percent')
                                            {{ floatval($voucher->discount_value) }}% Giảm
                                        @else
                                            {{ number_format($voucher->discount_value, 0, ',', '.') }} VNĐ
                                        @endif
                                    </span>
                                </td>
                                <td class="px-lg py-lg">
                                    @if($voucher->status && $voucher->end_date > now())
                                    <span class="px-sm py-1 rounded-full bg-primary/10 text-primary text-label-sm font-bold flex items-center w-fit gap-xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span> Active
                                    </span>
                                    @elseif(!$voucher->status)
                                    <span class="px-sm py-1 rounded-full bg-surface-container-high text-on-surface-variant text-label-sm font-bold flex items-center w-fit gap-xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-outline"></span> Inactive
                                    </span>
                                    @else
                                    <span class="px-sm py-1 rounded-full bg-error/10 text-error text-label-sm font-bold flex items-center w-fit gap-xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-error"></span> Expired
                                    </span>
                                    @endif
                                </td>
                                <td class="px-lg py-lg">
                                    <div class="w-full max-w-[120px]">
                                        <div class="flex justify-between text-[11px] mb-1">
                                            <span class="font-bold">{{ $voucher->used }}/{{ $voucher->quantity }}</span>
                                            <span class="text-on-surface-variant">{{ $voucher->quantity > 0 ? round(($voucher->used / $voucher->quantity) * 100) : 0 }}%</span>
                                        </div>
                                        <div class="w-full h-1.5 bg-surface-container-high rounded-full overflow-hidden">
                                            <div class="h-full bg-primary rounded-full" style="width: {{ $voucher->quantity > 0 ? ($voucher->used / $voucher->quantity) * 100 : 0 }}%;"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-lg py-lg">
                                    <p class="text-body-md text-on-surface">{{ $voucher->end_date ? $voucher->end_date->format('d/m/Y H:i') : '' }}</p>
                                </td>
                                <td class="px-lg py-lg text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="/admin/voucher/{{ $voucher->id }}/edit" class="p-2 text-on-surface-variant hover:text-primary transition-colors bg-surface-container-low rounded-lg" title="Sửa">
                                            <span class="material-symbols-outlined text-[20px]">edit</span>
                                        </a>
                                        <form method="POST" action="/admin/voucher/{{ $voucher->id }}/delete" onsubmit="return confirm('Bạn có chắc muốn xóa voucher này?');" class="inline-block">
                                            @csrf
                                            <button type="submit" class="p-2 text-error hover:bg-error-container hover:text-error transition-colors rounded-lg" title="Xóa">
                                                <span class="material-symbols-outlined text-[20px]">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-lg py-xl text-center text-on-surface-variant">
                                    <span class="material-symbols-outlined text-[48px] mb-2 opacity-50">confirmation_number</span>
                                    <p>Chưa có voucher nào trong hệ thống.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="px-lg py-md border-t border-outline-variant/30 bg-surface-container-low/30">
                    {{ $vouchers->links('pagination::tailwind') }}
                </div>
            </section>
            <!-- Bottom Action Grid (Informational Bento) -->
            <section class="grid grid-cols-1 lg:grid-cols-3 gap-lg pb-xl">
                <div
                    class="lg:col-span-2 bg-gradient-to-br from-primary-container/20 to-secondary-container/20 p-xl rounded-2xl border border-primary/10 flex items-center justify-between overflow-hidden relative">
                    <div class="relative z-10 space-y-md">
                        <h4 class="font-headline-md text-headline-md text-primary-fixed-variant">Optimize Your Campaigns
                        </h4>
                        <p class="text-body-lg text-on-surface-variant max-w-md">Our AI analyzed your recent vouchers.
                            Codes using "COZY" as a prefix have a 34% higher conversion rate. Try applying this to your
                            next campaign.</p>
                        <button
                            class="bg-primary text-on-primary px-lg py-2 rounded-lg font-title-lg active:scale-95 transition-all">View
                            Insights</button>
                    </div>
                    <div class="absolute -right-12 -bottom-12 opacity-10 transform rotate-12">
                        <span class="material-symbols-outlined text-[240px] text-primary">analytics</span>
                    </div>
                </div>
                <div
                    class="bg-surface-container-highest/40 p-xl rounded-2xl border border-outline-variant/30 space-y-md flex flex-col justify-center">
                    <h4 class="font-title-lg text-title-lg text-on-surface">Need Help?</h4>
                    <p class="text-body-md text-on-surface-variant">Check out our documentation on how to set up tiered
                        discounts and usage limits for high-volume periods.</p>
                    <a class="flex items-center gap-xs text-primary font-bold group" href="#">
                        User Guide <span
                            class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </a>
                </div>
            </section>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    // Simple ripple effect for buttons
    document.querySelectorAll('button').forEach(button => {
        button.addEventListener('mousedown', function(e) {
            this.classList.add('active:scale-95');
            setTimeout(() => {
                this.classList.remove('active:scale-95');
            }, 200);
        });
    });

    // Search bar interaction
    const searchInput = document.querySelector('input[type="text"]');
    if (searchInput) {
        searchInput.addEventListener('focus', () => {
            searchInput.parentElement.classList.add('ring-2', 'ring-primary/20');
        });
        searchInput.addEventListener('blur', () => {
            searchInput.parentElement.classList.remove('ring-2', 'ring-primary/20');
        });
    }
</script>
@endpush
