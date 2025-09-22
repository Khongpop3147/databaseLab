<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SocialMediaLinkController;
use App\Http\Controllers\DiaryEntryController;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard ต้องล็อกอินและยืนยันอีเมลแล้ว
Route::middleware(['auth', 'verified'])->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// กลุ่มเส้นทางที่ต้องล็อกอิน
Route::middleware('auth')->group(function () {

    // Diary (CRUD) — ย้ายมาในกลุ่ม auth เพื่อให้แน่ใจว่าจำเป็นต้องล็อกอิน
    Route::resource('diary', DiaryEntryController::class);

    // โปรไฟล์ (Breeze มาตรฐาน)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Bio (One-to-One) — แสดงหน้าแก้ไข bio และรับการอัพเดต (showBio / updateBio ใน ProfileController)
    Route::get('/profile/bio', [ProfileController::class, 'showBio'])->name('profile.show-bio');
    Route::patch('/profile/bio', [ProfileController::class, 'updateBio'])->name('profile.update-bio');

    // อัปเดตรูปโปรไฟล์
    Route::patch('/profile/photo/update', [UserController::class, 'updateProfilePhoto'])->name('profile.photo.update');
    Route::get('/profile/photo/{filename}', [UserController::class, 'showProfilePhoto'])
        ->where('filename', '.*')
        ->name('user.photo');

    // Social media links (CRUD)
    Route::resource('social_media_links', SocialMediaLinkController::class);
});
Route::get('/show-bio', function () {
    return view('show-bio'); // ถ้าไฟล์อยู่ที่ resources/views/show-bio.blade.php
});

require __DIR__.'/auth.php';
