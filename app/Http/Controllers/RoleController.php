<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with(['users' => function($q) {
            $q->orderBy('id', 'desc');
        }])->whereNotIn('code', ['customer', 'admin'])->get();

        // Lấy danh sách permissions và gom nhóm, loại trừ các quyền quản lý chức vụ vì chỉ Admin mới được cấp quyền này
        $permissions = DB::table('permissions')
            ->whereNotIn('code', ['view_roles', 'manage_permissions', 'assign_roles'])
            ->orderBy('description')->orderBy('id')->get();
        
        $groupedPermissions = [];
        foreach ($permissions as $p) {
            $group = $p->description ?: 'Khác';
            $groupedPermissions[$group][] = $p;
        }

        $rolePermissions = DB::table('role_permissions')->get();

        // Structure: $matrix[role_id][permission_id] = true
        $matrix = [];
        $rolePermissionCount = [];
        
        foreach ($rolePermissions as $rp) {
            $matrix[$rp->role_id][$rp->permission_id] = true;
            
            if (!isset($rolePermissionCount[$rp->role_id])) {
                $rolePermissionCount[$rp->role_id] = 0;
            }
            $rolePermissionCount[$rp->role_id]++;
        }

        return view('admin.roles', compact('roles', 'groupedPermissions', 'matrix', 'rolePermissionCount', 'permissions'));
    }

    public function updatePermissions(Request $request)
    {
        $roleId = $request->input('role_id');
        $permissionIds = $request->input('permissions', []);

        if (!$roleId) {
            return back()->with('error', 'Chưa chọn chức vụ để phân quyền.');
        }

        DB::beginTransaction();
        try {
            // Delete old permissions only for this specific role
            DB::table('role_permissions')->where('role_id', $roleId)->delete();

            $insertData = [];
            foreach ($permissionIds as $permissionId) {
                $insertData[] = [
                    'role_id' => $roleId,
                    'permission_id' => $permissionId,
                ];
            }

            if (!empty($insertData)) {
                DB::table('role_permissions')->insert($insertData);
            }

            DB::commit();
            return back()->with('success', 'Đã lưu thay đổi phân quyền thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi khi cập nhật phân quyền: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:50|unique:roles,code',
            'description' => 'nullable|string'
        ], [
            'name.required' => 'Vui lòng nhập tên chức vụ.',
            'code.required' => 'Vui lòng nhập mã chức vụ.',
            'code.unique' => 'Mã chức vụ này đã tồn tại.'
        ]);

        try {
            Role::create([
                'name' => $request->name,
                'code' => strtolower($request->code),
                'description' => $request->description,
                'status' => 1
            ]);
            
            return back()->with('success', 'Đã thêm chức vụ mới thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi khi thêm chức vụ: ' . $e->getMessage());
        }
    }
}
