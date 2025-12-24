<div class="p-8 bg-slate-50 min-h-screen font-sans relative">
    <!-- Header -->
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Command Center</h1>
            <p class="text-slate-500 mt-1">Real-time overview of port operations for {{ $now->format('l, d M Y') }}</p>
        </div>
        <div class="flex space-x-3">
            <button type="button" wire:click="exportReport" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-lg hover:bg-slate-50 font-medium shadow-sm flex items-center gap-2 transition-colors">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Export Snapshot
            </button>
            <button type="button" wire:click="logIncident" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium shadow-sm flex items-center gap-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                Log Alert
            </button>
        </div>
    </div>

    <!-- Notification Toast -->
    @if (session()->has('success'))
        <div x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 5000)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 lg:translate-x-full"
             x-transition:enter-end="opacity-100 lg:translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 lg:translate-x-0"
             x-transition:leave-end="opacity-0 lg:translate-x-full"
             class="fixed top-8 right-8 z-[60] bg-slate-900 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-4 border border-slate-700">
            <div class="bg-teal-500/20 p-2 rounded-lg">
                <svg class="w-6 h-6 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <div>
                <h4 class="font-bold text-sm tracking-wide uppercase text-teal-400">System Notification</h4>
                <p class="text-sm text-slate-300">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- KPI Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Card: Vessels Alongside -->
        <a href="{{ route('home') }}" class="group bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden relative">
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em]">Alongside</p>
                    <h3 class="text-4xl font-extrabold text-slate-900 mt-2">{{ $alongsideCount }}</h3>
                </div>
                <div class="p-3 bg-blue-50 text-blue-600 rounded-xl group-hover:bg-blue-600 group-hover:text-white transition-all duration-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs font-bold text-green-500 relative z-10">
                <span class="flex h-2 w-2 rounded-full bg-green-500 mr-2 animate-pulse"></span>
                LIVE BILLING ACTIVE
            </div>
            <div class="absolute -right-4 -bottom-4 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity">
                <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
        </a>

        <!-- Card: Arrivals -->
        <a href="{{ route('home') }}" class="group bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden relative">
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em]">Expected (24h)</p>
                    <h3 class="text-4xl font-extrabold text-slate-900 mt-2">{{ $expectedArrivals }}</h3>
                </div>
                <div class="p-3 bg-amber-50 text-amber-600 rounded-xl group-hover:bg-amber-600 group-hover:text-white transition-all duration-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
             <div class="mt-4 flex items-center text-xs font-bold text-slate-400 relative z-10">
                VIEW TIMELINE →
            </div>
            <div class="absolute -right-4 -bottom-4 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity">
                <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </a>

        <!-- Card: Occupancy -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm overflow-hidden relative">
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em]">Berth Utilization</p>
                    <h3 class="text-4xl font-extrabold text-slate-900 mt-2">{{ $occupancyRate }}%</h3>
                </div>
                <div class="p-3 bg-teal-50 text-teal-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
            </div>
             <div class="mt-4 flex items-center text-xs font-bold text-slate-400 relative z-10 uppercase">
                {{ 100 - $occupancyRate }}% CAPACITY AVAILABLE
            </div>
            <!-- Simple Progress Background -->
            <div class="absolute left-0 bottom-0 h-1.5 bg-slate-100 w-full">
                <div class="h-full bg-teal-500 transition-all duration-1000" style="width: {{ $occupancyRate }}%"></div>
            </div>
        </div>

        <!-- Card: Completed -->
        <a href="{{ route('billing.index') }}" class="group bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden relative">
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em]">Turnaround (MTD)</p>
                    <h3 class="text-4xl font-extrabold text-slate-900 mt-2">{{ $completedThisMonth }}</h3>
                </div>
                <div class="p-3 bg-purple-50 text-purple-600 rounded-xl group-hover:bg-purple-600 group-hover:text-white transition-all duration-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs font-bold text-purple-500 relative z-10">
                PROCEED TO BILLING →
            </div>
             <div class="absolute -right-4 -bottom-4 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity">
                <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Column: Actions -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Pending Bookings Section -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 flex justify-between items-center bg-slate-50/30">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Pending Approvals</h3>
                        <p class="text-xs text-slate-500 mt-1 uppercase tracking-wider font-semibold">Action required for incoming vessels</p>
                    </div>
                    <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-bold ring-4 ring-amber-50">{{ count($pendingRequests) }} REQUESTS</span>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($pendingRequests as $request)
                    <div wire:key="request-{{ $request->id }}" class="p-8 hover:bg-slate-50/80 transition-all flex items-center justify-between group">
                        <div class="flex items-center gap-6">
                            <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-white group-hover:shadow-lg group-hover:text-blue-600 transition-all duration-500">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-slate-800 tracking-tight">{{ $request->vessel->name }}</h4>
                                <div class="flex items-center gap-3 mt-1.5">
                                    <span class="text-[10px] font-extrabold uppercase bg-blue-100 text-blue-700 px-2 py-0.5 rounded-md tracking-wider">{{ $request->vessel->vessel_type }}</span>
                                    <span class="text-xs text-slate-400 font-medium">Agent: <span class="text-slate-600">{{ $request->agent->code }}</span></span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-8">
                            <div class="text-right">
                                <p class="text-[10px] text-slate-400 font-extrabold uppercase tracking-widest mb-1.5">Requested ETA</p>
                                <p class="text-sm font-bold text-slate-700 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ $request->eta->format('d M — H:i') }}
                                </p>
                            </div>
                            <button type="button" wire:click="openReviewModal({{ $request->id }})" class="px-6 py-3 bg-slate-900 text-white text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-blue-600 transition-all duration-300 shadow-lg shadow-slate-200">
                                Review
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="p-16 text-center">
                        <div class="w-16 h-16 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4 grayscale opacity-20">
                            <svg class="w-8 h-8 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        </div>
                        <h3 class="text-slate-900 font-bold">Zero Pending Requests</h3>
                        <p class="text-slate-500 text-sm mt-1">All booking submissions have been processed.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Alerts Grid -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 flex justify-between items-center bg-slate-50/30">
                    <h3 class="text-lg font-bold text-slate-900">Operational Notices</h3>
                    <button type="button" wire:click="toggleAlertsModal" class="text-xs font-bold text-blue-600 hover:text-blue-800 uppercase tracking-widest">Archive History →</button>
                </div>
                <div class="p-2 grid grid-cols-1 md:grid-cols-2 gap-2">
                    @forelse(array_slice($activeAlerts, 0, 4) as $alert)
                    <div wire:key="alert-{{ $alert['id'] }}" class="p-5 rounded-2xl border border-slate-100 hover:border-slate-300 transition-all group relative">
                        <button type="button" wire:click="dismissAlert({{ $alert['id'] }})" class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity p-1 hover:bg-slate-100 rounded">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                        <div class="flex gap-4">
                            <div class="w-2 h-10 {{ $alert['type'] === 'critical' ? 'bg-red-500' : ($alert['type'] === 'warning' ? 'bg-amber-500' : 'bg-blue-500') }} rounded-full flex-shrink-0 mt-1"></div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800">{{ $alert['title'] }}</h4>
                                <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ Str::limit($alert['message'], 80) }}</p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase mt-3 tracking-wider">{{ $alert['time'] }}</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-2 py-8 text-center text-slate-400 italic text-sm">No new notices.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar: Feed & Tools -->
        <div class="space-y-6">
            <!-- Activity Feed -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8">
                <h3 class="text-lg font-bold text-slate-900 mb-8 flex items-center justify-between">
                    System Feed
                    <span class="flex h-2 w-2 rounded-full bg-blue-500"></span>
                </h3>
                <div class="relative border-l-2 border-slate-100 ml-4 space-y-10">
                    @foreach($recentActivity as $activity)
                    <div class="relative pl-8">
                        <div class="absolute -left-[9px] top-1.5 w-4 h-4 rounded-full border-4 border-white shadow-sm
                            {{ $activity->status === 'alongside' ? 'bg-green-500' : 
                               ($activity->status === 'completed' ? 'bg-slate-300' : 
                               ($activity->status === 'requested' ? 'bg-amber-400' : 'bg-blue-500')) 
                            }}"></div>
                        <p class="text-[10px] text-slate-400 font-extrabold uppercase tracking-widest mb-1.5">
                            {{ $activity->updated_at->diffForHumans(null, true, true) }}
                        </p>
                        <p class="text-sm text-slate-800 leading-snug">
                            <span class="font-bold text-slate-900 tracking-tight">{{ $activity->vessel->name }}</span> 
                            <span class="text-slate-500">
                                @if($activity->status === 'alongside') moored at {{ $activity->berth?->name ?? 'Facility' }}
                                @elseif($activity->status === 'requested') submitted NOA
                                @elseif($activity->status === 'completed') dropped lines
                                @else status: {{ $activity->status }}
                                @endif
                            </span>
                        </p>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Pilotage Simulator (The Fancy Part) -->
            <div class="bg-indigo-900 rounded-3xl shadow-2xl p-8 text-white relative overflow-hidden group">
                <div class="absolute -top-12 -right-12 w-48 h-48 bg-indigo-800/10 rounded-full blur-3xl"></div>
                
                <div class="flex items-center justify-between mb-8 relative z-10">
                    <div>
                        <h3 class="text-xl font-black italic tracking-tighter">PILOT-OS</h3>
                        <p class="text-[10px] text-indigo-300 font-bold uppercase tracking-widest mt-0.5">Maritime Guidance</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="flex h-2 w-2 rounded-full {{ $pilotRequested ? 'bg-green-400 animate-pulse' : 'bg-slate-400' }}"></span>
                        <span class="text-[10px] font-bold uppercase">{{ $pilotStatus }}</span>
                    </div>
                </div>

                <div class="space-y-6 relative z-10">
                    @if($pilotRequested)
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-4">
                        <div class="flex justify-between text-xs font-bold mb-3">
                            <span class="text-indigo-200 uppercase tracking-widest">Progress to Berth</span>
                            <span>{{ $pilotProgress }}%</span>
                        </div>
                        <div class="h-2 bg-indigo-950/50 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-indigo-400 to-teal-400 transition-all duration-1000 shadow-[0_0_10px_rgba(79,70,229,0.5)]" 
                                 style="width: {{ $pilotProgress }}%"></div>
                        </div>
                    </div>
                    @endif

                    <div class="grid grid-cols-2 gap-3">
                        <div class="p-3 bg-white/5 rounded-xl border border-white/10">
                            <span class="block text-[10px] text-indigo-300 font-bold uppercase tracking-wider mb-1">Fleet 1</span>
                            <span class="text-xs font-bold">ALPHA 01</span>
                        </div>
                        <div class="p-3 bg-white/5 rounded-xl border border-white/10">
                            <span class="block text-[10px] text-indigo-300 font-bold uppercase tracking-wider mb-1">Fleet 2</span>
                            <span class="text-xs font-bold">READY</span>
                        </div>
                    </div>

                    @if(!$pilotRequested)
                    <button type="button" wire:click="requestPilot" class="w-full py-4 bg-teal-500 hover:bg-teal-400 text-white rounded-2xl font-bold text-xs uppercase tracking-widest transition-all shadow-xl shadow-teal-900/40">
                        Dispatch Pilotage
                    </button>
                    @else
                    <button type="button" wire:click="advancePilotSimulation" class="w-full py-4 bg-white/10 hover:bg-white/20 text-white rounded-2xl font-bold text-xs uppercase tracking-widest border border-white/10 transition-all">
                        {{ $pilotProgress < 100 ? 'Advance Sequence' : 'Reset Simulator' }}
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Review Booking Modal -->
    @if($showReviewModal && $selectedBooking)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="$set('showReviewModal', false)"></div>
        
        <div class="relative bg-white w-full max-w-xl rounded-[2rem] shadow-2xl overflow-hidden border border-slate-200">
            <div class="p-10">
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 text-[10px] font-bold uppercase tracking-widest rounded-md">Port Control Unit</span>
                        <h3 class="text-2xl font-black text-slate-900 mt-2 tracking-tight">Vessel Approval Required</h3>
                    </div>
                    <button type="button" wire:click="$set('showReviewModal', false)" class="p-2 hover:bg-slate-100 rounded-full text-slate-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Vessel Info Card -->
                <div class="bg-slate-900 rounded-3xl p-8 text-white mb-8 shadow-xl relative overflow-hidden">
                    <div class="relative z-10 flex justify-between items-end">
                        <div class="space-y-1">
                            <h4 class="text-2xl font-black tracking-tight">{{ $selectedBooking->vessel->name }}</h4>
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">{{ $selectedBooking->vessel->vessel_type }} — {{ $selectedBooking->agent->name }}</p>
                        </div>
                        <div class="text-right">
                            <span class="block text-[8px] text-slate-500 font-bold uppercase tracking-[.3em] mb-2">Registry</span>
                            <span class="text-sm font-bold bg-white/10 px-3 py-1 rounded-lg border border-white/5">{{ $selectedBooking->vessel->imo_number }}</span>
                        </div>
                    </div>
                    <div class="mt-8 pt-8 border-t border-white/10 grid grid-cols-2 gap-8 relative z-10">
                        <div>
                            <span class="block text-[9px] text-slate-500 font-bold uppercase tracking-widest mb-1.5 leading-none">Total Length</span>
                            <span class="text-lg font-black">{{ $selectedBooking->vessel->loa_meters }}m <span class="text-xs text-slate-500 font-bold italic ml-1">LOA</span></span>
                        </div>
                        <div>
                            <span class="block text-[9px] text-slate-500 font-bold uppercase tracking-widest mb-1.5 leading-none">Max Draft</span>
                            <span class="text-lg font-black text-teal-400">{{ $selectedBooking->vessel->draft_meters }}m <span class="text-xs text-slate-500 font-bold italic ml-1">DEPTH</span></span>
                        </div>
                    </div>
                    <!-- Aesthetic decoration -->
                    <div class="absolute right-0 top-0 w-32 h-32 bg-blue-500/10 blur-[60px]"></div>
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[.2em] mb-3 leading-none">Select Operations Berth</label>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($berths as $berth)
                            <label class="relative cursor-pointer">
                                <input type="radio" wire:model="selectedBerthId" value="{{ $berth->id }}" class="peer sr-only">
                                <div class="px-4 py-4 border-2 border-slate-100 rounded-2xl peer-checked:border-blue-600 peer-checked:bg-blue-50 transition-all hover:bg-slate-50">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-bold text-slate-800">{{ $berth->name }}</span>
                                        <div class="h-3 w-3 rounded-full border-2 border-slate-200 peer-checked:border-blue-600"></div>
                                    </div>
                                    <span class="text-[9px] font-bold text-slate-400 uppercase block mt-1">Ready for ops</span>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @error('selectedBerthId') <span class="block mt-2 text-[10px] font-bold text-red-500 uppercase tracking-widest">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex gap-4 pt-4">
                        <button type="button" wire:click="approveBooking" class="flex-1 bg-slate-900 text-white py-5 rounded-3xl font-black uppercase tracking-widest text-xs hover:bg-blue-600 transition-all shadow-xl shadow-slate-200">
                            Confirm Approval
                        </button>
                        <button type="button" wire:click="rejectBooking" class="px-8 border-2 border-slate-100 rounded-3xl text-sm font-bold text-slate-400 hover:text-red-600 hover:border-red-100 transition-all">
                            Reject
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Archive / Alerts Modal -->
    @if($showAlertsModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="relative bg-white w-full max-w-2xl rounded-3xl shadow-2xl overflow-hidden p-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-slate-900">Alert History Archive</h3>
                <button type="button" wire:click="toggleAlertsModal" class="text-slate-400 hover:text-slate-600 uppercase text-xs font-bold tracking-widest">Close</button>
            </div>
            <div class="space-y-4 max-h-[60vh] overflow-y-auto pr-2 custom-scrollbar">
                @foreach($activeAlerts as $alert)
                    <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-black uppercase tracking-widest {{ $alert['type'] === 'critical' ? 'text-red-500' : 'text-slate-400' }}">{{ $alert['type'] }}</span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase">{{ $alert['time'] }}</span>
                        </div>
                        <h4 class="font-bold text-slate-900 tracking-tight">{{ $alert['title'] }}</h4>
                        <p class="text-sm text-slate-500 mt-1 leading-relaxed">{{ $alert['message'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
