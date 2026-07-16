@extends('layouts.admin')

@section('title', 'Quản lý Đơn Hàng - Nhân viên')

@section('content')
<main class="pt-24 pb-12 px-gutter md:ml-[280px] min-h-screen">

    {{-- Header --}}
    <section class="mb-xl">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-lg">
            <div>
                <h2 class="font-headline-lg text-headline-lg text-on-background mb-base">Bảng Điều Khiển Nhân Viên</h2>
                <p class="font-body-lg text-body-lg text-on-surface-variant">Xử lý đơn hàng đến trong thời gian thực.</p>
            </div>
            <div class="glass-card rounded-2xl p-lg flex items-center gap-lg shadow-sm border border-outline-variant/20">
                <div class="flex flex-col">
                    <span class="font-label-sm text-label-sm text-primary uppercase tracking-wider">Hoàn thành hôm nay</span>
                    <span class="font-headline-md text-headline-md text-on-background font-bold">{{ $todayCompleted }}</span>
                </div>
                <span class="material-symbols-outlined text-secondary text-[32px]" style="font-variation-settings: 'FILL' 1;">task_alt</span>
            </div>
        </div>
    </section>

    {{-- Pending Orders --}}
    <section class="mb-xl">
        <div class="flex items-center gap-sm mb-lg">
            <div class="w-3 h-3 rounded-full bg-yellow-500 animate-pulse"></div>
            <h3 class="font-title-lg text-title-lg text-on-background">Chờ Xác Nhận
                <span class="ml-sm text-sm font-label-md px-sm py-xs bg-yellow-100 text-yellow-700 rounded-full">{{ $pendingOrders->count() }}</span>
            </h3>
        </div>

        @if($pendingOrders->isEmpty())
            <div class="glass-card rounded-2xl p-xl text-center shadow-sm border border-outline-variant/10">
                <span class="material-symbols-outlined text-[48px] text-outline-variant">hourglass_empty</span>
                <p class="font-body-lg text-on-surface-variant mt-sm">Không có đơn hàng chờ xác nhận</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-lg">
                @foreach($pendingOrders as $order)
                <div class="glass-card rounded-2xl p-lg shadow-sm border border-yellow-200 bg-yellow-50/30" id="order-card-{{ $order->id }}">
                    <div class="flex justify-between items-start mb-md">
                        <div>
                            <p class="font-label-md text-label-md text-on-surface-variant">Mã đơn</p>
                            <p class="font-bold text-on-surface tracking-wider">{{ $order->order_code }}</p>
                        </div>
                        <span class="px-sm py-xs rounded-full text-xs font-bold bg-yellow-100 text-yellow-700">Chờ xác nhận</span>
                    </div>

                    {{-- Customer info --}}
                    <div class="flex items-center gap-sm mb-md pb-md border-b border-outline-variant/20">
                        <span class="material-symbols-outlined text-on-surface-variant text-[20px]">person</span>
                        <div>
                            <p class="font-body-md text-body-md font-medium">{{ $order->customer->full_name ?? 'Khách hàng' }}</p>
                            @if($order->address)
                                <p class="font-label-sm text-label-sm text-on-surface-variant truncate max-w-[200px]">{{ $order->address->address }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- Items --}}
                    <div class="space-y-xs mb-md">
                        @foreach($order->items as $item)
                        <div class="flex justify-between text-body-md">
                            <span class="text-on-surface">{{ $item->productSize->product->name ?? 'SP' }} ({{ $item->productSize->size->name ?? '' }}) × {{ $item->quantity }}</span>
                            <span class="text-primary font-bold flex-shrink-0 ml-sm">{{ number_format($item->total_price, 0, ',', '.') }}đ</span>
                        </div>
                        @endforeach
                    </div>

                    {{-- Total & CTA --}}
                    <div class="pt-md border-t border-outline-variant/20 flex items-center justify-between">
                        <div>
                            <p class="font-label-sm text-label-sm text-on-surface-variant">Tổng cộng</p>
                            <p class="font-headline-md text-headline-md text-primary font-bold">{{ number_format($order->total_amount, 0, ',', '.') }}đ</p>
                        </div>
                        <button onclick="confirmOrder({{ $order->id }}, this)"
                            class="px-lg py-sm bg-primary text-on-primary rounded-xl font-bold shadow-sm hover:shadow-md active:scale-95 transition-all">
                            Xác nhận
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </section>

    {{-- Preparing Orders --}}
    <section class="mb-xl">
        <div class="flex items-center gap-sm mb-lg">
            <div class="w-3 h-3 rounded-full bg-orange-500 animate-pulse"></div>
            <h3 class="font-title-lg text-title-lg text-on-background">Đang Pha Chế
                <span class="ml-sm text-sm font-label-md px-sm py-xs bg-orange-100 text-orange-700 rounded-full">{{ $preparingOrders->count() }}</span>
            </h3>
        </div>

        @if($preparingOrders->isEmpty())
            <div class="glass-card rounded-2xl p-xl text-center shadow-sm border border-outline-variant/10">
                <span class="material-symbols-outlined text-[48px] text-outline-variant">local_cafe</span>
                <p class="font-body-lg text-on-surface-variant mt-sm">Không có đơn hàng đang pha chế</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-lg">
                @foreach($preparingOrders as $order)
                <div class="glass-card rounded-2xl p-lg shadow-sm border border-orange-200 bg-orange-50/30" id="order-card-{{ $order->id }}">
                    <div class="flex justify-between items-start mb-md">
                        <div>
                            <p class="font-label-md text-label-md text-on-surface-variant">Mã đơn</p>
                            <p class="font-bold text-on-surface tracking-wider">{{ $order->order_code }}</p>
                        </div>
                        <span class="px-sm py-xs rounded-full text-xs font-bold bg-orange-100 text-orange-700">Đang pha chế</span>
                    </div>
                    <div class="space-y-xs mb-md">
                        @foreach($order->items as $item)
                        <div class="flex justify-between text-body-md">
                            <span class="text-on-surface">{{ $item->productSize->product->name ?? 'SP' }} ({{ $item->productSize->size->name ?? '' }}) × {{ $item->quantity }}</span>
                        </div>
                        @endforeach
                    </div>
                    <div class="pt-md border-t border-outline-variant/20 flex items-center justify-between">
                        <p class="font-headline-md text-headline-md text-primary font-bold">{{ number_format($order->total_amount, 0, ',', '.') }}đ</p>
                        <button onclick="completeOrder({{ $order->id }}, this)"
                            class="px-lg py-sm bg-secondary text-white rounded-xl font-bold shadow-sm hover:shadow-md active:scale-95 transition-all">
                            Hoàn thành pha chế
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </section>
</main>
@endsection

@push('scripts')
<script>
function confirmOrder(orderId, btn) {
    btn.disabled = true;
    btn.innerText = 'Đang xử lý...';

    fetch(`/staff/orders/${orderId}/confirm`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            const card = document.getElementById(`order-card-${orderId}`);
            if (card) {
                card.style.transition = 'all 0.3s ease';
                card.style.opacity = '0';
                card.style.transform = 'scale(0.95)';
                setTimeout(() => card.remove(), 300);
            }
            // Show a toast notification
            showToast(res.message, 'success');
        } else {
            alert(res.error || 'Có lỗi xảy ra.');
            btn.disabled = false;
            btn.innerText = 'Xác nhận';
        }
    })
    .catch(() => {
        alert('Lỗi kết nối, vui lòng thử lại.');
        btn.disabled = false;
        btn.innerText = 'Xác nhận';
    });
}

function completeOrder(orderId, btn) {
    btn.disabled = true;
    btn.innerText = 'Đang xử lý...';

    fetch(`/staff/orders/${orderId}/complete`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            const card = document.getElementById(`order-card-${orderId}`);
            if (card) {
                card.style.transition = 'all 0.3s ease';
                card.style.opacity = '0';
                setTimeout(() => card.remove(), 300);
            }
            showToast(res.message, 'success');
        } else {
            alert(res.error || 'Có lỗi xảy ra.');
            btn.disabled = false;
            btn.innerText = 'Hoàn thành pha chế';
        }
    });
}

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    const bgColor = type === 'success' ? 'bg-green-600' : 'bg-red-600';
    toast.className = `fixed bottom-8 right-8 z-[999] ${bgColor} text-white px-xl py-md rounded-2xl shadow-xl font-body-md flex items-center gap-sm transition-all`;
    toast.innerHTML = `<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1">check_circle</span>${message}`;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
</script>
@endpush
