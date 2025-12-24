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

    <!-- Active Tasks Stream -->
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
