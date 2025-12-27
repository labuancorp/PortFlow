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
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Resource Marketplace</h1>
            <p class="text-slate-500 mt-2">Book cranes, forklifts, and storage facilities for your operations.</p>
        </div>
        <div class="flex items-center gap-4">
             <div class="text-right">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Logged Organization</p>
                <p class="text-xs font-bold text-emerald-600">{{ auth()->user()->organization->name }}</p>
             </div>
        </div>
    </div>

    <!-- Active Catalog -->
    <h3 class="font-black text-slate-900 uppercase tracking-widest text-xs mb-6 px-4 py-2 bg-slate-100 rounded-lg inline-block">Available for Hire</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        @foreach($availableAssets as $asset)
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm hover:shadow-xl hover:border-slate-300 transition-all group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-2xl bg-slate-900 text-white flex items-center justify-center shadow-lg shadow-slate-900/20">
                     @if($asset->type === 'crane')
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                     @elseif($asset->type === 'forklift')
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                     @else
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                     @endif
                </div>
                <span class="text-[10px] font-black text-slate-400 tracking-widest uppercase">ID: {{ $asset->identifier }}</span>
            </div>

            <h4 class="text-sm font-black text-slate-900 mb-1">{{ $asset->name }}</h4>
            <p class="text-[10px] text-slate-500 font-bold mb-6 line-clamp-2 h-8">{{ $asset->description }}</p>

            <div class="space-y-2 mb-6 border-y border-slate-50 py-4">
                <div class="flex justify-between items-center">
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Hourly Rate</span>
                    <span class="text-xs font-black text-slate-900">RM {{ number_format($asset->rate_per_hour, 2) }}</span>
                </div>
                <div class="flex justify-between items-center text-xs text-indigo-600 font-bold">
                    <span class="text-[10px] font-bold uppercase">Daily Best Value</span>
                    <span>RM {{ number_format($asset->rate_per_day, 2) }}</span>
                </div>
            </div>

            <button wire:click="openBookingModal({{ $asset->id }})" class="w-full bg-slate-900 text-white py-3 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-800 transition-all active:scale-95 shadow-lg shadow-slate-900/10">
                Book Resource
            </button>
        </div>
        @endforeach
    </div>

    <!-- My Bookings History -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 class="font-black text-slate-900 uppercase tracking-widest text-xs">My Rental History</h3>
        </div>
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-100 text-[10px] uppercase tracking-wider text-slate-500 font-bold">
                    <th class="px-6 py-4">Reference</th>
                    <th class="px-6 py-4">Resource</th>
                    <th class="px-6 py-4">Timeline</th>
                    <th class="px-6 py-4">Est. Cost</th>
                    <th class="px-6 py-4">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($myBookings as $booking)
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="px-6 py-5">
                        <span class="text-xs font-black text-slate-900">#{{ $booking->reference_no }}</span>
                    </td>
                    <td class="px-6 py-5">
                        <p class="text-sm font-bold text-slate-800">{{ $booking->asset->name }}</p>
                        <p class="text-[10px] font-bold text-slate-400 tracking-widest uppercase">{{ $booking->asset->identifier }}</p>
                    </td>
                    <td class="px-6 py-5">
                        <p class="text-xs font-bold text-slate-600">{{ $booking->start_time->format('d M, H:i') }}</p>
                        @if($booking->end_time)
                            <p class="text-[10px] text-slate-400">until {{ $booking->end_time->format('H:i') }}</p>
                        @endif
                    </td>
                    <td class="px-6 py-5">
                        <p class="text-sm font-black text-slate-900">RM {{ number_format($booking->estimated_cost, 2) }}</p>
                    </td>
                    <td class="px-6 py-5">
                        <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase border
                            {{ $booking->status === 'approved' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 
                               ($booking->status === 'requested' ? 'bg-indigo-50 text-indigo-600 border-indigo-100' : 
                               ($booking->status === 'active' ? 'bg-amber-50 text-amber-600 border-amber-100' : 
                               'bg-slate-50 text-slate-400 border-slate-100')) }}">
                            {{ $booking->status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-slate-400 font-bold uppercase tracking-widest text-xs">No rental activity logged.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $myBookings->links() }}
        </div>
    </div>

    <!-- Booking Modal -->
    @if($showBookingModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="$set('showBookingModal', false)"></div>
        <div class="relative bg-white w-full max-w-lg rounded-3xl shadow-2xl border border-white/20 overflow-hidden transform transition-all animate-in zoom-in duration-200">
            <div class="p-8">
                <div class="flex justify-between items-start mb-8">
                    <div>
                         <h3 class="text-2xl font-black text-slate-900 tracking-tight">Deploy Resource</h3>
                         <p class="text-sm text-slate-500 mt-1">Schedule equipment for your project operations.</p>
                    </div>
                    <button wire:click="$set('showBookingModal', false)" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form wire:submit.prevent="submitBooking" class="space-y-6">
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Resource to Dispatch</label>
                        <select wire:model="port_asset_id" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-900 text-sm p-4 focus:ring-slate-500 focus:border-slate-500 transition-all">
                            <option value="">Select an asset...</option>
                            @foreach($availableAssets as $asset)
                                <option value="{{ $asset->id }}">{{ $asset->name }} ({{ $asset->identifier }})</option>
                            @endforeach
                        </select>
                        @error('port_asset_id') <span class="text-red-500 text-[10px] font-bold mt-1 block uppercase">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Start Engagement</label>
                            <input type="datetime-local" wire:model="start_time" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-900 text-sm p-3 focus:ring-slate-500 focus:border-slate-500 transition-all">
                            @error('start_time') <span class="text-red-500 text-[10px] font-bold mt-1 block uppercase">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Est. Release Time</label>
                            <input type="datetime-local" wire:model="end_time" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-900 text-sm p-3 focus:ring-slate-500 focus:border-slate-500 transition-all">
                            @error('end_time') <span class="text-red-500 text-[10px] font-bold mt-1 block uppercase">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Operational Notes / Justification</label>
                        <textarea wire:model="notes" rows="3" placeholder="e.g. For tubular loading on Vessel Nautica Gamble" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-900 text-sm p-3 focus:ring-slate-500 focus:border-slate-500 transition-all"></textarea>
                    </div>

                    <div class="pt-6 border-t border-slate-100">
                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white py-4 rounded-2xl font-black uppercase tracking-[0.2em] shadow-xl shadow-emerald-900/20 transition-all active:scale-[0.98]">
                            Request Dispatch
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
