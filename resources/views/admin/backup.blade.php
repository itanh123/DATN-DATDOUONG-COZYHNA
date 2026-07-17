@extends('layouts.admin')
@section('title', 'Sao lưu & Khôi phục')
@section('content')
<main class="md:ml-[280px] p-lg min-h-screen">
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="mb-2xl">
            <h1 class="font-headline-lg text-headline-lg text-on-surface">Sao lưu & Khôi phục</h1>
            <p class="font-body-md text-on-surface-variant">Quản lý các bản sao lưu cơ sở dữ liệu của hệ thống</p>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
        <div class="mb-lg p-md bg-primary-container text-on-primary-container rounded-xl flex items-center gap-sm">
            <span class="material-symbols-outlined">check_circle</span>
            <span class="font-body-md">{{ session('success') }}</span>
        </div>
        @endif
        @if(session('error'))
        <div class="mb-lg p-md bg-error-container text-on-error-container rounded-xl flex items-center gap-sm">
            <span class="material-symbols-outlined">error</span>
            <span class="font-body-md">{{ session('error') }}</span>
        </div>
        @endif

        <!-- Action Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-lg mb-2xl">
            <!-- Create Backup Card -->
            <div class="bg-white border border-outline-variant/30 rounded-2xl p-xl">
                <div class="flex items-center gap-md mb-lg">
                    <div class="w-12 h-12 bg-primary-container rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary">backup</span>
                    </div>
                    <div>
                        <h2 class="font-title-lg text-title-lg text-on-surface">Tạo bản sao lưu</h2>
                        <p class="text-on-surface-variant text-body-md">Sao chép toàn bộ CSDL hiện tại</p>
                    </div>
                </div>
                <p class="text-on-surface-variant text-body-md mb-lg">
                    File sao lưu sẽ được lưu trên server và tự động tải xuống máy tính của bạn.
                </p>
                <form action="/admin/backup/create" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-primary text-on-primary py-md rounded-xl font-bold hover:shadow-lg transition-all active:scale-[0.98] flex items-center justify-center gap-sm">
                        <span class="material-symbols-outlined">add_circle</span>
                        Tạo bản sao lưu mới
                    </button>
                </form>
            </div>

            <!-- Upload Restore Card -->
            <div class="bg-white border border-outline-variant/30 rounded-2xl p-xl">
                <div class="flex items-center gap-md mb-lg">
                    <div class="w-12 h-12 bg-tertiary-container rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-on-tertiary-container">upload_file</span>
                    </div>
                    <div>
                        <h2 class="font-title-lg text-title-lg text-on-surface">Khôi phục từ file</h2>
                        <p class="text-on-surface-variant text-body-md">Upload file .sqlite từ máy tính</p>
                    </div>
                </div>
                <form action="/admin/backup/upload" method="POST" enctype="multipart/form-data" id="uploadForm">
                    @csrf
                    <label for="backup_file" class="block w-full border-2 border-dashed border-outline-variant/50 rounded-xl p-lg text-center cursor-pointer hover:border-primary hover:bg-primary/5 transition-all mb-md" id="dropZone">
                        <span class="material-symbols-outlined text-3xl text-outline mb-sm block">cloud_upload</span>
                        <span class="text-on-surface-variant text-body-md block" id="fileLabel">Kéo thả hoặc click để chọn file .sqlite</span>
                        <input type="file" name="backup_file" id="backup_file" accept=".sqlite" class="hidden" onchange="updateFileLabel(this)">
                    </label>
                    <button type="submit" id="uploadBtn" disabled class="w-full bg-tertiary text-on-tertiary py-md rounded-xl font-bold transition-all active:scale-[0.98] flex items-center justify-center gap-sm disabled:opacity-50 disabled:cursor-not-allowed hover:shadow-lg">
                        <span class="material-symbols-outlined">restore</span>
                        Upload & Khôi phục
                    </button>
                </form>
            </div>
        </div>

        <!-- Backup List -->
        <div class="bg-white border border-outline-variant/30 rounded-2xl overflow-hidden">
            <div class="p-xl border-b border-outline-variant/20">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-md">
                        <span class="material-symbols-outlined text-primary">folder</span>
                        <h2 class="font-title-lg text-title-lg text-on-surface">Danh sách bản sao lưu</h2>
                    </div>
                    <span class="text-on-surface-variant text-body-md">{{ count($backups) }} bản</span>
                </div>
            </div>

            @if(count($backups) > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-surface-container-low text-on-surface-variant text-label-md text-left">
                            <th class="px-xl py-md font-medium">Tên file</th>
                            <th class="px-xl py-md font-medium">Dung lượng</th>
                            <th class="px-xl py-md font-medium">Ngày tạo</th>
                            <th class="px-xl py-md font-medium text-right">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($backups as $backup)
                        <tr class="border-t border-outline-variant/10 hover:bg-surface-container-low/50 transition-colors">
                            <td class="px-xl py-md">
                                <div class="flex items-center gap-sm">
                                    <span class="material-symbols-outlined text-outline text-[20px]">description</span>
                                    <span class="font-body-md text-on-surface">{{ $backup['filename'] }}</span>
                                </div>
                            </td>
                            <td class="px-xl py-md text-on-surface-variant text-body-md">{{ $backup['size_human'] }}</td>
                            <td class="px-xl py-md text-on-surface-variant text-body-md">{{ $backup['created_at'] }}</td>
                            <td class="px-xl py-md">
                                <div class="flex items-center gap-xs justify-end">
                                    <!-- Download -->
                                    <a href="/admin/backup/download/{{ $backup['filename'] }}" class="p-2 rounded-lg text-primary hover:bg-primary-container transition-colors" title="Tải xuống">
                                        <span class="material-symbols-outlined text-[20px]">download</span>
                                    </a>
                                    <!-- Restore -->
                                    <button onclick="confirmRestore('{{ $backup['filename'] }}')" class="p-2 rounded-lg text-tertiary hover:bg-tertiary-container transition-colors" title="Khôi phục">
                                        <span class="material-symbols-outlined text-[20px]">restore</span>
                                    </button>
                                    <!-- Delete -->
                                    <button onclick="confirmDelete('{{ $backup['filename'] }}')" class="p-2 rounded-lg text-error hover:bg-error-container transition-colors" title="Xoá">
                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="p-2xl text-center">
                <span class="material-symbols-outlined text-5xl text-outline/40 mb-md block">inventory_2</span>
                <p class="text-on-surface-variant font-body-md">Chưa có bản sao lưu nào. Hãy tạo bản sao lưu đầu tiên!</p>
            </div>
            @endif
        </div>
    </div>
</main>

<!-- Restore Confirmation Modal -->
<div id="restoreModal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl p-xl max-w-md w-full mx-4 shadow-2xl">
        <div class="flex items-center gap-md mb-lg">
            <div class="w-12 h-12 bg-tertiary-container rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-on-tertiary-container">warning</span>
            </div>
            <h3 class="font-title-lg text-title-lg text-on-surface">Xác nhận khôi phục</h3>
        </div>
        <p class="text-on-surface-variant text-body-md mb-md">
            Bạn có chắc chắn muốn khôi phục từ bản sao lưu <strong id="restoreFilename" class="text-on-surface"></strong>?
        </p>
        <p class="text-on-surface-variant text-body-md mb-xl p-md bg-surface-container-low rounded-lg">
            <span class="material-symbols-outlined text-[16px] align-text-bottom">info</span>
            Hệ thống sẽ tự động tạo bản sao lưu hiện tại trước khi khôi phục để đề phòng sai sót.
        </p>
        <div class="flex gap-md">
            <button onclick="closeModal('restoreModal')" class="flex-1 py-md border-2 border-outline-variant/30 rounded-xl font-bold text-on-surface-variant hover:bg-surface-container transition-colors">Huỷ</button>
            <form id="restoreForm" action="/admin/backup/restore" method="POST" class="flex-1">
                @csrf
                <input type="hidden" name="filename" id="restoreInput">
                <button type="submit" class="w-full py-md bg-tertiary text-on-tertiary rounded-xl font-bold hover:shadow-lg transition-all">Khôi phục</button>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl p-xl max-w-md w-full mx-4 shadow-2xl">
        <div class="flex items-center gap-md mb-lg">
            <div class="w-12 h-12 bg-error-container rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-on-error-container">delete_forever</span>
            </div>
            <h3 class="font-title-lg text-title-lg text-on-surface">Xác nhận xoá</h3>
        </div>
        <p class="text-on-surface-variant text-body-md mb-xl">
            Bạn có chắc chắn muốn xoá bản sao lưu <strong id="deleteFilename" class="text-on-surface"></strong>? Hành động này không thể hoàn tác.
        </p>
        <div class="flex gap-md">
            <button onclick="closeModal('deleteModal')" class="flex-1 py-md border-2 border-outline-variant/30 rounded-xl font-bold text-on-surface-variant hover:bg-surface-container transition-colors">Huỷ</button>
            <form id="deleteForm" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full py-md bg-error text-on-error rounded-xl font-bold hover:shadow-lg transition-all">Xoá</button>
            </form>
        </div>
    </div>
</div>

<script>
function updateFileLabel(input) {
    const label = document.getElementById('fileLabel');
    const btn = document.getElementById('uploadBtn');
    if (input.files && input.files[0]) {
        label.textContent = input.files[0].name;
        btn.disabled = false;
    } else {
        label.textContent = 'Kéo thả hoặc click để chọn file .sqlite';
        btn.disabled = true;
    }
}

function confirmRestore(filename) {
    document.getElementById('restoreFilename').textContent = '"' + filename + '"';
    document.getElementById('restoreInput').value = filename;
    const modal = document.getElementById('restoreModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function confirmDelete(filename) {
    document.getElementById('deleteFilename').textContent = '"' + filename + '"';
    document.getElementById('deleteForm').action = '/admin/backup/' + encodeURIComponent(filename);
    const modal = document.getElementById('deleteModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeModal(id) {
    const modal = document.getElementById(id);
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Close modal on backdrop click
document.querySelectorAll('#restoreModal, #deleteModal').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) closeModal(this.id);
    });
});

// Drag and drop support
const dropZone = document.getElementById('dropZone');
const fileInput = document.getElementById('backup_file');

['dragenter', 'dragover'].forEach(event => {
    dropZone.addEventListener(event, (e) => {
        e.preventDefault();
        dropZone.classList.add('border-primary', 'bg-primary/5');
    });
});

['dragleave', 'drop'].forEach(event => {
    dropZone.addEventListener(event, (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-primary', 'bg-primary/5');
    });
});

dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    if (e.dataTransfer.files.length > 0) {
        fileInput.files = e.dataTransfer.files;
        updateFileLabel(fileInput);
    }
});

// Confirm before upload restore
document.getElementById('uploadForm').addEventListener('submit', function(e) {
    if (!confirm('Bạn có chắc chắn muốn khôi phục CSDL từ file upload này? Dữ liệu hiện tại sẽ được sao lưu tự động trước khi thay thế.')) {
        e.preventDefault();
    }
});
</script>
@endsection
