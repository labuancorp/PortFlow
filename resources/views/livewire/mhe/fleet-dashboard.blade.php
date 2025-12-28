<div class="p-6">
    <!-- Header & Stats -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">MHE Fleet Management</h1>
            <p class="text-slate-500">Optimization & Maintenance Tracking</p>
        </div>
        <div class="flex gap-4">
             <!-- Availability Stat -->
            <div class="bg-white px-4 py-2 rounded-lg border border-slate-200 shadow-sm flex items-center gap-3">
                <div class="p-2 bg-emerald-50 rounded-full text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-xs text-slate-400 font-bold uppercase">Available</p>
                    <p class="text-lg font-bold text-slate-700">{{ $stats['available'] }} / {{ $stats['total'] }}</p>
                </div>
            </div>
             <!-- Maintenance Stat -->
            <div class="bg-white px-4 py-2 rounded-lg border border-slate-200 shadow-sm flex items-center gap-3">
                <div class="p-2 bg-amber-50 rounded-full text-amber-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <div>
                    <p class="text-xs text-slate-400 font-bold uppercase">Maintenance</p>
                    <p class="text-lg font-bold text-slate-700">{{ $stats['maintenance'] }}</p>
                </div>
            </div>
        </div>
    </div>

    @foreach($equipment->groupBy('type') as $type => $assets)
        <div class="mb-8">
            <h2 class="text-lg font-bold text-slate-700 mb-4 pl-1 border-l-4 border-indigo-500">{{ $type }}s</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($assets as $asset)
                    @php
                        $statusColors = match($asset->status) {
                            'available' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                            'in-use' => 'bg-blue-100 text-blue-700 border-blue-200',
                            'maintenance' => 'bg-amber-100 text-amber-700 border-amber-200',
                            'breakdown' => 'bg-red-100 text-red-700 border-red-200',
                            default => 'bg-slate-100 text-slate-700 border-slate-200',
                        };
                        
                        // PM Alert
                        $pmAlert = ($asset->current_hour_meter >= $asset->next_pm_due_hours);
                    @endphp
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 transition-all hover:shadow-md">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-500">
                                    @if(str_contains($type, 'Forklift'))
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                                    @elseif(str_contains($type, 'Crane'))
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11v-4a2 2 0 00-2-2H7a2 2 0 00-2 2v4M5 21h14a2 2 0 002-2v-5a2 2 0 00-2-2H5a2 2 0 00-2 2v5a2 2 0 002 2z"></path></svg>
                                    @else
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-800 leading-tight">{{ $asset->name }}</h3>
                                    <p class="text-xs text-slate-400 font-mono">{{ $asset->asset_code }}</p>
                                </div>
                            </div>
                            <span class="text-[10px] uppercase font-bold px-2 py-1 rounded border {{ $statusColors }}">
                                {{ $asset->status }}
                            </span>
                        </div>

                        <div class="space-y-2 text-sm text-slate-600 mb-4 bg-slate-50 p-3 rounded-lg">
                            <div class="flex justify-between">
                                <span class="text-slate-400">Hours</span>
                                <span class="font-mono font-bold">{{ number_format($asset->current_hour_meter) }} h</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400">Next PM</span>
                                <span class="font-mono font-bold {{ $pmAlert ? 'text-red-500 animate-pulse' : 'text-slate-600' }}">
                                    {{ number_format($asset->next_pm_due_hours) }} h
                                </span>
                            </div>
                            @if($pmAlert)
                            <div class="text-[10px] text-red-500 font-bold text-right pt-1">
                                ⚠ SERVICE DUE
                            </div>
                            @endif
                            <div class="flex justify-between pt-2 border-t border-slate-200 mt-2">
                                <span class="text-slate-400">Location</span>
                                <span class="font-medium">{{ $asset->location ?? 'Unknown' }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <button wire:click="viewHistory({{ $asset->id }})" class="px-3 py-1.5 text-xs font-bold text-slate-600 bg-white border border-slate-200 rounded hover:bg-slate-50 transition-colors">
                                History
                            </button>
                            @if($asset->status === 'available')
                            <button wire:click="openBooking({{ $asset->id }})" class="px-3 py-1.5 text-xs font-bold text-white bg-teal-500 rounded hover:bg-teal-600 transition-colors">
                                Book Now
                            </button>
                            @else
                            <button disabled class="px-3 py-1.5 text-xs font-bold text-slate-400 bg-slate-100 rounded cursor-not-allowed">
                                Unavailable
                            </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    <!-- History Modal -->
    @if($showHistoryModal && $selectedAsset)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" wire:click.self="closeHistoryModal">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl p-6 relative max-h-[80vh] overflow-y-auto">
            <button wire:click="closeHistoryModal" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            
            <div class="mb-6">
                <h2 class="text-xl font-bold text-slate-800">Maintenance History</h2>
                <div class="flex items-center gap-2 text-sm text-slate-500 mt-1">
                    <span class="font-bold">{{ $selectedAsset->name }}</span>
                    <span>•</span>
                    <span class="font-mono">{{ $selectedAsset->asset_code }}</span>
                </div>
            </div>

            @if(count($historyLogs) > 0)
            <div class="space-y-4">
                @foreach($historyLogs as $log)
                <div class="border-l-4 border-slate-300 pl-4 py-1">
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="text-xs font-bold uppercase {{ $log->type === 'PM' ? 'text-indigo-600' : 'text-red-500' }}">{{ $log->type }}</span>
                            <p class="text-sm font-medium text-slate-800">{{ $log->description ?? 'No description' }}</p>
                            <div class="text-xs text-slate-500 mt-1">Tech: {{ $log->technician_name ?? 'N/A' }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-xs font-mono text-slate-500">{{ $log->service_date->format('d M Y') }}</div>
                            @if($log->parts_cost > 0 || $log->labor_cost > 0)
                            <div class="text-xs font-bold text-slate-700 mt-1">${{ number_format($log->parts_cost + $log->labor_cost, 2) }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8 text-slate-400">
                <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                <p>No maintenance records found.</p>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Booking Modal -->
    @if($showBookingModal && $selectedAsset)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6 relative">
            <button wire:click="closeBookingModal" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            
            <h2 class="text-xl font-bold text-slate-800 mb-6">Book Equipment</h2>
            
            <div class="bg-slate-50 p-3 rounded-lg flex items-center gap-3 mb-6">
                 <div class="w-10 h-10 rounded bg-white border border-slate-200 flex items-center justify-center font-bold text-slate-600 text-sm">
                     {{ $selectedAsset->type[0] }}
                 </div>
                 <div>
                     <div class="font-bold text-slate-800">{{ $selectedAsset->name }}</div>
                     <div class="text-xs text-slate-500 font-mono">{{ $selectedAsset->asset_code }}</div>
                 </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Job Type</label>
                    <select wire:model="booking_job_type" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        <option>Yard Operation</option>
                        <option>Vessel Loading/Unloading</option>
                        <option>Warehouse Transfer</option>
                        <option>Maintenance Transport</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Start Time</label>
                    <input type="datetime-local" wire:model="booking_start_time" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Duration (Hours)</label>
                    <input type="number" wire:model="booking_duration" min="1" step="0.5" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                </div>
            </div>

            <div class="mt-6 flex gap-3">
                <button wire:click="closeBookingModal" class="flex-1 px-4 py-2 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">Cancel</button>
                <button wire:click="submitBooking" class="flex-1 px-4 py-2 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-lg shadow-indigo-200 transition-colors">
                    Confirm Booking
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
