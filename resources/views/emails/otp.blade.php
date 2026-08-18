<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Mã Xác Nhận OTP</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #f8f9fa; padding: 20px; border-radius: 10px; text-align: center;">
        <h2 style="color: #006400; margin-top: 0;">CozyHNA Coffee</h2>
        <p>Xin chào,</p>
        <p>Bạn đã yêu cầu <strong>{{ $reason }}</strong> tại hệ thống của chúng tôi.</p>
        <p>Đây là mã xác nhận OTP của bạn:</p>
        
        <div style="background-color: #fff; border: 2px dashed #006400; padding: 15px; font-size: 32px; font-weight: bold; letter-spacing: 5px; color: #006400; margin: 20px 0; border-radius: 5px;">
            {{ $otp }}
        </div>
        
        <p style="color: #d32f2f; font-size: 14px;"><strong>Lưu ý:</strong> Mã này chỉ có hiệu lực trong vòng 10 phút. Tuyệt đối không chia sẻ mã này cho bất kỳ ai.</p>
        
        <hr style="border: 0; border-top: 1px solid #ddd; margin: 20px 0;">
        <p style="font-size: 12px; color: #777;">Nếu bạn không thực hiện yêu cầu này, vui lòng bỏ qua email hoặc liên hệ với bộ phận hỗ trợ của chúng tôi.</p>
    </div>
</body>
</html>
