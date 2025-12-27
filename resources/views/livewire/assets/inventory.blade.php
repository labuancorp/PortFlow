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
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Facility & Asset Control</h1>
            <p class="text-slate-500 mt-2">Manage port-owned heavy machinery, storage bays, and metered rentals.</p>
        </div>
        <button wire:click="openAssetModal" class="bg-slate-900 hover:bg-slate-800 text-white px-6 py-3 rounded-xl font-bold flex items-center gap-2 shadow-lg shadow-slate-900/20 transition-all transform active:scale-95">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
             Register New Asset
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Asset Inventory Table -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="font-black text-slate-900 uppercase tracking-widest text-xs">Asset Inventory</h3>
                </div>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100 text-[10px] uppercase tracking-wider text-slate-500 font-bold">
                            <th class="px-6 py-4">Resource</th>
                            <th class="px-6 py-4">ID / Type</th>
                            <th class="px-6 py-4">Current Owner</th>
                            <th class="px-6 py-4">Rate (Hr/Day)</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($assets as $asset)
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            <td class="px-6 py-5">
                                <p class="text-sm font-black text-slate-900">{{ $asset->name }}</p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase truncate max-w-[200px]">{{ $asset->description }}</p>
                            </td>
                            <td class="px-6 py-5">
                                <p class="text-xs font-bold text-slate-700">{{ $asset->identifier }}</p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">{{ $asset->type }}</p>
                            </td>
                            <td class="px-6 py-5">
                                @if($asset->currentBooking)
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded bg-indigo-50 flex items-center justify-center text-[10px] font-black text-indigo-600 border border-indigo-100">
                                            {{ substr($asset->currentBooking->organization->name, 0, 1) }}
                                        </div>
                                        <p class="text-[10px] font-black text-slate-700 uppercase">{{ $asset->currentBooking->organization->name }}</p>
                                    </div>
                                @else
                                    <span class="text-[10px] font-bold text-slate-300 uppercase tracking-widest">Unassigned</span>
                                @endif
                            </td>
                            <td class="px-6 py-5">
                                <div class="space-y-1">
                                    <p class="text-xs font-black text-slate-900">RM {{ number_format($asset->rate_per_hour, 2) }}<span class="text-[9px] text-slate-400 ml-1">/hr</span></p>
                                    <p class="text-[10px] font-bold text-slate-500">RM {{ number_format($asset->rate_per_day, 2) }}<span class="text-[9px] text-slate-400 ml-1">/day</span></p>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase border
                                    {{ $asset->status === 'available' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 
                                       ($asset->status === 'occupied' ? 'bg-amber-50 text-amber-600 border-amber-100' : 
                                       'bg-red-50 text-red-600 border-red-100') }}">
                                    {{ $asset->status }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-right flex justify-end gap-2">
                                <button wire:click="openMaintenanceModal({{ $asset->id }})" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all" title="Maintenance Log">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </button>
                                <button wire:click="openAssetModal({{ $asset->id }})" class="p-2 text-slate-400 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400 font-black uppercase tracking-widest text-xs">No assets registered.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $assets->links() }}
                </div>
            </div>
        </div>

        <!-- Pending Approvals Sidebar -->
        <div class="space-y-6">
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-slate-900 text-white">
                    <h3 class="font-black uppercase tracking-widest text-xs">Awaiting Approval</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase mt-1">Metered resource requests</p>
                </div>
                <div class="p-6 space-y-4">
                    @forelse($pendingBookings as $booking)
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 group transition-all hover:border-slate-300">
                        <div class="flex justify-between items-start mb-2">
                             <div>
                                <p class="text-xs font-black text-slate-900">{{ $booking->asset->name }}</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase">{{ $booking->organization->name }}</p>
                             </div>
                             <span class="text-[9px] font-black text-slate-900 bg-white px-2 py-0.5 rounded border border-slate-200">#{{ $booking->reference_no }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-[10px] text-slate-500 mb-4">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            {{ $booking->start_time->format('d M, H:i') }}
                        </div>
                        <button wire:click="approveBooking({{ $booking->id }})" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-lg shadow-emerald-900/10">
                            Authorize & Deploy
                        </button>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <p class="text-slate-400 font-bold uppercase text-[10px] tracking-widest">No pending requests</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Commercial Summary Card -->
            <div class="bg-indigo-600 rounded-3xl shadow-xl shadow-indigo-900/20 p-6 text-white">
                <p class="text-[10px] font-black uppercase tracking-[0.2em] mb-4 opacity-60">Asset Utilization</p>
                <div class="space-y-4">
                    <div class="flex justify-between items-end">
                        <p class="text-xs font-bold">Active Rentals</p>
                        <p class="text-2xl font-black">{{ $assets->where('status', 'occupied')->count() }}</p>
                    </div>
                    <div class="flex justify-between items-end">
                        <p class="text-xs font-bold">Maintenance Queue</p>
                        <p class="text-2xl font-black text-indigo-200">{{ $assets->where('status', 'maintenance')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Asset Registration Modal -->
    @if($showAssetModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="$set('showAssetModal', false)"></div>
        <div class="relative bg-white w-full max-w-xl rounded-3xl shadow-2xl border border-white/20 overflow-hidden transform transition-all animate-in zoom-in duration-200">
            <div class="p-8">
                <div class="flex justify-between items-start mb-8">
                    <div>
                         <h3 class="text-2xl font-black text-slate-900 tracking-tight">{{ $assetId ? 'Edit Resource' : 'Register New Resource' }}</h3>
                         <p class="text-sm text-slate-500 mt-1">Configure technical IDs and commercial rates.</p>
                    </div>
                    <button wire:click="$set('showAssetModal', false)" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form wire:submit.prevent="saveAsset" class="space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Asset Common Name</label>
                            <input type="text" wire:model="name" placeholder="e.g. Crawler Crane 100T" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-900 text-sm p-3 focus:ring-slate-500 focus:border-slate-500 transition-all">
                            @error('name') <span class="text-red-500 text-[10px] font-bold mt-1 block uppercase">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Resource Category</label>
                            <select wire:model="type" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-700 text-sm p-3 focus:ring-slate-500 focus:border-slate-500 transition-all">
                                <option value="crane">Crane</option>
                                <option value="forklift">Forklift</option>
                                <option value="warehouse_bay">Warehouse Bay</option>
                                <option value="vehicle">Service Vehicle</option>
                                <option value="utility">Utility Supply</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Technical Identifier</label>
                            <input type="text" wire:model="identifier" placeholder="e.g. CN-001" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-900 text-sm p-3 focus:ring-slate-500 focus:border-slate-500 transition-all">
                            @error('identifier') <span class="text-red-500 text-[10px] font-bold mt-1 block uppercase">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Rate Per Hour (RM)</label>
                            <input type="number" wire:model="rate_per_hour" step="0.01" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-900 text-sm p-3 focus:ring-slate-500 focus:border-slate-500 transition-all">
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Rate Per Day (RM)</label>
                            <input type="number" wire:model="rate_per_day" step="0.01" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-900 text-sm p-3 focus:ring-slate-500 focus:border-slate-500 transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Operational Status</label>
                        <select wire:model="status" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-700 text-sm p-3 focus:ring-slate-500 focus:border-slate-500 transition-all">
                            <option value="available">Available / Idle</option>
                            <option value="maintenance">Under Maintenance</option>
                            <option value="occupied">Occupied / On Hire</option>
                            <option value="standby">Reserve / Standby</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Asset Description</label>
                        <textarea wire:model="description" rows="3" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-900 text-sm p-3 focus:ring-slate-500 focus:border-slate-500 transition-all"></textarea>
                    </div>

                    <div class="pt-6 border-t border-slate-100">
                        <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white py-4 rounded-2xl font-black uppercase tracking-[0.2em] shadow-xl shadow-slate-900/20 transition-all active:scale-[0.98]">
                            {{ $assetId ? 'Apply Configuration' : 'Establish Asset Record' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Maintenance Record Modal -->
    @if($showMaintenanceModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="$set('showMaintenanceModal', false)"></div>
        <div class="relative bg-white w-full max-w-4xl rounded-3xl shadow-2xl border border-white/20 overflow-hidden transform transition-all animate-in zoom-in duration-200">
            <div class="flex flex-col md:flex-row h-[600px]">
                <!-- History Sidebar -->
                <div class="w-full md:w-80 bg-slate-50 border-r border-slate-200 p-6 overflow-y-auto">
                    <h4 class="font-black text-slate-900 uppercase tracking-widest text-[10px] mb-6">Technical Dossier</h4>
                    <div class="space-y-4">
                        @forelse($selectedAsset->maintenanceLogs as $log)
                        <div class="p-3 bg-white rounded-xl border border-slate-200 shadow-sm">
                            <div class="flex justify-between items-start mb-1">
                                <span class="text-[9px] font-black uppercase text-indigo-600 px-1.5 py-0.5 bg-indigo-50 rounded">{{ $log->type }}</span>
                                <p class="text-[9px] font-bold text-slate-400">{{ $log->performed_at->format('d M Y') }}</p>
                            </div>
                            <p class="text-[10px] font-bold text-slate-700 leading-tight mb-2">{{ $log->description }}</p>
                            <div class="flex justify-between items-center text-[9px] text-slate-400 font-bold border-t border-slate-50 pt-2">
                                <span>{{ $log->technician_name }}</span>
                                <span class="text-slate-900">RM {{ $log->cost }}</span>
                            </div>
                        </div>
                        @empty
                        <p class="text-center py-12 text-[10px] font-bold text-slate-400 uppercase tracking-widest">No prior service logs.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Log Entry Form -->
                <div class="flex-1 p-8 overflow-y-auto">
                    <div class="flex justify-between items-start mb-8">
                        <div>
                             <h3 class="text-2xl font-black text-slate-900 tracking-tight">Post-Service Record</h3>
                             <p class="text-sm text-slate-500 mt-1">Log technical intervention for **{{ $selectedAsset->identifier }}**.</p>
                        </div>
                        <button wire:click="$set('showMaintenanceModal', false)" class="text-slate-400 hover:text-slate-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <form wire:submit.prevent="saveMaintenance" class="space-y-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Intervention Type</label>
                                <select wire:model="mType" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-700 text-sm p-3 focus:ring-slate-500 focus:border-slate-500 transition-all">
                                    <option value="Routine">Routine Service</option>
                                    <option value="Repair">Major Repair</option>
                                    <option value="Inspection">Safety Inspection</option>
                                    <option value="Parts">Parts Replacement</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Technician / Vendor</label>
                                <input type="text" wire:model="mTechnician" placeholder="e.g. Caterpillar Malaysia" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-900 text-sm p-3 focus:ring-slate-500 focus:border-slate-500 transition-all">
                                @error('mTechnician') <span class="text-red-500 text-[10px] font-bold mt-1 block uppercase">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Technical Observations</label>
                            <textarea wire:model="mDescription" rows="4" placeholder="Detailed engineering notes..." class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-900 text-sm p-3 focus:ring-slate-500 focus:border-slate-500 transition-all"></textarea>
                            @error('mDescription') <span class="text-red-500 text-[10px] font-bold mt-1 block uppercase">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Date Performed</label>
                                <input type="datetime-local" wire:model="mPerformedAt" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-900 text-xs p-3 focus:ring-slate-500 focus:border-slate-500 transition-all">
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Next Service Due</label>
                                <input type="datetime-local" wire:model="mNextDue" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-900 text-xs p-3 focus:ring-slate-500 focus:border-slate-500 transition-all">
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Repair Cost (RM)</label>
                                <input type="number" wire:model="mCost" step="0.01" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-900 text-xs p-3 focus:ring-slate-500 focus:border-slate-500 transition-all">
                            </div>
                        </div>

                        <div class="pt-6 border-t border-slate-100">
                            <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white py-4 rounded-2xl font-black uppercase tracking-[0.2em] shadow-xl shadow-slate-900/20 transition-all active:scale-[0.98]">
                                Commit to Ledger
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
