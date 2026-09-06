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
            return redirect('/login')->with('error', __('Bạn cần đăng nhập để đánh giá.'));
        }

        $customerProfile = \Illuminate\Support\Facades\DB::table('customer_profiles')->where('user_id', $userId)->first();
        if (!$customerProfile) {
            return back()->with('error', __('Không tìm thấy thông tin khách hàng.'));
        }

        // Optional: verify that the user actually bought this product in this order
        $order = Order::where('id', $request->order_id)
                      ->where('customer_id', $customerProfile->id)
                      ->where('status', 'completed')
                      ->first();

        if (!$order) {
            return back()->with('error', __('Không tìm thấy đơn hàng hợp lệ để đánh giá.'));
        }

        // Check if review already exists for this order & product
        $existingReview = ProductReview::where('user_id', $userId)
                                       ->where('order_id', $request->order_id)
                                       ->where('product_id', $request->product_id)
                                       ->first();

        if ($existingReview) {
            return back()->with('error', __('Bạn đã đánh giá sản phẩm này trong đơn hàng này rồi.'));
        }

        $review = ProductReview::create([
            'user_id' => $userId,
            'product_id' => $request->product_id,
            'order_id' => $request->order_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'pending', // Wait for AI
        ]);

        \App\Jobs\ProcessAiReview::dispatch($review);

        return back()->with('success', __('Cảm ơn bạn đã đánh giá sản phẩm! Hệ thống AI đang xử lý đánh giá của bạn.'));
    }

    /**
     * Display a listing of the reviews (Admin).
     */
    public function index()
    {
        if (!check_permission('view_products')) {
            return redirect('/login')->with('error', __('Không có quyền truy cập.'));
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
        if (!check_permission('view_products')) {
            return response()->json(['success' => false, 'message' => 'Không có quyền truy cập.'], 403);
        }

        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $review->status = $request->status;
        $review->save();

        return response()->json(['success' => true, 'message' => 'Cập nhật trạng thái thành công.']);
    }

    /**
     * Reply to the specified review (Admin/Staff).
     */
    public function reply(Request $request, ProductReview $review)
    {
        if (!check_permission('view_products')) {
            return back()->with('error', __('Không có quyền truy cập.'));
        }

        $request->validate([
            'admin_reply' => 'required|string|max:1000',
        ]);

        $review->admin_reply = $request->admin_reply;
        $review->save();

        return back()->with('success', __('Đã lưu câu trả lời.'));
    }
}
