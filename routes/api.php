<?php

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
