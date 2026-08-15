<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!in_array(session('role_code'), ['admin', 'staff', 'shipper'])) {
            return redirect('/login/admin')->with('error', 'Vui lòng đăng nhập bằng tài khoản quản trị/nhân viên.');
        }

        return $next($request);
    }
}
