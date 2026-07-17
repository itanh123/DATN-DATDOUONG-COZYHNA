<!DOCTYPE html>
<html class="light" lang="en">
<head>
<meta charset="utf-8"/>
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
<p class="font-label-md text-label-md text-on-surface-variant">Cổng quản trị</p>
</div>
<nav class="flex-1 flex flex-col gap-xs">
@php
    $roleCode = session('role_code');
    $userPermissions = [];
    if ($roleCode) {
        $role = \Illuminate\Support\Facades\DB::table('roles')->where('code', $roleCode)->first();
        if ($role) {
            $userPermissions = \Illuminate\Support\Facades\DB::table('role_permissions')
                ->join('permissions', 'role_permissions.permission_id', '=', 'permissions.id')
                ->where('role_permissions.role_id', $role->id)
                ->pluck('permissions.code')->toArray();
        }
    }
@endphp

@if(in_array('view_dashboard', $userPermissions))
<a class="flex items-center gap-sm px-md py-sm rounded-lg hover:bg-surface-container-high transition-all" href="/admin/dashboard">
<span class="material-symbols-outlined" data-icon="dashboard">dashboard</span>
<span class="font-label-md text-label-md">Bảng điều khiển</span>
</a>
@endif

@if(in_array('view_orders', $userPermissions))
<a class="flex items-center gap-sm px-md py-sm rounded-lg text-on-surface-variant hover:bg-surface-container-high transition-all" href="/admin/orders">
<span class="material-symbols-outlined" data-icon="receipt_long">receipt_long</span>
<span class="font-label-md text-label-md">Đơn hàng</span>
</a>
@endif

@if(in_array('view_products', $userPermissions))
<a class="flex items-center gap-sm px-md py-sm rounded-lg text-on-surface-variant hover:bg-surface-container-high transition-all" href="/admin/ingredients">
<span class="material-symbols-outlined" data-icon="science">science</span>
<span class="font-label-md text-label-md">Nguyên liệu</span>
</a>
<a class="flex items-center gap-sm px-md py-sm rounded-lg text-on-surface-variant hover:bg-surface-container-high transition-all" href="/admin/product">
<span class="material-symbols-outlined" data-icon="category">category</span>
<span class="font-label-md text-label-md">Sản phẩm</span>
</a>
@endif

@if(in_array('view_users', $userPermissions))
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

@if(in_array('view_roles', $userPermissions))
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
@endif
</nav>
<div class="mt-auto pt-lg border-t border-outline-variant/30 flex flex-col gap-xs">
<a class="flex items-center gap-sm px-md py-sm text-on-surface-variant hover:bg-surface-container transition-all" href="#">
<span class="material-symbols-outlined" data-icon="help_outline">help_outline</span>
<span class="font-label-md text-label-md">Hỗ trợ</span>
</a>
<a class="flex items-center gap-sm px-md py-sm text-on-surface-variant hover:bg-surface-container transition-all" href="/">
<span class="material-symbols-outlined" data-icon="logout">logout</span>
<span class="font-label-md text-label-md">Đăng xuất</span>
</a>
</div>
</aside>

@yield('content')

<!-- BottomNavBar (Shared Component for Mobile) -->
<nav class="fixed bottom-0 left-0 w-full z-50 flex justify-around items-center px-4 py-2 pb-safe bg-surface shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] rounded-t-xl md:hidden">
<a class="flex flex-col items-center justify-center bg-primary-container text-on-primary-container rounded-2xl px-4 py-1 transition-transform active:scale-90" href="/admin/dashboard">
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
                                <p>${call.table_name}</p>
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
</script>
</body>
</html>
