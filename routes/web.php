<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\AdminUserController; // เพิ่ม Controller จัดการสมาชิก
use App\Http\Controllers\AdminBannerController; // เพิ่ม Controller แบนเนอร์
use App\Http\Controllers\AdminSettingController; // เพิ่ม Controller ตั้งค่า
use App\Models\News;
use App\Models\Banner; // เพิ่ม Model Banner
use App\Models\Setting; // เพิ่ม Model Setting
use Illuminate\Support\Facades\Route;

// --- หน้าแรก (ดึงข่าว, แบนเนอร์, และข้อมูลติดต่อมาแสดง) ---
Route::get('/', function () {
    $news = News::latest()->get(); 
    
    // 🔥 เพิ่มส่วนดึงแบนเนอร์และตั้งค่า Footer 🔥
    $banners = Banner::where('is_active', true)->orderBy('order')->get();
    $settings = Setting::pluck('value', 'key')->toArray();

    return view('welcome', compact('news', 'banners', 'settings'));
});

// --- Dashboard (แยกทาง User/Admin) ---
Route::get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.complaints.index');
    }
    return redirect()->route('complaints.history');
})->middleware(['auth', 'verified'])->name('dashboard');

// --- เส้นทางสำหรับบุคคลทั่วไป (ไม่ต้องล็อกอิน) ---
Route::get('/guest/complaint', [ComplaintController::class, 'guestCreate'])->name('guest.complaint.create');
Route::post('/guest/complaint', [ComplaintController::class, 'guestStore'])->name('guest.complaint.store');

// --- Routes สำหรับ User ทั่วไป (ต้อง Login) ---
Route::middleware('auth')->group(function () {
    // จัดการโปรไฟล์
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ระบบคำร้องเรียน (User)
    Route::get('/complaints/create', [ComplaintController::class, 'create'])->name('complaints.create');
    Route::post('/complaints', [ComplaintController::class, 'store'])->name('complaints.store');
    Route::get('/complaints/history', [ComplaintController::class, 'history'])->name('complaints.history');
    
    // ดูรายละเอียด (ใช้ได้ทั้ง User และ Admin)
    Route::get('/complaints/{id}', [ComplaintController::class, 'show'])->name('complaints.show');
    
    // แก้ไขคำร้อง (ภายใน 10 นาที)
    Route::get('/complaints/{id}/edit', [ComplaintController::class, 'edit'])->name('complaints.edit');
    Route::put('/complaints/{id}', [ComplaintController::class, 'update'])->name('complaints.update');
});

// --- Routes สำหรับ Admin Only ---
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    
    // 1. จัดการคำร้องเรียน
    Route::get('/complaints', [ComplaintController::class, 'index'])->name('admin.complaints.index');
    Route::post('/complaints/{id}/process', [ComplaintController::class, 'process'])->name('admin.complaints.process');
    Route::get('/complaints/{id}/download', [ComplaintController::class, 'downloadDocx'])->name('admin.complaints.download');
    Route::delete('/complaints/{id}', [ComplaintController::class, 'destroy'])->name('admin.complaints.destroy');
    Route::post('/complaints/bulk-delete', [ComplaintController::class, 'bulkDestroy'])->name('admin.complaints.bulk_destroy');

    // 2. จัดการข่าวประชาสัมพันธ์ (News)
    Route::get('/news', [NewsController::class, 'index'])->name('admin.news.index');
    Route::post('/news', [NewsController::class, 'store'])->name('admin.news.store');
    Route::get('/news/{id}/edit', [NewsController::class, 'edit'])->name('admin.news.edit');
    Route::put('/news/{id}', [NewsController::class, 'update'])->name('admin.news.update');
    Route::delete('/news/{id}', [NewsController::class, 'destroy'])->name('admin.news.destroy');

    // 3. จัดการสมาชิก (Users)
    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::put('/users/{id}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');

    // 🔥 4. จัดการแบนเนอร์ (เพิ่มใหม่) 🔥
    Route::get('/banners', [AdminBannerController::class, 'index'])->name('admin.banners.index');
    Route::post('/banners', [AdminBannerController::class, 'store'])->name('admin.banners.store');
    Route::delete('/banners/{id}', [AdminBannerController::class, 'destroy'])->name('admin.banners.destroy');

    // 🔥 5. จัดการข้อมูลติดต่อ Footer (เพิ่มใหม่) 🔥
    Route::get('/settings', [AdminSettingController::class, 'index'])->name('admin.settings.index');
    Route::post('/settings', [AdminSettingController::class, 'update'])->name('admin.settings.update');

});

require __DIR__.'/auth.php';