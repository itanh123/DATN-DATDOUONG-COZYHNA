<!DOCTYPE html>
<html class="light" lang="en">
<head>
<meta charset="utf-8"/>
<meta name="csrf-token" content="{{ csrf_token() }}"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>CozyHNA Admin | @yield('title', 'Bảng điều khiển')</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                    "error-container": "#ffdad6",
                    "outline-variant": "#becab9",
                    "on-primary-fixed": "#002204",
                    "on-primary-fixed-variant": "#005313",
                    "primary-fixed": "#94f990",
                    "error": "#ba1a1a",
                    "surface-tint": "#006e1c",
                    "surface-container-lowest": "#ffffff",
                    "secondary": "#3e6a00",
                    "on-surface": "#0b1c30",
                    "secondary-container": "#b9f474",
                    "surface": "#f8f9ff",
                    "on-secondary-container": "#437000",
                    "surface-container-high": "#dce9ff",
                    "background": "#f8f9ff",
                    "on-secondary-fixed-variant": "#2e4f00",
                    "on-secondary-fixed": "#0f2000",
                    "inverse-on-surface": "#eaf1ff",
                    "on-primary-container": "#003c0b",
                    "tertiary-fixed": "#ffdf9e",
                    "secondary-fixed": "#b9f474",
                    "surface-variant": "#d3e4fe",
                    "primary": "#006e1c",
                    "on-tertiary": "#ffffff",
                    "surface-bright": "#f8f9ff",
                    "tertiary-container": "#c49400",
                    "tertiary": "#785900",
                    "surface-container": "#e5eeff",
                    "inverse-surface": "#213145",
                    "on-surface-variant": "#3f4a3c",
                    "tertiary-fixed-dim": "#fabd00",
                    "on-tertiary-fixed-variant": "#5b4300",
                    "on-error": "#ffffff",
                    "on-tertiary-fixed": "#261a00",
                    "on-secondary": "#ffffff",
                    "surface-container-highest": "#d3e4fe",
                    "primary-fixed-dim": "#78dc77",
                    "surface-dim": "#cbdbf5",
                    "on-background": "#0b1c30",
                    "surface-container-low": "#eff4ff",
                    "on-error-container": "#93000a",
                    "on-tertiary-container": "#433000",
                    "primary-container": "#4caf50",
                    "on-primary": "#ffffff",
                    "inverse-primary": "#78dc77",
                    "outline": "#6f7a6b",
                    "secondary-fixed-dim": "#9ed75b"
            },
            "borderRadius": {
                    "DEFAULT": "0.25rem",
                    "lg": "0.5rem",
                    "xl": "0.75rem",
                    "full": "9999px"
            },
            "spacing": {
                    "lg": "24px",
                    "2xl": "48px",
                    "md": "16px",
                    "container-max": "1280px",
                    "xl": "32px",
                    "base": "4px",
                    "sm": "12px",
                    "xs": "8px",
                    "gutter": "24px"
            },
            "fontFamily": {
                    "body-lg": ["Inter"],
                    "title-lg": ["Inter"],
                    "headline-lg": ["Inter"],
                    "label-md": ["Inter"],
                    "label-sm": ["Inter"],
                    "headline-md": ["Inter"],
                    "display-lg": ["Inter"],
                    "body-md": ["Inter"],
                    "headline-lg-mobile": ["Inter"]
            },
            "fontSize": {
                    "body-lg": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
                    "title-lg": ["20px", {"lineHeight": "28px", "fontWeight": "600"}],
                    "headline-lg": ["32px", {"lineHeight": "40px", "letterSpacing": "-0.01em", "fontWeight": "600"}],
                    "label-md": ["12px", {"lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "500"}],
                    "label-sm": ["11px", {"lineHeight": "14px", "fontWeight": "600"}],
                    "headline-md": ["24px", {"lineHeight": "32px", "fontWeight": "600"}],
                    "display-lg": ["48px", {"lineHeight": "56px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                    "body-md": ["14px", {"lineHeight": "20px", "fontWeight": "400"}],
                    "headline-lg-mobile": ["24px", {"lineHeight": "32px", "fontWeight": "600"}]
            }
          },
        },
      }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        body { font-family: 'Inter', sans-serif; }
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
        @stack('styles')
</style>
</head>
<body class="bg-background text-on-surface">
<!-- SideNavBar (Shared Component) -->
<aside class="fixed left-0 top-0 h-full w-[280px] bg-surface border-r border-outline-variant/30 shadow-md flex flex-col py-lg px-md z-40 hidden md:flex">
<div class="mb-2xl px-sm">
<h1 class="font-headline-md text-headline-md font-bold text-primary">CozyHNA</h1>
@php
    $adminUser = \App\Models\User::find(session('user_id'));
    $adminName = $adminUser ? ($adminUser->name ?: $adminUser->username) : 'Admin';
@endphp
<p class="font-label-md text-label-md text-on-surface-variant">Xin chào, {{ $adminName }}</p>
</div>
<nav class="flex-1 flex flex-col gap-xs">
@php
    $roleCode = session('role_code');
    $userPermissions = [];
    $isAdmin = ($roleCode === 'admin');
    if ($roleCode) {
        $role = \Illuminate\Support\Facades\DB::table('roles')->where('code', $roleCode)->first();
        if ($role) {
            $userPermissions = \Illuminate\Support\Facades\DB::table('role_permissions')
                ->join('permissions', 'role_permissions.permission_id', '=', 'permissions.id')
                ->where('role_permissions.role_id', $role->id)
                ->pluck('permissions.code')->toArray();
        }
    }
    
    $hasPermission = function($code) use ($isAdmin, $userPermissions) {
        return $isAdmin || in_array($code, $userPermissions);
    };
@endphp

@if($hasPermission('view_dashboard'))
<a class="flex items-center gap-sm px-md py-sm rounded-lg hover:bg-surface-container-high transition-all" href="{{ session('role_code') === 'admin' ? '/admin/dashboard' : (session('role_code') === 'shipper' ? '/shipper/delivery_portal' : '/staff/dashboard') }}">
<span class="material-symbols-outlined" data-icon="dashboard">dashboard</span>
<span class="font-label-md text-label-md">Bảng điều khiển</span>
</a>
@endif

@if($hasPermission('view_orders'))
<a class="flex items-center gap-sm px-md py-sm rounded-lg text-on-surface-variant hover:bg-surface-container-high transition-all {{ request()->is('admin/orders') ? 'bg-primary/10 text-primary font-bold' : '' }}" href="/admin/orders">
<span class="material-symbols-outlined" data-icon="receipt_long">receipt_long</span>
<span class="font-label-md text-label-md">Đơn hàng</span>
</a>
@endif

@if(session('role_code') === 'shipper')
<a class="flex items-center gap-sm px-md py-sm rounded-lg text-on-surface-variant hover:bg-surface-container-high transition-all {{ request()->is('shipper/history') ? 'bg-primary/10 text-primary font-bold' : '' }}" href="/shipper/history">
<span class="material-symbols-outlined" data-icon="history">history</span>
<span class="font-label-md text-label-md">Lịch sử giao hàng</span>
</a>
<a class="flex items-center gap-sm px-md py-sm rounded-lg text-on-surface-variant hover:bg-surface-container-high transition-all {{ request()->is('shipper/reviews') ? 'bg-primary/10 text-primary font-bold' : '' }}" href="/shipper/reviews">
<span class="material-symbols-outlined" data-icon="star">star</span>
<span class="font-label-md text-label-md">Đánh giá</span>
</a>
@endif

@if($hasPermission('view_products'))
<a class="flex items-center gap-sm px-md py-sm rounded-lg text-on-surface-variant hover:bg-surface-container-high transition-all" href="/admin/ingredients">
<span class="material-symbols-outlined" data-icon="science">science</span>
<span class="font-label-md text-label-md">Nguyên liệu</span>
</a>
<a class="flex items-center gap-sm px-md py-sm rounded-lg text-on-surface-variant hover:bg-surface-container-high transition-all" href="/admin/product">
<span class="material-symbols-outlined" data-icon="category">category</span>
<span class="font-label-md text-label-md">Sản phẩm</span>
</a>
<a class="flex items-center gap-sm px-md py-sm rounded-lg text-on-surface-variant hover:bg-surface-container-high transition-all" href="/admin/reviews">
<span class="material-symbols-outlined" data-icon="reviews">reviews</span>
<span class="font-label-md text-label-md">Đánh giá</span>
</a>
@endif

@if($hasPermission('view_users'))
<a class="flex items-center gap-sm px-md py-sm rounded-lg text-on-surface-variant hover:bg-surface-container-high transition-all" href="/admin/users">
<span class="material-symbols-outlined" data-icon="manage_accounts">manage_accounts</span>
<span class="font-label-md text-label-md">Người dùng</span>
</a>
@endif

@if($roleCode === 'admin')
<a class="flex items-center gap-sm px-md py-sm rounded-lg text-on-surface-variant hover:bg-surface-container-high transition-all" href="/admin/tables">
<span class="material-symbols-outlined" data-icon="table_restaurant">table_restaurant</span>
<span class="font-label-md text-label-md">Quản lý Bàn</span>
</a>
@endif

@if($hasPermission('view_roles'))
<a class="flex items-center gap-sm px-md py-sm rounded-lg text-on-surface-variant hover:bg-surface-container-high transition-all" href="/admin/roles">
<span class="material-symbols-outlined" data-icon="admin_panel_settings">admin_panel_settings</span>
<span class="font-label-md text-label-md">Phân quyền</span>
</a>
@endif

@if($roleCode === 'admin')

<a class="flex items-center gap-sm px-md py-sm rounded-lg text-on-surface-variant hover:bg-surface-container-high transition-all" href="/admin/voucher">
<span class="material-symbols-outlined" data-icon="confirmation_number">confirmation_number</span>
<span class="font-label-md text-label-md">Voucher</span>
</a>
<a class="flex items-center gap-sm px-md py-sm rounded-lg text-on-surface-variant hover:bg-surface-container-high transition-all" href="/admin/reports">
<span class="material-symbols-outlined" data-icon="bar_chart">bar_chart</span>
<span class="font-label-md text-label-md">Báo cáo</span>
</a>
<a class="flex items-center gap-sm px-md py-sm rounded-lg text-on-surface-variant hover:bg-surface-container-high transition-all" href="/admin/backup">
<span class="material-symbols-outlined" data-icon="backup">backup</span>
<span class="font-label-md text-label-md">Sao lưu</span>
</a>
<a class="flex items-center gap-sm px-md py-sm rounded-lg text-on-surface-variant hover:bg-surface-container-high transition-all" href="/admin/delivery-settings">
<span class="material-symbols-outlined" data-icon="local_shipping">local_shipping</span>
<span class="font-label-md text-label-md">Giao hàng</span>
</a>
@endif
</nav>
<div class="mt-auto pt-lg border-t border-outline-variant/30 flex flex-col gap-xs">
<a class="flex items-center gap-sm px-md py-sm text-on-surface-variant hover:bg-surface-container transition-all" href="#">
<span class="material-symbols-outlined" data-icon="help_outline">help_outline</span>
<span class="font-label-md text-label-md">Hỗ trợ</span>
</a>
<a class="flex items-center gap-sm px-md py-sm text-on-surface-variant hover:bg-surface-container transition-all" href="/logout">
<span class="material-symbols-outlined" data-icon="logout">logout</span>
<span class="font-label-md text-label-md">Đăng xuất</span>
</a>
</div>
</aside>

@yield('content')

<!-- BottomNavBar (Shared Component for Mobile) -->
<nav class="fixed bottom-0 left-0 w-full z-50 flex justify-around items-center px-4 py-2 pb-safe bg-surface shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] rounded-t-xl md:hidden">
<a class="flex flex-col items-center justify-center bg-primary-container text-on-primary-container rounded-2xl px-4 py-1 transition-transform active:scale-90" href="{{ session('role_code') === 'admin' ? '/admin/dashboard' : (session('role_code') === 'shipper' ? '/shipper/delivery_portal' : '/staff/dashboard') }}">
<span class="material-symbols-outlined" data-icon="home">home</span>
<span class="font-label-sm text-label-sm">Trang chủ</span>
</a>
<a class="flex flex-col items-center justify-center text-on-surface-variant hover:text-primary transition-colors" href="/admin/orders">
<span class="material-symbols-outlined" data-icon="local_cafe">local_cafe</span>
<span class="font-label-sm text-label-sm">Đơn hàng</span>
</a>
<a class="flex flex-col items-center justify-center text-on-surface-variant hover:text-primary transition-colors" href="/admin/reports">
<span class="material-symbols-outlined" data-icon="history">history</span>
<span class="font-label-sm text-label-sm">Báo cáo</span>
</a>
<a class="flex flex-col items-center justify-center text-on-surface-variant hover:text-primary transition-colors" href="/admin/employees">
<span class="material-symbols-outlined" data-icon="person">person</span>
<span class="font-label-sm text-label-sm">Hồ sơ</span>
</a>
</nav>
@stack('scripts')

<div id="table-call-container" class="fixed top-4 right-4 z-[9999] flex flex-col gap-2"></div>
<script>
    function fetchTableCalls() {
        fetch('/admin/table-calls/pending')
            .then(r => r.json())
            .then(calls => {
                const container = document.getElementById('table-call-container');
                container.innerHTML = '';
                if(calls && calls.length > 0) {
                    calls.forEach(call => {
                        const div = document.createElement('div');
                        div.className = 'bg-error text-white p-4 rounded-xl shadow-xl flex items-center gap-4 animate-pulse';
                        div.innerHTML = `
                            <span class="material-symbols-outlined text-3xl">notifications_active</span>
                            <div>
                                <h4 class="font-bold">Khách gọi nhân viên!</h4>
                                <p>${call.table_name} ${call.area_name ? ' - ' + call.area_name : ''} ${call.floor_name ? '(' + call.floor_name + ')' : ''}</p>
                            </div>
                            <button onclick="resolveTableCall(${call.id})" class="ml-4 bg-white text-error px-4 py-2 rounded-lg font-bold hover:bg-gray-100 transition-colors">OK</button>
                        `;
                        container.appendChild(div);
                    });
                }
            }).catch(e => console.log('Error fetching table calls'));
    }

    function resolveTableCall(id) {
        fetch('/admin/table-calls/' + id + '/resolve', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(r => r.json()).then(res => {
            if(res.success) fetchTableCalls();
        });
    }

    setInterval(fetchTableCalls, 5000);
    fetchTableCalls();

    // COMPLAINTS POLLING
    let currentComplaintId = null;

    function fetchNewComplaints() {
        fetch('/admin/complaints/check-new')
            .then(r => r.json())
            .then(data => {
                if(data.success && data.complaint) {
                    showComplaintAlert(data.complaint);
                }
            }).catch(e => console.log('Error fetching complaints'));
    }

    function showComplaintAlert(complaint) {
        // Prevent multiple modals if one is already open
        if (!document.getElementById('complaint-alert-modal').classList.contains('hidden')) return;

        currentComplaintId = complaint.id;
        document.getElementById('ca-order-code').innerText = complaint.order ? complaint.order.code : 'N/A';
        document.getElementById('ca-customer-name').innerText = complaint.customer && complaint.customer.user ? complaint.customer.user.name : 'N/A';
        document.getElementById('ca-target').innerText = complaint.target_person || 'Không có';
        document.getElementById('ca-time').innerText = new Date(complaint.incident_time).toLocaleString('vi-VN');
        document.getElementById('ca-desc').innerText = complaint.description;

        const imgContainer = document.getElementById('ca-images');
        imgContainer.innerHTML = '';
        if (complaint.images && complaint.images.length > 0) {
            complaint.images.forEach(img => {
                imgContainer.innerHTML += `<a href="/storage/${img}" target="_blank"><img src="/storage/${img}" class="w-24 h-24 object-cover rounded-xl border border-gray-300"></a>`;
            });
        }

        document.getElementById('complaint-alert-modal').classList.remove('hidden');
    }

    function acknowledgeComplaint() {
        if(!currentComplaintId) return;

        fetch(`/admin/complaints/${currentComplaintId}/viewed`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(r => r.json()).then(res => {
            if(res.success) {
                document.getElementById('complaint-alert-modal').classList.add('hidden');
                openReplyModal();
            }
        });
    }

    function openReplyModal() {
        document.getElementById('reply-form').action = `/admin/complaints/${currentComplaintId}/reply`;
        document.getElementById('complaint-reply-modal').classList.remove('hidden');
    }

    function closeReplyModal() {
        document.getElementById('complaint-reply-modal').classList.add('hidden');
        currentComplaintId = null;
    }

    async function submitReplyForm(e) {
        e.preventDefault();
        const form = e.target;
        const btn = document.getElementById('btn-submit-reply');
        const originalText = btn.innerHTML;
        
        btn.innerHTML = '<span class="material-symbols-outlined animate-spin">sync</span> Đang gửi email...';
        btn.disabled = true;

        try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });
            const data = await response.json();
            
            if (data.success) {
                alert(data.message);
                closeReplyModal();
                form.reset();
            } else {
                alert(data.message || 'Có lỗi xảy ra');
            }
        } catch (error) {
            alert('Lỗi mạng khi gửi email.');
        } finally {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    }

    setInterval(fetchNewComplaints, 2000); // 2 seconds for near real-time
    fetchNewComplaints();
</script>

<!-- Complaint Alert Modal -->
<div id="complaint-alert-modal" class="hidden fixed inset-0 z-[10000] flex items-center justify-center p-4 bg-black/80 backdrop-blur-md">
    <div class="bg-white rounded-3xl max-w-2xl w-full p-8 shadow-2xl relative border-4 border-red-500 animate-bounce-slight">
        <div class="text-center mb-6">
            <span class="material-symbols-outlined text-6xl text-red-600 mb-2">warning</span>
            <h2 class="text-3xl font-bold text-red-600 uppercase">Có khiếu nại mới!</h2>
            <p class="text-gray-600 mt-2">Vui lòng xử lý ngay lập tức</p>
        </div>

        <div class="space-y-4 bg-red-50 p-6 rounded-2xl border border-red-200 text-lg">
            <p><strong>Mã đơn hàng:</strong> <span id="ca-order-code" class="text-red-700 font-bold"></span></p>
            <p><strong>Khách hàng:</strong> <span id="ca-customer-name"></span></p>
            <p><strong>Đối tượng liên quan:</strong> <span id="ca-target"></span></p>
            <p><strong>Thời gian:</strong> <span id="ca-time"></span></p>
            <div>
                <strong>Chi tiết sự việc:</strong>
                <p id="ca-desc" class="mt-2 p-4 bg-white rounded-xl border border-red-100"></p>
            </div>
            <div id="ca-images" class="flex flex-wrap gap-2 mt-4"></div>
        </div>

        <button onclick="acknowledgeComplaint()" class="w-full mt-6 py-4 bg-red-600 text-white font-bold text-xl rounded-2xl hover:bg-red-700 transition-colors shadow-lg">
            ĐÃ HIỂU - CHUYỂN ĐẾN TRẢ LỜI
        </button>
    </div>
</div>

<!-- Complaint Reply Modal -->
<div id="complaint-reply-modal" class="hidden fixed inset-0 z-[10000] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl relative">
        <button onclick="closeReplyModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800">
            <span class="material-symbols-outlined">close</span>
        </button>
        <h3 class="text-2xl font-bold text-gray-800 mb-4">Gửi Email Phản Hồi</h3>
        
        <form id="reply-form" method="POST" class="space-y-4" onsubmit="submitReplyForm(event)">
            @csrf
            <div>
                <label class="block font-bold text-gray-700 mb-2">Nội dung phản hồi (sẽ gửi qua Email khách hàng):</label>
                <textarea name="reply_content" required rows="6" class="w-full p-3 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500" placeholder="Kính gửi quý khách..."></textarea>
            </div>
            <button type="submit" id="btn-submit-reply" class="w-full py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-colors flex items-center justify-center gap-2">
                <span>Gửi Phản Hồi Email</span>
            </button>
        </form>
    </div>
</div>

</body>
</html>
