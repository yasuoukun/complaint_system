<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminBannerController;
use App\Http\Controllers\AdminSettingController;
use App\Models\News;
use App\Models\Banner;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 1. ส่วนสำหรับบุคคลทั่วไป (Public Routes) - ไม่ต้องล็อกอิน
|--------------------------------------------------------------------------
*/
Route::get('/global-logout', function (Request $request) {
    
    // สั่ง Logout ตามปกติ
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    // ดีดกลับไปหน้า Login
    return redirect('/login');
});
// หน้าแรก
Route::get('/', function () {
    $news = News::latest()->get(); 
    $banners = Banner::where('is_active', true)->orderBy('order')->get();
    $settings = Setting::pluck('value', 'key')->toArray();

    return view('welcome', compact('news', 'banners', 'settings'));
});

// ✅ ย้ายมาตรงนี้แล้ว! (เส้นทางอ่านข่าวสำหรับคนทั่วไป)
Route::get('/news/{id}', [NewsController::class, 'show'])->name('news.show');

// แจ้งเรื่องร้องทุกข์ (Guest)
Route::get('/guest/complaint', [ComplaintController::class, 'guestCreate'])->name('guest.complaint.create');
Route::post('/guest/complaint', [ComplaintController::class, 'guestStore'])->name('guest.complaint.store');

// Debug PHP (ถ้าต้องการเปิดเป็นสาธารณะ)
Route::get('/check-php', function () {
    phpinfo();
});


/*
|--------------------------------------------------------------------------
| 2. ส่วนสำหรับสมาชิก (User Routes) - ต้องล็อกอิน
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard (Redirect ตาม Role)
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.complaints.index');
        }
        return redirect()->route('complaints.history');
    })->name('dashboard');

    // จัดการโปรไฟล์
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ระบบคำร้องเรียน (User)
    Route::get('/complaints/create', [ComplaintController::class, 'create'])->name('complaints.create');
    Route::post('/complaints', [ComplaintController::class, 'store'])->name('complaints.store');
    Route::get('/complaints/history', [ComplaintController::class, 'history'])->name('complaints.history');
    
    // ดูรายละเอียด & แก้ไข
    Route::get('/complaints/{id}', [ComplaintController::class, 'show'])->name('complaints.show');
    Route::get('/complaints/{id}/edit', [ComplaintController::class, 'edit'])->name('complaints.edit');
    Route::put('/complaints/{id}', [ComplaintController::class, 'update'])->name('complaints.update');
});


/*
|--------------------------------------------------------------------------
| 3. ส่วนสำหรับผู้ดูแลระบบ (Admin Routes) - ต้องล็อกอินและเป็น Admin
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    
    // 1. จัดการคำร้องเรียน
    Route::get('/complaints', [ComplaintController::class, 'index'])->name('admin.complaints.index');
    Route::post('/complaints/{id}/process', [ComplaintController::class, 'process'])->name('admin.complaints.process');
    Route::get('/complaints/{id}/download', [ComplaintController::class, 'downloadDocx'])->name('admin.complaints.download');
    Route::delete('/complaints/{id}', [ComplaintController::class, 'destroy'])->name('admin.complaints.destroy');
    Route::post('/complaints/bulk-delete', [ComplaintController::class, 'bulkDestroy'])->name('admin.complaints.bulk_destroy');

    // 2. จัดการข่าวประชาสัมพันธ์ (ใช้ Resource ย่อโค้ดได้)
    // มันจะสร้าง route: index, create, store, edit, update, destroy ให้ครบเลย
    Route::resource('news', NewsController::class)->names('admin.news');

    // 3. จัดการสมาชิก (Users)
    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::put('/users/{id}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');

    // 4. จัดการแบนเนอร์
    Route::get('/banners', [AdminBannerController::class, 'index'])->name('admin.banners.index');
    Route::post('/banners', [AdminBannerController::class, 'store'])->name('admin.banners.store');
    Route::delete('/banners/{id}', [AdminBannerController::class, 'destroy'])->name('admin.banners.destroy');

    // 5. จัดการข้อมูลติดต่อ Footer
    Route::get('/settings', [AdminSettingController::class, 'index'])->name('admin.settings.index');
    Route::post('/settings', [AdminSettingController::class, 'update'])->name('admin.settings.update');
    Route::get('/users/{id}', [AdminUserController::class, 'show'])->name('admin.users.show');
    Route::get('/complaints/{id}', [ComplaintController::class, 'showAdmin'])->name('admin.complaints.show');
}); // <--- ปิดปีกกา Admin ตรงนี้

require __DIR__.'/auth.php';