<div class="p-6 bg-gradient-to-br from-slate-50 to-blue-50 min-h-screen">
    <!-- Premium Header with Animated Background -->
    <div class="mb-8 relative overflow-hidden rounded-3xl bg-gradient-to-r from-amber-500 via-orange-500 to-red-500 p-8 shadow-2xl">
        <div class="absolute inset-0">
            <div class="absolute w-96 h-96 bg-white rounded-full blur-3xl -top-48 -right-48 animate-pulse opacity-20"></div>
            <div class="absolute w-96 h-96 bg-white rounded-full blur-3xl -bottom-48 -left-48 animate-pulse delay-1000 opacity-20"></div>
        </div>
        <div class="relative z-10">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="p-4 bg-white/20 backdrop-blur-lg rounded-2xl border border-white/30 shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11v-4a2 2 0 00-2-2H7a2 2 0 00-2 2v4M5 21h14a2 2 0 002-2v-5a2 2 0 00-2-2H5a2 2 0 00-2 2v5a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <h1 class="text-4xl font-black text-white tracking-tight">MHE Fleet Intelligence</h1>
                        <p class="text-orange-100 text-lg mt-1">AI-Powered Equipment Optimization & Predictive Maintenance</p>
                    </div>
                </div>
                <button class="px-6 py-3 bg-white text-orange-600 font-bold rounded-xl hover:shadow-2xl transition-all transform hover:scale-105">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Quick Dispatch
                </button>
            </div>
        </div>
    </div>

    <!-- Advanced Analytics Dashboard -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-1 border-l-4 border-emerald-500">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="text-right">
                    <div class="text-xs text-slate-400 font-bold uppercase tracking-wider">Available</div>
                    <div class="text-4xl font-black text-slate-800 tabular-nums">{{ $stats['available'] }}</div>
                </div>
            </div>
            <div class="text-sm font-bold text-emerald-600">Ready for deployment</div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-1 border-l-4 border-blue-500">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg animate-pulse">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <div class="text-right">
                    <div class="text-xs text-slate-400 font-bold uppercase tracking-wider">Active</div>
                    <div class="text-4xl font-black text-slate-800 tabular-nums">{{ $stats['total'] - $stats['available'] - $stats['maintenance'] }}</div>
                </div>
            </div>
            <div class="text-sm font-bold text-blue-600">Currently in operation</div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-1 border-l-4 border-amber-500">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <div class="text-right">
                    <div class="text-xs text-slate-400 font-bold uppercase tracking-wider">Maintenance</div>
                    <div class="text-4xl font-black text-slate-800 tabular-nums">{{ $stats['maintenance'] }}</div>
                </div>
            </div>
            <div class="text-sm font-bold text-amber-600">Scheduled service</div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-1 border-l-4 border-purple-500">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
                <div class="text-right">
                    <div class="text-xs text-slate-400 font-bold uppercase tracking-wider">Utilization</div>
                    <div class="text-4xl font-black text-slate-800 tabular-nums">{{ number_format($stats['utilization'], 0) }}%</div>
                </div>
            </div>
            <div class="text-sm font-bold text-purple-600">Fleet efficiency</div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-1 border-l-4 border-red-500">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-gradient-to-br from-red-500 to-rose-600 rounded-xl shadow-lg animate-pulse">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div class="text-right">
                    <div class="text-xs text-slate-400 font-bold uppercase tracking-wider">PM Due</div>
                    <div class="text-4xl font-black text-slate-800 tabular-nums">{{ $stats['breakdown'] }}</div>
                </div>
            </div>
            <div class="text-sm font-bold text-red-600">Requires attention</div>
        </div>
    </div>

    <!-- Equipment Fleet Grid -->
    @foreach($equipment->groupBy('type') as $type => $assets)
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-black text-slate-800 flex items-center gap-3">
                    <div class="w-1 h-8 bg-gradient-to-b from-amber-500 to-orange-600 rounded-full"></div>
                    {{ $type }}s
                    <span class="text-slate-400 text-lg font-normal">({{ $assets->count() }} units)</span>
                </h2>
                <div class="flex gap-2">
                    <button class="px-4 py-2 bg-white text-slate-600 font-bold rounded-lg hover:bg-slate-50 transition-all text-sm border border-slate-200 shadow-sm">
                        Filter
                    </button>
                    <button class="px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-600 text-white font-bold rounded-lg shadow-lg hover:shadow-2xl transition-all text-sm">
                        Schedule PM
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($assets as $asset)
                    @php
                        $statusColors = match($asset->status) {
                            'available' => ['bg' => 'from-emerald-500 to-teal-600', 'text' => 'text-emerald-600', 'border' => 'border-emerald-200', 'badge' => 'bg-emerald-100 text-emerald-700'],
                            'in-use' => ['bg' => 'from-blue-500 to-indigo-600', 'text' => 'text-blue-600', 'border' => 'border-blue-200', 'badge' => 'bg-blue-100 text-blue-700'],
                            'maintenance' => ['bg' => 'from-amber-500 to-orange-600', 'text' => 'text-amber-600', 'border' => 'border-amber-200', 'badge' => 'bg-amber-100 text-amber-700'],
                            'breakdown' => ['bg' => 'from-red-500 to-rose-600', 'text' => 'text-red-600', 'border' => 'border-red-200', 'badge' => 'bg-red-100 text-red-700'],
                            default => ['bg' => 'from-slate-500 to-slate-600', 'text' => 'text-slate-600', 'border' => 'border-slate-200', 'badge' => 'bg-slate-100 text-slate-700'],
                        };
                        
                        $pmAlert = ($asset->current_hour_meter >= $asset->next_pm_due_hours);
                        $pmProgress = min(100, ($asset->current_hour_meter / $asset->next_pm_due_hours) * 100);
                    @endphp
                    
                    <div class="group relative bg-white rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-1 border border-{{ $statusColors['border'] }}">
                        <!-- Status Indicator -->
                        <div class="absolute top-4 right-4">
                            <span class="px-3 py-1 {{ $statusColors['badge'] }} text-[10px] uppercase font-bold rounded-full shadow-sm">
                                {{ $asset->status }}
                            </span>
                        </div>
                        
                        <!-- Header -->
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-3 bg-gradient-to-br {{ $statusColors['bg'] }} rounded-xl shadow-lg">
                                @if(str_contains($type, 'Forklift'))
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                                @elseif(str_contains($type, 'Crane'))
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11v-4a2 2 0 00-2-2H7a2 2 0 00-2 2v4M5 21h14a2 2 0 002-2v-5a2 2 0 00-2-2H5a2 2 0 00-2 2v5a2 2 0 002 2z"></path></svg>
                                @else
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                @endif
                            </div>
                            <div class="flex-1">
                                <h3 class="font-black text-slate-800 text-lg">{{ $asset->name }}</h3>
                                <p class="text-xs text-slate-400 font-mono">{{ $asset->asset_code }}</p>
                            </div>
                        </div>

                        <!-- Metrics Grid -->
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                                <div class="text-xs text-slate-500 mb-1 font-medium">Engine Hours</div>
                                <div class="text-2xl font-black text-slate-800 tabular-nums">{{ number_format($asset->current_hour_meter) }}</div>
                            </div>
                            <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                                <div class="text-xs text-slate-500 mb-1 font-medium">Location</div>
                                <div class="text-lg font-bold text-slate-800">{{ $asset->location ?? 'N/A' }}</div>
                            </div>
                        </div>

                        <!-- PM Progress -->
                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold text-slate-600">PM Progress</span>
                                <span class="text-xs font-bold {{ $pmAlert ? 'text-red-600 animate-pulse' : 'text-slate-500' }}">
                                    {{ number_format($pmProgress, 0) }}%
                                </span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                <div class="h-2 rounded-full transition-all duration-1000 {{ $pmAlert ? 'bg-gradient-to-r from-red-500 to-rose-600 animate-pulse' : 'bg-gradient-to-r from-emerald-500 to-teal-600' }}" 
                                     style="width: {{ $pmProgress }}%"></div>
                            </div>
                            @if($pmAlert)
                            <div class="mt-2 px-3 py-2 bg-red-50 border border-red-200 rounded-lg">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-red-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    <span class="text-xs font-bold text-red-600">SERVICE REQUIRED</span>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="grid grid-cols-2 gap-2">
                            <button wire:click="viewHistory({{ $asset->id }})" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold rounded-lg hover:bg-slate-200 transition-all text-sm border border-slate-200">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                History
                            </button>
                            @if($asset->status === 'available')
                            <button wire:click="openBooking({{ $asset->id }})" class="px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-600 text-white font-bold rounded-lg hover:shadow-lg hover:shadow-amber-500/50 transition-all text-sm">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                Deploy
                            </button>
                            @else
                            <button disabled class="px-4 py-2 bg-slate-100 text-slate-400 font-bold rounded-lg cursor-not-allowed text-sm border border-slate-200">
                                Busy
                            </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    <!-- Modals (Keep existing modal code) -->
    @if($showHistoryModal && $selectedAsset)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm" wire:click.self="closeHistoryModal">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl p-8 relative max-h-[80vh] overflow-y-auto border border-slate-200">
            <button wire:click="closeHistoryModal" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            
            <div class="mb-6">
                <h2 class="text-3xl font-black text-slate-800 mb-2">Maintenance History</h2>
                <div class="flex items-center gap-3 text-slate-500">
                    <span class="font-bold text-slate-700">{{ $selectedAsset->name }}</span>
                    <span>•</span>
                    <span class="font-mono">{{ $selectedAsset->asset_code }}</span>
                </div>
            </div>

            @if(count($historyLogs) > 0)
            <div class="space-y-4">
                @foreach($historyLogs as $log)
                <div class="bg-slate-50 border-l-4 {{ $log->type === 'PM' ? 'border-indigo-500' : 'border-red-500' }} rounded-xl p-4">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <span class="px-3 py-1 {{ $log->type === 'PM' ? 'bg-indigo-100 text-indigo-700' : 'bg-red-100 text-red-700' }} text-xs font-bold uppercase rounded-full">{{ $log->type }}</span>
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
                <p class="text-lg">No maintenance records found.</p>
            </div>
            @endif
        </div>
    </div>
    @endif

    @if($showBookingModal && $selectedAsset)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8 relative border border-slate-200">
            <button wire:click="closeBookingModal" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            
            <h2 class="text-3xl font-black text-slate-800 mb-6">Deploy Equipment</h2>
            
            <div class="bg-slate-50 p-4 rounded-xl flex items-center gap-4 mb-6 border border-slate-200">
                 <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center font-black text-white text-lg shadow-lg">
                     {{ substr($selectedAsset->type, 0, 1) }}
                 </div>
                 <div>
                     <div class="font-black text-slate-800">{{ $selectedAsset->name }}</div>
                     <div class="text-sm text-slate-500 font-mono">{{ $selectedAsset->asset_code }}</div>
                 </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Job Type</label>
                    <select wire:model="booking_job_type" class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/50">
                        <option>Yard Operation</option>
                        <option>Vessel Loading/Unloading</option>
                        <option>Warehouse Transfer</option>
                        <option>Maintenance Transport</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Start Time</label>
                    <input type="datetime-local" wire:model="booking_start_time" class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/50">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Duration (Hours)</label>
                    <input type="number" wire:model="booking_duration" min="1" step="0.5" class="w-full rounded-xl border-slate-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/50">
                </div>
            </div>

            <div class="mt-8 flex gap-3">
                <button wire:click="closeBookingModal" class="flex-1 px-6 py-3 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all border border-slate-200">Cancel</button>
                <button wire:click="submitBooking" class="flex-1 px-6 py-3 text-sm font-bold text-white bg-gradient-to-r from-amber-500 to-orange-600 hover:shadow-2xl hover:shadow-amber-500/50 rounded-xl transition-all">
                    Confirm Deployment
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
