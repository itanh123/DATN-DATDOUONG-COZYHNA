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
                        <th class="p-4 font-semibold text-gray-600 text-sm">ID</th>
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
                        <td class="p-4 text-sm font-medium text-gray-800">{{ $review->user->name ?? 'N/A' }}</td>
                        <td class="p-4 text-sm text-gray-600">{{ $review->product->name ?? 'N/A' }}</td>
                        <td class="p-4 text-sm text-yellow-500 font-bold">{{ $review->rating }} <span class="material-symbols-outlined text-sm align-middle" style="font-variation-settings: 'FILL' 1;">star</span></td>
                        <td class="p-4 text-sm text-gray-600 max-w-xs truncate">{{ $review->comment ?: '(Không có)' }}</td>
                        <td class="p-4">
                            <select onchange="updateStatus({{ $review->id }}, this.value)" class="text-sm rounded border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50
                                {{ $review->status == 'approved' ? 'bg-green-50 text-green-700' : ($review->status == 'pending' ? 'bg-yellow-50 text-yellow-700' : 'bg-red-50 text-red-700') }}">
                                <option value="pending" {{ $review->status == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                                <option value="approved" {{ $review->status == 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                                <option value="rejected" {{ $review->status == 'rejected' ? 'selected' : '' }}>Đã ẩn</option>
                            </select>
                        </td>
                        <td class="p-4 text-sm text-gray-500">{{ $review->created_at->format('d/m/Y H:i') }}</td>
                        <td class="p-4 text-right">
                            <form action="/admin/reviews/{{ $review->id }}/delete" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đánh giá này?');">
                                @csrf
                                <button type="submit" class="text-red-500 hover:text-red-700 p-2">
                                    <span class="material-symbols-outlined text-xl">delete</span>
                                </button>
                            </form>
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
    async function updateStatus(reviewId, status) {
        try {
            const response = await fetch(`/admin/reviews/${reviewId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ status: status })
            });
            const data = await response.json();
            if (data.success) {
                // Optionally show a toast notification here
                window.location.reload();
            } else {
                alert(data.message || 'Có lỗi xảy ra');
            }
        } catch (error) {
            console.error(error);
            alert('Lỗi kết nối mạng');
        }
    }
</script>
@endpush
