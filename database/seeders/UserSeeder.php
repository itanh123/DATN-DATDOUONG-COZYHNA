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
        // Ensure admin role exists
        $adminRoleId = DB::table('roles')->where('code', 'admin')->value('id');

        if (!$adminRoleId) {
            // If RoleSeeder wasn't run, create minimal role record
            $adminRoleId = DB::table('roles')->insertGetId([
                'code' => 'admin',
                'name' => 'Admin',
                'description' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $email = 'admin@gmail.com';

        // Insert or update user
        DB::table('users')->updateOrInsert(
            ['email' => $email],
            [
                'role_id' => $adminRoleId,
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'phone' => DB::table('users')->where('email', $email)->value('phone') ?? '0000000000',
                'avatar' => null,
                'status' => true,
                'last_login_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}

