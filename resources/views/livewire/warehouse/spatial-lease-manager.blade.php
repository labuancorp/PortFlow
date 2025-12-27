<div class="p-8" x-data="{ 
    toast: { show: false, message: '', type: 'success' },
    showToast(message, type = 'success') {
        this.toast = { show: true, message, type };
        setTimeout(() => { this.toast.show = false; }, 3000);
    }
 }" @notify.window="showToast($event.detail.message, $event.detail.type || 'success')">

    <!-- Notification Toast -->
    <template x-if="toast.show">
        <div class="fixed top-8 right-8 z-[60] bg-slate-900 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-4 border border-slate-700"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-8"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 translate-x-8">
            <div :class="toast.type === 'error' ? 'bg-red-500/20 text-red-400' : 'bg-teal-500/20 text-teal-400'" class="p-2 rounded-lg">
                <svg x-show="toast.type === 'success'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <svg x-show="toast.type === 'error'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h4 class="font-bold text-sm tracking-wide uppercase" :class="toast.type === 'error' ? 'text-red-400' : 'text-teal-400'" x-text="toast.type === 'error' ? 'Error' : 'Success'"></h4>
                <p class="text-sm text-slate-300" x-text="toast.message"></p>
            </div>
        </div>
    </template>

    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Spatial Lease Manager</h1>
            <p class="text-slate-500 mt-2">Manage square-foot occupancy and yard footprints for projects.</p>
        </div>
        <button wire:click="openLeaseModal" class="bg-indigo-600 hover:bg-indigo-500 text-white px-6 py-3 rounded-xl font-bold flex items-center gap-2 shadow-lg shadow-indigo-900/20 transition-all transform active:scale-95">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
             Execute Spatial Lease
        </button>
    </div>

    <!-- Portfolio Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 text-slate-50 group-hover:text-slate-100 transition-colors">
                <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path></svg>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 relative z-10">Total Leased Footprint</p>
            <h3 class="text-2xl font-black text-slate-900 relative z-10">{{ number_format($stats['leased_area']) }} <span class="text-xs text-slate-400">SQM</span></h3>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 text-indigo-50 group-hover:text-indigo-100 transition-colors">
                <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 relative z-10">Monthly Base Rent (Est)</p>
            <h3 class="text-2xl font-black text-slate-900 relative z-10">RM {{ number_format($stats['total_revenue'], 2) }}</h3>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 text-emerald-50 group-hover:text-emerald-100 transition-colors">
                <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 relative z-10">Active Yard Occupancy</p>
            <h3 class="text-2xl font-black text-slate-900 relative z-10">{{ $stats['active_leases'] }} <span class="text-xs text-slate-400">Zones</span></h3>
        </div>
    </div>

    <!-- Active Leases Table -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 class="font-black text-slate-900 uppercase tracking-widest text-xs text-slate-400">Current Occupancy Portfolio</h3>
        </div>
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-100 text-[10px] uppercase tracking-wider text-slate-500 font-bold">
                    <th class="px-6 py-4">Reference / Org</th>
                    <th class="px-6 py-4">Zone / Warehouse</th>
                    <th class="px-6 py-4">Footprint (SQM)</th>
                    <th class="px-6 py-4">Rate & Yield</th>
                    <th class="px-6 py-4">Timeline</th>
                    <th class="px-6 py-4">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($leases as $lease)
                <tr class="hover:bg-slate-50 transition-all group">
                    <td class="px-6 py-5">
                        <p class="text-xs font-black text-slate-900">#{{ $lease->reference_no }}</p>
                        <p class="text-[10px] font-bold text-slate-400 uppercase">{{ $lease->organization->name }}</p>
                    </td>
                    <td class="px-6 py-5">
                        <p class="text-xs font-black text-indigo-600 uppercase">{{ $lease->zone->name }}</p>
                        <p class="text-[10px] font-bold text-slate-400 uppercase">{{ $lease->zone->warehouse->name }}</p>
                    </td>
                    <td class="px-6 py-5">
                        <div class="flex items-center gap-2">
                             <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400">
                                 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
                             </div>
                             <p class="text-sm font-black text-slate-900">{{ number_format($lease->leased_area_sqm) }} sqm</p>
                        </div>
                    </td>
                    <td class="px-6 py-5 text-right font-mono">
                        <div class="text-right">
                             <p class="text-xs font-black text-slate-900">RM {{ number_format($lease->rate_per_sqm * $lease->leased_area_sqm, 2) }}</p>
                             <p class="text-[9px] text-slate-400 font-bold uppercase">RM {{ $lease->rate_per_sqm }}/sqm monthly</p>
                        </div>
                    </td>
                    <td class="px-6 py-5">
                        <div class="space-y-1">
                            <p class="text-[10px] font-black text-slate-900 uppercase tracking-tight">From: {{ $lease->start_date->format('d M Y') }}</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase">Until: {{ $lease->end_date->format('d M Y') }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-5">
                        <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase border
                            {{ $lease->status === 'active' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-slate-50 text-slate-400 border-slate-100' }}">
                            {{ $lease->status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-400 font-bold uppercase tracking-widest text-xs">No spatial leases found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $leases->links() }}
        </div>
    </div>

    <!-- Spatial Lease Modal -->
    @if($showLeaseModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-md" wire:click="$set('showLeaseModal', false)"></div>
        <div class="relative bg-white w-full max-w-2xl rounded-3xl shadow-2xl border border-white/20 overflow-hidden transform transition-all animate-in zoom-in duration-200">
            <div class="p-8">
                <div class="flex justify-between items-start mb-8">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white flex items-center justify-center shadow-xl shadow-indigo-900/20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                        </div>
                        <div>
                             <h3 class="text-2xl font-black text-slate-900 tracking-tight">Spatial Lease Issuance</h3>
                             <p class="text-sm text-slate-500 mt-1">Legitimize yard footprint for third-party operations.</p>
                        </div>
                    </div>
                    <button wire:click="$set('showLeaseModal', false)" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form wire:submit.prevent="saveLease" class="space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2 md:col-span-1">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Organization (Leasee)</label>
                            <select wire:model="organization_id" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-900 text-sm p-3 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                <option value="">Select Account...</option>
                                @foreach($organizations as $org)
                                    <option value="{{ $org->id }}">{{ $org->name }} ({{ $org->code }})</option>
                                @endforeach
                            </select>
                            @error('organization_id') <span class="text-red-500 text-[10px] font-bold mt-1 block uppercase">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Target Zone</label>
                            <select wire:model="warehouse_zone_id" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-900 text-sm p-3 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                <option value="">Select Zone...</option>
                                @foreach($zones as $zone)
                                    <option value="{{ $zone->id }}">{{ $zone->name }} ({{ $zone->warehouse->name }})</option>
                                @endforeach
                            </select>
                            @error('warehouse_zone_id') <span class="text-red-500 text-[10px] font-bold mt-1 block uppercase">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 p-6 bg-slate-900 rounded-3xl text-white">
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Rented Footprint (SQM)</label>
                            <input type="number" wire:model.live="leased_area_sqm" placeholder="0.00" class="w-full bg-white/10 border-white/10 rounded-xl font-black text-white text-xl p-4 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                            @error('leased_area_sqm') <span class="text-red-400 text-[10px] font-bold mt-1 block uppercase">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">SQM Rate (RM/Mo)</label>
                            <input type="number" wire:model.live="rate_per_sqm" placeholder="0.00" class="w-full bg-white/10 border-white/10 rounded-xl font-black text-indigo-400 text-xl p-4 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                            @error('rate_per_sqm') <span class="text-red-400 text-[10px] font-bold mt-1 block uppercase">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-span-2 mt-4 pt-4 border-t border-white/5 flex justify-between items-center px-2">
                             <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Estimated Monthly Yield</p>
                             <p class="text-2xl font-black text-indigo-400">RM {{ number_format(($leased_area_sqm ?? 0) * ($rate_per_sqm ?? 0), 2) }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Commencement Date</label>
                            <input type="date" wire:model="start_date" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-900 text-sm p-3 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Maturity Date</label>
                            <input type="date" wire:model="end_date" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-900 text-sm p-3 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100 flex gap-4">
                        <button type="button" wire:click="$set('showLeaseModal', false)" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-500 py-4 rounded-2xl font-black uppercase tracking-widest transition-all">
                            Decline
                        </button>
                        <button type="submit" class="flex-[2] bg-indigo-600 hover:bg-indigo-500 text-white py-4 rounded-2xl font-black uppercase tracking-[0.2em] shadow-xl shadow-indigo-900/20 transition-all active:scale-[0.98]">
                            Establish Lease Agreement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
