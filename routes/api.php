<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\ReportController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/admin/login', [AuthController::class, 'adminLogin']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/admin/logout', [AuthController::class, 'adminLogout']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/products', [ProductController::class, 'store']); // Admin only
    Route::get('/products', [ProductController::class, 'index']); // Admin sees all, cashier sees stock
    Route::get('/products/{sku}', [ProductController::class, 'show']); // Search by SKU
    Route::put('/products/{id}', [ProductController::class, 'update']); // Admin only
    Route::patch('/products/{id}', [ProductController::class, 'update']); // Admin only
    Route::delete('/products/{id}', [ProductController::class, 'destroy']); // Admin only
    // Update product stock
    Route::patch('/products/{id}/stock', [ProductController::class, 'updateStock']); // Admin only
});

// Sales
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/sales', [SaleController::class, 'store']);                 // create sale
    Route::get('/sales', [SaleController::class, 'index']);                  // list sales (paginated)
    Route::get('/sales/{id}', [SaleController::class, 'show']);              // single sale
    Route::get('/sales/date/{date}', [SaleController::class, 'salesByDate']); // sales by date (paginated)
    Route::get('/sales/receipts/{date}', [SaleController::class, 'receiptsByDate']); // all sales for a date
});

// CEO Reports
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/reports/ceo', [ReportController::class, 'ceoReport']);
});


