<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin gets all, others get specifics
        $rolePermissions = [
            'admin' => [
                'view_dashboard', 
                'view_products', 'create_products', 'edit_products', 'delete_products',
                'view_categories', 'create_categories', 'edit_categories', 'delete_categories',
                'view_sizes', 'create_sizes', 'edit_sizes', 'delete_sizes',
                'view_orders', 'update_orders', 'cancel_orders', 'deliver_orders',
                'view_users', 'assign_roles', 'view_roles', 'manage_permissions'
            ],
            'staff' => [
                'view_dashboard', 
                'view_products', 'create_products', 'edit_products',
                'view_categories', 'create_categories', 'edit_categories',
                'view_sizes', 'create_sizes', 'edit_sizes',
                'view_orders', 'update_orders', 'cancel_orders'
            ],
            'shipper' => [
                'view_dashboard', 
                'deliver_orders'
            ],
            'customer' => [],
        ];

        // Clear existing role permissions
        \Illuminate\Support\Facades\DB::table('role_permissions')->truncate();

        foreach ($rolePermissions as $roleCode => $permissions) {
            $role = \Illuminate\Support\Facades\DB::table('roles')->where('code', $roleCode)->first();
            if (!$role) continue;

            foreach ($permissions as $permCode) {
                $permission = \Illuminate\Support\Facades\DB::table('permissions')->where('code', $permCode)->first();
                if (!$permission) continue;

                \Illuminate\Support\Facades\DB::table('role_permissions')->insert([
                    'role_id' => $role->id,
                    'permission_id' => $permission->id,
                ]);
            }
        }
    }
}
