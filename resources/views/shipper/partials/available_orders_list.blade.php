@forelse($availableOrders as $order)
    <div class="glass-card rounded-xl p-md flex flex-col gap-md hover:shadow-lg transition-all" id="order-card-{{ $order->id }}">
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
                <div class="flex justify-between text-body-sm text-on-surface-variant border-b border-outline-variant/10 pb-xs last:border-0 last:pb-0 gap-md">
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
