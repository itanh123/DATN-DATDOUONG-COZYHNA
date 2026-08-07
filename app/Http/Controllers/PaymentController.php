<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Bank credentials configuration for VietQR
     */
    private $bankConfig = [
        'bank_id'      => 'MB',              // MBBank - 970422
        'bank_name'    => 'Ngân hàng Quân Đội (MBBank)',
        'account_no'   => '0987654321',
        'account_name' => 'COZYHNA COFFEE AND TEA',
        'template'     => 'compact2',
    ];

    /**
     * Get VietQR Payment Info & Image URL for an Order
     */
    public function getVietQr($orderCode)
    {
        $order = Order::where('order_code', $orderCode)
            ->with(['items.productSize.product', 'payment'])
            ->first();

        if (!$order) {
            return response()->json(['error' => 'Không tìm thấy đơn hàng.'], 404);
        }

        $amount      = (int) $order->total_amount;
        $addInfo     = $order->order_code;
        $accountName = rawurlencode($this->bankConfig['account_name']);
        
        $qrImageUrl = "https://img.vietqr.io/image/{$this->bankConfig['bank_id']}-{$this->bankConfig['account_no']}-{$this->bankConfig['template']}.png?amount={$amount}&addInfo={$addInfo}&accountName={$accountName}";

        return response()->json([
            'success'      => true,
            'order_code'   => $order->order_code,
            'amount'       => $amount,
            'formatted_amount' => number_format($amount, 0, ',', '.') . ' VNĐ',
            'qr_image'     => $qrImageUrl,
            'bank_name'    => $this->bankConfig['bank_name'],
            'account_no'   => $this->bankConfig['account_no'],
            'account_name' => $this->bankConfig['account_name'],
            'transfer_note'=> $addInfo,
            'payment_status' => $order->payment ? $order->payment->payment_status : 'PENDING',
        ]);
    }

    /**
     * Confirm/Verify Payment for an Order (Simulate or Webhook callback)
     */
    public function confirmPayment(Request $request, $orderCode)
    {
        $order = Order::where('order_code', $orderCode)->first();

        if (!$order) {
            return response()->json(['error' => 'Đơn hàng không tồn tại'], 404);
        }

        DB::transaction(function () use ($order) {
            Payment::where('order_id', $order->id)->update([
                'payment_status' => 'COMPLETED',
                'updated_at'     => now(),
            ]);

            $oldStatus = $order->order_status;
            if ($oldStatus === 'PENDING') {
                // Giữ nguyên PENDING, chỉ ghi lịch sử thanh toán
                OrderStatusHistory::create([
                    'order_id'   => $order->id,
                    'old_status' => $oldStatus,
                    'new_status' => $oldStatus,
                    'changed_by' => session('user_id'),
                    'note'       => 'Khách hàng đã thanh toán VietQR thành công',
                ]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Xác nhận thanh toán thành công! Đơn hàng đã được chuyển sang bộ phận pha chế.',
            'redirect'=> route('customer.orders'),
        ]);
    }

    /**
     * Show Fake Payment Gateway (for testing VNPAY/MOMO)
     */
    public function fakeGateway($orderCode)
    {
        $order = Order::where('order_code', $orderCode)
            ->with(['payment'])
            ->first();

        if (!$order) {
            return redirect()->route('customer.orders')->with('error', 'Không tìm thấy đơn hàng.');
        }
        
        $paymentMethod = $order->payment ? $order->payment->payment_method : 'online';

        return view('customer.fake_payment', compact('order', 'paymentMethod'));
    }

    /**
     * Process Fake Payment Result
     */
    public function processFakePayment(Request $request, $orderCode)
    {
        $order = Order::where('order_code', $orderCode)->first();

        if (!$order) {
            return redirect()->route('customer.orders')->with('error', 'Đơn hàng không tồn tại');
        }

        $status = $request->input('status'); // 'success' or 'failed'

        if ($status === 'success') {
            DB::transaction(function () use ($order) {
                Payment::where('order_id', $order->id)->update([
                    'payment_status' => 'COMPLETED',
                    'updated_at'     => now(),
                ]);

                $oldStatus = $order->order_status;
                if ($oldStatus === 'PENDING') {
                    // Giữ nguyên PENDING, chỉ ghi lịch sử thanh toán
                    OrderStatusHistory::create([
                        'order_id'   => $order->id,
                        'old_status' => $oldStatus,
                        'new_status' => $oldStatus,
                        'changed_by' => session('user_id'),
                        'note'       => 'Khách hàng đã thanh toán online thành công (giả lập)',
                    ]);
                }
            });

            return redirect()->route('customer.orders')->with('success', 'Thanh toán điện tử thành công! Đơn hàng đã được ghi nhận.');
        } else {
            DB::transaction(function () use ($order) {
                Payment::where('order_id', $order->id)->update([
                    'payment_status' => 'FAILED',
                    'updated_at'     => now(),
                ]);

                $oldStatus = $order->order_status;
                if (in_array($oldStatus, ['PENDING'])) {
                    $order->order_status = 'CANCELLED';
                    $order->status       = 'cancelled';
                    $order->cancel_reason = 'Khách hàng hủy thanh toán trực tuyến';
                    $order->cancelled_at = now();
                    $order->save();
                    
                    OrderStatusHistory::create([
                        'order_id'   => $order->id,
                        'old_status' => $oldStatus,
                        'new_status' => 'CANCELLED',
                        'changed_by' => session('user_id'),
                        'note'       => 'Hủy đơn do thanh toán thất bại',
                    ]);
                }
            });

            return redirect()->route('customer.orders')->with('error', 'Thanh toán trực tuyến thất bại hoặc đã bị hủy. Đơn hàng đã được hủy.');
        }
    }

    /**
     * Create VNPAY Payment Redirect URL
     */
    public function createVnpayPayment($order)
    {
        $vnp_TmnCode = config('vnpay.tmn_code');
        $vnp_HashSecret = config('vnpay.hash_secret');
        $vnp_Url = config('vnpay.url');
        // Use dynamic route instead of hardcoded config to prevent session loss from domain mismatch (localhost vs 127.0.0.1)
        $vnp_Returnurl = route('payment.vnpay.return');

        $vnp_TxnRef = $order->order_code;
        $vnp_OrderInfo = "Thanh toan don hang " . $order->order_code;
        $vnp_OrderType = 'other';
        $vnp_Amount = $order->total_amount * 100;
        $vnp_Locale = 'vn';
        $vnp_BankCode = ''; // Or dynamic based on user selection
        $vnp_IpAddr = request()->ip();

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        return redirect($vnp_Url);
    }

    /**
     * Process VNPAY Return Callback
     */
    public function vnpayReturn(Request $request)
    {
        $vnp_SecureHash = $request->input('vnp_SecureHash');
        $inputData = array();
        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
        
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $vnp_HashSecret = config('vnpay.hash_secret');
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        $orderCode = $request->input('vnp_TxnRef');
        $order = Order::where('order_code', $orderCode)->first();

        if (!$order) {
            return redirect()->route('customer.orders')->with('error', 'Đơn hàng không tồn tại.');
        }

        if ($secureHash == $vnp_SecureHash) {
            if ($request->input('vnp_ResponseCode') == '00') {
                DB::transaction(function () use ($order) {
                    Payment::where('order_id', $order->id)->update([
                        'payment_status' => 'COMPLETED',
                        'updated_at'     => now(),
                    ]);

                    $oldStatus = $order->order_status;
                    if ($oldStatus === 'PENDING') {
                        // Giữ nguyên PENDING, chỉ ghi lịch sử thanh toán
                        OrderStatusHistory::create([
                            'order_id'   => $order->id,
                            'old_status' => $oldStatus,
                            'new_status' => $oldStatus,
                            'changed_by' => session('user_id'),
                            'note'       => 'Khách hàng đã thanh toán VNPAY thành công',
                        ]);
                    }
                });
                return redirect()->route('customer.orders')->with('success', 'Thanh toán VNPAY thành công!');
            } else {
                DB::transaction(function () use ($order) {
                    Payment::where('order_id', $order->id)->update([
                        'payment_status' => 'FAILED',
                        'updated_at'     => now(),
                    ]);

                    $oldStatus = $order->order_status;
                    if (in_array($oldStatus, ['PENDING'])) {
                        $order->order_status = 'CANCELLED';
                        $order->status       = 'cancelled';
                        $order->cancel_reason = 'Khách hàng hủy thanh toán VNPAY';
                        $order->cancelled_at = now();
                        $order->save();
                        
                        OrderStatusHistory::create([
                            'order_id'   => $order->id,
                            'old_status' => $oldStatus,
                            'new_status' => 'CANCELLED',
                            'changed_by' => session('user_id'),
                            'note'       => 'Hủy đơn do thanh toán VNPAY thất bại',
                        ]);
                    }
                });
                return redirect()->route('customer.orders')->with('error', 'Thanh toán VNPAY bị hủy hoặc thất bại.');
            }
        } else {
            return redirect()->route('customer.orders')->with('error', 'Chữ ký thanh toán không hợp lệ.');
        }
    }
}
