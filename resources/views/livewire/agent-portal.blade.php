<div class="min-h-screen bg-slate-50 font-sans">
    <!-- Top Navigation (Client View) -->
    <div class="bg-white border-b border-slate-200 sticky top-0 z-30">
        <div class="max-w-5xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-black text-lg">P</div>
                <h1 class="font-bold text-slate-900 tracking-tight">PortFlow <span class="text-slate-400 font-normal">Connect</span></h1>
            </div>
            <div class="flex items-center gap-4">
                @if($agent)
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Organization</p>
                        <p class="text-sm font-bold text-slate-900">{{ $agent->name }}</p>
                    </div>
                    <div class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center text-slate-500 font-bold border border-slate-200">
                        {{ substr($agent->name, 0, 2) }}
                    </div>
                @endif
                <div class="h-8 w-px bg-slate-200 mx-2"></div>
                
                {{-- Dashboard Link (Outstanding) --}}
                @php
                    $dashboardRoute = auth()->user()->role === 'hse' ? 'hse.permits.dashboard' : 'dashboard';
                @endphp
                <a href="{{ route($dashboardRoute) }}" class="group relative px-4 py-2 bg-gradient-to-r from-indigo-600 to-violet-600 rounded-xl font-bold text-white text-xs uppercase tracking-widest shadow-lg shadow-indigo-900/20 hover:scale-105 active:scale-95 transition-all flex items-center gap-2 overflow-hidden">
                    <div class="absolute inset-0 bg-white/20 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700 skew-x-12"></div>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span>Management Console</span>
                </a>

                <div class="h-8 w-px bg-slate-200 mx-2"></div>
                <a href="{{ route('logout') }}" class="text-xs font-bold text-slate-500 hover:text-indigo-600 uppercase tracking-widest transition-colors flex items-center gap-1">
                    Sign Out <span aria-hidden="true">&rarr;</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-5xl mx-auto px-6 py-8">
        
        <!-- Welcome Hero -->
        <div class="mb-10">
            <h2 class="text-3xl font-black text-slate-900 tracking-tight mb-2">Fleet Overview</h2>
            <p class="text-slate-500 text-lg">Track your vessels in real-time across the port.</p>
        </div>

        <!-- Live Operations Stats (Only on Live Tab) -->
        @if($activeTab === 'live')
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-indigo-600 rounded-2xl p-6 text-white shadow-xl shadow-indigo-900/20 relative overflow-hidden group">
                <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 bg-white/10 rounded-full blur-xl group-hover:bg-white/20 transition-all"></div>
                <p class="text-indigo-200 text-sm font-bold uppercase tracking-widest mb-1">Live Vessels</p>
                <div class="flex items-end gap-2">
                    <span class="text-5xl font-black">{{ $liveVessels->count() }}</span>
                    <span class="text-indigo-200 font-medium mb-1">Alongisde</span>
                </div>
            </div>

            <div class="bg-emerald-600 rounded-2xl p-6 text-white shadow-xl shadow-emerald-900/20 relative overflow-hidden group">
                <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 bg-white/10 rounded-full blur-xl group-hover:bg-white/20 transition-all"></div>
                <p class="text-emerald-200 text-sm font-bold uppercase tracking-widest mb-1">Yard Storage</p>
                <div class="flex items-end gap-2">
                    <span class="text-5xl font-black">{{ $liveYardItems->count() }}</span>
                    <span class="text-emerald-200 font-medium mb-1">Items</span>
                </div>
            </div>

             <div class="bg-amber-500 rounded-2xl p-6 text-white shadow-xl shadow-amber-900/20 relative overflow-hidden group">
                <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 bg-white/10 rounded-full blur-xl group-hover:bg-white/20 transition-all"></div>
                <p class="text-amber-100 text-sm font-bold uppercase tracking-widest mb-1">Assets On Hire</p>
                <div class="flex items-end gap-2">
                    <span class="text-5xl font-black">{{ $liveAssets->count() }}</span>
                    <span class="text-amber-100 font-medium mb-1">Active</span>
                </div>
            </div>
        </div>
        @endif

        <!-- Quick Actions -->
        @if($activeTab === 'live' || $activeTab === 'scheduled')
        <div class="flex justify-end gap-4 mb-4">
             <button wire:click="openRequestModal" class="text-sm font-bold text-teal-600 hover:text-teal-500 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Request New Berth
            </button>
             <button wire:click="openVesselModal" class="text-sm font-bold text-indigo-600 hover:text-indigo-500 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Register New Vessel
            </button>
        </div>
        @endif

        <!-- Tabs -->
        <div class="flex gap-6 border-b border-slate-200 mb-8 overflow-x-auto">
            <button wire:click="$set('activeTab', 'live')" class="pb-3 text-sm font-bold uppercase tracking-wider transition-all whitespace-nowrap {{ $activeTab === 'live' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-slate-400 hover:text-slate-600' }}">
                Live Operations
            </button>
            <button wire:click="$set('activeTab', 'scheduled')" class="pb-3 text-sm font-bold uppercase tracking-wider transition-all whitespace-nowrap {{ $activeTab === 'scheduled' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-slate-400 hover:text-slate-600' }}">
                Scheduled
            </button>
            <button wire:click="$set('activeTab', 'history')" class="pb-3 text-sm font-bold uppercase tracking-wider transition-all whitespace-nowrap {{ $activeTab === 'history' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-slate-400 hover:text-slate-600' }}">
                History
            </button>
            <button wire:click="$set('activeTab', 'commercial')" class="pb-3 text-sm font-bold uppercase tracking-wider transition-all whitespace-nowrap flex items-center gap-2 {{ $activeTab === 'commercial' ? 'text-emerald-600 border-b-2 border-emerald-600' : 'text-slate-400 hover:text-emerald-600' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Financials
            </button>
        </div>

        @if($activeTab === 'commercial')
            <!-- Consolidated Consolidated Financial Dashboard -->
            <div class="space-y-8 animate-in fade-in duration-300">
                <!-- 1. Total Aggregated Exposure (The "Money" Card) -->
                <div class="bg-slate-900 rounded-3xl p-8 text-white relative overflow-hidden shadow-2xl">
                    <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-indigo-500 rounded-full blur-3xl opacity-20"></div>
                    <div class="relative z-10 text-center">
                         <p class="text-slate-400 font-bold uppercase tracking-[0.2em] text-xs mb-2">Total Unbilled Exposure</p>
                         <h2 class="text-5xl font-black tracking-tight text-white mb-4">RM {{ number_format($total_exposure, 2) }}</h2>
                         <p class="text-sm text-slate-400">Consolidated running costs across all port services.</p>
                    </div>
                </div>

                <!-- 2. Interactive KPI Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Marine Ops Card -->
                    <div wire:click="toggleSection('marine')" class="cursor-pointer group bg-white rounded-3xl p-6 border-2 transition-all hover:shadow-xl relative overflow-hidden {{ $activeSection === 'marine' ? 'border-indigo-600 ring-4 ring-indigo-50' : 'border-slate-100 hover:border-indigo-300' }}">
                        <div class="flex justify-between items-start mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <span class="px-2 py-1 rounded-lg bg-indigo-100 text-indigo-700 font-bold text-[10px] uppercase group-hover:bg-indigo-600 group-hover:text-white transition-colors">Marine Ops</span>
                        </div>
                        <p class="text-3xl font-black text-slate-900 mb-1">RM {{ number_format($marine['exposure'], 2) }}</p>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ $marine['count'] }} Active Vessels</p>
                        
                        @if($activeSection === 'marine')
                        <div class="absolute inset-x-0 bottom-0 h-1 bg-indigo-600"></div>
                        @endif
                    </div>

                    <!-- Yard Ops Card -->
                    <div wire:click="toggleSection('yard')" class="cursor-pointer group bg-white rounded-3xl p-6 border-2 transition-all hover:shadow-xl relative overflow-hidden {{ $activeSection === 'yard' ? 'border-emerald-600 ring-4 ring-emerald-50' : 'border-slate-100 hover:border-emerald-300' }}">
                         <div class="flex justify-between items-start mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            </div>
                            <span class="px-2 py-1 rounded-lg bg-emerald-100 text-emerald-700 font-bold text-[10px] uppercase group-hover:bg-emerald-600 group-hover:text-white transition-colors">Yard Storage</span>
                        </div>
                        <p class="text-3xl font-black text-slate-900 mb-1">RM {{ number_format($yard['exposure'], 2) }}</p>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ $yard['count'] }} Items Stored</p>

                        @if($activeSection === 'yard')
                        <div class="absolute inset-x-0 bottom-0 h-1 bg-emerald-600"></div>
                        @endif
                    </div>

                    <!-- Asset Ops Card -->
                    <div wire:click="toggleSection('assets')" class="cursor-pointer group bg-white rounded-3xl p-6 border-2 transition-all hover:shadow-xl relative overflow-hidden {{ $activeSection === 'assets' ? 'border-amber-500 ring-4 ring-amber-50' : 'border-slate-100 hover:border-amber-300' }}">
                         <div class="flex justify-between items-start mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                            </div>
                            <span class="px-2 py-1 rounded-lg bg-amber-100 text-amber-700 font-bold text-[10px] uppercase group-hover:bg-amber-600 group-hover:text-white transition-colors">Equipment</span>
                        </div>
                        <p class="text-3xl font-black text-slate-900 mb-1">RM {{ number_format($assets['exposure'], 2) }}</p>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ $assets['count'] }} Active Rentals</p>

                        @if($activeSection === 'assets')
                        <div class="absolute inset-x-0 bottom-0 h-1 bg-amber-500"></div>
                        @endif
                    </div>
                </div>

                <!-- 3. Drill Down Detail Sections -->
                
                <!-- MARINE DETAILS -->
                @if($activeSection === 'marine')
                <div class="bg-white rounded-3xl border border-indigo-100 shadow-xl shadow-indigo-900/10 overflow-hidden animate-in slide-in-from-top-4 duration-300">
                    <div class="p-6 border-b border-indigo-50 bg-indigo-50/50">
                        <h3 class="font-black text-indigo-900 uppercase tracking-widest text-xs">Live Marine Charges</h3>
                    </div>
                    <div class="p-6">
                        @forelse($marine['items'] as $call)
                        <div class="flex items-center justify-between p-4 bg-white border border-slate-100 rounded-xl mb-2 hover:border-indigo-200 transition-colors">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-lg bg-slate-50 flex items-center justify-center font-bold text-slate-400">
                                    {{ substr($call->vessel->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-900">{{ $call->vessel->name }}</p>
                                    <p class="text-xs text-slate-500 uppercase">
                                        {{ $call->berth->name ?? 'Unassigned' }} • 
                                        @if($call->atb)
                                        Berth: {{ $call->atb->format('d M H:i') }}
                                        @else
                                        ETA: {{ $call->eta->format('d M H:i') }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                 <p class="font-black text-indigo-600">RM {{ number_format($call->invoice->total_amount ?? 0, 2) }}</p>
                                 <p class="text-[10px] font-bold text-slate-400 uppercase">Running Total</p>
                            </div>
                        </div>
                        @empty
                        <p class="text-center text-slate-400 text-sm">No active marine operations.</p>
                        @endforelse
                    </div>
                </div>
                @endif

                <!-- YARD DETAILS -->
                @if($activeSection === 'yard')
                <div class="bg-white rounded-3xl border border-emerald-100 shadow-xl shadow-emerald-900/10 overflow-hidden animate-in slide-in-from-top-4 duration-300">
                     <div class="p-6 border-b border-emerald-50 bg-emerald-50/50">
                        <h3 class="font-black text-emerald-900 uppercase tracking-widest text-xs">Warehouse & Yard Storage</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-emerald-50/30 text-slate-500 font-bold uppercase tracing-wider border-b border-emerald-50">
                                <tr>
                                    <th class="px-6 py-4">Consignment</th>
                                    <th class="px-6 py-4">Zone</th>
                                    <th class="px-6 py-4 text-center">Volume</th>
                                    <th class="px-6 py-4 text-center">Days</th>
                                    <th class="px-6 py-4 text-right">Est. Cost</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-emerald-50">
                                @foreach($yard['items'] as $item)
                                <tr class="hover:bg-emerald-50/20 transition-colors">
                                    <td class="px-6 py-4">
                                        <p class="font-bold text-slate-900">{{ $item['tracking_number'] }}</p>
                                        <p class="text-[10px] text-slate-400 truncate max-w-[200px]">{{ $item['description'] }}</p>
                                        @if($item['is_dg'])
                                            <span class="text-[9px] font-black text-red-600 bg-red-50 px-1 py-0.5 rounded border border-red-100 uppercase ml-1">DG Cargo</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 rounded-lg bg-white border border-slate-200 text-slate-600 font-bold text-[10px] uppercase">{{ $item['zone_type'] }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center font-bold text-slate-700">{{ $item['volume_m3'] }} m³</td>
                                    <td class="px-6 py-4 text-center font-bold text-slate-700">{{ $item['days_stored'] }}</td>
                                    <td class="px-6 py-4 text-right font-black text-emerald-700">RM {{ number_format($item['total'], 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                <!-- ASSET DETAILS -->
                @if($activeSection === 'assets')
                <div class="bg-white rounded-3xl border border-amber-100 shadow-xl shadow-amber-900/10 overflow-hidden animate-in slide-in-from-top-4 duration-300">
                    <div class="p-6 border-b border-amber-50 bg-amber-50/50">
                        <h3 class="font-black text-amber-900 uppercase tracking-widest text-xs">Machine & Equipment Rentals</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse($assets['items'] as $booking)
                        <div class="flex items-center justify-between p-4 bg-white border border-slate-100 rounded-xl hover:border-amber-300 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-lg">
                                    {{ substr($booking->asset->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-900">{{ $booking->asset->name }}</p>
                                    <p class="text-xs text-slate-500 uppercase">{{ $booking->reference_no }} • {{ $booking->start_time->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                 @php
                                    $hours = max(1, now()->diffInHours($booking->start_time));
                                    $estCost = $hours * ($booking->asset->rate_per_hour ?? 0);
                                 @endphp
                                 <p class="font-black text-amber-600">RM {{ number_format($estCost, 2) }}</p>
                                 <p class="text-[10px] font-bold text-slate-400 uppercase">Est. Cost</p>
                            </div>
                        </div>
                        @empty
                         <p class="text-center text-slate-400 text-sm col-span-2">No active rentals.</p>
                        @endforelse
                    </div>
                </div>
                @endif

                <!-- Billing History (Always Visible at Bottom) -->
                <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm">
                    <h3 class="font-black text-slate-900 uppercase tracking-widest text-xs mb-6 flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Recent Invoices
                    </h3>
                     <div class="space-y-4">
                        @forelse($invoices as $invoice)
                        <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 border border-slate-100 hover:border-indigo-200 transition-colors group cursor-pointer">
                            <div class="flex items-center gap-4">
                                 <div class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                 </div>
                                <div>
                                    <p class="text-sm font-black text-slate-900">{{ $invoice->invoice_no }}</p>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase">{{ $invoice->issued_date->format('d M Y') }} • {{ $invoice->status }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-6">
                                <p class="text-lg font-black text-slate-900">RM {{ number_format($invoice->total_amount, 2) }}</p>
                                <a href="{{ route('invoice.print', $invoice->id) }}" target="_blank" class="px-4 py-2 bg-white rounded-lg border border-slate-200 text-slate-600 text-xs font-bold uppercase tracking-wider hover:bg-slate-50 transition-all">
                                    Download PDF
                                </a>
                            </div>
                        </div>
                        @empty
                        <p class="text-center text-slate-400 text-sm py-4">No invoices generated yet.</p>
                        @endforelse
                    </div>
                </div>

            </div>
            
        @elseif($activeTab === 'live')
        <!-- Live Operations (Marine, Yard, Assets) -->
        <div class="space-y-8 animate-in fade-in duration-300">
            
            <!-- 1. Marine Operations -->
            <div class="space-y-4">
                <h3 class="font-black text-indigo-900 uppercase tracking-widest text-xs flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-indigo-500"></span> Marine Operations
                </h3>
                @forelse($liveVessels as $call)
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:border-indigo-300 transition-all group relative overflow-hidden">
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-indigo-500"></div>
                    <div class="pl-4 flex flex-col md:flex-row items-center justify-between gap-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-lg">
                                {{ substr($call->vessel->name, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="font-bold text-lg text-slate-900">{{ $call->vessel->name }}</h3>
                                <p class="text-sm text-slate-500">{{ $call->berth->name ?? 'Unassigned' }} • {{ $call->status }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                             <p class="text-xs font-bold text-slate-400 uppercase">Live Charges</p>
                             <p class="font-black text-indigo-600">RM {{ number_format($call->invoice->total_amount ?? 0, 2) }}</p>
                             <button wire:click="openServiceModal({{ $call->id }})" class="mt-2 text-[10px] bg-indigo-50 text-indigo-700 px-2 py-1 rounded border border-indigo-200 font-bold hover:bg-indigo-100 uppercase tracking-wide">
                                + Service
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-6 bg-slate-50 rounded-xl border border-dashed border-slate-200 text-slate-400 text-sm">No active marine operations.</div>
                @endforelse
            </div>

            <!-- 2. Yard Operations -->
            <div class="space-y-4">
                <h3 class="font-black text-emerald-900 uppercase tracking-widest text-xs flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Yard Storage
                </h3>
                @if($liveYardItems->count() > 0)
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50 text-slate-500 font-bold uppercase border-b border-slate-100">
                                <tr>
                                    <th class="px-6 py-3">Consignment</th>
                                    <th class="px-6 py-3">Zone</th>
                                    <th class="px-6 py-3 text-center">Volume</th>
                                    <th class="px-6 py-3 text-center">Received</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($liveYardItems as $item)
                                <tr class="hover:bg-emerald-50/10">
                                    <td class="px-6 py-3 block">
                                        <span class="font-bold text-slate-900">{{ $item->tracking_number }}</span>
                                        @if($item->is_dg_cargo)
                                            <span class="ml-2 text-[9px] font-bold text-red-600 bg-red-50 px-1 rounded uppercase">DG</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3">{{ $item->zone->zone_name ?? 'General' }}</td>
                                    <td class="px-6 py-3 text-center">{{ $item->volume_m3 }} m³</td>
                                    <td class="px-6 py-3 text-center">{{ $item->received_at->format('d M H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @else
                <div class="text-center py-6 bg-slate-50 rounded-xl border border-dashed border-slate-200 text-slate-400 text-sm">No cargo in storage.</div>
                @endif
            </div>

            <!-- 3. Asset Operations -->
            <div class="space-y-4">
                <h3 class="font-black text-amber-900 uppercase tracking-widest text-xs flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span> Equipment Rentals
                </h3>
                @forelse($liveAssets as $booking)
                <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm hover:border-amber-300 transition-all flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                            {{ substr($booking->asset->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-bold text-slate-900">{{ $booking->asset->name }}</p>
                            <p class="text-xs text-slate-500 uppercase">Deployed: {{ $booking->start_time->format('d M H:i') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        @php
                            $hours = max(1, now()->diffInHours($booking->start_time));
                            $estCost = $hours * ($booking->asset->rate_per_hour ?? 0);
                        @endphp
                        <p class="font-black text-amber-600">RM {{ number_format($estCost, 2) }}</p>
                        <span class="text-[10px] bg-amber-100 text-amber-700 px-2 py-1 rounded font-bold uppercase">Active</span>
                    </div>
                </div>
                @empty
                <div class="text-center py-6 bg-slate-50 rounded-xl border border-dashed border-slate-200 text-slate-400 text-sm">No active equipment rentals.</div>
                @endforelse
            </div>
        </div>

        @elseif($activeTab === 'scheduled')
        <!-- Scheduled Berthing Requests -->
        <div class="space-y-4 animate-in fade-in duration-300">
            @forelse($portCalls as $call)
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-lg transition-all relative overflow-hidden">
                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-indigo-500"></div>
                <div class="pl-4 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center font-bold text-lg">
                            {{ substr($call->vessel->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-slate-900">{{ $call->vessel->name }}</h3>
                            <p class="text-sm text-slate-500">{{ $call->vessel->vessel_type }} • {{ $call->status }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-bold text-slate-400 uppercase">ETA</p>
                        <p class="font-bold text-slate-900">{{ $call->eta->format('d M, H:i') }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-12 bg-white rounded-2xl border border-dashed border-slate-300">
                <div class="inline-flex justify-center items-center w-16 h-16 rounded-full bg-slate-50 text-slate-300 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">No Scheduled Arrivals</h3>
                <p class="text-slate-500">You don't have any pending berthing requests.</p>
            </div>
            @endforelse
        </div>

        @elseif($activeTab === 'history')
        <!-- Unified History Timeline -->
        <div class="space-y-4 animate-in fade-in duration-300">
            @forelse($history as $item)
            <div class="bg-white rounded-2xl p-4 border border-slate-200 hover:border-slate-300 transition-all flex items-center justify-between group">
                <div class="flex items-center gap-4">
                    <!-- Icon based on type -->
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-lg
                        {{ $item['type'] === 'marine' ? 'bg-indigo-50 text-indigo-600' : 
                           ($item['type'] === 'yard' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600') }}">
                        @if($item['type'] === 'marine') ⚓ @elseif($item['type'] === 'yard') 📦 @else 🚜 @endif
                    </div>
                    <div>
                        <p class="font-bold text-slate-900">{{ $item['description'] }}</p>
                        <p class="text-xs text-slate-500 uppercase">{{ $item['reference'] }} • {{ $item['date']->format('d M Y, H:i') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        @if($item['amount'] > 0)
                        <p class="font-bold text-slate-900">RM {{ number_format($item['amount'], 2) }}</p>
                        @endif
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">{{ $item['status'] }}</span>
                    </div>
                    @if($item['invoice_id'])
                    <a href="{{ route('invoice.print', $item['invoice_id']) }}" target="_blank" class="p-2 text-slate-400 hover:text-indigo-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    </a>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-12 bg-white rounded-2xl border border-dashed border-slate-300">
                <p class="text-slate-500">No history records found.</p>
            </div>
            @endforelse
        </div>
        @endif
    </div>

    <!-- New Berth Request Modal -->
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" wire:click="$set('showModal', false)"></div>
        <div class="relative bg-white w-full max-w-lg rounded-3xl shadow-2xl border border-slate-200 overflow-hidden transform transition-all">
            <div class="p-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight">Request New Berth</h3>
                    <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form wire:submit.prevent="saveRequest" class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">Select Vessel</label>
                        <select wire:model="vessel_id" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-700 focus:ring-teal-500 focus:border-teal-500 p-3">
                            <option value="">-- Choose Vessel --</option>
                            @foreach($myVessels as $vessel)
                                <option value="{{ $vessel->id }}">{{ $vessel->name }} ({{ $vessel->vessel_type }})</option>
                            @endforeach
                        </select>
                        @error('vessel_id') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">ETA (Local)</label>
                            <input type="datetime-local" wire:model="eta" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-700 focus:ring-teal-500 focus:border-teal-500 p-3">
                            @error('eta') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">ETD (Local)</label>
                            <input type="datetime-local" wire:model="etd" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-700 focus:ring-teal-500 focus:border-teal-500 p-3">
                            @error('etd') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- AI Smart Suggest Button --}}
                    <div class="flex justify-center">
                        <button type="button" wire:click="getSmartSuggestions" class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white rounded-xl font-bold uppercase tracking-widest shadow-lg shadow-indigo-900/30 transition-all flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                            🤖 Get Smart Suggestions
                        </button>
                    </div>

                    {{-- AI Berth Suggestions --}}
                    @if($showSuggestions && !empty($berthSuggestions))
                    <div class="space-y-3">
                        <h4 class="text-sm font-bold text-slate-700 uppercase tracking-wider">AI Recommendations</h4>
                        
                        @foreach($berthSuggestions as $index => $suggestion)
                        <div wire:click="selectSuggestedBerth({{ $suggestion['berth']->id }})" 
                             class="p-4 rounded-xl border-2 cursor-pointer transition-all
                                {{ $selectedSuggestedBerth == $suggestion['berth']->id ? 'border-indigo-500 bg-indigo-50' : 'border-slate-200 hover:border-indigo-300' }}
                                {{ !$suggestion['available'] ? 'opacity-60' : '' }}">
                            
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    @if($index == 0 && $suggestion['available'])
                                        <span class="text-2xl">🏆</span>
                                    @elseif($suggestion['available'])
                                        <span class="text-2xl">✅</span>
                                    @else
                                        <span class="text-2xl">❌</span>
                                    @endif
                                    <div>
                                        <h5 class="font-bold text-slate-900">{{ $suggestion['berth']->name }}</h5>
                                        <p class="text-xs text-slate-500">{{ $suggestion['berth']->code }} • Max LOA: {{ $suggestion['berth']->max_loa }}m • Max Draft: {{ $suggestion['berth']->max_draft }}m</p>
                                    </div>
                                </div>
                                
                                @if($suggestion['available'])
                                <div class="text-right">
                                    <div class="text-2xl font-black 
                                        {{ $suggestion['score'] >= 90 ? 'text-green-600' : ($suggestion['score'] >= 75 ? 'text-blue-600' : 'text-slate-600') }}">
                                        {{ $suggestion['score'] }}
                                    </div>
                                    <div class="text-[10px] font-bold uppercase tracking-wider
                                        {{ $suggestion['confidence'] == 'very_high' ? 'text-green-600' : ($suggestion['confidence'] == 'high' ? 'text-blue-600' : 'text-slate-500') }}">
                                        {{ str_replace('_', ' ', $suggestion['confidence']) }}
                                    </div>
                                </div>
                                @endif
                            </div>
                            
                            <div class="space-y-1">
                                @foreach($suggestion['reasons'] as $reason)
                                <p class="text-xs text-slate-600">{{ $reason }}</p>
                                @endforeach
                            </div>
                            
                            @if(!empty($suggestion['conflicts']))
                            <div class="mt-2 p-2 bg-red-50 rounded-lg">
                                <p class="text-xs font-bold text-red-700 mb-1">Conflicts:</p>
                                @foreach($suggestion['conflicts'] as $conflict)
                                <p class="text-xs text-red-600">• {{ $conflict['vessel'] }} ({{ $conflict['eta'] }} - {{ $conflict['etd'] }})</p>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <div class="bg-blue-50 p-4 rounded-xl flex gap-3 items-start">
                        <svg class="w-5 h-5 text-blue-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-xs text-blue-700 font-medium">
                            @if($showSuggestions && $selectedSuggestedBerth)
                                AI has selected the optimal berth for you. Click Submit to confirm.
                            @else
                                Click "Get Smart Suggestions" to let AI recommend the best berth for your vessel.
                            @endif
                        </p>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex gap-3">
                         <button type="button" wire:click="$set('showModal', false)" class="px-6 py-3 rounded-xl border border-slate-200 font-bold text-slate-500 hover:bg-slate-50 transition-colors">Cancel</button>
                         <button type="submit" class="flex-1 px-6 py-3 bg-teal-600 hover:bg-teal-500 text-white rounded-xl font-bold uppercase tracking-widest shadow-lg shadow-teal-900/20 transition-all">
                            Submit Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Register Vessel Modal -->
    @if($showVesselModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" wire:click="$set('showVesselModal', false)"></div>
        <div class="relative bg-white w-full max-w-lg rounded-3xl shadow-2xl border border-slate-200 overflow-hidden transform transition-all">
            <div class="p-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight">Register New Vessel</h3>
                    <button wire:click="$set('showVesselModal', false)" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form wire:submit.prevent="saveVessel" class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">Vessel Name</label>
                        <input type="text" wire:model="new_vessel_name" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-700 focus:ring-teal-500 focus:border-teal-500 p-3" placeholder="e.g. MV SEALINK 178" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">IMO Number</label>
                            <input type="text" wire:model="new_vessel_imo" class="w-full bg-slate-50 border-slate-200 rounded-xl font-mono font-bold text-slate-700 focus:ring-teal-500 focus:border-teal-500 p-3" placeholder="9123456" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">Type</label>
                            <select wire:model="new_vessel_type" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-700 focus:ring-teal-500 focus:border-teal-500 p-3">
                                <option>Offshore Support Vessel</option>
                                <option>Tug</option>
                                <option>Barge</option>
                                <option>Landing Craft</option>
                                <option>Tanker</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                         <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">LOA (Meters)</label>
                            <input type="number" step="0.1" wire:model="new_vessel_loa" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-700 focus:ring-teal-500 focus:border-teal-500 p-3" placeholder="0.0 m" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">Max Draft (Meters)</label>
                            <input type="number" step="0.1" wire:model="new_vessel_draft" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-700 focus:ring-teal-500 focus:border-teal-500 p-3" placeholder="0.0 m" required>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex gap-3">
                         <button type="button" wire:click="$set('showVesselModal', false)" class="px-6 py-3 rounded-xl border border-slate-200 font-bold text-slate-500 hover:bg-slate-50 transition-colors">Cancel</button>
                         <button type="submit" class="flex-1 px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-bold uppercase tracking-widest shadow-lg shadow-indigo-900/20 transition-all">
                            Add Vessel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Service Request Modal -->
    @if($showServiceModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" wire:click="$set('showServiceModal', false)"></div>
        <div class="relative bg-white w-full max-w-lg rounded-3xl shadow-2xl border border-slate-200 overflow-hidden transform transition-all">
            <div class="p-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight">Request Port Services</h3>
                    <button wire:click="$set('showServiceModal', false)" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="bg-slate-50 p-4 rounded-xl mb-6">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Vessel</p>
                    <p class="font-bold text-slate-900">{{ $selectedPortCall->vessel->name ?? 'Unknown' }}</p>
                </div>

                <form wire:submit.prevent="saveServiceRequest" class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">Service Type</label>
                        <select wire:model.live="serviceType" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-700 focus:ring-teal-500 focus:border-teal-500 p-3">
                            <option value="water">Fresh Water Supply</option>
                            <option value="fuel">Bunker Fuel</option>
                            <option value="waste">Waste Disposal</option>
                            <option value="crane">Mobile Crane Hire</option>
                            <option value="pilot">Pilotage Extension</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">Quantity</label>
                            <div class="relative">
                                <input type="number" wire:model="serviceQuantity" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-700 focus:ring-teal-500 focus:border-teal-500 p-3 pr-12" required>
                                <span class="absolute right-4 top-3.5 text-xs font-bold text-slate-400">{{ $serviceUnit }}</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">Required Date</label>
                            <input type="datetime-local" wire:model="serviceDate" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-700 focus:ring-teal-500 focus:border-teal-500 p-3" required>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex gap-3">
                         <button type="button" wire:click="$set('showServiceModal', false)" class="px-6 py-3 rounded-xl border border-slate-200 font-bold text-slate-500 hover:bg-slate-50 transition-colors">Cancel</button>
                         <button type="submit" class="flex-1 px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-bold uppercase tracking-widest shadow-lg shadow-indigo-900/20 transition-all">
                            Submit Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Toast Component -->
    <div x-data="{ toast: { show: false, message: '' }, showToast(msg) { this.toast = { show: true, message: msg }; setTimeout(() => this.toast.show = false, 3000); } }"
         @notify.window="showToast($event.detail.message)"
         class="fixed bottom-4 right-4 z-[70]"
         style="display: none;"
         x-show="toast.show"
         x-transition.duration.300ms>
        <div class="bg-indigo-600 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center justify-center gap-3 font-bold">
            <span class="text-2xl">📨</span>
            <span x-text="toast.message"></span>
        </div>
    </div>
</div>
