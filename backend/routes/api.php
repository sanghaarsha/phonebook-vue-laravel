<?php

use App\Http\Controllers\ContactController;
use App\Http\Middleware\EnsureJsonRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(EnsureJsonRequest::class)->apiResource('/contact', ContactController::class);
Route::post('/contact/add', [ContactController::class, 'add']);
