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
            'status' => ['required', 'boolean'],
        ]);

        $path = $request->file('image')->store('products', 'public');

        Product::create([
            'category_id' => $validated['category_id'],
            'code' => $validated['code'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'image' => Storage::url($path),
            'status' => $validated['status'],
        ]);

        return redirect('/admin/product')->with('success', 'Product created successfully');
    }
}

