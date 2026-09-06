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
            @if(check_permission('edit_products'))
            <div class="mb-4">
                <button type="button" class="btn-open-ingredient-modal flex items-center gap-2 px-6 py-3 bg-surface-container-high hover:bg-surface-container-highest text-primary font-bold rounded-xl transition-colors border border-primary/20 shadow-sm">
                    <span class="material-symbols-outlined text-lg">add_circle</span> Chọn thêm nguyên liệu chung cho tất cả Size
                </button>
            </div>
            @endif
            @foreach($product->productSizes as $ps)
                @php
                    $recipe = $ps->recipes->first();
                    $recipeIngredients = $recipe ? $recipe->ingredients->keyBy('ingredient_id') : collect();
                @endphp
                <div class="glass-card rounded-2xl p-xl shadow-sm">
                    <h3 class="font-title-lg mb-4 text-primary">Size: {{ $ps->size->name }} ({{ $ps->size->volume_ml }}ml)</h3>
                    
                    <div class="size-ingredients-grid active-ingredients-list-{{ $ps->id }} grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4" data-size-id="{{ $ps->id }}">
                        @foreach($ingredients as $ingredient)
                            @php
                                $used = $recipeIngredients->has($ingredient->id);
                                if (!$used) continue;
                                $qty = $recipeIngredients->get($ingredient->id)->quantity;
                            @endphp
                            <div class="flex flex-col gap-2 p-3 border border-primary bg-primary/5 rounded-xl ingredient-row relative" data-ingredient-id="{{ $ingredient->id }}">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-label-md text-on-surface line-clamp-1" title="{{ $ingredient->name }}">{{ $ingredient->name }}</p>
                                        <p class="text-label-sm text-on-surface-variant">Tồn: {{ $ingredient->current_stock }} {{ $ingredient->unit->name ?? __('') }}</p>
                                    </div>
                                    @if(check_permission('edit_products'))
                                    <button type="button" class="text-error hover:bg-error/10 p-1 rounded transition-colors remove-ingredient-btn absolute top-2 right-2">
                                        <span class="material-symbols-outlined text-sm">close</span>
                                    </button>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2 mt-auto pt-2">
                                    <input type="number" step="0.01" name="recipes[{{ $ps->id }}][ingredients][{{ $ingredient->id }}][quantity]" value="{{ $qty }}" class="w-full px-2 py-1 border border-outline-variant rounded focus:border-primary outline-none bg-surface text-sm" placeholder="0" {{ !check_permission('edit_products') ? 'disabled' : '' }}>
                                    <span class="text-label-sm text-on-surface-variant flex-shrink-0 w-8">{{ $ingredient->unit->name ?? __('') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-4 border-t border-outline-variant/30 pt-4">
                        <label class="block font-label-md text-on-surface mb-2">Ghi chú / Hướng dẫn pha chế</label>
                        <textarea name="recipes[{{ $ps->id }}][instruction]" class="w-full bg-surface border border-outline-variant rounded-xl p-3 focus:border-primary outline-none resize-y disabled:bg-surface-container-low" rows="3" placeholder="Ví dụ: Lắc đều với đá, cho trân châu vào cuối..." {{ !check_permission('edit_products') ? 'disabled' : '' }}>{{ $recipe ? $recipe->instruction : '' }}</textarea>
                    </div>
                </div>
            @endforeach
            @endif
        </div>

        @if(!$product->productSizes->isEmpty())
        @if(check_permission('edit_products'))
        <div class="mt-xl flex justify-end">
            <button type="submit" class="px-8 py-3 bg-primary text-on-primary font-bold rounded-xl shadow hover:opacity-90 transition-all">
                Lưu Công Thức
            </button>
        </div>
        @endif
        @endif
    </form>
</main>

<!-- Ingredient Modal -->
<div id="ingredient-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
    <div class="bg-surface rounded-2xl shadow-xl w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden">
        <div class="p-4 border-b border-outline-variant/30 flex items-center justify-between bg-surface-container-low">
            <h3 class="font-title-lg text-primary flex items-center gap-2">
                <span class="material-symbols-outlined">kitchen</span>
                Chọn thêm nguyên liệu
            </h3>
            <button type="button" class="btn-close-modal p-2 hover:bg-surface-container rounded-full transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        
        <div class="p-4 border-b border-outline-variant/30 flex flex-col md:flex-row gap-4 bg-surface">
            <div class="flex-1 relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                <input type="text" id="modal-search-input" class="w-full pl-10 pr-4 py-2 border border-outline-variant rounded-xl focus:border-primary outline-none" placeholder="Tìm nguyên liệu theo tên...">
            </div>
            <div class="w-full md:w-64">
                <select id="modal-category-select" class="w-full px-4 py-2 border border-outline-variant rounded-xl focus:border-primary outline-none no-choices">
                    <option value="">Tất cả nhóm</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}">{{ $category }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="flex-1 overflow-y-auto p-4 bg-surface-container-lowest">
            <div id="modal-ingredients-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($ingredients as $ingredient)
                <label class="modal-ingredient-item flex items-start gap-3 p-3 border border-outline-variant/50 rounded-xl hover:bg-primary/5 cursor-pointer transition-colors" data-name="{{ mb_strtolower($ingredient->name, 'UTF-8') }}" data-category="{{ $ingredient->category }}">
                    <div class="pt-1">
                        <input type="checkbox" class="ingredient-checkbox w-5 h-5 rounded border-outline-variant text-primary focus:ring-primary" value="{{ $ingredient->id }}" data-name="{{ $ingredient->name }}" data-unit="{{ $ingredient->unit->name ?? __('') }}" data-stock="{{ $ingredient->current_stock }}">
                    </div>
                    <div>
                        <p class="font-label-md text-on-surface">{{ $ingredient->name }}</p>
                        <p class="text-label-sm text-on-surface-variant">Nhóm: {{ $ingredient->category ?? __('Khác') }}</p>
                        <p class="text-label-sm {{ $ingredient->current_stock > 0 ? 'text-primary' : 'text-error' }}">Tồn kho: {{ $ingredient->current_stock }} {{ $ingredient->unit->name ?? __('') }}</p>
                    </div>
                </label>
                @endforeach
            </div>
            <div id="modal-empty-state" class="hidden text-center py-8 text-on-surface-variant">
                Không tìm thấy nguyên liệu phù hợp.
            </div>
        </div>
        
        <div class="p-4 border-t border-outline-variant/30 flex items-center justify-between bg-surface-container-low">
            <span class="text-label-md text-on-surface-variant">Đã chọn: <strong id="modal-selected-count" class="text-primary">0</strong></span>
            <div class="flex gap-2">
                <button type="button" class="btn-close-modal px-4 py-2 border border-outline-variant rounded-xl font-label-md hover:bg-surface-container transition-colors">Hủy</button>
                <button type="button" id="btn-confirm-ingredients" class="px-6 py-2 bg-primary text-on-primary rounded-xl font-label-md shadow-sm hover:opacity-90 transition-opacity">
                    Đồng ý thêm
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('ingredient-modal');
        const searchInput = document.getElementById('modal-search-input');
        const categorySelect = document.getElementById('modal-category-select');
        const items = document.querySelectorAll('.modal-ingredient-item');
        const emptyState = document.getElementById('modal-empty-state');
        const checkboxes = document.querySelectorAll('.ingredient-checkbox');
        const selectedCount = document.getElementById('modal-selected-count');
        // Open Modal
        document.querySelectorAll('.btn-open-ingredient-modal').forEach(btn => {
            btn.addEventListener('click', function() {
                // Bỏ check tất cả khi mở
                checkboxes.forEach(cb => cb.checked = false);
                updateSelectedCount();
                
                // Mở modal
                modal.classList.remove('hidden');
            });
        });

        // Close Modal
        document.querySelectorAll('.btn-close-modal').forEach(btn => {
            btn.addEventListener('click', () => modal.classList.add('hidden'));
        });

        // Filter Logic
        function filterIngredients() {
            const term = searchInput.value.toLowerCase().trim();
            const cat = categorySelect.value;
            let visibleCount = 0;

            items.forEach(item => {
                const name = item.dataset.name;
                const category = item.dataset.category;
                
                const matchesSearch = name.includes(term);
                const matchesCat = (cat === '' || category === cat);

                if (matchesSearch && matchesCat) {
                    item.classList.remove('hidden');
                    visibleCount++;
                } else {
                    item.classList.add('hidden');
                }
            });

            if (visibleCount === 0) {
                emptyState.classList.remove('hidden');
            } else {
                emptyState.classList.add('hidden');
            }
        }

        searchInput.addEventListener('input', filterIngredients);
        categorySelect.addEventListener('change', filterIngredients);

        // Update Count
        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateSelectedCount);
        });

        function updateSelectedCount() {
            const count = document.querySelectorAll('.ingredient-checkbox:checked').length;
            selectedCount.innerText = count;
        }

        // Confirm Selection
        document.getElementById('btn-confirm-ingredients').addEventListener('click', function() {
            const listContainers = document.querySelectorAll('.size-ingredients-grid');
            const checkedBoxes = document.querySelectorAll('.ingredient-checkbox:checked');
            
            checkedBoxes.forEach(cb => {
                const id = cb.value;
                const name = cb.dataset.name;
                const unit = cb.dataset.unit;
                const stock = cb.dataset.stock;
                
                listContainers.forEach(container => {
                    const sizeId = container.dataset.sizeId;
                    
                    // Kiểm tra xem đã có trong list chưa
                    const existingRow = container.querySelector(`.ingredient-row[data-ingredient-id="${id}"]`);
                    if (!existingRow) {
                        // Thêm mới
                        const html = `
                            <div class="flex flex-col gap-2 p-3 border border-primary bg-primary/5 rounded-xl ingredient-row relative" data-ingredient-id="${id}">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-label-md text-on-surface line-clamp-1" title="${name}">${name}</p>
                                        <p class="text-label-sm text-on-surface-variant">Tồn: ${stock} ${unit}</p>
                                    </div>
                                    <button type="button" class="text-error hover:bg-error/10 p-1 rounded transition-colors remove-ingredient-btn absolute top-2 right-2">
                                        <span class="material-symbols-outlined text-sm">close</span>
                                    </button>
                                </div>
                                <div class="flex items-center gap-2 mt-auto pt-2">
                                    <input type="number" step="0.01" name="recipes[${sizeId}][ingredients][${id}][quantity]" value="" class="w-full px-2 py-1 border border-outline-variant rounded focus:border-primary outline-none bg-surface text-sm" placeholder="0" required>
                                    <span class="text-label-sm text-on-surface-variant flex-shrink-0 w-8">${unit}</span>
                                </div>
                            </div>
                        `;
                        container.insertAdjacentHTML('beforeend', html);
                    }
                });
            });
            
            modal.classList.add('hidden');
        });

        // Xóa nguyên liệu khỏi active list
        document.body.addEventListener('click', function(e) {
            let btn = e.target.closest('.remove-ingredient-btn');
            if (btn) {
                let row = btn.closest('.ingredient-row');
                let idToRemove = row.dataset.ingredientId;
                
                // Xóa ở tất cả các size luôn
                document.querySelectorAll(`.ingredient-row[data-ingredient-id="${idToRemove}"]`).forEach(el => el.remove());
            }
        });
    });
</script>
@endpush
@endsection
