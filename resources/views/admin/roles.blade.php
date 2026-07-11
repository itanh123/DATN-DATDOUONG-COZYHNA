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
                <div class="bg-primary-container text-on-primary-container px-4 py-2 rounded-lg text-label-md">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-error-container text-on-error-container px-4 py-2 rounded-lg text-label-md">
                    {{ session('error') }}
                </div>
            @endif
        </div>

        <form action="/admin/roles/update" method="POST">
            @csrf
            
            <div class="bg-white rounded-xl border border-outline-variant/30 shadow-sm overflow-hidden mb-6">
                <div class="overflow-x-auto pb-32">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-container-low border-b border-outline-variant/30">
                                <th class="p-4 font-label-sm text-on-surface-variant uppercase tracking-wider border-r border-outline-variant/30 w-64 bg-surface-container-lowest sticky left-0 z-10 shadow-[1px_0_0_0_rgba(0,0,0,0.05)]">Chức năng (Permission)</th>
                                @foreach($roles as $role)
                                    <th class="p-4 text-center border-r border-outline-variant/30 last:border-0 min-w-[120px]">
                                        <div class="font-title-md text-on-surface font-semibold flex flex-col items-center gap-1">
                                            @if($role->code === 'admin')
                                                <span class="material-symbols-outlined text-error text-[24px]">admin_panel_settings</span>
                                            @elseif($role->code === 'staff')
                                                <span class="material-symbols-outlined text-primary text-[24px]">badge</span>
                                            @elseif($role->code === 'shipper')
                                                <span class="material-symbols-outlined text-tertiary text-[24px]">local_shipping</span>
                                            @else
                                                <span class="material-symbols-outlined text-outline text-[24px]">person</span>
                                            @endif
                                            <span>{{ $role->name }}</span>
                                        </div>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/30">
                            @foreach($groupedPermissions as $groupName => $permissions)
                                <!-- Group Header -->
                                <tr class="bg-surface-container/30">
                                    <td colspan="{{ count($roles) + 1 }}" class="p-3 font-title-sm font-semibold text-on-surface border-y border-outline-variant/30">
                                        {{ $groupName }}
                                    </td>
                                </tr>
                                <!-- Group Items -->
                                @foreach($permissions as $permission)
                                    <tr class="hover:bg-surface-container-low/50 transition-colors">
                                        <td class="p-4 border-r border-outline-variant/30 bg-white sticky left-0 z-10 shadow-[1px_0_0_0_rgba(0,0,0,0.05)]">
                                            <div class="font-body-md text-on-surface">{{ $permission->name }}</div>
                                            <div class="text-[11px] text-on-surface-variant mt-0.5 font-mono">{{ $permission->code }}</div>
                                        </td>
                                        
                                        @foreach($roles as $role)
                                            <td class="p-4 text-center border-r border-outline-variant/30 last:border-0">
                                                <label class="flex items-center justify-center cursor-pointer p-2 rounded hover:bg-surface-container transition-colors w-full h-full">
                                                    <input type="checkbox" 
                                                           name="role_permissions[{{ $role->id }}][]" 
                                                           value="{{ $permission->id }}"
                                                           class="w-5 h-5 text-primary rounded border-outline-variant focus:ring-primary"
                                                           {{ isset($matrix[$role->id][$permission->id]) ? 'checked' : '' }}>
                                                </label>
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
            <div class="flex justify-end gap-4">
                <button type="reset" class="px-6 py-2 rounded-lg border border-outline-variant text-on-surface-variant font-label-md hover:bg-surface-container-low transition-colors">
                    Hủy bỏ
                </button>
                <button type="submit" class="px-6 py-2 rounded-lg bg-primary text-white font-label-md hover:bg-primary/90 transition-colors shadow-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    Lưu thay đổi
                </button>
            </div>
        </form>

    </div>
</main>
@endsection
