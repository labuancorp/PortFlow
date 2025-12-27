<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\BerthPlanner;

Route::middleware(['auth', '2fa'])->group(function () {
    // Accessible by All Roles (Client, Agent, Admin)
    Route::get('/settings', App\Livewire\Settings\Profile::class)->name('settings');
    Route::get('/portal', App\Livewire\AgentPortal::class)->name('agent.portal');

    // Admin & Agent Only
    Route::middleware(['role:admin,agent'])->group(function () {
        Route::get('/', BerthPlanner::class)->name('home');
        Route::get('/dashboard', App\Livewire\Dashboard::class)->name('dashboard');
        Route::get('/vessels', App\Livewire\Vessels\Index::class)->name('vessels.index');
        Route::get('/billing', App\Livewire\Billing\Index::class)->name('billing.index');
        Route::get('/ops', App\Livewire\MobileOps::class)->name('ops.mobile');
        Route::get('/mobile-ops', App\Livewire\MobileOps::class)->name('ops.mobile.alias');
        Route::get('/map', App\Livewire\Map\PortMap::class)->name('map.index');
        Route::get('/terminal', App\Livewire\Crew\Terminal::class)->name('crew.terminal');
        Route::get('/analytics', App\Livewire\Analytics\Dashboard::class)->name('analytics');
        Route::get('/gate/scanner', App\Livewire\Gate\Scanner::class)->name('gate.scanner');
        
        // Logistics / Cargo
        Route::get('/cargo/manifests', App\Livewire\Cargo\ManifestIndex::class)->name('cargo.manifests.index');
        Route::get('/cargo/manifests/create', App\Livewire\Cargo\ManifestCreate::class)->name('cargo.manifests.create');
        Route::get('/cargo/manifests/{manifest}', App\Livewire\Cargo\ManifestShow::class)->name('cargo.manifests.show');
        Route::get('/warehouse/map', App\Livewire\Warehouse\YardMap::class)->name('warehouse.map');
    });

    // HSE / Safety (Admin, Agent, HSE Role)
    Route::middleware(['role:admin,agent,hse'])->group(function () {
        Route::get('/hse/permits', App\Livewire\HSE\PermitDashboard::class)->name('hse.permits.dashboard');
        Route::get('/hse/permits/create', App\Livewire\HSE\PermitCreate::class)->name('hse.permits.create');
    });

    // Admin Only
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/agents', App\Livewire\Agents\Index::class)->name('agents.index');
        Route::get('/wharfs', App\Livewire\Wharfs\Index::class)->name('wharfs.index');
        Route::get('/admin/health', App\Livewire\Admin\SystemHealth::class)->name('admin.health');
        Route::get('/admin/audit', App\Livewire\Admin\AuditTrail::class)->name('admin.audit');
        // Print Route (No Composer dependency for speed)
    Route::get('/invoice/{invoice}/print', function (App\Models\Invoice $invoice) {
        $invoice->load(['organization', 'portCall.vessel', 'invoiceItems']);
        return view('pdf.invoice', ['invoice' => $invoice]);
    })->name('invoice.print');
});
});

// Auth Routes (Rate Limited)
Route::middleware('throttle:6,1')->group(function () {
    Route::get('/login', App\Livewire\Auth\Login::class)->name('login');
    Route::get('/register', App\Livewire\Auth\Register::class)->name('register');
});

Route::get('/2fa/verify', App\Livewire\Auth\TwoFactorChallenge::class)->name('2fa.verify')->middleware(['auth', 'throttle:10,1']);

Route::get('/logout', function () {
    Illuminate\Support\Facades\Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

Route::get('/track/{tracking_number}', App\Livewire\Cargo\TrackItem::class)->name('cargo.track');
Route::get('/gate/request', App\Livewire\Gate\PreRegister::class)->name('gate.request');
