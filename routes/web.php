<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\BerthPlanner;

Route::get('/', BerthPlanner::class)->name('home');
Route::get('/dashboard', App\Livewire\Dashboard::class)->name('dashboard');
Route::get('/vessels', App\Livewire\Vessels\Index::class)->name('vessels.index');
Route::get('/billing', App\Livewire\Billing\Index::class)->name('billing.index');
Route::get('/wharfs', App\Livewire\Wharfs\Index::class)->name('wharfs.index');
