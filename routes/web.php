<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BorrowingController;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/books', [HomeController::class, 'books'])->name('books');
Route::get('/book/{slug}', [HomeController::class, 'bookDetail'])->name('book.detail');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Member Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::post('/borrow', [BorrowController::class, 'store'])->name('borrow.store');
    Route::get('/my-borrowings', [BorrowController::class, 'index'])->name('borrowings.index');
    Route::patch('/borrowings/{borrowing}/extend', [BorrowController::class, 'extend'])->name('borrowings.extend');
});

// Admin Routes (Admin + Petugas)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Books Management
    Route::resource('books', AdminBookController::class);
    Route::delete('/books/bulk-delete', [AdminBookController::class, 'bulkDelete'])->name('books.bulk-delete');

    // Categories Management
    Route::resource('categories', CategoryController::class);

    // Borrowings Management
    Route::get('/borrowings', [BorrowingController::class, 'index'])->name('borrowings.index');
    Route::get('/borrowings/create', [BorrowingController::class, 'create'])->name('borrowings.create');
    Route::post('/borrowings', [BorrowingController::class, 'store'])->name('borrowings.store');
    Route::get('/borrowings/{borrowing}', [BorrowingController::class, 'show'])->name('borrowings.show');
    Route::patch('/borrowings/{borrowing}/approve', [BorrowingController::class, 'approve'])->name('borrowings.approve');
    Route::patch('/borrowings/{borrowing}/return', [BorrowingController::class, 'return'])->name('borrowings.return');
    Route::patch('/borrowings/{borrowing}/extend', [BorrowingController::class, 'extend'])->name('borrowings.extend');
});

// Super Admin Only Routes
Route::middleware(['auth', 'super_admin'])->prefix('admin')->name('admin.')->group(function () {
    // Users Management (Hanya Admin)
    Route::resource('users', UserController::class);
    Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
});
