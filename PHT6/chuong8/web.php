<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SinhVienController;

// TODO 12: Thêm 2 route này 
Route::get('/', [PageController::class, 'showHomepage']); 
Route::get('/about', [PageController::class, 'showHomepage']);

Route::get('/sinhvien', [SinhVienController::class, 'index'])->name('sinhvien.index'); 

Route::post('/sinhvien', [SinhVienController::class, 'store'])->name('sinhvien.store');
