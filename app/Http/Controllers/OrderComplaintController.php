<?php

namespace App\Http\Controllers;

use App\Models\OrderComplaint;
use App\Models\Order;
use App\Models\CustomerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\ComplaintReplyMail;
use Illuminate\Support\Facades\DB;

class OrderComplaintController extends Controller
{
    // Cấp quyền khách hàng gửi khiếu nại
    public function store(Request $request, $orderId)
    {
        $userId = session('user_id');
        if (!$userId) return redirect('/login')->with('error', __('Vui lòng đăng nhập.'));

        $customer = CustomerProfile::where('user_id', $userId)->first();
        if (!$customer) return redirect('/')->with('error', __('Lỗi tài khoản.'));

        $order = Order::where('id', $orderId)->where('customer_id', $customer->id)->first();
        if (!$order) return back()->with('error', __('Đơn hàng không hợp lệ.'));

        $request->validate([
            'incident_time' => 'required|date',
            'target_person' => 'nullable|string|max:255',
            'description' => 'required|string',
            'images.*' => 'nullable|image|max:5120', // max 5MB per image
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('complaints', 'public');
                $imagePaths[] = $path;
            }
        }

        OrderComplaint::create([
            'order_id' => $order->id,
            'customer_id' => $customer->id,
            'incident_time' => $request->incident_time,
            'target_person' => $request->target_person,
            'description' => $request->description,
            'images' => $imagePaths,
        ]);

        return back()->with('success', __('Đã gửi khiếu nại tới bộ phận Quản lý. Chúng tôi sẽ phản hồi sớm nhất qua Email của bạn.'));
    }

    // API cho Admin kiểm tra khiếu nại mới
    public function checkNew()
    {
        if (session('role_code') !== 'admin') return response()->json(['success' => false]);

        $complaint = OrderComplaint::with(['order', 'customer.user'])
            ->where('is_viewed_by_admin', false)
            ->orderBy('created_at', 'asc')
            ->first();

        if ($complaint) {
            return response()->json([
                'success' => true,
                'complaint' => $complaint
            ]);
        }

        return response()->json(['success' => false]);
    }

    // Đánh dấu đã xem (khi Admin bấm OK)
    public function markViewed(Request $request, $id)
    {
        if (session('role_code') !== 'admin') return response()->json(['success' => false]);

        $complaint = OrderComplaint::find($id);
        if ($complaint) {
            $complaint->update([
                'is_viewed_by_admin' => true,
                'status' => 'VIEWED'
            ]);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }

    // Admin gửi email phản hồi
    public function reply(Request $request, $id)
    {
        if (session('role_code') !== 'admin') return back()->with('error', __('Không có quyền truy cập.'));

        $request->validate([
            'reply_content' => 'required|string'
        ]);

        $complaint = OrderComplaint::with('customer.user')->find($id);
        if (!$complaint) return back()->with('error', __('Không tìm thấy khiếu nại.'));

        $complaint->update([
            'admin_reply' => $request->reply_content,
            'status' => 'REPLIED'
        ]);

        // Gửi email cho khách hàng
        if ($complaint->customer && $complaint->customer->user && $complaint->customer->user->email) {
            try {
                Mail::to($complaint->customer->user->email)->send(new ComplaintReplyMail($complaint));
            } catch (\Exception $e) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'Đã lưu phản hồi nhưng không thể gửi email: ' . $e->getMessage()]);
                }
                return back()->with('error', 'Đã lưu phản hồi nhưng không thể gửi email: ' . $e->getMessage());
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Đã gửi phản hồi khiếu nại thành công!']);
        }
        return back()->with('success', __('Đã gửi phản hồi khiếu nại thành công!'));
    }
}
