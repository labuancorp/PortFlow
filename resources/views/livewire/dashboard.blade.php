<div class="p-8 bg-slate-50 min-h-screen font-sans relative">
    <!-- Header -->
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Command Center</h1>
            <p class="text-slate-500 mt-1">Real-time overview of port operations for {{ $now->format('l, d M Y') }}</p>
        </div>
        <div class="flex space-x-3">
            <button type="button" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-lg hover:bg-slate-50 font-medium shadow-sm flex items-center gap-2 transition-colors">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Export Snapshot
            </button>
            <button type="button" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium shadow-sm flex items-center gap-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                Log Alert
            </button>
        </div>
    </div>

    <!-- Notification Toast -->
    @if (session()->has('success'))
        <div x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 5000)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 lg:translate-x-full"
             x-transition:enter-end="opacity-100 lg:translate-x-0"
             class="fixed top-8 right-8 z-[60] bg-slate-900 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-4 border border-slate-700">
            <div class="bg-teal-500/20 p-2 rounded-lg">
                <svg class="w-6 h-6 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <div>
                <h4 class="font-bold text-sm tracking-wide uppercase text-teal-400">System Notification</h4>
                <p class="text-sm text-slate-300">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if($mode === 'admin')
        <!-- ADMIN VIEW -->
        <div class="bg-gradient-to-br from-slate-50 to-indigo-50/20 rounded-3xl p-8 mb-8 border border-indigo-100/30">
            <!-- KPI Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Pending Billing -->
                <div class="bg-gradient-to-br from-emerald-50 to-teal-50 p-6 rounded-2xl border border-emerald-200 shadow-sm">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-xs font-bold text-emerald-600/70 uppercase tracking-widest">Pending Billing</p>
                            <h3 class="text-3xl font-black text-emerald-900 mt-2">RM {{ number_format($pendingBilling['total_pending'], 0) }}</h3>
                        </div>
                        <div class="p-2 bg-emerald-200/50 rounded-lg text-emerald-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-emerald-700">
                        <span class="flex h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        {{ $pendingBilling['count'] }} active units
                    </div>
                </div>

                <!-- Unpaid Invoices -->
                <button type="button" wire:click="openUnpaidModal" class="bg-gradient-to-br from-rose-50 to-pink-50 p-6 rounded-2xl border border-rose-200 shadow-sm hover:shadow-lg transition-all text-left group">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-xs font-bold text-rose-600/70 uppercase tracking-widest">Unpaid Invoices</p>
                            <h3 class="text-3xl font-black text-rose-900 mt-2">RM {{ number_format($unpaidInvoices['total'], 0) }}</h3>
                        </div>
                        <div class="p-2 bg-rose-200/50 rounded-lg group-hover:bg-rose-300 transition-colors text-rose-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-xs text-rose-700">
                        <span>{{ $unpaidInvoices['count'] }} outstanding invoices</span>
                        <span class="font-bold group-hover:translate-x-1 transition-transform">View →</span>
                    </div>
                </button>

                <!-- Berth Occupancy -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-6 rounded-2xl border border-blue-200 shadow-sm">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-xs font-bold text-blue-600/70 uppercase tracking-widest">Berth Occupancy</p>
                            <h3 class="text-3xl font-black text-blue-900 mt-2">{{ $stats['occupancy_rate'] }}%</h3>
                        </div>
                        <div class="p-2 bg-blue-200/50 rounded-lg text-blue-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                    </div>
                    <div class="text-xs text-blue-700">{{ $stats['alongside'] }} vessels alongside</div>
                </div>

                <!-- Service Requests -->
                <div class="bg-gradient-to-br from-amber-50 to-orange-50 p-6 rounded-2xl border border-amber-200 shadow-sm">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-xs font-bold text-amber-600/70 uppercase tracking-widest">Active Requests</p>
                            <h3 class="text-3xl font-black text-amber-900 mt-2">{{ $activeServiceRequests }}</h3>
                        </div>
                        <div class="p-2 bg-amber-200/50 rounded-lg text-amber-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        </div>
                    </div>
                    <div class="text-xs text-amber-700">Pending & in progress</div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mb-8">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Quick Actions</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <a href="{{ route('home') }}" class="group bg-indigo-50 p-4 rounded-xl border border-indigo-100 hover:bg-indigo-100 transition-all flex items-center gap-3">
                        <div class="p-2 bg-white rounded-lg text-indigo-600 shadow-sm group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <span class="font-bold text-indigo-900 text-sm">Berth Planner</span>
                    </a>
                    <a href="{{ route('ops.mobile') }}" class="group bg-indigo-50 p-4 rounded-xl border border-indigo-100 hover:bg-indigo-100 transition-all flex items-center gap-3">
                        <div class="p-2 bg-white rounded-lg text-indigo-600 shadow-sm group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        </div>
                        <span class="font-bold text-indigo-900 text-sm">Mobile Ops</span>
                    </a>
                    <a href="{{ route('warehouse.map') }}" class="group bg-purple-50 p-4 rounded-xl border border-purple-100 hover:bg-purple-100 transition-all flex items-center gap-3">
                        <div class="p-2 bg-white rounded-lg text-purple-600 shadow-sm group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <span class="font-bold text-purple-900 text-sm">Manage Yard</span>
                    </a>
                    <a href="{{ route('gate.scanner') }}" class="group bg-emerald-50 p-4 rounded-xl border border-emerald-100 hover:bg-emerald-100 transition-all flex items-center gap-3">
                        <div class="p-2 bg-white rounded-lg text-emerald-600 shadow-sm group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        </div>
                        <span class="font-bold text-emerald-900 text-sm">Gate Scanner</span>
                    </a>
                    <a href="{{ route('admin.audit') }}" class="group bg-cyan-50 p-4 rounded-xl border border-cyan-100 hover:bg-cyan-100 transition-all flex items-center gap-3">
                        <div class="p-2 bg-white rounded-lg text-cyan-600 shadow-sm group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                        <span class="font-bold text-cyan-900 text-sm">Analytics</span>
                    </a>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-8">
                    <!-- Recent Activity -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/20">
                            <h3 class="font-bold text-slate-900">Recent Port Activity</h3>
                            <span class="text-xs text-slate-400 font-bold uppercase tracking-widest">{{ $recentActivity->count() }} EVENTS</span>
                        </div>
                        <div class="divide-y divide-slate-100">
                            @foreach($recentActivity as $activity)
                            <div class="p-6 flex items-center justify-between hover:bg-slate-50 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 font-bold text-sm">
                                        {{ substr(optional($activity->vessel)->name ?? 'V', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 text-sm">{{ optional($activity->vessel)->name ?? 'Unknown Vessel' }}</p>
                                        <p class="text-xs text-slate-500">{{ optional($activity->agent)->name ?? 'Unknown Agent' }} • {{ ucfirst($activity->status) }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs font-bold text-indigo-600">{{ $activity->berth->name ?? 'Unassigned' }}</p>
                                    <p class="text-[10px] text-slate-400 uppercase font-black">{{ $activity->updated_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Pending Approvals -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-amber-50/30">
                            <h3 class="font-bold text-slate-900">Pending Arrival Requests</h3>
                            <span class="px-2 py-1 rounded-md bg-amber-100 text-amber-700 text-[10px] font-black uppercase">{{ count($pendingRequests) }} PENDING</span>
                        </div>
                        <div class="divide-y divide-slate-100">
                            @forelse($pendingRequests as $request)
                            <div class="p-6 flex items-center justify-between hover:bg-slate-50 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 text-sm">{{ optional($request->vessel)->name ?? 'Unknown Vessel' }}</p>
                                        <p class="text-xs text-slate-500">ETA: {{ optional($request->eta)->format('d M — H:i') ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <button type="button" wire:click="openReviewModal({{ $request->id }})" class="px-4 py-2 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-lg hover:bg-indigo-600 transition-all">Review</button>
                            </div>
                            @empty
                            <div class="p-12 text-center text-slate-400 italic text-sm">No pending requests</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="space-y-8">
                    <!-- Top Agents -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                        <h3 class="font-bold text-slate-900 mb-6">Top Agents (Revenue)</h3>
                        <div class="space-y-4">
                            @foreach($topAgents as $index => $agent)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-full bg-slate-100 text-[10px] font-black flex items-center justify-center text-slate-500">{{ $index + 1 }}</span>
                                    <span class="text-sm font-bold text-slate-700">{{ $agent->organization->name }}</span>
                                </div>
                                <span class="text-sm font-black text-emerald-600">RM {{ number_format($agent->revenue, 0) }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Operational Notices -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 overflow-hidden relative">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="font-bold text-slate-900">Notices</h3>
                            <button type="button" wire:click="toggleAlertsModal" class="text-[10px] font-black text-indigo-600 uppercase tracking-widest">History</button>
                        </div>
                        <div class="space-y-4">
                            @foreach(array_slice($activeAlerts, 0, 3) as $alert)
                            <div class="pl-4 border-l-4 {{ $alert['type'] === 'critical' ? 'border-red-500' : 'border-amber-500' }}">
                                <p class="text-sm font-bold text-slate-800">{{ $alert['title'] }}</p>
                                <p class="text-xs text-slate-500 mt-1 line-clamp-2">{{ $alert['message'] }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @elseif($mode === 'agent')
        <!-- AGENT VIEW -->
        <div class="space-y-8">
            <!-- Hero Live Billing -->
            <div class="bg-gradient-to-br from-indigo-900 via-slate-900 to-black rounded-3xl p-10 text-white relative overflow-hidden shadow-2xl">
                <div class="relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <div class="flex items-center gap-3 mb-6">
                            <span class="flex h-3 w-3 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span class="text-xs font-black uppercase tracking-[0.3em] text-emerald-400">Live Port Charges</span>
                        </div>
                        <h2 class="text-6xl font-black tracking-tighter mb-4">RM {{ number_format($liveBilling['total_charges'], 2) }}</h2>
                        <p class="text-indigo-200/70 max-w-md text-lg leading-relaxed font-medium">Accumulated real-time charges for your active vessels and yard storage.</p>
                    </div>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="bg-white/10 backdrop-blur-md p-6 rounded-2xl border border-white/10">
                            <p class="text-[10px] font-black uppercase tracking-widest text-indigo-300 mb-2">Berthing Fees</p>
                            <p class="text-2xl font-black">RM {{ number_format($liveBilling['berthing_charges'], 2) }}</p>
                            <p class="text-[10px] text-indigo-300/50 mt-1">{{ $liveBilling['berthing_vessels'] }} active vessels</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-md p-6 rounded-2xl border border-white/10">
                            <p class="text-[10px] font-black uppercase tracking-widest text-indigo-300 mb-2">Yard Storage</p>
                            <p class="text-2xl font-black">RM {{ number_format($liveBilling['warehouse_charges'], 2) }}</p>
                            <p class="text-[10px] text-indigo-300/50 mt-1">Daily accumulation</p>
                        </div>
                    </div>
                </div>
                <!-- Decorative element -->
                <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-500/10 rounded-full blur-[100px] -mr-48 -mt-48"></div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <a href="{{ route('vessels.index') }}" class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm hover:shadow-xl transition-all group">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Active Vessels</p>
                    <h3 class="text-4xl font-black text-slate-900 group-hover:text-indigo-600 transition-colors">{{ $stats['active_vessels'] }}</h3>
                </a>
                <a href="{{ route('home') }}" class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm hover:shadow-xl transition-all group">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Pending Requests</p>
                    <h3 class="text-4xl font-black text-slate-900 group-hover:text-amber-600 transition-colors">{{ $stats['pending_requests'] }}</h3>
                </a>
                <a href="{{ route('billing.index') }}" class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm hover:shadow-xl transition-all group">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Unpaid Invoices</p>
                    <h3 class="text-4xl font-black text-slate-900 group-hover:text-rose-600 transition-colors">{{ $stats['unpaid_invoices'] }}</h3>
                </a>
            </div>

            <!-- Activity -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-8 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-xl font-bold text-slate-900">Recent Fleet Activity</h3>
                    <a href="{{ route('home') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 transition-colors">View Berth Calendar →</a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 divide-x divide-y divide-slate-100">
                    @forelse($recentActivity as $activity)
                    <div class="p-8 hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 rounded-full bg-slate-900 flex items-center justify-center text-white font-black text-sm">
                                {{ substr(optional($activity->vessel)->name ?? 'V', 0, 1) }}
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900">{{ optional($activity->vessel)->name ?? 'Unknown Vessel' }}</h4>
                                <span class="text-[10px] font-black uppercase text-slate-400">{{ optional($activity->vessel)->vessel_type ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-end">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Current Status</p>
                                <span class="px-2 py-1 rounded-md bg-indigo-50 text-indigo-700 text-[10px] font-black uppercase tracking-wider">{{ $activity->status }}</span>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Assigned Berth</p>
                                <p class="text-sm font-black text-slate-700">{{ $activity->berth->name ?? 'TBA' }}</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-20 text-center col-span-full border-0">
                        <p class="text-slate-400 italic">No recent activity detected.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif

    <!-- MODALS SECTION -->
    
    <!-- 1. Unpaid Invoices Modal (Admin) -->
    @if($showUnpaidModal)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="closeModal"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-3xl overflow-hidden animate-in fade-in zoom-in duration-200">
            <div class="px-10 py-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <div>
                    <h2 class="text-2xl font-black text-slate-900">Outstanding Invoices</h2>
                    <p class="text-sm text-slate-500 font-medium">Review and monitor unpaid agent balances</p>
                </div>
                <button type="button" wire:click="closeModal" class="p-3 hover:bg-slate-200/50 rounded-xl transition-colors">
                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-10 max-h-[60vh] overflow-y-auto">
                <div class="space-y-4">
                    @forelse($unpaidInvoicesList as $invoice)
                    <div class="flex items-center justify-between p-5 bg-slate-50 rounded-2xl hover:bg-slate-100/80 transition-all border border-slate-100">
                        <div class="flex-1">
                            <p class="font-black text-slate-900 tracking-tight">{{ optional($invoice->organization)->name ?? 'Unknown Agent' }}</p>
                            <p class="text-xs text-slate-400 font-bold mt-1">INV #{{ $invoice->invoice_no }} • {{ $invoice->created_at->format('d M Y') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xl font-black text-rose-600 tracking-tighter">RM {{ number_format($invoice->total_amount, 2) }}</p>
                            <span class="inline-block px-2 py-0.5 mt-1.5 text-[10px] font-black uppercase rounded-md bg-rose-100 text-rose-700 tracking-widest">Unpaid</span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-12">
                        <p class="text-slate-400 font-bold italic">No unpaid invoices found.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- 2. Review Request Modal (Admin) -->
    @if($showReviewModal && $selectedBooking)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="closeModal"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-xl overflow-hidden animate-in fade-in zoom-in duration-200">
            <div class="p-10">
                <h3 class="text-2xl font-black text-slate-900 mb-2">Review Arrival Request</h3>
                <p class="text-slate-500 text-sm font-medium mb-8">Authorize berth allocation for <span class="text-indigo-600 font-bold">{{ $selectedBooking->vessel->name }}</span></p>
                
                <div class="space-y-6">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Select Assigned Berth</p>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($activeBerths as $berth)
                            <label class="cursor-pointer">
                                <input type="radio" wire:model.live="selectedBerthId" value="{{ $berth->id }}" class="hidden peer">
                                <div class="p-4 rounded-xl border-2 border-slate-100 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 transition-all">
                                    <p class="font-bold text-slate-900 text-sm">{{ $berth->name }}</p>
                                    <p class="text-[10px] text-slate-400 uppercase font-bold">{{ $berth->berth_type }}</p>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @error('selectedBerthId') <p class="text-xs text-red-500 font-bold mt-2">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex gap-4 pt-4">
                        <button type="button" wire:click="approveBooking" class="flex-1 bg-slate-900 text-white py-4 rounded-xl font-bold uppercase tracking-widest text-xs hover:bg-emerald-600 transition-all shadow-xl">Approve Arrival</button>
                        <button type="button" wire:click="rejectBooking" class="px-6 border-2 border-slate-100 rounded-xl text-xs font-bold text-slate-400 hover:text-red-500 hover:border-red-100 transition-all">Reject</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- 3. Operational Notice History (Admin) -->
    @if($showAlertsModal)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="toggleAlertsModal"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden animate-in fade-in zoom-in duration-200 p-10">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-2xl font-black text-slate-900">Notice Archive</h3>
                <button type="button" wire:click="toggleAlertsModal" class="text-slate-400 hover:text-slate-600 font-bold uppercase text-[10px] tracking-widest transition-colors">Close</button>
            </div>
            <div class="space-y-4 max-h-[50vh] overflow-y-auto pr-4 custom-scrollbar">
                @foreach($activeAlerts as $alert)
                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded {{ $alert['type'] === 'critical' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700' }}">{{ $alert['type'] }}</span>
                        <span class="text-[10px] font-bold text-slate-400">{{ $alert['time'] }}</span>
                    </div>
                    <h4 class="font-bold text-slate-900">{{ $alert['title'] }}</h4>
                    <p class="text-sm text-slate-500 mt-1">{{ $alert['message'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

</div>
