@extends('layouts.customer')

@section('title', 'Sản phẩm yêu thích')

@section('content')
<main class="mt-24 pb-24 max-w-container-max mx-auto px-4 md:px-lg">
    <div class="mb-xl">
        <h1 class="font-headline-lg text-headline-lg text-on-background">Sản phẩm yêu thích của tôi</h1>
        <p class="font-body-md text-body-md text-on-surface-variant">Các sản phẩm bạn đã đánh dấu yêu thích.</p>
    </div>

    @if(session('success'))
        <div class="mb-md p-md bg-green-100 text-green-700 rounded-xl font-body-md flex items-center gap-sm">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    @if($favorites->isEmpty())
        <div class="text-center py-2xl bg-surface-container-lowest rounded-2xl border border-outline-variant/10">
            <span class="material-symbols-outlined text-[80px] text-outline-variant">favorite</span>
            <h2 class="font-headline-md text-headline-md text-on-surface mt-md">Chưa có sản phẩm yêu thích nào</h2>
            <p class="text-on-surface-variant font-body-md mt-xs mb-xl">Hãy khám phá thực đơn và lưu lại món bạn thích nhé!</p>
            <a href="/" class="bg-primary text-white px-xl py-md rounded-xl font-bold hover:bg-primary/90 transition-all">Xem thực đơn</a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-lg">
            @foreach($favorites as $product)
            <div class="glass-card group flex flex-col h-full rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow relative cursor-pointer" 
                 data-product="{{ json_encode($product) }}" 
                 onclick="openDrawer(this)">
                <!-- Favorite Toggle Form -->
                <form action="{{ route('favorites.toggle', $product->id) }}" method="POST" class="absolute top-sm right-sm z-10 favorite-form">
                    @csrf
                    <button type="button" class="w-10 h-10 bg-white/80 backdrop-blur-md rounded-full flex items-center justify-center text-primary shadow-sm hover:scale-110 transition-transform btn-favorite-toggle" data-product-id="{{ $product->id }}">
                        <span class="material-symbols-outlined favorite-icon-{{ $product->id }}" style="font-variation-settings: 'FILL' 1;">favorite</span>
                    </button>
                </form>

                <div class="relative aspect-square rounded-2xl overflow-hidden mb-md shadow-sm">
                    <div
                        class="absolute inset-0 bg-cover bg-center transform group-hover:scale-110 transition-transform duration-700"
                        @if(!empty($product->image))
                            style="background-image: url('{{ $product->image }}')"
                        @else
                            style="background-image: none; background-color: rgba(148,249,144,0.15)"
                        @endif
                    ></div>
                </div>
                
                <div class="p-md flex flex-col flex-grow bg-white">
                    <div class="flex justify-between items-start mb-xs">
                        <h3 class="font-title-md text-on-surface line-clamp-1">{{ $product->name }}</h3>
                    </div>
                    <p class="font-body-sm text-on-surface-variant line-clamp-2 mb-md flex-grow">{{ $product->description }}</p>
                    
                    <div class="flex items-center justify-between mt-auto">
                        <div class="flex flex-col">
                            <span class="font-label-sm text-on-surface-variant">Từ</span>
                            @php
                                $defaultSize = $product->productSizes->firstWhere('is_default', true) ?? $product->productSizes->first();
                                $displayPrice = $defaultSize ? $defaultSize->selling_price : 0;
                            @endphp
                            <span class="font-title-lg text-primary">{{ number_format($displayPrice, 0, ',', '.') }}đ</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</main>
@endsection
