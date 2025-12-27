<div class="p-8 bg-slate-50 min-h-screen font-sans relative">
    <!-- Header -->
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Command Center</h1>
            <p class="text-slate-500 mt-1">Real-time overview of port operations for {{ $now->format('l, d M Y') }}</p>
        </div>
        <div class="flex space-x-3">
            <button type="button" wire:click="exportReport" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-lg hover:bg-slate-50 font-medium shadow-sm flex items-center gap-2 transition-colors">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Export Snapshot
            </button>
            <button type="button" wire:click="logIncident" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium shadow-sm flex items-center gap-2 transition-colors">
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
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 lg:translate-x-0"
             x-transition:leave-end="opacity-0 lg:translate-x-full"
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
    <!-- Admin Dashboard with Pastel Colors -->
    <div class="bg-gradient-to-br from-slate-50 to-indigo-50/20 rounded-3xl p-8 mb-8">
        <!-- KPI Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Live Pending Billing -->
            <div class="bg-gradient-to-br from-emerald-50 to-teal-50 p-6 rounded-2xl border border-emerald-200 shadow-sm hover:shadow-md transition-all cursor-pointer">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-xs font-bold text-emerald-600/70 uppercase tracking-widest">Pending Billing</p>
                        <h3 class="text-3xl font-black text-emerald-900 mt-2">RM {{ number_format($pendingBilling['total_pending'], 0) }}</h3>
                    </div>
                    <div class="p-2 bg-emerald-200/50 rounded-lg">
                        <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="flex items-center gap-2 text-xs text-emerald-700">
                    <span class="flex h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    {{ $pendingBilling['count'] }} active vessels
                </div>
            </div>

            <!-- Unpaid Invoices (Clickable) -->
            <button wire:click="openUnpaidModal" class="bg-gradient-to-br from-rose-50 to-pink-50 p-6 rounded-2xl border border-rose-200 shadow-sm hover:shadow-lg transition-all text-left group">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-xs font-bold text-rose-600/70 uppercase tracking-widest">Unpaid Invoices</p>
                        <h3 class="text-3xl font-black text-rose-900 mt-2">RM {{ number_format($unpaidInvoices['total'], 0) }}</h3>
                    </div>
                    <div class="p-2 bg-rose-200/50 rounded-lg group-hover:bg-rose-300 transition-colors">
                        <svg class="w-5 h-5 text-rose-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="flex items-center justify-between text-xs text-rose-700">
                    <span>{{ $unpaidInvoices['count'] }} outstanding invoices</span>
                    <span class="font-bold group-hover:translate-x-1 transition-transform">View →</span>
                </div>
            </button>

            <!-- Berth Occupancy -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-6 rounded-2xl border border-blue-200 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-xs font-bold text-blue-600/70 uppercase tracking-widest">Berth Occupancy</p>
                        <h3 class="text-3xl font-black text-blue-900 mt-2">{{ $stats['occupancy_rate'] }}%</h3>
                    </div>
                    <div class="p-2 bg-blue-200/50 rounded-lg">
                        <svg class="w-5 h-5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                </div>
                <div class="text-xs text-blue-700">
                    {{ $stats['alongside'] }} vessels alongside
                </div>
            </div>

            <!-- Service Requests -->
            <div class="bg-gradient-to-br from-amber-50 to-orange-50 p-6 rounded-2xl border border-amber-200 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-xs font-bold text-amber-600/70 uppercase tracking-widest">Active Requests</p>
                        <h3 class="text-3xl font-black text-amber-900 mt-2">{{ $activeServiceRequests }}</h3>
                    </div>
                    <div class="p-2 bg-amber-200/50 rounded-lg">
                        <svg class="w-5 h-5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                </div>
                <div class="text-xs text-amber-700">
                    Pending & in progress
                </div>
            </div>
        </div>

        <!-- Quick Actions Section -->
        <div class="mb-8">
            <h3 class="text-lg font-bold text-slate-900 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <a href="{{ route('home') }}" class="group bg-gradient-to-br from-indigo-100 to-purple-100 p-4 rounded-xl border border-indigo-200 hover:shadow-md transition-all">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-indigo-200/50 rounded-lg group-hover:bg-indigo-300 transition-colors">
                            <svg class="w-5 h-5 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                        <div>
                            <p class="font-bold text-indigo-900 text-sm">Berth Planner</p>
                            <p class="text-xs text-indigo-600">Manage schedule</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('warehouse.map') }}" class="group bg-gradient-to-br from-purple-100 to-pink-100 p-4 rounded-xl border border-purple-200 hover:shadow-md transition-all">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-purple-200/50 rounded-lg group-hover:bg-purple-300 transition-colors">
                            <svg class="w-5 h-5 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                        <div>
                            <p class="font-bold text-purple-900 text-sm">Manage Yard</p>
                            <p class="text-xs text-purple-600">Warehouse map</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('gate.scanner') }}" class="group bg-gradient-to-br from-emerald-100 to-teal-100 p-4 rounded-xl border border-emerald-200 hover:shadow-md transition-all">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-emerald-200/50 rounded-lg group-hover:bg-emerald-300 transition-colors">
                            <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        </div>
                        <div>
                            <p class="font-bold text-emerald-900 text-sm">Gate Scanner</p>
                            <p class="text-xs text-emerald-600">Security control</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.audit') }}" class="group bg-gradient-to-br from-cyan-100 to-blue-100 p-4 rounded-xl border border-cyan-200 hover:shadow-md transition-all">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-cyan-200/50 rounded-lg group-hover:bg-cyan-300 transition-colors">
                            <svg class="w-5 h-5 text-cyan-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                        <div>
                            <p class="font-bold text-cyan-900 text-sm">Analytics</p>
                            <p class="text-xs text-cyan-600">Audit trail</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Secondary Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Warehouse Revenue -->
            <div class="bg-white/60 backdrop-blur-sm p-6 rounded-xl border border-purple-200 shadow-sm">
                <div class="flex items-center gap-3 mb-3">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-purple-600 uppercase">Warehouse Revenue</p>
                        <p class="text-xl font-black text-purple-900">RM {{ number_format($warehouseSummary['total_charges'] ?? 0, 2) }}</p>
                    </div>
                </div>
                <p class="text-xs text-purple-600">{{ $warehouseSummary['total_items'] ?? 0 }} items in storage</p>
            </div>

            <!-- Expected Arrivals -->
            <div class="bg-white/60 backdrop-blur-sm p-6 rounded-xl border border-cyan-200 shadow-sm">
                <div class="flex items-center gap-3 mb-3">
                    <div class="p-2 bg-cyan-100 rounded-lg">
                        <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-cyan-600 uppercase">Expected (24h)</p>
                        <p class="text-xl font-black text-cyan-900">{{ $stats['expected_arrivals'] }}</p>
                    </div>
                </div>
                <p class="text-xs text-cyan-600">Vessels arriving soon</p>
            </div>

            <!-- Completed This Month -->
            <div class="bg-white/60 backdrop-blur-sm p-6 rounded-xl border border-green-200 shadow-sm">
                <div class="flex items-center gap-3 mb-3">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-green-600 uppercase">Completed (Month)</p>
                        <p class="text-xl font-black text-green-900">{{ $stats['completed_month'] }}</p>
                    </div>
                </div>
                <p class="text-xs text-green-600">Port calls completed</p>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Activity -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-8 shadow-sm border border-slate-200">
            <h3 class="text-xl font-bold text-slate-900 mb-6">Recent Activity</h3>
            <div class="space-y-3">
                @forelse($recentActivity as $activity)
                <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center flex-shrink-0 shadow-md">
                        <span class="font-bold text-white text-sm">{{ substr($activity->vessel->name, 0, 1) }}</span>
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-slate-900 text-sm">{{ $activity->vessel->name }}</p>
                        <p class="text-xs text-slate-500">{{ $activity->agent->name }} • {{ ucfirst($activity->status) }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-bold text-indigo-600">{{ $activity->berth->name ?? 'Unassigned' }}</p>
                        <p class="text-xs text-slate-400">{{ $activity->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <p class="text-slate-400 italic text-center py-8">No recent activity</p>
                @endforelse
            </div>
        </div>

        <!-- Top Agents -->
        <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-200">
            <h3 class="text-xl font-bold text-slate-900 mb-6">Top Agents (This Month)</h3>
            <div class="space-y-4">
                @forelse($topAgents as $index => $agent)
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center flex-shrink-0 font-bold text-white text-sm">
                        {{ $index + 1 }}
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-slate-900 text-sm">{{ $agent->organization->name }}</p>
                        <p class="text-xs text-emerald-600 font-bold">RM {{ number_format($agent->revenue, 2) }}</p>
                    </div>
                </div>
                @empty
                <p class="text-slate-400 italic text-center py-4">No data available</p>
                @endforelse
            </div>
            </div>
        </div>
    </div>

    <!-- Unpaid Invoices Modal -->
    @if($showUnpaidModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" wire:click="closeModal">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-3xl max-h-[80vh] overflow-hidden" @click.stop>
            <div class="p-8 border-b border-slate-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-black text-slate-900">Unpaid Invoices</h2>
                        <p class="text-sm text-slate-500 mt-1">{{ count($unpaidInvoicesList) }} outstanding invoices</p>
                    </div>
                    <button wire:click="closeModal" class="p-2 hover:bg-slate-100 rounded-lg transition-colors">
                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
            <div class="p-8 overflow-y-auto max-h-[60vh]">
                <div class="space-y-3">
                    @forelse($unpaidInvoicesList as $invoice)
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors">
                        <div class="flex-1">
                            <p class="font-bold text-slate-900">{{ $invoice->organization->name }}</p>
                            <p class="text-xs text-slate-500 mt-1">Invoice #{{ $invoice->invoice_number }} • {{ $invoice->created_at->format('d M Y') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-black text-rose-600">RM {{ number_format($invoice->total_amount, 2) }}</p>
                            <span class="inline-block px-2 py-1 text-xs font-bold rounded-full {{ $invoice->status === 'overdue' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <p class="text-slate-400 italic text-center py-8">No unpaid invoices</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    @endif
@elseif($mode === 'agent')
    <!-- KPI Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- ... existing admin content ... -->
        <!-- Card: Vessels Alongside -->
        <a href="{{ route('home') }}" class="group bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden relative">
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em]">Alongside</p>
                    <h3 class="text-4xl font-extrabold text-slate-900 mt-2">{{ $alongsideCount }}</h3>
                </div>
                <div class="p-3 bg-blue-50 text-blue-600 rounded-xl group-hover:bg-blue-600 group-hover:text-white transition-all duration-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs font-bold text-green-500 relative z-10">
                <span class="flex h-2 w-2 rounded-full bg-green-500 mr-2 animate-pulse"></span>
                LIVE BILLING ACTIVE
            </div>
            <div class="absolute -right-4 -bottom-4 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity">
                <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
        </a>

        <!-- Card: Arrivals -->
        <a href="{{ route('home') }}" class="group bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden relative">
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em]">Expected (24h)</p>
                    <h3 class="text-4xl font-extrabold text-slate-900 mt-2">{{ $expectedArrivals }}</h3>
                </div>
                <div class="p-3 bg-amber-50 text-amber-600 rounded-xl group-hover:bg-amber-600 group-hover:text-white transition-all duration-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
             <div class="mt-4 flex items-center text-xs font-bold text-slate-400 relative z-10">
                VIEW TIMELINE →
            </div>
            <div class="absolute -right-4 -bottom-4 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity">
                <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </a>

        <!-- Card: Occupancy -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm overflow-hidden relative">
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em]">Berth Utilization</p>
                    <h3 class="text-4xl font-extrabold text-slate-900 mt-2">{{ $occupancyRate }}%</h3>
                </div>
                <div class="p-3 bg-teal-50 text-teal-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
            </div>
             <div class="mt-4 flex items-center text-xs font-bold text-slate-400 relative z-10 uppercase">
                {{ 100 - $occupancyRate }}% CAPACITY AVAILABLE
            </div>
            <!-- Simple Progress Background -->
            <div class="absolute left-0 bottom-0 h-1.5 bg-slate-100 w-full">
                <div class="h-full bg-teal-500 transition-all duration-1000" style="width: {{ $occupancyRate }}%"></div>
            </div>
        </div>

        <!-- Card: Completed -->
        <a href="{{ route('billing.index') }}" class="group bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden relative">
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em]">Turnaround (MTD)</p>
                    <h3 class="text-4xl font-extrabold text-slate-900 mt-2">{{ $completedThisMonth }}</h3>
                </div>
                <div class="p-3 bg-purple-50 text-purple-600 rounded-xl group-hover:bg-purple-600 group-hover:text-white transition-all duration-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs font-bold text-purple-500 relative z-10">
                PROCEED TO BILLING →
            </div>
             <div class="absolute -right-4 -bottom-4 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity">
                <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </a>
    </div>

    <!-- Admin Content Blocks (Pending Requests, Alerts, IoT) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Column: Actions -->
        <div class="lg:col-span-2 space-y-8">
             <!-- Pending Bookings Section -->
             <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 flex justify-between items-center bg-slate-50/30">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Pending Approvals</h3>
                        <p class="text-xs text-slate-500 mt-1 uppercase tracking-wider font-semibold">Action required for incoming vessels</p>
                    </div>
                    <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-bold ring-4 ring-amber-50">{{ count($pendingRequests) }} REQUESTS</span>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($pendingRequests as $request)
                    <div wire:key="request-{{ $request->id }}" class="p-8 hover:bg-slate-50/80 transition-all flex items-center justify-between group">
                        <div class="flex items-center gap-6">
                            <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-white group-hover:shadow-lg group-hover:text-blue-600 transition-all duration-500">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-slate-800 tracking-tight">{{ $request->vessel->name }}</h4>
                                <div class="flex items-center gap-3 mt-1.5">
                                    <span class="text-[10px] font-extrabold uppercase bg-blue-100 text-blue-700 px-2 py-0.5 rounded-md tracking-wider">{{ $request->vessel->vessel_type }}</span>
                                    <span class="text-xs text-slate-400 font-medium">Agent: <span class="text-slate-600">{{ $request->agent->code }}</span></span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-8">
                            <div class="text-right">
                                <p class="text-[10px] text-slate-400 font-extrabold uppercase tracking-widest mb-1.5">Requested ETA</p>
                                <p class="text-sm font-bold text-slate-700 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ $request->eta->format('d M — H:i') }}
                                </p>
                            </div>
                            <button type="button" wire:click="openReviewModal({{ $request->id }})" class="px-6 py-3 bg-slate-900 text-white text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-blue-600 transition-all duration-300 shadow-lg shadow-slate-200">
                                Review
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="p-16 text-center">
                        <div class="w-16 h-16 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4 grayscale opacity-20">
                            <svg class="w-8 h-8 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        </div>
                        <h3 class="text-slate-900 font-bold">Zero Pending Requests</h3>
                        <p class="text-slate-500 text-sm mt-1">All booking submissions have been processed.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Alerts Grid -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden mb-8">
                <div class="px-8 py-6 border-b border-slate-50 flex justify-between items-center bg-slate-50/30">
                    <h3 class="text-lg font-bold text-slate-900">Operational Notices</h3>
                    <button type="button" wire:click="toggleAlertsModal" class="text-xs font-bold text-blue-600 hover:text-blue-800 uppercase tracking-widest">Archive History →</button>
                </div>
                <div class="p-2 grid grid-cols-1 md:grid-cols-2 gap-2">
                    @forelse(array_slice($activeAlerts, 0, 4) as $alert)
                    <div wire:key="alert-{{ $alert['id'] }}" class="p-5 rounded-2xl border border-slate-100 hover:border-slate-300 transition-all group relative">
                        <button type="button" wire:click="dismissAlert({{ $alert['id'] }})" class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity p-1 hover:bg-slate-100 rounded">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                        <div class="flex gap-4">
                            <div class="w-2 h-10 {{ $alert['type'] === 'critical' ? 'bg-red-500' : ($alert['type'] === 'warning' ? 'bg-amber-500' : 'bg-blue-500') }} rounded-full flex-shrink-0 mt-1"></div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800">{{ $alert['title'] }}</h4>
                                <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ Str::limit($alert['message'], 80) }}</p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase mt-3 tracking-wider">{{ $alert['time'] }}</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-2 py-8 text-center text-slate-400 italic text-sm">No new notices.</div>
                    @endforelse
                </div>
            </div>

            <!-- IoT Environmental Monitoring -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 flex justify-between items-center bg-slate-50/30">
                     <div>
                        <h3 class="text-lg font-bold text-slate-900">Environmental Conditions</h3>
                        <p class="text-xs text-slate-500 mt-1 uppercase tracking-wider font-semibold">Live IoT Sensor Data</p>
                    </div>
                    <div class="flex items-center gap-2">
                         <span class="flex h-2 w-2 rounded-full bg-green-500 animate-pulse"></span>
                         <span class="text-[10px] font-bold text-green-600 uppercase tracking-widest">LIVE FEED</span>
                    </div>
                </div>
                <div class="p-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($iotReadings as $reading)
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 relative overflow-hidden group">
                        <div class="relative z-10">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">{{ $reading['name'] }}</p>
                            <div class="flex items-baseline gap-1">
                                <span class="text-2xl font-black {{ $reading['risk'] === 'critical' ? 'text-red-600' : ($reading['risk'] === 'warning' ? 'text-amber-500' : 'text-slate-800') }}">
                                    {{ $reading['value'] }}
                                </span>
                                <span class="text-xs font-bold text-slate-500">{{ $reading['unit'] }}</span>
                            </div>
                             @if($reading['risk'] !== 'normal')
                            <div class="mt-2 text-[10px] font-bold uppercase px-2 py-1 rounded bg-white inline-block shadow-sm {{ $reading['risk'] === 'critical' ? 'text-red-600 border border-red-100' : 'text-amber-600 border border-amber-100' }}">
                                {{ $reading['risk'] }}
                            </div>
                            @endif
                        </div>
                        <!-- Icon Background -->
                        <div class="absolute -right-2 -bottom-2 text-slate-200 opacity-20 group-hover:opacity-40 transition-opacity">
                            @if($reading['type'] == 'wind')
                                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24"><path d="M14.5 12.5L14.5 12.5C14.5 11.67 13.83 11 13 11C12.17 11 11.5 11.67 11.5 12.5C11.5 13.33 12.17 14 13 14H18V16H13C11.07 16 9.5 14.43 9.5 12.5C9.5 10.57 11.07 9 13 9H17V7H13C9.97 7 7.5 9.47 7.5 12.5C7.5 15.53 9.97 18 13 18H20V12.5H14.5Z"></path></svg>
                            @elseif($reading['type'] == 'tide')
                                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24"><path d="M21.5 9.87L20.1 8.45C19.8 8.16 19.33 8.16 19.04 8.45L16.5 11L14 8.45C13.7 8.16 13.23 8.16 12.94 8.45L10.5 11L8 8.45C7.71 8.16 7.23 8.16 6.94 8.45L4.41 11L3 9.58V14C3 15.1 3.9 16 5 16H19C20.1 16 21 15.1 21 14V9.87Z"></path></svg>
                            @elseif($reading['type'] == 'swell')
                                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24"><path d="M2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2C6.48 2 2 6.48 2 12ZM12 4C14.21 4 16.21 4.9 17.66 6.34L12 12L6.34 6.34C7.79 4.9 9.79 4 12 4Z"></path></svg>
                            @else
                                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.73 7.61 1 12C2.73 16.39 7 19.5 12 19.5C17 19.5 21.27 16.39 23 12C21.27 7.61 17 4.5 12 4.5ZM12 17C9.24 17 7 14.76 7 12C7 9.24 9.24 7 12 7C14.76 7 17 9.24 17 12C17 14.76 14.76 17 12 17ZM12 9C10.34 9 9 10.34 9 12C9 13.66 10.34 15 12 15C13.66 15 15 13.66 15 12C15 10.34 13.66 9 12 9Z"></path></svg>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Sidebar: Feed & Tools -->
        <div class="space-y-6">
            <!-- Activity Feed -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8">
                <h3 class="text-lg font-bold text-slate-900 mb-8 flex items-center justify-between">
                    System Feed
                    <span class="flex h-2 w-2 rounded-full bg-blue-500"></span>
                </h3>
                <div class="relative border-l-2 border-slate-100 ml-4 space-y-10">
                    @foreach($recentActivity as $activity)
                    <div class="relative pl-8">
                        <div class="absolute -left-[9px] top-1.5 w-4 h-4 rounded-full border-4 border-white shadow-sm
                            {{ $activity->status === 'alongside' ? 'bg-green-500' : 
                               ($activity->status === 'completed' ? 'bg-slate-300' : 
                               ($activity->status === 'requested' ? 'bg-amber-400' : 'bg-blue-500')) 
                            }}"></div>
                        <p class="text-[10px] text-slate-400 font-extrabold uppercase tracking-widest mb-1.5">
                            {{ $activity->updated_at->diffForHumans(null, true, true) }}
                        </p>
                        <p class="text-sm text-slate-800 leading-snug">
                            <span class="font-bold text-slate-900 tracking-tight">{{ $activity->vessel->name }}</span> 
                            <span class="text-slate-500">
                                @if($activity->status === 'alongside') moored at {{ $activity->berth?->name ?? 'Facility' }}
                                @elseif($activity->status === 'requested') submitted NOA
                                @elseif($activity->status === 'completed') dropped lines
                                @else status: {{ $activity->status }}
                                @endif
                            </span>
                        </p>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Pilotage Simulator -->
            <div class="bg-indigo-900 rounded-3xl shadow-2xl p-8 text-white relative overflow-hidden group">
                <div class="absolute -top-12 -right-12 w-48 h-48 bg-indigo-800/10 rounded-full blur-3xl"></div>
                
                <div class="flex items-center justify-between mb-8 relative z-10">
                    <div>
                        <h3 class="text-xl font-black italic tracking-tighter">PILOT-OS</h3>
                        <p class="text-[10px] text-indigo-300 font-bold uppercase tracking-widest mt-0.5">Maritime Guidance</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="flex h-2 w-2 rounded-full {{ $pilotRequested ? 'bg-green-400 animate-pulse' : 'bg-slate-400' }}"></span>
                        <span class="text-[10px] font-bold uppercase">{{ $pilotStatus }}</span>
                    </div>
                </div>

                <div class="space-y-6 relative z-10">
                    @if($pilotRequested)
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-4">
                        <div class="flex justify-between text-xs font-bold mb-3">
                            <span class="text-indigo-200 uppercase tracking-widest">Progress to Berth</span>
                            <span>{{ $pilotProgress }}%</span>
                        </div>
                        <div class="h-2 bg-indigo-950/50 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-indigo-400 to-teal-400 transition-all duration-1000 shadow-[0_0_10px_rgba(79,70,229,0.5)]" 
                                 style="width: {{ $pilotProgress }}%"></div>
                        </div>
                    </div>
                    @endif

                    <div class="grid grid-cols-2 gap-3">
                        <div class="p-3 bg-white/5 rounded-xl border border-white/10">
                            <span class="block text-[10px] text-indigo-300 font-bold uppercase tracking-wider mb-1">Fleet 1</span>
                            <span class="text-xs font-bold">ALPHA 01</span>
                        </div>
                        <div class="p-3 bg-white/5 rounded-xl border border-white/10">
                            <span class="block text-[10px] text-indigo-300 font-bold uppercase tracking-wider mb-1">Fleet 2</span>
                            <span class="text-xs font-bold">READY</span>
                        </div>
                    </div>

                    @if(!$pilotRequested)
                    <button type="button" wire:click="requestPilot" class="w-full py-4 bg-teal-500 hover:bg-teal-400 text-white rounded-2xl font-bold text-xs uppercase tracking-widest transition-all shadow-xl shadow-teal-900/40">
                        Dispatch Pilotage
                    </button>
                    @else
                    <button type="button" wire:click="advancePilotSimulation" class="w-full py-4 bg-white/10 hover:bg-white/20 text-white rounded-2xl font-bold text-xs uppercase tracking-widest border border-white/10 transition-all">
                        {{ $pilotProgress < 100 ? 'Advance Sequence' : 'Reset Simulator' }}
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
@elseif($mode === 'agent')
    <!-- Premium Agent Dashboard -->
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50/30">
        
        <!-- Hero Section with Live Billing -->
        <div class="relative overflow-hidden bg-gradient-to-br from-blue-100 via-indigo-100 to-purple-100 rounded-3xl mb-8 shadow-lg border border-blue-200/50">
            <!-- Subtle Background Pattern -->
            <div class="absolute inset-0 opacity-30">
                <div class="absolute w-96 h-96 bg-white rounded-full -top-48 -left-48"></div>
                <div class="absolute w-96 h-96 bg-white rounded-full -bottom-48 -right-48"></div>
            </div>
            
            <div class="relative z-10 p-12">
                <div class="flex items-start justify-between mb-8">
                    <div>
                        <p class="text-indigo-600/70 text-sm font-medium mb-2">Welcome back,</p>
                        <h1 class="text-4xl font-black text-indigo-900 tracking-tight">{{ auth()->user()->organization->name }}</h1>
                    </div>
                    <div class="flex items-center gap-2 bg-white/60 backdrop-blur-sm px-4 py-2 rounded-full border border-indigo-200">
                        <span class="flex h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span class="text-indigo-700 text-sm font-bold">Live</span>
                    </div>
                </div>

                <!-- Live Billing Display -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Total Charges -->
                    <div class="md:col-span-2 bg-white/60 backdrop-blur-sm rounded-2xl p-8 border border-indigo-200/50 shadow-sm">
                        <p class="text-indigo-600/70 text-xs uppercase tracking-widest font-bold mb-3">Total Outstanding</p>
                        <h2 class="text-6xl font-black text-indigo-900 mb-4">RM {{ number_format($liveBilling['total_charges'], 2) }}</h2>
                        
                        <div class="grid grid-cols-2 gap-4 mt-6">
                            <div class="bg-blue-50/80 rounded-xl p-4 border border-blue-200/50">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path></svg>
                                    <span class="text-blue-700 text-xs font-bold">Berthing</span>
                                </div>
                                <p class="text-2xl font-black text-blue-900">RM {{ number_format($liveBilling['berthing_charges'], 2) }}</p>
                                <p class="text-blue-600/60 text-xs mt-1">{{ $liveBilling['berthing_vessels'] }} vessel(s)</p>
                            </div>
                            <div class="bg-amber-50/80 rounded-xl p-4 border border-amber-200/50">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="w-4 h-4 text-amber-600" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a5 5 0 00-5 5v2a2 2 0 00-2 2v5a2 2 0 002 2h10a2 2 0 002-2v-5a2 2 0 00-2-2H7V7a3 3 0 015.905-.75 1 1 0 001.937-.5A5.002 5.002 0 0010 2z"></path></svg>
                                    <span class="text-amber-700 text-xs font-bold">Warehouse</span>
                                </div>
                                <p class="text-2xl font-black text-amber-900">RM {{ number_format($liveBilling['warehouse_charges'], 2) }}</p>
                                <p class="text-amber-600/60 text-xs mt-1">{{ $liveBilling['warehouse_items'] }} item(s)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="space-y-4">
                        <div class="bg-white/60 backdrop-blur-sm rounded-2xl p-6 border border-emerald-200/50 shadow-sm">
                            <p class="text-emerald-600/70 text-xs uppercase tracking-widest font-bold mb-2">Active Vessels</p>
                            <p class="text-4xl font-black text-emerald-900">{{ $stats['active_vessels'] }}</p>
                        </div>
                        <div class="bg-white/60 backdrop-blur-sm rounded-2xl p-6 border border-rose-200/50 shadow-sm">
                            <p class="text-rose-600/70 text-xs uppercase tracking-widest font-bold mb-2">Unpaid Invoices</p>
                            <p class="text-4xl font-black text-rose-900">{{ $stats['unpaid_invoices'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Actions & Activity -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Quick Actions -->
                <div>
                    <h2 class="text-2xl font-black text-slate-900 mb-6">Quick Actions</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <a href="{{ route('home') }}" class="group relative overflow-hidden bg-gradient-to-br from-emerald-100 to-teal-100 rounded-2xl p-8 shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-emerald-200">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-white/40 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                            <div class="relative z-10">
                                <div class="w-12 h-12 bg-emerald-200/50 rounded-xl flex items-center justify-center mb-4">
                                    <svg class="w-6 h-6 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                </div>
                                <h3 class="text-xl font-bold text-emerald-900 mb-2">Plan New Voyage</h3>
                                <p class="text-emerald-700 text-sm">Book berth & submit NOA</p>
                            </div>
                        </a>

                        <a href="{{ route('vessels.index') }}" class="group relative overflow-hidden bg-gradient-to-br from-indigo-100 to-purple-100 rounded-2xl p-8 shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-indigo-200">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-white/40 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                            <div class="relative z-10">
                                <div class="w-12 h-12 bg-indigo-200/50 rounded-xl flex items-center justify-center mb-4">
                                    <svg class="w-6 h-6 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                </div>
                                <h3 class="text-xl font-bold text-indigo-900 mb-2">Manage Fleet</h3>
                                <p class="text-indigo-700 text-sm">View & register vessels</p>
                            </div>
                        </a>

                        <a href="{{ route('cargo.manifests.index') }}" class="group relative overflow-hidden bg-gradient-to-br from-amber-100 to-orange-100 rounded-2xl p-8 shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-amber-200">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-white/40 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                            <div class="relative z-10">
                                <div class="w-12 h-12 bg-amber-200/50 rounded-xl flex items-center justify-center mb-4">
                                    <svg class="w-6 h-6 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                </div>
                                <h3 class="text-xl font-bold text-amber-900 mb-2">Cargo Manifests</h3>
                                <p class="text-amber-700 text-sm">Declare & track cargo</p>
                            </div>
                        </a>

                        <a href="{{ route('billing.index') }}" class="group relative overflow-hidden bg-gradient-to-br from-rose-100 to-pink-100 rounded-2xl p-8 shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-rose-200">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-white/40 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                            <div class="relative z-10">
                                <div class="w-12 h-12 bg-rose-200/50 rounded-xl flex items-center justify-center mb-4">
                                    <svg class="w-6 h-6 text-rose-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                </div>
                                <h3 class="text-xl font-bold text-rose-900 mb-2">Billing & Invoices</h3>
                                <p class="text-rose-700 text-sm">Payments & history</p>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Fleet Activity -->
                <div class="bg-white rounded-2xl p-8 shadow-xl border border-slate-200">
                    <h3 class="text-xl font-bold text-slate-900 mb-6">Fleet Activity</h3>
                    <div class="space-y-4">
                        @forelse($recentActivity as $activity)
                        <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-cyan-400 to-blue-500 flex items-center justify-center flex-shrink-0 shadow-lg">
                                <span class="font-bold text-white text-lg">{{ substr($activity->vessel->name, 0, 1) }}</span>
                            </div>
                            <div class="flex-1">
                                <p class="font-bold text-slate-900">{{ $activity->vessel->name }}</p>
                                <p class="text-sm text-slate-500">{{ ucfirst($activity->status) }} • {{ $activity->updated_at->diffForHumans() }}</p>
                            </div>
                            <div class="px-3 py-1 bg-indigo-100 rounded-full">
                                <span class="text-xs font-bold text-indigo-700">{{ $activity->berth->name ?? 'N/A' }}</span>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            <p class="text-slate-400 italic">No recent activity</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Sidebar -->
        <div class="space-y-6">
            @if($warehouseBilling)
            <!-- Live Warehouse Billing -->
            <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-3xl p-6 text-white shadow-2xl">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-sm uppercase tracking-widest">Warehouse Charges</h3>
                    <span class="flex h-2 w-2 rounded-full bg-white animate-pulse"></span>
                </div>
                <div class="mb-4">
                    <div class="text-3xl font-black">RM {{ number_format($warehouseBilling['total_charges'], 2) }}</div>
                    <div class="text-amber-100 text-xs font-bold mt-1">{{ $warehouseBilling['items_count'] }} items in storage</div>
                </div>
                <div class="pt-4 border-t border-white/20">
                    <div class="text-xs space-y-1 text-amber-100">
                        <div class="flex justify-between">
                            <span>Base Rate:</span>
                            <span class="font-bold">RM {{ $warehouseBilling['rate_info']['base_rate'] }}/m³/day</span>
                        </div>
                        <div class="flex justify-between">
                            <span>DG Surcharge:</span>
                            <span class="font-bold">+{{ $warehouseBilling['rate_info']['dg_surcharge_pct'] }}%</span>
                        </div>
                    </div>
                </div>
                <a href="{{ route('warehouse.map') }}" class="mt-4 block w-full py-2 bg-white/20 hover:bg-white/30 rounded-xl text-center text-xs font-bold uppercase tracking-widest transition-colors">
                    View Yard Map
                </a>
            </div>
            @endif

            <!-- Reuse Pilot Simulator? Or Simplified Info -->
            <div class="bg-slate-900 rounded-3xl p-6 text-white">
                <h3 class="font-bold text-lg mb-2">Support</h3>
                <p class="text-slate-400 text-sm mb-4">Need assistance with a booking or invoice?</p>
                <button class="w-full py-3 bg-white text-slate-900 rounded-xl font-bold text-sm">Contact Port Control</button>
            </div>
        </div>
    </div>
@endif
    <!-- Review Booking Modal -->
    @if($showReviewModal && $selectedBooking)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="$set('showReviewModal', false)"></div>
        
        <div class="relative bg-white w-full max-w-xl rounded-[2rem] shadow-2xl overflow-hidden border border-slate-200">
            <div class="p-10">
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 text-[10px] font-bold uppercase tracking-widest rounded-md">Port Control Unit</span>
                        <h3 class="text-2xl font-black text-slate-900 mt-2 tracking-tight">Vessel Approval Required</h3>
                    </div>
                    <button type="button" wire:click="$set('showReviewModal', false)" class="p-2 hover:bg-slate-100 rounded-full text-slate-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Vessel Info Card -->
                <div class="bg-slate-900 rounded-3xl p-8 text-white mb-8 shadow-xl relative overflow-hidden">
                    <div class="relative z-10 flex justify-between items-end">
                        <div class="space-y-1">
                            <h4 class="text-2xl font-black tracking-tight">{{ $selectedBooking->vessel->name }}</h4>
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">{{ $selectedBooking->vessel->vessel_type }} — {{ $selectedBooking->agent->name }}</p>
                        </div>
                        <div class="text-right">
                            <span class="block text-[8px] text-slate-500 font-bold uppercase tracking-[.3em] mb-2">Registry</span>
                            <span class="text-sm font-bold bg-white/10 px-3 py-1 rounded-lg border border-white/5">{{ $selectedBooking->vessel->imo_number }}</span>
                        </div>
                    </div>
                    <div class="mt-8 pt-8 border-t border-white/10 grid grid-cols-2 gap-8 relative z-10">
                        <div>
                            <span class="block text-[9px] text-slate-500 font-bold uppercase tracking-widest mb-1.5 leading-none">Total Length</span>
                            <span class="text-lg font-black">{{ $selectedBooking->vessel->loa_meters }}m <span class="text-xs text-slate-500 font-bold italic ml-1">LOA</span></span>
                        </div>
                        <div>
                            <span class="block text-[9px] text-slate-500 font-bold uppercase tracking-widest mb-1.5 leading-none">Max Draft</span>
                            <span class="text-lg font-black text-teal-400">{{ $selectedBooking->vessel->draft_meters }}m <span class="text-xs text-slate-500 font-bold italic ml-1">DEPTH</span></span>
                        </div>
                    </div>
                    <!-- Aesthetic decoration -->
                    <div class="absolute right-0 top-0 w-32 h-32 bg-blue-500/10 blur-[60px]"></div>
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[.2em] mb-3 leading-none">Select Operations Berth</label>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($berths as $berth)
                            <label class="relative cursor-pointer">
                                <input type="radio" wire:model="selectedBerthId" value="{{ $berth->id }}" class="peer sr-only">
                                <div class="px-4 py-4 border-2 border-slate-100 rounded-2xl peer-checked:border-blue-600 peer-checked:bg-blue-50 transition-all hover:bg-slate-50">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-bold text-slate-800">{{ $berth->name }}</span>
                                        <div class="h-3 w-3 rounded-full border-2 border-slate-200 peer-checked:border-blue-600"></div>
                                    </div>
                                    <span class="text-[9px] font-bold text-slate-400 uppercase block mt-1">Ready for ops</span>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @error('selectedBerthId') <span class="block mt-2 text-[10px] font-bold text-red-500 uppercase tracking-widest">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex gap-4 pt-4">
                        <button type="button" wire:click="approveBooking" class="flex-1 bg-slate-900 text-white py-5 rounded-3xl font-black uppercase tracking-widest text-xs hover:bg-blue-600 transition-all shadow-xl shadow-slate-200">
                            Confirm Approval
                        </button>
                        <button type="button" wire:click="rejectBooking" class="px-8 border-2 border-slate-100 rounded-3xl text-sm font-bold text-slate-400 hover:text-red-600 hover:border-red-100 transition-all">
                            Reject
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Archive / Alerts Modal -->
    @if($showAlertsModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="relative bg-white w-full max-w-2xl rounded-3xl shadow-2xl overflow-hidden p-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-slate-900">Alert History Archive</h3>
                <button type="button" wire:click="toggleAlertsModal" class="text-slate-400 hover:text-slate-600 uppercase text-xs font-bold tracking-widest">Close</button>
            </div>
            <div class="space-y-4 max-h-[60vh] overflow-y-auto pr-2 custom-scrollbar">
                @foreach($activeAlerts as $alert)
                    <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-black uppercase tracking-widest {{ $alert['type'] === 'critical' ? 'text-red-500' : 'text-slate-400' }}">{{ $alert['type'] }}</span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase">{{ $alert['time'] }}</span>
                        </div>
                        <h4 class="font-bold text-slate-900 tracking-tight">{{ $alert['title'] }}</h4>
                        <p class="text-sm text-slate-500 mt-1 leading-relaxed">{{ $alert['message'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
