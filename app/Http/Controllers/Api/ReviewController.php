<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use App\Models\Order;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * API Gửi đánh giá
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'order_id' => 'required|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $user = clone $request->user();
        $user->load('customerProfile');
        $customerProfile = $user->customerProfile;

        if (!$customerProfile) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy hồ sơ khách hàng'], 404);
        }

        // Kiểm tra xem đơn hàng có phải của user này và đã giao không
        $order = Order::where('id', $request->order_id)
            ->where('customer_id', $customerProfile->id)
            ->where('status', 'completed')
            ->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Đơn hàng không hợp lệ hoặc chưa hoàn thành'], 400);
        }

        // Kiểm tra xem đã đánh giá chưa
        $existingReview = ProductReview::where('user_id', $user->id)
            ->where('order_id', $request->order_id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($existingReview) {
            return response()->json(['success' => false, 'message' => 'Bạn đã đánh giá sản phẩm này trong đơn hàng này rồi'], 400);
        }

        $review = ProductReview::create([
            'user_id' => $user->id,
            'product_id' => $request->product_id,
            'order_id' => $request->order_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'pending', // Chờ duyệt
        ]);

        \App\Jobs\ProcessAiReview::dispatch($review);

        return response()->json([
            'success' => true,
            'message' => 'Cảm ơn bạn đã đánh giá! Đánh giá đang được xử lý.',
            'data' => $review
        ]);
    }

    /**
     * API lấy danh sách đánh giá của sản phẩm (công khai)
     */
    public function getByProduct($productId)
    {
        $reviews = ProductReview::with('user:id,name,avatar')
            ->where('product_id', $productId)
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $reviews
        ]);
    }
}
