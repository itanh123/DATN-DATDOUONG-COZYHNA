@extends('layouts.admin')

@section('content')
<main class="md:ml-[280px] pt-24 pb-lg min-h-screen bg-background text-on-surface">
    <div class="px-4 md:px-lg max-w-container-max mx-auto space-y-lg">

        {{-- Header --}}
        <header class="flex flex-col md:flex-row md:items-center justify-between gap-md">
            <div>
                <h1 class="font-headline-md text-headline-md font-bold text-on-background">Quản lý Bàn</h1>
                <p class="font-body-md text-on-surface-variant">Sơ đồ bàn theo tầng · Ghép bàn · Quản lý khu vực</p>
            </div>
            <div class="flex gap-sm">
                <button onclick="openModal('addAreaModal')" class="px-lg py-sm bg-surface-container-lowest border border-outline-variant text-on-surface rounded-xl font-semibold flex items-center gap-xs hover:bg-surface-container transition-colors">
                    <span class="material-symbols-outlined text-[18px]">add_location</span>
                    Thêm Khu Vực
                </button>
                <button onclick="openModal('addFloorModal')" class="px-lg py-sm bg-surface-container-lowest border border-outline-variant text-on-surface rounded-xl font-semibold flex items-center gap-xs hover:bg-surface-container transition-colors">
                    <span class="material-symbols-outlined text-[18px]">layers</span>
                    Thêm Tầng
                </button>
                <button onclick="openModal('addTableModal')" class="px-lg py-sm bg-primary text-on-primary rounded-xl font-semibold flex items-center gap-xs hover:opacity-90 shadow-md transition-opacity">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    Thêm Bàn
                </button>
            </div>
        </header>

        @if(session('success'))
            <div class="bg-primary-container text-on-primary-container p-md rounded-xl font-body-md flex items-center gap-sm">
                <span class="material-symbols-outlined">check_circle</span>{{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-error-container text-on-error-container p-md rounded-xl font-body-md flex items-center gap-sm">
                <span class="material-symbols-outlined">error</span>{{ session('error') }}
            </div>
        @endif

        @if($floors->isEmpty())
            {{-- Empty State --}}
            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 p-2xl text-center">
                <span class="material-symbols-outlined text-[64px] text-outline-variant">table_restaurant</span>
                <h3 class="font-title-lg text-on-surface mt-md">Chưa có tầng nào</h3>
                <p class="text-on-surface-variant font-body-md mt-xs">Hãy thêm tầng đầu tiên để bắt đầu thiết lập sơ đồ bàn</p>
                <button onclick="openModal('addFloorModal')" class="mt-lg px-xl py-md bg-primary text-on-primary rounded-xl font-semibold">+ Thêm Tầng Đầu Tiên</button>
            </div>
        @else
            {{-- Floor Panels (Stacked Layout) --}}
            @foreach($floors as $floor)
            <div id="floor-{{ $floor->id }}" class="floor-panel mb-2xl">
                {{-- Floor Header --}}
                <div class="flex items-center justify-between mb-md">
                    <div>
                        <h2 class="font-title-lg text-on-surface font-bold">{{ $floor->name }}</h2>
                        @if($floor->description)
                        <p class="text-on-surface-variant text-sm">{{ $floor->description }}</p>
                        @endif
                    </div>
                    <div class="flex items-center gap-md">
                        {{-- Legend --}}
                        <div class="flex items-center gap-sm text-xs text-on-surface-variant">
                            <span class="inline-flex items-center gap-1"><span class="w-3 h-3 rounded-sm bg-green-500 inline-block"></span> Trống</span>
                            <span class="inline-flex items-center gap-1"><span class="w-3 h-3 rounded-sm bg-red-500 inline-block"></span> Có khách</span>
                            <span class="inline-flex items-center gap-1"><span class="w-3 h-3 rounded-sm bg-amber-400 inline-block"></span> Đặt trước</span>
                            <span class="inline-flex items-center gap-1"><span class="w-3 h-3 rounded-sm bg-slate-400 inline-block"></span> Đã ghép</span>
                        </div>
                        {{-- Delete floor button --}}
                        @if($floor->tables()->count() === 0)
                        <form action="/admin/tables/floors/{{ $floor->id }}" method="POST" class="m-0 inline">
                            @csrf @method('DELETE')
                            <button type="button" onclick="confirmFormSubmit(event, 'Xóa tầng {{ $floor->name }}?')" class="text-on-surface-variant hover:text-error transition-colors p-1">
                                <span class="material-symbols-outlined text-[18px]">delete</span>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>

                @if($floor->areas->isEmpty())
                    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 p-xl text-center">
                        <span class="material-symbols-outlined text-[48px] text-outline-variant">add_location</span>
                        <p class="text-on-surface-variant mt-sm">Tầng này chưa có khu vực nào. Hãy thêm khu vực để bắt đầu thêm bàn.</p>
                        <button onclick="document.getElementById('area_floor_id').value={{ $floor->id }}; openModal('addAreaModal')" class="mt-md px-lg py-sm bg-primary text-on-primary rounded-xl font-semibold text-sm">+ Thêm Khu Vực</button>
                    </div>
                @else
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-xl">
                    @foreach($floor->areas as $area)
                    {{-- Area Section --}}
                    <div>
                        <div class="flex items-center gap-sm mb-md">
                            <div class="h-px flex-1 bg-outline-variant/30"></div>
                            <span class="font-label-md text-on-surface-variant uppercase tracking-wider px-sm">{{ $area->name }}</span>
                            <div class="h-px flex-1 bg-outline-variant/30"></div>
                        </div>

                        {{-- Table Grid --}}
                        <div class="relative bg-surface-container-lowest rounded-2xl border border-outline-variant/30 p-lg overflow-x-auto">
                            <div class="table-grid bg-surface-container-lowest rounded-2xl p-md" style="display: grid; grid-template-columns: repeat(4, 120px); grid-template-rows: repeat(4, 120px); gap: 16px; min-width: 540px;">
                                @for($y = 0; $y < 4; $y++)
                                    @for($x = 0; $x < 4; $x++)
                                        @php
                                            $mId = $occupiedCells[$area->id]["$x,$y"] ?? null;
                                            $tableToRender = $area->tables->first(fn($t) => (int)$t->location_x === $x && (int)$t->location_y === $y);
                                            $isMergedGroup = false;
                                            $groupData = null;
                                            $borderClasses = '';
                                            $hasTop = $hasBottom = $hasLeft = $hasRight = $hasBottomRight = false;
                                            $isPrimaryCell = false;

                                            if ($mId && $tableToRender) {
                                                $groupData = $mergedGroups[$area->id][$mId];
                                                $isMergedGroup = true;
                                                $isPrimaryCell = ($tableToRender->id == $groupData['primaryTable']->id);
                                                
                                                $hasTop = isset($occupiedCells[$area->id][$x . ',' . ($y - 1)]) && $occupiedCells[$area->id][$x . ',' . ($y - 1)] == $mId;
                                                $hasBottom = isset($occupiedCells[$area->id][$x . ',' . ($y + 1)]) && $occupiedCells[$area->id][$x . ',' . ($y + 1)] == $mId;
                                                $hasLeft = isset($occupiedCells[$area->id][($x - 1) . ',' . $y]) && $occupiedCells[$area->id][($x - 1) . ',' . $y] == $mId;
                                                $hasRight = isset($occupiedCells[$area->id][($x + 1) . ',' . $y]) && $occupiedCells[$area->id][($x + 1) . ',' . $y] == $mId;
                                                $hasBottomRight = isset($occupiedCells[$area->id][($x + 1) . ',' . ($y + 1)]) && $occupiedCells[$area->id][($x + 1) . ',' . ($y + 1)] == $mId;
                                                
                                                if ($hasTop) $borderClasses .= ' !border-t-0 !rounded-t-none';
                                                if ($hasBottom) $borderClasses .= ' !border-b-0 !rounded-b-none';
                                                if ($hasLeft) $borderClasses .= ' !border-l-0 !rounded-l-none';
                                                if ($hasRight) $borderClasses .= ' !border-r-0 !rounded-r-none';
                                            }
                                        @endphp
                                        
                                        @if($tableToRender)
                                            @php
                                                $t = $tableToRender;
                                                // If it's a merged group, we use the primary table's status for the UI color
                                                $effectiveStatus = $isMergedGroup ? $groupData['primaryTable']->status : $t->status;
                                                $bgColor = '';
                                                if ($isMergedGroup) {
                                                    $bgColor = match($effectiveStatus) {
                                                        'available' => 'bg-slate-100 border-slate-400',
                                                        'occupied' => 'bg-red-100 border-red-400',
                                                        'reserved' => 'bg-amber-100 border-amber-400',
                                                        default => 'bg-slate-100 border-slate-400'
                                                    };
                                                } else {
                                                    $bgColor = match($t->status) {
                                                        'available' => 'bg-green-50 border-green-300 hover:border-green-500',
                                                        'occupied' => 'bg-red-50 border-red-300',
                                                        'reserved' => 'bg-amber-50 border-amber-300',
                                                        'disabled' => 'bg-gray-100 border-gray-300 opacity-60',
                                                        'merged' => 'bg-slate-100 border-slate-300 opacity-70',
                                                        default => 'bg-gray-50'
                                                    };
                                                }
                                                $displayName = $isMergedGroup ? $groupData['name'] : $t->table_name;
                                                $displayCapacity = $isMergedGroup ? $groupData['capacity'] : $t->capacity;
                                                $canDrag = !in_array($effectiveStatus, ['occupied', 'reserved']);
                                                // The status we pass to openTableDetail should be the effective status so the modal knows if it's occupied
                                            @endphp
                                            <div
                                                id="table-card-{{ $t->id }}"
                                                class="table-card group relative rounded-2xl border-2 p-sm transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5 select-none {{ $bgColor }} flex flex-col items-center justify-center {{ $borderClasses }} {{ $canDrag ? 'cursor-grab active:cursor-grabbing' : '' }}"
                                                @if($canDrag)
                                                    draggable="true"
                                                    ondragstart="handleDragStart(event, {{ $t->id }})"
                                                @endif
                                                ondragover="handleDragOver(event)"
                                                ondragleave="handleDragLeave(event)"
                                                ondrop="handleDropOnTable(event, {{ $t->id }})"
                                                data-table-id="{{ $t->id }}"
                                                data-table-name="{{ $displayName }}"
                                                data-table-status="{{ $effectiveStatus }}"
                                                data-table-capacity="{{ $displayCapacity }}"
                                                data-table-shape="{{ $t->shape }}"
                                                data-table-qr="{{ $t->qr_token }}"
                                                data-area-id="{{ $area->id }}"
                                                data-is-merged="{{ $isMergedGroup ? 'true' : 'false' }}"
                                                onclick="openTableDetail({{ $t->id }})"
                                                style="grid-column: {{ $x + 1 }} / span 1; grid-row: {{ $y + 1 }} / span 1; z-index: 10;"
                                            >
                                                @if ($isMergedGroup)
                                                    @php
                                                        $borderColor = match($effectiveStatus) {
                                                            'available' => 'border-slate-400',
                                                            'occupied' => 'border-red-400',
                                                            'reserved' => 'border-amber-400',
                                                            default => 'border-slate-400'
                                                        };
                                                    @endphp
                                                    @if ($hasRight)
                                                        <div class="absolute top-[-2px] bottom-[-2px] right-[-16px] w-[16px] bg-inherit border-y-2 {{ $borderColor }} z-0" style="pointer-events: none;"></div>
                                                    @endif
                                                    @if ($hasBottom)
                                                        <div class="absolute left-[-2px] right-[-2px] bottom-[-16px] h-[16px] bg-inherit border-x-2 {{ $borderColor }} z-0" style="pointer-events: none;"></div>
                                                    @endif
                                                    @if ($hasRight && $hasBottom && $hasBottomRight)
                                                        <div class="absolute right-[-16px] bottom-[-16px] w-[16px] h-[16px] bg-inherit z-0" style="pointer-events: none;"></div>
                                                    @endif
                                                @endif
                                                
                                                {{-- Shape Icon --}}
                                                <div class="flex justify-center mb-1">
                                                    @if(!$isMergedGroup)
                                                        @if($t->shape === 'round')
                                                            <div class="w-8 h-8 rounded-full border-2
                                                                {{ $t->status === 'available' ? 'border-green-400 bg-green-100' : '' }}
                                                                {{ $t->status === 'occupied' ? 'border-red-400 bg-red-100' : '' }}
                                                                {{ $t->status === 'reserved' ? 'border-amber-400 bg-amber-100' : '' }}
                                                                {{ $t->status === 'disabled' ? 'border-gray-400 bg-gray-100' : '' }}
                                                                flex items-center justify-center">
                                                                <span class="material-symbols-outlined text-[16px]
                                                                    {{ $t->status === 'available' ? 'text-green-600' : '' }}
                                                                    {{ $t->status === 'occupied' ? 'text-red-600' : '' }}
                                                                    {{ $t->status === 'reserved' ? 'text-amber-600' : '' }}
                                                                    {{ $t->status === 'disabled' ? 'text-gray-600' : '' }}
                                                                ">table_restaurant</span>
                                                            </div>
                                                        @else
                                                            <div class="w-8 h-6 rounded border-2
                                                                {{ $t->status === 'available' ? 'border-green-400 bg-green-100' : '' }}
                                                                {{ $t->status === 'occupied' ? 'border-red-400 bg-red-100' : '' }}
                                                                {{ $t->status === 'reserved' ? 'border-amber-400 bg-amber-100' : '' }}
                                                                {{ $t->status === 'disabled' ? 'border-gray-400 bg-gray-100' : '' }}
                                                                flex items-center justify-center
                                                                {{ $t->shape === 'rectangle' ? 'w-12' : '' }}">
                                                                <span class="material-symbols-outlined text-[16px]
                                                                    {{ $t->status === 'available' ? 'text-green-600' : '' }}
                                                                    {{ $t->status === 'occupied' ? 'text-red-600' : '' }}
                                                                    {{ $t->status === 'reserved' ? 'text-amber-600' : '' }}
                                                                    {{ $t->status === 'disabled' ? 'text-gray-600' : '' }}
                                                                ">table_restaurant</span>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>

                                                {{-- Table Info --}}
                                                @if (!$isMergedGroup)
                                                    <p class="font-bold text-xs text-on-surface text-center truncate w-full">{{ $displayName }}</p>
                                                    <p class="text-[10px] text-center
                                                        {{ $t->status === 'available' ? 'text-green-600' : '' }}
                                                        {{ $t->status === 'occupied' ? 'text-red-600' : '' }}
                                                        {{ $t->status === 'reserved' ? 'text-amber-600' : '' }}
                                                        {{ $t->status === 'disabled' ? 'text-gray-500' : '' }}
                                                    ">{{ $t->status_label }}</p>
                                                    <p class="text-[10px] text-on-surface-variant text-center">
                                                        <span class="material-symbols-outlined text-[10px] align-middle">person</span> {{ $displayCapacity }}
                                                    </p>
                                                @endif


                                            </div>
                                        @else
                                            {{-- Empty Dropzone --}}
                                            <div class="empty-cell rounded-2xl border-2 border-dashed border-outline-variant/30 hover:border-primary/50 hover:bg-primary-container/5 flex items-center justify-center cursor-pointer transition-colors group relative"
                                                ondragover="handleDragOver(event)"
                                                ondragleave="handleDragLeave(event)"
                                                ondrop="handleDropOnEmpty(event, {{ $area->id }}, {{ $x }}, {{ $y }})"
                                                onclick="openAddTableModalWithCoords({{ $area->id }}, {{ $x }}, {{ $y }})"
                                                style="grid-column: {{ $x + 1 }}; grid-row: {{ $y + 1 }};">
                                                <span class="material-symbols-outlined text-outline-variant/30 text-[32px] group-hover:scale-110 transition-transform group-hover:text-primary/50">add</span>
                                                
                                                <!-- Overlay for drop target highlight -->
                                                <div class="drop-overlay absolute inset-0 bg-primary-container/20 rounded-2xl opacity-0 pointer-events-none transition-opacity"></div>
                                            </div>
                                        @endif
                                    @endfor
                                @endfor

                                {{-- Centralized Text for Merged Groups --}}
                                @foreach($mergedGroups[$area->id] ?? [] as $mId => $groupData)
                                    @php
                                        $primary = $groupData['primaryTable'];
                                        $pStatus = $primary->status;
                                        
                                        $statusColorClass = match($pStatus) {
                                            'available' => 'text-green-600',
                                            'occupied' => 'text-red-600',
                                            'reserved' => 'text-amber-600',
                                            default => 'text-slate-600'
                                        };
                                        
                                        $statusLabelsMap = [
                                            'available' => 'Trống',
                                            'occupied'  => 'Có khách',
                                            'reserved'  => 'Đặt trước',
                                            'disabled'  => 'Không dùng',
                                            'merged'    => 'Đã ghép',
                                        ];
                                        $pStatusLabel = $statusLabelsMap[$pStatus] ?? 'Đã ghép';
                                        
                                        $xs = []; $ys = [];
                                        foreach($groupData['coords'] as $coord) {
                                            list($x, $y) = explode(',', $coord);
                                            $xs[] = (int)$x; $ys[] = (int)$y;
                                        }
                                        $minX = min($xs); $maxX = max($xs);
                                        $minY = min($ys); $maxY = max($ys);
                                        $spanX = $maxX - $minX + 1;
                                        $spanY = $maxY - $minY + 1;
                                    @endphp
                                    <div class="flex items-center justify-center pointer-events-none" style="grid-column: {{ $minX + 1 }} / span {{ $spanX }}; grid-row: {{ $minY + 1 }} / span {{ $spanY }}; z-index: 20;">
                                        <div class="pointer-events-auto cursor-pointer p-2 rounded-xl transition-transform hover:scale-105" onclick="openTableDetail({{ $primary->id }})">
                                            <div class="flex justify-center mb-1">
                                                <div class="w-16 h-8 rounded border-2 border-slate-400 bg-white/50 backdrop-blur-sm flex items-center justify-center shadow-sm">
                                                    <span class="material-symbols-outlined text-[16px] {{ $statusColorClass }}">table_restaurant</span>
                                                </div>
                                            </div>
                                            <p class="font-bold text-sm text-slate-800 text-center truncate w-full drop-shadow-sm">{{ $groupData['name'] }}</p>
                                            <p class="text-[11px] text-center {{ $statusColorClass }} font-medium">{{ $pStatusLabel }}</p>
                                            <p class="text-[11px] text-slate-700 text-center font-bold mt-0.5">
                                                <span class="material-symbols-outlined text-[12px] align-middle">person</span> {{ $groupData['capacity'] }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach
                    </div>
                @endif
            </div>
            @endforeach

            {{-- Merge Action Bar (appears when checkboxes selected) --}}
            <div id="mergeActionBar" class="fixed bottom-8 left-1/2 -translate-x-1/2 z-50 hidden">
                <div class="bg-surface shadow-2xl border border-outline-variant/30 rounded-2xl px-xl py-md flex items-center gap-lg">
                    <span class="material-symbols-outlined text-primary text-2xl">link</span>
                    <div>
                        <p class="font-bold text-on-surface" id="mergeSelectedCount">0 bàn đã chọn</p>
                        <p class="text-xs text-on-surface-variant">Chọn bàn chính rồi nhấn Ghép Bàn</p>
                    </div>
                    <button onclick="triggerMerge()" class="px-lg py-sm bg-primary text-on-primary rounded-xl font-bold shadow-md hover:opacity-90 transition-opacity">
                        <span class="material-symbols-outlined align-middle mr-xs text-[18px]">link</span>Ghép Bàn
                    </button>
                    <button onclick="clearMergeSelection()" class="px-md py-sm bg-surface-container text-on-surface rounded-xl border border-outline-variant hover:bg-surface-container-high transition-colors">
                        Hủy
                    </button>
                </div>
            </div>
        @endif

    </div>
</main>

{{-- ====== MODALS ====== --}}

{{-- Modal Thêm Tầng --}}
<div id="addFloorModal" class="modal-overlay fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300">
    <div class="modal-content bg-surface rounded-2xl shadow-2xl border border-outline-variant/30 w-[420px] max-w-[90vw] transform translate-y-4 transition-transform duration-300">
        <div class="p-md border-b border-outline-variant/30 flex justify-between items-center">
            <h3 class="font-title-lg font-bold text-on-surface">Thêm Tầng Mới</h3>
            <button onclick="closeModal('addFloorModal')" class="p-1 rounded-full hover:bg-surface-container text-on-surface-variant transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form action="/admin/tables/floors" method="POST" class="p-lg space-y-md">
            @csrf
            <div>
                <label class="block font-label-md text-on-surface-variant mb-xs">Tên Tầng <span class="text-error">*</span></label>
                <input type="text" name="name" placeholder="VD: Tầng 1, Tầng Lửng, Sân Vườn..." required
                    class="w-full bg-surface border border-outline-variant rounded-xl px-md py-sm focus:ring-2 focus:ring-primary outline-none text-on-surface">
            </div>
            <div>
                <label class="block font-label-md text-on-surface-variant mb-xs">Mô tả</label>
                <input type="text" name="description" placeholder="Mô tả ngắn về khu vực tầng này..."
                    class="w-full bg-surface border border-outline-variant rounded-xl px-md py-sm focus:ring-2 focus:ring-primary outline-none text-on-surface">
            </div>
            <div class="flex justify-end gap-sm pt-sm">
                <button type="button" onclick="closeModal('addFloorModal')" class="px-lg py-sm rounded-xl border border-outline-variant text-on-surface-variant hover:bg-surface-container transition-colors">Hủy</button>
                <button type="submit" class="px-lg py-sm bg-primary text-on-primary rounded-xl font-bold shadow-sm">Tạo Tầng</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Thêm Khu Vực --}}
<div id="addAreaModal" class="modal-overlay fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300">
    <div class="modal-content bg-surface rounded-2xl shadow-2xl border border-outline-variant/30 w-[420px] max-w-[90vw] transform translate-y-4 transition-transform duration-300">
        <div class="p-md border-b border-outline-variant/30 flex justify-between items-center">
            <h3 class="font-title-lg font-bold text-on-surface">Thêm Khu Vực</h3>
            <button onclick="closeModal('addAreaModal')" class="p-1 rounded-full hover:bg-surface-container text-on-surface-variant transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form action="/admin/tables/areas" method="POST" class="p-lg space-y-md">
            @csrf
            <div>
                <label class="block font-label-md text-on-surface-variant mb-xs">Thuộc Tầng <span class="text-error">*</span></label>
                <select name="floor_id" id="area_floor_id" required class="w-full bg-surface border border-outline-variant rounded-xl px-md py-sm focus:ring-2 focus:ring-primary outline-none text-on-surface">
                    @foreach($floors as $f)
                    <option value="{{ $f->id }}">{{ $f->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-label-md text-on-surface-variant mb-xs">Tên Khu Vực <span class="text-error">*</span></label>
                <input type="text" name="name" placeholder="VD: Khu A, Khu VIP, Hiên Ngoài..." required
                    class="w-full bg-surface border border-outline-variant rounded-xl px-md py-sm focus:ring-2 focus:ring-primary outline-none text-on-surface">
            </div>
            <div class="flex justify-end gap-sm pt-sm">
                <button type="button" onclick="closeModal('addAreaModal')" class="px-lg py-sm rounded-xl border border-outline-variant text-on-surface-variant hover:bg-surface-container transition-colors">Hủy</button>
                <button type="submit" class="px-lg py-sm bg-primary text-on-primary rounded-xl font-bold shadow-sm">Tạo Khu Vực</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Thêm Bàn --}}
<div id="addTableModal" class="modal-overlay fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300">
    <div class="modal-content bg-surface rounded-2xl shadow-2xl border border-outline-variant/30 w-[480px] max-w-[90vw] transform translate-y-4 transition-transform duration-300">
        <div class="p-md border-b border-outline-variant/30 flex justify-between items-center">
            <h3 class="font-title-lg font-bold text-on-surface">Thêm Bàn Mới</h3>
            <button onclick="closeModal('addTableModal')" class="p-1 rounded-full hover:bg-surface-container text-on-surface-variant transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form action="/admin/tables/tables" method="POST" class="p-lg space-y-md">
            @csrf
            <div>
                <label class="block font-label-md text-on-surface-variant mb-xs">Khu Vực <span class="text-error">*</span></label>
                <select name="area_id" required class="w-full bg-surface border border-outline-variant rounded-xl px-md py-sm focus:ring-2 focus:ring-primary outline-none text-on-surface">
                    @foreach($floors as $f)
                        <optgroup label="{{ $f->name }}">
                            @foreach($f->areas as $a)
                            <option value="{{ $a->id }}">{{ $a->name }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-label-md text-on-surface-variant mb-xs">Tên Bàn <span class="text-error">*</span></label>
                <input type="text" name="table_name" placeholder="VD: Bàn 01, Bàn VIP 1..." required
                    class="w-full bg-surface border border-outline-variant rounded-xl px-md py-sm focus:ring-2 focus:ring-primary outline-none text-on-surface">
            </div>
            <div class="grid grid-cols-2 gap-md">
                <div>
                    <label class="block font-label-md text-on-surface-variant mb-xs">Sức Chứa Tối Đa <span class="text-error">*</span></label>
                    <input type="number" name="capacity" value="4" min="1" max="50" required
                        class="w-full bg-surface border border-outline-variant rounded-xl px-md py-sm focus:ring-2 focus:ring-primary outline-none text-on-surface">
                </div>
                <div>
                    <label class="block font-label-md text-on-surface-variant mb-xs">Tối Thiểu</label>
                    <input type="number" name="minimum_capacity" value="1" min="1"
                        class="w-full bg-surface border border-outline-variant rounded-xl px-md py-sm focus:ring-2 focus:ring-primary outline-none text-on-surface">
                </div>
            </div>
            <div>
                <label class="block font-label-md text-on-surface-variant mb-xs">Hình Dạng Bàn</label>
                <div class="flex gap-md">
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="shape" value="square" class="sr-only peer" checked>
                        <div class="border-2 border-outline-variant peer-checked:border-primary peer-checked:bg-primary-container rounded-xl p-md text-center transition-all">
                            <div class="w-8 h-8 border-2 border-current rounded mx-auto mb-xs"></div>
                            <span class="text-sm font-medium">Vuông</span>
                        </div>
                    </label>
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="shape" value="round" class="sr-only peer">
                        <div class="border-2 border-outline-variant peer-checked:border-primary peer-checked:bg-primary-container rounded-xl p-md text-center transition-all">
                            <div class="w-8 h-8 border-2 border-current rounded-full mx-auto mb-xs"></div>
                            <span class="text-sm font-medium">Tròn</span>
                        </div>
                    </label>
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="shape" value="rectangle" class="sr-only peer">
                        <div class="border-2 border-outline-variant peer-checked:border-primary peer-checked:bg-primary-container rounded-xl p-md text-center transition-all">
                            <div class="w-12 h-6 border-2 border-current rounded mx-auto mb-xs"></div>
                            <span class="text-sm font-medium">Chữ Nhật</span>
                        </div>
                    </label>
                </div>
            </div>
            <div class="flex justify-end gap-sm pt-sm">
                <button type="button" onclick="closeModal('addTableModal')" class="px-lg py-sm rounded-xl border border-outline-variant text-on-surface-variant hover:bg-surface-container transition-colors">Hủy</button>
                <button type="submit" class="px-lg py-sm bg-primary text-on-primary rounded-xl font-bold shadow-sm">Thêm Bàn</button>
            </div>
        </form>
    </div>
</div>

{{-- Toast Container --}}
<div id="toast-container" class="fixed top-4 right-4 z-[9999] space-y-2 pointer-events-none"></div>

{{-- Modal Xác Nhận (Custom Confirm) --}}
<div id="customConfirmModal" class="modal-overlay fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300">
    <div class="modal-content bg-surface rounded-2xl shadow-2xl border border-outline-variant/30 w-[400px] max-w-[90vw] transform translate-y-4 transition-transform duration-300">
        <div class="p-lg text-center">
            <div class="w-16 h-16 rounded-full bg-warning-container text-on-warning-container flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-[32px]">help</span>
            </div>
            <h3 class="font-title-lg font-bold text-on-surface mb-2" id="confirmTitle">Xác nhận</h3>
            <p class="text-on-surface-variant mb-6" id="confirmMessage">Bạn có chắc chắn muốn thực hiện hành động này?</p>
            <div class="flex justify-center gap-3">
                <button onclick="closeConfirmModal(false)" class="px-6 py-2 rounded-xl border border-outline-variant text-on-surface-variant hover:bg-surface-container transition-colors font-medium">Hủy</button>
                <button onclick="closeConfirmModal(true)" class="px-6 py-2 bg-primary text-on-primary rounded-xl font-bold shadow-sm hover:opacity-90 transition-opacity">Đồng ý</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Chi Tiết Bàn --}}
<div id="tableDetailModal" class="modal-overlay fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300">
    <div class="modal-content bg-surface rounded-2xl shadow-2xl border border-outline-variant/30 w-[440px] max-w-[90vw] transform translate-y-4 transition-transform duration-300">
        <div class="p-md border-b border-outline-variant/30 flex justify-between items-center">
            <h3 class="font-title-lg font-bold text-on-surface" id="detailTableName">Chi Tiết Bàn</h3>
            <button onclick="closeModal('tableDetailModal')" class="p-1 rounded-full hover:bg-surface-container text-on-surface-variant transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-lg space-y-md" id="tableDetailContent">
            {{-- Filled by JS --}}
        </div>
    </div>
</div>

{{-- Modal Xác Nhận Ghép Bàn --}}
<div id="mergeConfirmModal" class="modal-overlay fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300">
    <div class="modal-content bg-surface rounded-2xl shadow-2xl border border-outline-variant/30 w-[480px] max-w-[90vw] transform translate-y-4 transition-transform duration-300">
        <div class="p-md border-b border-outline-variant/30 flex justify-between items-center">
            <h3 class="font-title-lg font-bold text-on-surface">Xác Nhận Ghép Bàn</h3>
            <button onclick="closeModal('mergeConfirmModal')" class="p-1 rounded-full hover:bg-surface-container text-on-surface-variant transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-lg space-y-md">
            <p class="text-on-surface-variant">Chọn bàn <strong>chính</strong> (bàn nhận QR code):</p>
            <div id="mergePrimarySelector" class="space-y-sm"></div>
            <div class="bg-surface-container-low rounded-xl p-md text-sm text-on-surface-variant">
                <p class="font-medium text-on-surface mb-xs">Bàn sẽ được ghép:</p>
                <p id="mergeTablesList" class="text-on-surface-variant"></p>
            </div>
            <div class="flex justify-end gap-sm pt-sm">
                <button onclick="closeModal('mergeConfirmModal')" class="px-lg py-sm rounded-xl border border-outline-variant text-on-surface-variant hover:bg-surface-container transition-colors">Hủy</button>
                <button onclick="confirmMerge()" class="px-lg py-sm bg-primary text-on-primary rounded-xl font-bold shadow-sm">
                    <span class="material-symbols-outlined align-middle mr-xs text-[18px]">link</span>Xác Nhận Ghép
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const CSRF_TOKEN = '{{ csrf_token() }}';

// ─── Custom UI Helpers ───────────────────────────────────────────
function showToast(message, type = 'error') {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    const colors = {
        success: 'bg-green-100 text-green-800 border-green-200',
        error: 'bg-red-100 text-red-800 border-red-200',
        warning: 'bg-amber-100 text-amber-800 border-amber-200',
        info: 'bg-blue-100 text-blue-800 border-blue-200'
    };
    const icons = {
        success: 'check_circle',
        error: 'error',
        warning: 'warning',
        info: 'info'
    };
    
    toast.className = `flex items-center gap-2 px-4 py-3 rounded-xl shadow-lg border ${colors[type]} transform transition-all duration-300 translate-x-full opacity-0 bg-surface`;
    // Thêm bg-surface để đè lên nền trong suốt nếu có class màu, hoặc thay bg-x-100 bằng màu nền đặc
    // Đã dùng bg-x-100 nên nền đặc rồi
    toast.innerHTML = `
        <span class="material-symbols-outlined">${icons[type]}</span>
        <span class="font-medium">${message}</span>
    `;
    
    container.appendChild(toast);
    
    // Animate in
    setTimeout(() => {
        toast.classList.remove('translate-x-full', 'opacity-0');
    }, 10);
    
    // Animate out and remove
    setTimeout(() => {
        toast.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

let confirmCallback = null;

function showConfirm(message, callback, title = 'Xác nhận') {
    document.getElementById('confirmMessage').textContent = message;
    document.getElementById('confirmTitle').textContent = title;
    confirmCallback = callback;
    openModal('customConfirmModal');
}

function closeConfirmModal(result) {
    closeModal('customConfirmModal');
    if (confirmCallback) {
        confirmCallback(result);
        confirmCallback = null;
    }
}

function confirmFormSubmit(e, message) {
    e.preventDefault();
    const form = e.currentTarget.closest('form');
    showConfirm(message, (confirmed) => {
        if (confirmed) form.submit();
    });
}

// ─── Modal Controls ──────────────────────────────────────────────
function openModal(id) {
    const el = document.getElementById(id);
    el.classList.remove('opacity-0', 'pointer-events-none');
    el.querySelector('.modal-content').classList.remove('translate-y-4');
}

function closeModal(id) {
    const el = document.getElementById(id);
    el.classList.add('opacity-0', 'pointer-events-none');
    el.querySelector('.modal-content').classList.add('translate-y-4');
}

// Close on overlay click
document.querySelectorAll('.modal-overlay').forEach(el => {
    el.addEventListener('click', function(e) {
        if (e.target === this) closeModal(this.id);
    });
});



// ─── Table Detail ─────────────────────────────────────────────────
function openTableDetail(tableId) {
    const card = document.getElementById('table-card-' + tableId);
    const data = card.dataset;

    document.getElementById('detailTableName').textContent = data.tableName;

    const statusColors = {
        available: 'text-green-600 bg-green-100',
        occupied:  'text-red-600 bg-red-100',
        reserved:  'text-amber-600 bg-amber-100',
        disabled:  'text-gray-600 bg-gray-100',
        merged:    'text-slate-600 bg-slate-100',
    };
    const statusLabels = {
        available: 'Trống',
        occupied:  'Có khách',
        reserved:  'Đặt trước',
        disabled:  'Không dùng',
        merged:    'Đã ghép',
    };

    const status = data.tableStatus;
    const isMerged = card.dataset.isMerged === 'true';

    document.getElementById('tableDetailContent').innerHTML = `
        <div class="flex items-start justify-between gap-lg mb-md">
            <div class="flex items-center gap-lg">
                <div class="w-16 h-16 rounded-2xl bg-surface-container-low flex items-center justify-center">
                    <span class="material-symbols-outlined text-[36px] text-on-surface-variant">table_restaurant</span>
                </div>
                <div>
                    <p class="font-headline-sm font-bold text-on-surface">${data.tableName}</p>
                    <span class="inline-block mt-xs px-md py-1 rounded-full text-xs font-bold ${statusColors[status]}">${statusLabels[status]}</span>
                </div>
            </div>
            ${data.tableQr ? `
                <div class="flex flex-col items-center">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=${encodeURIComponent(window.location.origin + '/table/login/' + data.tableQr)}" alt="QR Code" class="w-20 h-20 rounded shadow-sm border border-outline-variant/30">
                    <span class="text-[10px] text-on-surface-variant mt-1">Mã QR Khách quét</span>
                </div>
            ` : ''}
        </div>

        <div class="grid grid-cols-2 gap-sm text-sm">
            <div class="bg-surface-container-low rounded-xl p-sm text-center">
                <p class="text-on-surface-variant text-xs">Sức chứa</p>
                <p class="font-bold text-on-surface text-lg">${data.tableCapacity}</p>
                <p class="text-on-surface-variant text-xs">người</p>
            </div>
            <div class="bg-surface-container-low rounded-xl p-sm text-center">
                <p class="text-on-surface-variant text-xs">Hình dạng</p>
                <p class="font-bold text-on-surface">${{'square':'Vuông','round':'Tròn','rectangle':'Chữ Nhật'}[data.tableShape]}</p>
            </div>
        </div>

        <div class="space-y-sm">
            <p class="font-label-md text-on-surface-variant uppercase tracking-wider text-xs">Thay đổi trạng thái</p>
            <div class="grid grid-cols-2 gap-xs">
                ${['available','occupied','reserved','disabled'].map(s => `
                    <button onclick="changeTableStatus(${tableId}, '${s}')" class="py-sm rounded-xl text-sm font-medium border-2 transition-all ${status === s ? statusColors[s] + ' border-current' : 'border-outline-variant text-on-surface-variant hover:border-primary'}">
                        ${statusLabels[s]}
                    </button>
                `).join('')}
            </div>
        </div>

        ${(status === 'occupied' || status === 'reserved') ? '' : 
            (isMerged ? `
            <div class="flex gap-sm pt-sm border-t border-outline-variant/20">
                <button onclick="unmergeTable(${tableId})"
                    class="flex-1 py-sm bg-surface-container rounded-xl text-sm font-medium border border-outline-variant hover:bg-surface-container-high transition-colors flex items-center justify-center gap-xs text-amber-600">
                    <span class="material-symbols-outlined text-[16px]">link_off</span> Tách Bàn Ghép
                </button>
            </div>` : `
            <div class="flex gap-sm pt-sm border-t border-outline-variant/20">
                <button onclick="closeModal('tableDetailModal'); setTimeout(() => initMergeFromDetail(${tableId}), 100)"
                    class="flex-1 py-sm bg-surface-container rounded-xl text-sm font-medium border border-outline-variant hover:bg-surface-container-high transition-colors flex items-center justify-center gap-xs">
                    <span class="material-symbols-outlined text-[16px]">link</span> Ghép Bàn
                </button>
                <form action="/admin/tables/tables/${tableId}" method="POST" class="flex-1 m-0">
                    <input type="hidden" name="_token" value="${CSRF_TOKEN}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="button" onclick="confirmFormSubmit(event, 'Xóa bàn ${data.tableName}?')"
                        class="w-full py-sm bg-error-container text-on-error-container rounded-xl text-sm font-medium hover:opacity-90 transition-opacity flex items-center justify-center gap-xs">
                        <span class="material-symbols-outlined text-[16px]">delete</span> Xóa Bàn
                    </button>
                </form>
            </div>`)
        }
    `;

    openModal('tableDetailModal');
}

// ─── Change Status ────────────────────────────────────────────────
function changeTableStatus(tableId, status) {
    fetch(`/admin/tables/tables/${tableId}/status`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
        body: JSON.stringify({ status })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            closeModal('tableDetailModal');
            location.reload();
        }
    });
}

// ─── Merge Logic ──────────────────────────────────────────────────
let selectedTableIds = [];

document.addEventListener('change', function(e) {
    if (e.target.classList.contains('merge-checkbox')) {
        const id = parseInt(e.target.value);
        if (e.target.checked) {
            selectedTableIds.push(id);
        } else {
            selectedTableIds = selectedTableIds.filter(x => x !== id);
        }
        updateMergeActionBar();
    }
});

function updateMergeActionBar() {
    const bar = document.getElementById('mergeActionBar');
    const count = document.getElementById('mergeSelectedCount');
    if (selectedTableIds.length >= 2) {
        bar.classList.remove('hidden');
        count.textContent = `${selectedTableIds.length} bàn đã chọn`;
    } else {
        bar.classList.add('hidden');
    }
}

function clearMergeSelection() {
    selectedTableIds = [];
    document.querySelectorAll('.merge-checkbox:checked').forEach(cb => cb.checked = false);
    document.getElementById('mergeActionBar').classList.add('hidden');
}

function triggerMerge() {
    if (selectedTableIds.length < 2) {
        showToast('Cần chọn ít nhất 2 bàn để ghép!', 'warning');
        return;
    }

    const names = selectedTableIds.map(id => {
        const card = document.querySelector(`[data-table-id="${id}"]`);
        return card ? card.dataset.tableName : 'Bàn ' + id;
    });

    // Populate primary selector
    const selector = document.getElementById('mergePrimarySelector');
    selector.innerHTML = selectedTableIds.map((id, i) => `
        <label class="flex items-center gap-sm p-sm border rounded-xl cursor-pointer hover:bg-surface-container transition-colors ${i === 0 ? 'border-primary bg-primary-container/20' : 'border-outline-variant'}">
            <input type="radio" name="primary_table" value="${id}" ${i === 0 ? 'checked' : ''} class="accent-primary">
            <span class="font-medium text-on-surface">${names[i]}</span>
        </label>
    `).join('');

    document.getElementById('mergeTablesList').textContent = names.join(', ');

    openModal('mergeConfirmModal');
}

function initMergeFromDetail(tableId) {
    const card = document.querySelector(`[data-table-id="${tableId}"]`);
    if (card) {
        const cb = card.querySelector('.merge-checkbox');
        if (cb) {
            cb.checked = true;
            selectedTableIds.push(tableId);
            updateMergeActionBar();
        }
    } else {
        showToast('Hãy tích chọn thêm các bàn khác muốn ghép, rồi nhấn nút "Ghép Bàn" ở thanh phía dưới.', 'info');
    }
}

function confirmMerge() {
    const primaryId = parseInt(document.querySelector('input[name="primary_table"]:checked').value);

    fetch('/admin/tables/merge', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
        body: JSON.stringify({ primary_table_id: primaryId, table_ids: selectedTableIds })
    })
    .then(async r => {
        if (!r.ok) {
            const err = await r.json().catch(() => ({ error: 'Lỗi máy chủ ' + r.status }));
            throw new Error(err.error || err.message || 'Lỗi ' + r.status);
        }
        return r.json();
    })
    .then(data => {
        if (data.success) {
            closeModal('mergeConfirmModal');
            clearMergeSelection();
            location.reload();
        } else {
            showToast(data.error || 'Có lỗi xảy ra!', 'error');
        }
    })
    .catch(err => {
        showToast('Lỗi: ' + err.message, 'error');
    });
}

function unmergeTable(tableId) {
    showConfirm('Xác nhận tách bàn ghép? Tất cả các bàn trong nhóm sẽ trở về trạng thái Trống.', (confirmed) => {
        if (!confirmed) return;

        fetch('/admin/tables/unmerge', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
            body: JSON.stringify({ table_id: tableId })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                closeModal('tableDetailModal');
                location.reload();
            } else {
                showToast(data.error || 'Có lỗi xảy ra!', 'error');
            }
        });
    });
}
// ─── Drag & Drop Logic ─────────────────────────────────────────────
let draggedTableId = null;

function handleDragStart(e, tableId) {
    draggedTableId = tableId;
    e.dataTransfer.effectAllowed = 'move';
    e.dataTransfer.setData('text/plain', tableId);
    e.target.classList.add('opacity-50');
}

function handleDragOver(e) {
    e.preventDefault();
    e.dataTransfer.dropEffect = 'move';
    
    // Highlight dropzone
    const dropzone = e.currentTarget;
    if (dropzone.classList.contains('empty-cell')) {
        dropzone.querySelector('.drop-overlay').classList.remove('opacity-0');
    }
}

function handleDragLeave(e) {
    const dropzone = e.currentTarget;
    if (dropzone.classList.contains('empty-cell')) {
        dropzone.querySelector('.drop-overlay').classList.add('opacity-0');
    }
}

function handleDropOnEmpty(e, areaId, x, y) {
    e.preventDefault();
    e.stopPropagation();
    
    const dropzone = e.currentTarget;
    if (dropzone.classList.contains('empty-cell')) {
        dropzone.querySelector('.drop-overlay').classList.add('opacity-0');
    }

    if (!draggedTableId) return;

    fetch(`/admin/tables/tables/${draggedTableId}/position`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
        body: JSON.stringify({ location_x: x, location_y: y })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) location.reload();
    });
}

function handleDropOnTable(e, targetTableId) {
    e.preventDefault();
    e.stopPropagation();
    
    document.querySelectorAll('.table-card').forEach(el => el.classList.remove('opacity-50'));

    if (!draggedTableId || draggedTableId === targetTableId) return;

    // Show merge confirmation modal directly
    const draggedCard = document.querySelector(`[data-table-id="${draggedTableId}"]`);
    const targetCard = document.querySelector(`[data-table-id="${targetTableId}"]`);
    
    // Capture variables locally before dragend clears them
    const currentDraggedId = draggedTableId;
    const currentTargetId = targetTableId;

    if (draggedCard.dataset.tableStatus !== 'available' || targetCard.dataset.tableStatus !== 'available') {
        showToast('Chỉ có thể ghép các bàn đang trống!', 'warning');
        return;
    }

    showConfirm(`Bạn muốn ghép ${draggedCard.dataset.tableName} vào ${targetCard.dataset.tableName}?`, (confirmed) => {
        if (confirmed) {
            fetch('/admin/tables/merge', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                body: JSON.stringify({ 
                    primary_table_id: currentTargetId, 
                    table_ids: [currentDraggedId, currentTargetId] 
                })
            })
            .then(async r => {
                if (!r.ok) {
                    const err = await r.json().catch(() => ({ error: 'Lỗi máy chủ ' + r.status }));
                    throw new Error(err.error || err.message || 'Lỗi ' + r.status);
                }
                return r.json();
            })
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    showToast(data.error || 'Có lỗi xảy ra!', 'error');
                }
            })
            .catch(err => {
                showToast('Lỗi: ' + err.message, 'error');
            });
        }
    });
}

document.addEventListener('dragend', function(e) {
    if (e.target.classList && e.target.classList.contains('table-card')) {
        e.target.classList.remove('opacity-50');
    }
    draggedTableId = null;
    document.querySelectorAll('.drop-overlay').forEach(el => el.classList.add('opacity-0'));
});

function openAddTableModalWithCoords(areaId, x, y) {
    const modal = document.getElementById('addTableModal');
    
    // Select correct area
    const select = modal.querySelector('select[name="area_id"]');
    select.value = areaId;

    // We need to inject hidden inputs for X and Y, or calculate them dynamically in Controller.
    // Actually, currently Controller calculates X, Y automatically:
    // $x = $existingCount % 6; $y = intdiv($existingCount, 6);
    // Let's pass X and Y to the form to override this.
    
    let inputX = modal.querySelector('input[name="location_x"]');
    if (!inputX) {
        inputX = document.createElement('input');
        inputX.type = 'hidden';
        inputX.name = 'location_x';
        modal.querySelector('form').appendChild(inputX);
    }
    inputX.value = x;

    let inputY = modal.querySelector('input[name="location_y"]');
    if (!inputY) {
        inputY = document.createElement('input');
        inputY.type = 'hidden';
        inputY.name = 'location_y';
        modal.querySelector('form').appendChild(inputY);
    }
    inputY.value = y;

    openModal('addTableModal');
}
</script>
@endpush
@endsection
