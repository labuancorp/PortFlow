<div class="min-h-screen bg-slate-100 flex items-center justify-center p-4">
    <div class="bg-white max-w-md w-full rounded-2xl shadow-xl overflow-hidden border border-slate-200">
        <!-- Header -->
        <div class="bg-indigo-600 p-6 text-center">
            <div class="inline-block p-3 rounded-full bg-white/10 mb-3">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
            </div>
            <h1 class="text-xl font-black text-white tracking-tight">Cargo Tracker</h1>
            <p class="text-indigo-200 text-sm font-mono mt-1">{{ $item->tracking_number }}</p>
        </div>

        <!-- Body -->
        <div class="p-6 space-y-6">
            <!-- Item Details -->
            <div>
                <h2 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Item Description</h2>
                <div class="text-lg font-bold text-slate-900 leading-tight">{{ $item->description }}</div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                    <div class="text-xs text-slate-400 font-bold uppercase">Weight</div>
                    <div class="font-mono font-bold text-slate-700">{{ number_format($item->weight_kg) }} KG</div>
                </div>
                <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                    <div class="text-xs text-slate-400 font-bold uppercase">Volume</div>
                    <div class="font-mono font-bold text-slate-700">{{ number_format($item->volume_m3, 2) }} M³</div>
                </div>
            </div>

            <!-- Location Status -->
            <div class="relative pl-6 border-l-2 border-slate-200 space-y-6 py-2">
                <!-- Current Status -->
                <div class="relative">
                    <div class="absolute -left-[31px] bg-emerald-500 w-4 h-4 rounded-full border-2 border-white ring-2 ring-emerald-100"></div>
                    <div>
                        <div class="text-sm font-bold text-slate-900">Current Status</div>
                        <div class="inline-block mt-1 px-3 py-1 rounded-md bg-emerald-100 text-emerald-700 text-xs font-black uppercase tracking-wide">
                            {{ str_replace('_', ' ', $item->status) }}
                        </div>
                    </div>
                </div>

                <!-- Location -->
                <div class="relative">
                     <div class="absolute -left-[31px] bg-indigo-500 w-4 h-4 rounded-full border-2 border-white ring-2 ring-indigo-100"></div>
                    <div>
                        <div class="text-sm font-bold text-slate-900">Location</div>
                        @if($item->zone)
                            <div class="text-sm text-slate-600 font-medium">
                                {{ $item->zone->warehouse->name }} <br>
                                <span class="text-indigo-600 font-bold">{{ $item->zone->name }} ({{ $item->zone->code }})</span>
                            </div>
                        @else
                            <div class="text-sm text-slate-500 italic">Location not assigned</div>
                        @endif
                    </div>
                </div>

                 <!-- Manifest -->
                 <div class="relative">
                    <div class="absolute -left-[31px] bg-slate-300 w-4 h-4 rounded-full border-2 border-white"></div>
                   <div>
                       <div class="text-sm font-bold text-slate-900">Manifest Ref</div>
                       <div class="text-sm font-mono text-slate-500">{{ $item->manifest->reference_no }}</div>
                   </div>
               </div>
            </div>
            
            @if($item->dg_class)
            <div class="bg-red-50 border border-red-100 p-4 rounded-xl flex items-start gap-3">
                 <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                 <div>
                     <div class="text-sm font-bold text-red-700">Dangerous Goods (Class {{ $item->dg_class }})</div>
                     <div class="text-xs text-red-600 mt-1">Handle with extreme care. Follow DG protocols.</div>
                 </div>
            </div>
            @endif

        </div>
        
        <div class="bg-slate-50 p-4 text-center text-xs text-slate-400 font-bold border-t border-slate-200">
            PortFlow Logistics Tracker &copy; {{ date('Y') }}
        </div>
    </div>
</div>
