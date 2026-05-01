<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\PurchaseRequisitionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockIssueController;
use App\Http\Controllers\StockReceiptController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::resource('categories', CategoryController::class);
    Route::resource('items', ItemController::class);
    Route::resource('stores', StoreController::class);
    Route::resource('purchase-requisitions', PurchaseRequisitionController::class);
    Route::resource('purchase-orders', PurchaseOrderController::class);
    Route::resource('stock-receipts', StockReceiptController::class)->except(['edit', 'update']);
    Route::resource('stock-issues', StockIssueController::class)->except(['edit', 'update']);
    Route::get('/stock-balance', [ReportController::class, 'index'])->name('stock-balance.index');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
