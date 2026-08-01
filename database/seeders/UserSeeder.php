<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $roles = DB::table('roles')->pluck('id', 'code');

        $users = [
            [
                'code' => 'admin',
                'email' => 'admin@gmail.com',
                'role_id' => $roles['admin'] ?? null,
                'username' => 'admin',
                'name' => 'Quản Trị Viên (Admin)',
                'password' => Hash::make('admin123'),
                'phone' => '0987654321',
                'status' => true,
            ],
            [
                'code' => 'staff',
                'email' => 'staff@gmail.com',
                'role_id' => $roles['staff'] ?? null,
                'username' => 'staff01',
                'name' => 'Nhân Viên Thu Ngân',
                'password' => Hash::make('123456'),
                'phone' => '0912345678',
                'status' => true,
            ],
            [
                'code' => 'shipper',
                'email' => 'shipper@gmail.com',
                'role_id' => $roles['shipper'] ?? null,
                'username' => 'shipper01',
                'name' => 'Nhân Viên Giao Hàng',
                'password' => Hash::make('123456'),
                'phone' => '0933445566',
                'status' => true,
            ],
            [
                'code' => 'customer',
                'email' => 'khachhang@gmail.com',
                'role_id' => $roles['customer'] ?? null,
                'username' => 'khachhang01',
                'name' => 'Nguyễn Văn Khách',
                'password' => Hash::make('123456'),
                'phone' => '0977889900',
                'status' => true,
            ],
        ];

        foreach ($users as $userData) {
            $code = $userData['code'];
            unset($userData['code']);

            DB::table('users')->updateOrInsert(
                ['email' => $userData['email']],
                array_merge($userData, [
                    'avatar' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );

            $userId = DB::table('users')->where('email', $userData['email'])->value('id');

            // Seed profiles based on role
            if ($code === 'customer' && $userId) {
                DB::table('customer_profiles')->updateOrInsert(
                    ['user_id' => $userId],
                    [
                        'full_name' => $userData['name'],
                        'gender' => 'Male',
                        'birthday' => '1998-05-15',
                        'total_orders' => 5,
                        'total_spent' => 250000,
                        'status' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );

                $customerProfileId = DB::table('customer_profiles')->where('user_id', $userId)->value('id');
                if ($customerProfileId) {
                    DB::table('customer_addresses')->updateOrInsert(
                        ['customer_id' => $customerProfileId],
                        [
                            'receiver_name' => 'Nguyễn Văn Khách',
                            'receiver_phone' => '0977889900',
                            'province' => 'Hà Nội',
                            'district' => 'Cầu Giấy',
                            'ward' => 'Dịch Vọng',
                            'address' => 'Số 123 Đường Cầu Giấy',
                            'is_default' => true,
                            'note' => 'Giao hàng giờ hành chính',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            }

            if ($code === 'shipper' && $userId) {
                DB::table('shipper_profiles')->updateOrInsert(
                    ['user_id' => $userId],
                    [
                        'full_name' => $userData['name'],
                        'phone' => $userData['phone'],
                        'license_plate' => '29A1-12345',
                        'vehicle_type' => 'Honda Wave Alpha',
                        'status' => 'Available',
                        'rating' => 4.9,
                        'total_deliveries' => 42,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }

            if ($code === 'staff' && $userId) {
                if (Schema::hasTable('employee_profiles')) {
                    DB::table('employee_profiles')->updateOrInsert(
                        ['user_id' => $userId],
                        [
                            'employee_code' => 'NV-001',
                            'address' => 'Hà Nội',
                            'citizen_id' => '001200123456',
                            'hire_date' => '2024-01-01',
                            'salary' => 8000000,
                            'status' => true,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            }
        }
    }
}
