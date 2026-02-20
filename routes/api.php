<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BlogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

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

Route::middleware("auth:sanctum")->prefix('blog')->name('blog')->group(function () {
    Route::get('/', [BlogController::class, 'index']);
    Route::post('/', [BlogController::class, 'store'])->name('.store');
    Route::delete('/{blog:slug}', [BlogController::class, 'destroy'])->name('.destroy');
    Route::match(['PUT', 'PATCH'], '/{blog}', [BlogController::class, 'update'])->name('update');
    Route::get('/{slug}', [BlogController::class, 'show'])->name('.show');
});

//Route::group(function () {
//    Route::post('/register', [RegisterController::class, 'register'])->name('.register');
//    Route::post('/login', [LoginController::class, 'login'])->name('.login');
//});

Route::post('/auth/register', [RegisterController::class, 'register'])->name('register');
Route::post('/auth/login', [LoginController::class, 'login'])->name('login');

