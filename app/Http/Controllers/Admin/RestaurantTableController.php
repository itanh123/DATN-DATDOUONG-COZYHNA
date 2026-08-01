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

        $mergedGroups = [];
        $occupiedCells = [];

        foreach ($floors as $floor) {
            foreach ($floor->areas as $area) {
                foreach ($area->tables as $table) {
                    $mItem = MergedTableItem::where('table_id', $table->id)->first();
                    if ($mItem) {
                        $mId = $mItem->merged_table_id;
                        if (!isset($mergedGroups[$area->id][$mId])) {
                            $mTablesIds = MergedTableItem::where('merged_table_id', $mId)->pluck('table_id');
                            $mTables = RestaurantTable::whereIn('id', $mTablesIds)->get();

                            $primaryTableId = MergedTableItem::where('merged_table_id', $mId)->where('is_primary', true)->value('table_id');
                            $primaryTable = $mTables->firstWhere('id', $primaryTableId) ?? $mTables->first();
                            
                            $mergedTableRecord = MergedTable::find($mId);

                            if ($primaryTable && $primaryTable->area_id == $area->id) {
                                $coords = $mTables->map(fn($t) => (int)$t->location_x . ',' . (int)$t->location_y)->toArray();
                                $mergedGroups[$area->id][$mId] = [
                                    'primaryTable' => $primaryTable,
                                    'capacity' => $mergedTableRecord ? $mergedTableRecord->capacity : $mTables->sum('capacity'),
                                    'name' => $mergedTableRecord ? $mergedTableRecord->name : 'Bàn ghép',
                                    'table_ids' => $mTablesIds->toArray(),
                                    'coords' => $coords,
                                ];
                                
                                foreach ($mTables as $mt) {
                                    $occupiedCells[$area->id][(int)$mt->location_x . ',' . (int)$mt->location_y] = $mId;
                                }
                            }
                        }
                    }
                }
            }
        }

        return view('admin.tables.index', compact('floors', 'mergedGroups', 'occupiedCells'));
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
            $x = $existingCount % 4; // 4 cột
            $y = intdiv($existingCount, 4);
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
        if (in_array($table->status, ['occupied', 'reserved'])) {
            return response()->json(['success' => false, 'error' => 'Không thể di chuyển bàn đang có khách hoặc đã đặt trước!'], 400);
        }

        $request->validate([
            'location_x' => 'required|numeric|min:0',
            'location_y' => 'required|numeric|min:0',
        ]);

        $newX = $request->location_x;
        $newY = $request->location_y;

        $mergedItem = MergedTableItem::where('table_id', $table->id)->first();
        if ($mergedItem) {
            $allMergeItems = MergedTableItem::where('merged_table_id', $mergedItem->merged_table_id)->get();
            $tableIds = $allMergeItems->pluck('table_id');
            $tables = RestaurantTable::whereIn('id', $tableIds)->get();
            
            // Tìm toạ độ góc trên bên trái (bounding box) của nhóm bàn ghép
            $minX = $tables->min('location_x');
            $minY = $tables->min('location_y');
            
            // Delta di chuyển được tính dựa trên điểm neo (top-left) của cả khối
            $deltaX = $newX - $minX;
            $deltaY = $newY - $minY;
            
            foreach ($tables as $t) {
                $t->update([
                    'location_x' => max(0, $t->location_x + $deltaX),
                    'location_y' => max(0, $t->location_y + $deltaY),
                ]);
            }
        } else {
            $oldX = $table->location_x;
            $oldY = $table->location_y;
            $deltaX = $newX - $oldX;
            $deltaY = $newY - $oldY;
            
            $table->update([
                'location_x' => max(0, $table->location_x + $deltaX),
                'location_y' => max(0, $table->location_y + $deltaY),
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function destroyTable(RestaurantTable $table)
    {
        if (in_array($table->status, ['occupied', 'reserved'])) {
            return back()->with('error', 'Không thể xóa bàn đang có khách hoặc đã đặt trước!');
        }

        // Tách khỏi bàn ghép nếu có
        MergedTableItem::where('table_id', $table->id)->delete();

        $table->delete();
        return back()->with('success', 'Đã xóa bàn!');
    }

    // ─── Merge/Unmerge ─────────────────────────────────────────────

    public function mergeTables(Request $request)
    {
        \Log::info('Merge Tables Request:', $request->all());
        
        $request->validate([
            'primary_table_id'  => 'required|exists:restaurant_tables,id',
            'table_ids'         => 'required|array|min:1',
            'table_ids.*'       => 'exists:restaurant_tables,id',
        ]);

        $primaryTable = RestaurantTable::findOrFail($request->primary_table_id);
        
        // 1. Lấy tất cả table_ids từ request
        $inputTableIds = array_unique(array_merge($request->table_ids, [$request->primary_table_id]));
        
        // 2. Mở rộng danh sách bàn (nếu có bàn đang nằm trong nhóm ghép cũ)
        $expandedTableIds = $inputTableIds;
        $oldMergedTableIds = MergedTableItem::whereIn('table_id', $inputTableIds)
            ->pluck('merged_table_id')
            ->unique()
            ->toArray();
            
        if (!empty($oldMergedTableIds)) {
            $additionalTableIds = MergedTableItem::whereIn('merged_table_id', $oldMergedTableIds)
                ->pluck('table_id')
                ->toArray();
            $expandedTableIds = array_unique(array_merge($expandedTableIds, $additionalTableIds));
        }
        
        $tablesToMerge = RestaurantTable::whereIn('id', $expandedTableIds)->get();

        // 3. Kiểm tra trạng thái các bàn (không được có khách hoặc đặt trước)
        // Tất cả bàn phải là available (bàn trống) hoặc merged (bàn phụ trong nhóm cũ)
        $invalidTables = $tablesToMerge->filter(fn($t) => !in_array($t->status, ['available', 'merged']));
        if ($invalidTables->count() > 0) {
            return response()->json(['success' => false, 'error' => 'Chỉ có thể ghép các bàn đang trống!'], 400);
        }

        // 4. Kiểm tra cùng khu vực
        if ($tablesToMerge->pluck('area_id')->unique()->count() > 1) {
            return response()->json(['success' => false, 'error' => 'Các bàn phải nằm trong cùng một khu vực!'], 400);
        }

        // 5. Kiểm tra tính liên kết (BFS)
        $graph = [];
        $tableDict = [];
        foreach ($tablesToMerge as $t) {
            $x = (int)$t->location_x;
            $y = (int)$t->location_y;
            $graph[$t->id] = [];
            $tableDict[$x . ',' . $y] = $t->id;
        }
        
        foreach ($tablesToMerge as $t) {
            $x = (int)$t->location_x;
            $y = (int)$t->location_y;
            $neighbors = [
                ($x - 1) . ',' . $y,
                ($x + 1) . ',' . $y,
                $x . ',' . ($y - 1),
                $x . ',' . ($y + 1),
            ];
            foreach ($neighbors as $n) {
                if (isset($tableDict[$n])) {
                    $graph[$t->id][] = $tableDict[$n];
                }
            }
        }
        
        $visited = [];
        $queue = [$tablesToMerge->first()->id];
        $visited[$tablesToMerge->first()->id] = true;
        
        while (count($queue) > 0) {
            $curr = array_shift($queue);
            foreach ($graph[$curr] as $neighbor) {
                if (!isset($visited[$neighbor])) {
                    $visited[$neighbor] = true;
                    $queue[] = $neighbor;
                }
            }
        }
        
        if (count($visited) != $tablesToMerge->count()) {
            return response()->json(['success' => false, 'error' => 'Các bàn được chọn phải nằm sát nhau liền kề!'], 400);
        }

        // 6. Xóa các nhóm ghép cũ và phục hồi capacity cho bàn chính cũ
        if (!empty($oldMergedTableIds)) {
            foreach ($oldMergedTableIds as $mId) {
                $oldMergedTable = MergedTable::find($mId);
                if ($oldMergedTable) {
                    $oldPrimary = $oldMergedTable->primaryTable();
                    if ($oldPrimary) {
                        $noteData = json_decode($oldPrimary->note, true);
                        if (is_array($noteData) && isset($noteData['original_capacity'])) {
                            $oldPrimary->capacity = $noteData['original_capacity'];
                            $oldPrimary->minimum_capacity = $noteData['original_minimum_capacity'];
                            $oldPrimary->note = $noteData['original_note'];
                            $oldPrimary->save();
                        }
                    }
                    MergedTableItem::where('merged_table_id', $mId)->delete();
                    $oldMergedTable->delete();
                }
            }
        }

        // Làm mới data tablesToMerge sau khi phục hồi capacity
        $tablesToMerge = RestaurantTable::whereIn('id', $expandedTableIds)->get();
        $primaryTable = $tablesToMerge->firstWhere('id', $request->primary_table_id);

        // 7. Tính tổng sức chứa
        $totalCapacity = $tablesToMerge->sum('capacity');
        $maxCapacity = $tablesToMerge->max('capacity');

        // 8. Tạo bàn ghép mới
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

        foreach ($expandedTableIds as $tid) {
            if ($tid == $primaryTable->id) continue;
            
            MergedTableItem::create([
                'merged_table_id' => $mergedTable->id,
                'table_id'        => $tid,
                'is_primary'      => false,
            ]);

            RestaurantTable::where('id', $tid)->update(['status' => 'merged']);
        }

        // Bàn chính vẫn available để nhận khách, cập nhật sức chứa và yêu cầu tối thiểu
        $primaryTable->update([
            'status' => 'available',
            'capacity' => $totalCapacity,
            'minimum_capacity' => $maxCapacity + 1,
            'note' => json_encode([
                'original_capacity' => $primaryTable->capacity,
                'original_minimum_capacity' => $primaryTable->minimum_capacity,
                'original_note' => $primaryTable->note
            ])
        ]);

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
        
        $primaryTable = $mergedTable->primaryTable();
        if ($primaryTable && in_array($primaryTable->status, ['occupied', 'reserved'])) {
            return response()->json(['success' => false, 'error' => 'Không thể tách bàn ghép khi đang có khách hoặc đã đặt trước!'], 400);
        }
        
        if ($primaryTable) {
            $noteData = json_decode($primaryTable->note, true);
            if (is_array($noteData) && isset($noteData['original_capacity'])) {
                $primaryTable->update([
                    'capacity' => $noteData['original_capacity'],
                    'minimum_capacity' => $noteData['original_minimum_capacity'],
                    'note' => $noteData['original_note'] ?? null
                ]);
            }
        }

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
