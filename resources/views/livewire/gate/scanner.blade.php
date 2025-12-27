<div class="h-full flex flex-col sm:flex-row">
    <!-- Scanner / Action Panel -->
    <div class="w-full sm:w-1/2 p-6 flex flex-col bg-slate-900 border-r border-slate-700 text-slate-100">
        <div class="mb-8">
            <h2 class="text-2xl font-bold tracking-tight text-white flex items-center gap-2">
                <svg class="w-8 h-8 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                Gate Security Scanner
            </h2>
            <p class="text-slate-400">Scan vendor QR codes for entry validation.</p>
        </div>

        <!-- Simulated Scan Input -->
        <form wire:submit.prevent="scan" class="mb-8">
            <label for="searchUuid" class="block text-sm font-medium text-slate-300 mb-2">Scan QR Code (UUID)</label>
            <div class="relative">
                <input wire:model="searchUuid" type="text" id="searchUuid" class="block w-full rounded-md border-0 bg-slate-800 text-white shadow-sm ring-1 ring-inset ring-slate-600 focus:ring-2 focus:ring-inset focus:ring-teal-500 sm:text-lg sm:leading-6 p-4" placeholder="Scan or enter Pass ID..." autofocus>
                <button type="button" wire:click="scan" class="absolute right-2 top-2 bottom-2 bg-teal-600 hover:bg-teal-500 text-white font-bold py-1 px-4 rounded">
                    SCAN
                </button>
            </div>
        </form>

        <!-- Scanned Entry Result -->
        @if($scannedEntry)
        <div class="bg-slate-800 rounded-lg p-6 border border-slate-700 shadow-xl animate-fade-in flex-1">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <span class="inline-flex items-center rounded-md bg-slate-700 px-2 py-1 text-xs font-medium text-slate-200 ring-1 ring-inset ring-slate-600 mb-2">PASS ID: {{ substr($scannedEntry->uuid, 0, 8) }}...</span>
                    <h3 class="text-xl font-bold text-white">{{ $scannedEntry->driver_name }}</h3>
                    <p class="text-slate-400 font-mono">{{ $scannedEntry->vehicle_plate }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-slate-500">IC / Passport</p>
                    <p class="text-white">{{ $scannedEntry->driver_ic }}</p>
                </div>
            </div>

            <div class="bg-slate-900 rounded p-4 mb-6 border border-slate-700">
                <p class="text-slate-400 text-xs uppercase tracking-wider mb-1">Declared Cargo</p>
                <p class="text-white text-lg">{{ $scannedEntry->cargo_description }}</p>
            </div>

            <!-- Validation Status -->
            <div class="mb-6">
                @if($scannedEntry->has_dangerous_goods)
                    <div class="bg-red-900/30 border border-red-500 rounded-lg p-4 flex items-center gap-4 animate-pulse">
                        <svg class="h-10 w-10 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <div>
                            <h4 class="text-red-400 font-bold text-lg">DANGEROUS GOODS DETECTED</h4>
                            <p class="text-red-300 text-sm">Verify safety documentation before entry.</p>
                        </div>
                    </div>
                @else
                     <div class="bg-emerald-900/30 border border-emerald-500 rounded-lg p-4 flex items-center gap-4">
                        <svg class="h-10 w-10 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <div>
                            <h4 class="text-emerald-400 font-bold text-lg">CLEARED FOR ENTRY</h4>
                            <p class="text-emerald-300 text-sm">Standard cargo manifest.</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="grid grid-cols-2 gap-4">
                 <button wire:click="$set('scannedEntry', null)" class="w-full flex justify-center py-3 px-4 border border-slate-600 rounded-md shadow-sm text-sm font-bold text-slate-300 bg-slate-800 hover:bg-slate-700">
                    CANCEL
                </button>
                @if($scannedEntry->status !== 'checked_in')
                <button wire:click="processCheckIn" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-bold text-white {{ $scannedEntry->has_dangerous_goods ? 'bg-amber-600 hover:bg-amber-700' : 'bg-green-600 hover:bg-green-700' }}">
                     {{ $scannedEntry->has_dangerous_goods ? 'APPROVE WITH CAUTION' : 'APPROVE ENTRY' }}
                </button>
                @else
                <button disabled class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-bold text-white bg-slate-600 cursor-not-allowed">
                     ALREADY CHECKED IN
                </button>
                @endif
            </div>
        </div>
        @else
        <div class="flex-1 flex items-center justify-center text-slate-600 border border-dashed border-slate-700 rounded-lg">
            <div class="text-center">
                <svg class="mx-auto h-12 w-12 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                <h3 class="mt-2 text-sm font-medium text-slate-500">Ready to Scan</h3>
            </div>
        </div>
        @endif
    </div>

    <!-- Active List Panel -->
    <div class="w-full sm:w-1/2 bg-slate-50 p-6 flex flex-col h-full overflow-hidden">
        
        <!-- Pending Queue -->
        <div class="mb-6 flex-1 overflow-auto">
            <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center justify-between">
                <span>Pending Arrivals</span>
                <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ $pendingEntries->count() }}</span>
            </h3>
            <div class="space-y-3">
                 @forelse($pendingEntries as $entry)
                <div wire:click="scan('{{ $entry->uuid }}')" class="bg-white p-4 rounded-lg shadow-sm border border-slate-200 hover:border-indigo-500 hover:shadow-md cursor-pointer transition-all">
                    <div class="flex justify-between items-start">
                         <div>
                            <p class="font-bold text-slate-800">{{ $entry->driver_name }}</p>
                            <p class="text-sm text-slate-500">{{ $entry->vehicle_plate }}</p>
                        </div>
                        @if($entry->has_dangerous_goods)
                             <span class="bg-red-100 text-red-800 text-xs font-bold px-2 py-1 rounded">DG Cargo</span>
                        @endif
                    </div>
                     <p class="text-xs text-slate-400 mt-2 truncate">{{ $entry->cargo_description }}</p>
                </div>
                @empty
                 <div class="text-center py-8 text-slate-400 text-sm">No pending entries</div>
                @endforelse
            </div>
        </div>

        <!-- Recent Log -->
        <div class="border-t border-slate-200 pt-6">
             <h3 class="text-lg font-bold text-slate-800 mb-4">Recent Gate-Ins</h3>
              <div class="space-y-2">
                 @foreach($recentEntries as $entry)
                 <div class="flex items-center justify-between text-sm p-2 rounded hover:bg-slate-100">
                    <div class="flex items-center gap-3">
                         <div class="h-2 w-2 rounded-full bg-emerald-500"></div>
                         <span class="font-medium text-slate-700">{{ $entry->vehicle_plate }}</span>
                    </div>
                    <span class="text-slate-400">{{ $entry->gate_in_at->format('H:i') }}</span>
                 </div>
                 @endforeach
            </div>
        </div>
    </div>

    <!-- Alerts (Toast) -->
    @if($showAlert)
    <div class="fixed top-4 right-4 z-50 animate-bounce-in">
        <div class="rounded-md p-4 shadow-lg flex items-start gap-3 w-80 {{ $alertType === 'danger' ? 'bg-red-50 text-red-800 border-red-200' : ($alertType === 'success' ? 'bg-green-50 text-green-800 border-green-200' : 'bg-blue-50 text-blue-800 border-blue-200') }} border">
             <div class="flex-shrink-0">
                @if($alertType === 'danger')
                    <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                @elseif($alertType === 'success')
                     <svg class="h-5 w-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                @else
                    <svg class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                @endif
            </div>
            <div class="flex-1">
                <p class="text-sm font-bold">{{ $alertMessage }}</p>
            </div>
            <button wire:click="closeAlert" class="text-slate-400 hover:text-slate-600">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>
    @endif
</div>
