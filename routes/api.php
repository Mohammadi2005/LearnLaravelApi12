<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use App\Http\Middleware\LoginCheck;
use App\Http\Middleware\GuestCheck;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/', function () {
    return response()->json([
        'message' => 'api',
        'status' => 200
    ]);
});

Route::post('/user', function (Request $request) {

    Log::info(json_encode($request->all()));

    return response()->json([
        'name' => $request->get('name'),
        'age' => $request->get('age'),
    ]);
});

Route::middleware([LoginCheck::class])->prefix('blog')->name('blog')->group(function () {
    Route::get('/', [BlogController::class, 'index']);
    Route::post('/', [BlogController::class, 'store'])->name('.store');
    Route::delete('/{blog:slug}', [BlogController::class, 'destroy'])->name('.destroy');
    Route::match(['PUT', 'PATCH'], '/{blog}', [BlogController::class, 'update'])->name('update');
    Route::get('/{slug}', [BlogController::class, 'show'])->name('.show');
});

Route::middleware([GuestCheck::class])->prefix('auth')->name('auth')->group(function () {
    Route::post('/register', [RegisterController::class, 'register'])->name('.register');
    Route::post('/login', [LoginController::class, 'login'])->name('.login');
});

Route::middleware([LoginCheck::class])->prefix('user')->name('user')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/{id}', [UserController::class, 'show'])->name('.show');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware([LoginCheck::class]);
