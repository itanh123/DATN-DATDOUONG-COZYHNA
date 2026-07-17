@props(['product'])

@php
    $status = $product->status ?? 0;
@endphp

@if((int)$status === 1)
    <div class="group cursor-pointer" data-product="{{ json_encode($product) }}" onclick="openDrawer(this)">
        <div class="relative aspect-square rounded-2xl overflow-hidden mb-md shadow-sm">
            <div
                class="absolute inset-0 bg-cover bg-center transform group-hover:scale-110 transition-transform duration-700"
                @if(!empty($product->image))
                    style="background-image: url('{{ $product->image }}')"
                @else
                    style="background-image: none; background-color: rgba(148,249,144,0.15)"
                @endif
            ></div>
            <button type="button" data-product-id="{{ $product->id }}"
                class="absolute top-3 right-3 w-8 h-8 bg-white/80 backdrop-blur-sm rounded-full flex items-center justify-center text-on-surface hover:text-error transition-colors btn-favorite-toggle">
                <span class="material-symbols-outlined text-[20px] favorite-icon-{{ $product->id }}" style="font-variation-settings: 'FILL' 0;">favorite</span>
            </button>
        </div>

        <h3 class="font-title-lg text-title-lg mb-xs group-hover:text-primary transition-colors">{{ $product->name }}</h3>

        <div class="flex items-center gap-xs mb-sm">
            <span class="material-symbols-outlined text-tertiary text-[14px]" style="font-variation-settings: 'FILL' 1;">star</span>
            <span class="text-label-sm">{{ $product->code ?? 'N/A' }}</span>
        </div>

        @php
            $defaultSize = $product->productSizes->firstWhere('is_default', true) ?? $product->productSizes->first();
            $displayPrice = $defaultSize ? $defaultSize->selling_price : 0;
        @endphp
        <span class="font-headline-md text-primary">{{ number_format($displayPrice, 0, ',', '.') }} đ</span>
    </div>
@endif
