@extends('layouts.admin')

@section('title', 'Quản lý Đánh Giá')

@section('content')
<main class="md:ml-[280px] min-h-screen p-lg md:p-xl space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800">Quản lý Đánh Giá Sản Phẩm</h1>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="p-4 font-semibold text-gray-600 text-sm">{{ __('ID') }}</th>
                        <th class="p-4 font-semibold text-gray-600 text-sm">Khách hàng</th>
                        <th class="p-4 font-semibold text-gray-600 text-sm">Sản phẩm</th>
                        <th class="p-4 font-semibold text-gray-600 text-sm">Đánh giá</th>
                        <th class="p-4 font-semibold text-gray-600 text-sm">Bình luận</th>
                        <th class="p-4 font-semibold text-gray-600 text-sm">Trạng thái</th>
                        <th class="p-4 font-semibold text-gray-600 text-sm">Ngày tạo</th>
                        <th class="p-4 font-semibold text-gray-600 text-sm text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($reviews as $review)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="p-4 text-sm text-gray-600">#{{ $review->id }}</td>
                        <td class="p-4 text-sm font-medium text-gray-800">{{ $review->user->name ?? __('N/A') }}</td>
                        <td class="p-4 text-sm text-gray-600">{{ $review->product->name ?? __('N/A') }}</td>
                        <td class="p-4 text-sm text-yellow-500 font-bold">{{ $review->rating }} <span class="material-symbols-outlined text-sm align-middle" style="font-variation-settings: 'FILL' 1;">star</span></td>
                        <td class="p-4 text-sm text-gray-600 max-w-xs">
                            <p class="truncate" title="{{ $review->comment }}">{{ $review->comment ?: '(Không có)' }}</p>
                            @if($review->admin_reply)
                                <div class="mt-2 p-2 bg-gray-100 rounded text-xs text-gray-700 border border-gray-200">
                                    <strong class="text-blue-600">Phản hồi:</strong> {{ $review->admin_reply }}
                                </div>
                            @endif
                        </td>
                        <td class="p-4 text-sm">
                            @if($review->status === 'approved')
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Đã duyệt</span>
                            @elseif($review->status === 'pending')
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-medium">Chờ duyệt</span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium">Đã ẩn</span>
                            @endif
                        </td>
                        <td class="p-4 text-sm text-gray-500">{{ $review->created_at->format('d/m/Y H:i') }}</td>
                        <td class="p-4 text-right">
                            <div class="flex justify-end items-center">
                                @if($review->status === 'pending')
                                <button type="button" onclick="updateReviewStatus({{ $review->id }}, 'approved')" class="text-green-500 hover:text-green-700 p-2" title="Duyệt">
                                    <span class="material-symbols-outlined text-xl">check_circle</span>
                                </button>
                                <button type="button" onclick="updateReviewStatus({{ $review->id }}, 'rejected')" class="text-red-500 hover:text-red-700 p-2" title="Từ chối">
                                    <span class="material-symbols-outlined text-xl">cancel</span>
                                </button>
                                @elseif($review->status === 'approved')
                                <button type="button" onclick="updateReviewStatus({{ $review->id }}, 'rejected')" class="text-red-500 hover:text-red-700 p-2" title="Ẩn đánh giá">
                                    <span class="material-symbols-outlined text-xl">visibility_off</span>
                                </button>
                                @elseif($review->status === 'rejected')
                                <button type="button" onclick="updateReviewStatus({{ $review->id }}, 'approved')" class="text-green-500 hover:text-green-700 p-2" title="Hiện đánh giá">
                                    <span class="material-symbols-outlined text-xl">visibility</span>
                                </button>
                                @endif
                                <button type="button" onclick="replyReview({{ $review->id }}, '{{ addslashes($review->admin_reply) }}')" class="text-blue-500 hover:text-blue-700 p-2" title="Trả lời">
                                    <span class="material-symbols-outlined text-xl">reply</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="p-8 text-center text-gray-500">
                            Chưa có đánh giá nào.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($reviews->hasPages())
        <div class="p-4 border-t border-gray-200">
            {{ $reviews->links() }}
        </div>
        @endif
    </div>
</main>
@endsection

@push('scripts')
<script>
    function replyReview(id, currentReply) {
        const reply = prompt("Nhập câu trả lời cho đánh giá này:", currentReply);
        if (reply !== null) {
            if (reply.trim() === "") {
                alert("Nội dung trả lời không được để trống!");
                return;
            }
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/reviews/${id}/reply`;
            
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            
            const replyInput = document.createElement('input');
            replyInput.type = 'hidden';
            replyInput.name = 'admin_reply';
            replyInput.value = reply;
            
            form.appendChild(csrf);
            form.appendChild(replyInput);
            document.body.appendChild(form);
            form.submit();
        }
    }

    function updateReviewStatus(id, status) {
        if (!confirm('Bạn có chắc chắn muốn ' + (status === 'approved' ? 'duyệt/hiển thị' : 'từ chối/ẩn') + ' đánh giá này?')) return;
        
        fetch(`/admin/reviews/${id}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ status: status })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                window.location.reload();
            } else {
                alert(data.message || 'Có lỗi xảy ra');
            }
        })
        .catch(err => alert('Lỗi kết nối'));
    }
</script>
@endpush
