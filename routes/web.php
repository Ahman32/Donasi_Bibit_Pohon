<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SeedlingController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlantingProofController;
use App\Http\Controllers\CertificateController;

// Public routes
Route::get('/', [SeedlingController::class, 'welcome'])->name('welcome');
Route::get('/stats-json', [DashboardController::class, 'statsJson'])->name('stats.json');

// Guest only routes (login, register)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // User dashboard & donations
    Route::get('/dashboard', [DashboardController::class, 'userDashboard'])->name('dashboard');
    Route::get('/donations/create', [DonationController::class, 'create'])->name('donations.create');
    Route::post('/donations', [DonationController::class, 'store'])->name('donations.store');
    Route::get('/donations/{donation}/pay', [DonationController::class, 'pay'])->name('donations.pay');
    Route::post('/donations/{donation}/proof', [DonationController::class, 'submitProof'])->name('donations.proof');
    Route::post('/donations/{donation}/simulate-success', [DonationController::class, 'simulateSuccess'])->name('donations.simulate_success');

    // Certificate download & preview
    Route::get('/certificates/{certificate}/download', [CertificateController::class, 'downloadPdf'])->name('certificates.download');
    Route::get('/certificates/{certificate}/preview', [CertificateController::class, 'preview'])->name('certificates.preview');
    Route::get('/donations/{donation}/certificate', [CertificateController::class, 'downloadByDonation'])->name('donations.certificate.download');

    // Admin routes
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [DashboardController::class, 'adminDashboard'])->name('dashboard');
        
        // Seedling management (CRUD)
        Route::resource('seedlings', SeedlingController::class)->except(['show']);

        // Proof & Gallery management
        Route::resource('proofs', PlantingProofController::class)->only(['index', 'store', 'destroy']);

        // Certificate management
        Route::resource('certificates', CertificateController::class)->except(['show']);
        Route::post('/certificates/quick-generate/{donation}', [CertificateController::class, 'quickGenerate'])->name('certificates.quick_generate');
        
        // Donation approval & report routes
        Route::get('/donations/pdf', [DonationController::class, 'exportPdf'])->name('donations.pdf');
        Route::get('/donations/print', [DonationController::class, 'printReport'])->name('donations.print');
        Route::post('/donations/{donation}/approve', [DonationController::class, 'approve'])->name('donations.approve');
        Route::post('/donations/{donation}/reject', [DonationController::class, 'reject'])->name('donations.reject');
    });
});
