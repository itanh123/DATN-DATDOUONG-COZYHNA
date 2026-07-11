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
        $categories = Category::query()->whereNull('deleted_at')->get();
        
        $query = Product::query()->whereNull('deleted_at')->with('category', 'productSizes.size');
        
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->has('status') && $request->status !== null) {
            $query->where('status', $request->status);
        }
        
        $products = $query->paginate(10);
        $sizes = Size::orderBy('name')->get();
        return view('admin.product', compact('categories', 'products', 'sizes'));
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


        return redirect('/admin/product')->with('success', 'Product created successfully');
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

        return redirect('/admin/product')->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect('/admin/product')->with('success', 'Product deleted successfully');
    }

    public function storeSize(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:20', 'unique:sizes,name'],
            'volume_ml' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        Size::create($validated);

        return redirect('/admin/product')->with('success', 'Size created successfully');
    }

    public function updateSize(Request $request, Size $size)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:20', 'unique:sizes,name,' . $size->id],
            'volume_ml' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $size->update($validated);

        return redirect('/admin/product')->with('success', 'Size updated successfully');
    }

    public function destroySize(Size $size)
    {
        $size->delete();
        return redirect('/admin/product')->with('success', 'Size deleted successfully');
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

        return redirect('/admin/product')->with('success', 'Product sizes updated successfully');
    }

    // --- Category Management ---

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'string'],
            'status' => ['boolean'],
        ]);

        $validated['status'] = $request->has('status');

        Category::create($validated);

        return redirect('/admin/product')->with('success', 'Category created successfully');
    }

    public function updateCategory(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name,' . $category->id],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'string'],
            'status' => ['boolean'],
        ]);

        $validated['status'] = $request->has('status');

        $category->update($validated);

        return redirect('/admin/product')->with('success', 'Category updated successfully');
    }

    public function destroyCategory(Category $category)
    {
        $category->delete();
        return redirect('/admin/product')->with('success', 'Category deleted successfully');
    }
}


