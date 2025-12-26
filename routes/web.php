<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\BerthPlanner;

Route::middleware(['auth'])->group(function () {
    Route::get('/', BerthPlanner::class)->name('home');
    Route::get('/dashboard', App\Livewire\Dashboard::class)->name('dashboard');
    Route::get('/vessels', App\Livewire\Vessels\Index::class)->name('vessels.index');
    Route::get('/billing', App\Livewire\Billing\Index::class)->name('billing.index');
    Route::get('/wharfs', App\Livewire\Wharfs\Index::class)->name('wharfs.index');
    Route::get('/portal', App\Livewire\AgentPortal::class)->name('agent.portal');
    Route::get('/ops', App\Livewire\MobileOps::class)->name('ops.mobile');
    Route::get('/agents', App\Livewire\Agents\Index::class)->name('agents.index');
    Route::get('/map', App\Livewire\Map\PortMap::class)->name('map.index');
    Route::get('/terminal', App\Livewire\Crew\Terminal::class)->name('crew.terminal');
    Route::get('/analytics', App\Livewire\Analytics\Dashboard::class)->name('analytics');
});

// Admin Routes (RBAC Protected)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/health', App\Livewire\Admin\SystemHealth::class)->name('admin.health');
});

// Auth Routes
Route::get('/login', App\Livewire\Auth\Login::class)->name('login');
Route::get('/register', App\Livewire\Auth\Register::class)->name('register');
Route::get('/logout', function () {
    Illuminate\Support\Facades\Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');
