<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/admin-test', function () {
    return "Admin Panel Working";
})->middleware(['auth', 'admin']);

Route::middleware(['auth','admin'])->group(function () {

    Route::resource('users', UserController::class)->except(['show', 'destroy']);
    Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])
        ->name('users.toggleStatus');

});
Route::middleware(['auth'])->group(function () {
    Route::resource('customers', CustomerController::class)->except(['show']);
    Route::resource('products', ProductController::class)->except(['show']);
    Route::resource('orders', OrderController::class)->except(['show']);
});
require __DIR__.'/auth.php';
