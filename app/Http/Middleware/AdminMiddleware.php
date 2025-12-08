<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // ตรวจสอบว่าล็อกอินหรือยัง และเป็น role 'admin' หรือไม่
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        // ถ้าไม่ใช่ ให้ดีดกลับหรือแสดง Error
        abort(403, 'คุณไม่มีสิทธิ์เข้าถึงส่วนนี้ (สำหรับผู้ดูแลระบบเท่านั้น)');
    }
}