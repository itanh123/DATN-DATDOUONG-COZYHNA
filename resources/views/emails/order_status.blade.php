<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cập nhật trạng thái đơn hàng</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f9f9f9; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header { border-bottom: 2px solid #006400; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2 { color: #006400; margin: 0; }
        .content { font-size: 16px; }
        .status-box { background: #f0fff0; border: 1px solid #006400; padding: 15px; border-radius: 5px; margin: 20px 0; font-weight: bold; color: #006400; }
        .footer { margin-top: 30px; font-size: 14px; color: #777; border-top: 1px solid #eee; padding-top: 15px; text-align: center; }
        .btn { display: inline-block; padding: 10px 20px; background-color: #006400; color: #fff; text-decoration: none; border-radius: 5px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>CozyHNA - Thông báo đơn hàng</h2>
        </div>
        <div class="content">
            <p>Xin chào <strong>{{ $customerName }}</strong>,</p>
            <p>Cảm ơn bạn đã đồng hành cùng CozyHNA. Chúng tôi xin thông báo về đơn hàng <strong>#{{ $order->order_code }}</strong> của bạn:</p>
            
            <div class="status-box">
                Trạng thái hiện tại: {{ $statusMessage }}
            </div>
            
            <p>Bạn có thể theo dõi chi tiết đơn hàng bằng cách nhấn vào nút bên dưới:</p>
            <p style="text-align: center;">
                <a href="{{ url('/customer/orders') }}" class="btn" style="color: white">Xem Đơn Hàng</a>
            </p>
            
            <p>Nếu có bất kỳ thắc mắc nào, vui lòng liên hệ với chúng tôi qua tổng đài hỗ trợ hoặc email này.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} CozyHNA. All rights reserved.
        </div>
    </div>
</body>
</html>
