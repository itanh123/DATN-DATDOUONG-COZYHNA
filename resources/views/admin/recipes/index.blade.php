@extends('layouts.admin')

@section('title', 'Quản lý Công thức - ' . $product->name)

@section('content')
<main class="md:ml-[280px] min-h-screen p-md md:p-xl pb-2xl">
    <header class="flex items-center gap-4 mb-xl">
        <a href="/admin/product" class="p-2 bg-surface-container rounded-full hover:bg-surface-container-high transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Công thức: {{ $product->name }}</h2>
            <p class="text-on-surface-variant font-body-md text-body-md">Định lượng nguyên liệu sẽ tự động trừ kho khi có đơn hàng.</p>
        </div>
    </header>

    @if(session('success'))
    <div class="bg-primary-container text-on-primary-container p-4 rounded-xl mb-4">
        {{ session('success') }}
    </div>
    @endif

    <form action="/admin/product/{{ $product->id }}/recipe" method="POST">
        @csrf
        <div class="space-y-lg">
            @if($product->productSizes->isEmpty())
                <div class="bg-surface-container-highest p-6 rounded-2xl text-center">
                    <span class="material-symbols-outlined text-5xl text-on-surface-variant mb-2">straighten</span>
                    <h3 class="font-title-lg text-on-surface mb-2">Chưa có Kích Cỡ (Size) nào!</h3>
                    <p class="text-on-surface-variant mb-4">Bạn cần phải thêm ít nhất 1 Size (ví dụ: M, L) cho sản phẩm này trước khi cài đặt Công thức.</p>
                    <a href="/admin/product" class="inline-flex items-center gap-2 px-6 py-2 bg-primary text-on-primary rounded-xl font-label-md">
                        <span class="material-symbols-outlined">arrow_back</span> Quay lại để thêm Size
                    </a>
                </div>
            @else
            @foreach($product->productSizes as $ps)
                @php
                    $recipe = $ps->recipes->first();
                    $recipeIngredients = $recipe ? $recipe->ingredients->keyBy('ingredient_id') : collect();
                @endphp
                <div class="glass-card rounded-2xl p-xl shadow-sm">
                    <h3 class="font-title-lg mb-4 text-primary">Size: {{ $ps->size->name }} ({{ $ps->size->volume_ml }}ml)</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($ingredients as $ingredient)
                            @php
                                $used = $recipeIngredients->has($ingredient->id);
                                $qty = $used ? $recipeIngredients->get($ingredient->id)->quantity : '';
                            @endphp
                            <div class="flex items-center gap-3 p-3 border rounded-xl {{ $used ? 'border-primary bg-primary/5' : 'border-outline-variant/30' }}">
                                <div class="flex-1">
                                    <p class="font-label-md text-on-surface">{{ $ingredient->name }}</p>
                                    <p class="text-label-sm text-on-surface-variant">Tồn: {{ $ingredient->current_stock }} {{ $ingredient->unit->name ?? '' }}</p>
                                </div>
                                <div class="w-24 flex items-center gap-1">
                                    <input type="number" step="0.01" name="recipes[{{ $ps->id }}][ingredients][{{ $ingredient->id }}][quantity]" value="{{ $qty }}" class="w-full px-2 py-1 border border-outline-variant rounded focus:border-primary outline-none" placeholder="0">
                                    <span class="text-label-sm text-on-surface-variant">{{ $ingredient->unit->name ?? '' }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
            @endif
        </div>

        @if(!$product->productSizes->isEmpty())
        <div class="mt-xl flex justify-end">
            <button type="submit" class="px-8 py-3 bg-primary text-on-primary font-bold rounded-xl shadow hover:opacity-90 transition-all">
                Lưu Công Thức
            </button>
        </div>
        @endif
    </form>
</main>
@endsection
