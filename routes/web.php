<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProfileController,
    UserController,
    DashboardController,
    SocialMediaLinkController,
    DiaryEntryController,
    ReminderController
};

Route::view('/', 'welcome')->name('welcome');

/**
 * Dashboard ต้องล็อกอิน + ยืนยันอีเมล
 */
Route::middleware(['auth', 'verified'])
    ->get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

/**
 * กลุ่มเส้นทางที่ต้องล็อกอิน
 */
Route::middleware('auth')->group(function () {
    // Diary (CRUD)
    Route::resource('diary', DiaryEntryController::class);

    // ✅ Conflicting Emotions (Sad+มีคำว่า "happy")
    Route::get('/conflicts', [DiaryEntryController::class, 'conflicts'])
        ->name('diary.conflicts');

    // Reminders (CRUD)
    Route::resource('reminders', ReminderController::class);

    // Social media links (CRUD)
    Route::resource('social_media_links', SocialMediaLinkController::class);

    // โปรไฟล์ (Breeze มาตรฐาน)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Bio (One-to-One)
    Route::get('/profile/bio', [ProfileController::class, 'showBio'])->name('profile.show-bio');
    Route::patch('/profile/bio', [ProfileController::class, 'updateBio'])->name('profile.update-bio');

    // อัปเดตรูปโปรไฟล์
    Route::patch('/profile/photo/update', [UserController::class, 'updateProfilePhoto'])->name('profile.photo.update');
    Route::get('/profile/photo/{filename}', [UserController::class, 'showProfilePhoto'])
        ->where('filename', '.*')
        ->name('user.photo');
});

// ตัวอย่างหน้าโชว์ bio แบบ static (ถ้าใช้จริงให้ลบ/ย้าย)
Route::get('/show-bio', fn () => view('show-bio'));

require __DIR__ . '/auth.php';
