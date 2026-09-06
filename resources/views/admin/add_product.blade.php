@extends('layouts.admin')

@section('title', 'Add Product')

@section('content')
<main class="md:ml-[280px] pt-16 min-h-screen bg-surface p-gutter">
<div class="max-w-[1100px] mx-auto">
<!-- Breadcrumbs -->
<nav class="flex items-center gap-xs py-md text-on-surface-variant mb-md">
<a class="font-label-md text-label-md hover:text-primary" href="#">Sản phẩm</a>
<span class="material-symbols-outlined text-[16px]">chevron_right</span>
<span class="font-label-md text-label-md text-primary font-bold">Thêm Mới Product</span>
</nav>
<!-- Page Title & Thao tác -->
<div class="flex justify-between items-end mb-xl">
<div>
<h1 class="font-headline-lg text-headline-lg text-on-surface">{{ __('New Product Entry') }}</h1>
<p class="font-body-md text-body-md text-on-surface-variant">{{ __('Set up a new premium beverage in your catalog') }}</p>
</div>
<div class="flex gap-md">
<button class="px-lg py-md rounded-lg border border-outline text-on-surface font-label-md text-label-md hover:bg-surface-container transition-colors">
                        Hủy
                    </button>
<button class="px-lg py-md rounded-lg bg-primary text-on-primary font-label-md text-label-md hover:opacity-90 shadow-sm transition-all transform active:scale-95">Thêm Sản Phẩm</button>
</div>
</div>
<!-- Bento Grid Layout -->
<div class="grid grid-cols-12 gap-lg">
<!-- Left Column: Primary Details -->
<div class="col-span-12 lg:col-span-8 space-y-lg">
<!-- General Information Card -->
<section class="bg-surface-container-lowest rounded-xl p-xl border border-outline-variant shadow-sm">
<div class="flex items-center gap-sm mb-lg">
<span class="material-symbols-outlined text-primary">info</span>
<h3 class="font-title-lg text-title-lg">{{ __('General Information') }}</h3>
</div>
<div class="grid grid-cols-2 gap-lg">
<div class="col-span-2">
<label class="block font-label-md text-label-md text-on-surface-variant mb-xs">Tên Sản Phẩm</label>
<input class="w-full border-outline-variant rounded-lg p-md focus:border-primary focus:ring-1 focus:ring-primary text-body-md" placeholder="{{ __('e.g. Organic Sparkling Matcha') }}" type="text"/>
</div>
<div>
<label class="block font-label-md text-label-md text-on-surface-variant mb-xs">Danh mục</label>
<select class="w-full border-outline-variant rounded-lg p-md focus:border-primary focus:ring-1 focus:ring-primary text-body-md">
<option>{{ __('Select Category') }}</option>
<option>{{ __('Sparkling Tea') }}</option>
<option>{{ __('Fresh Juices') }}</option>
<option>{{ __('Wellness Shots') }}</option>
<option>{{ __('Kombucha') }}</option>
</select>
</div>
<div>
<label class="block font-label-md text-label-md text-on-surface-variant mb-xs">{{ __('SKU') }}</label>
<input class="w-full border-outline-variant rounded-lg p-md focus:border-primary focus:ring-1 focus:ring-primary text-body-md" placeholder="{{ __('O-MAT-330') }}" type="text"/>
</div>
<div class="col-span-2">
<label class="block font-label-md text-label-md text-on-surface-variant mb-xs">Mô tả</label>
<textarea class="w-full border-outline-variant rounded-lg p-md focus:border-primary focus:ring-1 focus:ring-primary text-body-md" placeholder="{{ __('Highlight the natural ingredients and health benefits...') }}" rows="4"></textarea>
</div>
</div>
</section>
<!-- Kho hàng Management Card -->
<section class="bg-surface-container-lowest rounded-xl p-xl border border-outline-variant shadow-sm">
<div class="flex items-center gap-sm mb-lg">
<span class="material-symbols-outlined text-primary">inventory_2</span>
<h3 class="font-title-lg text-title-lg">Kho hàng Tracking</h3>
</div>
<div class="grid grid-cols-3 gap-lg">
<div>
<label class="block font-label-md text-label-md text-on-surface-variant mb-xs">{{ __('Initial Stock') }}</label>
<input class="w-full border-outline-variant rounded-lg p-md focus:border-primary focus:ring-1 focus:ring-primary text-body-md" placeholder="0" type="number"/>
</div>
<div>
<label class="block font-label-md text-label-md text-on-surface-variant mb-xs">{{ __('Low Stock Alert') }}</label>
<input class="w-full border-outline-variant rounded-lg p-md focus:border-primary focus:ring-1 focus:ring-primary text-body-md" placeholder="10" type="number"/>
</div>
<div>
<label class="block font-label-md text-label-md text-on-surface-variant mb-xs">{{ __('Unit Type') }}</label>
<select class="w-full border-outline-variant rounded-lg p-md focus:border-primary focus:ring-1 focus:ring-primary text-body-md">
<option>{{ __('Bottle') }}</option>
<option>Case (12ct)</option>
<option>{{ __('Kg') }}</option>
<option>{{ __('Liter') }}</option>
</select>
</div>
</div>
</section>
</div>
<!-- Right Column: Media & Meta -->
<div class="col-span-12 lg:col-span-4 space-y-lg">
<!-- Media Upload Card -->
<section class="bg-surface-container-lowest rounded-xl p-xl border border-outline-variant shadow-sm">
<div class="flex items-center gap-sm mb-lg">
<span class="material-symbols-outlined text-primary">image</span>
<h3 class="font-title-lg text-title-lg">{{ __('Product Media') }}</h3>
</div>
<div class="border-2 border-dashed border-outline-variant rounded-xl p-xl text-center hover:bg-surface-container-low transition-colors cursor-pointer group">
<span class="material-symbols-outlined text-[48px] text-outline mb-md group-hover:text-primary transition-colors">cloud_upload</span>
<p class="font-body-md text-body-md text-on-surface-variant mb-xs">{{ __('Drag and drop images here') }}</p>
<p class="font-label-sm text-label-sm text-outline">Supports PNG, JPG (Max 5MB)</p>
<input class="hidden" id="file-upload" type="file"/>
<label class="mt-md inline-block px-md py-xs bg-surface-container-high text-primary rounded-lg font-label-md text-label-md hover:bg-primary-container hover:text-on-primary-container transition-all" for="file-upload">{{ __('Browse Files') }}</label>
</div>
<div class="mt-md grid grid-cols-3 gap-xs">
<div class="aspect-square bg-surface-container rounded-lg flex items-center justify-center border border-outline-variant">
<span class="material-symbols-outlined text-outline">add_a_photo</span>
</div>
</div>
</section>
<!-- Pricing & Attributes Card -->
<section class="bg-surface-container-lowest rounded-xl p-xl border border-outline-variant shadow-sm">
<div class="flex items-center gap-sm mb-lg">
<span class="material-symbols-outlined text-primary">payments</span>
<h3 class="font-title-lg text-title-lg">Pricing &amp; Attributes</h3>
</div>
<div class="space-y-lg">
<div>
<label class="block font-label-md text-label-md text-on-surface-variant mb-xs">Base Price ($)</label>
<input class="w-full border-outline-variant rounded-lg p-md focus:border-primary focus:ring-1 focus:ring-primary text-body-md" placeholder="0.00" type="text"/>
</div>
<div>
<label class="block font-label-md text-label-md text-on-surface-variant mb-md">{{ __('Product Tags') }}</label>
<div class="flex flex-wrap gap-xs">
<button class="px-md py-xs rounded-full border border-primary bg-primary-container text-on-primary-container font-label-sm text-label-sm">{{ __('Organic') }}</button>
<button class="px-md py-xs rounded-full border border-outline-variant text-on-surface-variant font-label-sm text-label-sm hover:border-primary hover:text-primary">{{ __('Sugar-Free') }}</button>
<button class="px-md py-xs rounded-full border border-outline-variant text-on-surface-variant font-label-sm text-label-sm hover:border-primary hover:text-primary">{{ __('Vegan') }}</button>
<button class="px-md py-xs rounded-full border border-outline-variant text-on-surface-variant font-label-sm text-label-sm hover:border-primary hover:text-primary">{{ __('Seasonal') }}</button>
<button class="px-md py-xs rounded-full border border-outline-variant text-on-surface-variant font-label-sm text-label-sm hover:border-primary hover:text-primary">{{ __('Locally Sourced') }}</button>
<button class="p-xs rounded-full border border-outline-variant text-on-surface-variant hover:bg-surface-container transition-colors">
<span class="material-symbols-outlined text-[16px]">add</span>
</button>
</div>
</div>
<div class="pt-md border-t border-outline-variant">
<label class="flex items-center gap-md cursor-pointer group">
<div class="relative flex items-center">
<input class="peer h-5 w-5 rounded border-outline-variant text-primary focus:ring-primary" type="checkbox"/>
</div>
<span class="font-body-md text-body-md text-on-surface group-hover:text-primary transition-colors">Hoạt động for Sale</span>
</label>
</div>
</div>
</section>
</div>
</div>
<!-- Footer Form Info -->
<div class="mt-xl flex items-center justify-center gap-sm text-on-surface-variant opacity-60">
<span class="material-symbols-outlined text-[18px]">lock</span>
<p class="font-label-sm text-label-sm">{{ __('Changes will be logged to the audit trail for manager review.') }}</p>
</div>
</div>
</main>
@endsection

@push('scripts')
<script>

        // Micro-interaction for primary button
        const primaryBtn = document.querySelector('button.bg-primary');
        primaryBtn.addEventListener('click', () => {
            primaryBtn.innerHTML = '<span class="material-symbols-outlined animate-spin mr-2">sync</span> Saving...';
            setTimeout(() => {
                primaryBtn.innerHTML = 'Add Product';
                alert('Product initialized successfully in Organic Vitality backend.');
            }, 1200);
        });

        // Simple tag toggle logic
        const tags = document.querySelectorAll('.rounded-full.border');
        tags.forEach(tag => {
            tag.addEventListener('click', () => {
                if(tag.classList.contains('bg-primary-container')) {
                    tag.classList.remove('bg-primary-container', 'text-on-primary-container', 'border-primary');
                    tag.classList.add('border-outline-variant', 'text-on-surface-variant');
                } else {
                    tag.classList.add('bg-primary-container', 'text-on-primary-container', 'border-primary');
                    tag.classList.remove('border-outline-variant', 'text-on-surface-variant');
                }
            });
        });
    
</script>
@endpush
