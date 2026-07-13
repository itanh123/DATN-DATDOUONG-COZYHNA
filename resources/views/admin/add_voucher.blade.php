@extends('layouts.admin')

@section('title', 'Thêm Voucher')

@section('content')
<main class="ml-[280px] pt-16 min-h-screen bg-surface p-gutter">
<div class="max-w-[1100px] mx-auto">
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-xs py-md text-on-surface-variant mb-md">
        <a class="font-label-md text-label-md hover:text-primary" href="/admin/voucher">Voucher</a>
        <span class="material-symbols-outlined text-[16px]">chevron_right</span>
        <span class="font-label-md text-label-md text-primary font-bold">Thêm Mới Voucher</span>
    </nav>
    
    <form action="/admin/voucher/store" method="POST">
        @csrf
        <!-- Page Title & Thao tác -->
        <div class="flex justify-between items-end mb-xl">
            <div>
                <h1 class="font-headline-lg text-headline-lg text-on-surface">Thêm Mới Voucher</h1>
                <p class="font-body-md text-body-md text-on-surface-variant">Thiết lập chương trình khuyến mãi mới</p>
            </div>
            <div class="flex gap-md">
                <a href="/admin/voucher" class="px-lg py-md rounded-lg border border-outline text-on-surface font-label-md text-label-md hover:bg-surface-container transition-colors inline-block text-center">
                    Hủy
                </a>
                <button type="submit" class="px-lg py-md rounded-lg bg-primary text-on-primary font-label-md text-label-md hover:opacity-90 shadow-sm transition-all transform active:scale-95">
                    Thêm Voucher
                </button>
            </div>
        </div>
        
        @if ($errors->any())
            <div class="bg-error-container text-on-error-container p-4 rounded-lg mb-lg">
                <ul class="list-disc ml-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Bento Grid Layout -->
        <div class="grid grid-cols-12 gap-lg">
            <!-- Left Column: Primary Details -->
            <div class="col-span-12 lg:col-span-8 space-y-lg">
                <!-- General Information Card -->
                <section class="bg-surface-container-lowest rounded-xl p-xl border border-outline-variant shadow-sm">
                    <div class="flex items-center gap-sm mb-lg">
                        <span class="material-symbols-outlined text-primary">info</span>
                        <h3 class="font-title-lg text-title-lg">Thông tin chung</h3>
                    </div>
                    <div class="grid grid-cols-2 gap-lg">
                        <div class="col-span-2">
                            <label class="block font-label-md text-label-md text-on-surface-variant mb-xs">Tên Voucher <span class="text-error">*</span></label>
                            <input name="name" value="{{ old('name') }}" class="w-full border-outline-variant rounded-lg p-md focus:border-primary focus:ring-1 focus:ring-primary text-body-md" placeholder="e.g. Giảm giá Mùa Hè" type="text" required/>
                        </div>
                        <div>
                            <label class="block font-label-md text-label-md text-on-surface-variant mb-xs">Mã Code <span class="text-error">*</span></label>
                            <input name="code" value="{{ old('code') }}" class="w-full border-outline-variant rounded-lg p-md focus:border-primary focus:ring-1 focus:ring-primary text-body-md" placeholder="e.g. SUMMER24" type="text" required/>
                        </div>
                        <div>
                            <label class="block font-label-md text-label-md text-on-surface-variant mb-xs">Số lượng phát hành <span class="text-error">*</span></label>
                            <input name="quantity" value="{{ old('quantity', 100) }}" class="w-full border-outline-variant rounded-lg p-md focus:border-primary focus:ring-1 focus:ring-primary text-body-md" placeholder="100" type="number" min="1" required/>
                        </div>
                        <div class="col-span-2">
                            <label class="block font-label-md text-label-md text-on-surface-variant mb-xs">Mô tả</label>
                            <textarea name="description" class="w-full border-outline-variant rounded-lg p-md focus:border-primary focus:ring-1 focus:ring-primary text-body-md" placeholder="Nhập mô tả chi tiết cho voucher này..." rows="3">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </section>

                <!-- Configuration Card -->
                <section class="bg-surface-container-lowest rounded-xl p-xl border border-outline-variant shadow-sm">
                    <div class="flex items-center gap-sm mb-lg">
                        <span class="material-symbols-outlined text-primary">settings</span>
                        <h3 class="font-title-lg text-title-lg">Cấu hình Giảm giá</h3>
                    </div>
                    <div class="grid grid-cols-2 gap-lg">
                        <div>
                            <label class="block font-label-md text-label-md text-on-surface-variant mb-xs">Loại giảm giá <span class="text-error">*</span></label>
                            <select name="discount_type" class="w-full border-outline-variant rounded-lg p-md focus:border-primary focus:ring-1 focus:ring-primary text-body-md">
                                <option value="percent" {{ old('discount_type') == 'percent' ? 'selected' : '' }}>Phần trăm (%)</option>
                                <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>Số tiền cố định (VNĐ)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-label-md text-label-md text-on-surface-variant mb-xs">Mức giảm <span class="text-error">*</span></label>
                            <input name="discount_value" value="{{ old('discount_value') }}" class="w-full border-outline-variant rounded-lg p-md focus:border-primary focus:ring-1 focus:ring-primary text-body-md" placeholder="Ví dụ: 10 hoặc 50000" type="number" step="0.01" min="0" required/>
                        </div>
                        <div>
                            <label class="block font-label-md text-label-md text-on-surface-variant mb-xs">Giá trị đơn hàng tối thiểu (VNĐ) <span class="text-error">*</span></label>
                            <input name="minimum_order" value="{{ old('minimum_order', 0) }}" class="w-full border-outline-variant rounded-lg p-md focus:border-primary focus:ring-1 focus:ring-primary text-body-md" placeholder="0" type="number" min="0" required/>
                        </div>
                        <div>
                            <label class="block font-label-md text-label-md text-on-surface-variant mb-xs">Mức giảm tối đa (VNĐ)</label>
                            <input name="maximum_discount" value="{{ old('maximum_discount') }}" class="w-full border-outline-variant rounded-lg p-md focus:border-primary focus:ring-1 focus:ring-primary text-body-md" placeholder="Dành cho giảm theo %" type="number" min="0"/>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Right Column: Media & Meta -->
            <div class="col-span-12 lg:col-span-4 space-y-lg">
                <!-- Status & Timing Card -->
                <section class="bg-surface-container-lowest rounded-xl p-xl border border-outline-variant shadow-sm">
                    <div class="flex items-center gap-sm mb-lg">
                        <span class="material-symbols-outlined text-primary">calendar_month</span>
                        <h3 class="font-title-lg text-title-lg">Thời hạn & Trạng thái</h3>
                    </div>
                    <div class="space-y-lg">
                        <label class="flex items-center gap-md p-md border border-outline-variant rounded-lg cursor-pointer hover:bg-surface-container-low transition-colors">
                            <div class="relative flex items-center">
                                <input type="checkbox" name="status" class="peer sr-only" value="1" {{ old('status', true) ? 'checked' : '' }}>
                                <div class="w-10 h-6 bg-surface-variant rounded-full peer peer-checked:bg-primary transition-colors"></div>
                                <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-4 shadow-sm"></div>
                            </div>
                            <span class="font-label-md text-label-md text-on-surface">Kích hoạt (Active)</span>
                        </label>
                        
                        <div>
                            <label class="block font-label-md text-label-md text-on-surface-variant mb-xs">Ngày bắt đầu <span class="text-error">*</span></label>
                            <input name="start_date" value="{{ old('start_date', now()->format('Y-m-d\TH:i')) }}" class="w-full border-outline-variant rounded-lg p-md focus:border-primary focus:ring-1 focus:ring-primary text-body-md" type="datetime-local" required/>
                        </div>
                        
                        <div>
                            <label class="block font-label-md text-label-md text-on-surface-variant mb-xs">Ngày kết thúc <span class="text-error">*</span></label>
                            <input name="end_date" value="{{ old('end_date', now()->addDays(7)->format('Y-m-d\TH:i')) }}" class="w-full border-outline-variant rounded-lg p-md focus:border-primary focus:ring-1 focus:ring-primary text-body-md" type="datetime-local" required/>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </form>
</div>
</main>
@endsection
