<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CashierDashboardController;

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
    Route::get('/cashier/dashboard-stats', [CashierDashboardController::class, 'index']);
});


//Route::middleware('auth:sanctum')->get('/cashier/dashboard-stats', [CashierDashboardController::class, 'index']);

// CEO Reports
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/reports/ceo', [ReportController::class, 'ceoReport']);
});



Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/categories', [CategoryController::class, 'store'])->middleware('auth:sanctum');


// search products by name or SKU add this to the code on the server
Route::get('/products/search/{term}', [ProductController::class, 'search']);


// Admin user management add this too to the server
// Admin user management
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/users', [AuthController::class, 'index']);       // list cashiers
    Route::post('/users', [AuthController::class, 'store']);      // create cashier
    Route::put('/users/{id}', [AuthController::class, 'update']); // update cashier
    Route::delete('/users/{id}', [AuthController::class, 'destroy']); // delete cashier
    Route::get('/profile', [AuthController::class, 'profile']);   // view own profile
    Route::put('/profile', [AuthController::class, 'updateProfile']); // edit own profile
});
