@extends('layouts.admin')

@section('content')
<main class="md:ml-[280px] pt-24 pb-lg min-h-screen bg-background text-on-surface">
    <div class="px-4 md:px-lg max-w-container-max mx-auto space-y-lg">
        <!-- Header -->
        <header class="flex flex-col md:flex-row md:items-center justify-between gap-md">
            <div>
                <h1 class="font-headline-md text-headline-md font-bold text-on-background">Quản lý Bàn</h1>
                <p class="font-body-md text-on-surface-variant">Quản lý danh sách bàn và mã QR đặt món.</p>
            </div>
            <button onclick="toggleModal('addTableModal')" class="bg-primary text-on-primary px-lg py-sm rounded-xl font-semibold flex items-center gap-xs hover:bg-opacity-90 transition-opacity shadow-md">
                <span class="material-symbols-outlined">add</span>
                Thêm Bàn
            </button>
        </header>

        @if(session('success'))
            <div class="bg-primary-container text-on-primary-container p-4 rounded-xl mb-4 font-body-md">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-error-container text-on-error-container p-4 rounded-xl mb-4 font-body-md">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="bg-error-container text-on-error-container p-4 rounded-xl mb-4 font-body-md">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-surface-container-low border-b border-outline-variant/30">
                        <tr>
                            <th class="px-lg py-md font-label-sm text-on-surface-variant uppercase tracking-wider">ID</th>
                            <th class="px-lg py-md font-label-sm text-on-surface-variant uppercase tracking-wider">Tên Bàn</th>
                            <th class="px-lg py-md font-label-sm text-on-surface-variant uppercase tracking-wider">Tài khoản</th>
                            <th class="px-lg py-md font-label-sm text-on-surface-variant uppercase tracking-wider">Trạng thái</th>
                            <th class="px-lg py-md font-label-sm text-on-surface-variant uppercase tracking-wider">Mã QR</th>
                            <th class="px-lg py-md font-label-sm text-on-surface-variant uppercase tracking-wider text-right">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/20">
                        @foreach($tables as $table)
                        <tr class="transition-colors hover:bg-surface-container-low">
                            <td class="px-lg py-lg font-body-md">{{ $table->id }}</td>
                            <td class="px-lg py-lg font-body-md font-semibold text-primary">{{ $table->name }}</td>
                            <td class="px-lg py-lg font-body-md">{{ $table->user ? $table->user->username : 'N/A' }}</td>
                            <td class="px-lg py-lg">
                                @if($table->status)
                                    <span class="inline-flex items-center gap-xs px-md py-1 rounded-full bg-primary-container text-on-primary-container font-semibold text-xs border border-outline-variant/30">Đang hoạt động</span>
                                @else
                                    <span class="inline-flex items-center gap-xs px-md py-1 rounded-full bg-error-container text-on-error-container font-semibold text-xs border border-outline-variant/30">Ngừng hoạt động</span>
                                @endif
                            </td>
                            <td class="px-lg py-lg">
                                <div class="flex gap-4 items-center">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(url('/table/login/'.$table->qr_token)) }}" alt="QR Code" class="w-16 h-16 rounded border border-outline-variant">
                                    <div class="flex flex-col gap-2">
                                        <a href="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ urlencode(url('/table/login/'.$table->qr_token)) }}" target="_blank" class="text-primary hover:underline font-label-md flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[16px]">download</span> Tải QR
                                        </a>
                                        <a href="{{ url('/table/login/'.$table->qr_token) }}" target="_blank" class="text-secondary hover:underline font-label-md flex items-center gap-1" title="Mở link đăng nhập của bàn này (Dùng để test trên máy tính)">
                                            <span class="material-symbols-outlined text-[16px]">login</span> Mở test
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td class="px-lg py-lg text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <form action="{{ url('/admin/tables/'.$table->id.'/qr') }}" method="POST" class="inline-block m-0">
                                        @csrf
                                        <button type="submit" class="p-2 bg-secondary-container text-on-secondary-container rounded-lg hover:bg-opacity-90 transition-colors flex items-center justify-center" onclick="return confirm('Bạn có chắc muốn tạo lại mã QR? Mã cũ sẽ không dùng được nữa.')" title="Tạo lại QR">
                                            <span class="material-symbols-outlined text-[20px]">refresh</span>
                                        </button>
                                    </form>
                                    <form action="{{ url('/admin/tables/'.$table->id) }}" method="POST" class="inline-block m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-error-container text-on-error-container rounded-lg hover:bg-opacity-90 transition-colors flex items-center justify-center" onclick="return confirm('Bạn có chắc muốn xóa bàn này? Tài khoản liên kết cũng sẽ bị xóa.')" title="Xóa bàn">
                                            <span class="material-symbols-outlined text-[20px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</main>

<!-- Modal Thêm Bàn -->
<div id="addTableModal" class="fixed inset-0 z-50 flex items-center justify-center bg-on-background/50 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300">
    <div class="bg-surface rounded-2xl shadow-lg border border-outline-variant/30 w-[400px] max-w-[90vw] overflow-hidden transform translate-y-4 transition-transform duration-300">
        <div class="p-md border-b border-outline-variant/30 flex justify-between items-center bg-surface-container-low">
            <h3 class="font-title-lg font-semibold text-on-surface">Thêm Bàn Mới</h3>
            <button onclick="toggleModal('addTableModal')" class="text-on-surface-variant hover:text-on-surface transition-colors p-1 rounded-full hover:bg-surface-container">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form action="{{ url('/admin/tables') }}" method="POST" class="p-md">
            @csrf
            <div class="mb-lg">
                <label for="name" class="block font-label-md text-on-surface-variant mb-xs">Tên Bàn (VD: Bàn 1)</label>
                <input type="text" id="name" name="name" class="w-full bg-surface border border-outline-variant rounded-lg px-md py-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all text-on-surface" required>
                <p class="text-xs text-on-surface-variant mt-2">Hệ thống sẽ tự động tạo một tài khoản người dùng tương ứng cho bàn này.</p>
            </div>
            <div class="flex justify-end gap-sm mt-lg">
                <button type="button" onclick="toggleModal('addTableModal')" class="px-lg py-sm font-label-md text-on-surface-variant border border-outline-variant rounded-xl hover:bg-surface-container transition-colors">Hủy</button>
                <button type="submit" class="px-lg py-sm font-label-md bg-primary text-on-primary rounded-xl hover:bg-opacity-90 shadow-sm transition-all">Lưu</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function toggleModal(modalId) {
        const modal = document.getElementById(modalId);
        const content = modal.firstElementChild;
        if (modal.classList.contains('opacity-0')) {
            modal.classList.remove('opacity-0', 'pointer-events-none');
            content.classList.remove('translate-y-4');
        } else {
            modal.classList.add('opacity-0', 'pointer-events-none');
            content.classList.add('translate-y-4');
        }
    }
</script>
@endpush
@endsection
