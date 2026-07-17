@extends('layouts.customer')

@section('title', 'Đặt món thành công')

@section('content')
<main class="mt-24 pb-24 max-w-container-max mx-auto px-4 md:px-lg min-h-[60vh] flex items-center justify-center">
    <div class="text-center max-w-md">
        <span class="material-symbols-outlined text-primary text-[80px] mb-4">check_circle</span>
        <h1 class="font-headline-lg text-headline-lg text-on-background mb-2">Đã gửi yêu cầu đến bếp!</h1>
        <p class="font-body-md text-body-md text-on-surface-variant mb-6">
            Đơn hàng của bạn tại <strong>{{ session('table_name', 'bàn') }}</strong> đã được chuyển đến nhân viên pha chế. Đồ uống sẽ được mang ra ngay khi hoàn thành.
        </p>
        <div class="flex gap-4 justify-center">
            <a href="/" class="px-6 py-2 bg-primary text-on-primary rounded-lg font-label-md hover:bg-primary/90 transition-colors">
                Xem thêm món
            </a>
        </div>
    </div>
</main>
@endsection
