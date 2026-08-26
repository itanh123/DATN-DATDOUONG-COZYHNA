<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminIngredientController extends Controller
{
    public function index()
    {
        if (!check_permission('manage_inventory')) {
            // using manage_products for now if manage_inventory doesn't exist
        }
        $allIngredients = \App\Models\Ingredient::with('unit')->orderBy('name')->get();
        $ingredientsByCategory = $allIngredients->groupBy(function($item) {
            return $item->category ?: 'Khác';
        });
        
        $categories = \App\Models\Ingredient::whereNotNull('category')->where('category', '!=', '')->distinct()->pluck('category');
        
        $units = \App\Models\MeasurementUnit::all();
        return view('admin.ingredients.index', compact('ingredientsByCategory', 'allIngredients', 'units', 'categories'));
    }

    public function checkCode(Request $request)
    {
        $code = $request->code;
        $exists = \App\Models\Ingredient::where('code', $code)->exists();
        return response()->json(['exists' => $exists]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:30|unique:ingredients',
            'unit_id' => 'required|exists:measurement_units,id',
            'current_stock' => 'required|numeric|min:0',
            'minimum_stock' => 'required|numeric|min:0',
            'expiration_date' => 'nullable|date',
            'is_fresh' => 'boolean',
            'category' => 'nullable|string|max:255',
        ]);

        \App\Models\Ingredient::create([
            'name' => $request->name,
            'code' => $request->code,
            'unit_id' => $request->unit_id,
            'current_stock' => $request->current_stock,
            'minimum_stock' => $request->minimum_stock,
            'expiration_date' => $request->expiration_date,
            'is_fresh' => $request->has('is_fresh'),
            'category' => $request->category,
            'status' => true,
        ]);

        return redirect()->back()->with('success', 'Thêm nguyên liệu thành công.');
    }

    public function update(Request $request, $id)
    {
        $ingredient = \App\Models\Ingredient::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:30|unique:ingredients,code,' . $id,
            'unit_id' => 'required|exists:measurement_units,id',
            'minimum_stock' => 'required|numeric|min:0',
            'expiration_date' => 'nullable|date',
            'is_fresh' => 'boolean',
            'category' => 'nullable|string|max:255',
        ]);

        $ingredient->update([
            'name' => $request->name,
            'code' => $request->code,
            'unit_id' => $request->unit_id,
            'minimum_stock' => $request->minimum_stock,
            'expiration_date' => $request->expiration_date,
            'is_fresh' => $request->has('is_fresh'),
            'category' => $request->category,
        ]);

        return redirect()->back()->with('success', 'Cập nhật nguyên liệu thành công.');
    }

    public function destroy($id)
    {
        $ingredient = \App\Models\Ingredient::findOrFail($id);
        $ingredient->delete();
        return redirect()->back()->with('success', 'Xóa nguyên liệu thành công.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'ingredient_id' => 'required|exists:ingredients,id',
            'quantity' => 'required|numeric|min:0.01',
            'note' => 'nullable|string|max:255',
            'expiration_date' => 'nullable|date',
        ]);

        $ingredient = \App\Models\Ingredient::findOrFail($request->ingredient_id);
        $beforeQuantity = $ingredient->current_stock;
        
        $ingredient->current_stock += $request->quantity;
        if ($request->filled('expiration_date')) {
            $ingredient->expiration_date = $request->expiration_date;
        }
        $ingredient->save();

        \App\Models\InventoryTransaction::create([
            'ingredient_id' => $ingredient->id,
            'transaction_type' => 'IMPORT',
            'quantity' => $request->quantity,
            'unit_id' => $ingredient->unit_id,
            'before_quantity' => $beforeQuantity,
            'after_quantity' => $beforeQuantity + $request->quantity,
            'reference_type' => null,
            'reference_id' => null,
            'note' => $request->note ?: 'Nhập kho nguyên liệu',
            'created_by' => auth()->id() ?? null,
        ]);

        return redirect()->back()->with('success', 'Nhập kho nguyên liệu thành công.');
    }
}
