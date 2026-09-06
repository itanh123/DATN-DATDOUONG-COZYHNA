@extends('layouts.customer')

@section('title', 'Cổng thanh toán ảo')

@section('content')
<div class="container mx-auto px-4 py-12 max-w-lg mt-[120px]">
    <div class="bg-surface-container-lowest rounded-3xl p-8 md:p-10 shadow-lg border border-outline-variant/20 relative overflow-hidden">
        <!-- Decor pattern -->
        <div class="absolute -top-24 -right-24 w-48 h-48 bg-primary/5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-secondary/5 rounded-full blur-3xl"></div>
        
        <div class="relative z-10">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="w-20 h-20 mx-auto bg-primary/10 rounded-2xl flex items-center justify-center mb-6">
                    <span class="material-symbols-outlined text-[40px] text-primary">account_balance_wallet</span>
                </div>
                <h1 class="text-2xl font-bold text-on-surface mb-2">Cổng Thanh Toán Ảo</h1>
                <p class="text-on-surface-variant text-sm">
                    Mô phỏng giao dịch qua 
                    <strong class="text-primary">{{ strtoupper($paymentMethod ?? __('Online')) }}</strong>
                </p>
            </div>

            <!-- Order Summary -->
            <div class="bg-surface-container rounded-2xl p-6 mb-8 border border-outline-variant/30">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-on-surface-variant text-sm">Mã đơn hàng</span>
                    <span class="font-bold text-on-surface">{{ $order->order_code }}</span>
                </div>
                
                <div class="flex justify-between items-center mb-4">
                    <span class="text-on-surface-variant text-sm">Khách hàng</span>
                    <span class="font-medium text-on-surface text-right">{{ $order->receiver_name }}</span>
                </div>
                
                <hr class="border-outline-variant/30 my-4 border-dashed">
                
                <div class="flex justify-between items-end">
                    <span class="text-on-surface-variant text-sm">Số tiền thanh toán</span>
                    <span class="text-2xl font-black text-primary">{{ number_format($order->total_amount, 0, ',', '.') }} đ</span>
                </div>
            </div>

            <!-- Actions -->
            <div class="space-y-4">
                <form action="{{ route('payment.fake.process', $order->order_code) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="success">
                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 rounded-xl transition-all flex justify-center items-center gap-2 shadow-sm shadow-emerald-500/20 group">
                        <span class="material-symbols-outlined group-hover:scale-110 transition-transform">check_circle</span>
                        Xác nhận Thanh Toán Thành Công
                    </button>
                </form>

                <form action="{{ route('payment.fake.process', $order->order_code) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="failed">
                    <button type="submit" class="w-full bg-error/10 hover:bg-error/20 text-error font-bold py-4 rounded-xl transition-all flex justify-center items-center gap-2 border border-error/20">
                        <span class="material-symbols-outlined">cancel</span>
                        Hủy Thanh Toán
                    </button>
                </form>
            </div>
            
            <div class="mt-8 text-center text-xs text-on-surface-variant/70 flex items-center justify-center gap-1">
                <span class="material-symbols-outlined text-[14px]">info</span>
                <span>Đây là trang giả lập dành cho thử nghiệm. Không có giao dịch thật nào diễn ra.</span>
            </div>
        </div>
    </div>
</div>
@endsection
