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
            @include('shipper.partials.available_orders_list', ['availableOrders' => $availableOrders])
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
                                    <li class="flex justify-between items-start py-xs border-b border-outline-variant/10 text-body-sm last:border-0 gap-md">
                                        <div class="flex-1">
                                            <div class="text-on-surface">
                                                {{ $item->product_name ?? $item->productSize?->product?->name ?? 'Sản phẩm' }}
                                                @if($item->size_name || $item->productSize?->size)
                                                    ({{ $item->size_name ?? $item->productSize->size->name }})
                                                @endif
                                            </div>
                                            @if($item->toppings && $item->toppings->count() > 0)
                                                <div class="text-[11px] text-on-surface-variant mt-1">
                                                    + Topping: 
                                                    @foreach($item->toppings as $index => $t)
                                                        {{ $t->topping?->name ?? 'Topping' }} x{{ $t->quantity }}@if(!$loop->last), @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                        <div class="text-right shrink-0">
                                            <span class="font-medium text-on-surface-variant mr-xs">x{{ $item->quantity }}</span>
                                            <span class="font-semibold">{{ number_format(($item->final_price ?? $item->unit_price ?? 0) * $item->quantity, 0, ',', '.') }}đ</span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        </div>
                    </div>
                    
                    {{-- Map Button & Container --}}
                    @if($order->delivery_latitude && $order->delivery_longitude)
                    <div class="mt-md border-t border-outline-variant/20 pt-md">
                        <div class="flex items-center justify-between">
                            <p class="font-label-md text-on-surface-variant flex items-center gap-xs">
                                <span class="material-symbols-outlined text-[16px]">map</span>
                                Khoảng cách: {{ $order->distance_km ?? 0 }} km ({{ $order->route_duration_minutes ?? '--' }} phút)
                            </p>
                            <div class="flex gap-sm">
                                <a href="https://www.google.com/maps/dir/?api=1&destination={{ $order->delivery_latitude }},{{ $order->delivery_longitude }}&travelmode=driving" 
                                   target="_blank" 
                                   class="px-sm py-xs border border-blue-200 text-blue-600 rounded-lg text-label-sm hover:bg-blue-50 flex items-center gap-xs">
                                    <span class="material-symbols-outlined text-[14px]">directions</span> Google Maps
                                </a>
                                <button onclick="toggleMap({{ $order->id }}, {{ $order->delivery_latitude }}, {{ $order->delivery_longitude }})"
                                    class="px-sm py-xs border border-primary text-primary rounded-lg text-label-sm hover:bg-primary/10 flex items-center gap-xs">
                                    <span class="material-symbols-outlined text-[14px]">route</span> Lộ trình
                                </button>
                            </div>
                        </div>
                        <div id="map_container_{{ $order->id }}" class="mt-sm hidden w-full h-[300px] rounded-xl overflow-hidden border border-outline z-0"></div>
                    </div>
                    @endif
                    <div class="mt-lg flex flex-wrap gap-md border-t border-outline-variant/20 pt-md">
                        <button onclick="updateStatus({{ $order->id }}, 'picked_up', this)"
                            class="flex-1 border border-outline px-md py-sm rounded-xl font-label-md text-label-md hover:bg-surface-container transition-colors text-center">
                            <span class="material-symbols-outlined align-middle text-[18px] mr-xs">inventory_2</span>Đã lấy hàng
                        </button>
                        <button onclick="updateStatus({{ $order->id }}, 'delivering', this)"
                            class="flex-1 border border-outline px-md py-sm rounded-xl font-label-md text-label-md hover:bg-surface-container transition-colors text-center">
                            <span class="material-symbols-outlined align-middle text-[18px] mr-xs">local_shipping</span>Đang giao
                        </button>
                        <button onclick="openCompleteModal({{ $order->id }}, this)"
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
            <div class="glass-card rounded-2xl overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[600px]">
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
     class="fixed bottom-8 right-8 z-50 hidden px-lg py-md rounded-xl shadow-lg font-label-md text-label-md transition-all duration-300"
     style="min-width: 260px;">
</div>

{{-- New Order Modal --}}
<div id="new-order-modal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-md bg-black/50 backdrop-blur-sm transition-opacity duration-300 opacity-0">
    <div class="bg-surface rounded-2xl shadow-xl w-full max-w-md overflow-hidden transform scale-95 transition-transform duration-300 p-lg flex flex-col gap-lg border border-outline-variant/30">
        <div class="flex items-center gap-md text-primary">
            <span class="material-symbols-outlined text-[32px] animate-bounce">notifications_active</span>
            <h2 class="font-headline-sm text-headline-sm">Có đơn hàng mới!</h2>
        </div>
        
        <div id="new-order-modal-content" class="bg-surface-container-low rounded-xl p-md flex flex-col gap-sm">
            <!-- Order details will be injected here -->
        </div>

        <div class="flex flex-col gap-sm mt-xs">
            <button id="btn-accept-new-order" class="w-full bg-primary text-on-primary py-md rounded-xl font-title-md text-title-md active:scale-95 transition-transform flex items-center justify-center gap-xs">
                <span class="material-symbols-outlined">check_circle</span> Nhận đơn ngay
            </button>
            <div class="flex gap-sm">
                <button id="btn-view-new-order" onclick="closeNewOrderModal(true)" class="flex-1 bg-secondary-container text-on-secondary-container py-sm rounded-xl font-label-lg text-label-lg active:scale-95 transition-transform flex items-center justify-center gap-xs">
                    <span class="material-symbols-outlined text-[20px]">visibility</span> Xem chi tiết
                </button>
                <button onclick="closeNewOrderModal(false)" class="flex-1 bg-surface-container-high text-on-surface py-sm rounded-xl font-label-lg text-label-lg active:scale-95 transition-transform flex items-center justify-center gap-xs hover:bg-error/10 hover:text-error">
                    <span class="material-symbols-outlined text-[20px]">close</span> Không nhận
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Complete Order Modal --}}
<div id="complete-modal" class="fixed inset-0 z-[200] hidden flex items-center justify-center p-md bg-black/50 backdrop-blur-sm transition-opacity duration-300 opacity-0">
    <div class="bg-surface rounded-2xl shadow-xl w-full max-w-sm overflow-hidden transform scale-95 transition-transform duration-300 p-lg flex flex-col gap-lg border border-outline-variant/30">
        <div class="flex flex-col items-center gap-md text-primary text-center">
            <span class="material-symbols-outlined text-[48px]">check_circle</span>
            <h2 class="font-headline-sm text-headline-sm text-on-surface">Hoàn thành đơn hàng?</h2>
            <p class="text-body-md text-on-surface-variant">Vui lòng tải lên hình ảnh xác nhận giao hàng.</p>
        </div>
        
        <div class="flex flex-col gap-sm">
            <input type="file" id="complete-proof-image" accept="image/*" class="w-full border border-outline rounded-lg p-sm font-body-md">
            <p id="complete-error" class="text-error text-label-sm hidden"></p>
        </div>
        
        <div class="flex gap-sm mt-xs">
            <button onclick="closeCompleteModal()" class="flex-1 bg-surface-container-high text-on-surface py-sm rounded-xl font-label-lg text-label-lg active:scale-95 transition-transform flex items-center justify-center">
                Huỷ bỏ
            </button>
            <button id="btn-confirm-complete" class="flex-1 bg-primary text-on-primary py-sm rounded-xl font-label-lg text-label-lg active:scale-95 transition-transform flex items-center justify-center">
                Xác nhận
            </button>
        </div>
    </div>
</div>

{{-- Confirm Cancel Modal --}}
<div id="custom-confirm-modal" class="fixed inset-0 z-[200] hidden flex items-center justify-center p-md bg-black/50 backdrop-blur-sm transition-opacity duration-300 opacity-0">
    <div class="bg-surface rounded-2xl shadow-xl w-full max-w-sm overflow-hidden transform scale-95 transition-transform duration-300 p-lg flex flex-col gap-lg border border-outline-variant/30">
        <div class="flex flex-col items-center gap-md text-error text-center">
            <span class="material-symbols-outlined text-[48px]">warning</span>
            <h2 class="font-headline-sm text-headline-sm text-on-surface">Xác nhận thất bại?</h2>
            <p class="text-body-md text-on-surface-variant">Vui lòng nhập lý do và tải lên hình ảnh xác nhận.</p>
        </div>
        
        <div class="flex flex-col gap-sm">
            <textarea id="failed-reason" rows="2" placeholder="Lý do hủy đơn..." class="w-full border border-outline rounded-lg p-sm font-body-md focus:outline-none focus:border-primary resize-none"></textarea>
            <input type="file" id="failed-proof-image" accept="image/*" class="w-full border border-outline rounded-lg p-sm font-body-md">
            <p id="failed-error" class="text-error text-label-sm hidden"></p>
        </div>
        
        <div class="flex gap-sm mt-xs">
            <button onclick="closeConfirmModal()" class="flex-1 bg-surface-container-high text-on-surface py-sm rounded-xl font-label-lg text-label-lg active:scale-95 transition-transform flex items-center justify-center">
                Huỷ bỏ
            </button>
            <button id="btn-confirm-action" class="flex-1 bg-error text-on-error py-sm rounded-xl font-label-lg text-label-lg active:scale-95 transition-transform flex items-center justify-center">
                Xác nhận
            </button>
        </div>
    </div>
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
                // Thành công -> F5 để chuyển sang tab Đang giao
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(data.error || 'Đã có lỗi xảy ra.', 'error');
                // Thất bại -> Refresh danh sách đơn chờ ngay lập tức
                pollAvailableOrders();
            }
        })
        .catch(() => {
            showToast('Lỗi kết nối, vui lòng thử lại.', 'error');
            btn.disabled = false;
            btn.innerHTML = '<span class="material-symbols-outlined text-[18px]">check_circle</span> Nhận đơn';
        });
    }

    // =============================================
    // Polling Tự Động Cập Nhật Danh Sách Đơn (Real-time)
    // =============================================
    
    // Store currently known order IDs to detect new ones
    let knownOrderIds = Array.from(document.querySelectorAll('[id^="order-card-"]')).map(el => el.id);

    function playNotificationSound() {
        try {
            // A simple beep sound using AudioContext
            const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioCtx.createOscillator();
            const gainNode = audioCtx.createGain();
            
            oscillator.connect(gainNode);
            gainNode.connect(audioCtx.destination);
            
            oscillator.type = 'sine';
            oscillator.frequency.setValueAtTime(880, audioCtx.currentTime); // A5 note
            gainNode.gain.setValueAtTime(0.1, audioCtx.currentTime);
            
            oscillator.start();
            oscillator.stop(audioCtx.currentTime + 0.15); // short beep
            
            // Second beep
            setTimeout(() => {
                const osc2 = audioCtx.createOscillator();
                const gain2 = audioCtx.createGain();
                osc2.connect(gain2);
                gain2.connect(audioCtx.destination);
                osc2.type = 'sine';
                osc2.frequency.setValueAtTime(1046.50, audioCtx.currentTime); // C6 note
                gain2.gain.setValueAtTime(0.1, audioCtx.currentTime);
                osc2.start();
                osc2.stop(audioCtx.currentTime + 0.2);
            }, 200);
        } catch (e) {
            console.log("Audio not supported or blocked");
        }
    }

    function pollAvailableOrders() {
        const fetchUrl = '/shipper/orders/available-html?_t=' + new Date().getTime();
        fetch(fetchUrl, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            cache: 'no-store',
            credentials: 'include'
        })
        .then(res => {
            if (!res.ok) throw new Error('Network response was not ok');
            return res.text();
        })
        .then(html => {
            const container = document.getElementById('content-available');
            if (container) {
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = html;
                
                // Check for new orders
                const newOrderElements = Array.from(tempDiv.querySelectorAll('[id^="order-card-"]'));
                const newOrderIds = newOrderElements.map(el => el.id);
                
                let hasNewOrder = false;
                let newestOrderId = null;
                let newestOrderCard = null;

                for (let id of newOrderIds) {
                    if (!knownOrderIds.includes(id)) {
                        hasNewOrder = true;
                        newestOrderId = id.replace('order-card-', '');
                        newestOrderCard = tempDiv.querySelector('#' + id);
                        break; // Just take the first new one
                    }
                }
                
                if (hasNewOrder && newestOrderCard) {
                    playNotificationSound();
                    showNewOrderModal(newestOrderId, newestOrderCard);
                }
                
                // Update known IDs
                knownOrderIds = newOrderIds;
                
                // Update badge count
                const count = newOrderElements.length;
                const tabBtn = document.getElementById('tab-available');
                if (tabBtn) tabBtn.innerHTML = `Đơn sẵn sàng (${count})`;

                // Update content
                container.innerHTML = html;
            }
        })
        .catch(err => console.error("Polling error:", err))
        .finally(() => {
            // Wait 3 seconds AFTER the current request finishes before polling again
            setTimeout(pollAvailableOrders, 3000);
        });
    }

    function showNewOrderModal(orderId, cardElement) {
        const modal = document.getElementById('new-order-modal');
        const content = document.getElementById('new-order-modal-content');
        
        // Extract basic info from the card
        const title = cardElement.querySelector('.font-title-lg').innerText;
        let customer = cardElement.querySelector('.font-body-md:nth-of-type(1)').innerText;
        let total = cardElement.querySelector('.bg-secondary-container').innerText;
        
        // Remove material icon text if present
        customer = customer.replace('person', '').trim();
        
        const addressEl = cardElement.querySelectorAll('.font-body-md')[1];
        let address = addressEl ? addressEl.innerText : 'Khách lấy tại quầy';
        address = address.replace('location_on', '').trim();
        
        content.innerHTML = `
            <div class="flex justify-between items-center mb-xs">
                <span class="font-title-lg text-title-lg text-primary">${title}</span>
                <span class="font-title-md text-title-md text-on-surface">${total}</span>
            </div>
            <div class="text-body-md text-on-surface-variant flex flex-col gap-xs">
                <p><strong class="text-on-surface">Khách hàng:</strong> ${customer}</p>
                <p><strong class="text-on-surface">Địa chỉ:</strong> ${address}</p>
            </div>
        `;
        
        // Setup accept button
        const acceptBtn = document.getElementById('btn-accept-new-order');
        acceptBtn.onclick = function() {
            closeNewOrderModal();
            // Automatically find the real button in the DOM and click it to reuse logic
            const realBtn = document.querySelector(`#order-card-${orderId} button`);
            if (realBtn) {
                acceptOrder(orderId, realBtn);
            }
        };

        // Show modal
        modal.classList.remove('hidden');
        // Small delay for transition
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.firstElementChild.classList.remove('scale-95');
        }, 10);
    }

    function closeNewOrderModal(scrollToOrder = false) {
        const modal = document.getElementById('new-order-modal');
        modal.classList.add('opacity-0');
        modal.firstElementChild.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            if (scrollToOrder) {
                // Switch to available tab if not active
                switchTab('available');
            }
        }, 300);
    }

    // Khởi động polling sau 3s
    setTimeout(pollAvailableOrders, 3000);

    // =============================================
    // Cập nhật trạng thái giao hàng
    // =============================================
    function updateStatus(orderId, status, btn, note = null, proofImageFile = null) {
        if (btn.disabled) return;
        btn.disabled = true;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span class="material-symbols-outlined animate-spin text-[18px]">sync</span> Đang cập nhật...';

        const formData = new FormData();
        formData.append('status', status);
        if (note) formData.append('note', note);
        if (proofImageFile) formData.append('proof_image', proofImageFile);

        fetch(`/shipper/orders/${orderId}/status`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
            },
            body: formData,
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

    // Modal Hoàn thành
    function openCompleteModal(orderId, btn) {
        const modal = document.getElementById('complete-modal');
        const fileInput = document.getElementById('complete-proof-image');
        const errorMsg = document.getElementById('complete-error');
        
        fileInput.value = '';
        errorMsg.classList.add('hidden');

        document.getElementById('btn-confirm-complete').onclick = function() {
            if (!fileInput.files || fileInput.files.length === 0) {
                errorMsg.textContent = 'Vui lòng chọn ảnh chụp xác nhận.';
                errorMsg.classList.remove('hidden');
                return;
            }
            updateStatus(orderId, 'completed', btn, null, fileInput.files[0]);
            closeCompleteModal();
        };

        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.firstElementChild.classList.remove('scale-95');
        }, 10);
    }

    function closeCompleteModal() {
        const modal = document.getElementById('complete-modal');
        modal.classList.add('opacity-0');
        modal.firstElementChild.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    // Modal Thất bại
    function updateStatusWithConfirm(orderId, status, btn) {
        const modal = document.getElementById('custom-confirm-modal');
        const fileInput = document.getElementById('failed-proof-image');
        const reasonInput = document.getElementById('failed-reason');
        const errorMsg = document.getElementById('failed-error');
        
        fileInput.value = '';
        reasonInput.value = '';
        errorMsg.classList.add('hidden');

        document.getElementById('btn-confirm-action').onclick = function() {
            if (!reasonInput.value.trim()) {
                errorMsg.textContent = 'Vui lòng nhập lý do hủy đơn.';
                errorMsg.classList.remove('hidden');
                return;
            }
            if (!fileInput.files || fileInput.files.length === 0) {
                errorMsg.textContent = 'Vui lòng chọn ảnh chụp xác nhận.';
                errorMsg.classList.remove('hidden');
                return;
            }
            updateStatus(orderId, status, btn, reasonInput.value.trim(), fileInput.files[0]);
            closeConfirmModal();
        };

        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.firstElementChild.classList.remove('scale-95');
        }, 10);
    }

    function closeConfirmModal() {
        const modal = document.getElementById('custom-confirm-modal');
        modal.classList.add('opacity-0');
        modal.firstElementChild.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    // Map functionality for active orders
    const mapInstances = {};

    function toggleMap(orderId, destLat, destLon) {
        const containerId = 'map_container_' + orderId;
        const container = document.getElementById(containerId);
        
        if (container.classList.contains('hidden')) {
            // Mở bản đồ
            container.classList.remove('hidden');
            
            if (!mapInstances[orderId]) {
                const map = L.map(containerId);
                mapInstances[orderId] = map;
                
                // Đảm bảo map render đúng kích thước sau khi bỏ hidden
                setTimeout(() => {
                    map.invalidateSize();
                }, 100);
                
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                const storeLat = {{ \App\Models\Setting::get('store_lat', '0') }};
                const storeLon = {{ \App\Models\Setting::get('store_lon', '0') }};

                const initMap = (shipperLat, shipperLon) => {
                    // 1. Store marker (Red)
                    const storeIcon = L.icon({
                        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                        iconSize: [25, 41],
                        iconAnchor: [12, 41],
                        popupAnchor: [1, -34],
                        shadowSize: [41, 41]
                    });
                    L.marker([storeLat, storeLon], {icon: storeIcon}).addTo(map).bindPopup('<b>Cửa hàng</b>');

                    // 2. Customer marker (Green)
                    const cusIcon = L.icon({
                        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                        iconSize: [25, 41],
                        iconAnchor: [12, 41],
                        popupAnchor: [1, -34],
                        shadowSize: [41, 41]
                    });
                    L.marker([destLat, destLon], {icon: cusIcon}).addTo(map).bindPopup('<b>Khách hàng</b>');

                    let routeStartLat = storeLat;
                    let routeStartLon = storeLon;

                    // 3. Shipper marker (Blue) if available
                    if (shipperLat !== null && shipperLon !== null) {
                        const shipIcon = L.icon({
                            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
                            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                            iconSize: [25, 41],
                            iconAnchor: [12, 41],
                            popupAnchor: [1, -34],
                            shadowSize: [41, 41]
                        });
                        L.marker([shipperLat, shipperLon], {icon: shipIcon}).addTo(map).bindPopup('<b>Vị trí của bạn (Shipper)</b>');
                        routeStartLat = shipperLat;
                        routeStartLon = shipperLon;
                    }

                    // Fit bounds to include all markers
                    setTimeout(() => {
                        const bounds = L.latLngBounds([
                            [storeLat, storeLon],
                            [destLat, destLon]
                        ]);
                        if (shipperLat !== null && shipperLon !== null) {
                            bounds.extend([shipperLat, shipperLon]);
                        }
                        map.fitBounds(bounds, {padding: [30, 30]});
                    }, 200);

                    // Fetch route from start (Shipper or Store) to Customer
                    fetch(`https://router.project-osrm.org/route/v1/driving/${routeStartLon},${routeStartLat};${destLon},${destLat}?overview=full&geometries=geojson`)
                        .then(res => res.json())
                        .then(data => {
                            if(data.routes && data.routes[0]) {
                                L.geoJSON(data.routes[0].geometry, {
                                    style: { color: '#006e1c', weight: 4, opacity: 0.8 }
                                }).addTo(map);
                            }
                        })
                        .catch(e => console.error('Lỗi lấy đường đi:', e));
                };

                // Lấy vị trí shipper nếu có thể
                if (navigator.geolocation) {
                    const toast = document.createElement('div');
                    toast.className = 'fixed top-4 left-1/2 -translate-x-1/2 bg-blue-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 transition-opacity';
                    toast.innerText = 'Đang tìm vị trí GPS của bạn...';
                    document.body.appendChild(toast);

                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            toast.remove();
                            initMap(position.coords.latitude, position.coords.longitude);
                        },
                        (error) => {
                            toast.remove();
                            let errorMsg = 'Không thể lấy vị trí shipper.';
                            if(error.code === 1) errorMsg = 'Bạn đã từ chối quyền truy cập vị trí.';
                            if(error.code === 2) errorMsg = 'Không có tín hiệu GPS/Vị trí.';
                            if(error.code === 3) errorMsg = 'Quá thời gian tìm vị trí.';
                            console.warn(errorMsg);
                            initMap(null, null);
                        },
                        { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                    );
                } else {
                    initMap(null, null);
                }
            } else {
                // Resize map nếu container thay đổi
                setTimeout(() => {
                    mapInstances[orderId].invalidateSize();
                }, 100);
            }
        } else {
            // Đóng bản đồ
            container.classList.add('hidden');
        }
    }
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
@endpush
