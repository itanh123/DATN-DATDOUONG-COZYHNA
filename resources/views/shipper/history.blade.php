@extends('layouts.admin')

@section('title', 'Lịch sử giao hàng - Shipper')

@section('content')
<main class="md:ml-[280px] min-h-screen pb-2xl md:pb-lg">
    <header class="sticky top-0 z-30 bg-surface/80 backdrop-blur-md px-lg py-md border-b border-outline-variant/30 flex justify-between items-center">
        <div>
            <h2 class="font-headline-md text-headline-md text-on-surface flex items-center gap-sm">
                <span class="material-symbols-outlined text-primary">history</span>
                Lịch sử giao hàng
            </h2>
            <p class="font-body-md text-body-md text-on-surface-variant">
                Quản lý các chuyến giao hàng thành công và doanh thu của bạn
            </p>
        </div>
        <div class="hidden lg:block text-right">
            <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Tổng doanh thu nhận được (phí ship)</p>
            <p class="font-headline-md text-headline-md text-primary font-bold">{{ number_format($totalRevenue, 0, ',', '.') }}đ</p>
        </div>
    </header>

    <div class="p-lg md:p-xl lg:px-2xl max-w-container-max mx-auto space-y-lg">
        <div class="lg:hidden glass-card p-md rounded-xl flex items-center gap-md">
            <div class="h-12 w-12 rounded-lg bg-primary/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-primary">payments</span>
            </div>
            <div>
                <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Tổng doanh thu nhận được</p>
                <p class="font-headline-md text-headline-md text-primary font-bold">{{ number_format($totalRevenue, 0, ',', '.') }}đ</p>
            </div>
        </div>

        <div class="glass-card rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[600px]">
                    <thead>
                        <tr class="bg-surface-container-low border-b border-outline-variant/30">
                            <th class="px-lg py-md font-label-sm text-label-sm text-on-surface-variant uppercase">Mã đơn</th>
                            <th class="px-lg py-md font-label-sm text-label-sm text-on-surface-variant uppercase">Thời gian hoàn thành</th>
                            <th class="px-lg py-md font-label-sm text-label-sm text-on-surface-variant uppercase">Khách hàng</th>
                            <th class="px-lg py-md font-label-sm text-label-sm text-on-surface-variant uppercase hidden md:table-cell">Địa chỉ</th>
                            <th class="px-lg py-md font-label-sm text-label-sm text-on-surface-variant uppercase hidden lg:table-cell">Khoảng cách</th>
                            <th class="px-lg py-md font-label-sm text-label-sm text-on-surface-variant uppercase text-right">Phí Ship (Doanh thu)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/10">
                        @forelse($orders as $order)
                            <tr class="hover:bg-surface-container-lowest transition-colors">
                                <td class="px-lg py-md font-label-md font-bold text-primary">
                                    #{{ $order->order_code }}
                                </td>
                                <td class="px-lg py-md text-on-surface-variant text-body-md">
                                    {{ $order->completed_at ? \Carbon\Carbon::parse($order->completed_at)->format('d/m/Y H:i') : $order->updated_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-lg py-md text-on-surface-variant text-body-md">
                                    {{ $order->customer->user->username ?? $order->customer->full_name ?? __('Khách hàng') }}
                                </td>
                                <td class="px-lg py-md text-on-surface-variant text-body-md hidden md:table-cell max-w-[250px] truncate">
                                    {{ $order->delivery_address ?: ($order->address->address ?? __('—')) }}
                                </td>
                                <td class="px-lg py-md hidden lg:table-cell">
                                    @if($order->distance_km > 0)
                                        <span class="text-body-md text-on-surface-variant">{{ number_format($order->distance_km, 1) }} km</span>
                                    @else
                                        <span class="text-[11px] text-on-surface-variant/50 italic">Chưa ghi nhận</span>
                                    @endif
                                </td>
                                <td class="px-lg py-md text-on-surface font-semibold text-right">
                                    <div class="flex flex-col items-end gap-0.5">
                                        <span class="text-primary font-bold">+{{ number_format($order->shipping_fee, 0, ',', '.') }}đ</span>
                                        @if($order->distance_km > 0)
                                            <span class="text-[10px] text-on-surface-variant/60">{{ number_format($order->distance_km, 1) }} km</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-lg py-2xl text-center text-on-surface-variant">
                                    <span class="material-symbols-outlined text-[48px] opacity-20 mb-sm">history</span>
                                    <p class="font-body-lg">Bạn chưa có đơn hàng nào hoàn thành.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($orders->hasPages())
                <div class="px-lg py-md border-t border-outline-variant/30 bg-surface-container-lowest">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
</main>
@endsection
