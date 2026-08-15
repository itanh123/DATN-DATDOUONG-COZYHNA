@extends('layouts.admin')

@section('title', 'Đánh giá của bạn - Shipper')

@section('content')
<main class="md:ml-[280px] min-h-screen pb-2xl md:pb-lg">
    <header class="sticky top-0 z-30 bg-surface/80 backdrop-blur-md px-lg py-md border-b border-outline-variant/30 flex justify-between items-center">
        <div>
            <h2 class="font-headline-md text-headline-md text-on-surface flex items-center gap-sm">
                <span class="material-symbols-outlined text-primary">star</span>
                Đánh giá của bạn
            </h2>
            <p class="font-body-md text-body-md text-on-surface-variant">
                Lịch sử đánh giá từ khách hàng sau khi giao hàng thành công
            </p>
        </div>
        <div class="hidden lg:flex items-center gap-md text-right">
            <div>
                <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Đánh giá trung bình</p>
                <div class="flex items-center gap-1 justify-end mt-1">
                    <span class="material-symbols-outlined text-yellow-500 fill-current">star</span>
                    <span class="font-headline-md text-headline-md text-primary font-bold">{{ number_format($shipper->rating, 1) }}</span>
                    <span class="font-body-md text-on-surface-variant">/ 5.0</span>
                </div>
            </div>
        </div>
    </header>

    <div class="p-lg md:p-xl lg:px-2xl max-w-container-max mx-auto space-y-lg">
        <div class="lg:hidden glass-card p-md rounded-xl flex items-center gap-md">
            <div class="h-12 w-12 rounded-lg bg-yellow-500/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-yellow-500">star</span>
            </div>
            <div>
                <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Đánh giá trung bình</p>
                <p class="font-headline-md text-headline-md text-primary font-bold">{{ number_format($shipper->rating, 1) }} <span class="text-on-surface-variant font-normal text-body-md">/ 5.0</span></p>
            </div>
        </div>

        <div class="glass-card rounded-2xl overflow-hidden border border-outline-variant/30 shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[600px]">
                    <thead>
                        <tr class="bg-surface-container-low border-b border-outline-variant/30">
                            <th class="px-lg py-md font-label-sm text-label-sm text-on-surface-variant uppercase">Mã đơn</th>
                            <th class="px-lg py-md font-label-sm text-label-sm text-on-surface-variant uppercase">Thời gian</th>
                            <th class="px-lg py-md font-label-sm text-label-sm text-on-surface-variant uppercase">Khách hàng</th>
                            <th class="px-lg py-md font-label-sm text-label-sm text-on-surface-variant uppercase">Địa chỉ</th>
                            <th class="px-lg py-md font-label-sm text-label-sm text-on-surface-variant uppercase text-right">Số sao đánh giá</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/10">
                        @forelse($ordersWithReviews as $order)
                            <tr class="hover:bg-surface-container-lowest transition-colors">
                                <td class="px-lg py-md font-label-md font-bold text-primary">
                                    #{{ $order->order_code }}
                                </td>
                                <td class="px-lg py-md text-on-surface-variant text-body-md">
                                    {{ $order->completed_at ? \Carbon\Carbon::parse($order->completed_at)->format('d/m/Y H:i') : $order->updated_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-lg py-md text-on-surface-variant text-body-md">
                                    {{ $order->customer->user->username ?? $order->customer->full_name ?? 'Khách hàng' }}
                                </td>
                                <td class="px-lg py-md text-on-surface-variant text-body-md max-w-[250px] truncate">
                                    {{ $order->delivery_address ?: ($order->address->address ?? '—') }}
                                </td>
                                <td class="px-lg py-md text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span class="material-symbols-outlined text-[18px] {{ $i <= $order->shipper_rating ? 'text-yellow-500 fill-current' : 'text-outline-variant' }}">
                                                star
                                            </span>
                                        @endfor
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-lg py-2xl text-center text-on-surface-variant">
                                    <span class="material-symbols-outlined text-[48px] opacity-20 mb-sm">star_rate</span>
                                    <p class="font-body-lg">Bạn chưa nhận được đánh giá nào.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($ordersWithReviews->hasPages())
                <div class="px-lg py-md border-t border-outline-variant/30 bg-surface-container-lowest">
                    {{ $ordersWithReviews->links() }}
                </div>
            @endif
        </div>
    </div>
</main>
@endsection
