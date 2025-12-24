<div class="min-h-screen bg-slate-900 text-white font-sans">
    <!-- Header -->
    <div class="bg-indigo-900 px-8 py-5 flex justify-between items-center shadow-2xl border-b border-indigo-800">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-indigo-500 rounded-xl flex items-center justify-center font-bold text-2xl shadow-lg shadow-indigo-500/50">
                🛂
            </div>
            <div>
                <h1 class="text-2xl font-black tracking-tight">ASB Crew Terminal</h1>
                <p class="text-xs text-indigo-300 font-bold uppercase tracking-widest">Immigration & Security Checkpoint</p>
            </div>
        </div>
        <div class="text-right">
             <p class="text-xs text-indigo-300 font-bold uppercase tracking-widest">Date</p>
             <p class="font-mono font-bold">{{ now()->format('d M Y H:i') }}</p>
        </div>
    </div>

    <div class="p-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Panel: Vessel Selection & Scanner -->
        <div class="space-y-6">
            <!-- Vessel Select -->
            <div class="bg-slate-800 p-6 rounded-3xl border border-slate-700 shadow-xl">
                <label class="block text-xs font-bold uppercase tracking-widest text-slate-500 mb-3">Active Vessel Operation</label>
                <select wire:model.live="selectedPortCallId" class="w-full bg-slate-900 border-slate-700 text-white rounded-xl focus:ring-indigo-500 p-4 font-bold text-lg">
                    <option value="">-- Select Vessel --</option>
                    @foreach($portCalls as $call)
                        <option value="{{ $call->id }}">{{ $call->vessel->name }} ({{ $call->reference_no }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Scanner Simulator -->
             <div class="bg-slate-800 p-8 rounded-3xl border border-slate-700 shadow-xl relative overflow-hidden group">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-green-400 to-transparent opacity-50 animate-pulse"></div>
                
                <h3 class="text-xl font-bold mb-4 flex items-center gap-3">
                    <span class="w-3 h-3 rounded-full bg-green-500 animate-pulse"></span>
                    Ready to Scan
                </h3>
                
                <p class="text-slate-400 text-sm mb-6">Place passport on scanner or manually enter document number below.</p>
                
                <form wire:submit.prevent="scanPassport">
                    <div class="relative">
                        <input type="text" wire:model="passportInput" class="w-full bg-slate-900 border-2 border-slate-700 text-white rounded-xl focus:ring-green-500 focus:border-green-500 p-4 pl-12 font-mono text-xl tracking-widest uppercase placeholder-slate-600 transition-all" placeholder="P12345678" autofocus>
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-2xl">📟</span>
                    </div>
                </form>

                <div class="mt-4 flex gap-2 justify-center">
                    <button wire:click="$set('passportInput', 'A' . rand(100000,999999))" class="text-[10px] bg-slate-700 hover:bg-slate-600 px-3 py-1 rounded-lg uppercase font-bold tracking-wider transition-colors">Test Scan</button>
                </div>
            </div>
        </div>

        <!-- Right Panel: Manifest & Flow -->
        <div class="lg:col-span-2">
            <div class="bg-slate-800 rounded-3xl border border-slate-700 shadow-xl overflow-hidden min-h-[600px]">
                <div class="px-8 py-6 border-b border-slate-700 flex justify-between items-center bg-slate-900/30">
                    <h3 class="text-xl font-bold">Live Passenger Processing</h3>
                    <span class="bg-indigo-900 text-indigo-300 px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider">{{ count($transfers) }} PAX Processed</span>
                </div>
                
                <div class="p-4 space-y-3">
                    @forelse($transfers as $transfer)
                    <div class="flex items-center gap-4 p-4 rounded-xl border border-dashed transition-all
                        {{ $transfer->status === 'flagged' ? 'bg-red-900/10 border-red-500/50' : 
                          ($transfer->status === 'completed' ? 'bg-green-900/10 border-green-500/50' : 'bg-slate-700/50 border-slate-600') }}">
                        
                        <!-- Status Icon -->
                         <div class="w-12 h-12 rounded-xl flex items-center justify-center font-bold text-2xl
                            {{ $transfer->status === 'flagged' ? 'bg-red-500/20 text-red-500' : 
                              ($transfer->status === 'completed' ? 'bg-green-500/20 text-green-500' : 'bg-indigo-500/20 text-indigo-400') }}">
                            @if($transfer->status === 'flagged') ⛔ 
                            @elseif($transfer->status === 'completed') ✅ 
                            @elseif($transfer->status === 'immigration_cleared') 🛂
                            @else ⏳
                            @endif
                         </div>

                         <div class="flex-1">
                             <div class="flex justify-between items-start">
                                 <div>
                                     <h4 class="font-bold text-lg text-white">{{ $transfer->crewMember->name }}</h4>
                                     <p class="text-xs font-mono text-slate-400 uppercase tracking-wider">Pass: {{ $transfer->crewMember->passport_number }} • {{ $transfer->crewMember->nationality }}</p>
                                 </div>
                                 <div class="text-right">
                                     <span class="block text-[10px] font-bold uppercase tracking-widest mb-1 text-slate-500">Direction</span>
                                     <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide {{ $transfer->direction === 'sign_on' ? 'bg-blue-500/20 text-blue-300' : 'bg-amber-500/20 text-amber-300' }}">
                                         {{ str_replace('_', ' ', $transfer->direction) }}
                                     </span>
                                 </div>
                             </div>
                             
                             <!-- Progress Bar -->
                            <div class="mt-3 h-1.5 bg-slate-900 rounded-full overflow-hidden flex">
                                <div class="h-full bg-indigo-500 transition-all duration-500" style="width: 33%"></div>
                                <div class="h-full {{ in_array($transfer->status, ['immigration_cleared', 'completed']) ? 'bg-indigo-400' : 'bg-transparent' }} transition-all duration-500" style="width: 33%"></div>
                                <div class="h-full {{ $transfer->status === 'completed' ? 'bg-green-500' : 'bg-transparent' }} transition-all duration-500" style="width: 34%"></div>
                            </div>
                            <div class="flex justify-between text-[8px] font-bold uppercase tracking-widest text-slate-500 mt-1">
                                <span>Security Check</span>
                                <span>Immigration</span>
                                <span>Terminal Gate</span>
                            </div>
                         </div>

                         <!-- Controls -->
                         <div class="flex flex-col gap-2">
                             @if($transfer->status !== 'completed' && $transfer->status !== 'flagged')
                             <button wire:click="processTransfer({{ $transfer->id }}, 'clear')" class="px-3 py-2 bg-green-600 hover:bg-green-500 text-white rounded-lg text-xs font-bold transition-colors">Force Clear</button>
                             <button wire:click="processTransfer({{ $transfer->id }}, 'flag')" class="px-3 py-2 bg-red-800 hover:bg-red-700 text-red-200 rounded-lg text-xs font-bold transition-colors">Flag Risk</button>
                             @endif
                         </div>
                    </div>
                    @empty
                    <div class="text-center py-24 border-2 border-dashed border-slate-700 rounded-2xl">
                        <div class="text-6xl grayscale opacity-20 mb-4">🛂</div>
                        <h3 class="text-slate-400 font-bold">Waiting for Crew</h3>
                        <p class="text-slate-600 text-sm">Scan a document to begin processing.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    
     <!-- Toast Component (Reused logic) -->
    <div x-data="{ toast: { show: false, message: '' }, showToast(msg) { this.toast = { show: true, message: msg }; setTimeout(() => this.toast.show = false, 3000); } }"
         @notify.window="showToast($event.detail.message)"
         class="fixed bottom-4 right-4 z-[70]"
         style="display: none;"
         x-show="toast.show"
         x-transition.duration.300ms>
        <div class="bg-green-500 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center justify-center gap-3 font-bold">
            <span class="text-2xl">✅</span>
            <span x-text="toast.message"></span>
        </div>
    </div>
</div>
