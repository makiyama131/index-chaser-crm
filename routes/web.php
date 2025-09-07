<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ActivityController; // 先頭に追加
use App\Http\Controllers\DocumentController; // 先頭に追加





Route::get('/', function () {
    return view('welcome');
});

// 上下、配置を気をつけろ！！！

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // ダッシュボード
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // タスク管理
    Route::resource('tasks', TaskController::class);
    Route::get('tasks/{task}/complete', [TaskController::class, 'showCompleteForm'])->name('tasks.showCompleteForm')->middleware('auth');
    Route::patch('tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete')->middleware('auth');
    Route::get('customers/auto-create', [CustomerController::class, 'showAutoCreateForm'])->name('customers.autoCreate');
    Route::post('customers/auto-store', [CustomerController::class, 'parseAndStore'])->name('customers.autoStore');

    Route::resource('customers', CustomerController::class);

    Route::patch('customers/{customer}/update-rank', [CustomerController::class, 'updateRank'])->name('customers.updateRank');
    Route::patch('customers/{customer}/update-status', [CustomerController::class, 'updateStatus'])->name('customers.updateStatus');

    Route::post('tasks', [TaskController::class, 'store'])->name('tasks.store')->middleware('auth');
    Route::post('activities', [ActivityController::class, 'store'])->name('activities.store')->middleware('auth');

    Route::post('documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::get('documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::delete('documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    Route::get('documents/{document}/preview', [DocumentController::class, 'preview'])->name('documents.preview');
    Route::get('documents/{document}/edit', [DocumentController::class, 'edit'])->name('documents.edit');
    Route::patch('documents/{document}', [DocumentController::class, 'update'])->name('documents.update');
});

require __DIR__ . '/auth.php';
