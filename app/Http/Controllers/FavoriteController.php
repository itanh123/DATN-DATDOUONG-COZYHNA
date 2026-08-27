<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;

class FavoriteController extends Controller
{
    public function index()
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect('/login');
        }
        $user = User::find($userId);
        
        // Load favorite products along with necessary relations
        $favorites = $user->favoriteProducts()->with(['productSizes.size', 'category'])->get();
        return view('customer.favorites', compact('favorites'));
    }

    public function toggle(Request $request, $productId)
    {
        $userId = session('user_id');
        if (!$userId) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập'], 401);
            }
            return redirect('/login');
        }
        
        $user = User::find($userId);
        $product = Product::findOrFail($productId);
        
        if ($user->favoriteProducts()->where('product_id', $productId)->exists()) {
            $user->favoriteProducts()->detach($productId);
            $status = 'removed';
        } else {
            $user->favoriteProducts()->attach($productId);
            $status = 'added';
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'status' => $status,
                'message' => $status === 'added' ? 'Đã thêm vào danh sách yêu thích' : 'Đã xóa khỏi danh sách yêu thích'
            ]);
        }
        
        return back()->with('success', $status === 'added' ? 'Đã thêm vào danh sách yêu thích' : 'Đã xóa khỏi danh sách yêu thích');
    }
}
