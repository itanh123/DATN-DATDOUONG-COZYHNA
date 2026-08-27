<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Topping;
use App\Models\Voucher;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $vouchers = Voucher::where('status', 1)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->whereRaw('used_count < quantity')
            ->orderBy('used_count', 'desc')
            ->orderBy('discount_value', 'desc')
            ->get();
            
        $banners = Banner::where('status', 1)
            ->where('position', 'sidebar')
            ->where(function($q) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', now());
            })
            ->where(function($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now());
            })
            ->orderBy('priority', 'asc')
            ->get();
        $categories = Category::where('status', true)->get();
        $toppings = Topping::where('status', true)
            ->where(function($q) {
                $q->whereNull('ingredient_id')
                  ->orWhereHas('ingredient', function($subQ) {
                      $subQ->where('current_stock', '>', 0);
                  });
            })
            ->get();

        $query = Product::query()
            ->whereNull('deleted_at')
            ->where('status', true)
            ->where(function($q) {
                $q->whereHas('category', function($subQ) {
                    $subQ->where('status', true);
                })->orWhereNull('category_id');
            })
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

        return view('customer.home', compact('products', 'categories', 'isFiltered', 'toppings', 'vouchers', 'banners'));
    }
}
