<div class="p-8 bg-slate-50 min-h-screen font-sans relative" 
     x-data="{ 
        toast: { show: false, message: '', type: 'success' },
        showPrerequisiteModal: false,
        showToast(message, type = 'success') {
            this.toast = { show: true, message, type };
            setTimeout(() => { this.toast.show = false; }, 4000);
        },
        draggingId: null,
        dragStartX: 0,
        handleDragStart(event, id) {
            this.draggingId = id;
            event.dataTransfer.effectAllowed = 'move';
            event.dataTransfer.setData('text/plain', id);
            event.target.style.opacity = '0.5';
        },
        handleDragEnd(event) {
            this.draggingId = null;
            event.target.style.opacity = '1';
        },
        handleDragOver(event) {
            event.preventDefault();
            event.dataTransfer.dropEffect = 'move';
        },
        handleDrop(event, berthId) {
            event.preventDefault();
            event.stopPropagation();
            const bookingId = event.dataTransfer.getData('text/plain');
            if (!bookingId) return;
            const rect = event.currentTarget.getBoundingClientRect();
            const x = event.clientX - rect.left;
            const width = rect.width;
            const percentage = Math.max(0, Math.min(1, x / width));
            // Send percentage (0-1) to backend instead of minutes
            $wire.updateSchedule(bookingId, berthId, percentage);
        }
     }"
     @schedule-error.window="showToast($event.detail.message, 'error')"
     @schedule-success.window="showToast($event.detail.message, 'success')"
     @show-prerequisite-modal.window="showPrerequisiteModal = true">

    <!-- Header & Tools -->
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Berth Planner</h1>
            <p class="text-slate-500 mt-1">Operational Schedule for <span class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($dateFilter)->format('l, d M Y') }}</span></p>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex bg-slate-200/50 p-1 rounded-xl">
                 <button wire:click="setViewMode('day')" class="px-4 py-2 rounded-lg text-xs font-bold transition-all {{ $viewMode === 'day' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">Day</button>
                 <button wire:click="setViewMode('week')" class="px-4 py-2 rounded-lg text-xs font-bold transition-all {{ $viewMode === 'week' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">Week</button>
                 <button wire:click="setViewMode('month')" class="px-4 py-2 rounded-lg text-xs font-bold transition-all {{ $viewMode === 'month' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">Month</button>
                 <button wire:click="setViewMode('quarter')" class="px-4 py-2 rounded-lg text-xs font-bold transition-all {{ $viewMode === 'quarter' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">Quarter</button>
            </div>
            
            <div class="flex bg-white rounded-xl shadow-sm border border-slate-200 p-1">
                <button type="button" wire:click="$set('dateFilter', '{{ \Carbon\Carbon::parse($dateFilter)->subDay()->format('Y-m-d') }}')" class="p-2 hover:bg-slate-50 rounded-lg text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <div class="px-4 py-2 border-x border-slate-100 flex items-center gap-2">
                    <input type="date" wire:model.live="dateFilter" class="border-0 p-0 text-sm font-bold text-slate-700 focus:ring-0 cursor-pointer">
                </div>
                <button type="button" wire:click="$set('dateFilter', '{{ \Carbon\Carbon::parse($dateFilter)->addDay()->format('Y-m-d') }}')" class="p-2 hover:bg-slate-50 rounded-lg text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </div>
            <button type="button" wire:click="optimizeSchedule" class="px-5 py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-bold text-sm shadow-lg shadow-indigo-900/20 flex items-center gap-2 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                Smart Optimize
            </button>
            <button type="button" wire:click="openCreateModal" class="px-5 py-3 bg-teal-600 hover:bg-teal-500 text-white rounded-xl font-bold text-sm shadow-lg shadow-teal-900/20 flex items-center gap-2 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                New Booking
            </button>
        </div>
    </div>
      
    <!-- Planner Container -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden relative">
        <!-- Time Axis -->
        <div class="flex border-b border-slate-100 bg-slate-50/50">
            <div class="w-64 flex-shrink-0 p-4 border-r border-slate-100 bg-slate-50">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Asset / Facility</span>
            </div>
            <div class="flex-1 relative h-12">
                <div class="absolute inset-0 flex">
                    @if($viewMode === 'day')
                        @for($hour = 0; $hour < 24; $hour++)
                            <div class="flex-1 border-r border-slate-100 {{ $hour % 3 === 0 ? 'border-slate-200 bg-slate-50/20' : '' }} flex items-end justify-center pb-2 group relative">
                                 @if($hour % 3 === 0)
                                <span class="text-[10px] font-bold text-slate-400 group-hover:text-slate-600">{{ sprintf('%02d:00', $hour) }}</span>
                                @endif
                            </div>
                        @endfor
                    @elseif($viewMode === 'week')
                        @for($i = 0; $i < 7; $i++)
                             <div class="flex-1 border-r border-slate-100 flex items-end justify-center pb-2">
                                <span class="text-[10px] font-bold text-slate-500 uppercase">{{ $windowStart->copy()->addDays($i)->format('D d') }}</span>
                            </div>
                        @endfor
                    @elseif($viewMode === 'month')
                         @for($i = 0; $i < 30; $i++)
                             <div class="flex-1 border-r border-slate-100 flex items-end justify-center pb-2">
                                @if($i % 5 == 0)
                                <span class="text-[9px] font-bold text-slate-400">{{ $windowStart->copy()->addDays($i)->format('d') }}</span>
                                @endif
                            </div>
                        @endfor
                    @elseif($viewMode === 'quarter')
                         @for($i = 0; $i < 13; $i++)
                             <div class="flex-1 border-r border-slate-100 flex items-end justify-center pb-2">
                                <span class="text-[9px] font-bold text-slate-400">W{{ $windowStart->copy()->addWeeks($i)->format('W') }}</span>
                            </div>
                        @endfor
                    @endif
                </div>
                <!-- Precision Indicator -->
                <div class="absolute bottom-0 left-0 w-full h-[1px] bg-indigo-500/20"></div>
            </div>
        </div>

        <!-- Berths Rows -->
        <div class="divide-y divide-slate-100">
            @foreach($berths as $berth)
            <div class="flex h-40 group relative">
                <!-- Left: Berth Info -->
                <div class="w-64 flex-shrink-0 bg-white border-r border-slate-100 p-6 flex flex-col justify-center relative z-20 group-hover:bg-slate-50 transition-colors">
                    <div class="flex items-center justify-between mb-2">
                         <span class="w-2 h-2 rounded-full bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.6)]"></span>
                         <span class="text-[9px] font-bold text-slate-300 uppercase tracking-widest">{{ $berth->code ?? 'B-' . $berth->id }}</span>
                    </div>
                    <div class="bg-slate-900 text-white px-3 py-1.5 rounded-lg w-fit mb-2 shadow-md">
                        <span class="text-sm font-bold tracking-tight">{{ $berth->name }}</span>
                    </div>
                    <div class="flex gap-4 mt-2">
                        <div>
                            <span class="block text-[8px] text-slate-400 font-bold uppercase tracking-wider">Max LOA</span>
                            <span class="text-xs font-bold text-slate-700">{{ $berth->max_loa }}m</span>
                        </div>
                        <div>
                            <span class="block text-[8px] text-slate-400 font-bold uppercase tracking-wider">Depth</span>
                            <span class="text-xs font-bold text-teal-600">{{ $berth->max_draft }}m</span>
                        </div>
                    </div>
                </div>

                     <div class="flex-1 relative bg-slate-50/10 group-hover:bg-slate-50/30 transition-colors"
                     :class="{ 'bg-indigo-50/30': draggingId !== null }"
                     @if(auth()->user()->role !== 'agent')
                     @dragover.prevent="handleDragOver($event)"
                     @dragenter.prevent="$el.classList.add('bg-indigo-100/50')"
                     @dragleave="$el.classList.remove('bg-indigo-100/50')"
                     @drop="handleDrop($event, {{ $berth->id }}); $el.classList.remove('bg-indigo-100/50')"
                     @endif
                     >
                    
                    <!-- Background Grid -->
                    <div class="absolute inset-0 flex pointer-events-none">
                        @for($hour = 0; $hour < 24; $hour++)
                            <div class="flex-1 border-r border-slate-100 {{ $hour % 3 === 0 ? 'border-dashed border-slate-200' : '' }}"></div>
                        @endfor
                    </div>

                    <!-- Current Time Line (Only if today) -->
                    @if(\Carbon\Carbon::parse($dateFilter)->isToday())
                    <div class="absolute top-0 bottom-0 border-r-2 border-red-500 border-dashed z-0 pointer-events-none opacity-60"
                         style="left: {{ (now()->diffInMinutes(now()->startOfDay()) / 1440) * 100 }}%">
                        <div class="absolute top-0 -right-1.5 w-3 h-3 bg-red-500 rounded-full shadow-sm"></div>
                    </div>
                    @endif

                    <!-- Drop Zone Hint -->
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 pointer-events-none transition-opacity" :class="{ 'opacity-100': draggingId !== null }">
                         <span class="text-sm font-bold text-indigo-300 uppercase tracking-widest border-2 border-dashed border-indigo-200 px-4 py-2 rounded-xl bg-white/50">Release to Schedule</span>
                    </div>

                    <!-- Bookings -->
                    @foreach($berth->portCalls as $call)
                        <div class="absolute top-4 bottom-4 rounded-xl border p-1 shadow-sm hover:shadow-xl transition-all z-10 group/card overflow-hidden
                            {{ $call->status === 'approved' ? 'bg-gradient-to-br from-indigo-500 to-blue-600 border-indigo-400/50 text-white' : 
                               ($call->status === 'alongside' ? 'bg-gradient-to-br from-green-500 to-teal-600 border-green-400/50 text-white' : 
                               ($call->status === 'completed' ? 'bg-slate-100 border-slate-200 text-slate-500' : 
                               'bg-amber-100 border-amber-200 text-amber-800')) }}
                            {{ auth()->user()->role !== 'agent' ? 'cursor-grab active:cursor-grabbing' : 'cursor-default' }}"
                             style="{{ $this->calculateStyle($call) }}"
                             draggable="{{ auth()->user()->role !== 'agent' ? 'true' : 'false' }}"
                             wire:key="call-{{ $call->id }}"
                             @if(auth()->user()->role !== 'agent')
                             @dragstart="handleDragStart($event, {{ $call->id }})"
                             @dragend="handleDragEnd($event)"
                             @endif
                             wire:click="viewBooking({{ $call->id }})">
                            
                            <!-- Card Content -->
                            <div class="h-full w-full px-3 py-2 flex flex-col justify-between relative">
                                <!-- Glass Overlay -->
                                <div class="absolute inset-0 bg-white/5 group-hover/card:bg-white/10 transition-colors"></div>
                                
                                <div class="relative z-10">
                                    <div class="flex justify-between items-start">
                                        <span class="font-bold text-xs truncate leading-tight">
                                            @if(auth()->user()->role === 'agent' && $call->agent_id !== auth()->user()->organization_id)
                                                Occupied Slot
                                            @else
                                                {{ $call->vessel->name }}
                                            @endif
                                        </span>
                                        @if($call->status === 'completed')
                                            <svg class="w-3 h-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        @endif
                                    </div>
                                    <p class="text-[10px] font-medium opacity-80 truncate mt-0.5">
                                        @if(auth()->user()->role === 'agent' && $call->agent_id !== auth()->user()->organization_id)
                                            --
                                        @else
                                            {{ $call->vessel->vessel_type }}
                                        @endif
                                    </p>
                                </div>

                                <div class="relative z-10 flex justify-between items-end">
                                    <div class="bg-black/10 px-1.5 py-0.5 rounded text-[9px] font-bold font-mono">
                                        {{ $call->eta ? $call->eta->format('Hi') : '' }}-{{ $call->etd ? $call->etd->format('Hi') : '' }}
                                    </div>
                                    <span class="text-[8px] uppercase tracking-wider font-bold opacity-70">{{ $call->agent ? $call->agent->code : '' }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Notification Toast (Reused from Dashboard style) -->
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
    
    <!-- New Booking Modal -->
    @if($showCreateModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="$set('showCreateModal', false)"></div>
        <div class="relative bg-white w-full max-w-xl rounded-[2rem] shadow-2xl border border-slate-200 overflow-hidden">
            <div class="p-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight">New Vessel Arrival</h3>
                    <button type="button" wire:click="$set('showCreateModal', false)" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <form wire:submit="saveBooking" class="space-y-6">
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                             <div>
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">Select Vessel</label>
                                <select wire:model="newVesselId" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-700 focus:ring-teal-500 focus:border-teal-500">
                                    <option value="">Select...</option>
                                    @foreach($vessels as $v)
                                        <option value="{{ $v->id }}">{{ $v->name }} ({{ $v->vessel_type }})</option>
                                    @endforeach
                                </select>
                                @error('newVesselId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                             </div>
                             <div>
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">Shipping Agent</label>
                                <select wire:model="newAgentId" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-700 focus:ring-teal-500 focus:border-teal-500">
                                    <option value="">Select...</option>
                                    @foreach($agents as $a)
                                        <option value="{{ $a->id }}">{{ $a->name }}</option>
                                    @endforeach
                                </select>
                                @error('newAgentId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                             </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">ETA (Arrival)</label>
                                <input type="datetime-local" wire:model="newEta" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-700 focus:ring-teal-500 focus:border-teal-500">
                                @error('newEta') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">ETD (Departure)</label>
                                <input type="datetime-local" wire:model="newEtd" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-700 focus:ring-teal-500 focus:border-teal-500">
                                @error('newEtd') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Smart Berth Suggestion -->
                        <div class="bg-indigo-50/50 p-4 rounded-xl border border-indigo-100">
                             <div class="flex justify-between items-center mb-3">
                                 <label class="block text-[10px] font-bold uppercase tracking-widest text-indigo-500">Optimization Engine</label>
                                 <button type="button" wire:click="generateRecommendations" class="text-xs bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-lg font-bold transition-colors flex items-center gap-1">
                                     <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                     Find Best Slot
                                 </button>
                             </div>
                             
                             @if($searchStatus)
                                <p class="text-xs text-slate-500 mb-2">{{ $searchStatus }}</p>
                             @endif

                             @if(count($recommendedBerths) > 0)
                                <div class="grid grid-cols-2 gap-2 mb-3">
                                    @foreach($recommendedBerths as $rec)
                                        <div wire:click="$set('newBerthId', {{ $rec['berth']['id'] }})" 
                                             class="cursor-pointer p-2 rounded-lg border transition-all relative overflow-hidden group
                                             {{ $newBerthId == $rec['berth']['id'] ? 'bg-indigo-600 border-indigo-600 text-white' : 'bg-white border-slate-200 hover:border-indigo-300 text-slate-600' }}">
                                            
                                            <!-- Fit Score Badge -->
                                            <div class="absolute top-0 right-0 bg-indigo-500 text-white text-[8px] font-bold px-1.5 py-0.5 rounded-bl-lg opacity-0 group-hover:opacity-100 transition-opacity">
                                                Fit Score: {{ $rec['score'] }}
                                            </div>

                                            <div class="font-bold text-sm">{{ $rec['berth']['name'] }}</div>
                                            <div class="text-[10px] opacity-80 mt-0.5 flex gap-2">
                                                <span>LOA: +{{ number_format($rec['loa_diff'], 1) }}m</span>
                                                <span>Draft: +{{ number_format($rec['draft_diff'], 1) }}m</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                             @endif
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">Pre-Assign Berth (Optional)</label>
                            <select wire:model="newBerthId" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-700 focus:ring-teal-500 focus:border-teal-500">
                                <option value="">Auto-Assign Later (Unassigned)</option>
                                @foreach($berths as $b)
                                    <option value="{{ $b->id }}">{{ $b->name }}</option>
                                @endforeach
                            </select>
                            @error('newBerthId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex gap-3">
                        <button type="button" wire:click="$set('showCreateModal', false)" class="px-6 py-3 rounded-xl border border-slate-200 font-bold text-slate-500 hover:bg-slate-50">Cancel</button>
                        <button type="submit" class="flex-1 px-6 py-3 bg-teal-600 hover:bg-teal-500 text-white rounded-xl font-bold uppercase tracking-widest shadow-lg shadow-teal-900/20">
                            Submit Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Booking Details Modal -->
    @if($bookingToView)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="closeBooking"></div>
        <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-lg overflow-hidden transform transition-all relative" @click.stop>
            @livewire('view-booking', ['booking' => $bookingToView], key($bookingToView))
        </div>
    </div>
    @endif

    <!-- Prerequisite Setup Modal -->
    <template x-if="showPrerequisiteModal">
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showPrerequisiteModal = false"></div>
            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all relative z-10">
                <div class="p-8">
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-black text-slate-900 mb-2">Setup Required</h3>
                        <p class="text-slate-600">Complete these steps before booking a berth</p>
                    </div>

                    <div class="space-y-3 mb-6">
                        <div class="flex items-start gap-3 p-3 bg-green-50 border border-green-200 rounded-xl">
                            <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <div class="flex-1">
                                <p class="font-bold text-sm text-slate-800">Organization Profile</p>
                                <p class="text-xs text-slate-600">Your profile is complete</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-3 bg-red-50 border border-red-200 rounded-xl">
                            <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                            <div class="flex-1">
                                <p class="font-bold text-sm text-slate-800">Vessel Registration</p>
                                <p class="text-xs text-slate-600">You need to register at least one vessel</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button @click="showPrerequisiteModal = false" class="flex-1 px-4 py-3 border border-slate-200 rounded-xl font-bold text-slate-600 hover:bg-slate-50 transition-colors">
                            Cancel
                        </button>
                        <a href="{{ route('vessels.index') }}" class="flex-1 px-4 py-3 bg-teal-600 hover:bg-teal-700 text-white rounded-xl font-bold text-center transition-colors">
                            Register Vessel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
