<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TableSessionTimeout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session('is_table_order') && session('table_login_time')) {
            $loginTime = session('table_login_time');
            $currentTime = now()->timestamp;
            
            // 120 minutes = 7200 seconds
            if ($currentTime - $loginTime > 7200) {
                $tableId = session('table_id');
                if ($tableId) {
                    $table = \App\Models\RestaurantTable::find($tableId);
                    if ($table && $table->status === 'occupied') {
                        $table->status = 'available';
                        $table->save();
                    }
                }

                // Clear session
                session()->forget('user_id');
                session()->forget('role_code');
                session()->forget('username');
                session()->forget('is_table_order');
                session()->forget('table_id');
                session()->forget('table_name');
                session()->forget('table_login_time');
                
                return redirect('/')->with('error', 'Bạn đã hết phiên đăng nhập, vui lòng quét mã để đăng nhập.');
            }
        }

        return $next($request);
    }
}
