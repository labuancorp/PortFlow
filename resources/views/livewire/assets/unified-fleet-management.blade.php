<div class="p-6 bg-slate-50 min-h-screen">
    <!-- Soft Pastel Header -->
    <div class="mb-8 bg-gradient-to-br from-violet-50 to-purple-50 rounded-2xl p-8 border border-violet-200 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-white rounded-xl shadow-sm">
                    <svg class="w-8 h-8 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Fleet & Asset Management</h1>
                    <p class="text-violet-700/70 text-sm mt-1 font-medium">Unified equipment tracking, rentals & maintenance</p>
                </div>
            </div>
            <div class="flex gap-3">
                <input type="text" wire:model.live="search" placeholder="Search assets..." class="px-4 py-2 rounded-xl border-slate-300 focus:border-slate-900 focus:ring-2 focus:ring-slate-900/20">
                <button wire:click="openAssetModal" class="px-6 py-3 bg-slate-900 text-white font-bold rounded-xl hover:bg-slate-800 transition-all shadow-sm">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Register Asset
                </button>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl shadow-sm flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-bold text-sm">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-6 gap-6 mb-8">
        <div class="bg-gradient-to-br from-violet-50 to-purple-50 p-6 rounded-2xl border border-violet-200 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <div class="p-2 bg-white rounded-lg shadow-sm">
                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
            </div>
            <div class="text-xs text-violet-600/70 font-bold uppercase tracking-widest">Total Assets</div>
            <div class="text-3xl font-black text-violet-900 tabular-nums">{{ $stats['total'] }}</div>
        </div>

        <div class="bg-gradient-to-br from-emerald-50 to-teal-50 p-6 rounded-2xl border border-emerald-200 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <div class="p-2 bg-white rounded-lg shadow-sm">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="text-xs text-emerald-600/70 font-bold uppercase tracking-widest">Available</div>
            <div class="text-3xl font-black text-emerald-900 tabular-nums">{{ $stats['available'] }}</div>
        </div>

        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-6 rounded-2xl border border-blue-200 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <div class="p-2 bg-white rounded-lg shadow-sm">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="text-xs text-blue-600/70 font-bold uppercase tracking-widest">In Use</div>
            <div class="text-3xl font-black text-blue-900 tabular-nums">{{ $stats['in_use'] }}</div>
        </div>

        <div class="bg-gradient-to-br from-amber-50 to-orange-50 p-6 rounded-2xl border border-amber-200 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <div class="p-2 bg-white rounded-lg shadow-sm">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
            </div>
            <div class="text-xs text-amber-600/70 font-bold uppercase tracking-widest">Maintenance</div>
            <div class="text-3xl font-black text-amber-900 tabular-nums">{{ $stats['maintenance'] }}</div>
        </div>

        <div class="bg-gradient-to-br from-cyan-50 to-blue-50 p-6 rounded-2xl border border-cyan-200 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <div class="p-2 bg-white rounded-lg shadow-sm">
                    <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
            </div>
            <div class="text-xs text-cyan-600/70 font-bold uppercase tracking-widest">MHE Fleet</div>
            <div class="text-3xl font-black text-cyan-900 tabular-nums">{{ $stats['mhe_count'] }}</div>
        </div>

        <div class="bg-gradient-to-br from-rose-50 to-pink-50 p-6 rounded-2xl border border-rose-200 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <div class="p-2 bg-white rounded-lg shadow-sm">
                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
            </div>
            <div class="text-xs text-rose-600/70 font-bold uppercase tracking-widest">PM Due</div>
            <div class="text-3xl font-black text-rose-900 tabular-nums">{{ $stats['pm_due'] }}</div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm mb-6">
        <div class="flex gap-3 overflow-x-auto">
            <button wire:click="setTab('all')" class="px-4 py-2 rounded-lg font-bold text-sm transition-all whitespace-nowrap {{ $activeTab === 'all' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                📦 All Assets
            </button>
            <button wire:click="setTab('mhe')" class="px-4 py-2 rounded-lg font-bold text-sm transition-all whitespace-nowrap {{ $activeTab === 'mhe' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                🚜 MHE Equipment
            </button>
            <button wire:click="setTab('rentals')" class="px-4 py-2 rounded-lg font-bold text-sm transition-all whitespace-nowrap {{ $activeTab === 'rentals' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                💰 Rental Assets
            </button>
            <button wire:click="setTab('maintenance')" class="px-4 py-2 rounded-lg font-bold text-sm transition-all whitespace-nowrap {{ $activeTab === 'maintenance' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                🔧 Maintenance
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Asset Table -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <h3 class="font-bold text-slate-900 text-base">Asset Inventory</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-xs border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-3">Asset</th>
                                <th class="px-6 py-3">Type</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3">PM/Cert</th>
                                <th class="px-6 py-3">Rate</th>
                                <th class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($assets as $asset)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900">{{ $asset->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $asset->identifier }}</div>
                                    @if($asset->isMHE() && $asset->current_engine_hours)
                                    <div class="text-xs text-slate-400">{{ number_format($asset->current_engine_hours, 1) }} hrs</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded text-xs font-bold bg-slate-100 text-slate-700">
                                        {{ ucfirst(str_replace('_', ' ', $asset->type)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded text-xs font-bold
                                        {{ $asset->status === 'available' ? 'bg-emerald-100 text-emerald-700' : 
                                           ($asset->status === 'occupied' ? 'bg-blue-100 text-blue-700' : 
                                           ($asset->status === 'maintenance' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-700')) }}">
                                        {{ ucfirst($asset->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($asset->isMHE() && $asset->isPMDue())
                                    <span class="px-2 py-1 rounded text-xs font-bold bg-rose-100 text-rose-700 animate-pulse">
                                        PM DUE
                                    </span>
                                    @elseif($asset->isMHE() && $asset->next_pm_due_hours)
                                    <div class="w-full bg-slate-100 rounded-full h-2">
                                        <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $asset->getPMProgress() }}%"></div>
                                    </div>
                                    <div class="text-xs text-slate-500 mt-1">{{ number_format($asset->getPMProgress(), 0) }}%</div>
                                    @else
                                    <span class="text-xs text-slate-400">N/A</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs font-bold text-slate-900">RM {{ number_format($asset->rate_per_hour, 2) }}/hr</div>
                                    <div class="text-xs text-slate-500">RM {{ number_format($asset->rate_per_day, 2) }}/day</div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <button wire:click="openMaintenanceModal({{ $asset->id }})" class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all" title="Maintenance">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        </button>
                                        <button wire:click="openAssetModal({{ $asset->id }})" class="p-2 text-slate-400 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-all" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </button>
                                        <button wire:click="deleteAsset({{ $asset->id }})" wire:confirm="Are you sure?" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all" title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400 italic">No assets found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-slate-200">
                    {{ $assets->links() }}
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Pending Approvals -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-200 bg-slate-900 text-white">
                    <h3 class="font-bold uppercase tracking-widest text-xs">Pending Approvals</h3>
                    <p class="text-xs text-slate-400 font-bold uppercase mt-1">Booking requests</p>
                </div>
                <div class="p-6 space-y-4">
                    @forelse($pendingBookings as $booking)
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <p class="text-xs font-bold text-slate-900">{{ $booking->asset->name }}</p>
                                <p class="text-xs text-slate-500">{{ $booking->organization->name ?? 'N/A' }}</p>
                            </div>
                            <span class="text-xs font-bold text-slate-900 bg-white px-2 py-0.5 rounded border border-slate-200">#{{ $booking->reference_no }}</span>
                        </div>
                        <button wire:click="approveBooking({{ $booking->id }})" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white py-2 rounded-xl text-xs font-bold uppercase tracking-widest transition-all shadow-sm">
                            Approve & Deploy
                        </button>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <p class="text-slate-400 font-bold uppercase text-xs tracking-widest">No pending requests</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Active Deployments -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-200 bg-emerald-600 text-white">
                    <h3 class="font-bold uppercase tracking-widest text-xs">Active Deployments</h3>
                    <p class="text-xs text-emerald-100 font-bold uppercase mt-1">Currently on hire</p>
                </div>
                <div class="p-6 space-y-4">
                    @forelse($activeBookings as $booking)
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <p class="text-xs font-bold text-slate-900">{{ $booking->asset->name }}</p>
                                <p class="text-xs text-slate-500">{{ $booking->organization->name ?? 'N/A' }}</p>
                            </div>
                            <span class="text-xs font-bold text-slate-900 bg-white px-2 py-0.5 rounded border border-slate-200">#{{ $booking->reference_no }}</span>
                        </div>
                        <button wire:click="completeBooking({{ $booking->id }})" wire:confirm="Complete this rental?" class="w-full bg-slate-900 hover:bg-slate-800 text-white py-2 rounded-xl text-xs font-bold uppercase tracking-widest transition-all shadow-sm">
                            Check-In & Bill
                        </button>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <p class="text-slate-400 font-bold uppercase text-xs tracking-widest">No active deployments</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Asset Modal -->
    @if($showAssetModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeAssetModal"></div>
        <div class="relative bg-white w-full max-w-4xl rounded-2xl shadow-2xl border border-slate-200 overflow-hidden transform transition-all max-h-[90vh] overflow-y-auto">
            <div class="p-8">
                <h3 class="text-2xl font-bold text-slate-900 mb-6">{{ $editMode ? 'Edit' : 'Register' }} Asset</h3>
                <form wire:submit.prevent="saveAsset">
                    <div class="grid grid-cols-3 gap-4">
                        <div class="col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Asset Name *</label>
                            <input type="text" wire:model="name" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                            @error('name') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Type *</label>
                            <select wire:model="type" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                                <option value="forklift">Forklift</option>
                                <option value="crane">Crane</option>
                                <option value="reach_stacker">Reach Stacker</option>
                                <option value="terminal_tractor">Terminal Tractor</option>
                                <option value="container_handler">Container Handler</option>
                                <option value="warehouse_bay">Warehouse Bay</option>
                                <option value="vehicle">Vehicle</option>
                                <option value="utility">Utility</option>
                            </select>
                            @error('type') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Identifier *</label>
                            <input type="text" wire:model="identifier" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                            @error('identifier') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Asset Code</label>
                            <input type="text" wire:model="asset_code" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Model</label>
                            <input type="text" wire:model="model" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Manufacturer</label>
                            <input type="text" wire:model="manufacturer" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Year</label>
                            <input type="number" wire:model="year" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Location</label>
                            <input type="text" wire:model="location" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Rate/Hour (RM) *</label>
                            <input type="number" wire:model="rate_per_hour" step="0.01" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                            @error('rate_per_hour') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Rate/Day (RM) *</label>
                            <input type="number" wire:model="rate_per_day" step="0.01" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                            @error('rate_per_day') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Status *</label>
                            <select wire:model="status" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                                <option value="available">Available</option>
                                <option value="occupied">Occupied</option>
                                <option value="maintenance">Maintenance</option>
                                <option value="standby">Standby</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Current Hours</label>
                            <input type="number" wire:model="current_engine_hours" step="0.1" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">PM Due Hours</label>
                            <input type="number" wire:model="next_pm_due_hours" step="0.1" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Cert Expiry</label>
                            <input type="date" wire:model="safety_cert_expiry" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                        </div>
                        <div class="col-span-3">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Description</label>
                            <textarea wire:model="description" rows="3" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent"></textarea>
                        </div>
                    </div>
                    <div class="mt-6 flex gap-3">
                        <button type="button" wire:click="closeAssetModal" class="flex-1 px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold transition-all">
                            Cancel
                        </button>
                        <button type="submit" class="flex-1 px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-bold transition-all">
                            {{ $editMode ? 'Update' : 'Register' }} Asset
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Maintenance Modal -->
    @if($showMaintenanceModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeMaintenanceModal"></div>
        <div class="relative bg-white w-full max-w-2xl rounded-2xl shadow-2xl border border-slate-200 overflow-hidden transform transition-all">
            <div class="p-8">
                <h3 class="text-2xl font-bold text-slate-900 mb-6">Maintenance Log - {{ $selectedAsset->name }}</h3>
                <form wire:submit.prevent="saveMaintenance">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Type *</label>
                            <select wire:model="mType" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                                <option value="PM">Preventive Maintenance</option>
                                <option value="Repair">Repair</option>
                                <option value="Inspection">Inspection</option>
                                <option value="Parts">Parts Replacement</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Technician *</label>
                            <input type="text" wire:model="mTechnician" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                            @error('mTechnician') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Description *</label>
                            <textarea wire:model="mDescription" rows="3" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent"></textarea>
                            @error('mDescription') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Performed At *</label>
                            <input type="datetime-local" wire:model="mPerformedAt" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                            @error('mPerformedAt') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Cost (RM) *</label>
                            <input type="number" wire:model="mCost" step="0.01" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                            @error('mCost') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="mt-6 flex gap-3">
                        <button type="button" wire:click="closeMaintenanceModal" class="flex-1 px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold transition-all">
                            Cancel
                        </button>
                        <button type="submit" class="flex-1 px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-bold transition-all">
                            Save Log
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
