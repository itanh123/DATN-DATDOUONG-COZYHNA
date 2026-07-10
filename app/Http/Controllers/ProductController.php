<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::query()->whereNull('deleted_at')->get();
        $products = Product::query()->whereNull('deleted_at')->with('category')->get();
        return view('admin.product', compact('categories', 'products'));
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
        // Eloquent/DB thường lưu status dạng 0/1, validate trả về boolean nhưng đôi khi form gửi string.
        $product->status = (int) $validated['status'];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $product->image = Storage::url($path);
        }

        $product->save();

        return redirect('/admin/product')->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect('/admin/product')->with('success', 'Product deleted successfully');
    }
}


