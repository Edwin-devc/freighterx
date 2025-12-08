<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\TeamController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('orders', [OrderController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('orders');

Route::get('orders/create', [OrderController::class, 'create'])
    ->middleware(['auth', 'verified'])
    ->name('orders.create');

Route::post('orders', [OrderController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('orders.store');

Route::get('orders/{id}', [OrderController::class, 'show'])
    ->name('orders.show');

Route::patch('orders/{id}', [OrderController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('orders.update');

Route::get('customers', [CustomerController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('customers');

Route::post('customers', [CustomerController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('customers.store');

// Team Management Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('team', [TeamController::class, 'index'])->name('team.index');
    Route::patch('team/{user}/role', [TeamController::class, 'updateRole'])->name('team.update-role');
    Route::delete('team/{user}', [TeamController::class, 'destroy'])->name('team.destroy');
});

// Invitation Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('invitations', [InvitationController::class, 'index'])->name('invitations.index');
    Route::post('invitations', [InvitationController::class, 'store'])->name('invitations.store');
    Route::delete('invitations/{invitation}', [InvitationController::class, 'destroy'])->name('invitations.destroy');
});

// Public invitation acceptance routes (no auth required)
Route::get('invitations/{token}/accept', [InvitationController::class, 'show'])->name('invitations.show');
Route::post('invitations/{token}/accept', [InvitationController::class, 'accept'])->name('invitations.accept');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});
