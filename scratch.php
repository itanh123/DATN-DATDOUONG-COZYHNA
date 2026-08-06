<?php

$files = [
    'app/Http/Controllers/ProductController.php',
    'app/Http/Controllers/VoucherController.php',
    'app/Http/Controllers/ProfileController.php',
    'app/Http/Controllers/ReviewController.php',
    'app/Http/Controllers/AdminDashboardController.php',
    'app/Http/Controllers/AdminOrderController.php',
    'app/Http/Controllers/UserController.php',
    'app/Http/Controllers/StaffController.php',
    'app/Http/Controllers/CartController.php',
    'app/Http/Controllers/TableOrderController.php',
    'app/Http/Controllers/ShipperController.php'
];

$replacements = [
    'Product created successfully' => 'Tạo sản phẩm thành công.',
    'Product updated successfully' => 'Cập nhật sản phẩm thành công.',
    'Product deleted successfully' => 'Xóa sản phẩm thành công.',
    'Size created successfully' => 'Tạo kích thước thành công.',
    'Size updated successfully' => 'Cập nhật kích thước thành công.',
    'Size deleted successfully' => 'Xóa kích thước thành công.',
    'Product sizes updated successfully' => 'Cập nhật kích thước sản phẩm thành công.',
    'Category created successfully' => 'Tạo danh mục thành công.',
    'Category updated successfully' => 'Cập nhật danh mục thành công.',
    'Category deleted successfully' => 'Xóa danh mục thành công.',
    'Voucher created successfully.' => 'Tạo voucher thành công.',
    'Voucher updated successfully.' => 'Cập nhật voucher thành công.',
    'Voucher deleted successfully.' => 'Xóa voucher thành công.',
    'Unauthorized' => 'Không có quyền truy cập.',
    'You do not have permission to access this page.' => 'Bạn không có quyền truy cập trang này.',
    'You do not have permission.' => 'Bạn không có quyền truy cập.'
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);
    foreach ($replacements as $old => $new) {
        $content = str_replace($old, $new, $content);
    }
    file_put_contents($file, $content);
}
echo "Done\n";
