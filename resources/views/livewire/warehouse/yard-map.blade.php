<div class="p-8 h-full flex flex-col">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Yard Density Map</h1>
            <p class="text-slate-500 mt-1">Real-time warehouse utilization and aging cargo visualization.</p>
        </div>
        <div class="flex items-center gap-4 text-sm font-bold text-slate-500">
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 rounded-full bg-emerald-400"></div> < 50%
            </div>
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 rounded-full bg-amber-400"></div> 50-80%
            </div>
             <div class="flex items-center gap-2">
                <div class="w-3 h-3 rounded-full bg-red-500"></div> > 80% (Critical)
            </div>
        </div>
    </div>

    <div class="flex-1 grid grid-cols-3 gap-8 min-h-0">
        <!-- Map Visualization -->
        <div class="col-span-2 space-y-8 overflow-y-auto pr-2 pb-8">
            @foreach($warehouses as $warehouse)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">{{ $warehouse->name }}</h2>
                        <span class="text-xs uppercase tracking-wide text-slate-400 font-bold border border-slate-200 px-2 py-1 rounded-md">{{ $warehouse->type }}</span>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-black text-slate-900">{{ number_format($warehouse->total_capacity_m3) }} <span class="text-sm text-slate-400 font-medium">M³</span></div>
                        <div class="text-xs text-slate-400 uppercase font-bold">Total Capacity</div>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($warehouse->zones as $zone)
                        @php
                            $util = $zone->utilization_percentage;
                            $color = $util > 80 ? 'bg-red-500' : ($util > 50 ? 'bg-amber-400' : 'bg-emerald-400');
                            $textColor = $util > 80 ? 'text-white' : 'text-slate-900';
                            $subTextColor = $util > 80 ? 'text-red-100' : 'text-slate-600';
                        @endphp
                        <div class="relative group cursor-pointer hover:scale-[1.02] transition-transform">
                            <div class="h-32 rounded-xl {{ $color }} p-4 flex flex-col justify-between shadow-lg shadow-slate-200">
                                <div class="flex justify-between items-start">
                                    <span class="font-bold {{ $textColor }} text-sm">{{ $zone->code }}</span>
                                    @if($zone->is_dg_allowed)
                                        <span class="bg-red-900/20 text-white px-1.5 py-0.5 rounded text-[10px] font-bold border border-white/20">DG</span>
                                    @endif
                                </div>
                                <div>
                                    <div class="text-2xl font-black {{ $textColor }}">{{ round($util) }}%</div>
                                    <div class="text-[10px] font-bold uppercase {{ $subTextColor }}">
                                        {{ number_format($zone->utilization) }} / {{ number_format($zone->capacity_limit_m3) }} M³
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

        <!-- Aging Reports Sidebar -->
        <div class="bg-slate-50 border-l border-slate-200 -my-8 p-8 overflow-y-auto">
            <h3 class="font-black text-xl text-slate-800 mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Aging Cargo (>90 Days)
            </h3>
            
            <div class="space-y-4">
                @forelse($agingItems as $item)
                <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200">
                    <div class="flex justify-between items-start mb-2">
                        <span class="bg-red-100 text-red-600 text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wide">
                            {{ $item->created_at->diffInDays() }} Days
                        </span>
                        <a href="{{ route('cargo.manifests.show', $item->cargo_manifest_id) }}" class="text-indigo-600 hover:underline text-xs font-bold">View</a>
                    </div>
                    <h4 class="font-bold text-slate-800 text-sm leading-tight mb-1">{{ $item->description }}</h4>
                    <p class="text-xs font-mono text-slate-400 mb-2">{{ $item->tracking_number }}</p>
                    
                    <div class="flex items-center gap-2 text-xs text-slate-500 pt-2 border-t border-slate-50">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span>{{ $item->zone->code ?? 'Unassigned' }}</span>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-slate-400">
                    <svg class="w-12 h-12 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-sm font-medium">No aging cargo found.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
