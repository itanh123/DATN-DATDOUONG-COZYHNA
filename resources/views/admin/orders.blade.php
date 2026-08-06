@extends('layouts.admin')

@section('title', 'Quản lý Đơn hàng')

@section('content')
<main class="md:ml-[280px] min-h-screen p-lg md:p-xl pb-24">
    <!-- Header -->
    <header class="flex flex-col md:flex-row md:items-center justify-between mb-2xl gap-md">
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Quản lý Đơn hàng</h2>
            <p class="font-body-md text-on-surface-variant">Trạng thái theo thời gian thực của tất cả các đơn hàng.</p>
        </div>
        <div class="flex items-center gap-md">
            <form action="{{ route('admin.orders.index') }}" method="GET" class="relative">
                <input type="hidden" name="tab" value="{{ request('tab', 'online') }}">
                <span class="absolute inset-y-0 left-0 pl-md flex items-center text-on-surface-variant">
                    <span class="material-symbols-outlined">search</span>
                </span>
                <input name="search" value="{{ request('search') }}" class="pl-xl pr-md py-sm w-64 rounded-xl border border-outline-variant bg-surface-container-lowest focus:ring-2 focus:ring-primary focus:border-transparent outline-none font-body-md transition-all" placeholder="Tìm đơn, khách hàng..." type="text"/>
            </form>
            <button class="bg-surface-container-high text-on-surface px-md py-sm rounded-xl font-semibold flex items-center gap-xs hover:bg-surface-container-highest transition-colors active:scale-95">
                <span class="material-symbols-outlined">notifications</span>
            </button>
        </div>
    </header>

    <!-- Stats -->
    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-lg mb-2xl">
        <div class="bg-surface-container-lowest p-lg rounded-2xl shadow-sm border border-outline-variant/30 flex items-start justify-between">
            <div>
                <p class="font-label-md text-on-surface-variant uppercase tracking-wider mb-xs">Đơn hàng Hôm nay</p>
                <h3 class="font-headline-lg text-headline-lg text-on-surface">{{ $todayOrders }}</h3>
            </div>
            <div class="p-md bg-primary-fixed rounded-xl text-on-primary-fixed">
                <span class="material-symbols-outlined">shopping_bag</span>
            </div>
        </div>
        
        <div class="bg-surface-container-lowest p-lg rounded-2xl shadow-sm border border-outline-variant/30 flex items-start justify-between">
            <div>
                <p class="font-label-md text-on-surface-variant uppercase tracking-wider mb-xs">Chờ xử lý / Đang làm</p>
                <h3 class="font-headline-lg text-headline-lg text-on-surface">{{ $pendingPrep }}</h3>
            </div>
            <div class="p-md bg-tertiary-fixed rounded-xl text-on-tertiary-fixed">
                <span class="material-symbols-outlined">coffee_maker</span>
            </div>
        </div>
        
        <div class="bg-surface-container-lowest p-lg rounded-2xl shadow-sm border border-outline-variant/30 flex items-start justify-between">
            <div>
                <p class="font-label-md text-on-surface-variant uppercase tracking-wider mb-xs">Đang giao</p>
                <h3 class="font-headline-lg text-headline-lg text-on-surface">{{ $delivering }}</h3>
            </div>
            <div class="p-md bg-secondary-container rounded-xl text-on-secondary-container">
                <span class="material-symbols-outlined">local_shipping</span>
            </div>
        </div>
        
        <div class="bg-surface-container-lowest p-lg rounded-2xl shadow-sm border border-outline-variant/30 flex items-start justify-between">
            <div>
                <p class="font-label-md text-on-surface-variant uppercase tracking-wider mb-xs">Tốc độ hoàn thành</p>
                <h3 class="font-headline-lg text-headline-lg text-on-surface">{{ $avgFulfillment }}</h3>
            </div>
            <div class="p-md bg-surface-container-highest rounded-xl text-on-surface">
                <span class="material-symbols-outlined">schedule</span>
            </div>
        </div>
    </section>

    <!-- Tabs -->
    <div class="flex border-b border-outline-variant/30 mb-lg">
        <a href="{{ route('admin.orders.index', ['tab' => 'online']) }}" class="px-lg py-sm font-semibold {{ request('tab', 'online') === 'online' ? 'border-b-2 border-primary text-primary' : 'text-on-surface-variant hover:text-on-surface' }}">Khách Đặt Online</a>
        <a href="{{ route('admin.orders.index', ['tab' => 'table']) }}" class="px-lg py-sm font-semibold {{ request('tab') === 'table' ? 'border-b-2 border-primary text-primary' : 'text-on-surface-variant hover:text-on-surface' }}">Khách Tại Bàn</a>
    </div>

    <!-- Filters & Tools -->
    <section class="flex flex-col lg:flex-row items-center justify-between gap-md mb-lg">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="flex flex-wrap items-center gap-sm">
            <input type="hidden" name="tab" value="{{ request('tab', 'online') }}">
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex items-center px-md py-sm">
                <span class="material-symbols-outlined text-on-surface-variant mr-xs">filter_list</span>
                <select name="status" onchange="this.form.submit()" class="bg-transparent border-none focus:ring-0 font-body-md text-on-surface p-0 cursor-pointer">
                    <option value="ALL">Tất cả Trạng thái</option>
                    <option value="PENDING" {{ request('status') === 'PENDING' ? 'selected' : '' }}>Chờ xác nhận</option>
                    <option value="PREPARING" {{ request('status') === 'PREPARING' ? 'selected' : '' }}>Đang chuẩn bị</option>
                    @if(request('tab') !== 'table')
                    <option value="DELIVERING" {{ request('status') === 'DELIVERING' ? 'selected' : '' }}>Đang giao</option>
                    <option value="READY_FOR_DELIVERY" {{ request('status') === 'READY_FOR_DELIVERY' ? 'selected' : '' }}>Chờ giao hàng</option>
                    @endif
                    <option value="COMPLETED" {{ request('status') === 'COMPLETED' ? 'selected' : '' }}>Hoàn thành</option>
                    <option value="CANCELLED" {{ request('status') === 'CANCELLED' ? 'selected' : '' }}>Đã hủy</option>
                </select>
            </div>
        </form>
        <div class="flex items-center gap-sm">
            <button class="bg-surface-container-lowest border border-outline-variant text-on-surface px-md py-sm rounded-xl font-semibold flex items-center gap-xs hover:bg-surface-container-low transition-colors">
                <span class="material-symbols-outlined">file_download</span> Export CSV
            </button>
            <a href="{{ route('admin.orders.index') }}" class="bg-primary text-on-primary px-lg py-sm rounded-xl font-semibold flex items-center gap-xs hover:bg-opacity-90 transition-opacity shadow-md">
                <span class="material-symbols-outlined">refresh</span> Refresh Data
            </a>
        </div>
    </section>

    <!-- Table -->
    <section class="bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-surface-container-low border-b border-outline-variant/30">
                    <tr>
                        <th class="px-lg py-md font-label-sm text-on-surface-variant uppercase tracking-wider">Mã Đơn</th>
                        <th class="px-lg py-md font-label-sm text-on-surface-variant uppercase tracking-wider">Khách hàng</th>
                        <th class="px-lg py-md font-label-sm text-on-surface-variant uppercase tracking-wider">Sản phẩm</th>
                        <th class="px-lg py-md font-label-sm text-on-surface-variant uppercase tracking-wider">Tổng tiền</th>
                        <th class="px-lg py-md font-label-sm text-on-surface-variant uppercase tracking-wider">Trạng thái</th>
                        <th class="px-lg py-md font-label-sm text-on-surface-variant uppercase tracking-wider">Thời gian tạo</th>
                        <th class="px-lg py-md font-label-sm text-on-surface-variant uppercase tracking-wider text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/20">
                    @forelse($orders as $order)
                    <tr class="order-row transition-colors hover:bg-surface-container-lowest">
                        <td class="px-lg py-lg font-body-md font-semibold text-primary">#{{ $order->code }}</td>
                        <td class="px-lg py-lg">
                            <div class="flex items-center gap-sm">
                                <span class="font-body-md font-medium">{{ $order->customer->user->username ?? $order->customer->full_name ?? $order->receiver_name }}</span>
                            </div>
                        </td>
                        <td class="px-lg py-lg">
                            <span class="font-body-md text-on-surface-variant">
                                @if($order->items->count() > 0)
                                    {{ $order->items->first()->quantity }}x {{ $order->items->first()->product_name ?? 'Sản phẩm' }}
                                    @if($order->items->count() > 1)
                                        <br><span class="text-xs italic text-on-surface-variant opacity-70">+ {{ $order->items->count() - 1 }} sản phẩm khác</span>
                                    @endif
                                @else
                                    Không có SP
                                @endif
                            </span>
                        </td>
                        <td class="px-lg py-lg font-body-md font-semibold">{{ number_format($order->total_amount, 0, ',', '.') }}đ</td>
                        <td class="px-lg py-lg">
                            <span class="inline-flex items-center gap-xs px-md py-1 rounded-full font-semibold text-xs border {{ str_replace('text-', 'border-', $order->status_color) }} {{ $order->status_color }}">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td class="px-lg py-lg font-body-md text-on-surface-variant">{{ $order->created_at->format('H:i d/m/Y') }}</td>
                        <td class="px-lg py-lg text-right space-x-2">
                            <button onclick="viewOrderDetails({{ $order->id }})" class="p-2 rounded-lg hover:bg-surface-container-high transition-colors text-primary" title="Xem chi tiết">
                                <span class="material-symbols-outlined">visibility</span>
                            </button>
                            <button onclick="openStatusModal({{ $order->id }}, '{{ $order->order_status }}')" class="p-2 rounded-lg hover:bg-surface-container-high transition-colors text-secondary" title="Đổi trạng thái">
                                <span class="material-symbols-outlined">edit_square</span>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-lg py-2xl text-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-[48px] opacity-30 block mb-sm">inbox</span>
                            Không tìm thấy đơn hàng nào.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        @if($orders->hasPages())
        <div class="bg-surface-container-low px-lg py-md flex items-center justify-between border-t border-outline-variant/30">
            {{ $orders->appends(request()->query())->links() }}
        </div>
        @endif
    </section>
</main>

<!-- Modal Chi Tiết Đơn Hàng -->
<div id="orderDetailModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-scrim/50 backdrop-blur-sm" onclick="closeModal('orderDetailModal')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl bg-surface rounded-3xl shadow-xl overflow-hidden flex flex-col max-h-[90vh]">
        <div class="p-lg md:p-xl border-b border-outline-variant/30 flex justify-between items-center bg-surface-container-lowest">
            <h3 class="font-headline-sm text-headline-sm text-on-surface flex items-center gap-sm">
                Chi tiết Đơn Hàng <span id="modalOrderCode" class="text-primary font-bold"></span>
            </h3>
            <button onclick="closeModal('orderDetailModal')" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-surface-container transition-colors text-on-surface-variant">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-lg md:p-xl overflow-y-auto bg-surface">
            <!-- Thông tin khách hàng & Giao hàng -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-lg mb-xl">
                <div>
                    <h4 class="font-label-lg text-label-lg text-on-surface-variant mb-sm uppercase tracking-wider">Khách hàng</h4>
                    <p class="font-body-lg text-on-surface font-semibold" id="modalCustomerName"></p>
                    <p class="font-body-md text-on-surface-variant flex items-center gap-xs mt-xs"><span class="material-symbols-outlined text-[18px]">call</span> <span id="modalCustomerPhone"></span></p>
                </div>
                <div>
                    <h4 class="font-label-lg text-label-lg text-on-surface-variant mb-sm uppercase tracking-wider">Giao tới</h4>
                    <p class="font-body-md text-on-surface flex items-start gap-xs"><span class="material-symbols-outlined text-[18px] mt-[2px]">location_on</span> <span id="modalCustomerAddress"></span></p>
                </div>
            </div>

            <!-- Danh sách sản phẩm -->
            <h4 class="font-label-lg text-label-lg text-on-surface-variant mb-sm uppercase tracking-wider">Sản phẩm</h4>
            <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-xl overflow-hidden mb-xl">
                <table class="w-full text-left border-collapse">
                    <tbody id="modalItemsList" class="divide-y divide-outline-variant/20">
                        <!-- Items injected by JS -->
                    </tbody>
                    <tfoot class="bg-surface-container-low border-t-2 border-outline-variant/30 font-bold">
                        <tr>
                            <td class="px-md py-sm text-right" colspan="2">Tổng cộng:</td>
                            <td class="px-md py-sm text-primary" id="modalTotalAmount"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Ghi chú -->
            <div id="modalNotes" class="mb-xl hidden">
                <h4 class="font-label-lg text-label-lg text-on-surface-variant mb-sm uppercase tracking-wider">Ghi chú</h4>
                <div class="bg-yellow-50 text-yellow-800 p-md rounded-xl font-body-md" id="modalNotesContent"></div>
            </div>

            <!-- Lịch sử trạng thái -->
            <h4 class="font-label-lg text-label-lg text-on-surface-variant mb-sm uppercase tracking-wider">Lịch sử Cập nhật</h4>
            <div class="space-y-sm" id="modalHistoryList">
                <!-- History injected by JS -->
            </div>
        </div>
    </div>
</div>

<!-- Modal Đổi Trạng Thái -->
<div id="statusModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-scrim/50 backdrop-blur-sm" onclick="closeModal('statusModal')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md bg-surface rounded-3xl shadow-xl overflow-hidden">
        <div class="p-lg md:p-xl border-b border-outline-variant/30 flex justify-between items-center bg-surface-container-lowest">
            <h3 class="font-headline-sm text-headline-sm text-on-surface">Cập nhật Trạng thái</h3>
            <button onclick="closeModal('statusModal')" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-surface-container transition-colors text-on-surface-variant">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-lg md:p-xl">
            <input type="hidden" id="statusOrderId">
            <div class="mb-lg">
                <label class="block font-label-md text-on-surface-variant mb-sm">Trạng thái mới</label>
                <select id="newStatusSelect" class="w-full px-md py-sm rounded-xl border border-outline-variant bg-surface-container-lowest focus:ring-2 focus:ring-primary outline-none font-body-lg">
                    <option value="PENDING">Chờ xác nhận</option>
                    <option value="PREPARING">Đang chuẩn bị</option>
                    @if(request('tab') !== 'table')
                    <option value="READY_FOR_DELIVERY">Chờ giao hàng</option>
                    <option value="DELIVERING">Đang giao hàng</option>
                    @endif
                    <option value="COMPLETED">Hoàn thành</option>
                    <option value="CANCELLED">Hủy đơn</option>
                </select>
            </div>
            <div class="flex justify-end gap-md">
                <button onclick="closeModal('statusModal')" class="px-lg py-sm rounded-xl font-semibold text-on-surface-variant hover:bg-surface-container transition-colors">Hủy</button>
                <button onclick="submitStatusUpdate()" class="px-lg py-sm rounded-xl font-semibold bg-primary text-on-primary hover:bg-opacity-90 transition-opacity">Xác nhận</button>
            </div>
        </div>
    </div>
</div>

<!-- Toast -->
<div id="toast" class="fixed bottom-lg right-lg z-50 hidden px-lg py-md rounded-xl shadow-lg font-label-md transition-all duration-300" style="min-width: 260px;"></div>

@endsection

@push('scripts')
<script>
    const CSRF_TOKEN = '{{ csrf_token() }}';

    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        toast.textContent = message;
        toast.className = toast.className.replace(/bg-\S+/g, '').replace(/text-\S+/g, 'font-label-md text-label-md');
        if (type === 'success') toast.classList.add('bg-green-600', 'text-white');
        else toast.classList.add('bg-red-600', 'text-white');
        toast.classList.remove('hidden');
        setTimeout(() => toast.classList.add('hidden'), 3500);
    }

    // View Details
    function viewOrderDetails(id) {
        fetch(`/admin/orders/${id}`)
            .then(res => res.json())
            .then(data => {
                if(data.error) return showToast(data.error, 'error');
                
                const order = data.order;
                const histories = data.histories;

                document.getElementById('modalOrderCode').textContent = order.code;
                document.getElementById('modalCustomerName').textContent = order.receiver_name || (order.customer ? (order.customer.user.username || order.customer.full_name) : 'Khách vãng lai');
                document.getElementById('modalCustomerPhone').textContent = order.receiver_phone || 'Không cung cấp';
                document.getElementById('modalCustomerAddress').textContent = order.address ? order.address.address : (order.delivery_address || 'Tại quán');

                if (order.customer_note || order.kitchen_note) {
                    document.getElementById('modalNotes').classList.remove('hidden');
                    let notes = [];
                    if(order.customer_note) notes.push(`Khách: ${order.customer_note}`);
                    if(order.kitchen_note) notes.push(`Bếp: ${order.kitchen_note}`);
                    document.getElementById('modalNotesContent').innerHTML = notes.join('<br>');
                } else {
                    document.getElementById('modalNotes').classList.add('hidden');
                }

                // Render Items
                let itemsHtml = '';
                order.items.forEach(item => {
                    const price = Number(item.price);
                    itemsHtml += `
                        <tr>
                            <td class="px-md py-sm font-body-md text-on-surface">
                                <span class="font-bold">${item.quantity}x</span> 
                                ${item.product ? item.product.name : 'Sản phẩm'}
                                ${item.product_size && item.product_size.size ? `(${item.product_size.size.name})` : ''}
                            </td>
                            <td class="px-md py-sm text-right text-on-surface-variant font-body-sm">${new Intl.NumberFormat('vi-VN').format(price)}đ</td>
                            <td class="px-md py-sm text-right font-semibold text-on-surface">${new Intl.NumberFormat('vi-VN').format(price * item.quantity)}đ</td>
                        </tr>
                    `;
                });
                document.getElementById('modalItemsList').innerHTML = itemsHtml;
                document.getElementById('modalTotalAmount').textContent = new Intl.NumberFormat('vi-VN').format(order.total_amount) + 'đ';

                // Render History
                let historyHtml = '';
                histories.forEach(h => {
                    const date = new Date(h.created_at).toLocaleString('vi-VN');
                    historyHtml += `
                        <div class="flex items-start gap-sm">
                            <div class="w-8 h-8 rounded-full bg-surface-container-high flex items-center justify-center shrink-0 mt-1">
                                <span class="material-symbols-outlined text-[16px] text-on-surface-variant">history</span>
                            </div>
                            <div>
                                <p class="font-body-md text-on-surface">
                                    <span class="font-semibold">${h.changedBy ? (h.changedBy.username || 'Admin') : 'Hệ thống'}</span> 
                                    đã đổi trạng thái thành <span class="font-bold">${h.new_status}</span>
                                </p>
                                <p class="font-label-sm text-on-surface-variant">${date} - ${h.note || ''}</p>
                            </div>
                        </div>
                    `;
                });
                if(histories.length === 0) historyHtml = '<p class="text-on-surface-variant italic">Chưa có cập nhật nào</p>';
                document.getElementById('modalHistoryList').innerHTML = historyHtml;

                openModal('orderDetailModal');
            })
            .catch(() => showToast('Lỗi khi tải dữ liệu đơn hàng.', 'error'));
    }

    // Status Update
    function openStatusModal(id, currentStatus) {
        document.getElementById('statusOrderId').value = id;
        
        const select = document.getElementById('newStatusSelect');
        const options = select.options;
        
        for (let i = 0; i < options.length; i++) {
            options[i].disabled = false;
        }

        const currentTab = '{{ request('tab', 'online') }}';
        
        const allowedTransitions = {
            'PENDING': ['PREPARING', 'CANCELLED'],
            'PREPARING': currentTab === 'table' ? ['COMPLETED', 'CANCELLED'] : ['READY_FOR_DELIVERY', 'DELIVERING', 'CANCELLED'],
            'READY_FOR_DELIVERY': ['DELIVERING', 'CANCELLED'],
            'DELIVERING': ['COMPLETED', 'CANCELLED'],
            'COMPLETED': [],
            'CANCELLED': []
        };

        const allowedNext = allowedTransitions[currentStatus] || [];
        
        for (let i = 0; i < options.length; i++) {
            const val = options[i].value;
            if (val !== currentStatus && !allowedNext.includes(val)) {
                options[i].disabled = true;
            }
        }

        select.value = currentStatus;
        openModal('statusModal');
    }

    function submitStatusUpdate() {
        const id = document.getElementById('statusOrderId').value;
        const status = document.getElementById('newStatusSelect').value;

        fetch(`/admin/orders/${id}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            body: JSON.stringify({ status })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(data.error || 'Có lỗi xảy ra', 'error');
            }
        })
        .catch(() => showToast('Lỗi kết nối', 'error'));
    }
</script>
@endpush
