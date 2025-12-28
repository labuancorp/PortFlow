<div class="p-6 bg-slate-50 min-h-screen">
    <!-- Soft Pastel Header -->
    <div class="mb-8 bg-gradient-to-br from-cyan-50 to-blue-50 rounded-2xl p-8 border border-cyan-200 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-white rounded-xl shadow-sm">
                    <svg class="w-8 h-8 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 tracking-tight">CCU & Container Tracking</h1>
                    <p class="text-cyan-700/70 text-sm mt-1 font-medium">Yard Inventory & Demurrage Management</p>
                </div>
            </div>
            <div class="flex gap-3">
                <input type="text" wire:model.live="search" placeholder="Search container..." class="px-4 py-2 rounded-xl border-slate-300 focus:border-slate-900 focus:ring-2 focus:ring-slate-900/20">
                <button wire:click="openGateInModal" class="px-6 py-3 bg-slate-900 text-white font-bold rounded-xl hover:bg-slate-800 transition-all shadow-sm">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Gate In
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

    <!-- Pastel Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-cyan-50 to-blue-50 p-6 rounded-2xl border border-cyan-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-white rounded-lg shadow-sm">
                    <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <div class="text-right">
                    <div class="text-xs text-cyan-600/70 font-bold uppercase tracking-widest">Total Units</div>
                    <div class="text-3xl font-black text-cyan-900 tabular-nums">{{ $stats['total'] }}</div>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-rose-50 to-pink-50 p-6 rounded-2xl border border-rose-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-white rounded-lg shadow-sm">
                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="text-right">
                    <div class="text-xs text-rose-600/70 font-bold uppercase tracking-widest">Demurrage</div>
                    <div class="text-3xl font-black text-rose-900 tabular-nums">{{ $stats['demurrage'] }}</div>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-amber-50 to-orange-50 p-6 rounded-2xl border border-amber-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-white rounded-lg shadow-sm">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div class="text-right">
                    <div class="text-xs text-amber-600/70 font-bold uppercase tracking-widest">Expired Certs</div>
                    <div class="text-3xl font-black text-amber-900 tabular-nums">{{ $stats['expired'] }}</div>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-emerald-50 to-teal-50 p-6 rounded-2xl border border-emerald-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-white rounded-lg shadow-sm">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
                <div class="text-right">
                    <div class="text-xs text-emerald-600/70 font-bold uppercase tracking-widest">Yard Util.</div>
                    <div class="text-3xl font-black text-emerald-900 tabular-nums">{{ $stats['yard_util'] }}%</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Container List -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
            <h3 class="font-bold text-slate-900 text-base">Active Containers</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-xs border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3">Container #</th>
                        <th class="px-6 py-3">Type</th>
                        <th class="px-6 py-3">Owner</th>
                        <th class="px-6 py-3">Location</th>
                        <th class="px-6 py-3">Days in Yard</th>
                        <th class="px-6 py-3">Sling Cert</th>
                        <th class="px-6 py-3">Demurrage</th>
                        <th class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($containers as $c)
                    <tr class="hover:bg-slate-50 {{ $c->calc_demurrage['status'] === 'demurrage' ? 'bg-rose-50/50' : '' }}">
                        <td class="px-6 py-4">
                            <div class="font-mono font-bold text-slate-800">{{ $c->container_number }}</div>
                            <div class="text-xs text-slate-400">{{ $c->size }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold {{ $c->type === 'Reefer' ? 'bg-blue-100 text-blue-700' : ($c->type === 'Basket' || $c->type === 'Skip' ? 'bg-purple-100 text-purple-700' : 'bg-slate-100 text-slate-700') }}">
                                {{ $c->type }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $c->owner ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="font-mono text-xs bg-slate-100 px-2 py-1 rounded">{{ $c->location_yard_zone ?? 'N/A' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold {{ $c->calc_demurrage['status'] === 'demurrage' ? 'text-rose-600' : 'text-slate-700' }}">
                                {{ $c->calc_demurrage['days'] ?? 0 }} days
                            </div>
                            @if($c->calc_demurrage['status'] === 'free_period')
                            <div class="text-xs text-emerald-600">{{ $c->calc_demurrage['remaining'] }} free days left</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($c->calc_cert['status'] === 'na')
                                <span class="text-xs text-slate-400">N/A</span>
                            @elseif(!$c->calc_cert['valid'])
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-rose-100 text-rose-700">
                                    ⚠ Expired
                                </span>
                            @elseif($c->calc_cert['status'] === 'expiring_soon')
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-amber-100 text-amber-700">
                                    ⏰ Soon
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-emerald-100 text-emerald-700">
                                    ✓ Valid
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($c->calc_demurrage['status'] === 'demurrage')
                                <div class="font-bold text-rose-600">${{ number_format($c->calc_demurrage['cost'], 2) }}</div>
                                <div class="text-xs text-rose-500">{{ $c->calc_demurrage['over_days'] }} days over</div>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold {{ $c->status === 'in_yard' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700' }}">
                                {{ ucfirst(str_replace('_', ' ', $c->status)) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-slate-400 italic">No containers found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Gate In Modal -->
    @if($showGateInModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm" wire:click.self="closeGateInModal">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl p-8 relative max-h-[90vh] overflow-y-auto border border-slate-200">
            <button wire:click="closeGateInModal" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            
            <h2 class="text-2xl font-bold text-slate-900 mb-6">Gate In Container</h2>
            
            <div class="grid grid-cols-2 gap-6">
                <!-- Container Number -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Container Number *</label>
                    <input type="text" wire:model="container_number" class="w-full rounded-xl border-slate-300 focus:border-slate-900 focus:ring-2 focus:ring-slate-900/20 uppercase" placeholder="e.g., ABCU1234567">
                    @error('container_number') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <!-- Type -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Type *</label>
                    <select wire:model="type" class="w-full rounded-xl border-slate-300 focus:border-slate-900 focus:ring-2 focus:ring-slate-900/20">
                        <option value="Dry">Dry</option>
                        <option value="Reefer">Reefer</option>
                        <option value="Basket">Basket</option>
                        <option value="Skip">Skip</option>
                        <option value="Tank">Tank</option>
                    </select>
                    @error('type') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <!-- Size -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Size *</label>
                    <select wire:model="size" class="w-full rounded-xl border-slate-300 focus:border-slate-900 focus:ring-2 focus:ring-slate-900/20">
                        <option value="20ft">20ft</option>
                        <option value="40ft">40ft</option>
                        <option value="40ft HC">40ft HC</option>
                        <option value="45ft">45ft</option>
                    </select>
                    @error('size') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <!-- Owner -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Owner</label>
                    <input type="text" wire:model="owner" class="w-full rounded-xl border-slate-300 focus:border-slate-900 focus:ring-2 focus:ring-slate-900/20" placeholder="e.g., Maersk">
                    @error('owner') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <!-- Location -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Yard Zone</label>
                    <input type="text" wire:model="location_yard_zone" class="w-full rounded-xl border-slate-300 focus:border-slate-900 focus:ring-2 focus:ring-slate-900/20" placeholder="e.g., A-01-05">
                    @error('location_yard_zone') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <!-- Gate In Date -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Gate In Date/Time *</label>
                    <input type="datetime-local" wire:model="gate_in_date" class="w-full rounded-xl border-slate-300 focus:border-slate-900 focus:ring-2 focus:ring-slate-900/20">
                    @error('gate_in_date') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <!-- Free Days -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Free Days *</label>
                    <input type="number" wire:model="free_days" min="0" class="w-full rounded-xl border-slate-300 focus:border-slate-900 focus:ring-2 focus:ring-slate-900/20" placeholder="e.g., 7">
                    @error('free_days') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <!-- Sling Cert Expiry -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Sling Cert Expiry</label>
                    <input type="date" wire:model="sling_cert_expiry" class="w-full rounded-xl border-slate-300 focus:border-slate-900 focus:ring-2 focus:ring-slate-900/20">
                    @error('sling_cert_expiry') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
            
            <div class="mt-8 flex gap-3">
                <button wire:click="closeGateInModal" class="flex-1 px-6 py-3 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all border border-slate-200">Cancel</button>
                <button wire:click="gateIn" class="flex-1 px-6 py-3 text-sm font-bold text-white bg-slate-900 hover:bg-slate-800 rounded-xl transition-all shadow-sm">
                    Gate In Container
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
