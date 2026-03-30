<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;

Route::get('/check-sso-token', function (Request $request) {
    $token = $request->query('token');
    
    // ดึง User ID ออกมา แล้วลบทิ้งเลย
    $userId = Cache::pull('sso_token_' . $token);

    if (!$userId) {
        return response()->json(['error' => 'Invalid token'], 401);
    }

    // ส่ง User ID กลับไปให้ Project 2
    return response()->json(['user_id' => $userId]);
});
        