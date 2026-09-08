<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\SparepartController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController;

use App\Http\Controllers\Teknisi\DashboardController as TeknisiDashboardController;
use App\Http\Controllers\Teknisi\TaskController;

use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\ServiceController as CustomerServiceController;

// Public routes
Route::get('/', [\App\Http\Controllers\LandingController::class, 'index'])->name('home');
Route::post('/booking', [\App\Http\Controllers\LandingController::class, 'booking'])->name('booking.store');

// Customer Tracking (Public & Ticket-based)
Route::get('/lacak', [CustomerServiceController::class, 'trackForm'])->name('customer.trackForm');
Route::post('/lacak', [CustomerServiceController::class, 'track'])->name('customer.track');

// Customer Approval (Public link from WA)
Route::get('/approve/{service}', [CustomerServiceController::class, 'approveForm'])->name('customer.services.approve-form');
Route::post('/approve/{service}', [CustomerServiceController::class, 'approve'])->name('customer.services.approve');

// Payment Callback (Midtrans/Xendit webhook)
Route::post('/payment/callback', [CustomerServiceController::class, 'paymentCallback'])->name('customer.payment.callback');

// Dashboard Router based on Role
Route::get('/dashboard', function () {
    $role = auth()->user()->role;
    if ($role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($role === 'teknisi') {
        return redirect()->route('teknisi.dashboard');
    } elseif ($role === 'customer') {
        return redirect()->route('customer.dashboard');
    }
    return redirect('/');
})->middleware(['auth'])->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('customers', CustomerController::class);
    Route::resource('spareparts', SparepartController::class);
    Route::resource('services', AdminServiceController::class);
    Route::post('/services/{service}/status', [AdminServiceController::class, 'updateStatus'])->name('services.updateStatus');
    Route::post('/services/{service}/spareparts', [AdminServiceController::class, 'addSparepart'])->name('services.addSparepart');
    Route::delete('/services/{service}/spareparts/{detail}', [AdminServiceController::class, 'removeSparepart'])->name('services.removeSparepart');
    Route::post('/services/{service}/photos', [AdminServiceController::class, 'uploadPhoto'])->name('services.uploadPhoto');
    Route::delete('/services/{service}/photos/{photo}', [AdminServiceController::class, 'deletePhoto'])->name('services.deletePhoto');

    Route::resource('transactions', TransactionController::class);
    Route::get('/transactions/{transaction}/invoice', [TransactionController::class, 'invoice'])->name('transactions.invoice');

    Route::resource('users', UserController::class);

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/pdf', [ReportController::class, 'pdf'])->name('reports.pdf');
    Route::get('/reports/excel', [ReportController::class, 'excel'])->name('reports.excel');
});

// Teknisi Routes
Route::middleware(['auth', 'role:teknisi'])->prefix('teknisi')->name('teknisi.')->group(function () {
    Route::get('/dashboard', [TeknisiDashboardController::class, 'index'])->name('dashboard');
    Route::resource('tasks', TaskController::class)->only(['index', 'show', 'edit', 'update']);
    Route::post('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');
    Route::post('/tasks/{task}/spareparts', [TaskController::class, 'addSparepart'])->name('tasks.addSparepart');
    Route::delete('/tasks/{task}/spareparts/{detail}', [TaskController::class, 'removeSparepart'])->name('tasks.removeSparepart');
    Route::post('/tasks/{task}/photos', [TaskController::class, 'uploadPhoto'])->name('tasks.uploadPhoto');
    Route::delete('/tasks/{task}/photos/{photo}', [TaskController::class, 'deletePhoto'])->name('tasks.deletePhoto');
});

// Customer Routes (Authenticated)
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/services/{service}', [CustomerServiceController::class, 'show'])->name('services.show');
    Route::post('/services/{service}/payment', [CustomerServiceController::class, 'createPayment'])->name('services.createPayment');
    Route::post('/services/{service}/comment', [CustomerServiceController::class, 'addComment'])->name('services.addComment');
});

// Shared Comment Routes (all authenticated roles)
Route::middleware(['auth'])->group(function () {
    Route::post('/services/{service}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/services/{service}/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
