<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Topping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $toppings = Topping::where('status', true)->get();

        $query = Product::query()
            ->whereNull('deleted_at')
            ->where('status', true)
            ->with(['productSizes.size', 'productSizes.recipes.ingredients.ingredient', 'category', 'reviews.user']);

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // We fetch the products based on the query. For a highly trafficked page, 
        // we could paginate or limit, but to maintain the exact current view behavior
        // without breaking UI, we fetch what's needed.
        $products = $query->get();

        // Check availability logic
        $availableProducts = $products->filter(function ($product) {
            $hasAvailableSize = false;
            foreach ($product->productSizes as $size) {
                $isSizeAvailable = true;
                $recipe = $size->recipes->first();
                if ($recipe) {
                    foreach ($recipe->ingredients as $ri) {
                        if ($ri->ingredient && $ri->ingredient->current_stock < $ri->quantity) {
                            $isSizeAvailable = false;
                            break;
                        }
                    }
                }
                if ($isSizeAvailable) {
                    $hasAvailableSize = true;
                    break;
                }
            }
            return $hasAvailableSize;
        });

        // Cache sales data for 30 minutes to reduce DB load
        $sales = Cache::remember('product_total_sales', 1800, function () {
            return DB::table('order_items')
                ->join('product_sizes', 'order_items.product_size_id', '=', 'product_sizes.id')
                ->select('product_sizes.product_id', DB::raw('SUM(order_items.quantity) as total_sales'))
                ->groupBy('product_sizes.product_id')
                ->pluck('total_sales', 'product_id')
                ->toArray();
        });

        // Sort descending by sales
        $products = $availableProducts->sortByDesc(function ($product) use ($sales) {
            return $sales[$product->id] ?? 0;
        });

        $isFiltered = $request->has('category_id');

        return view('customer.home', compact('products', 'categories', 'isFiltered', 'toppings'));
    }
}
