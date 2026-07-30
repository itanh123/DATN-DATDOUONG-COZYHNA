<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Floor;
use App\Models\TableArea;
use App\Models\RestaurantTable;
use App\Models\MergedTable;
use App\Models\MergedTableItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RestaurantTableController extends Controller
{
    public function index()
    {
        if (!check_permission('view_dashboard')) {
            return redirect('/login');
        }

        $floors = Floor::with(['areas.tables' => function ($q) {
            $q->withCount('mergedTableItems');
        }])->where('status', true)->orderBy('display_order')->get();

        return view('admin.tables.index', compact('floors'));
    }

    // ─── Floor CRUD ─────────────────────────────────────────────────

    public function storeFloor(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
        ]);

        $maxOrder = Floor::max('display_order') ?? 0;
        Floor::create([
            'code'          => 'F' . strtoupper(Str::random(4)),
            'name'          => $request->name,
            'description'   => $request->description,
            'display_order' => $maxOrder + 1,
            'status'        => true,
        ]);

        return back()->with('success', 'Đã thêm tầng mới!');
    }

    public function destroyFloor(Floor $floor)
    {
        if ($floor->tables()->count() > 0) {
            return back()->with('error', 'Không thể xóa tầng đang có bàn!');
        }
        $floor->areas()->delete();
        $floor->delete();
        return back()->with('success', 'Đã xóa tầng!');
    }

    // ─── Table CRUD ─────────────────────────────────────────────────

    public function storeTable(Request $request)
    {
        $request->validate([
            'area_id'    => 'required|exists:table_areas,id',
            'table_name' => 'required|string|max:100',
            'capacity'   => 'required|integer|min:1',
            'shape'      => 'required|in:round,square,rectangle',
        ]);

        $area = TableArea::findOrFail($request->area_id);
        
        $x = $request->location_x;
        $y = $request->location_y;
        
        if ($x === null || $y === null) {
            $existingCount = $area->tables()->count();
            $x = $existingCount % 6; // 6 cột
            $y = intdiv($existingCount, 6);
        }

        RestaurantTable::create([
            'area_id'    => $request->area_id,
            'code'       => 'TBL-' . strtoupper(Str::random(6)),
            'table_name' => $request->table_name,
            'qr_token'   => Str::random(40),
            'capacity'   => $request->capacity,
            'minimum_capacity' => $request->minimum_capacity ?? 1,
            'shape'      => $request->shape,
            'status'     => 'available',
            'location_x' => $x,
            'location_y' => $y,
        ]);

        return back()->with('success', 'Đã thêm bàn mới!');
    }

    public function updateTableStatus(Request $request, RestaurantTable $table)
    {
        $request->validate([
            'status' => 'required|in:available,occupied,reserved,disabled',
        ]);

        $table->update(['status' => $request->status]);

        return response()->json(['success' => true]);
    }

    public function updatePosition(Request $request, RestaurantTable $table)
    {
        $request->validate([
            'location_x' => 'required|numeric|min:0',
            'location_y' => 'required|numeric|min:0',
        ]);

        $table->update([
            'location_x' => $request->location_x,
            'location_y' => $request->location_y,
        ]);

        return response()->json(['success' => true]);
    }

    public function destroyTable(RestaurantTable $table)
    {
        if ($table->status === 'occupied') {
            return back()->with('error', 'Không thể xóa bàn đang có khách!');
        }

        // Tách khỏi bàn ghép nếu có
        MergedTableItem::where('table_id', $table->id)->delete();

        $table->delete();
        return back()->with('success', 'Đã xóa bàn!');
    }

    // ─── Merge/Unmerge ─────────────────────────────────────────────

    public function mergeTables(Request $request)
    {
        $request->validate([
            'primary_table_id'  => 'required|exists:restaurant_tables,id',
            'table_ids'         => 'required|array|min:1',
            'table_ids.*'       => 'exists:restaurant_tables,id',
        ]);

        $primaryTable = RestaurantTable::findOrFail($request->primary_table_id);
        $tableIds = collect($request->table_ids)->filter(fn($id) => $id != $request->primary_table_id);

        // Kiểm tra tất cả các bàn phải đang trống
        $allTableIds = $tableIds->push($request->primary_table_id);
        $nonAvailable = RestaurantTable::whereIn('id', $allTableIds)
            ->where('status', '!=', 'available')
            ->count();

        if ($nonAvailable > 0) {
            return response()->json(['success' => false, 'error' => 'Chỉ có thể ghép các bàn đang trống!'], 400);
        }

        // Tính tổng sức chứa
        $totalCapacity = RestaurantTable::whereIn('id', $allTableIds)->sum('capacity');

        // Tạo bàn ghép mới
        $mergedTable = MergedTable::create([
            'code'     => 'MRG-' . strtoupper(Str::random(6)),
            'name'     => $primaryTable->table_name . ' (Ghép)',
            'capacity' => $totalCapacity,
            'status'   => true,
        ]);

        // Thêm từng bàn vào danh sách
        MergedTableItem::create([
            'merged_table_id' => $mergedTable->id,
            'table_id'        => $primaryTable->id,
            'is_primary'      => true,
        ]);

        foreach ($tableIds as $tid) {
            MergedTableItem::create([
                'merged_table_id' => $mergedTable->id,
                'table_id'        => $tid,
                'is_primary'      => false,
            ]);

            RestaurantTable::where('id', $tid)->update(['status' => 'merged']);
        }

        // Bàn chính vẫn available để nhận khách
        $primaryTable->update(['status' => 'available']);

        return response()->json([
            'success' => true,
            'message' => 'Đã ghép bàn thành công!',
            'merged_table' => $mergedTable->load('items.table'),
        ]);
    }

    public function unmergeTables(Request $request)
    {
        $request->validate([
            'table_id' => 'required|exists:restaurant_tables,id',
        ]);

        $table = RestaurantTable::findOrFail($request->table_id);

        // Tìm bàn ghép chứa bàn này
        $mergedItem = MergedTableItem::where('table_id', $table->id)->first();
        if (!$mergedItem) {
            return response()->json(['success' => false, 'error' => 'Bàn này chưa được ghép!'], 400);
        }

        $mergedTable = MergedTable::find($mergedItem->merged_table_id);

        // Trả tất cả bàn về trạng thái available
        $tableIds = MergedTableItem::where('merged_table_id', $mergedTable->id)->pluck('table_id');
        RestaurantTable::whereIn('id', $tableIds)->update(['status' => 'available']);

        // Xóa bàn ghép
        MergedTableItem::where('merged_table_id', $mergedTable->id)->delete();
        $mergedTable->delete();

        return response()->json(['success' => true, 'message' => 'Đã tách bàn thành công!']);
    }

    // ─── Area CRUD ───────────────────────────────────────────────────

    public function storeArea(Request $request)
    {
        $request->validate([
            'floor_id' => 'required|exists:floors,id',
            'name'     => 'required|string|max:100',
        ]);

        $maxOrder = TableArea::where('floor_id', $request->floor_id)->max('display_order') ?? 0;

        TableArea::create([
            'floor_id'      => $request->floor_id,
            'code'          => 'A' . strtoupper(Str::random(4)),
            'name'          => $request->name,
            'description'   => $request->description ?? null,
            'display_order' => $maxOrder + 1,
            'status'        => true,
        ]);

        return back()->with('success', 'Đã thêm khu vực mới!');
    }
}
