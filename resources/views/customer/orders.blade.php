@extends('layouts.customer')

@section('title', 'Đơn hàng')

@section('content')
<main class="pt-24 pb-32 px-4 md:px-lg max-w-container-max mx-auto">
<!-- Header & Tabs -->
<div class="mb-xl flex flex-col md:flex-row md:items-end justify-between gap-md">
<div>
<h1 class="font-headline-lg text-headline-lg text-on-surface">Your Đơn hàng</h1>
<p class="text-on-surface-variant mt-1">Track, manage, and reorder your favorite brews.</p>
</div>
<div class="flex bg-surface-container p-1 rounded-xl w-fit">
<button class="px-xl py-2 rounded-lg font-label-md text-label-md transition-all bg-surface-container-lowest text-primary shadow-sm active-tab-indicator" id="btn-active" onclick="switchTab('active')">Đơn hàng hiện tại</button>
<button class="px-xl py-2 rounded-lg font-label-md text-label-md transition-all text-on-surface-variant hover:text-primary" id="btn-history" onclick="switchTab('history')">Lịch sử đơn hàng</button>
</div>
</div>

@if(session('success'))
<div class="mb-lg p-4 bg-green-100 text-green-700 rounded-xl border border-green-200">
    {{ session('success') }}
</div>
@endif

@if(session('cancel_success'))
<div class="mb-lg p-4 bg-red-100 text-red-700 rounded-xl border border-red-200 font-bold">
    {{ session('cancel_success') }}
</div>
@endif

@if(session('error'))
<div class="mb-lg p-4 bg-red-100 text-red-700 rounded-xl border border-red-200">
    {{ session('error') }}
</div>
@endif

<!-- Hoạt động Đơn hàng Giâytion -->
<section class="space-y-gutter" id="section-active">
    @if($activeOrders->isEmpty())
        <div class="flex flex-col items-center justify-center py-2xl text-center space-y-md">
            <h3 class="font-headline-md text-headline-md text-on-surface-variant">Chưa có đơn hàng nào đang hoạt động</h3>
        </div>
    @else
        @foreach($activeOrders as $order)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter mb-xl">
            <!-- Trạng thái Timeline -->
            <div class="lg:col-span-2 bg-white rounded-xl border border-outline-variant/30 shadow-sm p-lg overflow-hidden relative h-fit">
                <div class="flex justify-between items-start mb-xl">
                    <div>
                        <span class="bg-primary/10 text-primary px-3 py-1 rounded-full text-label-sm font-label-sm uppercase tracking-wider mb-2 inline-block">Order #{{ $order->order_code }}</span>
                        <h2 class="font-title-lg text-title-lg">Trạng thái: 
                            @if($order->status == 'pending') Chờ xác nhận
                            @elseif($order->status == 'confirmed') Đã xác nhận
                            @elseif($order->status == 'preparing') Đang chuẩn bị
                            @elseif($order->status == 'delivering') Đang giao hàng
                            @else {{ ucfirst($order->status) }} @endif
                        </h2>
                    </div>
                    <div class="text-right flex flex-col items-end gap-2">
                        <div>
                            <p class="text-label-sm font-label-sm text-on-surface-variant">NGÀY ĐẶT</p>
                            <p class="text-headline-md font-headline-md text-primary">{{ \Carbon\Carbon::parse($order->created_at)->format('H:i, d/m/Y') }}</p>
                        </div>
                        @if(in_array($order->status, ['pending', 'confirmed']))
                        <button type="button" onclick="openCancelModal({{ $order->id }})" class="px-4 py-2 bg-error/10 text-error hover:bg-error hover:text-white rounded-lg text-sm font-bold transition-colors">
                            Hủy đơn hàng
                        </button>
                        @endif
                        @if(\Illuminate\Support\Facades\Storage::disk('public')->exists('invoices/' . $order->order_code . '.pdf'))
                        <a href="/orders/invoice/{{ $order->order_code }}" target="_blank" class="px-4 py-2 bg-primary/10 text-primary hover:bg-primary hover:text-white rounded-lg text-sm font-bold transition-colors mt-2 text-center inline-block">
                            <i class="fa-solid fa-file-pdf mr-1"></i> Xem hóa đơn
                        </a>
                        @endif
                    </div>
                </div>
                <!-- Timeline -->
                @php
                    $progress = 0;
                    if (in_array($order->status, ['confirmed', 'preparing'])) $progress = 33;
                    if ($order->status == 'delivering') $progress = 66;
                    if ($order->status == 'completed') $progress = 100;
                @endphp
                <div class="relative flex justify-between items-center px-4 py-8 mt-lg mb-md">
                    <div class="absolute top-1/2 left-0 w-full h-1 bg-surface-container -translate-y-1/2 -z-10"></div>
                    <div class="absolute top-1/2 left-0 h-1 bg-primary -translate-y-1/2 -z-10 transition-all duration-1000" style="width: {{ $progress }}%;"></div>
                    
                    <div class="flex flex-col items-center gap-xs">
                        <div class="w-10 h-10 rounded-full {{ $progress >= 0 ? 'bg-primary text-white' : 'bg-surface-container text-on-surface-variant' }} flex items-center justify-center shadow-md">
                            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">receipt</span>
                        </div>
                        <span class="font-label-md text-label-md {{ $progress >= 0 ? 'text-primary font-bold' : 'text-on-surface-variant' }}">Đã nhận</span>
                    </div>
                    
                    <div class="flex flex-col items-center gap-xs">
                        <div class="w-10 h-10 rounded-full {{ $progress >= 33 ? 'bg-primary text-white' : 'bg-surface-container text-on-surface-variant' }} flex items-center justify-center shadow-md">
                            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">coffee_maker</span>
                        </div>
                        <span class="font-label-md text-label-md {{ $progress >= 33 ? 'text-primary font-bold' : 'text-on-surface-variant' }}">Chuẩn bị</span>
                    </div>
                    
                    <div class="flex flex-col items-center gap-xs">
                        <div class="w-10 h-10 rounded-full {{ $progress >= 66 ? 'bg-primary text-white' : 'bg-surface-container text-on-surface-variant' }} flex items-center justify-center shadow-md">
                            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">delivery_dining</span>
                        </div>
                        <span class="font-label-md text-label-md {{ $progress >= 66 ? 'text-primary font-bold' : 'text-on-surface-variant' }}">Đang giao</span>
                    </div>
                </div>
            </div>
            
            <!-- Order Details Panel -->
            <div class="bg-white rounded-xl border border-outline-variant/30 shadow-sm overflow-hidden h-fit">
                <div class="p-lg border-b border-outline-variant/30 flex justify-between items-center bg-surface-container-lowest">
                    <h3 class="font-title-lg text-title-lg">Chi Tiết Đơn Hàng</h3>
                </div>
                <div class="grid grid-cols-1 gap-xl p-lg">
                    <div class="space-y-md">
                        @foreach($order->items as $item)
                        <div class="flex gap-md">
                            <div class="w-16 h-16 rounded-lg overflow-hidden flex-shrink-0 bg-surface-container">
                                <img class="w-full h-full object-cover" src="{{ $item->product_image ?: 'https://placehold.co/100' }}"/>
                            </div>
                            <div class="flex-grow">
                                <div class="flex justify-between">
                                    <p class="font-bold">{{ $item->product_name }}</p>
                                    <p class="text-primary font-bold">{{ number_format($item->total_price, 0, ',', '.') }}đ</p>
                                </div>
                                <p class="text-on-surface-variant text-body-md">Size: {{ $item->size_name }} (x{{ $item->quantity }})</p>
                            </div>
                        </div>
                        @endforeach
                        
                        <div class="pt-md border-t border-outline-variant/30">
                            <div class="flex justify-between text-body-md text-on-surface-variant mb-1">
                                <span>Tạm tính</span>
                                <span>{{ number_format($order->subtotal, 0, ',', '.') }}đ</span>
                            </div>
                            <div class="flex justify-between text-body-md text-on-surface-variant mb-1">
                                <span>Giảm giá</span>
                                <span>-{{ number_format($order->discount_amount, 0, ',', '.') }}đ</span>
                            </div>
                            <div class="flex justify-between text-body-md text-on-surface-variant mb-2">
                                <span>Phí giao hàng</span>
                                <span>{{ number_format($order->shipping_fee, 0, ',', '.') }}đ</span>
                            </div>
                            <div class="flex justify-between font-bold text-title-lg mt-xs text-primary pt-2 border-t border-outline-variant/30">
                                <span>Tổng cộng</span>
                                <span>{{ number_format($order->total_amount, 0, ',', '.') }}đ</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-surface-container-low rounded-b-xl p-lg space-y-md border-t border-outline-variant/30">
                    <div>
                        <p class="text-label-sm font-label-sm text-on-surface-variant uppercase mb-1">Địa Chỉ Giao Hàng</p>
                        <p class="text-body-md">{{ $order->address ? $order->address->address : 'Không có thông tin' }}</p>
                    </div>
                    <div>
                        <p class="text-label-sm font-label-sm text-on-surface-variant uppercase mb-1">Phương Thức Thanh Toán</p>
                        <div class="flex items-center gap-xs">
                            <span class="material-symbols-outlined text-primary">credit_card</span>
                            <p class="text-body-md uppercase">{{ $order->payment_method }}</p>
                        </div>
                    </div>
                    @if($order->note)
                    <div class="bg-white p-md rounded-lg border border-outline-variant/20">
                        <p class="text-label-sm font-label-sm text-on-surface-variant uppercase mb-1">Ghi chú</p>
                        <p class="text-body-md italic">"{{ $order->note }}"</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    @endif
</section>
<!-- History Giâytion (Hidden by default) -->
<section class="hidden space-y-md" id="section-history">
    @if($historyOrders->isEmpty())
        <div class="flex flex-col items-center justify-center py-2xl text-center space-y-md">
            <h3 class="font-headline-md text-headline-md text-on-surface-variant">Chưa có lịch sử đơn hàng</h3>
        </div>
    @else
        @foreach($historyOrders as $order)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter mb-xl">
            <!-- Order Details Panel -->
            <div class="lg:col-span-3 bg-white rounded-xl border border-outline-variant/30 shadow-sm overflow-hidden h-fit">
                <div class="p-lg border-b border-outline-variant/30 flex justify-between items-center bg-surface-container-lowest">
                    <div>
                        <span class="{{ $order->status == 'completed' ? 'bg-green-100 text-secondary' : 'bg-red-100 text-error' }} px-3 py-1 rounded-full text-label-sm font-label-sm uppercase tracking-wider mb-2 inline-block">Order #{{ $order->order_code }}</span>
                        <h3 class="font-title-lg text-title-lg">Trạng thái: 
                            @if($order->status == 'completed') Hoàn thành
                            @else <span class="text-error font-bold">Đã hủy</span>
                            @endif
                        </h3>
                        @if($order->status == 'cancelled')
                        <p class="text-error text-sm mt-1">Cảm ơn bạn đã góp ý kiến.</p>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="text-label-sm font-label-sm text-on-surface-variant">NGÀY ĐẶT</p>
                        <p class="text-headline-md font-headline-md {{ $order->status == 'completed' ? 'text-primary' : 'text-error' }}">{{ \Carbon\Carbon::parse($order->created_at)->format('H:i, d/m/Y') }}</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 gap-xl p-lg">
                    <div class="space-y-md">
                        @foreach($order->items as $item)
                        <div class="flex gap-md">
                            <div class="w-16 h-16 rounded-lg overflow-hidden flex-shrink-0 bg-surface-container">
                                <img class="w-full h-full object-cover" src="{{ $item->product_image ?: 'https://placehold.co/100' }}"/>
                            </div>
                            <div class="flex-grow">
                                <div class="flex justify-between">
                                    <p class="font-bold">{{ $item->product_name }}</p>
                                    <p class="text-primary font-bold">{{ number_format($item->total_price, 0, ',', '.') }}đ</p>
                                </div>
                                <div class="flex justify-between items-center mt-1">
                                    <p class="text-on-surface-variant text-body-md">Size: {{ $item->size_name }} (x{{ $item->quantity }})</p>
                                    @if($order->status == 'completed')
                                        @if($item->is_reviewed)
                                        <span class="px-3 py-1 bg-surface-container-high text-on-surface-variant rounded text-xs font-bold">Đã đánh giá</span>
                                        @else
                                        <button onclick="openReviewModal({{ $order->id }}, {{ $item->product_id }}, '{{ $item->product_name }}')" class="px-3 py-1 bg-primary/10 text-primary hover:bg-primary hover:text-white rounded text-xs font-bold transition-colors">Đánh giá</button>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                        
                        <div class="pt-md border-t border-outline-variant/30">
                            <div class="flex justify-between text-body-md text-on-surface-variant mb-1">
                                <span>Tạm tính</span>
                                <span>{{ number_format($order->subtotal, 0, ',', '.') }}đ</span>
                            </div>
                            <div class="flex justify-between text-body-md text-on-surface-variant mb-1">
                                <span>Giảm giá</span>
                                <span>-{{ number_format($order->discount_amount, 0, ',', '.') }}đ</span>
                            </div>
                            <div class="flex justify-between text-body-md text-on-surface-variant mb-2">
                                <span>Phí giao hàng</span>
                                <span>{{ number_format($order->shipping_fee, 0, ',', '.') }}đ</span>
                            </div>
                            <div class="flex justify-between font-bold text-title-lg mt-xs text-primary pt-2 border-t border-outline-variant/30">
                                <span>Tổng cộng</span>
                                <span>{{ number_format($order->total_amount, 0, ',', '.') }}đ</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-surface-container-low rounded-b-xl p-lg space-y-md border-t border-outline-variant/30">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                        <div>
                            <p class="text-label-sm font-label-sm text-on-surface-variant uppercase mb-1">Địa Chỉ Giao Hàng</p>
                            <p class="text-body-md">{{ $order->address ? $order->address->address : 'Không có thông tin' }}</p>
                        </div>
                        <div>
                            <p class="text-label-sm font-label-sm text-on-surface-variant uppercase mb-1">Phương Thức Thanh Toán</p>
                            <div class="flex items-center gap-xs">
                                <span class="material-symbols-outlined text-primary">credit_card</span>
                                <p class="text-body-md uppercase">{{ $order->payment_method }}</p>
                            </div>
                        </div>
                    </div>
                    @if($order->note)
                    <div class="bg-white p-md rounded-lg border border-outline-variant/20 mt-2">
                        <p class="text-label-sm font-label-sm text-on-surface-variant uppercase mb-1">Ghi chú</p>
                        <p class="text-body-md italic text-on-surface">"{{ $order->note }}"</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    @endif
</section>
</main>

<!-- Review Modal -->
<div id="reviewModal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 relative">
        <button onclick="closeReviewModal()" class="absolute top-4 right-4 text-on-surface-variant hover:text-error">
            <span class="material-symbols-outlined">close</span>
        </button>
        <h3 class="text-title-lg font-bold mb-4">Đánh giá sản phẩm</h3>
        <p id="reviewProductName" class="text-primary font-bold mb-4"></p>
        
        <form action="/customer/reviews" method="POST">
            @csrf
            <input type="hidden" name="order_id" id="reviewOrderId">
            <input type="hidden" name="product_id" id="reviewProductId">
            
            <div class="mb-4">
                <label class="block text-label-md font-bold mb-2">Đánh giá của bạn</label>
                <div class="flex gap-2 text-2xl cursor-pointer" id="starRating">
                    <span class="material-symbols-outlined text-outline-variant star" data-value="1" style="font-variation-settings: 'FILL' 1;">star</span>
                    <span class="material-symbols-outlined text-outline-variant star" data-value="2" style="font-variation-settings: 'FILL' 1;">star</span>
                    <span class="material-symbols-outlined text-outline-variant star" data-value="3" style="font-variation-settings: 'FILL' 1;">star</span>
                    <span class="material-symbols-outlined text-outline-variant star" data-value="4" style="font-variation-settings: 'FILL' 1;">star</span>
                    <span class="material-symbols-outlined text-outline-variant star" data-value="5" style="font-variation-settings: 'FILL' 1;">star</span>
                </div>
                <input type="hidden" name="rating" id="reviewRating" value="5" required>
            </div>
            
            <div class="mb-4">
                <label class="block text-label-md font-bold mb-2">Bình luận (Tùy chọn)</label>
                <textarea name="comment" class="w-full p-3 border border-outline-variant rounded-lg focus:border-primary focus:ring-0" rows="3" placeholder="Chia sẻ cảm nhận của bạn về sản phẩm..."></textarea>
            </div>
            
            <button type="submit" class="w-full py-3 bg-primary text-on-primary rounded-xl font-bold">Gửi đánh giá</button>
        </form>
    </div>
</div>

<!-- Cancel Order Modal -->
<div id="cancelModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50" onclick="closeCancelModal()"></div>
    <div class="bg-surface w-full max-w-md rounded-2xl p-6 relative z-10 mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold">Lý do hủy đơn hàng</h3>
            <button onclick="closeCancelModal()" class="text-on-surface-variant hover:text-on-surface">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form id="cancelForm" method="POST" action="">
            @csrf
            <div class="space-y-3 mb-4">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="radio" name="cancel_reason" value="Muốn thay đổi địa chỉ nhận hàng" class="text-primary focus:ring-primary h-4 w-4" required onchange="toggleOtherReason()">
                    <span>Muốn thay đổi địa chỉ nhận hàng</span>
                </label>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="radio" name="cancel_reason" value="Muốn thay đổi món/số lượng" class="text-primary focus:ring-primary h-4 w-4" onchange="toggleOtherReason()">
                    <span>Muốn thay đổi món/số lượng</span>
                </label>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="radio" name="cancel_reason" value="Thời gian giao hàng quá lâu" class="text-primary focus:ring-primary h-4 w-4" onchange="toggleOtherReason()">
                    <span>Thời gian giao hàng quá lâu</span>
                </label>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="radio" name="cancel_reason" value="Lý do khác" id="radioOtherReason" class="text-primary focus:ring-primary h-4 w-4" onchange="toggleOtherReason()">
                    <span>Lý do khác</span>
                </label>
            </div>
            
            <div id="otherReasonDiv" class="hidden mb-4">
                <textarea id="otherReasonText" class="w-full p-3 border border-outline-variant rounded-lg focus:border-primary focus:ring-0" rows="3" placeholder="Nhập lý do cụ thể..."></textarea>
            </div>
            
            <button type="submit" class="w-full py-3 bg-error text-white rounded-xl font-bold mt-2">Xác nhận hủy</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openReviewModal(orderId, productId, productName) {
        document.getElementById('reviewOrderId').value = orderId;
        document.getElementById('reviewProductId').value = productId;
        document.getElementById('reviewProductName').innerText = productName;
        document.getElementById('reviewModal').classList.remove('hidden');
        setRating(5); // default to 5 stars
    }

    function closeReviewModal() {
        document.getElementById('reviewModal').classList.add('hidden');
    }

    function setRating(rating) {
        document.getElementById('reviewRating').value = rating;
        const stars = document.querySelectorAll('#starRating .star');
        stars.forEach(star => {
            if (parseInt(star.dataset.value) <= rating) {
                star.classList.remove('text-outline-variant');
                star.classList.add('text-[#FFD700]'); // Gold color
            } else {
                star.classList.add('text-outline-variant');
                star.classList.remove('text-[#FFD700]');
            }
        });
    }

    document.querySelectorAll('#starRating .star').forEach(star => {
        star.addEventListener('click', function() {
            setRating(parseInt(this.dataset.value));
        });
    });

    function openCancelModal(orderId) {
        document.getElementById('cancelForm').action = '/customer/orders/' + orderId + '/cancel';
        document.getElementById('cancelModal').classList.remove('hidden');
    }

    function closeCancelModal() {
        document.getElementById('cancelModal').classList.add('hidden');
    }

    function toggleOtherReason() {
        const isOther = document.getElementById('radioOtherReason').checked;
        const otherDiv = document.getElementById('otherReasonDiv');
        const otherText = document.getElementById('otherReasonText');
        
        if (isOther) {
            otherDiv.classList.remove('hidden');
            otherText.setAttribute('required', 'required');
        } else {
            otherDiv.classList.add('hidden');
            otherText.removeAttribute('required');
        }
    }

    document.getElementById('cancelForm').addEventListener('submit', function(e) {
        const isOther = document.getElementById('radioOtherReason').checked;
        if (isOther) {
            const otherText = document.getElementById('otherReasonText').value.trim();
            if (otherText) {
                document.getElementById('radioOtherReason').value = otherText;
            }
        }
    });
</script>
@endpush
@endsection

@push('scripts')
<script>

        function switchTab(tab) {
            const activeSection = document.getElementById('section-active');
            const historySection = document.getElementById('section-history');
            const btnActive = document.getElementById('btn-active');
            const btnHistory = document.getElementById('btn-history');

            if (tab === 'active') {
                activeSection.classList.remove('hidden');
                historySection.classList.add('hidden');
                
                btnActive.classList.add('bg-surface-container-lowest', 'text-primary', 'shadow-sm', 'active-tab-indicator');
                btnActive.classList.remove('text-on-surface-variant');
                
                btnHistory.classList.remove('bg-surface-container-lowest', 'text-primary', 'shadow-sm', 'active-tab-indicator');
                btnHistory.classList.add('text-on-surface-variant');
            } else {
                activeSection.classList.add('hidden');
                historySection.classList.remove('hidden');
                
                btnHistory.classList.add('bg-surface-container-lowest', 'text-primary', 'shadow-sm', 'active-tab-indicator');
                btnHistory.classList.remove('text-on-surface-variant');
                
                btnActive.classList.remove('bg-surface-container-lowest', 'text-primary', 'shadow-sm', 'active-tab-indicator');
                btnActive.classList.add('text-on-surface-variant');
            }
        }

        // Micro-interaction for buttons
        document.querySelectorAll('button').forEach(btn => {
            btn.addEventListener('mousedown', () => btn.classList.add('scale-95'));
            btn.addEventListener('mouseup', () => btn.classList.remove('scale-95'));
            btn.addEventListener('mouseleave', () => btn.classList.remove('scale-95'));
        });

        @if(session('cancel_success'))
        document.addEventListener('DOMContentLoaded', function() {
            switchTab('history');
        });
        @endif
</script>
@endpush
