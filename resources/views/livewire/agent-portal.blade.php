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
                <div class="h-8 w-px bg-slate-200 mx-2"></div>
                <a href="{{ route('logout') }}" class="text-xs font-bold text-slate-500 hover:text-indigo-600 uppercase tracking-widest transition-colors flex items-center gap-1">
                    Sign Out <span aria-hidden="true">&rarr;</span>
                </a>
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

            <button wire:click="openRequestModal" class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm relative overflow-hidden flex flex-col justify-center items-center gap-3 cursor-pointer hover:border-teal-400 hover:shadow-md transition-all group">
                <div class="w-12 h-12 bg-teal-50 rounded-full flex items-center justify-center text-teal-600 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </div>
                <span class="font-bold text-teal-600">Request New Berth</span>
            </button>
        </div>

        <!-- Quick Actions -->
        <div class="flex justify-end mb-4">
             <button wire:click="openVesselModal" class="text-sm font-bold text-indigo-600 hover:text-indigo-500 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Register New Vessel to Fleet
            </button>
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

    <!-- New Berth Request Modal -->
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" wire:click="$set('showModal', false)"></div>
        <div class="relative bg-white w-full max-w-lg rounded-3xl shadow-2xl border border-slate-200 overflow-hidden transform transition-all">
            <div class="p-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight">Request New Berth</h3>
                    <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form wire:submit.prevent="saveRequest" class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">Select Vessel</label>
                        <select wire:model="vessel_id" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-700 focus:ring-teal-500 focus:border-teal-500 p-3">
                            <option value="">-- Choose Vessel --</option>
                            @foreach($myVessels as $vessel)
                                <option value="{{ $vessel->id }}">{{ $vessel->name }} ({{ $vessel->vessel_type }})</option>
                            @endforeach
                        </select>
                        @error('vessel_id') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">ETA (Local)</label>
                            <input type="datetime-local" wire:model="eta" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-700 focus:ring-teal-500 focus:border-teal-500 p-3">
                            @error('eta') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">ETD (Local)</label>
                            <input type="datetime-local" wire:model="etd" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-700 focus:ring-teal-500 focus:border-teal-500 p-3">
                            @error('etd') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- AI Smart Suggest Button --}}
                    <div class="flex justify-center">
                        <button type="button" wire:click="getSmartSuggestions" class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white rounded-xl font-bold uppercase tracking-widest shadow-lg shadow-indigo-900/30 transition-all flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                            🤖 Get Smart Suggestions
                        </button>
                    </div>

                    {{-- AI Berth Suggestions --}}
                    @if($showSuggestions && !empty($berthSuggestions))
                    <div class="space-y-3">
                        <h4 class="text-sm font-bold text-slate-700 uppercase tracking-wider">AI Recommendations</h4>
                        
                        @foreach($berthSuggestions as $index => $suggestion)
                        <div wire:click="selectSuggestedBerth({{ $suggestion['berth']->id }})" 
                             class="p-4 rounded-xl border-2 cursor-pointer transition-all
                                {{ $selectedSuggestedBerth == $suggestion['berth']->id ? 'border-indigo-500 bg-indigo-50' : 'border-slate-200 hover:border-indigo-300' }}
                                {{ !$suggestion['available'] ? 'opacity-60' : '' }}">
                            
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    @if($index == 0 && $suggestion['available'])
                                        <span class="text-2xl">🏆</span>
                                    @elseif($suggestion['available'])
                                        <span class="text-2xl">✅</span>
                                    @else
                                        <span class="text-2xl">❌</span>
                                    @endif
                                    <div>
                                        <h5 class="font-bold text-slate-900">{{ $suggestion['berth']->name }}</h5>
                                        <p class="text-xs text-slate-500">{{ $suggestion['berth']->code }} • Max LOA: {{ $suggestion['berth']->max_loa }}m • Max Draft: {{ $suggestion['berth']->max_draft }}m</p>
                                    </div>
                                </div>
                                
                                @if($suggestion['available'])
                                <div class="text-right">
                                    <div class="text-2xl font-black 
                                        {{ $suggestion['score'] >= 90 ? 'text-green-600' : ($suggestion['score'] >= 75 ? 'text-blue-600' : 'text-slate-600') }}">
                                        {{ $suggestion['score'] }}
                                    </div>
                                    <div class="text-[10px] font-bold uppercase tracking-wider
                                        {{ $suggestion['confidence'] == 'very_high' ? 'text-green-600' : ($suggestion['confidence'] == 'high' ? 'text-blue-600' : 'text-slate-500') }}">
                                        {{ str_replace('_', ' ', $suggestion['confidence']) }}
                                    </div>
                                </div>
                                @endif
                            </div>
                            
                            <div class="space-y-1">
                                @foreach($suggestion['reasons'] as $reason)
                                <p class="text-xs text-slate-600">{{ $reason }}</p>
                                @endforeach
                            </div>
                            
                            @if(!empty($suggestion['conflicts']))
                            <div class="mt-2 p-2 bg-red-50 rounded-lg">
                                <p class="text-xs font-bold text-red-700 mb-1">Conflicts:</p>
                                @foreach($suggestion['conflicts'] as $conflict)
                                <p class="text-xs text-red-600">• {{ $conflict['vessel'] }} ({{ $conflict['eta'] }} - {{ $conflict['etd'] }})</p>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <div class="bg-blue-50 p-4 rounded-xl flex gap-3 items-start">
                        <svg class="w-5 h-5 text-blue-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-xs text-blue-700 font-medium">
                            @if($showSuggestions && $selectedSuggestedBerth)
                                AI has selected the optimal berth for you. Click Submit to confirm.
                            @else
                                Click "Get Smart Suggestions" to let AI recommend the best berth for your vessel.
                            @endif
                        </p>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex gap-3">
                         <button type="button" wire:click="$set('showModal', false)" class="px-6 py-3 rounded-xl border border-slate-200 font-bold text-slate-500 hover:bg-slate-50 transition-colors">Cancel</button>
                         <button type="submit" class="flex-1 px-6 py-3 bg-teal-600 hover:bg-teal-500 text-white rounded-xl font-bold uppercase tracking-widest shadow-lg shadow-teal-900/20 transition-all">
                            Submit Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Register Vessel Modal -->
    @if($showVesselModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" wire:click="$set('showVesselModal', false)"></div>
        <div class="relative bg-white w-full max-w-lg rounded-3xl shadow-2xl border border-slate-200 overflow-hidden transform transition-all">
            <div class="p-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight">Register New Vessel</h3>
                    <button wire:click="$set('showVesselModal', false)" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form wire:submit.prevent="saveVessel" class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">Vessel Name</label>
                        <input type="text" wire:model="new_vessel_name" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-700 focus:ring-teal-500 focus:border-teal-500 p-3" placeholder="e.g. MV SEALINK 178" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">IMO Number</label>
                            <input type="text" wire:model="new_vessel_imo" class="w-full bg-slate-50 border-slate-200 rounded-xl font-mono font-bold text-slate-700 focus:ring-teal-500 focus:border-teal-500 p-3" placeholder="9123456" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">Type</label>
                            <select wire:model="new_vessel_type" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-700 focus:ring-teal-500 focus:border-teal-500 p-3">
                                <option>Offshore Support Vessel</option>
                                <option>Tug</option>
                                <option>Barge</option>
                                <option>Landing Craft</option>
                                <option>Tanker</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                         <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">LOA (Meters)</label>
                            <input type="number" step="0.1" wire:model="new_vessel_loa" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-700 focus:ring-teal-500 focus:border-teal-500 p-3" placeholder="0.0 m" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">Max Draft (Meters)</label>
                            <input type="number" step="0.1" wire:model="new_vessel_draft" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-700 focus:ring-teal-500 focus:border-teal-500 p-3" placeholder="0.0 m" required>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex gap-3">
                         <button type="button" wire:click="$set('showVesselModal', false)" class="px-6 py-3 rounded-xl border border-slate-200 font-bold text-slate-500 hover:bg-slate-50 transition-colors">Cancel</button>
                         <button type="submit" class="flex-1 px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-bold uppercase tracking-widest shadow-lg shadow-indigo-900/20 transition-all">
                            Add Vessel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Toast Component -->
    <div x-data="{ toast: { show: false, message: '' }, showToast(msg) { this.toast = { show: true, message: msg }; setTimeout(() => this.toast.show = false, 3000); } }"
         @notify.window="showToast($event.detail.message)"
         class="fixed bottom-4 right-4 z-[70]"
         style="display: none;"
         x-show="toast.show"
         x-transition.duration.300ms>
        <div class="bg-indigo-600 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center justify-center gap-3 font-bold">
            <span class="text-2xl">📨</span>
            <span x-text="toast.message"></span>
        </div>
    </div>
