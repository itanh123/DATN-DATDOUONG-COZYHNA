<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Phản Hồi Khiếu Nại</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #f8f9fa; padding: 20px; border-radius: 10px; border: 1px solid #ddd;">
        <h2 style="color: #b91c1c; text-align: center; margin-top: 0;">Phản Hồi Khiếu Nại Đơn Hàng</h2>
        
        <p>Kính gửi quý khách <strong>{{ $complaint->customer->user->name ?? __('Khách hàng') }}</strong>,</p>
        
        <p>Cảm ơn quý khách đã gửi phản hồi và khiếu nại về đơn hàng <strong>#{{ $complaint->order->code ?? __('') }}</strong> cho CozyHNA. Quản lý cửa hàng đã tiếp nhận và xem xét sự việc.</p>
        
        <div style="background-color: #fff; padding: 15px; border-radius: 8px; border-left: 4px solid #b91c1c; margin: 20px 0;">
            <h3 style="margin-top: 0; color: #b91c1c;">Nội dung phản hồi từ Quản lý:</h3>
            <p style="white-space: pre-line;">{{ $complaint->admin_reply }}</p>
        </div>

        <h4 style="margin-bottom: 5px;">Thông tin khiếu nại của quý khách:</h4>
        <ul style="background-color: #fff; padding: 15px 15px 15px 35px; border-radius: 8px; margin-top: 0;">
            <li><strong>Thời gian sự việc:</strong> {{ \Carbon\Carbon::parse($complaint->incident_time)->format('d/m/Y H:i') }}</li>
            <li><strong>Đối tượng liên quan:</strong> {{ $complaint->target_person ?? __('Không có') }}</li>
            <li><strong>Chi tiết:</strong> {{ $complaint->description }}</li>
        </ul>

        <p>Chúng tôi thành thật xin lỗi vì những trải nghiệm không tốt của quý khách và cam kết sẽ cải thiện chất lượng dịch vụ trong thời gian tới.</p>
        
        <p>Trân trọng,<br>
        <strong>Ban Quản lý CozyHNA</strong></p>
    </div>
</body>
</html>
