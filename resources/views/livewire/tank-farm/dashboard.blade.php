<div class="p-6 bg-gradient-to-br from-slate-50 to-slate-100 min-h-screen">
    <!-- Animated Header with Particles Effect -->
    <div class="mb-8 relative overflow-hidden rounded-2xl bg-gradient-to-r from-pink-500 via-purple-500 to-indigo-600 p-8 shadow-2xl">
        <div class="absolute inset-0 opacity-20">
            <div class="absolute w-96 h-96 bg-white rounded-full blur-3xl -top-48 -left-48 animate-pulse"></div>
            <div class="absolute w-96 h-96 bg-white rounded-full blur-3xl -bottom-48 -right-48 animate-pulse delay-1000"></div>
        </div>
        <div class="relative z-10 flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-black text-white mb-2 tracking-tight">Liquid Mud Plant Intelligence</h1>
                <p class="text-pink-100 text-lg">Real-time Tank Farm Monitoring & Predictive Analytics</p>
            </div>
            <div class="flex gap-4">
                <button wire:click="openTransferModal" class="px-6 py-3 bg-white/20 backdrop-blur-lg text-white font-bold rounded-xl hover:bg-white/30 transition-all border border-white/30 shadow-lg hover:scale-105 transform">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    New Transfer
                </button>
                <button wire:click="openAnalytics" class="px-6 py-3 bg-white text-indigo-600 font-bold rounded-xl hover:shadow-2xl transition-all hover:scale-105 transform">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    Analytics
                </button>
            </div>
        </div>
    </div>

    <!-- Live Stats with Animated Counters -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-1 border-l-4 border-indigo-500">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                </div>
                <div class="text-right">
                    <div class="text-xs text-slate-400 font-bold uppercase tracking-wider">Utilization</div>
                    <div class="text-3xl font-black text-slate-800 tabular-nums">{{ number_format($stats['utilization'], 1) }}%</div>
                </div>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-2 rounded-full transition-all duration-1000 ease-out" style="width: {{ $stats['utilization'] }}%"></div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-1 border-l-4 border-emerald-500">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="text-right">
                    <div class="text-xs text-slate-400 font-bold uppercase tracking-wider">Total Volume</div>
                    <div class="text-3xl font-black text-slate-800 tabular-nums">{{ number_format($stats['total_volume']) }}<span class="text-lg text-slate-400">L</span></div>
                </div>
            </div>
            <div class="text-xs text-emerald-600 font-bold">
                <svg class="w-3 h-3 inline" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                +12.5% vs last week
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-1 border-l-4 border-amber-500">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl shadow-lg animate-pulse">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div class="text-right">
                    <div class="text-xs text-slate-400 font-bold uppercase tracking-wider">Active Transfers</div>
                    <div class="text-3xl font-black text-slate-800 tabular-nums">3</div>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <div class="flex-1 h-1 bg-amber-200 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-amber-500 to-orange-500 rounded-full animate-pulse" style="width: 65%"></div>
                </div>
                <span class="text-xs font-bold text-amber-600">65%</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-1 border-l-4 border-pink-500">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-gradient-to-br from-pink-500 to-rose-600 rounded-xl shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <div class="text-right">
                    <div class="text-xs text-slate-400 font-bold uppercase tracking-wider">AI Prediction</div>
                    <div class="text-3xl font-black text-slate-800 tabular-nums">94<span class="text-lg text-slate-400">%</span></div>
                </div>
            </div>
            <div class="text-xs text-pink-600 font-bold">Optimal efficiency score</div>
        </div>
    </div>

    <!-- Interactive 3D Tank Visualization -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Main Tank Display -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-2xl p-8 border border-slate-200">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-black text-slate-800">Live Tank Status</h2>
                <div class="flex gap-2">
                    <button wire:click="switchView('grid')" class="px-4 py-2 {{ $viewMode === 'grid' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }} font-bold rounded-lg transition-all text-sm">
                        Grid View
                    </button>
                    <button wire:click="switchView('3d')" class="px-4 py-2 {{ $viewMode === '3d' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }} font-bold rounded-lg transition-all text-sm">
                        3D View
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($equipment->take(8) as $tank)
                    @php
                        $percentage = $tank->current_volume > 0 ? ($tank->current_volume / $tank->capacity_volume) * 100 : 0;
                        $productName = $tank->product ? $tank->product->name : 'Empty';
                        $productColors = [
                            'OBM' => ['from-slate-700', 'to-slate-900', 'bg-slate-800'],
                            'WBM' => ['from-blue-400', 'to-blue-600', 'bg-blue-500'],
                            'Brine' => ['from-cyan-300', 'to-cyan-500', 'bg-cyan-400'],
                            'Base Oil' => ['from-amber-400', 'to-amber-600', 'bg-amber-500'],
                        ];
                        // Match by checking if product name contains the key
                        $colors = ['from-slate-400', 'to-slate-600', 'bg-slate-500'];
                        foreach($productColors as $key => $value) {
                            if(str_contains($productName, $key)) {
                                $colors = $value;
                                break;
                            }
                        }
                    @endphp
                    
                    <div class="group relative">
                        <!-- 3D Tank Container with Perspective -->
                        <div class="relative h-64 perspective-1000">
                            <!-- Tank Body with 3D Effect -->
                            <div class="absolute inset-x-0 bottom-0 h-56 bg-gradient-to-b from-slate-200 to-slate-300 rounded-t-3xl shadow-2xl transform group-hover:scale-105 transition-all duration-300 border-4 border-slate-400/50">
                                <!-- Liquid with Wave Animation -->
                                <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t {{ $colors[0] }} {{ $colors[1] }} rounded-t-2xl overflow-hidden transition-all duration-1000 ease-out" 
                                     style="height: {{ $percentage }}%">
                                    <!-- Animated Wave Effect -->
                                    <div class="absolute inset-0 opacity-30">
                                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white to-transparent animate-shimmer"></div>
                                    </div>
                                    <!-- Bubbles Animation -->
                                    <div class="absolute bottom-0 left-1/4 w-2 h-2 bg-white/40 rounded-full animate-bubble"></div>
                                    <div class="absolute bottom-0 left-1/2 w-1.5 h-1.5 bg-white/30 rounded-full animate-bubble delay-500"></div>
                                    <div class="absolute bottom-0 right-1/4 w-1 h-1 bg-white/20 rounded-full animate-bubble delay-1000"></div>
                                </div>
                                
                                <!-- Level Indicator Lines -->
                                <div class="absolute inset-0 pointer-events-none">
                                    <div class="absolute left-0 right-0 h-px bg-white/20" style="top: 25%"></div>
                                    <div class="absolute left-0 right-0 h-px bg-white/20" style="top: 50%"></div>
                                    <div class="absolute left-0 right-0 h-px bg-white/20" style="top: 75%"></div>
                                </div>
                            </div>
                            
                            <!-- Tank Top Cap with Shine -->
                            <div class="absolute inset-x-0 top-0 h-8 bg-gradient-to-b from-slate-400 to-slate-500 rounded-full shadow-xl border-4 border-slate-500/50 transform group-hover:scale-105 transition-all duration-300">
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent rounded-full"></div>
                            </div>
                        </div>

                        <!-- Info Card with Glassmorphism -->
                        <div class="mt-4 p-4 bg-white/80 backdrop-blur-lg rounded-xl shadow-lg border border-slate-200/50 group-hover:shadow-2xl transition-all">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="font-black text-slate-800 text-sm">{{ $tank->name }}</h3>
                                <span class="px-2 py-1 bg-{{ $percentage > 90 ? 'red' : ($percentage > 70 ? 'amber' : 'emerald') }}-100 text-{{ $percentage > 90 ? 'red' : ($percentage > 70 ? 'amber' : 'emerald') }}-700 text-xs font-bold rounded-full">
                                    {{ number_format($percentage, 0) }}%
                                </span>
                            </div>
                            
                            <div class="space-y-1 text-xs">
                                <div class="flex justify-between">
                                    <span class="text-slate-500">Product:</span>
                                    <span class="font-bold {{ $colors[2] }} text-white px-2 py-0.5 rounded">{{ $productName }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-500">Volume:</span>
                                    <span class="font-mono font-bold text-slate-700">{{ number_format($tank->current_volume) }}L</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-500">Capacity:</span>
                                    <span class="font-mono text-slate-600">{{ number_format($tank->capacity_volume) }}L</span>
                                </div>
                            </div>

                            <!-- Quick Actions -->
                            <div class="mt-3 flex gap-2">
                                <button wire:click="openTransferFrom({{ $tank->id }})" class="flex-1 px-2 py-1.5 bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-xs font-bold rounded-lg hover:shadow-lg transition-all transform hover:scale-105">
                                    Transfer
                                </button>
                                <button class="px-2 py-1.5 bg-slate-100 text-slate-600 text-xs font-bold rounded-lg hover:bg-slate-200 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- AI Insights Panel -->
        <div class="space-y-6">
            <!-- Predictive Analytics -->
            <div class="bg-gradient-to-br from-purple-600 to-indigo-700 rounded-2xl p-6 shadow-2xl text-white">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-white/20 rounded-lg backdrop-blur-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                    </div>
                    <h3 class="font-black text-lg">AI Insights</h3>
                </div>
                
                <div class="space-y-4">
                    <div class="p-4 bg-white/10 backdrop-blur-lg rounded-xl border border-white/20">
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-emerald-500 rounded-lg">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div class="flex-1">
                                <div class="font-bold text-sm mb-1">Optimal Transfer Window</div>
                                <div class="text-xs text-purple-100">Tank T-03 → T-07 recommended in next 2 hours for cost efficiency</div>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 bg-white/10 backdrop-blur-lg rounded-xl border border-white/20">
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-amber-500 rounded-lg animate-pulse">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <div class="flex-1">
                                <div class="font-bold text-sm mb-1">Capacity Alert</div>
                                <div class="text-xs text-purple-100">Tank T-01 reaching 95% - Schedule discharge within 24h</div>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 bg-white/10 backdrop-blur-lg rounded-xl border border-white/20">
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-blue-500 rounded-lg">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            </div>
                            <div class="flex-1">
                                <div class="font-bold text-sm mb-1">Demand Forecast</div>
                                <div class="text-xs text-purple-100">OBM demand +18% predicted next week based on vessel schedule</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Real-time Activity Feed -->
            <div class="bg-white rounded-2xl p-6 shadow-xl border border-slate-200">
                <h3 class="font-black text-slate-800 mb-4 flex items-center gap-2">
                    <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                    Live Activity
                </h3>
                
                <div class="space-y-3">
                    <div class="flex items-start gap-3 p-3 bg-slate-50 rounded-lg hover:bg-slate-100 transition-all">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path></svg>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-bold text-slate-800">Transfer Started</div>
                            <div class="text-xs text-slate-500">T-03 → T-07 • 5,000L WBM</div>
                            <div class="text-xs text-slate-400 mt-1">2 minutes ago</div>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 p-3 bg-slate-50 rounded-lg hover:bg-slate-100 transition-all">
                        <div class="p-2 bg-emerald-100 rounded-lg">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-bold text-slate-800">Quality Check Passed</div>
                            <div class="text-xs text-slate-500">Tank T-05 • OBM Batch #2401</div>
                            <div class="text-xs text-slate-400 mt-1">15 minutes ago</div>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 p-3 bg-slate-50 rounded-lg hover:bg-slate-100 transition-all">
                        <div class="p-2 bg-purple-100 rounded-lg">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-bold text-slate-800">New Delivery</div>
                            <div class="text-xs text-slate-500">12,000L Base Oil received</div>
                            <div class="text-xs text-slate-400 mt-1">1 hour ago</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transfer Modal -->
    @if($showTransferModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm" wire:click.self="closeTransferModal">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl p-8 relative border border-slate-200">
            <button wire:click="closeTransferModal" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            
            <h2 class="text-3xl font-black text-slate-800 mb-6">New Transfer</h2>
            
            @if (session()->has('success'))
                <div class="mb-4 p-4 bg-emerald-100 border border-emerald-500 text-emerald-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif
            
            @if (session()->has('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-500 text-red-700 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif
            
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">From Tank</label>
                    <select wire:model="transfer_from_tank" class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50">
                        <option value="">Select source tank...</option>
                        @foreach($equipment as $tank)
                            @if($tank->current_volume > 0)
                                <option value="{{ $tank->id }}">{{ $tank->name }} ({{ number_format($tank->current_volume) }}L available)</option>
                            @endif
                        @endforeach
                    </select>
                    @error('transfer_from_tank') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">To Tank</label>
                    <select wire:model="transfer_to_tank" class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50">
                        <option value="">Select destination tank...</option>
                        @foreach($equipment as $tank)
                            <option value="{{ $tank->id }}">{{ $tank->name }} ({{ number_format($tank->capacity_volume - $tank->current_volume) }}L capacity)</option>
                        @endforeach
                    </select>
                    @error('transfer_to_tank') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
            
            <div class="mt-6">
                <label class="block text-sm font-bold text-slate-700 mb-2">Volume (Liters)</label>
                <input type="number" wire:model="transfer_volume" min="1" step="100" class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50" placeholder="Enter volume in liters">
                @error('transfer_volume') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            
            <div class="mt-8 flex gap-3">
                <button wire:click="closeTransferModal" class="flex-1 px-6 py-3 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all">Cancel</button>
                <button wire:click="submitTransfer" class="flex-1 px-6 py-3 text-sm font-bold text-white bg-gradient-to-r from-indigo-600 to-purple-700 hover:shadow-2xl hover:shadow-indigo-500/50 rounded-xl transition-all">
                    Start Transfer
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Analytics Modal -->
    @if($showAnalyticsModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm" wire:click.self="closeAnalytics">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl p-8 relative border border-slate-200 max-h-[90vh] overflow-y-auto">
            <button wire:click="closeAnalytics" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            
            <h2 class="text-3xl font-black text-slate-800 mb-6">Tank Farm Analytics</h2>
            
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl p-6 text-white">
                    <div class="text-sm font-bold opacity-80 mb-2">Total Capacity</div>
                    <div class="text-4xl font-black">{{ number_format($stats['total_capacity']) }}L</div>
                </div>
                <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl p-6 text-white">
                    <div class="text-sm font-bold opacity-80 mb-2">Current Volume</div>
                    <div class="text-4xl font-black">{{ number_format($stats['total_volume']) }}L</div>
                </div>
            </div>
            
            <div class="bg-slate-50 rounded-xl p-6 mb-6">
                <h3 class="font-bold text-slate-800 mb-4">Tank Utilization</h3>
                <div class="space-y-3">
                    @foreach($equipment as $tank)
                        @php
                            $util = $tank->capacity_volume > 0 ? ($tank->current_volume / $tank->capacity_volume) * 100 : 0;
                        @endphp
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="font-medium text-slate-700">{{ $tank->name }}</span>
                                <span class="font-bold text-slate-600">{{ number_format($util, 1) }}%</span>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-2 rounded-full transition-all" style="width: {{ $util }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <button wire:click="closeAnalytics" class="w-full px-6 py-3 text-sm font-bold text-white bg-gradient-to-r from-indigo-600 to-purple-700 hover:shadow-2xl rounded-xl transition-all">
                Close
            </button>
        </div>
    </div>
    @endif


    <style>
    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }

    @keyframes bubble {
        0% { transform: translateY(0) scale(1); opacity: 0.7; }
        100% { transform: translateY(-200px) scale(0); opacity: 0; }
    }

    .animate-shimmer {
        animation: shimmer 3s infinite;
    }

    .animate-bubble {
        animation: bubble 4s infinite ease-in;
    }

    .delay-500 {
        animation-delay: 0.5s;
    }

    .delay-1000 {
        animation-delay: 1s;
    }

    .perspective-1000 {
        perspective: 1000px;
    }
    </style>
</div>
