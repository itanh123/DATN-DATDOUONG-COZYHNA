@extends('layouts.customer')

@section('title', 'Mã giảm giá của tôi - CozyHNA')

@section('content')
<main class="pt-24 pb-12 bg-surface-container-lowest min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-display-sm font-bold text-on-surface mb-2">Mã giảm giá của tôi</h1>
                <p class="text-body-lg text-on-surface-variant">Quản lý và sử dụng các voucher bạn đã lưu.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-success-container text-on-success-container font-medium flex items-center gap-2">
                <span class="material-symbols-outlined">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 rounded-xl bg-error-container text-on-error-container font-medium flex items-center gap-2">
                <span class="material-symbols-outlined">error</span>
                {{ session('error') }}
            </div>
        @endif

        @if($vouchers->isEmpty())
            <div class="bg-surface rounded-3xl p-12 text-center shadow-sm border border-outline-variant/30 flex flex-col items-center justify-center">
                <div class="w-24 h-24 bg-surface-container rounded-full flex items-center justify-center mb-6">
                    <span class="material-symbols-outlined text-[48px] text-primary">discount</span>
                </div>
                <h3 class="text-title-lg font-bold text-on-surface mb-2">Chưa có mã giảm giá nào</h3>
                <p class="text-body-md text-on-surface-variant mb-6">Bạn chưa lưu mã giảm giá nào. Hãy quay lại trang chủ để tìm kiếm các ưu đãi mới nhất nhé!</p>
                <a href="/" class="px-6 py-3 bg-primary text-on-primary rounded-xl font-title-md font-bold hover:bg-primary/90 transition-colors active:scale-95 flex items-center gap-2">
                    <span class="material-symbols-outlined">home</span> Khám phá ngay
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($vouchers as $voucher)
                    <div class="bg-surface rounded-3xl p-6 shadow-sm border border-outline-variant/30 relative overflow-hidden group hover:shadow-md transition-shadow">
                        <!-- Decorative circle -->
                        <div class="absolute -right-6 -top-6 w-24 h-24 bg-primary/5 rounded-full z-0 group-hover:scale-110 transition-transform"></div>
                        
                        <div class="relative z-10">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                                    <span class="material-symbols-outlined text-[24px]">confirmation_number</span>
                                </div>
                                @if($voucher->pivot->is_used)
                                    <span class="px-3 py-1 bg-surface-container text-on-surface-variant text-label-sm font-bold rounded-full">Đã dùng</span>
                                @else
                                    <span class="px-3 py-1 bg-success/10 text-success text-label-sm font-bold rounded-full">Khả dụng</span>
                                @endif
                            </div>
                            
                            @if(is_array($voucher->target_audience) && !in_array('all', $voucher->target_audience))
                                @php
                                    $rankNames = [
                                        'Member' => 'Thành viên',
                                        'Silver' => 'Hạng Bạc',
                                        'Gold' => 'Hạng Vàng',
                                        'Diamond' => 'Hạng Kim Cương'
                                    ];
                                    $allowedRanks = array_map(function($r) use ($rankNames) {
                                        return $rankNames[$r] ?? $r;
                                    }, $voucher->target_audience);
                                    $targetName = implode(', ', $allowedRanks);
                                @endphp
                                <div class="mb-2 inline-block px-2 py-0.5 bg-error/10 text-error text-[10px] font-bold rounded-full uppercase tracking-wider">
                                    Chỉ dành cho {{ $targetName }}
                                </div>
                            @endif
                            
                            <h3 class="text-title-md font-bold text-on-surface mb-2">{{ $voucher->name }}</h3>
                            <p class="text-body-sm text-on-surface-variant mb-2 line-clamp-2 h-10">{{ $voucher->description }}</p>
                            
                            <div class="mb-4 space-y-1.5 text-body-sm text-on-surface-variant bg-surface-container-lowest p-3 rounded-xl">
                                <div class="flex justify-between items-center">
                                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">sell</span> Giảm:</span>
                                    <span class="font-bold text-primary">
                                        @if($voucher->discount_type === 'percent')
                                            {{ $voucher->discount_value }}% 
                                            @if($voucher->maximum_discount)
                                                (Tối đa {{ number_format($voucher->maximum_discount, 0, ',', '.') }}đ)
                                            @endif
                                        @else
                                            {{ number_format($voucher->discount_value, 0, ',', '.') }}đ
                                        @endif
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">shopping_bag</span> Đơn tối thiểu:</span>
                                    <span class="font-bold text-on-surface">{{ number_format($voucher->minimum_order, 0, ',', '.') }}đ</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">schedule</span> HSD:</span>
                                    <span class="font-bold {{ \Carbon\Carbon::parse($voucher->end_date)->isPast() ? 'text-error' : 'text-on-surface' }}">
                                        {{ \Carbon\Carbon::parse($voucher->end_date)->format('d/m/Y H:i') }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between bg-surface-container-lowest border border-outline-variant/50 rounded-xl p-3">
                                <div>
                                    <div class="text-label-sm text-on-surface-variant mb-0.5">Mã Code</div>
                                    <div class="font-mono font-bold text-primary text-title-md">{{ $voucher->code }}</div>
                                </div>
                                <button onclick="copyVoucher('{{ $voucher->code }}', this)" class="w-10 h-10 rounded-full flex items-center justify-center hover:bg-surface-container transition-colors text-primary" title="Copy mã">
                                    <span class="material-symbols-outlined text-[20px]">content_copy</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</main>
@endsection

@push('scripts')
<script>
    function copyVoucher(code, btn) {
        navigator.clipboard.writeText(code);
        const icon = btn.querySelector('.material-symbols-outlined');
        const originalText = icon.innerHTML;
        
        icon.innerHTML = 'check';
        icon.classList.remove('text-primary');
        icon.classList.add('text-success');
        
        setTimeout(() => {
            icon.innerHTML = originalText;
            icon.classList.add('text-primary');
            icon.classList.remove('text-success');
        }, 2000);
    }
</script>
@endpush
