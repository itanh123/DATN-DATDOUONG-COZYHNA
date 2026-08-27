<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Topping;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Lấy danh sách sản phẩm mẫu cho Mobile App
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'productSizes.recipes.ingredients.ingredient'])
            ->where('status', 1);
            
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        $products = $query->get();

        return response()->json([
            'success' => true,
            'message' => 'Lấy danh sách sản phẩm thành công',
            'data' => $products
        ], 200);
    }

    /**
     * Lấy danh sách danh mục
     */
    public function getCategories()
    {
        $categories = Category::where('status', 1)->get();

        return response()->json([
            'success' => true,
            'message' => 'Lấy danh sách danh mục thành công',
            'data' => $categories
        ], 200);
    }

    /**
     * Lấy danh sách Topping
     */
    public function getToppings()
    {
        $toppings = Topping::where('status', 1)->get();

        return response()->json([
            'success' => true,
            'message' => 'Lấy danh sách Topping thành công',
            'data' => $toppings
        ], 200);
    }
}
