<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRoleId = DB::table('roles')->where('code', 'admin')->value('id') ?? DB::table('roles')->insertGetId(['code' => 'admin', 'name' => 'Admin', 'created_at' => now(), 'updated_at' => now()]);
        $staffRoleId = DB::table('roles')->where('code', 'staff')->value('id') ?? DB::table('roles')->insertGetId(['code' => 'staff', 'name' => 'Staff', 'created_at' => now(), 'updated_at' => now()]);
        $shipperRoleId = DB::table('roles')->where('code', 'shipper')->value('id') ?? DB::table('roles')->insertGetId(['code' => 'shipper', 'name' => 'Shipper', 'created_at' => now(), 'updated_at' => now()]);

        $users = [
            [
                'email' => 'admin@gmail.com',
                'role_id' => $adminRoleId,
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'phone' => '0000000000',
            ],
            [
                'email' => 'nhanvien@gmail.com',
                'role_id' => $staffRoleId,
                'username' => 'nhanvien',
                'password' => Hash::make('nhanvien123'),
                'phone' => '0000000001',
            ],
            [
                'email' => 'shipper@gmail.com',
                'role_id' => $shipperRoleId,
                'username' => 'shipper',
                'password' => Hash::make('shipper123'),
                'phone' => '0000000002',
            ]
        ];

        foreach ($users as $user) {
            DB::table('users')->updateOrInsert(
                ['email' => $user['email']],
                [
                    'role_id' => $user['role_id'],
                    'username' => $user['username'],
                    'password' => $user['password'],
                    'phone' => DB::table('users')->where('email', $user['email'])->value('phone') ?? $user['phone'],
                    'avatar' => null,
                    'status' => true,
                    'last_login_at' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}

