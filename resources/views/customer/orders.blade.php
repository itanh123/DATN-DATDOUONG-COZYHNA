@extends('layouts.customer')

@section('title', 'Lịch Sử Đơn Hàng')

@section('content')
<main class="mt-24 pb-24 max-w-container-max mx-auto px-4 md:px-lg">
    <div class="mb-xl">
        <h1 class="font-headline-lg text-headline-lg text-on-background">Đơn hàng của tôi</h1>
        <p class="font-body-md text-body-md text-on-surface-variant">Theo dõi và quản lý tất cả đơn hàng của bạn.</p>
    </div>

    @if(session('success'))
        <div class="mb-md p-md bg-green-100 text-green-700 rounded-xl font-body-md flex items-center gap-sm">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    @if($orders->isEmpty())
        <div class="text-center py-2xl bg-surface-container-lowest rounded-2xl border border-outline-variant/10">
            <span class="material-symbols-outlined text-[80px] text-outline-variant">receipt_long</span>
            <h2 class="font-headline-md text-headline-md text-on-surface mt-md">Chưa có đơn hàng nào</h2>
            <p class="text-on-surface-variant font-body-md mt-xs mb-xl">Hãy đặt đồ uống đầu tiên của bạn ngay!</p>
            <a href="/" class="bg-primary text-white px-xl py-md rounded-xl font-bold hover:bg-primary/90 transition-all">Xem thực đơn</a>
        </div>
    @else
        <div class="space-y-lg">
            @foreach($orders as $order)
            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/10 shadow-sm overflow-hidden">
                {{-- Order Header --}}
                <div class="p-lg border-b border-outline-variant/10 flex flex-col sm:flex-row sm:items-center justify-between gap-md">
                    <div>
                        <p class="font-label-md text-label-md text-on-surface-variant mb-xs">Mã đơn hàng</p>
                        <p class="font-title-lg text-title-lg font-bold tracking-wider">{{ $order->order_code }}</p>
                    </div>
                    <div class="flex items-center gap-md">
                        <span class="px-md py-xs rounded-full font-label-md text-label-md font-bold {{ $order->status_color }}">
                            {{ $order->status_label }}
                        </span>
                        @if(in_array($order->status, ['pending', 'confirmed']))
                            <button onclick="cancelOrder({{ $order->id }}, this)"
                                class="px-md py-xs rounded-full border border-red-300 text-red-600 font-label-md text-label-md hover:bg-red-50 transition-colors">
                                Hủy đơn
                            </button>
                        @endif
                    </div>
                </div>

                {{-- Order Items --}}
                <div class="p-lg">
                    <div class="space-y-sm mb-lg">
                        @foreach($order->items as $item)
                        @php
                            $product = $item->productSize->product ?? null;
                            $size    = $item->productSize->size ?? null;
                        @endphp
                        <div class="flex items-center gap-md">
                            <div class="w-14 h-14 rounded-xl bg-surface-container overflow-hidden flex-shrink-0">
                                @if($product && $product->image)
                                    <img class="w-full h-full object-cover" src="{{ $product->image }}" alt="{{ $product->name }}"/>
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <span class="material-symbols-outlined text-outline-variant text-[24px]">local_cafe</span>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow">
                                <p class="font-body-lg text-body-lg font-medium">{{ $product->name ?? 'Sản phẩm' }}</p>
                                <p class="font-label-md text-label-md text-on-surface-variant">{{ $size->name ?? '' }} × {{ $item->quantity }}</p>
                            </div>
                            <p class="font-body-lg text-body-lg font-bold text-primary flex-shrink-0">
                                {{ number_format($item->total_price, 0, ',', '.') }} đ
                            </p>
                        </div>
                        @endforeach
                    </div>

                    {{-- Order Footer --}}
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-sm pt-md border-t border-outline-variant/10">
                        <p class="font-label-md text-label-md text-on-surface-variant">
                            Đặt lúc: {{ $order->ordered_at ? $order->ordered_at->format('d/m/Y H:i') : 'N/A' }}
                        </p>
                        <div class="flex items-center gap-sm">
                            <p class="font-body-md text-body-md text-on-surface-variant">Tổng cộng:</p>
                            <p class="font-headline-md text-headline-md text-primary font-bold">
                                {{ number_format($order->total_amount, 0, ',', '.') }} đ
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</main>
@endsection

@push('scripts')
<script>
function cancelOrder(orderId, btn) {
    if (!confirm('Bạn có chắc muốn hủy đơn hàng này không?')) return;

    btn.disabled = true;
    btn.innerText = 'Đang hủy...';

    fetch(`/orders/${orderId}/cancel`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            alert(res.message);
            location.reload();
        } else {
            alert(res.error || 'Có lỗi xảy ra.');
            btn.disabled = false;
            btn.innerText = 'Hủy đơn';
        }
    })
    .catch(() => {
        alert('Có lỗi mạng xảy ra, vui lòng thử lại.');
        btn.disabled = false;
        btn.innerText = 'Hủy đơn';
    });
}
</script>
@endpush
