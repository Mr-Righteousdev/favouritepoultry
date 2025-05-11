<?php

// use App\Http\Livewire\Dashboard;
use App\Livewire\Settings\Appearance;
use App\Livewire\Dashboard;
use App\Livewire\Eggs\EggForm;
use App\Livewire\Eggs\EggList;
use App\Livewire\FeedManagement;
use App\Livewire\Flocks\FlockList;
use App\Livewire\Flocks\FlockForm;
use App\Livewire\HealthManagement;;

use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');




Route::middleware(['auth'])->group(function () {

    Route::get('/flocks', FlockList::class)->name('flocks');
    Route::get('/flocks/add', FlockForm::class)->name('flocks.add');
    Route::get('/flocks/edit/{flockId}', FlockForm::class)->name('flocks.edit');
    Route::get('/eggs', EggList::class)->name('egg-production');
    Route::get('/eggs/add', EggForm::class)->name('eggs.add');
    Route::get('/eggs/edit/{eggsId}', EggForm::class)->name('eggs.edit');
    Route::get('/feeds', FeedManagement::class)->name('feeds');
    Route::get('/health', HealthManagement::class)->name('health');


    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__ . '/auth.php';
