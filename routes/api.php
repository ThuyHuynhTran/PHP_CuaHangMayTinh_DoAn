<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BrandController;

// ✅ API trả danh sách thương hiệu
Route::get('/brands', [BrandController::class, 'index']);
