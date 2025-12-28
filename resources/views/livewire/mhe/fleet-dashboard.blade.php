<div class="p-6 bg-slate-50 min-h-screen">
    <!-- Soft Pastel Header -->
    <div class="mb-8 bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl p-8 border border-amber-200 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-white rounded-xl shadow-sm">
                    <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11v-4a2 2 0 00-2-2H7a2 2 0 00-2 2v4M5 21h14a2 2 0 002-2v-5a2 2 0 00-2-2H5a2 2 0 00-2 2v5a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 tracking-tight">MHE Fleet Management</h1>
                    <p class="text-amber-700/70 text-sm mt-1 font-medium">Equipment Optimization & Predictive Maintenance</p>
                </div>
            </div>
            <button wire:click="openEquipmentModal" class="px-6 py-3 bg-slate-900 text-white font-bold rounded-xl hover:bg-slate-800 transition-all shadow-sm">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add Equipment
            </button>
        </div>
    </div>

    <!-- Success Message -->
    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl shadow-sm flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-bold text-sm">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Pastel Analytics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
        <div class="bg-gradient-to-br from-emerald-50 to-teal-50 p-6 rounded-2xl border border-emerald-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-white rounded-lg shadow-sm">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="text-right">
                    <div class="text-xs text-emerald-600/70 font-bold uppercase tracking-widest">Available</div>
                    <div class="text-3xl font-black text-emerald-900 tabular-nums">{{ $stats['available'] }}</div>
                </div>
            </div>
            <div class="text-xs text-emerald-700 font-medium">Ready for deployment</div>
        </div>

        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-6 rounded-2xl border border-blue-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-white rounded-lg shadow-sm">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <div class="text-right">
                    <div class="text-xs text-blue-600/70 font-bold uppercase tracking-widest">Active</div>
                    <div class="text-3xl font-black text-blue-900 tabular-nums">{{ $stats['total'] - $stats['available'] - $stats['maintenance'] }}</div>
                </div>
            </div>
            <div class="text-xs text-blue-700 font-medium">Currently in operation</div>
        </div>

        <div class="bg-gradient-to-br from-amber-50 to-orange-50 p-6 rounded-2xl border border-amber-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-white rounded-lg shadow-sm">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <div class="text-right">
                    <div class="text-xs text-amber-600/70 font-bold uppercase tracking-widest">Maintenance</div>
                    <div class="text-3xl font-black text-amber-900 tabular-nums">{{ $stats['maintenance'] }}</div>
                </div>
            </div>
            <div class="text-xs text-amber-700 font-medium">Scheduled service</div>
        </div>

        <div class="bg-gradient-to-br from-purple-50 to-pink-50 p-6 rounded-2xl border border-purple-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-white rounded-lg shadow-sm">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
                <div class="text-right">
                    <div class="text-xs text-purple-600/70 font-bold uppercase tracking-widest">Utilization</div>
                    <div class="text-3xl font-black text-purple-900 tabular-nums">{{ number_format($stats['utilization'], 0) }}%</div>
                </div>
            </div>
            <div class="text-xs text-purple-700 font-medium">Fleet efficiency</div>
        </div>

        <div class="bg-gradient-to-br from-rose-50 to-pink-50 p-6 rounded-2xl border border-rose-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-white rounded-lg shadow-sm">
                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div class="text-right">
                    <div class="text-xs text-rose-600/70 font-bold uppercase tracking-widest">PM Due</div>
                    <div class="text-3xl font-black text-rose-900 tabular-nums">{{ $stats['breakdown'] }}</div>
                </div>
            </div>
            <div class="text-xs text-rose-700 font-medium">Requires attention</div>
        </div>
    </div>

    <!-- Equipment Fleet Grid -->
    @foreach($equipment->groupBy('type') as $type => $assets)
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-slate-900 flex items-center gap-3">
                    {{ $type }}s
                    <span class="text-slate-400 text-base font-normal">({{ $assets->count() }} units)</span>
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($assets as $asset)
                    @php
                        $statusColors = match($asset->status) {
                            'available' => ['bg' => 'from-emerald-50 to-teal-50', 'border' => 'border-emerald-200', 'badge' => 'bg-emerald-100 text-emerald-700', 'icon' => 'bg-emerald-600'],
                            'in-use' => ['bg' => 'from-blue-50 to-indigo-50', 'border' => 'border-blue-200', 'badge' => 'bg-blue-100 text-blue-700', 'icon' => 'bg-blue-600'],
                            'maintenance' => ['bg' => 'from-amber-50 to-orange-50', 'border' => 'border-amber-200', 'badge' => 'bg-amber-100 text-amber-700', 'icon' => 'bg-amber-600'],
                            'breakdown' => ['bg' => 'from-rose-50 to-pink-50', 'border' => 'border-rose-200', 'badge' => 'bg-rose-100 text-rose-700', 'icon' => 'bg-rose-600'],
                            default => ['bg' => 'from-slate-50 to-slate-100', 'border' => 'border-slate-200', 'badge' => 'bg-slate-100 text-slate-700', 'icon' => 'bg-slate-600'],
                        };
                        
                        $pmAlert = ($asset->current_hour_meter >= $asset->next_pm_due_hours);
                        $pmProgress = min(100, ($asset->current_hour_meter / $asset->next_pm_due_hours) * 100);
                    @endphp
                    
                    <div class="group bg-gradient-to-br {{ $statusColors['bg'] }} p-6 rounded-2xl border {{ $statusColors['border'] }} shadow-sm hover:shadow-md transition-all">
                        <!-- Status Badge -->
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-3 py-1 {{ $statusColors['badge'] }} text-xs uppercase font-bold rounded-full">
                                {{ $asset->status }}
                            </span>
                        </div>
                        
                        <!-- Header -->
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-2 {{ $statusColors['icon'] }} rounded-lg shadow-sm">
                                @if(str_contains($type, 'Forklift'))
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                                @elseif(str_contains($type, 'Crane'))
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11v-4a2 2 0 00-2-2H7a2 2 0 00-2 2v4M5 21h14a2 2 0 002-2v-5a2 2 0 00-2-2H5a2 2 0 00-2 2v5a2 2 0 002 2z"></path></svg>
                                @else
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                @endif
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-slate-900 text-base">{{ $asset->name }}</h3>
                                <p class="text-xs text-slate-500 font-mono">{{ $asset->asset_code }}</p>
                            </div>
                        </div>

                        <!-- Metrics Grid -->
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div class="bg-white/60 rounded-xl p-3 border border-white/40">
                                <div class="text-xs text-slate-600 mb-1 font-medium">Engine Hours</div>
                                <div class="text-xl font-black text-slate-900 tabular-nums">{{ number_format($asset->current_hour_meter) }}</div>
                            </div>
                            <div class="bg-white/60 rounded-xl p-3 border border-white/40">
                                <div class="text-xs text-slate-600 mb-1 font-medium">Location</div>
                                <div class="text-base font-bold text-slate-900 truncate">{{ $asset->location ?? 'N/A' }}</div>
                            </div>
                        </div>

                        <!-- PM Progress -->
                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold text-slate-700">PM Progress</span>
                                <span class="text-xs font-bold {{ $pmAlert ? 'text-rose-600' : 'text-slate-600' }}">
                                    {{ number_format($pmProgress, 0) }}%
                                </span>
                            </div>
                            <div class="w-full bg-white/60 rounded-full h-2 overflow-hidden border border-white/40">
                                <div class="h-2 rounded-full transition-all duration-1000 {{ $pmAlert ? 'bg-rose-500' : 'bg-emerald-500' }}" 
                                     style="width: {{ $pmProgress }}%"></div>
                            </div>
                            @if($pmAlert)
                            <div class="mt-2 px-3 py-2 bg-rose-100 border border-rose-200 rounded-lg">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    <span class="text-xs font-bold text-rose-700">SERVICE REQUIRED</span>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-2">
                            <div class="grid grid-cols-2 gap-2">
                                <button wire:click="viewHistory({{ $asset->id }})" class="px-3 py-2 bg-white text-slate-700 font-bold rounded-lg hover:bg-slate-50 transition-all text-xs border border-slate-200 shadow-sm">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    History
                                </button>
                                @if($asset->status === 'available')
                                <button wire:click="openBooking({{ $asset->id }})" class="px-3 py-2 bg-slate-900 text-white font-bold rounded-lg hover:bg-slate-800 transition-all text-xs shadow-sm">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    Deploy
                                </button>
                                @else
                                <button disabled class="px-3 py-2 bg-slate-100 text-slate-400 font-bold rounded-lg cursor-not-allowed text-xs border border-slate-200">
                                    Busy
                                </button>
                                @endif
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <button wire:click="editEquipment({{ $asset->id }})" class="px-3 py-2 bg-blue-100 text-blue-700 font-bold rounded-lg hover:bg-blue-200 transition-all text-xs border border-blue-200">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Edit
                                </button>
                                <button wire:click="deleteEquipment({{ $asset->id }})" wire:confirm="Are you sure you want to delete this equipment?" class="px-3 py-2 bg-rose-100 text-rose-700 font-bold rounded-lg hover:bg-rose-200 transition-all text-xs border border-rose-200">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    <!-- Modals (Keep existing modal code) -->
    @if($showHistoryModal && $selectedAsset)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm" wire:click.self="closeHistoryModal">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl p-8 relative max-h-[80vh] overflow-y-auto border border-slate-200">
            <button wire:click="closeHistoryModal" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-slate-900 mb-2">Maintenance History</h2>
                <div class="flex items-center gap-3 text-slate-500">
                    <span class="font-bold text-slate-700">{{ $selectedAsset->name }}</span>
                    <span>•</span>
                    <span class="font-mono text-sm">{{ $selectedAsset->asset_code }}</span>
                </div>
            </div>

            @if(count($historyLogs) > 0)
            <div class="space-y-4">
                @foreach($historyLogs as $log)
                <div class="bg-slate-50 border-l-4 {{ $log->type === 'PM' ? 'border-indigo-500' : 'border-rose-500' }} rounded-xl p-4">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <span class="px-3 py-1 {{ $log->type === 'PM' ? 'bg-indigo-100 text-indigo-700' : 'bg-rose-100 text-rose-700' }} text-xs font-bold uppercase rounded-full">{{ $log->type }}</span>
                            <p class="text-slate-800 font-medium mt-2">{{ $log->description ?? 'No description' }}</p>
                            <div class="text-sm text-slate-500 mt-2">Technician: {{ $log->technician_name ?? 'N/A' }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-mono text-slate-500">{{ $log->service_date->format('d M Y') }}</div>
                            @if($log->parts_cost > 0 || $log->labor_cost > 0)
                            <div class="text-lg font-bold text-slate-800 mt-1">${{ number_format($log->parts_cost + $log->labor_cost, 2) }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-12 text-slate-400">
                <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                <p class="text-base font-medium">No maintenance records found.</p>
            </div>
            @endif
        </div>
    </div>
    @endif

    @if($showBookingModal && $selectedAsset)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8 relative border border-slate-200">
            <button wire:click="closeBookingModal" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            
            <h2 class="text-2xl font-bold text-slate-900 mb-6">Deploy Equipment</h2>
            
            <div class="bg-slate-50 p-4 rounded-xl flex items-center gap-4 mb-6 border border-slate-200">
                 <div class="w-12 h-12 rounded-xl bg-slate-900 flex items-center justify-center font-bold text-white text-lg shadow-sm">
                     {{ substr($selectedAsset->type, 0, 1) }}
                 </div>
                 <div>
                     <div class="font-bold text-slate-900">{{ $selectedAsset->name }}</div>
                     <div class="text-sm text-slate-500 font-mono">{{ $selectedAsset->asset_code }}</div>
                 </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Job Type</label>
                    <select wire:model="booking_job_type" class="w-full rounded-xl border-slate-300 focus:border-slate-900 focus:ring-2 focus:ring-slate-900/20">
                        <option>Yard Operation</option>
                        <option>Vessel Loading/Unloading</option>
                        <option>Warehouse Transfer</option>
                        <option>Maintenance Transport</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Start Time</label>
                    <input type="datetime-local" wire:model="booking_start_time" class="w-full rounded-xl border-slate-300 focus:border-slate-900 focus:ring-2 focus:ring-slate-900/20">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Duration (Hours)</label>
                    <input type="number" wire:model="booking_duration" min="1" step="0.5" class="w-full rounded-xl border-slate-300 focus:border-slate-900 focus:ring-2 focus:ring-slate-900/20">
                </div>
            </div>

            <div class="mt-8 flex gap-3">
                <button wire:click="closeBookingModal" class="flex-1 px-6 py-3 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all border border-slate-200">Cancel</button>
                <button wire:click="submitBooking" class="flex-1 px-6 py-3 text-sm font-bold text-white bg-slate-900 hover:bg-slate-800 rounded-xl transition-all shadow-sm">
                    Confirm Deployment
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Equipment CRUD Modal -->
    @if($showEquipmentModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm" wire:click.self="closeEquipmentModal">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl p-8 relative max-h-[90vh] overflow-y-auto border border-slate-200">
            <button wire:click="closeEquipmentModal" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            
            <h2 class="text-2xl font-bold text-slate-900 mb-6">
                {{ $editMode ? 'Edit Equipment' : 'Add New Equipment' }}
            </h2>
            
            <div class="grid grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Equipment Name *</label>
                    <input type="text" wire:model="name" class="w-full rounded-xl border-slate-300 focus:border-slate-900 focus:ring-2 focus:ring-slate-900/20" placeholder="e.g., Forklift Unit 1">
                    @error('name') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <!-- Asset Code -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Asset Code *</label>
                    <input type="text" wire:model="asset_code" class="w-full rounded-xl border-slate-300 focus:border-slate-900 focus:ring-2 focus:ring-slate-900/20" placeholder="e.g., FL-001">
                    @error('asset_code') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <!-- Type -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Equipment Type *</label>
                    <select wire:model="type" class="w-full rounded-xl border-slate-300 focus:border-slate-900 focus:ring-2 focus:ring-slate-900/20">
                        <option value="Forklift">Forklift</option>
                        <option value="Crane">Crane</option>
                        <option value="Reach Stacker">Reach Stacker</option>
                        <option value="Terminal Tractor">Terminal Tractor</option>
                        <option value="Container Handler">Container Handler</option>
                        <option value="Other">Other</option>
                    </select>
                    @error('type') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <!-- Model -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Model</label>
                    <input type="text" wire:model="model" class="w-full rounded-xl border-slate-300 focus:border-slate-900 focus:ring-2 focus:ring-slate-900/20" placeholder="e.g., TCM FD50">
                    @error('model') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <!-- Manufacturer -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Manufacturer</label>
                    <input type="text" wire:model="manufacturer" class="w-full rounded-xl border-slate-300 focus:border-slate-900 focus:ring-2 focus:ring-slate-900/20" placeholder="e.g., Toyota">
                    @error('manufacturer') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <!-- Year -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Year</label>
                    <input type="number" wire:model="year" min="1900" max="{{ date('Y') + 1 }}" class="w-full rounded-xl border-slate-300 focus:border-slate-900 focus:ring-2 focus:ring-slate-900/20" placeholder="e.g., 2020">
                    @error('year') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <!-- Status -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Status *</label>
                    <select wire:model="status" class="w-full rounded-xl border-slate-300 focus:border-slate-900 focus:ring-2 focus:ring-slate-900/20">
                        <option value="available">Available</option>
                        <option value="in-use">In Use</option>
                        <option value="maintenance">Maintenance</option>
                        <option value="breakdown">Breakdown</option>
                    </select>
                    @error('status') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <!-- Location -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Location</label>
                    <input type="text" wire:model="location" class="w-full rounded-xl border-slate-300 focus:border-slate-900 focus:ring-2 focus:ring-slate-900/20" placeholder="e.g., Yard A">
                    @error('location') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <!-- Current Hour Meter -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Current Hour Meter *</label>
                    <input type="number" wire:model="current_hour_meter" min="0" step="0.01" class="w-full rounded-xl border-slate-300 focus:border-slate-900 focus:ring-2 focus:ring-slate-900/20" placeholder="e.g., 1250.5">
                    @error('current_hour_meter') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <!-- Next PM Due Hours -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Next PM Due (Hours) *</label>
                    <input type="number" wire:model="next_pm_due_hours" min="0" step="0.01" class="w-full rounded-xl border-slate-300 focus:border-slate-900 focus:ring-2 focus:ring-slate-900/20" placeholder="e.g., 1500">
                    @error('next_pm_due_hours') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
            
            <div class="mt-8 flex gap-3">
                <button wire:click="closeEquipmentModal" class="flex-1 px-6 py-3 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all border border-slate-200">Cancel</button>
                <button wire:click="saveEquipment" class="flex-1 px-6 py-3 text-sm font-bold text-white bg-slate-900 hover:bg-slate-800 rounded-xl transition-all shadow-sm">
                    {{ $editMode ? 'Update Equipment' : 'Add Equipment' }}
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
