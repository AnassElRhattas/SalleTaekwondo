<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
use App\Models\Client;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $totalClients = Client::count();

    // Tous les clients dont l'inscription est ancienne de 30 jours ou plus
    $expiringClients = Client::where('payer_abon', '<=', Carbon::now()->subDays(30))->get();

    foreach ($expiringClients as $client) {
        $nextPaymentDate = Carbon::parse($client->payer_abon)->addMonth()->startOfDay();
        $today = Carbon::now()->startOfDay();
    
        $daysRemaining = $nextPaymentDate->diffInDays($today, false);
        $client->days_remaining = $daysRemaining;
    }
    
    

    return view('dashboard', compact('totalClients', 'expiringClients'));
})->middleware(['auth', 'verified'])->name('dashboard');


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
});

require __DIR__.'/auth.php';
