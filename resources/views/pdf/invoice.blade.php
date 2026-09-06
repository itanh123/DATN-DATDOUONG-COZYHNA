<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Hóa đơn #{{ $order->order_code }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 14px; line-height: 1.5; color: #333; margin: 0; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #006400; padding-bottom: 20px; margin-bottom: 30px; }
        .header h1 { color: #006400; margin: 0 0 10px 0; font-size: 28px; }
        .header p { margin: 0; color: #666; }
        .row { width: 100%; display: table; margin-bottom: 30px; }
        .col-half { width: 48%; display: table-cell; vertical-align: top; }
        .invoice-details { text-align: right; }
        .section-title { font-size: 16px; font-weight: bold; border-bottom: 1px solid #ddd; padding-bottom: 5px; margin-bottom: 10px; color: #006400; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f8f9fa; font-weight: bold; color: #333; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .totals-table { width: 50%; float: right; border: none; }
        .totals-table th, .totals-table td { border: none; padding: 8px 12px; }
        .totals-table th { background: transparent; text-align: right; }
        .total-row { font-size: 18px; font-weight: bold; color: #006400; border-top: 2px solid #006400 !important; }
        .footer { text-align: center; font-size: 12px; color: #777; border-top: 1px solid #eee; padding-top: 20px; margin-top: 50px; clear: both; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ __('COZYHNA COFFEE') }}</h1>
        <p>123 Đường Cầu Giấy, Quận Cầu Giấy, Hà Nội</p>
        <p>Điện thoại: 0846.146.594 | Email: hi@cozyhna.com</p>
        <p>{{ __('Website: www.cozyhna.com') }}</p>
    </div>

    <div class="row">
        <div class="col-half">
            <div class="section-title">THÔNG TIN KHÁCH HÀNG</div>
            @if($order->customer_id && isset($customer))
                <p><strong>Khách hàng:</strong> {{ $customer->name ?? $customer->username ?? __('Khách hàng') }}</p>
                @if($customer->email) <p><strong>{{ __('Email:') }}</strong> {{ $customer->email }}</p> @endif
                @if($customer->phone) <p><strong>SĐT:</strong> {{ $customer->phone }}</p> @endif
            @endif
            
            @if(!empty($address))
            <div class="section-title" style="margin-top: 20px;">ĐỊA CHỈ GIAO HÀNG</div>
            <p><strong>Người nhận:</strong> {{ $address->receiver_name }}</p>
            <p><strong>SĐT:</strong> {{ $address->receiver_phone }}</p>
            <p><strong>Địa chỉ:</strong> {{ $address->address }}</p>
            @endif
        </div>
        <div class="col-half invoice-details">
            <div class="section-title text-right">HÓA ĐƠN BÁN HÀNG</div>
            <p><strong>Mã đơn hàng:</strong> {{ $order->order_code }}</p>
            <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('H:i, d/m/Y') }}</p>
            <p><strong>Ngày xuất HĐ:</strong> {{ now()->format('H:i, d/m/Y') }}</p>
            <p><strong>Phương thức TT:</strong> 
                {{ [
                    'cash' => 'Tiền mặt (COD)',
                    'momo' => 'Ví MoMo',
                    'vnpay' => 'VNPay',
                    'bank' => 'Chuyển khoản'
                ][$order->payment_method] ?? $order->payment_method }}
            </p>
        </div>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th width="5%" class="text-center">{{ __('STT') }}</th>
                <th width="45%">Sản phẩm</th>
                <th width="15%" class="text-center">Số lượng</th>
                <th width="15%" class="text-right">Đơn giá</th>
                <th width="20%" class="text-right">Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->product_name }} - Size {{ $item->size_name ?? __('M') }}</td>
                <td class="text-center">{{ $item->quantity }}</td>
                <td class="text-right">{{ number_format($item->unit_price, 0, ',', '.') }}đ</td>
                <td class="text-right">{{ number_format($item->total_price, 0, ',', '.') }}đ</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals-table">
        <tr>
            <th>Tạm tính:</th>
            <td class="text-right">{{ number_format($order->subtotal, 0, ',', '.') }}đ</td>
        </tr>
        @if($order->discount_amount > 0)
        <tr>
            <th>Giảm giá:</th>
            <td class="text-right" style="color: #d32f2f;">-{{ number_format($order->discount_amount, 0, ',', '.') }}đ</td>
        </tr>
        @endif
        <tr>
            <th>Phí vận chuyển:</th>
            <td class="text-right">{{ number_format($order->shipping_fee, 0, ',', '.') }}đ</td>
        </tr>
        <tr>
            <th class="total-row">TỔNG CỘNG:</th>
            <td class="total-row text-right">{{ number_format($order->total_amount, 0, ',', '.') }}đ</td>
        </tr>
    </table>

    <div class="footer">
        <p>Cảm ơn quý khách đã mua hàng tại CozyHNA. Chúc quý khách một ngày vui vẻ!</p>
        <p>Hóa đơn được xuất tự động từ hệ thống.</p>
    </div>
</body>
</html>
