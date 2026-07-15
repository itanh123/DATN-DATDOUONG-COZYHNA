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
                    <div class="text-right">
                        <p class="text-label-sm font-label-sm text-on-surface-variant">NGÀY ĐẶT</p>
                        <p class="text-headline-md font-headline-md text-primary">{{ \Carbon\Carbon::parse($order->created_at)->format('H:i, d/m/Y') }}</p>
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
                            @else Đã hủy
                            @endif
                        </h3>
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
    
</script>
@endpush
