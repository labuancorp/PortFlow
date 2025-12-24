<div class="min-h-screen bg-slate-50 font-sans">
    <!-- Top Navigation (Client View) -->
    <div class="bg-white border-b border-slate-200 sticky top-0 z-30">
        <div class="max-w-5xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-black text-lg">P</div>
                <h1 class="font-bold text-slate-900 tracking-tight">PortFlow <span class="text-slate-400 font-normal">Connect</span></h1>
            </div>
            <div class="flex items-center gap-4">
                @if($agent)
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Organization</p>
                        <p class="text-sm font-bold text-slate-900">{{ $agent->name }}</p>
                    </div>
                    <div class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center text-slate-500 font-bold border border-slate-200">
                        {{ substr($agent->name, 0, 2) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-5xl mx-auto px-6 py-8">
        
        <!-- Welcome Hero -->
        <div class="mb-10">
            <h2 class="text-3xl font-black text-slate-900 tracking-tight mb-2">Fleet Overview</h2>
            <p class="text-slate-500 text-lg">Track your vessels in real-time across the port.</p>
        </div>

        <!-- Dashboard Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-indigo-600 rounded-2xl p-6 text-white shadow-xl shadow-indigo-900/20 relative overflow-hidden group">
                <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 bg-white/10 rounded-full blur-xl group-hover:bg-white/20 transition-all"></div>
                <p class="text-indigo-200 text-sm font-bold uppercase tracking-widest mb-1">Live Vessels</p>
                <div class="flex items-end gap-2">
                    <span class="text-5xl font-black">{{ $portCalls->whereIn('status', ['alongside', 'anchored'])->count() }}</span>
                    <span class="text-indigo-200 font-medium mb-1">In Port</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm relative overflow-hidden">
                 <p class="text-slate-400 text-sm font-bold uppercase tracking-widest mb-1">Incoming</p>
                <div class="flex items-end gap-2">
                    <span class="text-5xl font-black text-slate-800">{{ $portCalls->whereIn('status', ['requested', 'approved'])->count() }}</span>
                    <span class="text-slate-400 font-medium mb-1">Expected 24h</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm relative overflow-hidden flex flex-col justify-center items-center gap-3 cursor-pointer hover:border-teal-400 hover:shadow-md transition-all group">
                <div class="w-12 h-12 bg-teal-50 rounded-full flex items-center justify-center text-teal-600 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </div>
                <span class="font-bold text-teal-600">Request New Berth</span>
            </div>
        </div>

        <!-- Tabs -->
        <div class="flex gap-6 border-b border-slate-200 mb-8">
            <button wire:click="$set('activeTab', 'live')" class="pb-3 text-sm font-bold uppercase tracking-wider transition-all {{ $activeTab === 'live' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-slate-400 hover:text-slate-600' }}">
                Live Operations
            </button>
            <button wire:click="$set('activeTab', 'scheduled')" class="pb-3 text-sm font-bold uppercase tracking-wider transition-all {{ $activeTab === 'scheduled' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-slate-400 hover:text-slate-600' }}">
                Scheduled
            </button>
            <button wire:click="$set('activeTab', 'history')" class="pb-3 text-sm font-bold uppercase tracking-wider transition-all {{ $activeTab === 'history' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-slate-400 hover:text-slate-600' }}">
                History
            </button>
        </div>

        <!-- Cards List -->
        <div class="space-y-4">
            @forelse($portCalls as $call)
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-lg transition-all group relative overflow-hidden">
                    <!-- Status Strip -->
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 
                        {{ $call->status === 'alongside' ? 'bg-green-500' : 
                           ($call->status === 'anchored' ? 'bg-amber-500' : 
                           ($call->status === 'completed' ? 'bg-slate-300' : 'bg-indigo-500')) }}"></div>

                    <div class="pl-4 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                        <!-- Vessel Info -->
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center font-bold text-lg
                                {{ $call->status === 'alongside' ? 'bg-green-50 text-green-600' : 'bg-slate-50 text-slate-500' }}">
                                {{ substr($call->vessel->name, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="font-bold text-lg text-slate-900">{{ $call->vessel->name }}</h3>
                                <p class="text-sm text-slate-500">{{ $call->vessel->vessel_type }} • {{ $call->vessel->imo_number }}</p>
                            </div>
                        </div>

                        <!-- Timeline Visual -->
                        <div class="flex-1 w-full md:w-auto px-4">
                             <div class="flex items-center justify-between text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">
                                <span>ETA</span>
                                <span class="{{ $call->status === 'alongside' ? 'text-green-600 animate-pulse' : '' }}">{{ $call->status }}</span>
                                <span>ETD</span>
                             </div>
                             <div class="h-2 bg-slate-100 rounded-full overflow-hidden relative">
                                 <!-- Progress Bar Logic -->
                                 @php
                                    $progress = 0;
                                    if ($call->status === 'alongside' && $call->atb) {
                                        $duration = $call->atb->diffInMinutes($call->etd, false);
                                        $elapsed = $call->atb->diffInMinutes(now(), false);
                                        $progress = $duration > 0 ? min(100, max(5, ($elapsed / $duration) * 100)) : 0;
                                    } elseif ($call->status === 'completed') {
                                        $progress = 100;
                                    }
                                 @endphp
                                 <div class="absolute left-0 top-0 bottom-0 bg-indigo-500 transition-all duration-1000" style="width: {{ $progress }}%"></div>
                             </div>
                        </div>

                        <!-- Actions / Info -->
                        <div class="text-right min-w-[120px]">
                            @if($call->status === 'alongside')
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Berth</p>
                                <p class="font-bold text-slate-900">{{ $call->berth->name ?? 'Unassigned' }}</p>
                                @if($call->invoice)
                                     <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] bg-green-100 text-green-700 font-bold border border-green-200">
                                        Active Billing
                                     </span>
                                @endif
                            @else
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Time</p>
                                <p class="font-bold text-slate-900">{{ $call->eta->format('M d, H:i') }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Expanded Details (Hover or Click in real app) -->
                    <div class="mt-4 pt-4 border-t border-slate-50 flex gap-4 text-xs text-slate-500">
                         <div class="flex gap-2 items-center">
                            <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            {{ $call->berth->name ?? 'Berth Pending' }}
                         </div>
                         <div class="flex gap-2 items-center">
                            <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Ref: {{ $call->reference_no }}
                         </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 bg-white rounded-2xl border border-dashed border-slate-300">
                    <div class="inline-flex justify-center items-center w-16 h-16 rounded-full bg-slate-50 text-slate-300 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">No Vessels Found</h3>
                    <p class="text-slate-500">You don't have any {{ $activeTab }} bookings at the moment.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
