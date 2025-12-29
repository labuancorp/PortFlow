<div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
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

        @if($permissionError)
            <div class="p-12 text-center">
                <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Restricted Access</h3>
                <p class="text-slate-500 mt-2 max-w-xs mx-auto text-sm">This booking belongs to another organization. Operational details are hidden for privacy.</p>
                <div class="mt-8 flex justify-center">
                     <button wire:click="close" class="px-6 py-2 bg-slate-800 text-white rounded-lg font-bold text-sm">Close</button>
                </div>
            </div>
        @else
            <!-- Body -->
            <div class="p-6">
                @if(!$editMode)
                    <!-- View Mode -->
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
                            <p class="text-sm font-medium text-slate-900">{{ $booking->berth->name ?? 'Unassigned' }}</p>
                        </div>
                    </div>
                @else
                    <!-- Edit Mode -->
                    <div class="mb-6">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </div>
                            <h4 class="text-lg font-bold text-slate-800">Edit Booking Details</h4>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Vessel</label>
                                <select wire:model="editVesselId" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    <option value="">Select Vessel</option>
                                    @foreach($vessels as $vessel)
                                        <option value="{{ $vessel->id }}">{{ $vessel->name }} ({{ $vessel->imo_number }})</option>
                                    @endforeach
                                </select>
                                @error('editVesselId') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Agent</label>
                                <select wire:model="editAgentId" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    <option value="">Select Agent</option>
                                    @foreach($agents as $agent)
                                        <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                                    @endforeach
                                </select>
                                @error('editAgentId') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Assigned Berth</label>
                                <select wire:model="editBerthId" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    <option value="">Unassigned</option>
                                    @foreach($berths as $berth)
                                        <option value="{{ $berth->id }}">{{ $berth->name }}</option>
                                    @endforeach
                                </select>
                                @error('editBerthId') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">ETA</label>
                                    <input type="datetime-local" wire:model="editEta" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    @error('editEta') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">ETD</label>
                                    <input type="datetime-local" wire:model="editEtd" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    @error('editEtd') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if(auth()->user()->role === 'admin')
                <!-- Operational Controls (Ground Ops) -->
                <div class="mb-6 p-4 bg-slate-100 rounded-xl border border-slate-200">
                    <h5 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">Ground Operations</h5>
                    <div class="flex gap-2">
                        @if($booking->status === 'requested' || $booking->status === 'approved')
                            <button wire:click="updateStatus('anchored')" class="flex-1 py-3 bg-white border border-slate-300 rounded-lg text-xs font-bold text-slate-700 hover:bg-amber-50 hover:border-amber-300 shadow-sm transition-all flex flex-col items-center gap-1">
                                <span class="text-lg">⚓</span>
                                Confirm Arrival (ATA)
                            </button>
                        @endif
                        
                        @if($booking->status === 'anchored' || $booking->status === 'requested' || $booking->status === 'approved')
                            <button wire:click="updateStatus('alongside')" class="flex-1 py-3 bg-green-600 text-white rounded-lg text-xs font-bold shadow-lg shadow-green-900/20 hover:bg-green-500 transition-all flex flex-col items-center gap-1">
                                <span class="text-lg">✅</span>
                                Line Secured (Start Billing)
                            </button>
                        @endif

                        @if($booking->status === 'alongside')
                            <button wire:click="updateStatus('completed')" class="flex-1 py-3 bg-slate-800 text-white rounded-lg text-xs font-bold shadow-lg shadow-slate-900/20 hover:bg-slate-700 transition-all flex flex-col items-center gap-1">
                                <span class="text-lg">🏁</span>
                                Line Released (End Billing)
                            </button>
                        @endif

                        @if($booking->status === 'completed')
                             <div class="flex-1 py-3 bg-slate-200 text-slate-500 rounded-lg text-xs font-bold text-center cursor-not-allowed flex flex-col items-center gap-1">
                                <span class="text-lg">🔒</span>
                                Voyage Closed
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Live Invoice Section -->
                <div class="mb-6 border border-slate-200 rounded-xl overflow-hidden">
                    <div class="bg-slate-50 px-4 py-3 border-b border-slate-200 flex justify-between items-center">
                        <h5 class="text-xs font-bold text-slate-500 uppercase tracking-widest flex items-center gap-2">
                            <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            Live Billing Engine
                        </h5>
                        <button wire:click="refreshInvoice" class="text-[10px] text-teal-600 font-bold hover:underline">Refresh</button>
                    </div>
                    <div class="p-4 bg-white">
                        @if($invoice && $invoice->invoiceItems->count() > 0)
                            <table class="w-full text-sm">
                                <thead class="text-xs text-slate-400 font-bold uppercase border-b border-slate-100">
                                    <tr>
                                        <th class="text-left py-2 font-bold pl-2">Item</th>
                                        <th class="text-right py-2 font-bold pr-2">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @foreach($invoice->invoiceItems as $item)
                                    <tr>
                                        <td class="py-2 text-slate-600 pl-2 text-xs">
                                            <div class="font-bold text-slate-700">{{ $item->description }}</div>
                                            <div class="text-[10px] opacity-70">{{ $item->quantity }} units @ RM {{ number_format($item->unit_price / $item->quantity, 2) }}</div>
                                        </td>
                                        <td class="py-2 text-right font-mono font-bold text-slate-800 pr-2">RM {{ number_format($item->total_price, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="border-t-2 border-slate-100 bg-slate-50/50">
                                    <tr>
                                        <td class="py-3 pl-2 text-right font-black text-slate-900 uppercase text-xs tracking-widest">Total Due</td>
                                        <td class="py-3 pr-2 text-right font-black text-xl text-teal-600 font-mono">RM {{ number_format($invoice->total_amount, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                            <div class="mt-3 flex gap-2 justify-end">
                                 <div class="text-[10px] bg-yellow-100 text-yellow-800 px-2 py-1 rounded font-bold uppercase tracking-wider">
                                    Status: {{ $invoice->status }}
                                 </div>
                                 @if($booking->atb)
                                    <div class="text-[10px] bg-indigo-100 text-indigo-700 px-2 py-1 rounded font-mono font-bold">
                                        ⏱ {{ $booking->atb->diffInHours($booking->atd ?? now()) }} hrs
                                    </div>
                                 @endif
                            </div>
                        @else
                            <div class="text-center py-6">
                                <div class="inline-flex justify-center items-center w-12 h-12 rounded-full bg-slate-100 text-slate-300 mb-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Waiting for operations...</p>
                                <p class="text-[10px] text-slate-400">Billing starts when line is secured.</p>
                                
                                {{-- Debug Info --}}
                                <div class="mt-4 p-3 bg-slate-50 rounded text-left text-[10px] text-slate-500">
                                    <p><strong>Debug:</strong></p>
                                    <p>ATB: {{ $booking->atb ? $booking->atb->format('Y-m-d H:i:s') : 'NULL' }}</p>
                                    <p>Status: {{ $booking->status }}</p>
                                    <p>Invoice ID: {{ $invoice ? $invoice->id : 'NULL' }}</p>
                                    <p>Invoice Items: {{ $invoice ? $invoice->invoiceItems->count() : '0' }}</p>
                                    <p>Vessel LOA: {{ $booking->vessel->loa_meters ?? 'NULL' }}m</p>
                                    <p>Berth: {{ $booking->berth->name ?? 'NULL' }}</p>
                                    <button wire:click="refreshInvoice" class="mt-2 px-3 py-1 bg-indigo-600 text-white rounded text-xs">Force Refresh</button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-4 flex justify-end space-x-3">
                    @if($editMode)
                        <button wire:click="cancelEdit" class="px-4 py-2 text-slate-600 hover:bg-slate-100 rounded text-sm font-medium">Cancel</button>
                        <button wire:click="saveEdit" class="px-4 py-2 bg-indigo-600 text-white hover:bg-indigo-700 rounded text-sm font-medium">💾 Save Changes</button>
                    @else
                        <button wire:click="close" class="px-4 py-2 text-slate-600 hover:bg-slate-100 rounded text-sm font-medium">Close</button>
                        
                        @if(auth()->user()->role === 'admin')
                            {{-- Admins can always edit --}}
                            <button wire:click="toggleEditMode" class="px-4 py-2 bg-teal-600 text-white hover:bg-teal-700 rounded text-sm font-medium">✏️ Edit Booking</button>
                        @elseif(auth()->user()->role === 'agent' && auth()->user()->organization_id == $booking->agent_id)
                            {{-- Agents can only edit if booking is still in 'requested' status --}}
                            @if($booking->status === 'requested')
                                <button wire:click="toggleEditMode" class="px-4 py-2 bg-teal-600 text-white hover:bg-teal-700 rounded text-sm font-medium">✏️ Edit Booking</button>
                            @else
                                <div class="relative group">
                                    <button disabled class="px-4 py-2 bg-slate-300 text-slate-500 rounded text-sm font-medium cursor-not-allowed">🔒 Edit Locked</button>
                                    <div class="absolute bottom-full right-0 mb-2 hidden group-hover:block w-64 bg-slate-900 text-white text-xs rounded-lg p-3 shadow-xl">
                                        <p class="font-bold mb-1">Editing Disabled</p>
                                        <p>Bookings in the timeline can only be edited by Port Authority administrators.</p>
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
