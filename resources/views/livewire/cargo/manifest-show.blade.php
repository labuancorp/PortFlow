<div class="p-8 max-w-6xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('cargo.manifests.index') }}" class="p-2 rounded-lg hover:bg-slate-200 text-slate-500 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">{{ $manifest->reference_no }}</h1>
                <div class="flex items-center gap-2 mt-1">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-lg text-xs font-bold uppercase tracking-wide {{ $manifest->type === 'inbound' ? 'bg-teal-50 text-teal-700 border border-teal-100' : 'bg-blue-50 text-blue-700 border border-blue-100' }}">
                        {{ $manifest->type }}
                    </span>
                    <span class="text-slate-400 text-sm font-medium">•</span>
                    <span class="text-slate-500 text-sm font-bold">{{ $manifest->items->count() }} Items</span>
                </div>
            </div>
        </div>
        <div class="flex gap-3">
            <button onclick="window.print()" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-lg text-sm font-bold shadow-sm hover:bg-slate-50 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Print Manifest
            </button>
            <button wire:click="toggleStatus" class="px-6 py-2 bg-slate-900 text-white rounded-lg text-sm font-bold shadow-lg hover:bg-slate-800 transition-all">
                Advance Status ({{ ucfirst($manifest->status) }})
            </button>
        </div>
    </div>

    <!-- DG Alert Banner -->
    @php
        $dgItems = $manifest->items->whereNotNull('dg_class')->where('dg_class', '!=', '');
    @endphp
    @if($dgItems->count() > 0)
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl shadow-sm flex items-start gap-3">
        <svg class="w-6 h-6 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        <div>
            <h3 class="text-red-800 font-bold text-sm uppercase tracking-wide">Dangerous Goods Detected</h3>
            <p class="text-red-600 text-sm mt-1">This manifest contains {{ $dgItems->count() }} items classified as Dangerous Goods. Ensure strict adherence to IMDG segregation rules.</p>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-3 gap-8">
        <!-- Main Info -->
        <div class="col-span-2 space-y-8">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                 <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100 text-[10px] uppercase tracking-wider text-slate-500 font-bold">
                            <th class="px-6 py-4">Item Details</th>
                            <th class="px-6 py-4">DG Class</th>
                            <th class="px-6 py-4 text-center">QR Code</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($manifest->items as $item)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600 font-black text-xs">
                                        {{ $loop->iteration }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900">{{ $item->description }}</div>
                                        <div class="font-mono text-xs text-slate-500">{{ $item->tracking_number }}</div>
                                        <div class="text-xs text-slate-400 mt-1">{{ $item->weight_kg }} KG / {{ $item->volume_m3 ?? '-' }} M³</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($item->dg_class)
                                    <span class="inline-flex items-center px-2 py-1 rounded bg-red-100 text-red-700 font-bold text-xs border border-red-200">
                                        Class {{ $item->dg_class }}
                                    </span>
                                @else
                                    <span class="text-slate-400 text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="text-center">
                                    <div class="bg-white p-2 rounded-lg inline-block border border-slate-100 shadow-sm">
                                        {!! QrCode::size(80)->generate(route('cargo.track', $item->tracking_number)) !!}
                                    </div>
                                    <div class="text-[10px] font-mono font-bold text-slate-400 mt-1">{{ $item->tracking_number }}</div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                 </table>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Vessel Information</h3>
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <div>
                        <div class="font-bold text-slate-900">{{ $manifest->vessel->name }}</div>
                        <div class="text-xs text-slate-500">{{ $manifest->vessel->vessel_type }}</div>
                    </div>
                </div>
                <div class="space-y-3 pt-4 border-t border-slate-100">
                    <div class="flex justify-between">
                        <span class="text-xs text-slate-500">Voyage No</span>
                        <span class="text-xs font-bold text-slate-700">V-{{ substr($manifest->vessel->id . $manifest->created_at->timestamp, -6) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-slate-500">Agent</span>
                        <span class="text-xs font-bold text-slate-700">{{ $manifest->agent->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-slate-500">ETA</span>
                        <span class="text-xs font-bold text-slate-700">{{ $manifest->eta_etd->format('d M H:i') }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                 <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Manifest Status</h3>
                 <div class="flex items-center gap-3">
                     <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                         <div class="h-full bg-indigo-500 rounded-full" style="width: {{ 
                            $manifest->status === 'draft' ? '25%' : 
                            ($manifest->status === 'submitted' ? '50%' : 
                            ($manifest->status === 'approved' ? '75%' : '100%')) 
                         }}"></div>
                     </div>
                     <span class="text-xs font-bold text-indigo-600 uppercase">{{ $manifest->status }}</span>
                 </div>
            </div>
        </div>
    </div>
</div>
