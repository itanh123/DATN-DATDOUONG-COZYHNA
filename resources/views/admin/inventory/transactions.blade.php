@extends('layouts.admin')

@section('title', 'Lịch sử Biến động Kho')

@section('content')
<main class="md:ml-[280px] pt-16 min-h-screen p-gutter bg-background">
    <div class="max-w-[1400px] mx-auto space-y-gutter">
        
        <!-- Header & Breadcrumbs -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="font-headline-md text-on-surface">Lịch sử Biến động Kho</h1>
                <p class="text-body-md text-on-surface-variant">Theo dõi chi tiết nhập, xuất, và điều chỉnh nguyên vật liệu</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="/admin/ingredients" class="flex items-center gap-xs px-lg py-md border border-outline rounded-xl font-label-md text-on-surface-variant hover:bg-surface-container-low transition-all">
                    <span class="material-symbols-outlined">inventory_2</span>
                    Quản lý kho
                </a>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm">
            <div class="p-lg border-b border-outline-variant flex justify-between items-center bg-surface-bright">
                <h3 class="font-title-lg text-on-surface">Danh sách giao dịch</h3>
                <div class="flex gap-sm">
                    <input type="text" placeholder="Tìm kiếm mã đơn hoặc nguyên liệu..." class="px-4 py-2 border border-outline rounded-lg text-body-md min-w-[250px] focus:ring-primary focus:border-primary">
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-surface-container-low border-b border-outline-variant">
                        <tr>
                            <th class="p-lg font-label-sm text-on-surface-variant uppercase">Thời gian</th>
                            <th class="p-lg font-label-sm text-on-surface-variant uppercase">Loại</th>
                            <th class="p-lg font-label-sm text-on-surface-variant uppercase">Nguyên liệu</th>
                            <th class="p-lg font-label-sm text-on-surface-variant uppercase">Số lượng</th>
                            <th class="p-lg font-label-sm text-on-surface-variant uppercase">Tồn trước/sau</th>
                            <th class="p-lg font-label-sm text-on-surface-variant uppercase">Ghi chú</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/30">
                        @forelse($transactions as $transaction)
                        <tr class="hover:bg-surface-container-low/50 transition-colors">
                            <td class="p-lg">
                                <div class="font-body-md text-on-surface">{{ $transaction->created_at->format('d/m/Y') }}</div>
                                <div class="font-label-sm text-outline">{{ $transaction->created_at->format('H:i:s') }}</div>
                            </td>
                            <td class="p-lg">
                                @if($transaction->transaction_type == 'EXPORT')
                                    <span class="px-sm py-1 bg-error-container/20 text-error rounded-full font-label-sm border border-error/10 flex items-center gap-1 w-fit">
                                        <span class="material-symbols-outlined text-[16px]">arrow_outward</span> Xuất
                                    </span>
                                @elseif($transaction->transaction_type == 'IMPORT')
                                    <span class="px-sm py-1 bg-primary-container/20 text-primary rounded-full font-label-sm border border-primary/10 flex items-center gap-1 w-fit">
                                        <span class="material-symbols-outlined text-[16px]">south_east</span> Nhập
                                    </span>
                                @elseif($transaction->transaction_type == 'EXPIRED')
                                    <span class="px-sm py-1 bg-error-container/20 text-error rounded-full font-label-sm border border-error/10 flex items-center gap-1 w-fit">
                                        <span class="material-symbols-outlined text-[16px]">delete_forever</span> Hủy (Hết hạn)
                                    </span>
                                @else
                                    <span class="px-sm py-1 bg-secondary-container/20 text-secondary rounded-full font-label-sm border border-secondary/10 w-fit">
                                        {{ $transaction->transaction_type }}
                                    </span>
                                @endif
                            </td>
                            <td class="p-lg">
                                <div class="font-title-md text-on-surface">{{ $transaction->ingredient->name ?? __('Không rõ') }}</div>
                            </td>
                            <td class="p-lg">
                                <div class="font-title-lg {{ in_array($transaction->transaction_type, ['EXPORT', 'EXPIRED']) ? 'text-error' : 'text-primary' }}">
                                    {{ in_array($transaction->transaction_type, ['EXPORT', 'EXPIRED']) ? '-' : '+' }}{{ number_format($transaction->quantity, 2) }}
                                </div>
                                <div class="font-label-sm text-outline">{{ $transaction->ingredient->unit->name ?? __('') }}</div>
                            </td>
                            <td class="p-lg">
                                <div class="flex items-center gap-2">
                                    <span class="font-body-md text-on-surface-variant">{{ number_format($transaction->before_quantity, 2) }}</span>
                                    <span class="material-symbols-outlined text-[16px] text-outline">arrow_forward</span>
                                    <span class="font-body-md text-on-surface">{{ number_format($transaction->after_quantity, 2) }}</span>
                                </div>
                            </td>
                            <td class="p-lg">
                                <span class="font-body-md text-on-surface-variant">{{ $transaction->note }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-lg text-center text-on-surface-variant py-10">
                                Chưa có giao dịch nào được ghi nhận.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($transactions->hasPages())
            <div class="p-lg border-t border-outline-variant flex items-center justify-between">
                {{ $transactions->links() }}
            </div>
            @endif
        </div>
    </div>
</main>
@endsection
