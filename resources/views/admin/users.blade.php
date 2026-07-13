@extends('layouts.admin')

@section('title', 'Quản lý Người dùng')

@section('content')
<main class="ml-0 md:ml-[280px] pt-16 min-h-screen p-8 bg-surface-container-lowest">
    <div class="max-w-7xl mx-auto space-y-8">
        
        <!-- Header -->
        <div class="flex justify-between items-center border-b border-outline-variant/30 pb-4">
            <div>
                <h2 class="font-headline-md text-headline-md text-on-surface">Quản lý Người dùng</h2>
                <p class="font-body-md text-on-surface-variant">Phân quyền và quản lý tài khoản trên hệ thống</p>
            </div>
            <div class="flex gap-4 items-center">
                @if(session('success'))
                    <div class="bg-primary-container text-on-primary-container px-4 py-2 rounded-lg text-label-md">
                        {{ session('success') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="bg-error-container text-on-error-container px-4 py-2 rounded-lg text-label-md">
                        Có lỗi xảy ra, vui lòng kiểm tra lại!
                    </div>
                @endif
                @if(session('role_code') === 'admin')
                <button onclick="document.getElementById('addUserModal').classList.remove('hidden')" class="px-4 py-2 bg-primary text-white rounded-lg font-label-md hover:bg-primary/90 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    Thêm người dùng
                </button>
                @endif
            </div>
        </div>

        <!-- Add User Modal -->
        <div id="addUserModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
            <div class="bg-surface-container-lowest w-full max-w-md rounded-xl shadow-lg p-6 relative">
                <button onclick="document.getElementById('addUserModal').classList.add('hidden')" class="absolute top-4 right-4 text-on-surface-variant hover:text-on-surface">
                    <span class="material-symbols-outlined">close</span>
                </button>
                <h3 class="font-title-lg text-title-lg mb-6 text-on-surface">Thêm Người Dùng Mới</h3>
                
                <form action="/admin/users" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block font-label-md mb-1 text-on-surface-variant">Tên đăng nhập</label>
                        <input type="text" name="username" required value="{{ old('username') }}" class="w-full px-3 py-2 rounded-lg border border-outline-variant bg-surface focus:border-primary focus:ring-primary">
                        @error('username') <span class="text-error text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block font-label-md mb-1 text-on-surface-variant">Email</label>
                        <input type="email" name="email" required value="{{ old('email') }}" class="w-full px-3 py-2 rounded-lg border border-outline-variant bg-surface focus:border-primary focus:ring-primary">
                        @error('email') <span class="text-error text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block font-label-md mb-1 text-on-surface-variant">Số điện thoại</label>
                        <input type="text" name="phone" required value="{{ old('phone') }}" class="w-full px-3 py-2 rounded-lg border border-outline-variant bg-surface focus:border-primary focus:ring-primary">
                        @error('phone') <span class="text-error text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block font-label-md mb-1 text-on-surface-variant">Mật khẩu</label>
                        <input type="password" name="password" required class="w-full px-3 py-2 rounded-lg border border-outline-variant bg-surface focus:border-primary focus:ring-primary">
                        @error('password') <span class="text-error text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block font-label-md mb-1 text-on-surface-variant">Chức vụ (Role)</label>
                        <select name="role_id" required class="w-full px-3 py-2 rounded-lg border border-outline-variant bg-surface focus:border-primary focus:ring-primary">
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="pt-4 flex justify-end gap-3">
                        <button type="button" onclick="document.getElementById('addUserModal').classList.add('hidden')" class="px-4 py-2 border border-outline-variant rounded-lg font-label-md hover:bg-surface-container-low">Hủy</button>
                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg font-label-md hover:bg-primary/90">Tạo tài khoản</button>
                    </div>
                </form>
            </div>
        </div>

        @if($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('addUserModal').classList.remove('hidden');
            });
        </script>
        @endif

        <!-- Tabs Header -->
        <div class="flex bg-surface-container-low p-1 rounded-lg w-max mb-4">
            <button class="tab-btn px-4 py-1.5 rounded-md font-label-md transition-all bg-white shadow-sm text-primary" data-tab="employees" type="button" onclick="switchTab('employees')">Nhân viên</button>
            <button class="tab-btn px-4 py-1.5 rounded-md font-label-md transition-all text-on-surface-variant hover:text-on-surface" data-tab="customers" type="button" onclick="switchTab('customers')">Khách hàng</button>
        </div>

        <script>
            function switchTab(tabId) {
                // Reset tabs
                document.querySelectorAll('.tab-btn').forEach(btn => {
                    btn.classList.remove('bg-white', 'shadow-sm', 'text-primary');
                    btn.classList.add('text-on-surface-variant', 'hover:text-on-surface');
                });
                // Set active tab
                const activeBtn = document.querySelector(`.tab-btn[data-tab="${tabId}"]`);
                activeBtn.classList.remove('text-on-surface-variant', 'hover:text-on-surface');
                activeBtn.classList.add('bg-white', 'shadow-sm', 'text-primary');

                // Hide all contents
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.add('hidden');
                });
                // Show active content
                document.getElementById(`${tabId}-content`).classList.remove('hidden');
            }
        </script>

        <!-- Bảng danh sách Nhân viên -->
        <div id="employees-content" class="tab-content bg-white rounded-xl border border-outline-variant/30 shadow-sm overflow-hidden">
            <div class="overflow-x-auto pb-32">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low border-b border-outline-variant/30">
                            <th class="p-4 font-label-sm text-on-surface-variant uppercase tracking-wider">ID</th>
                            <th class="p-4 font-label-sm text-on-surface-variant uppercase tracking-wider">Tên & Email</th>
                            <th class="p-4 font-label-sm text-on-surface-variant uppercase tracking-wider">Số điện thoại</th>
                            <th class="p-4 font-label-sm text-on-surface-variant uppercase tracking-wider">Quyền hiện tại</th>
                            @if(check_permission('assign_roles'))
                            <th class="p-4 font-label-sm text-on-surface-variant uppercase tracking-wider">Cập nhật quyền</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/30">
                        @forelse($staffUsers as $user)
                        <tr class="hover:bg-surface-container-low/50 transition-colors">
                            <td class="p-4 text-body-md text-on-surface font-semibold">#{{ $user->id }}</td>
                            <td class="p-4">
                                <div class="font-label-md text-on-surface">{{ $user->username }}</div>
                                <div class="font-body-sm text-on-surface-variant text-sm">{{ $user->email }}</div>
                            </td>
                            <td class="p-4 text-body-md text-on-surface">{{ $user->phone }}</td>
                            <td class="p-4">
                                @if($user->role)
                                    <span class="px-2 py-1 rounded-md text-xs font-semibold
                                        @if($user->role->code === 'admin') bg-error-container text-error
                                        @elseif($user->role->code === 'staff') bg-primary-container text-primary
                                        @elseif($user->role->code === 'shipper') bg-tertiary-container text-tertiary
                                        @else bg-surface-variant text-on-surface-variant @endif
                                    ">
                                        {{ $user->role->name }}
                                    </span>
                                @else
                                    <span class="text-on-surface-variant text-sm italic">Không có</span>
                                @endif
                            </td>
                            @if(check_permission('assign_roles'))
                            <td class="p-4">
                                <form action="/admin/users/{{ $user->id }}/role" method="POST" class="flex items-center gap-2 m-0">
                                    @csrf
                                    <select name="role_id" class="px-3 py-1.5 rounded-lg border border-outline-variant text-sm bg-surface focus:ring-primary focus:border-primary">
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="px-3 py-1.5 bg-primary text-white text-sm rounded-lg hover:bg-primary/90 transition-colors">
                                        Lưu
                                    </button>
                                </form>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-on-surface-variant">Chưa có người dùng nào.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Bảng danh sách Khách hàng -->
        <div id="customers-content" class="tab-content hidden bg-white rounded-xl border border-outline-variant/30 shadow-sm overflow-hidden">
            <div class="overflow-x-auto pb-32">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low border-b border-outline-variant/30">
                            <th class="p-4 font-label-sm text-on-surface-variant uppercase tracking-wider">ID</th>
                            <th class="p-4 font-label-sm text-on-surface-variant uppercase tracking-wider">Tên & Email</th>
                            <th class="p-4 font-label-sm text-on-surface-variant uppercase tracking-wider">Số điện thoại</th>
                            <th class="p-4 font-label-sm text-on-surface-variant uppercase tracking-wider">Quyền hiện tại</th>
                            @if(check_permission('assign_roles'))
                            <th class="p-4 font-label-sm text-on-surface-variant uppercase tracking-wider">Cập nhật quyền</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/30">
                        @forelse($customerUsers as $user)
                        <tr class="hover:bg-surface-container-low/50 transition-colors">
                            <td class="p-4 text-body-md text-on-surface font-semibold">#{{ $user->id }}</td>
                            <td class="p-4">
                                <div class="font-label-md text-on-surface">{{ $user->username }}</div>
                                <div class="font-body-sm text-on-surface-variant text-sm">{{ $user->email }}</div>
                            </td>
                            <td class="p-4 text-body-md text-on-surface">{{ $user->phone }}</td>
                            <td class="p-4">
                                @if($user->role)
                                    <span class="px-2 py-1 rounded-md text-xs font-semibold
                                        @if($user->role->code === 'admin') bg-error-container text-error
                                        @elseif($user->role->code === 'staff') bg-primary-container text-primary
                                        @elseif($user->role->code === 'shipper') bg-tertiary-container text-tertiary
                                        @else bg-surface-variant text-on-surface-variant @endif
                                    ">
                                        {{ $user->role->name }}
                                    </span>
                                @else
                                    <span class="text-on-surface-variant text-sm italic">Không có</span>
                                @endif
                            </td>
                            @if(check_permission('assign_roles'))
                            <td class="p-4">
                                <form action="/admin/users/{{ $user->id }}/role" method="POST" class="flex items-center gap-2 m-0">
                                    @csrf
                                    <select name="role_id" class="px-3 py-1.5 rounded-lg border border-outline-variant text-sm bg-surface focus:ring-primary focus:border-primary">
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="px-3 py-1.5 bg-primary text-white text-sm rounded-lg hover:bg-primary/90 transition-colors">
                                        Lưu
                                    </button>
                                </form>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-on-surface-variant">Chưa có người dùng nào.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
@endsection
