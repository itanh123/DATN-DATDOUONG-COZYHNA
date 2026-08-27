<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Hệ thống
            ['code' => 'view_dashboard', 'name' => 'Xem Bảng điều khiển', 'group' => 'Hệ thống'],
            ['code' => 'manage_tables', 'name' => 'Quản lý Bàn', 'group' => 'Hệ thống'],
            ['code' => 'manage_vouchers', 'name' => 'Quản lý Voucher', 'group' => 'Hệ thống'],
            ['code' => 'view_reports', 'name' => 'Xem Báo cáo', 'group' => 'Hệ thống'],
            ['code' => 'manage_backup', 'name' => 'Sao lưu Hệ thống', 'group' => 'Hệ thống'],
            ['code' => 'manage_delivery_settings', 'name' => 'Cấu hình Giao hàng', 'group' => 'Hệ thống'],
            // Sản phẩm
            ['code' => 'view_products', 'name' => 'Xem Sản phẩm', 'group' => 'Sản phẩm'],
            ['code' => 'view_ingredients', 'name' => 'Xem Nguyên liệu', 'group' => 'Sản phẩm'],
            ['code' => 'view_reviews', 'name' => 'Xem Đánh giá', 'group' => 'Sản phẩm'],
            ['code' => 'create_products', 'name' => 'Thêm Sản phẩm', 'group' => 'Sản phẩm'],
            ['code' => 'edit_products', 'name' => 'Sửa Sản phẩm', 'group' => 'Sản phẩm'],
            ['code' => 'delete_products', 'name' => 'Xóa Sản phẩm', 'group' => 'Sản phẩm'],
            // Danh mục
            ['code' => 'view_categories', 'name' => 'Xem Danh mục', 'group' => 'Danh mục'],
            ['code' => 'create_categories', 'name' => 'Thêm Danh mục', 'group' => 'Danh mục'],
            ['code' => 'edit_categories', 'name' => 'Sửa Danh mục', 'group' => 'Danh mục'],
            ['code' => 'delete_categories', 'name' => 'Xóa Danh mục', 'group' => 'Danh mục'],
            // Kích thước
            ['code' => 'view_sizes', 'name' => 'Xem Kích thước', 'group' => 'Kích thước'],
            ['code' => 'create_sizes', 'name' => 'Thêm Kích thước', 'group' => 'Kích thước'],
            ['code' => 'edit_sizes', 'name' => 'Sửa Kích thước', 'group' => 'Kích thước'],
            ['code' => 'delete_sizes', 'name' => 'Xóa Kích thước', 'group' => 'Kích thước'],
            // Đơn hàng
            ['code' => 'view_orders', 'name' => 'Xem Đơn hàng', 'group' => 'Đơn hàng'],
            ['code' => 'update_orders', 'name' => 'Cập nhật Đơn hàng', 'group' => 'Đơn hàng'],
            ['code' => 'cancel_orders', 'name' => 'Hủy Đơn hàng', 'group' => 'Đơn hàng'],
            ['code' => 'deliver_orders', 'name' => 'Giao Đơn hàng', 'group' => 'Đơn hàng'],
            // Người dùng & Chức vụ
            ['code' => 'view_users', 'name' => 'Xem Người dùng', 'group' => 'Nhân sự & Chức vụ'],
            ['code' => 'assign_roles', 'name' => 'Gán chức vụ (Role)', 'group' => 'Nhân sự & Chức vụ'],
            ['code' => 'view_roles', 'name' => 'Xem Chức vụ', 'group' => 'Nhân sự & Chức vụ'],
            ['code' => 'manage_permissions', 'name' => 'Sửa Chức vụ', 'group' => 'Nhân sự & Chức vụ'],
        ];

        // Ensure group column exists if not we can just store it in description for now to avoid migration
        foreach ($permissions as $permission) {
            \Illuminate\Support\Facades\DB::table('permissions')->updateOrInsert(
                ['code' => $permission['code']],
                [
                    'name' => $permission['name'],
                    'description' => $permission['group'], // Use description as group
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
        
        // Remove old deprecated permissions
        $codes = array_column($permissions, 'code');
        \Illuminate\Support\Facades\DB::table('permissions')->whereNotIn('code', $codes)->delete();
    }
}
