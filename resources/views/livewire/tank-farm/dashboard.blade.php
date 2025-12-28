<div class="p-6">
    <!-- Header & Stats -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Tank Farm Management</h1>
            <p class="text-slate-500">Liquid Mud Plant & Bulk Storage Monitoring</p>
        </div>
        <div class="flex gap-4">
            <!-- Global Stats -->
            <div class="bg-white px-4 py-2 rounded-lg border border-slate-200 shadow-sm flex items-center gap-3">
                <div class="p-2 bg-indigo-50 rounded-full text-indigo-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                </div>
                <div>
                    <p class="text-xs text-slate-400 font-bold uppercase">Utilization</p>
                    <p class="text-lg font-bold text-slate-700">{{ number_format($utilizationRate, 1) }}%</p>
                </div>
            </div>
            <div class="bg-white px-4 py-2 rounded-lg border border-slate-200 shadow-sm flex items-center gap-3">
                <div class="p-2 bg-emerald-50 rounded-full text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div>
                    <p class="text-xs text-slate-400 font-bold uppercase">Total Volume</p>
                    <p class="text-lg font-bold text-slate-700">{{ number_format($totalUtilized) }} <span class="text-xs text-slate-400">m³</span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tanks Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
        @foreach($tanks as $tank)
            @php
                $percentage = $tank->capacity_volume > 0 ? ($tank->current_volume / $tank->capacity_volume) * 100 : 0;
                $color = $tank->product ? $tank->product->color_code : '#e5e7eb'; // Default gray
                $statusColor = match($tank->status) {
                    'active' => 'bg-emerald-100 text-emerald-700',
                    'maintenance' => 'bg-amber-100 text-amber-700',
                    'cleaning' => 'bg-blue-100 text-blue-700',
                    'contaminated' => 'bg-red-100 text-red-700',
                    default => 'bg-slate-100 text-slate-600'
                };
            @endphp
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden relative group hover:shadow-md transition-shadow">
                <div class="p-4 relative z-10">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex flex-col">
                            <h3 class="font-bold text-slate-800 text-lg">{{ $tank->name }}</h3>
                            <span class="text-xs text-slate-400">{{ $tank->zone_id ?? 'General Zone' }}</span>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded {{ $statusColor }}">
                            {{ $tank->status }}
                        </span>
                    </div>
                    
                    <!-- Tank Visualization -->
                    <div class="h-48 w-full bg-slate-50 rounded-lg border border-slate-200 relative overflow-hidden mb-4 shadow-inner">
                         <!-- Product Color Background with Opacity -->
                         <div class="absolute inset-0 bg-white"></div>
                         
                         <!-- Liquid Level -->
                        <div class="absolute bottom-0 left-0 right-0 transition-all duration-1000 ease-in-out border-t border-black/5"
                             style="height: {{ $percentage }}%; background-color: {{ $color }}; opacity: 0.9;">
                             <!-- Highlight/Sheen -->
                             <div class="absolute top-0 left-0 w-full h-[2px] bg-white/20"></div>
                        </div>

                        <!-- Capacity Grid Lines (25%, 50%, 75%) -->
                        <div class="absolute inset-0 pointer-events-none flex flex-col justify-between py-2">
                             <div class="w-full h-px border-t border-dashed border-slate-300/50"></div>
                             <div class="w-full h-px border-t border-dashed border-slate-300/50"></div>
                             <div class="w-full h-px border-t border-dashed border-slate-300/50"></div>
                        </div>

                        <!-- Percentage Label -->
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none drop-shadow-md">
                            <span class="font-black text-3xl {{ $percentage > 50 ? 'text-white/90' : 'text-slate-800/80' }} mix-blend-hard-light">{{ round($percentage) }}%</span>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm items-center">
                            <span class="text-slate-500 text-xs uppercase font-semibold">Product</span>
                            @if($tank->product)
                            <div class="flex items-center gap-1.5">
                                <div class="w-2 h-2 rounded-full" style="background-color: {{ $tank->product->color_code }}"></div>
                                <span class="font-bold text-slate-700">{{ $tank->product->code }}</span>
                            </div>
                            @else
                                <span class="text-slate-400 italic">Empty</span>
                            @endif
                        </div>
                        
                        <div class="pt-2 border-t border-slate-100">
                             <div class="flex justify-between items-end mb-1">
                                <span class="text-xl font-bold text-slate-800">{{ number_format($tank->current_volume) }}</span>
                                <span class="text-xs text-slate-400 mb-1">/ {{ number_format($tank->capacity_volume) }} m³</span>
                             </div>
                             <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                 <div class="h-full rounded-full transition-all" style="width: {{ $percentage }}%; background-color: {{ $color }}"></div>
                             </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
