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
                $order->order_status = 'CONFIRMED';
                $order->status       = 'confirmed';
                $order->save();

                OrderStatusHistory::create([
                    'order_id'   => $order->id,
                    'old_status' => $oldStatus,
                    'new_status' => 'CONFIRMED',
                    'changed_by' => session('user_id'),
                    'note'       => 'Xác nhận thanh toán VietQR thành công',
                ]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Xác nhận thanh toán thành công! Đơn hàng đã được chuyển sang bộ phận pha chế.',
            'redirect'=> route('customer.orders'),
        ]);
    }
}
