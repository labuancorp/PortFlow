<div class="p-6 bg-gradient-to-br from-red-50 via-orange-50 to-amber-50 min-h-screen">
    <!-- Critical Alert Header -->
    <div class="mb-8 relative overflow-hidden rounded-3xl bg-gradient-to-r from-red-600 via-orange-600 to-amber-600 p-8 shadow-2xl border-2 border-red-200">
        <div class="absolute inset-0">
            <div class="absolute w-96 h-96 bg-white rounded-full blur-3xl -top-48 -right-48 animate-pulse opacity-20"></div>
            <div class="absolute w-96 h-96 bg-white rounded-full blur-3xl -bottom-48 -left-48 animate-pulse delay-1000 opacity-20"></div>
        </div>
        <div class="relative z-10 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="p-4 bg-white/20 backdrop-blur-lg rounded-2xl border border-white/30 animate-pulse shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div>
                    <h1 class="text-4xl font-black text-white tracking-tight mb-2">Dangerous Goods Command</h1>
                    <p class="text-red-100 text-lg">IMDG Compliance & Explosive Bunker Protocol</p>
                </div>
            </div>
            <button class="px-6 py-3 bg-white text-red-600 font-bold rounded-xl hover:shadow-2xl transition-all transform hover:scale-105">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                New Declaration
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Explosive Bunker Status with 3D Visualization -->
        <div class="bg-white rounded-2xl shadow-2xl p-8 border-l-4 border-red-500">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-3 bg-gradient-to-br from-red-500 to-rose-600 rounded-xl shadow-lg animate-pulse">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h2 class="font-black text-xl text-slate-800">Explosive Bunker</h2>
            </div>
            
            <!-- 3D Radial Progress -->
            <div class="flex items-center justify-center py-8 relative">
                <div class="relative w-48 h-48">
                    <!-- Outer Glow Ring -->
                    <div class="absolute inset-0 rounded-full bg-gradient-to-r from-red-500 to-rose-600 opacity-10 blur-2xl animate-pulse"></div>
                    
                    <!-- SVG Radial Progress -->
                    <svg class="w-full h-full transform -rotate-90 relative z-10">
                        <defs>
                            <linearGradient id="progressGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color:{{ $bunkerStatus['utilization_percent'] > 90 ? '#ef4444' : '#f59e0b' }};stop-opacity:1" />
                                <stop offset="100%" style="stop-color:{{ $bunkerStatus['utilization_percent'] > 90 ? '#dc2626' : '#d97706' }};stop-opacity:1" />
                            </linearGradient>
                        </defs>
                        <circle cx="96" cy="96" r="88" stroke="currentColor" stroke-width="16" fill="transparent" class="text-slate-100" />
                        <circle cx="96" cy="96" r="88" stroke="url(#progressGradient)" stroke-width="16" fill="transparent" 
                                stroke-dasharray="553" 
                                stroke-dashoffset="{{ 553 - (553 * $bunkerStatus['utilization_percent'] / 100) }}"
                                class="transition-all duration-1000 ease-out drop-shadow-2xl"
                                stroke-linecap="round" />
                    </svg>
                    
                    <!-- Center Content -->
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-5xl font-black {{ $bunkerStatus['utilization_percent'] > 90 ? 'text-red-600 animate-pulse' : 'text-amber-600' }}">
                            {{ number_format($bunkerStatus['utilization_percent'], 1) }}%
                        </span>
                        <span class="text-xs text-slate-500 font-bold uppercase tracking-wider mt-2">NEQ Capacity</span>
                    </div>
                </div>
            </div>
            
            <!-- Stats Grid -->
            <div class="space-y-3 mt-6">
                <div class="flex justify-between items-center p-4 bg-slate-50 rounded-xl border border-slate-200">
                    <span class="text-slate-600 font-medium">Total NEQ</span>
                    <span class="font-black text-slate-800 text-lg">{{ $bunkerStatus['total_neq'] }} kg</span>
                </div>
                <div class="flex justify-between items-center p-4 bg-slate-50 rounded-xl border border-slate-200">
                    <span class="text-slate-600 font-medium">Limit</span>
                    <span class="font-black text-slate-800 text-lg">{{ $bunkerStatus['limit_neq'] }} kg</span>
                </div>
                <div class="flex justify-between items-center p-4 bg-slate-50 rounded-xl border border-slate-200">
                    <span class="text-slate-600 font-medium">Stored Items</span>
                    <span class="font-black text-slate-800 text-lg">{{ $bunkerStatus['items_count'] }}</span>
                </div>
            </div>
            
            <button class="w-full mt-6 px-6 py-3 text-sm font-bold text-white bg-gradient-to-r from-red-600 to-rose-700 rounded-xl hover:shadow-2xl hover:shadow-red-500/50 transition-all transform hover:scale-105">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                View Bunker Inventory
            </button>
        </div>

        <!-- Interactive Segregation Checker -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-2xl p-8 border border-slate-200">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-3 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h2 class="font-black text-xl text-slate-800">IMDG Segregation Calculator</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-3">Dangerous Goods Class A</label>
                    <select wire:model="checkClassA" wire:change="checkSegregation" class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 font-medium">
                        <option value="">Select IMDG Class...</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->class_code }}">{{ $c->class_code }} - {{ Str::limit($c->name, 35) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-3">Dangerous Goods Class B</label>
                    <select wire:model="checkClassB" wire:change="checkSegregation" class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 font-medium">
                        <option value="">Select IMDG Class...</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->class_code }}">{{ $c->class_code }} - {{ Str::limit($c->name, 35) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            @if($compatibilityResult)
            <div class="p-6 rounded-2xl flex items-center gap-6 border-2 transition-all transform scale-100 animate-fadeIn
                {{ $compatibilityResult === 'prohibited' ? 'bg-red-50 border-red-500 shadow-lg shadow-red-200' : 
                   ($compatibilityResult === 'allowed' ? 'bg-emerald-50 border-emerald-500 shadow-lg shadow-emerald-200' : 
                   'bg-amber-50 border-amber-500 shadow-lg shadow-amber-200') }}">
                <div class="flex-shrink-0">
                    <div class="w-20 h-20 rounded-2xl flex items-center justify-center text-5xl
                        {{ $compatibilityResult === 'prohibited' ? 'bg-red-600 shadow-lg shadow-red-300' : 
                           ($compatibilityResult === 'allowed' ? 'bg-emerald-600 shadow-lg shadow-emerald-300' : 
                           'bg-amber-600 shadow-lg shadow-amber-300') }}">
                        @if($compatibilityResult === 'prohibited') 
                            <span class="animate-pulse">⛔</span>
                        @elseif($compatibilityResult === 'allowed') 
                            <span>✅</span>
                        @else 
                            <span class="animate-pulse">⚠️</span>
                        @endif
                    </div>
                </div>
                <div class="flex-1">
                    <p class="font-black uppercase text-2xl tracking-wide mb-2
                        {{ $compatibilityResult === 'prohibited' ? 'text-red-700' : 
                           ($compatibilityResult === 'allowed' ? 'text-emerald-700' : 'text-amber-700') }}">
                        {{ strtoupper($compatibilityResult) }}
                    </p>
                    <p class="text-sm font-medium
                        {{ $compatibilityResult === 'prohibited' ? 'text-red-600' : 
                           ($compatibilityResult === 'allowed' ? 'text-emerald-600' : 'text-amber-600') }}">
                        @if($compatibilityResult === 'prohibited')
                            ⚠️ CRITICAL: These classes MUST NOT be transported or stored together. Immediate separation required per IMDG Code.
                        @elseif($compatibilityResult === 'allowed')
                            ✓ SAFE: Standard safety precautions apply. Compatible for co-loading/storage.
                        @else
                            ⚠️ RESTRICTED: Minimum separation distance required. Check specific IMDG segregation table (typically 3-6 meters).
                        @endif
                    </p>
                </div>
            </div>
            @else
            <div class="p-8 rounded-2xl border-2 border-dashed border-slate-300 text-center bg-slate-50">
                <svg class="w-16 h-16 mx-auto mb-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                <p class="text-slate-500 font-medium">Select two DG classes to check compatibility</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Active Declarations with Enhanced Table -->
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border border-slate-200">
        <div class="px-8 py-6 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-slate-100">
            <div class="flex items-center justify-between">
                <h3 class="font-black text-slate-800 text-2xl">Active DG Declarations</h3>
                <div class="flex gap-3">
                    <button class="px-4 py-2 bg-white text-slate-600 font-bold rounded-lg hover:bg-slate-50 transition-all text-sm border border-slate-200 shadow-sm">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        Filter
                    </button>
                    <button class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-700 text-white font-bold rounded-lg shadow-lg hover:shadow-2xl transition-all text-sm">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Export
                    </button>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-600 uppercase font-bold text-xs border-b border-slate-200">
                    <tr>
                        <th class="px-8 py-4">UN Number</th>
                        <th class="px-8 py-4">Proper Shipping Name</th>
                        <th class="px-8 py-4">IMDG Class</th>
                        <th class="px-8 py-4">Packing Group</th>
                        <th class="px-8 py-4">NEQ (kg)</th>
                        <th class="px-8 py-4">Status</th>
                        <th class="px-8 py-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($declarations as $d)
                    <tr class="hover:bg-slate-50 transition-all">
                        <td class="px-8 py-5">
                            <span class="font-mono font-bold text-slate-800 bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200">{{ $d->un_number }}</span>
                        </td>
                        <td class="px-8 py-5 text-slate-700 font-medium">{{ $d->proper_shipping_name }}</td>
                        <td class="px-8 py-5">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg font-bold border-2
                                {{ str_starts_with($d->dgClass->class_code, '1') ? 'bg-red-100 text-red-700 border-red-300' : 
                                   (str_starts_with($d->dgClass->class_code, '3') ? 'bg-orange-100 text-orange-700 border-orange-300' : 
                                   'bg-slate-100 text-slate-700 border-slate-300') }}">
                                {{ $d->dgClass->class_code }}
                            </span>
                        </td>
                        <td class="px-8 py-5 text-slate-700 font-bold">{{ $d->packing_group ?? '-' }}</td>
                        <td class="px-8 py-5 text-slate-700 font-mono">{{ $d->neq_kg ?? '-' }}</td>
                        <td class="px-8 py-5">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-emerald-100 text-emerald-700 font-bold text-xs uppercase border border-emerald-300">
                                <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2 animate-pulse"></span>
                                Cleared
                            </span>
                        </td>
                        <td class="px-8 py-5">
                            <button class="px-3 py-1.5 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 transition-all text-xs shadow-sm">
                                Details
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-8 py-12 text-center text-slate-400 italic">
                            <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            No active declarations found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <style>
    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }

    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
    }
    </style>
</div>
