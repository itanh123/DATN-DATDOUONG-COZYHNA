<?php

namespace App\Http\Controllers;

use App\Models\ProductReview;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Store a new product review (Customer).
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'order_id' => 'required|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $userId = session('user_id');
        if (!$userId) {
            return redirect('/login')->with('error', 'Bạn cần đăng nhập để đánh giá.');
        }

        $customerProfile = \Illuminate\Support\Facades\DB::table('customer_profiles')->where('user_id', $userId)->first();
        if (!$customerProfile) {
            return back()->with('error', 'Không tìm thấy thông tin khách hàng.');
        }

        // Optional: verify that the user actually bought this product in this order
        $order = Order::where('id', $request->order_id)
                      ->where('customer_id', $customerProfile->id)
                      ->where('status', 'completed')
                      ->first();

        if (!$order) {
            return back()->with('error', 'Không tìm thấy đơn hàng hợp lệ để đánh giá.');
        }

        // Check if review already exists for this order & product
        $existingReview = ProductReview::where('user_id', $userId)
                                       ->where('order_id', $request->order_id)
                                       ->where('product_id', $request->product_id)
                                       ->first();

        if ($existingReview) {
            return back()->with('error', 'Bạn đã đánh giá sản phẩm này trong đơn hàng này rồi.');
        }

        $status = 'approved';
        $comment = $request->comment;
        
        if ($comment) {
            $badWords = ['địt', 'lồn', 'cặc', 'buồi', 'đụ', 'chó', 'điếm', 'đĩ', 'đm', 'vcl', 'vl', 'đcm', 'dkm', 'ngu', 'cc', 'cứt', 'fuck', 'shit', 'bitch'];
            foreach ($badWords as $word) {
                // Use regex with 'u' modifier for unicode and 'i' for case-insensitivity
                if (preg_match("/\b" . preg_quote($word, '/') . "\b/ui", $comment)) {
                    $status = 'rejected';
                    break;
                }
            }
        }

        ProductReview::create([
            'user_id' => $userId,
            'product_id' => $request->product_id,
            'order_id' => $request->order_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => $status,
        ]);

        if ($status === 'rejected') {
            return back()->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm! Đánh giá của bạn đang chờ kiểm duyệt do chứa từ khóa nhạy cảm.');
        }

        return back()->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm!');
    }

    /**
     * Display a listing of the reviews (Admin).
     */
    public function index()
    {
        if (session('role_code') !== 'admin') {
            return redirect('/login');
        }

        $reviews = ProductReview::with(['user', 'product'])
                                ->orderBy('created_at', 'desc')
                                ->paginate(20);

        return view('admin.reviews', compact('reviews'));
    }

    /**
     * Update the status of a review (Admin).
     */
    public function updateStatus(Request $request, ProductReview $review)
    {
        if (session('role_code') !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $review->status = $request->status;
        $review->save();

        return response()->json(['success' => true, 'message' => 'Cập nhật trạng thái thành công.']);
    }

    /**
     * Remove the specified review (Admin).
     */
    public function destroy(ProductReview $review)
    {
        if (session('role_code') !== 'admin') {
            return back()->with('error', 'Unauthorized');
        }

        $review->delete();

        return back()->with('success', 'Đã xóa đánh giá.');
    }
}
