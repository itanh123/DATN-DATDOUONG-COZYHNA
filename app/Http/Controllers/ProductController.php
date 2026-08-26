<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $categoriesQuery = Category::query()->whereNull('deleted_at');
        if ($search) {
            $categoriesQuery->where('name', 'like', '%' . $search . '%');
        }
        $categories = $categoriesQuery->get();
        
        $query = Product::query()->whereNull('deleted_at')->with('category', 'productSizes.size');
        
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->has('status') && $request->status !== null) {
            $query->where('status', $request->status);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('code', 'like', '%' . $search . '%');
            });
        }
        
        $products = $query->paginate(10)->withQueryString();
        
        $sizesQuery = Size::orderBy('name');
        if ($search) {
            $sizesQuery->where('name', 'like', '%' . $search . '%');
        }
        $sizes = $sizesQuery->get();

        $ingredients = \App\Models\Ingredient::orderBy('name')->get();

        $toppingsQuery = \App\Models\Topping::query();
        if ($search) {
            $toppingsQuery->where('name', 'like', '%' . $search . '%');
        }
        $toppings = $toppingsQuery->get();

        $allCategories = Category::query()->whereNull('deleted_at')->get();
        $allSizes = Size::orderBy('name')->get();
        $allToppings = \App\Models\Topping::orderBy('name')->get();

        return view('admin.product', compact('categories', 'products', 'sizes', 'ingredients', 'toppings', 'allCategories', 'allSizes', 'allToppings'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:30', 'unique:products,code'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'description' => ['nullable', 'string'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            // Nhận status từ form (string "0"/"1") để mapping chắc chắn
            'status' => ['required', 'in:0,1'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'is_auto_stock' => ['nullable', 'boolean'],
        ]);

        $path = $request->file('image')->store('products', 'public');

        Product::create([
            'category_id' => $validated['category_id'],
            'code' => $validated['code'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'image' => Storage::url($path),
            'status' => (int) $validated['status'],
            'stock' => $validated['stock'] ?? 0,
            'is_auto_stock' => $request->has('is_auto_stock'),
        ]);


        return redirect('/admin/product')->with('success', 'Tạo sản phẩm thành công.');
    }

    public function update(Request $request, Product $product)
    {
        $role = session('role_code');
        
        if ($role === 'admin') {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'code' => ['required', 'string', 'max:30', 'unique:products,code,' . $product->id],
                'category_id' => ['required', 'integer', 'exists:categories,id'],
                'description' => ['nullable', 'string'],
                'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
                'status' => ['required', 'in:0,1'],
                'stock' => ['nullable', 'integer', 'min:0'],
                'is_auto_stock' => ['nullable', 'boolean'],
            ]);

            $product->category_id = $validated['category_id'];
            $product->code = $validated['code'];
            $product->name = $validated['name'];
            $product->description = $validated['description'] ?? null;
            $product->stock = $validated['stock'] ?? 0;
            $product->is_auto_stock = $request->has('is_auto_stock');

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('products', 'public');
                $product->image = Storage::url($path);
            }
        } else {
            $validated = $request->validate([
                'status' => ['required', 'in:0,1'],
            ]);
        }

        $product->status = (int) $validated['status'];

        $product->save();

        return redirect('/admin/product')->with('success', 'Cập nhật sản phẩm thành công.');
    }

    public function destroy(Product $product)
    {
        try {
            // Kiểm tra xem sản phẩm đã có trong đơn hàng nào chưa
            $hasOrders = \App\Models\OrderItem::whereIn('product_size_id', $product->productSizes->pluck('id'))->exists();

            \Illuminate\Support\Facades\DB::transaction(function () use ($product, $hasOrders) {
                // Xóa các sản phẩm trong giỏ hàng
                \App\Models\CartItem::whereIn('product_size_id', $product->productSizes->pluck('id'))->delete();
                
                if ($hasOrders) {
                    // Xóa mềm nếu có đơn hàng để không ảnh hưởng lịch sử
                    $product->update(['deleted_at' => now(), 'status' => 0]);
                } else {
                    // Gỡ bỏ quan hệ
                    $product->favoritedBy()->detach();
                    $product->toppings()->detach();
                    
                    // Xóa các bản ghi con
                    $product->reviews()->delete();
                    \App\Models\ProductImage::where('product_id', $product->id)->delete();
                    $product->productSizes()->delete();
                    
                    // Xóa sản phẩm
                    $product->delete();
                }
            });

            return redirect('/admin/product')->with('success', 'Xóa sản phẩm thành công.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xóa: ' . $e->getMessage());
        }
    }

    public function storeSize(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:20', 'unique:sizes,name'],
            'volume_ml' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        Size::create($validated);

        return redirect('/admin/product')->with('success', 'Tạo kích thước thành công.');
    }

    public function updateSize(Request $request, Size $size)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:20', 'unique:sizes,name,' . $size->id],
            'volume_ml' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $size->update($validated);

        return redirect('/admin/product')->with('success', 'Cập nhật kích thước thành công.');
    }

    public function destroySize(Size $size)
    {
        $size->delete();
        return redirect('/admin/product')->with('success', 'Xóa kích thước thành công.');
    }

    public function syncSizes(Request $request, Product $product)
    {
        $sizesData = $request->input('sizes', []);
        $defaultSizeId = $request->input('default_size_id');

        $activeSizeIds = [];

        foreach ($sizesData as $sizeId => $data) {
            if (isset($data['active']) && $data['active'] == '1') {
                $activeSizeIds[] = $sizeId;
                
                $product->productSizes()->updateOrCreate(
                    ['size_id' => $sizeId],
                    [
                        'selling_price' => $data['selling_price'] ?: 0,
                        'cost_price' => $data['cost_price'] ?? 0,
                        'is_default' => ($defaultSizeId == $sizeId),
                        'status' => true,
                    ]
                );
            }
        }

        // Xử lý các size bị bỏ chọn
        $removedSizes = $product->productSizes()->whereNotIn('size_id', $activeSizeIds)->get();
        foreach ($removedSizes as $rs) {
            try {
                $rs->delete();
            } catch (\Illuminate\Database\QueryException $e) {
                // Nếu dính khóa ngoại (đang nằm trong giỏ hàng/đơn hàng), chuyển status = false thay vì xóa
                $rs->update(['status' => false, 'is_default' => false]);
            }
        }

        return redirect('/admin/product')->with('success', 'Cập nhật kích thước sản phẩm thành công.');
    }

    // --- Category Management ---

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'status' => ['boolean'],
            'display_order' => ['nullable', 'numeric', 'min:1'],
        ]);

        $validated['status'] = $request->has('status');
        if (isset($validated['display_order'])) {
            $validated['display_order'] = (int) $validated['display_order'];
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            $validated['image'] = Storage::url($path);
        } else {
            $validated['image'] = null;
        }

        Category::create($validated);

        return redirect('/admin/product')->with('success', 'Tạo danh mục thành công.');
    }

    public function updateCategory(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name,' . $category->id],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'status' => ['boolean'],
            'display_order' => ['nullable', 'numeric', 'min:1'],
        ]);

        $validated['status'] = $request->has('status');
        if (isset($validated['display_order'])) {
            $validated['display_order'] = (int) $validated['display_order'];
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            $validated['image'] = Storage::url($path);
        } else {
            unset($validated['image']);
        }

        $category->update($validated);

        return redirect('/admin/product')->with('success', 'Cập nhật danh mục thành công.');
    }

    public function destroyCategory(Category $category)
    {
        $category->delete();
        return redirect('/admin/product')->with('success', 'Xóa danh mục thành công.');
    }

    // --- Recipe Management ---
    public function recipe(Product $product)
    {
        $product->load('productSizes.size', 'productSizes.recipes.ingredients');
        $ingredients = \App\Models\Ingredient::orderBy('name')->get();
        $categories = \App\Models\Ingredient::select('category')->distinct()->whereNotNull('category')->pluck('category');
        return view('admin.recipes.index', compact('product', 'ingredients', 'categories'));
    }

    public function updateRecipe(Request $request, Product $product)
    {
        $data = $request->input('recipes', []);

        foreach ($data as $productSizeId => $recipeData) {
            $recipe = \App\Models\Recipe::firstOrCreate(
                ['product_size_id' => $productSizeId],
                ['name' => 'Recipe for size ' . $productSizeId]
            );

            if (isset($recipeData['instruction'])) {
                $recipe->instruction = $recipeData['instruction'];
                $recipe->save();
            }

            $recipe->ingredients()->delete();

            if (isset($recipeData['ingredients'])) {
                foreach ($recipeData['ingredients'] as $ingId => $qtyData) {
                    if (!empty($qtyData['quantity']) && $qtyData['quantity'] > 0) {
                        $recipe->ingredients()->create([
                            'ingredient_id' => $ingId,
                            'unit_id' => \App\Models\Ingredient::find($ingId)->unit_id ?? 1,
                            'quantity' => $qtyData['quantity']
                        ]);
                    }
                }
            }
        }

        return redirect('/admin/product')->with('success', 'Cập nhật công thức thành công!');
    }

    public function calculateStock(Product $product)
    {
        // Find default size or the first active one
        $productSize = $product->productSizes()->where('is_default', true)->first();
        if (!$productSize) {
            $productSize = $product->productSizes()->where('status', true)->first();
        }

        if (!$productSize) {
            return response()->json(['success' => true, 'stock' => 0]);
        }

        // Get the first recipe for this size
        $recipe = $productSize->recipes()->with('ingredients.ingredient')->first();

        if (!$recipe || $recipe->ingredients->isEmpty()) {
            return response()->json(['success' => true, 'stock' => 0]);
        }

        $maxProducts = -1;

        foreach ($recipe->ingredients as $recipeIngredient) {
            $ingredient = $recipeIngredient->ingredient;
            if (!$ingredient || $recipeIngredient->quantity <= 0) {
                continue;
            }

            // Calculate max products based on this ingredient
            $possible = floor($ingredient->current_stock / $recipeIngredient->quantity);
            
            if ($maxProducts === -1 || $possible < $maxProducts) {
                $maxProducts = $possible;
            }
        }

        if ($maxProducts === -1) {
            $maxProducts = 0;
        }

        return response()->json(['success' => true, 'stock' => $maxProducts]);
    }
}


