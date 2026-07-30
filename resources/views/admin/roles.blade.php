@extends('layouts.admin')

@section('title', 'Phân Quyền Chi Tiết')

@section('content')
<main class="ml-0 md:ml-[280px] pt-16 min-h-screen p-8 bg-surface-container-lowest">
    <div class="max-w-7xl mx-auto space-y-8">
        
        <!-- Header -->
        <div class="flex justify-between items-center border-b border-outline-variant/30 pb-4">
            <div>
                <h2 class="font-headline-md text-headline-md text-on-surface">Phân Quyền Chi Tiết</h2>
                <p class="font-body-md text-on-surface-variant">Thiết lập chi tiết từng thao tác cho từng chức vụ</p>
            </div>
            @if(session('success'))
                <div class="bg-primary-container text-on-primary-container px-4 py-2 rounded-lg text-label-md shadow-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-error-container text-on-error-container px-4 py-2 rounded-lg text-label-md shadow-sm">
                    {{ session('error') }}
                </div>
            @endif
        </div>

        <form action="/admin/roles/update" method="POST" id="permissionsForm">
            @csrf
            
            <div class="bg-white rounded-xl border border-outline-variant/30 shadow-md overflow-hidden mb-6 relative">
                <div class="overflow-x-auto max-h-[70vh]">
                    <table class="w-full text-left border-collapse relative" id="permissionsTable">
                        <thead class="sticky top-0 z-30">
                            <tr class="bg-surface-container-low border-b border-outline-variant/30 backdrop-blur-md bg-opacity-90">
                                <th class="p-4 font-label-sm text-on-surface-variant uppercase tracking-wider border-r border-outline-variant/30 w-72 bg-surface-container-low/90 backdrop-blur-md sticky left-0 z-40 shadow-[1px_0_0_0_rgba(0,0,0,0.05)] align-top">
                                    <div class="flex flex-col h-full justify-between gap-4">
                                        <span class="font-bold text-on-surface">Chức năng (Permission)</span>
                                        <p class="text-[11px] text-on-surface-variant font-normal normal-case">Quản lý quyền truy cập cho từng chức vụ.</p>
                                    </div>
                                </th>
                                @foreach($roles as $role)
                                    <th class="p-4 text-center border-r border-outline-variant/30 last:border-0 min-w-[150px] align-top bg-surface-container-low/90 backdrop-blur-md transition-colors" data-col-role="{{ $role->id }}">
                                        <div class="font-title-md text-on-surface font-semibold flex flex-col items-center gap-2">
                                            @php
                                                $iconColor = 'text-outline';
                                                if($role->code === 'admin') $iconColor = 'text-error';
                                                elseif($role->code === 'staff') $iconColor = 'text-primary';
                                                elseif($role->code === 'shipper') $iconColor = 'text-tertiary';
                                            @endphp
                                            
                                            @if($role->code === 'admin')
                                                <span class="material-symbols-outlined {{ $iconColor }} text-[32px]">admin_panel_settings</span>
                                            @elseif($role->code === 'staff')
                                                <span class="material-symbols-outlined {{ $iconColor }} text-[32px]">badge</span>
                                            @elseif($role->code === 'shipper')
                                                <span class="material-symbols-outlined {{ $iconColor }} text-[32px]">local_shipping</span>
                                            @else
                                                <span class="material-symbols-outlined {{ $iconColor }} text-[32px]">person</span>
                                            @endif
                                            
                                            <span class="text-lg">{{ $role->name }}</span>
                                            
                                            <button type="button" class="mt-2 text-[11px] uppercase tracking-wider font-bold text-on-surface-variant hover:text-primary transition-colors border border-outline-variant/50 rounded-md px-3 py-1.5 shadow-sm hover:bg-surface-container-lowest active:scale-95 select-all-role-btn" data-role="{{ $role->id }}">
                                                Chọn tất cả
                                            </button>
                                        </div>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/30">
                            @foreach($groupedPermissions as $groupName => $permissions)
                                <!-- Group Header -->
                                <tr class="bg-surface-container/40 group-header-row">
                                    <td class="p-3 font-title-sm font-bold text-on-surface border-r border-outline-variant/30 bg-surface-container/90 sticky left-0 z-20 flex justify-between items-center shadow-[1px_0_0_0_rgba(0,0,0,0.05)] backdrop-blur-sm">
                                        <span class="text-[14px] uppercase tracking-wide">{{ $groupName }}</span>
                                        <button type="button" class="text-[11px] uppercase font-bold text-primary bg-primary-container/50 hover:bg-primary-container text-on-primary-container px-2 py-1 rounded transition-colors select-all-group-btn border border-primary/20" data-group="{{ Str::slug($groupName) }}">
                                            Chọn nhóm này
                                        </button>
                                    </td>
                                    <td colspan="{{ count($roles) }}" class="p-3 bg-surface-container/40"></td>
                                </tr>
                                <!-- Group Items -->
                                @foreach($permissions as $permission)
                                    <tr class="hover:bg-surface-container-low/70 transition-colors permission-row group" data-group="{{ Str::slug($groupName) }}">
                                        <td class="p-4 border-r border-outline-variant/30 bg-white group-hover:bg-surface-container-low/70 transition-colors sticky left-0 z-10 shadow-[1px_0_0_0_rgba(0,0,0,0.05)]">
                                            <div class="font-body-md text-on-surface font-medium">{{ $permission->name }}</div>
                                            <div class="text-[11.5px] text-on-surface-variant mt-1.5 font-mono bg-surface-container-low inline-block px-1.5 py-0.5 rounded border border-outline-variant/20">{{ $permission->code }}</div>
                                        </td>
                                        
                                        @foreach($roles as $role)
                                            @php
                                                $toggleColor = 'peer-checked:bg-primary';
                                                if($role->code === 'admin') $toggleColor = 'peer-checked:bg-error';
                                                elseif($role->code === 'staff') $toggleColor = 'peer-checked:bg-primary';
                                                elseif($role->code === 'shipper') $toggleColor = 'peer-checked:bg-tertiary';
                                            @endphp
                                            <td class="p-4 text-center border-r border-outline-variant/30 last:border-0 hover:bg-black/5 transition-colors role-cell" data-cell-role="{{ $role->id }}">
                                                <div class="flex items-center justify-center w-full h-full">
                                                    <label class="relative inline-flex items-center cursor-pointer" title="Cấp quyền {{ $permission->name }} cho {{ $role->name }}">
                                                        <input type="checkbox" 
                                                               name="role_permissions[{{ $role->id }}][]" 
                                                               value="{{ $permission->id }}"
                                                               class="sr-only peer permission-checkbox"
                                                               data-role="{{ $role->id }}"
                                                               data-group="{{ Str::slug($groupName) }}"
                                                               {{ isset($matrix[$role->id][$permission->id]) ? 'checked' : '' }}>
                                                        <div class="w-12 h-6 bg-outline-variant/50 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all {{ $toggleColor }} shadow-inner"></div>
                                                    </label>
                                                </div>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Footer / Action Buttons -->
            <div class="flex justify-end gap-4 sticky bottom-8 z-50 bg-surface-container-lowest/80 backdrop-blur-md p-4 rounded-xl border border-outline-variant/30 shadow-lg">
                <button type="reset" class="px-6 py-2.5 rounded-xl border border-outline-variant text-on-surface-variant font-label-md hover:bg-surface-container-low transition-colors shadow-sm">
                    Khôi phục gốc
                </button>
                <button type="submit" class="px-8 py-2.5 rounded-xl bg-primary text-white font-label-md hover:bg-primary/90 transition-all shadow-md hover:shadow-lg active:scale-95 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">save</span>
                    Lưu các thay đổi
                </button>
            </div>
        </form>

    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // 1. Logic Chọn tất cả theo Cột (Role)
    document.querySelectorAll('.select-all-role-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const roleId = e.target.getAttribute('data-role');
            const checkboxes = document.querySelectorAll(`.permission-checkbox[data-role="${roleId}"]`);
            
            // Check if all are already checked
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            
            // Toggle state
            checkboxes.forEach(cb => cb.checked = !allChecked);
            
            // Update button text
            e.target.innerText = allChecked ? 'Chọn tất cả' : 'Bỏ chọn tất cả';
            
            if(!allChecked) {
                e.target.classList.add('bg-primary-container', 'text-on-primary-container', 'border-primary');
            } else {
                e.target.classList.remove('bg-primary-container', 'text-on-primary-container', 'border-primary');
            }
        });
    });

    // 2. Logic Chọn tất cả theo Hàng (Group)
    document.querySelectorAll('.select-all-group-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const group = e.target.getAttribute('data-group');
            const checkboxes = document.querySelectorAll(`.permission-checkbox[data-group="${group}"]`);
            
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            
            checkboxes.forEach(cb => cb.checked = !allChecked);
            
            e.target.innerText = allChecked ? 'Chọn nhóm này' : 'Bỏ chọn nhóm';
            
            if(!allChecked) {
                e.target.classList.remove('bg-primary-container/50', 'text-primary');
                e.target.classList.add('bg-primary', 'text-white');
            } else {
                e.target.classList.add('bg-primary-container/50', 'text-primary');
                e.target.classList.remove('bg-primary', 'text-white');
            }
        });
    });

    // 3. Crosshair Highlight Effect (Làm nổi bật cột khi hover vào ô)
    const table = document.getElementById('permissionsTable');
    
    table.addEventListener('mouseover', (e) => {
        const cell = e.target.closest('.role-cell');
        if (!cell) return;
        
        const roleId = cell.getAttribute('data-cell-role');
        const headerCell = document.querySelector(`th[data-col-role="${roleId}"]`);
        
        if (headerCell) {
            headerCell.classList.add('bg-surface-container', 'shadow-inner');
        }
    });
    
    table.addEventListener('mouseout', (e) => {
        const cell = e.target.closest('.role-cell');
        if (!cell) return;
        
        const roleId = cell.getAttribute('data-cell-role');
        const headerCell = document.querySelector(`th[data-col-role="${roleId}"]`);
        
        if (headerCell) {
            headerCell.classList.remove('bg-surface-container', 'shadow-inner');
        }
    });
});
</script>
@endsection
