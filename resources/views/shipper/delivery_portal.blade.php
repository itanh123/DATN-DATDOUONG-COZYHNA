@extends('layouts.admin')

@section('title', 'Delivery Portal - Shipper')

@section('content')
<main class="md:ml-[280px] min-h-screen pb-2xl md:pb-lg">

{{-- Header / Stats Bar --}}
<header class="sticky top-0 z-30 bg-surface/80 backdrop-blur-md px-lg py-md border-b border-outline-variant/30 flex justify-between items-center">
    <div>
        <h2 class="font-headline-md text-headline-md text-on-surface">Delivery Board</h2>
        <p class="font-body-md text-body-md text-on-surface-variant">
            Xin chào, <strong>{{ $shipper->user->full_name ?? $shipper->user->username ?? 'Shipper' }}</strong> — Quản lý chuyến giao hàng của bạn
        </p>
    </div>
    <div class="hidden lg:flex items-center gap-lg">
        <div class="text-right">
            <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Hôm nay</p>
            <p class="font-headline-md text-headline-md text-primary">{{ $todayDeliveries }} đơn</p>
        </div>
        <div class="h-10 w-[1px] bg-outline-variant/30"></div>
        <div class="text-right">
            <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Tổng cộng</p>
            <p class="font-headline-md text-headline-md text-secondary">{{ $totalDeliveries }}</p>
        </div>
        <div class="flex items-center justify-center h-12 w-12 rounded-full bg-surface-container-highest">
            <span class="material-symbols-outlined text-primary">account_circle</span>
        </div>
    </div>
</header>

<div class="max-w-container-max mx-auto p-md md:p-lg space-y-lg">

    {{-- Quick Stats Cards --}}
    <section class="grid grid-cols-1 md:grid-cols-4 gap-md">
        <div class="md:col-span-1 glass-card p-md rounded-xl flex items-center gap-md">
            <div class="h-12 w-12 rounded-lg bg-primary/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-primary">star</span>
            </div>
            <div>
                <p class="font-label-md text-label-md text-on-surface-variant">Đánh giá</p>
                <p class="font-title-lg text-title-lg">{{ number_format($shipper->rating, 2) }}</p>
            </div>
        </div>
        <div class="md:col-span-1 glass-card p-md rounded-xl flex items-center gap-md">
            <div class="h-12 w-12 rounded-lg bg-secondary/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-secondary">local_shipping</span>
            </div>
            <div>
                <p class="font-label-md text-label-md text-on-surface-variant">Hôm nay</p>
                <p class="font-title-lg text-title-lg">{{ $todayDeliveries }} đơn</p>
            </div>
        </div>
        <div class="md:col-span-1 glass-card p-md rounded-xl flex items-center gap-md">
            <div class="h-12 w-12 rounded-lg bg-green-500/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-green-600">package_2</span>
            </div>
            <div>
                <p class="font-label-md text-label-md text-on-surface-variant">Sẵn sàng nhận</p>
                <p class="font-title-lg text-title-lg">{{ $availableOrders->count() }}</p>
            </div>
        </div>
        <div class="md:col-span-1 glass-card p-md rounded-xl flex items-center gap-md">
            <div class="h-12 w-12 rounded-lg bg-orange-500/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-orange-600">delivery_truck_speed</span>
            </div>
            <div>
                <p class="font-label-md text-label-md text-on-surface-variant">Đang giao</p>
                <p class="font-title-lg text-title-lg">{{ $activeOrders->count() }}</p>
            </div>
        </div>
    </section>

    {{-- Thông báo flash --}}
    @if(session('success'))
        <div class="p-md rounded-xl bg-green-100 text-green-800 font-label-md flex items-center gap-sm">
            <span class="material-symbols-outlined">check_circle</span> {{ session('success') }}
        </div>
    @endif

    {{-- Main Tab Interface --}}
    <div class="flex flex-col gap-lg">
        <div class="flex items-center gap-md border-b border-outline-variant/30">
            <button class="px-lg py-md font-label-md text-label-md transition-all border-b-2 border-primary text-primary font-bold"
                    id="tab-available" onclick="switchTab('available')">
                Đơn sẵn sàng ({{ $availableOrders->count() }})
            </button>
            <button class="px-lg py-md font-label-md text-label-md text-on-surface-variant hover:text-primary transition-all"
                    id="tab-active" onclick="switchTab('active')">
                Đang giao ({{ $activeOrders->count() }})
            </button>
            <button class="px-lg py-md font-label-md text-label-md text-on-surface-variant hover:text-primary transition-all"
                    id="tab-history" onclick="switchTab('history')">
                Lịch sử
            </button>
        </div>

        {{-- Tab Content: Available --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-lg animate-in fade-in duration-500" id="content-available">
            @forelse($availableOrders as $order)
                <div class="glass-card rounded-xl p-md flex flex-col gap-md hover:shadow-lg transition-all">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-title-lg text-title-lg text-on-surface">
                                #{{ $order->code }}
                            </h3>
                            <p class="font-body-md text-body-md text-on-surface-variant flex items-center gap-xs mt-xs">
                                <span class="material-symbols-outlined text-[18px]">person</span>
                                {{ $order->customer->user->username ?? $order->customer->full_name ?? 'Khách hàng' }}
                            </p>
                        </div>
                        <span class="bg-secondary-container text-on-secondary-container px-sm py-[2px] rounded-full font-label-sm text-label-sm">
                            {{ number_format($order->total_amount, 0, ',', '.') }}đ
                        </span>
                    </div>

                    @if($order->delivery_address)
                        <p class="font-body-md text-body-md text-on-surface-variant flex items-start gap-xs">
                            <span class="material-symbols-outlined text-[18px] mt-[2px] shrink-0">location_on</span>
                            {{ $order->delivery_address }}
                        </p>
                    @endif

                    {{-- Danh sách sản phẩm --}}
                    <div class="bg-surface-container-low rounded-lg p-sm space-y-xs">
                        @foreach($order->items->take(3) as $item)
                            <div class="flex justify-between text-body-sm text-on-surface-variant border-b border-outline-variant/10 pb-xs last:border-0 last:pb-0">
                                <div>
                                    <span class="text-on-surface">
                                        {{ $item->product_name ?? $item->productSize?->product?->name ?? 'Sản phẩm' }}
                                        @if($item->size_name || $item->productSize?->size)
                                            ({{ $item->size_name ?? $item->productSize->size->name }})
                                        @endif
                                    </span>
                                    <span class="font-bold ml-sm">x{{ $item->quantity }}</span>
                                    @if($item->toppings && $item->toppings->count() > 0)
                                        <div class="text-[11px] text-on-surface-variant mt-1">
                                            + Topping: 
                                            @foreach($item->toppings as $index => $t)
                                                {{ $t->topping?->name ?? 'Topping' }} x{{ $t->quantity }}@if(!$loop->last), @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <span class="font-semibold">{{ number_format(($item->final_price ?? $item->unit_price ?? 0) * $item->quantity, 0, ',', '.') }}đ</span>
                            </div>
                        @endforeach
                        @if($order->items->count() > 3)
                            <p class="text-label-sm text-on-surface-variant italic">... và {{ $order->items->count() - 3 }} sản phẩm khác</p>
                        @endif
                    </div>

                    <div class="flex items-center justify-between mt-auto">
                        <span class="font-label-md text-label-md text-on-surface-variant">
                            {{ $order->updated_at->diffForHumans() }}
                        </span>
                        <button
                            onclick="acceptOrder({{ $order->id }}, this)"
                            class="bg-primary text-on-primary px-lg py-sm rounded-xl font-label-md text-label-md active:scale-95 transition-transform flex items-center gap-xs">
                            <span class="material-symbols-outlined text-[18px]">check_circle</span>
                            Nhận đơn
                        </button>
                    </div>
                </div>
            @empty
                <div class="xl:col-span-2 flex flex-col items-center justify-center py-2xl text-on-surface-variant gap-md">
                    <span class="material-symbols-outlined text-[64px] opacity-30">inbox</span>
                    <p class="font-body-lg">Hiện chưa có đơn hàng nào chờ giao.</p>
                </div>
            @endforelse
        </div>

        {{-- Tab Content: Active --}}
        <div class="hidden grid grid-cols-1 gap-lg animate-in fade-in duration-500" id="content-active">
            @forelse($activeOrders as $order)
                <div class="glass-card rounded-2xl p-lg">
                    <div class="flex flex-col md:flex-row gap-lg items-start">
                        <div class="flex-1 space-y-sm">
                            <div class="flex justify-between items-center">
                                <h3 class="font-headline-md text-headline-md">
                                    #{{ $order->code }}
                                </h3>
                                <span class="bg-orange-100 text-orange-700 px-sm py-xs rounded-full font-label-sm text-label-sm">
                                    Đang giao
                                </span>
                            </div>

                            <p class="font-body-md text-body-md text-on-surface-variant">
                                <span class="material-symbols-outlined text-[16px] align-middle">person</span>
                                {{ $order->customer->user->username ?? $order->customer->full_name ?? 'Khách hàng' }}
                                &bull; {{ number_format($order->total_amount, 0, ',', '.') }}đ
                            </p>

                            @if($order->delivery_address)
                                <p class="font-body-md text-body-md text-on-surface-variant flex items-start gap-xs">
                                    <span class="material-symbols-outlined text-[18px] mt-[2px] shrink-0">location_on</span>
                                    {{ $order->delivery_address }}
                                </p>
                            @endif

                            @if($order->note)
                                <div class="bg-surface-container-low p-sm rounded-lg">
                                    <p class="font-label-sm text-label-sm text-on-surface-variant mb-xs">Ghi chú:</p>
                                    <p class="text-body-md italic">{{ $order->note }}</p>
                                </div>
                            @endif
                        </div>

                        {{-- Danh sách sản phẩm --}}
                        <div class="glass-card rounded-xl p-md min-w-[220px]">
                            <h4 class="font-title-md text-title-md mb-sm">Sản phẩm</h4>
                            <ul class="space-y-sm">
                                @foreach($order->items as $item)
                                    <li class="flex justify-between items-start py-xs border-b border-outline-variant/10 text-body-sm last:border-0">
                                        <div>
                                            <span class="text-on-surface">
                                                {{ $item->product_name ?? $item->productSize?->product?->name ?? 'Sản phẩm' }}
                                                @if($item->size_name || $item->productSize?->size)
                                                    ({{ $item->size_name ?? $item->productSize->size->name }})
                                                @endif
                                            </span>
                                            <span class="font-bold ml-sm">x{{ $item->quantity }}</span>
                                            @if($item->toppings && $item->toppings->count() > 0)
                                                <div class="text-[11px] text-on-surface-variant mt-1">
                                                    + Topping: 
                                                    @foreach($item->toppings as $index => $t)
                                                        {{ $t->topping?->name ?? 'Topping' }} x{{ $t->quantity }}@if(!$loop->last), @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                        <span class="font-semibold">{{ number_format(($item->final_price ?? $item->unit_price ?? 0) * $item->quantity, 0, ',', '.') }}đ</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    {{-- Nút cập nhật trạng thái --}}
                    <div class="mt-lg flex flex-wrap gap-md border-t border-outline-variant/20 pt-md">
                        <button onclick="updateStatus({{ $order->id }}, 'picked_up', this)"
                            class="flex-1 border border-outline px-md py-sm rounded-xl font-label-md text-label-md hover:bg-surface-container transition-colors text-center">
                            <span class="material-symbols-outlined align-middle text-[18px] mr-xs">inventory_2</span>Đã lấy hàng
                        </button>
                        <button onclick="updateStatus({{ $order->id }}, 'delivering', this)"
                            class="flex-1 border border-outline px-md py-sm rounded-xl font-label-md text-label-md hover:bg-surface-container transition-colors text-center">
                            <span class="material-symbols-outlined align-middle text-[18px] mr-xs">local_shipping</span>Đang giao
                        </button>
                        <button onclick="updateStatus({{ $order->id }}, 'completed', this)"
                            class="flex-1 bg-primary text-on-primary px-md py-sm rounded-xl font-label-md text-label-md active:scale-95 transition-transform text-center">
                            <span class="material-symbols-outlined align-middle text-[18px] mr-xs">check_circle</span>Hoàn thành
                        </button>
                        <button onclick="updateStatusWithConfirm({{ $order->id }}, 'failed', this)"
                            class="border border-red-300 text-red-600 px-md py-sm rounded-xl font-label-md text-label-md hover:bg-red-50 transition-colors text-center">
                            <span class="material-symbols-outlined align-middle text-[18px] mr-xs">cancel</span>Thất bại
                        </button>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-2xl text-on-surface-variant gap-md">
                    <span class="material-symbols-outlined text-[64px] opacity-30">local_shipping</span>
                    <p class="font-body-lg">Bạn chưa có đơn hàng nào đang giao.</p>
                    <p class="font-body-md opacity-60">Hãy nhận đơn từ tab "Đơn sẵn sàng".</p>
                </div>
            @endforelse
        </div>

        {{-- Tab Content: History --}}
        <div class="hidden animate-in fade-in duration-500" id="content-history">
            <div class="glass-card rounded-2xl overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low border-b border-outline-variant/30">
                            <th class="px-lg py-md font-label-sm text-label-sm text-on-surface-variant uppercase">Mã đơn</th>
                            <th class="px-lg py-md font-label-sm text-label-sm text-on-surface-variant uppercase">Thời gian</th>
                            <th class="px-lg py-md font-label-sm text-label-sm text-on-surface-variant uppercase hidden md:table-cell">Địa chỉ</th>
                            <th class="px-lg py-md font-label-sm text-label-sm text-on-surface-variant uppercase">Trạng thái</th>
                            <th class="px-lg py-md font-label-sm text-label-sm text-on-surface-variant uppercase hidden lg:table-cell">Ghi chú</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/10">
                        @forelse($historyItems as $history)
                            <tr class="hover:bg-surface-container-lowest transition-colors">
                                <td class="px-lg py-md font-label-md">
                                    #{{ $history->order->order_code ?? '—' }}
                                </td>
                                <td class="px-lg py-md text-on-surface-variant text-body-md">
                                    {{ $history->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-lg py-md text-on-surface-variant text-body-md hidden md:table-cell max-w-[200px] truncate">
                                    {{ $history->order->address->address ?? '—' }}
                                </td>
                                <td class="px-lg py-md">
                                    <span class="px-sm py-[2px] rounded-full text-label-sm font-medium {{ $history->status_color }}">
                                        {{ $history->status_label }}
                                    </span>
                                </td>
                                <td class="px-lg py-md text-on-surface-variant text-body-md hidden lg:table-cell">
                                    {{ $history->note ?? '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-lg py-2xl text-center text-on-surface-variant">
                                    <span class="material-symbols-outlined text-[48px] opacity-30 block mb-sm">history</span>
                                    Chưa có lịch sử giao hàng nào.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Pagination --}}
                @if($historyItems->hasPages())
                    <div class="px-lg py-md border-t border-outline-variant/20">
                        {{ $historyItems->links() }}
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

{{-- Toast Notification --}}
<div id="toast"
     class="fixed bottom-lg right-lg z-50 hidden px-lg py-md rounded-xl shadow-lg font-label-md text-label-md transition-all duration-300"
     style="min-width: 260px;">
</div>

</main>
@endsection

@push('scripts')
<script>
    const CSRF_TOKEN = '{{ csrf_token() }}';

    // =============================================
    // Tab switching
    // =============================================
    function switchTab(tabId) {
        ['available', 'active', 'history'].forEach(id => {
            document.getElementById('content-' + id).classList.add('hidden');
            const btn = document.getElementById('tab-' + id);
            btn.classList.remove('border-b-2', 'border-primary', 'text-primary', 'font-bold');
            btn.classList.add('text-on-surface-variant');
        });

        document.getElementById('content-' + tabId).classList.remove('hidden');
        const activeBtn = document.getElementById('tab-' + tabId);
        activeBtn.classList.add('border-b-2', 'border-primary', 'text-primary', 'font-bold');
        activeBtn.classList.remove('text-on-surface-variant');
    }

    window.onload = () => switchTab('available');

    // =============================================
    // Toast
    // =============================================
    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        toast.textContent = message;
        toast.className = toast.className
            .replace(/bg-\S+/g, '')
            .replace(/text-\S+/g, 'font-label-md text-label-md');

        if (type === 'success') {
            toast.classList.add('bg-green-600', 'text-white');
        } else {
            toast.classList.add('bg-red-600', 'text-white');
        }

        toast.classList.remove('hidden');
        setTimeout(() => toast.classList.add('hidden'), 3500);
    }

    // =============================================
    // Nhận đơn hàng
    // =============================================
    function acceptOrder(orderId, btn) {
        if (btn.disabled) return;
        btn.disabled = true;
        btn.textContent = 'Đang xử lý...';

        fetch(`/shipper/orders/${orderId}/accept`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
            },
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast(data.error || 'Đã có lỗi xảy ra.', 'error');
                btn.disabled = false;
                btn.textContent = 'Nhận đơn';
            }
        })
        .catch(() => {
            showToast('Lỗi kết nối, vui lòng thử lại.', 'error');
            btn.disabled = false;
            btn.textContent = 'Nhận đơn';
        });
    }

    // =============================================
    // Cập nhật trạng thái giao hàng
    // =============================================
    function updateStatus(orderId, status, btn, note = null) {
        if (btn.disabled) return;
        btn.disabled = true;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span class="material-symbols-outlined animate-spin text-[18px]">sync</span> Đang cập nhật...';

        fetch(`/shipper/orders/${orderId}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
            },
            body: JSON.stringify({ status, note }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast(data.error || 'Đã có lỗi xảy ra.', 'error');
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        })
        .catch(() => {
            showToast('Lỗi kết nối, vui lòng thử lại.', 'error');
            btn.disabled = false;
            btn.innerHTML = originalText;
        });
    }

    // Cập nhật trạng thái "Thất bại" có xác nhận trước
    function updateStatusWithConfirm(orderId, status, btn) {
        if (!confirm('Xác nhận giao hàng thất bại?\nĐơn hàng sẽ được trả lại vào danh sách chờ.')) return;
        updateStatus(orderId, status, btn);
    }
</script>
@endpush
