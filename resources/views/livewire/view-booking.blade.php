<div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" x-data="{ open: true }" x-show="open">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-lg overflow-hidden transform transition-all">
        <!-- Header -->
        <div class="bg-slate-900 px-6 py-4 flex justify-between items-center">
            <h3 class="text-white font-bold text-lg">Booking Details</h3>
            <button wire:click="close" class="text-slate-400 hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Body -->
        <div class="p-6">
            <div class="flex items-center space-x-4 mb-6">
                <div class="w-12 h-12 bg-teal-100 rounded-full flex items-center justify-center text-teal-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <div>
                    <h4 class="text-xl font-bold text-slate-800">{{ $booking->vessel->name }}</h4>
                    <p class="text-sm text-slate-500">{{ $booking->vessel->vessel_type }} • {{ $booking->vessel->imo_number }}</p>
                </div>
                <div class="ml-auto">
                    <span class="px-3 py-1 rounded-full text-xs font-bold 
                        {{ $booking->status === 'alongside' ? 'bg-green-100 text-green-700' : 
                           ($booking->status === 'requested' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-700') }}">
                        {{ ucfirst($booking->status) }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="p-3 bg-slate-50 rounded border border-slate-100">
                    <p class="text-xs text-slate-500 uppercase font-semibold">Arrival (ETA)</p>
                    <p class="text-sm font-medium text-slate-900">{{ $booking->eta ? $booking->eta->format('d M H:i') : '-' }}</p>
                </div>
                <div class="p-3 bg-slate-50 rounded border border-slate-100">
                    <p class="text-xs text-slate-500 uppercase font-semibold">Departure (ETD)</p>
                    <p class="text-sm font-medium text-slate-900">{{ $booking->etd ? $booking->etd->format('d M H:i') : '-' }}</p>
                </div>
                <div class="p-3 bg-slate-50 rounded border border-slate-100">
                    <p class="text-xs text-slate-500 uppercase font-semibold">Agent</p>
                    <p class="text-sm font-medium text-slate-900">{{ $booking->agent->name }}</p>
                </div>
                <div class="p-3 bg-slate-50 rounded border border-slate-100">
                    <p class="text-xs text-slate-500 uppercase font-semibold">Assigned Berth</p>
                    <p class="text-sm font-medium text-slate-900">{{ $booking->berth->name }}</p>
                </div>
            </div>

            <div class="border-t border-slate-100 pt-4 flex justify-end space-x-3">
                <button wire:click="close" class="px-4 py-2 text-slate-600 hover:bg-slate-100 rounded text-sm font-medium">Close</button>
                <button class="px-4 py-2 bg-teal-600 text-white hover:bg-teal-700 rounded text-sm font-medium">Edit Booking</button>
            </div>
        </div>
    </div>
</div>
