<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index()
    {
        $roles = DB::table('roles')->get();
        // Lấy danh sách permissions và gom nhóm theo cột 'description' (chứa tên nhóm)
        $permissions = DB::table('permissions')->orderBy('description')->orderBy('id')->get();
        
        $groupedPermissions = [];
        foreach ($permissions as $p) {
            $group = $p->description ?: 'Khác';
            $groupedPermissions[$group][] = $p;
        }

        $rolePermissions = DB::table('role_permissions')->get();

        // Create a lookup map for easy checking in Blade
        // Structure: $matrix[role_id][permission_id] = true
        $matrix = [];
        foreach ($rolePermissions as $rp) {
            $matrix[$rp->role_id][$rp->permission_id] = true;
        }

        return view('admin.roles', compact('roles', 'groupedPermissions', 'matrix'));
    }

    public function updatePermissions(Request $request)
    {
        // Expected input format:
        // role_permissions[role_id][] = permission_id
        $inputPermissions = $request->input('role_permissions', []);

        DB::beginTransaction();
        try {
            // Remove all existing role_permissions to recreate them
            DB::table('role_permissions')->delete();

            $insertData = [];
            foreach ($inputPermissions as $roleId => $permissionIds) {
                foreach ($permissionIds as $permissionId) {
                    $insertData[] = [
                        'role_id' => $roleId,
                        'permission_id' => $permissionId,
                    ];
                }
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
}
