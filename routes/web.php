<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\WhatsAppReminderController;
use App\Http\Controllers\PaymentTrackingController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
use App\Models\Client;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

Route::middleware('auth')->group(function () {
    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
    Route::get('/clients/create', [ClientController::class, 'create'])->name('clients.create');
    Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');
    Route::get('/clients/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
    Route::patch('/clients/{client}', [ClientController::class, 'update'])->name('clients.update');
    Route::delete('/clients/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');
    Route::get('/clients/{client}/validate-payment', [ClientController::class, 'validatePayment'])->name('clients.validate-payment');
    
    // Routes pour le suivi des paiements mensuels
    Route::get('/payments/tracking', [PaymentTrackingController::class, 'index'])->name('payments.tracking');
    Route::post('/payments/{client}/validate-month', [PaymentTrackingController::class, 'validateMonthPayment'])->name('payments.validate-month');
    
    // Routes pour les rappels WhatsApp
    Route::get('/whatsapp-reminders', [WhatsAppReminderController::class, 'index'])->name('whatsapp.index');
    Route::post('/whatsapp-reminders/generate', [WhatsAppReminderController::class, 'generateAndDownload'])->name('whatsapp.generate');
    Route::get('/whatsapp-reminders/download', [WhatsAppReminderController::class, 'generateAndDownload'])->name('whatsapp.download');
});

require __DIR__.'/auth.php';
