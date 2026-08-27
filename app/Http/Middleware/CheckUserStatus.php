<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session()->has('user_id')) {
            $user = \App\Models\User::find(session('user_id'));
            
            // If user doesn't exist or is locked
            if (!$user || !$user->status) {
                session()->forget(['user_id', 'role_code']);
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['error' => 'Tài khoản của bạn đã bị khóa.'], 403);
                }
                return redirect('/login')->withErrors(['email' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.']);
            }
            
            // If role has changed
            $currentRoleCode = \Illuminate\Support\Facades\DB::table('roles')->where('id', $user->role_id)->value('code');
            if (session('role_code') !== $currentRoleCode) {
                session()->forget(['user_id', 'role_code']);
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['error' => 'Quyền hạn đã thay đổi. Vui lòng đăng nhập lại.'], 403);
                }
                return redirect('/login')->withErrors(['email' => 'Quyền hạn của bạn đã được cập nhật. Vui lòng đăng nhập lại.']);
            }
        }

        return $next($request);
    }
}
