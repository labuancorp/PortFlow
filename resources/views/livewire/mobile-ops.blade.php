<div class="min-h-screen bg-slate-900 text-white font-sans pb-20">
    <!-- Mobile Header -->
    <div class="bg-indigo-600 px-6 py-4 sticky top-0 z-50 shadow-lg">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="font-black text-xl tracking-tighter">PortFlow <span class="text-indigo-300 font-normal">Ops</span></h1>
                <p class="text-[10px] font-bold uppercase tracking-widest text-indigo-300">Ground Crew Unit</p>
            </div>
            <div class="w-8 h-8 rounded-full bg-indigo-500 border border-indigo-400 flex items-center justify-center font-bold text-xs">
                GC
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="px-2 pt-2 bg-slate-900 sticky top-[64px] z-40 pb-2">
        <div class="flex bg-slate-800 p-1 rounded-xl">
            <button wire:click="$set('tab', 'vessels')" 
                class="flex-1 py-2 rounded-lg text-sm font-bold uppercase tracking-wide transition-all {{ $tab === 'vessels' ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-400 hover:text-white' }}">
                Marine
            </button>
            <button wire:click="$set('tab', 'assets')" 
                class="flex-1 py-2 rounded-lg text-sm font-bold uppercase tracking-wide transition-all {{ $tab === 'assets' ? 'bg-amber-500 text-slate-900 shadow-lg' : 'text-slate-400 hover:text-white' }}">
                Assets <span class="ml-1 text-[10px] bg-slate-700 text-white px-1.5 py-0.5 rounded-full">{{ $assetTasks->count() }}</span>
            </button>
        </div>
    </div>

    <!-- Active Tasks Stream (VESSELS) -->
    @if($tab === 'vessels')
    <div class="pb-20"> <!-- Tab Wrapper -->
        <!-- Job Queue (Service Requests) -->
        @if($pendingServices->count() > 0)
        <div class="px-4 py-4">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                Pending Service Jobs ({{ $pendingServices->count() }})
            </h3>
            <div class="space-y-3">
                @foreach($pendingServices as $service)
                <div class="bg-slate-800 rounded-xl p-4 border border-slate-700 shadow-lg relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-1 h-full bg-amber-500"></div>
                    <div class="flex justify-between items-center mb-2 pl-2">
                        <span class="text-xs font-bold text-slate-300">{{ $service->portCall->vessel->name ?? 'Unknown Vessel' }}</span>
                        <span class="text-[10px] bg-slate-700 px-2 py-0.5 rounded text-amber-500 font-bold uppercase">{{ $service->service_type }}</span>
                    </div>
                    <div class="pl-2 flex justify-between items-end">
                        <div>
                            <div class="text-2xl font-black text-white">{{ $service->quantity }} <span class="text-sm font-medium text-slate-400">{{ $service->unit }}</span></div>
                            <div class="text-[10px] text-slate-500">{{ $service->requested_at->format('M d, H:i') }} • {{ $service->portCall->berth->name ?? 'Pending Berth' }}</div>
                        </div>
                        <button wire:click="fulfillService({{ $service->id }})" class="bg-amber-500 hover:bg-amber-400 text-slate-900 px-4 py-2 rounded-lg font-bold text-xs uppercase tracking-wide shadow-lg shadow-amber-900/20 flex items-center gap-1 transition-all active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Complete
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="p-4 space-y-4">
            @forelse($bookings as $booking)
                <div class="bg-slate-800 rounded-2xl overflow-hidden border border-slate-700 shadow-xl">
                    <!-- Status Header -->
                    <div class="bg-slate-900/50 px-5 py-3 border-b border-slate-700 flex justify-between items-center">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">{{ $booking->reference_no }}</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                            {{ $booking->status === 'alongside' ? 'bg-green-500/20 text-green-400' : 'bg-blue-500/20 text-blue-400' }}">
                            {{ $booking->status }}
                        </span>
                    </div>

                    <!-- Vessel Info -->
                    <div class="p-5">
                        <h2 class="text-2xl font-black mb-1">{{ $booking->vessel->name }}</h2>
                        <p class="text-sm text-slate-400 mb-4">{{ $booking->vessel->vessel_type }} • {{ $booking->berth->name ?? 'Unassigned' }}</p>

                        <!-- Big Action Button based on state -->
                        @if($booking->status === 'approaching')
                            <button wire:click="updateStatus({{ $booking->id }}, 'anchored')" class="w-full py-4 bg-amber-500 hover:bg-amber-400 text-slate-900 rounded-xl font-bold uppercase tracking-widest text-sm shadow-lg shadow-amber-900/20 transition-all flex items-center justify-center gap-2">
                                <span class="text-xl">⚓</span> Confirm ATA
                            </button>
                        @elseif($booking->status === 'anchored')
                            <button wire:click="updateStatus({{ $booking->id }}, 'alongside')" class="w-full py-4 bg-green-500 hover:bg-green-400 text-white rounded-xl font-bold uppercase tracking-widest text-sm shadow-lg shadow-green-900/20 transition-all flex items-center justify-center gap-2 relative overflow-hidden group">
                                <span class="relative z-10 flex items-center gap-2">
                                    <span class="text-xl">🔗</span> Secure Lines
                                </span>
                                <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform"></div>
                            </button>
                             <p class="text-[10px] text-center text-slate-500 mt-2 uppercase tracking-wide">Triggers Billing Clock</p>
                        @elseif($booking->status === 'alongside')
                             <div class="grid grid-cols-2 gap-3 mb-3">
                                <button wire:click="requestService({{ $booking->id }}, 'water')" class="py-3 bg-slate-700 hover:bg-blue-600 rounded-xl text-xs font-bold text-slate-300 hover:text-white transition-colors">Request Water</button>
                                <button wire:click="requestService({{ $booking->id }}, 'fuel')" class="py-3 bg-slate-700 hover:bg-amber-600 rounded-xl text-xs font-bold text-slate-300 hover:text-white transition-colors">Request Fuel</button>
                             </div>
                            <button wire:click="updateStatus({{ $booking->id }}, 'completed')" class="w-full py-4 bg-slate-700 hover:bg-slate-600 text-white rounded-xl font-bold uppercase tracking-widest text-sm border-2 border-slate-600 hover:border-slate-500 transition-all flex items-center justify-center gap-2">
                                <span class="text-xl">👋</span> Release Lines
                            </button>
                        @endif
                    </div>

                    <!-- Timestamps Footer -->
                    @if($booking->ata || $booking->atb)
                    <div class="bg-black/20 px-5 py-3 flex gap-4 text-[10px] font-mono text-slate-500">
                        @if($booking->ata) <div>ATA: {{ $booking->ata->format('H:i') }}</div> @endif
                        @if($booking->atb) <div>ATB: {{ $booking->atb->format('H:i') }}</div> @endif
                    </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-20 text-slate-500">
                    <div class="w-16 h-16 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 grayscale opacity-50">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <p>No active tasks in your queue.</p>
                </div>
            @endforelse
        </div>
    </div>
    @endif

    <!-- Active Tasks Stream (ASSETS) -->
    @if($tab === 'assets')
    <div class="p-4 space-y-4 pb-20">
        @forelse($assetTasks as $task)
            <div class="bg-slate-800 rounded-2xl overflow-hidden border border-slate-700 shadow-xl">
                 <div class="p-5">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h2 class="text-xl font-black">{{ $task->asset->name }}</h2>
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-wide">{{ $task->asset->identifier }}</p>
                        </div>
                        <div class="text-right">
                             <p class="text-[10px] text-slate-500 uppercase tracking-widest mb-1">Client</p>
                             <p class="text-xs font-bold text-white">{{ $task->organization->code }}</p>
                        </div>
                    </div>

                    @if($task->task_type === 'checkout')
                        <div class="bg-amber-500/10 border border-amber-500/20 rounded-xl p-4 mb-4">
                             <p class="text-amber-500 text-xs font-bold uppercase tracking-wide mb-2">Instructions</p>
                             <p class="text-sm text-slate-300">{{ $task->notes ?? 'Deploy to yard area as requested.' }}</p>
                        </div>
                        <button wire:click="openHandover({{ $task->id }}, 'checkout')" class="w-full py-4 bg-amber-500 hover:bg-amber-400 text-slate-900 rounded-xl font-bold uppercase tracking-widest text-sm shadow-lg shadow-amber-900/20 transition-all flex items-center justify-center gap-2">
                            <span class="text-xl">📸</span> Handover (Check-Out)
                        </button>
                    @else
                        <div class="bg-indigo-500/10 border border-indigo-500/20 rounded-xl p-4 mb-4">
                             <div class="flex justify-between items-center mb-2">
                                <p class="text-indigo-400 text-xs font-bold uppercase tracking-wide">Usage Timer</p>
                                <p class="text-xs font-mono text-slate-400">Since {{ $task->check_out_time->format('H:i') }}</p>
                             </div>
                             <p class="text-2xl font-black text-white font-mono">{{ $task->check_out_time->diffInHours(now()) }}<span class="text-sm text-slate-400 font-medium ml-1">hrs</span></p>
                        </div>
                        <button wire:click="openHandover({{ $task->id }}, 'checkin')" class="w-full py-4 bg-slate-700 hover:bg-slate-600 text-white rounded-xl font-bold uppercase tracking-widest text-sm border border-slate-600 flex items-center justify-center gap-2">
                            <span class="text-xl">🏁</span> Return (Check-In)
                        </button>
                    @endif
                 </div>
            </div>
        @empty
            <div class="text-center py-20 text-slate-500">
                <p>No pending asset handovers.</p>
            </div>
        @endforelse
    </div>
    @endif

    <!-- Handover Modal -->
    @if($showHandoverModal)
    <div class="fixed inset-0 z-[60] flex items-end justify-center sm:items-center">
        <div class="fixed inset-0 bg-slate-900/90 backdrop-blur-sm" wire:click="$set('showHandoverModal', false)"></div>
        <div class="relative bg-slate-800 w-full max-w-md rounded-t-3xl sm:rounded-3xl border-t sm:border border-slate-700 shadow-2xl p-6 transform transition-all animate-in slide-in-from-bottom duration-300">
            <h3 class="text-xl font-black text-white mb-6 uppercase tracking-tight flex items-center gap-2">
                {{ $handoverType === 'checkout' ? '📸 Proof of Delivery' : '📝 Return Inspection' }}
            </h3>
            
            <div class="space-y-4 mb-6">
                <!-- Mock Camera Input -->
                <div class="border-2 border-dashed border-slate-600 rounded-2xl p-8 text-center bg-slate-900/50 cursor-pointer hover:border-slate-500 transition-colors">
                    <div class="w-12 h-12 bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <p class="text-sm font-bold text-slate-400">Tap to Capture Photo</p>
                    <p class="text-[10px] text-slate-500 uppercase mt-1">Evidence Required</p>
                </div>

                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 block">Field Notes</label>
                    <textarea wire:model="handoverNotes" rows="3" class="w-full bg-slate-900 border-slate-700 rounded-xl text-sm text-white p-3 focus:ring-indigo-500 focus:border-indigo-500" placeholder="{{ $handoverType === 'checkout' ? 'Verify condition...' : 'Note any damage or issues...' }}"></textarea>
                </div>
            </div>

            <button wire:click="submitHandover" class="w-full py-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-black uppercase tracking-widest text-sm shadow-lg shadow-indigo-900/30 transition-all">
                Confirm Transaction
            </button>
        </div>
    </div>
    @endif

    <!-- Toast Component -->
    <div x-data="{ toast: { show: false, message: '' }, showToast(msg) { this.toast = { show: true, message: msg }; setTimeout(() => this.toast.show = false, 3000); } }"
         @notify.window="showToast($event.detail.message)"
         class="fixed bottom-4 left-4 right-4 z-[60]"
         style="display: none;"
         x-show="toast.show"
         x-transition.duration.300ms>
        <div class="bg-indigo-600 text-white px-4 py-3 rounded-xl shadow-2xl flex items-center justify-center gap-2 font-bold text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span x-text="toast.message"></span>
        </div>
    </div>
</div>
