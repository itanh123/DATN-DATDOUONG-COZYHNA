<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InventoryTransaction;

class InventoryController extends Controller
{
    public function index()
    {
        return view('admin.inventory');
    }

    public function transactions()
    {
        $transactions = InventoryTransaction::with(['ingredient', 'ingredient.unit'])
            ->orderBy('created_at', 'desc')
            ->paginate(50);
            
        return view('admin.inventory.transactions', compact('transactions'));
    }
}
