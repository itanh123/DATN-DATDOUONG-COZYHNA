<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiningTable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DiningTableController extends Controller
{
    public function index()
    {
        $tables = DiningTable::with('user')->orderBy('id', 'desc')->get();
        return view('admin.tables.index', compact('tables'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:dining_tables,name',
        ]);

        DB::beginTransaction();
        try {
            // Get customer role ID
            $roleId = DB::table('roles')->where('code', 'customer')->value('id');

            if (!$roleId) {
                return back()->with('error', 'Chưa có role customer trong hệ thống!');
            }

            $username = 'table_' . time() . '_' . rand(100, 999);
            
            // Create user
            $user = User::create([
                'username' => $username,
                'email' => $username . '@local.com',
                'phone' => '0' . rand(100000000, 999999999),
                'password' => Hash::make(Str::random(10)),
                'role_id' => $roleId,
                'status' => true,
            ]);

            // Create customer profile
            DB::table('customer_profiles')->insert([
                'user_id' => $user->id,
                'full_name' => $request->name,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create dining table
            DiningTable::create([
                'name' => $request->name,
                'user_id' => $user->id,
                'qr_token' => Str::random(32),
                'status' => true,
            ]);

            DB::commit();
            return back()->with('success', 'Đã thêm bàn mới thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    public function destroy(DiningTable $table)
    {
        DB::beginTransaction();
        try {
            $user = $table->user;
            if ($user) {
                DB::table('customer_profiles')->where('user_id', $user->id)->delete();
                $user->delete();
            }
            $table->delete();
            DB::commit();
            return back()->with('success', 'Đã xóa bàn thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    public function generateQrCode(DiningTable $table)
    {
        $table->update([
            'qr_token' => Str::random(32)
        ]);
        return back()->with('success', 'Đã tạo lại mã QR mới!');
    }
}
