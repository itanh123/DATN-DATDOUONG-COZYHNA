@extends('layouts.admin')

@section('title', 'Quản lý chức vụ')

@section('content')
<main class="ml-0 md:ml-[280px] pt-16 min-h-screen p-8 bg-surface-container-lowest">
    <div class="max-w-7xl mx-auto space-y-6">
        
        <!-- Header -->
        <div class="flex justify-between items-center border-b border-outline-variant/30 pb-4">
            <div class="flex items-center justify-between w-full">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary text-3xl">shield_person</span>
                    <h2 class="font-headline-md text-headline-md text-on-surface">Quản lý chức vụ</h2>
                </div>
                <button type="button" onclick="openModal('modal-add-role')" class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition-colors">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    Thêm chức vụ
                </button>
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

        <!-- Info Alert -->
        <div class="bg-primary-container border border-primary/20 text-on-primary-container px-4 py-3 rounded-lg shadow-sm flex items-start gap-2">
            <span class="material-symbols-outlined text-[20px] mt-0.5">info</span>
            <p class="text-sm font-medium"><strong>Lưu ý:</strong> Admin có tất cả quyền và không cần phân quyền. Bạn có thể phân quyền cho từng Quản lý cụ thể hoặc cấu hình quyền mặc định cho Nhân viên.</p>
        </div>

        @foreach($roles as $role)
            @php
                $headerBg = 'bg-surface-container-low';
                $headerText = 'text-on-surface';
                $badgeBg = 'bg-primary-container text-on-primary-container';
                $permissionCount = $rolePermissionCount[$role->id] ?? 0;
            @endphp
            
            <div class="bg-white rounded-xl border border-outline-variant/30 shadow-md overflow-hidden relative">
                <!-- Role Header -->
                <div class="{{ $headerBg }} px-4 py-3 flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        @if($role->code === 'staff')
                            <span class="material-symbols-outlined {{ $headerText }}">group</span>
                        @else
                            <span class="material-symbols-outlined {{ $headerText }}">badge</span>
                        @endif
                        <h3 class="text-lg font-bold {{ $headerText }}">{{ $role->name }} ({{ ucfirst($role->code) }})</h3>
                        <span class="{{ $badgeBg }} text-xs px-2.5 py-1 rounded-md ml-2 font-medium shadow-sm">{{ $role->users->count() }} tài khoản</span>
                    </div>
                </div>

                <div class="p-5 text-on-surface">
                    <p class="text-sm text-on-surface-variant mb-4">Quyền mặc định cho tất cả {{ $role->name }}. Tất cả tài khoản có role "{{ $role->name }}" sẽ tự động có các quyền này.</p>
                    
                    <div class="flex justify-between items-center mb-4">
                        <div class="inline-flex items-center gap-2 bg-primary-container text-primary px-3 py-1.5 rounded-md text-sm font-medium">
                            <span class="material-symbols-outlined text-[18px]">verified_user</span>
                            {{ $permissionCount }} quyền được gán
                        </div>
                        
                        <div class="flex gap-2">
                            <button type="button" onclick="openModal('modal-{{ $role->id }}')" class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition-colors">
                                <span class="material-symbols-outlined text-[18px]">settings</span>
                                Cấu hình quyền {{ $role->name }}
                            </button>
                            @if(!in_array($role->code, ['admin', 'customer']))
                            <form action="/admin/roles/{{ $role->id }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa chức vụ này không?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-error hover:bg-error/90 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                    Xóa
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-lg border border-outline-variant/30">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-surface-container-low text-on-surface">
                                <tr>
                                    <th class="p-3 font-semibold w-16 border-b border-outline-variant/30">ID</th>
                                    <th class="p-3 font-semibold border-b border-outline-variant/30">Tên</th>
                                    <th class="p-3 font-semibold border-b border-outline-variant/30">Email</th>
                                    <th class="p-3 font-semibold border-b border-outline-variant/30">Trạng thái</th>
                                    <th class="p-3 font-semibold border-b border-outline-variant/30">Quyền</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant/30 bg-white">
                                @forelse($role->users as $user)
                                    <tr class="hover:bg-surface-container-low/50 transition-colors">
                                        <td class="p-3 text-on-surface-variant">{{ $user->id }}</td>
                                        <td class="p-3 font-medium text-on-surface">{{ $user->name }}</td>
                                        <td class="p-3 text-on-surface-variant">{{ $user->email }}</td>
                                        <td class="p-3">
                                            @if($user->status)
                                                <span class="bg-green-100 text-green-700 border border-green-200 px-2.5 py-1 rounded-md text-[11px] font-bold uppercase">Hoạt động</span>
                                            @else
                                                <span class="bg-red-100 text-red-700 border border-red-200 px-2.5 py-1 rounded-md text-[11px] font-bold uppercase">Đã khóa</span>
                                            @endif
                                        </td>
                                        <td class="p-3">
                                            <span class="bg-primary/10 text-primary border border-primary/20 px-2 py-0.5 rounded-md text-[11px] font-medium">{{ $permissionCount }} quyền mặc định</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-4 text-center text-on-surface-variant text-sm">Không có tài khoản nào thuộc chức vụ này</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Modal for this role -->
            <div id="modal-{{ $role->id }}" class="fixed inset-0 z-[100] hidden">
                <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeModal('modal-{{ $role->id }}')"></div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-4xl bg-white rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[85vh]">
                    <div class="px-6 py-4 border-b flex justify-between items-center bg-surface-container-lowest">
                        <h3 class="text-xl font-bold text-on-surface">Cấu hình quyền - {{ $role->name }}</h3>
                        <button type="button" onclick="closeModal('modal-{{ $role->id }}')" class="text-on-surface-variant hover:text-error transition-colors p-1 rounded-md hover:bg-surface-container-low">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>
                    
                    <div class="p-6 overflow-y-auto flex-1 bg-surface-container-lowest">
                        <form action="/admin/roles/update" method="POST" id="form-{{ $role->id }}">
                            @csrf
                            <input type="hidden" name="role_id" value="{{ $role->id }}">
                            
                            <div class="flex justify-between items-center mb-6 bg-surface-container-low p-4 rounded-xl border border-outline-variant/30 shadow-sm">
                                <div>
                                    <h4 class="font-bold text-on-surface">Tùy chỉnh phân quyền</h4>
                                    <p class="text-sm text-on-surface-variant">Đánh dấu vào các ô bên dưới để gán quyền cho chức vụ này.</p>
                                </div>
                                <button type="button" onclick="selectAll('{{ $role->id }}')" class="text-sm font-medium text-primary hover:text-primary/80 transition-colors bg-primary-container px-4 py-2 rounded-lg" id="select-all-btn-{{ $role->id }}">Chọn tất cả quyền</button>
                            </div>

                            <div class="grid grid-cols-1 gap-6">
                                @foreach($groupedPermissions as $groupName => $perms)
                                    <div class="border border-outline-variant/30 rounded-xl overflow-hidden shadow-sm bg-white">
                                        <div class="bg-surface-container-low px-5 py-3 flex justify-between items-center border-b border-outline-variant/30">
                                            <div class="flex items-center gap-2">
                                                <span class="material-symbols-outlined text-primary text-xl">folder_special</span>
                                                <h4 class="font-bold text-on-surface text-[15px] uppercase tracking-wide">{{ $groupName }}</h4>
                                            </div>
                                            <button type="button" onclick="selectGroup('{{ $role->id }}', '{{ Str::slug($groupName) }}')" class="text-[13px] font-medium text-primary hover:underline px-2 py-1 bg-surface-container-lowest rounded border border-outline-variant/30">Chọn nhóm này</button>
                                        </div>
                                        <div class="p-5 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-y-4 gap-x-6">
                                            @foreach($perms as $permission)
                                                <label class="flex items-start gap-3 cursor-pointer group hover:bg-surface-container-low/50 p-2 rounded-lg transition-colors -m-2">
                                                    <div class="relative flex items-center mt-0.5">
                                                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" 
                                                               class="peer h-5 w-5 cursor-pointer appearance-none rounded border-2 border-outline-variant checked:border-primary checked:bg-primary transition-all role-{{ $role->id }}-checkbox group-{{ Str::slug($groupName) }}-{{ $role->id }}"
                                                               {{ isset($matrix[$role->id][$permission->id]) ? 'checked' : '' }}>
                                                        <span class="absolute text-white opacity-0 peer-checked:opacity-100 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 pointer-events-none">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" stroke="currentColor" stroke-width="2">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <div class="text-[14px] font-semibold text-on-surface group-hover:text-primary transition-colors leading-tight">{{ $permission->name }}</div>
                                                        <div class="text-[11px] text-on-surface-variant font-mono mt-1 bg-surface-container-lowest inline-block px-1.5 rounded border border-outline-variant/30">{{ $permission->code }}</div>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </form>
                    </div>
                    
                    <div class="px-6 py-4 border-t bg-surface-container-lowest flex justify-end gap-3 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] z-10">
                        <button type="button" onclick="closeModal('modal-{{ $role->id }}')" class="px-5 py-2.5 rounded-lg font-medium text-on-surface-variant hover:bg-surface-container-low border border-outline-variant/30 transition-colors">
                            Hủy bỏ
                        </button>
                        <button type="button" onclick="document.getElementById('form-{{ $role->id }}').submit()" class="px-6 py-2.5 rounded-lg font-medium text-white bg-primary hover:bg-primary/90 shadow-md hover:shadow-lg transition-all flex items-center gap-2 active:scale-95">
                            <span class="material-symbols-outlined text-[20px]">save</span>
                            Lưu cấu hình quyền
                        </button>
                    </div>
                </div>
            </div>
        @endforeach

    </div>

    <!-- Modal for adding new role -->
    <div id="modal-add-role" class="fixed inset-0 z-[100] hidden">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeModal('modal-add-role')"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md bg-white rounded-xl shadow-2xl overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b flex justify-between items-center bg-surface-container-lowest">
                <h3 class="text-xl font-bold text-on-surface">Thêm chức vụ mới</h3>
                <button type="button" onclick="closeModal('modal-add-role')" class="text-on-surface-variant hover:text-error transition-colors p-1 rounded-md hover:bg-surface-container-low">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <div class="p-6 bg-surface-container-lowest">
                <form action="/admin/roles/store" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-on-surface mb-1">Tên chức vụ <span class="text-error">*</span></label>
                            <input type="text" name="name" required placeholder="Ví dụ: Kế toán" class="w-full px-4 py-2 border border-outline-variant/50 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/50 transition-colors bg-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-on-surface mb-1">Mã chức vụ (code) <span class="text-error">*</span></label>
                            <input type="text" name="code" required placeholder="Ví dụ: ketoan" class="w-full px-4 py-2 border border-outline-variant/50 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/50 transition-colors bg-white">
                            <p class="text-xs text-on-surface-variant mt-1">Dùng để phân biệt trong hệ thống (viết liền không dấu).</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-on-surface mb-1">Mô tả</label>
                            <textarea name="description" rows="3" placeholder="Mô tả công việc của chức vụ này..." class="w-full px-4 py-2 border border-outline-variant/50 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/50 transition-colors bg-white"></textarea>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" onclick="closeModal('modal-add-role')" class="px-4 py-2 text-on-surface-variant font-medium hover:bg-surface-container-low rounded-lg transition-colors">
                            Hủy bỏ
                        </button>
                        <button type="submit" class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg font-medium flex items-center gap-2 transition-colors">
                            <span class="material-symbols-outlined text-[20px]">add_circle</span>
                            Tạo chức vụ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
    function openModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        }
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }

    function selectAll(roleId) {
        const checkboxes = document.querySelectorAll(`.role-${roleId}-checkbox`);
        const btn = document.getElementById(`select-all-btn-${roleId}`);
        const allChecked = Array.from(checkboxes).every(cb => cb.checked);
        
        checkboxes.forEach(cb => cb.checked = !allChecked);
        btn.innerText = allChecked ? 'Chọn tất cả quyền' : 'Bỏ chọn tất cả';
    }

    function selectGroup(roleId, groupSlug) {
        const checkboxes = document.querySelectorAll(`.group-${groupSlug}-${roleId}`);
        const allChecked = Array.from(checkboxes).every(cb => cb.checked);
        checkboxes.forEach(cb => cb.checked = !allChecked);
    }
</script>
@endsection
