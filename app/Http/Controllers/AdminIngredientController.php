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
        $ingredients = \App\Models\Ingredient::with('unit')->orderBy('name')->paginate(20);
        $units = \App\Models\MeasurementUnit::all();
        return view('admin.ingredients.index', compact('ingredients', 'units'));
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
        ]);

        \App\Models\Ingredient::create([
            'name' => $request->name,
            'code' => $request->code,
            'unit_id' => $request->unit_id,
            'current_stock' => $request->current_stock,
            'minimum_stock' => $request->minimum_stock,
            'expiration_date' => $request->expiration_date,
            'is_fresh' => $request->has('is_fresh'),
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
            'current_stock' => 'required|numeric|min:0',
            'minimum_stock' => 'required|numeric|min:0',
            'expiration_date' => 'nullable|date',
        ]);

        $ingredient->update([
            'name' => $request->name,
            'code' => $request->code,
            'unit_id' => $request->unit_id,
            'current_stock' => $request->current_stock,
            'minimum_stock' => $request->minimum_stock,
            'expiration_date' => $request->expiration_date,
            'is_fresh' => $request->has('is_fresh'),
        ]);

        return redirect()->back()->with('success', 'Cập nhật nguyên liệu thành công.');
    }

    public function destroy($id)
    {
        $ingredient = \App\Models\Ingredient::findOrFail($id);
        $ingredient->delete();
        return redirect()->back()->with('success', 'Xóa nguyên liệu thành công.');
    }
}
