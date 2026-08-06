@extends('layouts.customer')

@section('title', 'Lịch Sử Đơn Hàng')

@section('content')
<main class="mt-24 pb-24 max-w-container-max mx-auto px-4 md:px-lg font-sans">
    <div class="mb-xl flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="font-headline-lg text-headline-lg text-on-background font-bold">Đơn hàng của tôi</h1>
            <p class="font-body-md text-body-md text-on-surface-variant">Theo dõi tiến độ, xem lại hóa đơn và thanh toán VietQR tiện lợi.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-md p-md bg-emerald-100 text-emerald-800 rounded-2xl font-body-md flex items-center gap-sm border border-emerald-300 shadow-sm">
            <span class="material-symbols-outlined text-emerald-600">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    @if($orders->isEmpty())
        <div class="text-center py-2xl bg-surface-container-lowest rounded-2xl border border-outline-variant/10 shadow-sm">
            <span class="material-symbols-outlined text-[80px] text-outline-variant">receipt_long</span>
            <h2 class="font-headline-md text-headline-md text-on-surface mt-md">Chưa có đơn hàng nào</h2>
            <p class="text-on-surface-variant font-body-md mt-xs mb-xl">Hãy chọn những món quà thơm ngon từ CozyHNA nhé!</p>
            <a href="/" class="bg-primary text-white px-xl py-md rounded-xl font-bold hover:bg-primary/90 transition-all shadow-md">Xem thực đơn</a>
        </div>
    @else
        <div class="space-y-lg">
            @foreach($orders as $order)
            @php
                $statusUpper = strtoupper($order->order_status ?? $order->status ?? 'PENDING');
                $isUnpaid = ($order->payment && $order->payment->payment_status === 'PENDING') || $statusUpper === 'PENDING';
            @endphp
            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/15 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                {{-- Order Header --}}
                <div class="p-lg border-b border-outline-variant/10 bg-surface-container-low/30 flex flex-col sm:flex-row sm:items-center justify-between gap-md">
                    <div>
                        <p class="font-label-md text-label-md text-on-surface-variant mb-xs">Mã đơn hàng</p>
                        <p class="font-title-lg text-title-lg font-bold tracking-wider text-primary">{{ $order->code }}</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        @if($statusUpper === 'PENDING')
                            <span class="px-md py-xs rounded-full font-label-md text-label-md font-bold bg-amber-100 text-amber-800">Chờ xác nhận</span>
                        @elseif($statusUpper === 'CONFIRMED' || $statusUpper === 'PREPARING')
                            <span class="px-md py-xs rounded-full font-label-md text-label-md font-bold bg-blue-100 text-blue-800">Đang pha chế</span>
                        @elseif($statusUpper === 'READY_FOR_DELIVERY')
                            <span class="px-md py-xs rounded-full font-label-md text-label-md font-bold bg-purple-100 text-purple-800">Chờ giao hàng</span>
                        @elseif($statusUpper === 'DELIVERING' || $statusUpper === 'SHIPPING')
                            <span class="px-md py-xs rounded-full font-label-md text-label-md font-bold bg-indigo-100 text-indigo-800">Đang giao hàng</span>
                        @elseif($statusUpper === 'COMPLETED')
                            <span class="px-md py-xs rounded-full font-label-md text-label-md font-bold bg-emerald-100 text-emerald-800">Hoàn thành</span>
                        @else
                            <span class="px-md py-xs rounded-full font-label-md text-label-md font-bold bg-red-100 text-red-800">Đã hủy</span>
                        @endif

                        @if($isUnpaid && $statusUpper !== 'CANCELLED' && $statusUpper !== 'COMPLETED')
                            <button onclick="openVietQrModal('{{ $order->code }}')" 
                                class="px-3 py-1.5 rounded-full bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold text-xs flex items-center gap-1 shadow-sm hover:scale-105 active:scale-95 transition-all">
                                <span class="material-symbols-outlined text-sm">qr_code_2</span>
                                Thanh toán VietQR
                            </button>
                        @endif

                        @if(in_array($statusUpper, ['PENDING', 'CONFIRMED']))
                            <button onclick="cancelOrder({{ $order->id }}, this)"
                                class="px-md py-xs rounded-full border border-red-300 text-red-600 font-label-md text-label-md hover:bg-red-50 transition-colors">
                                Hủy đơn
                            </button>
                        @endif

                        @if($statusUpper === 'COMPLETED')
                            @if(in_array($order->id, $reviewedOrderIds))
                                <span class="px-md py-xs rounded-full border border-emerald-300 text-emerald-700 font-label-md text-label-md bg-emerald-50">
                                    Đã đánh giá
                                </span>
                            @else
                                <a href="{{ route('orders.review', $order->id) }}" class="px-md py-xs rounded-full bg-primary text-on-primary font-label-md text-label-md hover:bg-primary/90 transition-colors flex items-center gap-1 shadow-sm">
                                    <span class="material-symbols-outlined text-sm">star</span>
                                    Đánh giá
                                </a>
                            @endif
                        @endif
                    </div>
                </div>

                {{-- Order Items --}}
                <div class="p-lg">
                    <div class="space-y-sm mb-lg">
                        @foreach($order->items as $item)
                        <div class="flex items-center gap-md">
                            <div class="w-14 h-14 rounded-xl bg-surface-container overflow-hidden flex-shrink-0 border border-outline-variant/10">
                                @if(isset($item->product_image) && $item->product_image)
                                    <img class="w-full h-full object-cover" src="{{ $item->product_image }}" alt="{{ $item->product_name }}"/>
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-amber-50 text-amber-700">
                                        <span class="material-symbols-outlined text-[24px]">local_cafe</span>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow">
                                <p class="font-body-lg text-body-lg font-bold text-on-surface">{{ $item->product_name ?? 'Sản phẩm' }}</p>
                                <p class="font-label-md text-label-md text-on-surface-variant">
                                    {{ $item->size_name ? 'Size ' . $item->size_name : '' }} × {{ $item->quantity }}
                                </p>
                                @if(isset($item->toppings) && count($item->toppings) > 0)
                                    <p class="text-xs text-emerald-700 mt-0.5">
                                        + Topping: {{ collect($item->toppings)->pluck('topping_name')->join(', ') }}
                                    </p>
                                @endif
                            </div>
                            <p class="font-body-lg text-body-lg font-bold text-primary flex-shrink-0">
                                {{ number_format($item->unit_price * $item->quantity, 0, ',', '.') }} đ
                            </p>
                        </div>
                        @endforeach
                    </div>

                    {{-- Order Footer --}}
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-sm pt-md border-t border-outline-variant/10">
                        <div>
                            <p class="font-label-md text-label-md text-on-surface-variant">
                                Nơi giao: <b>{{ $order->receiver_name }}</b> ({{ $order->receiver_phone }}) - {{ $order->delivery_address }}
                            </p>
                            <p class="font-label-sm text-label-sm text-on-surface-variant/70 mt-0.5">
                                Thời gian đặt: {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}
                            </p>
                        </div>
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

<!-- VietQR Payment Modal -->
<div id="vietqr-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm transition-all duration-300">
    <div class="bg-surface-container-lowest rounded-3xl max-w-md w-full p-6 shadow-2xl border border-outline-variant/20 relative transform scale-95 transition-transform duration-200" id="vietqr-card">
        <!-- Close button -->
        <button onclick="closeVietQrModal()" class="absolute top-4 right-4 text-on-surface-variant hover:text-on-surface p-1 rounded-full hover:bg-surface-container">
            <span class="material-symbols-outlined">close</span>
        </button>

        <div class="text-center">
            <div class="inline-flex items-center gap-2 bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full text-xs font-bold mb-2">
                <span class="material-symbols-outlined text-base">qr_code_scanner</span>
                Thanh Toán Chuyển Khoản VietQR
            </div>
            <h3 class="font-bold text-xl text-on-surface" id="modal-order-code">Đang tải mã QR...</h3>
            <p class="text-xs text-on-surface-variant mt-1">Quét mã bằng ứng dụng Ngân hàng (MBBank, VCB, MoMo...)</p>

            <!-- QR Image Box -->
            <div class="my-4 p-3 bg-white rounded-2xl border-2 border-emerald-500/30 inline-block shadow-inner relative group">
                <img id="vietqr-img" src="" alt="Mã VietQR Thanh toán" class="w-56 h-56 object-contain rounded-lg mx-auto min-h-[220px] bg-gray-50"/>
                <div id="qr-loading" class="absolute inset-0 flex items-center justify-center bg-white/90 rounded-lg">
                    <span class="material-symbols-outlined text-emerald-600 text-3xl animate-spin">sync</span>
                </div>
            </div>

            <!-- Transfer Info Cards -->
            <div class="space-y-2 text-left bg-surface-container-low p-3.5 rounded-2xl border border-outline-variant/30 text-xs">
                <div class="flex justify-between items-center">
                    <span class="text-on-surface-variant">Ngân hàng:</span>
                    <span class="font-bold text-on-surface" id="modal-bank-name">MBBank</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-on-surface-variant">Số tài khoản:</span>
                    <div class="flex items-center gap-1">
                        <span class="font-bold font-mono text-emerald-700 text-sm" id="modal-account-no">0987654321</span>
                        <button onclick="copyText('modal-account-no')" class="p-1 text-primary hover:bg-primary/10 rounded" title="Sao chép">
                            <span class="material-symbols-outlined text-sm">content_copy</span>
                        </button>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-on-surface-variant">Chủ tài khoản:</span>
                    <span class="font-bold text-on-surface" id="modal-account-name">COZYHNA COFFEE AND TEA</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-on-surface-variant">Số tiền:</span>
                    <span class="font-bold text-primary text-sm" id="modal-amount">0 đ</span>
                </div>
                <div class="flex justify-between items-center bg-amber-50 p-2 rounded-xl border border-amber-200">
                    <span class="text-amber-900 font-semibold">Nội dung chuyển:</span>
                    <div class="flex items-center gap-1">
                        <span class="font-bold font-mono text-amber-900 text-sm" id="modal-transfer-note">ORD-XXXX</span>
                        <button onclick="copyText('modal-transfer-note')" class="p-1 text-amber-900 hover:bg-amber-200 rounded" title="Sao chép">
                            <span class="material-symbols-outlined text-sm">content_copy</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Action buttons -->
            <div class="mt-4 space-y-2">
                <button onclick="confirmVietQrPayment()" id="btn-confirm-payment"
                    class="w-full py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl active:scale-98 transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">check_circle</span>
                    Tôi đã chuyển khoản xong
                </button>
                <button onclick="closeVietQrModal()" class="w-full py-2 text-xs text-on-surface-variant hover:text-on-surface">
                    Thanh toán sau
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let currentQrOrderCode = null;

async function openVietQrModal(orderCode) {
    currentQrOrderCode = orderCode;
    const modal = document.getElementById('vietqr-modal');
    const loading = document.getElementById('qr-loading');
    const qrImg = document.getElementById('vietqr-img');

    modal.classList.remove('hidden');
    loading.classList.remove('hidden');

    try {
        const response = await fetch(`/payment/qr/${orderCode}`);
        const data = await response.json();

        if (data.success) {
            document.getElementById('modal-order-code').innerText = `Đơn hàng #${data.order_code}`;
            document.getElementById('modal-bank-name').innerText = data.bank_name;
            document.getElementById('modal-account-no').innerText = data.account_no;
            document.getElementById('modal-account-name').innerText = data.account_name;
            document.getElementById('modal-amount').innerText = data.formatted_amount;
            document.getElementById('modal-transfer-note').innerText = data.transfer_note;

            qrImg.src = data.qr_image;
            qrImg.onload = () => loading.classList.add('hidden');
        } else {
            alert(data.error || 'Có lỗi tải mã VietQR');
            closeVietQrModal();
        }
    } catch (e) {
        alert('Có lỗi mạng khi lấy mã VietQR');
        closeVietQrModal();
    }
}

function closeVietQrModal() {
    document.getElementById('vietqr-modal').classList.add('hidden');
}

function copyText(elementId) {
    const text = document.getElementById(elementId).innerText;
    navigator.clipboard.writeText(text);
    alert('Đã sao chép: ' + text);
}

async function confirmVietQrPayment() {
    if (!currentQrOrderCode) return;
    const btn = document.getElementById('btn-confirm-payment');
    btn.disabled = true;
    btn.innerText = 'Đang xác nhận...';

    try {
        const response = await fetch(`/payment/confirm/${currentQrOrderCode}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        const res = await response.json();
        if (res.success) {
            alert(res.message);
            location.reload();
        } else {
            alert(res.error || 'Xác nhận thất bại.');
            btn.disabled = false;
            btn.innerText = 'Tôi đã chuyển khoản xong';
        }
    } catch (e) {
        alert('Lỗi mạng khi xác nhận thanh toán.');
        btn.disabled = false;
        btn.innerText = 'Tôi đã chuyển khoản xong';
    }
}

function cancelOrder(orderId, btn) {
    if (!confirm('Bạn có chắc muốn hủy đơn hàng này không?')) return;

    btn.disabled = true;
    btn.innerText = 'Đang hủy...';

    fetch(`/customer/orders/${orderId}/cancel`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(r => r.json())
    .then(res => {
        if (res.success || res.cancel_success) {
            alert(res.message || 'Đã hủy đơn hàng');
            location.reload();
        } else {
            alert(res.error || 'Có lỗi xảy ra.');
            btn.disabled = false;
            btn.innerText = 'Hủy đơn';
        }
    })
    .catch(() => {
        alert('Đã xử lý hủy đơn.');
        location.reload();
    });
}

// Auto open VietQR modal if redirected from checkout
@if(session('show_vietqr'))
    document.addEventListener('DOMContentLoaded', () => {
        openVietQrModal('{{ session('show_vietqr') }}');
    });
@endif
</script>
@endpush
