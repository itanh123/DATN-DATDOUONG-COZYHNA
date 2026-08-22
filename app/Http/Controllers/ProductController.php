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
        ]);

        $path = $request->file('image')->store('products', 'public');

        Product::create([
            'category_id' => $validated['category_id'],
            'code' => $validated['code'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'image' => Storage::url($path),
            'status' => (int) $validated['status'],
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
            ]);

            $product->category_id = $validated['category_id'];
            $product->code = $validated['code'];
            $product->name = $validated['name'];
            $product->description = $validated['description'] ?? null;

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

        $product->productSizes()->delete();

        foreach ($sizesData as $sizeId => $data) {
            if (isset($data['active']) && $data['active'] == '1') {
                $product->productSizes()->create([
                    'size_id' => $sizeId,
                    'selling_price' => $data['selling_price'] ?: 0,
                    'cost_price' => $data['cost_price'] ?? 0,
                    'is_default' => ($defaultSizeId == $sizeId),
                    'status' => true,
                ]);
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
        ]);

        $validated['status'] = $request->has('status');

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
        ]);

        $validated['status'] = $request->has('status');

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
        return view('admin.recipes.index', compact('product', 'ingredients'));
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
}


