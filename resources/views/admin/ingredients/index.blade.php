@extends('layouts.admin')

@section('title', 'Quản lý Nguyên liệu')

@section('content')
<main class="md:ml-[280px] min-h-screen p-md md:p-xl pb-2xl">
    <header class="flex justify-between items-center mb-xl">
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Nguyên liệu</h2>
            <p class="text-on-surface-variant font-body-md text-body-md">Quản lý kho nguyên liệu và cảnh báo hết hạn.</p>
        </div>
        <div class="flex gap-2">
            <a href="/admin/inventory/transactions" class="bg-surface-container-high text-on-surface hover:text-primary hover:bg-primary-container/20 px-4 py-2 rounded-xl font-label-md flex items-center gap-2 transition-colors border border-outline-variant/30">
                <span class="material-symbols-outlined">history</span> Lịch sử biến động
            </a>
            <button onclick="document.getElementById('importModal').classList.remove('hidden')" class="bg-secondary text-on-secondary px-4 py-2 rounded-xl font-label-md flex items-center gap-2 hover:shadow-md transition-all">
                <span class="material-symbols-outlined">input</span> Nhập kho
            </button>
            <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="bg-primary text-on-primary px-4 py-2 rounded-xl font-label-md flex items-center gap-2 hover:shadow-md transition-all">
                <span class="material-symbols-outlined">add</span> Thêm mới
            </button>
        </div>
    </header>

    @if(session('success'))
    <div class="bg-primary-container text-on-primary-container p-4 rounded-xl mb-4">
        {{ session('success') }}
    </div>
    @endif

    <div class="glass-card rounded-3xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container/30 border-b border-outline-variant/20">
                        <th class="px-xl py-md font-label-sm uppercase text-on-surface-variant">Mã NL</th>
                        <th class="px-xl py-md font-label-sm uppercase text-on-surface-variant">Tên</th>
                        <th class="px-xl py-md font-label-sm uppercase text-on-surface-variant">Loại</th>
                        <th class="px-xl py-md font-label-sm uppercase text-on-surface-variant">Tồn kho</th>
                        <th class="px-xl py-md font-label-sm uppercase text-on-surface-variant">Đơn vị</th>
                        <th class="px-xl py-md font-label-sm uppercase text-on-surface-variant">Hạn sử dụng</th>
                        <th class="px-xl py-md font-label-sm uppercase text-on-surface-variant">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10">
                    @forelse($ingredientsByCategory as $category => $items)
                        @php
                            $hasWarning = false;
                            foreach($items as $item) {
                                if ($item->current_stock <= $item->minimum_stock) {
                                    $hasWarning = true;
                                    break;
                                }
                                if ($item->expiration_date) {
                                    $daysLeft = now()->diffInDays($item->expiration_date, false);
                                    $isExpiring = $item->is_fresh ? now()->diffInHours($item->expiration_date, false) <= 2 : $daysLeft <= 2;
                                    if ($isExpiring) {
                                        $hasWarning = true;
                                        break;
                                    }
                                }
                            }
                        @endphp
                        <tr class="{{ $hasWarning ? 'bg-error-container/50 hover:bg-error-container/70 text-error' : 'bg-surface-container hover:bg-surface-container-high text-on-surface' }} transition-colors cursor-pointer" onclick="toggleCategory('cat-{{ Str::slug($category) }}')">
                            <td colspan="7" class="px-xl py-md font-label-lg font-bold">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined transition-transform duration-300 transform -rotate-90" id="icon-cat-{{ Str::slug($category) }}">expand_more</span>
                                    {{ $category }} ({{ $items->count() }})
                                </div>
                            </td>
                        </tr>
                        @foreach($items as $item)
                        <tr class="hover:bg-surface-container-low transition-colors hidden category-row cat-{{ Str::slug($category) }}">
                        <td class="px-xl py-md font-body-md font-bold">{{ $item->code }}</td>
                        <td class="px-xl py-md font-body-md">{{ $item->name }}</td>
                        <td class="px-xl py-md font-body-md">
                            @if($item->is_fresh)
                            <span class="px-2 py-1 bg-tertiary-container text-on-tertiary-container rounded-full text-[10px] whitespace-nowrap">Đồ tươi</span>
                            @else
                            <span class="px-2 py-1 bg-surface-variant text-on-surface-variant rounded-full text-[10px] whitespace-nowrap">Thường</span>
                            @endif
                        </td>
                        <td class="px-xl py-md font-body-md">
                            <span class="{{ $item->current_stock <= $item->minimum_stock ? 'text-error font-bold' : '' }}">
                                {{ $item->current_stock }}
                            </span>
                        </td>
                        <td class="px-xl py-md font-body-md">{{ $item->unit->name ?? '' }}</td>
                        <td class="px-xl py-md font-body-md">
                            @if($item->expiration_date)
                                @php
                                    $daysLeft = now()->diffInDays($item->expiration_date, false);
                                    $isExpiring = $item->is_fresh ? now()->diffInHours($item->expiration_date, false) <= 2 : $daysLeft <= 2;
                                @endphp
                                <span class="{{ $isExpiring ? 'text-error font-bold' : '' }}">
                                    {{ $item->expiration_date->format('d/m/Y H:i') }}
                                </span>
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="px-xl py-md flex gap-2">
                            <button onclick="editItem({{ json_encode($item) }})" class="p-xs text-outline hover:text-primary transition-colors">
                                <span class="material-symbols-outlined">edit</span>
                            </button>
                            <button type="button" onclick="confirmDelete({{ $item->id }})" class="p-xs text-outline hover:text-error transition-colors">
                                <span class="material-symbols-outlined">delete</span>
                            </button>
                        </td>
                    </tr>
                        @endforeach
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-xl text-on-surface-variant">Chưa có nguyên liệu nào.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- Add Modal -->
<div id="addModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center">
    <div class="bg-surface p-xl rounded-2xl w-[500px] max-w-full">
        <h3 class="font-title-lg mb-md">Thêm Nguyên Liệu</h3>
        <form action="/admin/ingredients" method="POST" class="flex flex-col gap-4">
            @csrf
            <div>
                <label class="block font-label-md mb-1">Mã NL</label>
                <input type="text" name="code" id="add_code" required class="w-full rounded-lg border-outline-variant px-3 py-2" onkeyup="checkDuplicateCode(this.value)">
                <span id="code_error" class="text-error font-label-sm hidden mt-1">Mã nguyên liệu đã được sử dụng!</span>
            </div>
            <div>
                <label class="block font-label-md mb-1">Tên NL</label>
                <input type="text" name="name" required class="w-full rounded-lg border-outline-variant px-3 py-2">
            </div>
            <div>
                <label class="block font-label-md mb-1">Nhóm nguyên liệu</label>
                <input type="text" name="category" list="category-list" class="w-full rounded-lg border-outline-variant px-3 py-2" placeholder="Ví dụ: Trà, Sữa, Trái cây...">
            </div>
            <div>
                <label class="block font-label-md mb-1">Đơn vị</label>
                <select name="unit_id" required class="w-full rounded-lg border-outline-variant px-3 py-2">
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->name }} ({{ $unit->symbol }})</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-4">
                <div class="flex-1">
                    <label class="block font-label-md mb-1">Tồn kho</label>
                    <input type="number" step="0.01" name="current_stock" required class="w-full rounded-lg border-outline-variant px-3 py-2">
                </div>
                <div class="flex-1">
                    <label class="block font-label-md mb-1">Tồn kho tối thiểu</label>
                    <input type="number" step="0.01" name="minimum_stock" required class="w-full rounded-lg border-outline-variant px-3 py-2">
                </div>
            </div>
            <div>
                <label class="block font-label-md mb-1">Hạn sử dụng</label>
                <input type="datetime-local" name="expiration_date" class="w-full rounded-lg border-outline-variant px-3 py-2">
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_fresh" id="is_fresh" value="1">
                <label for="is_fresh" class="font-label-md">Đồ tươi (Cảnh báo trước 2 tiếng)</label>
            </div>
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="px-4 py-2 font-label-md text-on-surface-variant">Hủy</button>
                <button type="submit" id="btn_add_save" class="px-4 py-2 font-label-md bg-primary text-on-primary rounded-lg">Lưu</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center">
    <div class="bg-surface p-xl rounded-2xl w-[500px] max-w-full">
        <h3 class="font-title-lg mb-md">Sửa Nguyên Liệu</h3>
        <form id="editForm" action="" method="POST" class="flex flex-col gap-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block font-label-md mb-1">Mã NL</label>
                <input type="text" name="code" id="edit_code" required class="w-full rounded-lg border-outline-variant px-3 py-2">
            </div>
            <div>
                <label class="block font-label-md mb-1">Tên NL</label>
                <input type="text" name="name" id="edit_name" required class="w-full rounded-lg border-outline-variant px-3 py-2">
            </div>
            <div>
                <label class="block font-label-md mb-1">Nhóm nguyên liệu</label>
                <input type="text" name="category" id="edit_category" list="category-list" class="w-full rounded-lg border-outline-variant px-3 py-2" placeholder="Ví dụ: Trà, Sữa, Trái cây...">
            </div>
            <div>
                <label class="block font-label-md mb-1">Đơn vị</label>
                <select name="unit_id" id="edit_unit_id" required class="w-full rounded-lg border-outline-variant px-3 py-2">
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->name }} ({{ $unit->symbol }})</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-4">
                <div class="flex-1">
                    <label class="block font-label-md mb-1">Tồn kho tối thiểu</label>
                    <input type="number" step="0.01" name="minimum_stock" id="edit_minimum_stock" required class="w-full rounded-lg border-outline-variant px-3 py-2">
                </div>
            </div>
            <div>
                <label class="block font-label-md mb-1">Hạn sử dụng</label>
                <input type="datetime-local" name="expiration_date" id="edit_expiration_date" class="w-full rounded-lg border-outline-variant px-3 py-2">
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_fresh" id="edit_is_fresh" value="1">
                <label for="edit_is_fresh" class="font-label-md">Đồ tươi (Cảnh báo trước 2 tiếng)</label>
            </div>
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="px-4 py-2 font-label-md text-on-surface-variant">Hủy</button>
                <button type="submit" class="px-4 py-2 font-label-md bg-primary text-on-primary rounded-lg">Cập nhật</button>
            </div>
        </form>
    </div>
</div>
<!-- Import Modal -->
<div id="importModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center">
    <div class="bg-surface p-xl rounded-2xl w-[500px] max-w-full">
        <h3 class="font-title-lg mb-md">Nhập kho nguyên liệu</h3>
        <form action="/admin/ingredients/import" method="POST" class="flex flex-col gap-4">
            @csrf
            <div>
                <label class="block font-label-md mb-1">Lọc theo nhóm nguyên liệu</label>
                <select id="import_category_filter" class="no-choices w-full rounded-lg border-outline-variant px-3 py-2 mb-2" onchange="filterImportIngredients()">
                    <option value="all">-- Tất cả nhóm --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                    <option value="Khác">Khác</option>
                </select>
            </div>
            <div>
                <label class="block font-label-md mb-1">Chọn nguyên liệu</label>
                <select name="ingredient_id" id="import_ingredient_id" required class="no-choices w-full rounded-lg border-outline-variant px-3 py-2">
                    @foreach($allIngredients as $ing)
                        <option value="{{ $ing->id }}" data-category="{{ $ing->category ?: 'Khác' }}">{{ $ing->name }} ({{ $ing->code }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-label-md mb-1">Số lượng nhập</label>
                <input type="number" step="0.01" min="0.01" name="quantity" required class="w-full rounded-lg border-outline-variant px-3 py-2" placeholder="Ví dụ: 10">
            </div>
            <div>
                <label class="block font-label-md mb-1">Ghi chú (Tùy chọn)</label>
                <input type="text" name="note" class="w-full rounded-lg border-outline-variant px-3 py-2" placeholder="Ví dụ: Nhập hàng từ nhà cung cấp">
            </div>
            <div>
                <label class="block font-label-md mb-1">Hạn sử dụng mới (Nếu có)</label>
                <input type="datetime-local" name="expiration_date" class="w-full rounded-lg border-outline-variant px-3 py-2">
                <span class="text-on-surface-variant text-sm mt-1 block">Ngày hết hạn này sẽ áp dụng cho toàn bộ số lượng tồn kho của nguyên liệu.</span>
            </div>
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="px-4 py-2 font-label-md text-on-surface-variant">Hủy</button>
                <button type="submit" class="px-4 py-2 font-label-md bg-secondary text-on-secondary rounded-lg">Xác nhận nhập kho</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center">
    <div class="bg-surface p-xl rounded-2xl w-[400px] max-w-full text-center">
        <span class="material-symbols-outlined text-5xl text-error mb-4">warning</span>
        <h3 class="font-title-lg mb-2 text-on-surface">Xác nhận xóa</h3>
        <p class="font-body-md text-on-surface-variant mb-6">Bạn có chắc chắn muốn xóa nguyên liệu này không? Hành động này không thể hoàn tác.</p>
        <div class="flex justify-center gap-4">
            <button type="button" onclick="document.getElementById('deleteModal').classList.add('hidden')" class="px-6 py-2 font-label-md text-on-surface-variant bg-surface-container hover:bg-surface-container-high rounded-lg transition-colors">Hủy</button>
            <form id="deleteForm" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-6 py-2 font-label-md bg-error text-on-error rounded-lg hover:bg-error/90 transition-colors">Xóa</button>
            </form>
        </div>
    </div>
</div>

<datalist id="category-list">
    @foreach($categories as $cat)
        <option value="{{ $cat }}">
    @endforeach
</datalist>

<script>
    let codeCheckTimeout;
    function checkDuplicateCode(code) {
        clearTimeout(codeCheckTimeout);
        const errorSpan = document.getElementById('code_error');
        const saveBtn = document.getElementById('btn_add_save');
        const input = document.getElementById('add_code');
        
        if (!code.trim()) {
            errorSpan.classList.add('hidden');
            input.classList.remove('border-error');
            saveBtn.disabled = false;
            saveBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            return;
        }

        codeCheckTimeout = setTimeout(() => {
            fetch(`/admin/ingredients/check-code?code=${encodeURIComponent(code)}`)
                .then(res => res.json())
                .then(data => {
                    if (data.exists) {
                        errorSpan.classList.remove('hidden');
                        input.classList.add('border-error');
                        saveBtn.disabled = true;
                        saveBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    } else {
                        errorSpan.classList.add('hidden');
                        input.classList.remove('border-error');
                        saveBtn.disabled = false;
                        saveBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    }
                });
        }, 500); // 500ms debounce
    }

    function confirmDelete(id) {
        document.getElementById('deleteForm').action = '/admin/ingredients/' + id;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function toggleCategory(catClass) {
        const rows = document.querySelectorAll('.' + catClass);
        const icon = document.getElementById('icon-' + catClass);
        let isHidden = false;
        
        rows.forEach(row => {
            row.classList.toggle('hidden');
            if(row.classList.contains('hidden')) isHidden = true;
        });
        
        if (isHidden) {
            icon.classList.add('-rotate-90');
        } else {
            icon.classList.remove('-rotate-90');
        }
    }

    function filterImportIngredients() {
        const category = document.getElementById('import_category_filter').value;
        const select = document.getElementById('import_ingredient_id');
        const options = select.querySelectorAll('option');
        
        let firstVisible = null;

        options.forEach(option => {
            const optCat = option.getAttribute('data-category');
            if (category === 'all' || optCat === category) {
                option.style.display = '';
                if (!firstVisible) firstVisible = option;
            } else {
                option.style.display = 'none';
            }
        });

        if (firstVisible) {
            select.value = firstVisible.value;
        }
    }

    function editItem(item) {
        document.getElementById('editForm').action = '/admin/ingredients/' + item.id;
        document.getElementById('edit_code').value = item.code;
        document.getElementById('edit_name').value = item.name;
        document.getElementById('edit_category').value = item.category || '';
        document.getElementById('edit_unit_id').value = item.unit_id;
        document.getElementById('edit_minimum_stock').value = item.minimum_stock;
        if(item.expiration_date) {
            // Format for datetime-local: YYYY-MM-DDThh:mm
            let d = new Date(item.expiration_date);
            let tzoffset = (new Date()).getTimezoneOffset() * 60000;
            let localISOTime = (new Date(d - tzoffset)).toISOString().slice(0,16);
            document.getElementById('edit_expiration_date').value = localISOTime;
        } else {
            document.getElementById('edit_expiration_date').value = '';
        }
        document.getElementById('edit_is_fresh').checked = item.is_fresh;
        
        document.getElementById('editModal').classList.remove('hidden');
    }
</script>
@endsection
