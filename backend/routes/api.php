<?php

use App\Http\Controllers\ContactController;
use App\Http\Middleware\EnsureJsonRequest;
use Illuminate\Support\Facades\Route;

Route::middleware(EnsureJsonRequest::class)->group(function () {
    Route::apiResource('/contact', ContactController::class);
    Route::prefix('/contact/{contact}')
        ->controller(ContactController::class)
        ->group(function () {
            Route::post('/add', 'add');
            Route::delete('/remove', 'remove');
        });
});

Route::fallback(function () {
    return response()->json([
        'status' => false,
        'message' => 'Invalid Route',
    ], 404);
});
