@extends('layouts.admin')

@section('title', 'Quản lý Nguyên liệu')

@section('content')
<main class="md:ml-[280px] min-h-screen p-md md:p-xl pb-2xl">
    <header class="flex justify-between items-center mb-xl">
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Nguyên liệu</h2>
            <p class="text-on-surface-variant font-body-md text-body-md">Quản lý kho nguyên liệu và cảnh báo hết hạn.</p>
        </div>
        <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="bg-primary text-on-primary px-4 py-2 rounded-xl font-label-md flex items-center gap-2">
            <span class="material-symbols-outlined">add</span> Thêm mới
        </button>
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
                    @forelse($ingredients as $item)
                    <tr class="hover:bg-surface-container-low transition-colors">
                        <td class="px-xl py-md font-body-md font-bold">{{ $item->code }}</td>
                        <td class="px-xl py-md font-body-md">{{ $item->name }}</td>
                        <td class="px-xl py-md font-body-md">
                            @if($item->is_fresh)
                            <span class="px-2 py-1 bg-tertiary-container text-on-tertiary-container rounded-full text-[10px]">Đồ tươi</span>
                            @else
                            <span class="px-2 py-1 bg-surface-variant text-on-surface-variant rounded-full text-[10px]">Thường</span>
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
                            <form action="/admin/ingredients/{{ $item->id }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-xs text-outline hover:text-error transition-colors">
                                    <span class="material-symbols-outlined">delete</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-xl text-on-surface-variant">Chưa có nguyên liệu nào.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-md">
            {{ $ingredients->links() }}
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
                <input type="text" name="code" required class="w-full rounded-lg border-outline-variant px-3 py-2">
            </div>
            <div>
                <label class="block font-label-md mb-1">Tên NL</label>
                <input type="text" name="name" required class="w-full rounded-lg border-outline-variant px-3 py-2">
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
                <button type="submit" class="px-4 py-2 font-label-md bg-primary text-on-primary rounded-lg">Lưu</button>
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
                <label class="block font-label-md mb-1">Đơn vị</label>
                <select name="unit_id" id="edit_unit_id" required class="w-full rounded-lg border-outline-variant px-3 py-2">
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->name }} ({{ $unit->symbol }})</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-4">
                <div class="flex-1">
                    <label class="block font-label-md mb-1">Tồn kho</label>
                    <input type="number" step="0.01" name="current_stock" id="edit_current_stock" required class="w-full rounded-lg border-outline-variant px-3 py-2">
                </div>
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

<script>
    function editItem(item) {
        document.getElementById('editForm').action = '/admin/ingredients/' + item.id;
        document.getElementById('edit_code').value = item.code;
        document.getElementById('edit_name').value = item.name;
        document.getElementById('edit_unit_id').value = item.unit_id;
        document.getElementById('edit_current_stock').value = item.current_stock;
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
