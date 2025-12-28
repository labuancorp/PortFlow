<div class="p-6">
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">CCU & Container Tracking</h1>
            <p class="text-slate-500">Yard Inventory & Demurrage Management</p>
        </div>
        <div class="flex gap-3">
            <input type="text" wire:model.live="search" placeholder="Search container..." class="px-4 py-2 rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
            <button class="px-4 py-2 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-colors">
                + Gate In
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-400 font-bold uppercase">Total Units</p>
                    <p class="text-3xl font-black text-slate-800 mt-1">{{ $stats['total'] }}</p>
                </div>
                <div class="p-3 bg-indigo-50 rounded-full">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-400 font-bold uppercase">Demurrage</p>
                    <p class="text-3xl font-black text-red-600 mt-1">{{ $stats['demurrage'] }}</p>
                </div>
                <div class="p-3 bg-red-50 rounded-full">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-400 font-bold uppercase">Expired Certs</p>
                    <p class="text-3xl font-black text-amber-600 mt-1">{{ $stats['expired'] }}</p>
                </div>
                <div class="p-3 bg-amber-50 rounded-full">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-400 font-bold uppercase">Yard Util.</p>
                    <p class="text-3xl font-black text-emerald-600 mt-1">{{ $stats['yard_util'] }}%</p>
                </div>
                <div class="p-3 bg-emerald-50 rounded-full">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Container List -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
            <h3 class="font-bold text-slate-800">Active Containers</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-xs border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3">Container #</th>
                        <th class="px-6 py-3">Type</th>
                        <th class="px-6 py-3">Owner</th>
                        <th class="px-6 py-3">Location</th>
                        <th class="px-6 py-3">Days in Yard</th>
                        <th class="px-6 py-3">Sling Cert</th>
                        <th class="px-6 py-3">Demurrage</th>
                        <th class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($containers as $c)
                    <tr class="hover:bg-slate-50 {{ $c->calc_demurrage['status'] === 'demurrage' ? 'bg-red-50/50' : '' }}">
                        <td class="px-6 py-4">
                            <div class="font-mono font-bold text-slate-800">{{ $c->container_number }}</div>
                            <div class="text-xs text-slate-400">{{ $c->size }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold {{ $c->type === 'Reefer' ? 'bg-blue-100 text-blue-700' : ($c->type === 'Basket' || $c->type === 'Skip' ? 'bg-purple-100 text-purple-700' : 'bg-slate-100 text-slate-700') }}">
                                {{ $c->type }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $c->owner ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="font-mono text-xs bg-slate-100 px-2 py-1 rounded">{{ $c->location_yard_zone ?? 'N/A' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold {{ $c->calc_demurrage['status'] === 'demurrage' ? 'text-red-600' : 'text-slate-700' }}">
                                {{ $c->calc_demurrage['days'] ?? 0 }} days
                            </div>
                            @if($c->calc_demurrage['status'] === 'free_period')
                            <div class="text-xs text-emerald-600">{{ $c->calc_demurrage['remaining'] }} free days left</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($c->calc_cert['status'] === 'na')
                                <span class="text-xs text-slate-400">N/A</span>
                            @elseif(!$c->calc_cert['valid'])
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-red-100 text-red-700">
                                    ⚠ Expired
                                </span>
                            @elseif($c->calc_cert['status'] === 'expiring_soon')
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-amber-100 text-amber-700">
                                    ⏰ Soon
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-emerald-100 text-emerald-700">
                                    ✓ Valid
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($c->calc_demurrage['status'] === 'demurrage')
                                <div class="font-bold text-red-600">${{ number_format($c->calc_demurrage['cost'], 2) }}</div>
                                <div class="text-xs text-red-500">{{ $c->calc_demurrage['over_days'] }} days over</div>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold {{ $c->status === 'in_yard' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700' }}">
                                {{ ucfirst(str_replace('_', ' ', $c->status)) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-slate-400 italic">No containers found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
