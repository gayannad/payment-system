<?php

use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Payment file upload
Route::post('/upload', [PaymentController::class, 'upload']);

// List all payments
Route::get('/payments', [PaymentController::class, 'index']);

// List all invoices
Route::get('/invoices', [InvoiceController::class, 'index']);
