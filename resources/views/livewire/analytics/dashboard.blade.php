<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Port Analytics</h1>
            <p class="text-slate-500">Key performance indicators and operational insights</p>
        </div>
        <div class="flex bg-white rounded-lg p-1 border border-slate-200">
            <button wire:click="$set('dateRange', 'month')" class="px-3 py-1 text-sm font-medium rounded-md {{ $dateRange === 'month' ? 'bg-indigo-100 text-indigo-700' : 'text-slate-500 hover:text-slate-900' }}">30 Days</button>
            <button wire:click="$set('dateRange', 'quarter')" class="px-3 py-1 text-sm font-medium rounded-md {{ $dateRange === 'quarter' ? 'bg-indigo-100 text-indigo-700' : 'text-slate-500 hover:text-slate-900' }}">Quarter</button>
            <button wire:click="$set('dateRange', 'year')" class="px-3 py-1 text-sm font-medium rounded-md {{ $dateRange === 'year' ? 'bg-indigo-100 text-indigo-700' : 'text-slate-500 hover:text-slate-900' }}">Year</button>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Card 1 -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Total Port Calls</p>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-3xl font-black text-slate-900">{{ $totalCalls }}</span>
                <span class="text-sm font-bold {{ $callsGrowth >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $callsGrowth >= 0 ? '+' : '' }}{{ number_format($callsGrowth, 1) }}%
                </span>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Revenue (Est.)</p>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-3xl font-black text-emerald-600">RM {{ number_format($revenue / 1000, 1) }}k</span>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Avg Turnaround</p>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-3xl font-black text-blue-600">{{ $avgTurnaround }}h</span>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Active Berths</p>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-3xl font-black text-purple-600">{{ $berthStats->count() }}</span>
                <span class="text-sm text-slate-500">utilized</span>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Berth Utilization Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="font-bold text-slate-900 mb-6">Berth Utilization</h3>
            <div class="space-y-4">
                @foreach($berthStats as $stat)
                <div>
                    <div class="flex justify-between text-sm font-medium text-slate-700 mb-1">
                        <span>{{ $stat->berth->name ?? 'Unknown' }}</span>
                        <span>{{ $stat->total }} calls</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2.5">
                        <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ min(100, ($stat->total / max(1, $totalCalls)) * 100 * 2) }}%"></div>
                    </div>
                </div>
                @endforeach
                @if($berthStats->isEmpty())
                <p class="text-center text-slate-500 py-4">No data available for this period.</p>
                @endif
            </div>
        </div>

        <!-- AI Insights -->
        <div class="bg-gradient-to-br from-indigo-900 to-slate-900 rounded-xl shadow-lg p-6 text-white">
            <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
                <span class="text-2xl">⚡</span> AI Operational Insights
            </h3>
            <div class="space-y-4">
                <div class="bg-white/10 rounded-lg p-4 backdrop-blur-sm border border-white/10">
                    <p class="text-xs font-bold text-indigo-300 uppercase mb-1">Recommendation</p>
                    <p class="text-sm font-medium">Berth utilization at <strong>Main Wharf 1</strong> is 20% higher than average. Consider routing smaller vessels to <strong>Alpha Jetty</strong> to reduce congestion.</p>
                </div>
                <div class="bg-white/10 rounded-lg p-4 backdrop-blur-sm border border-white/10">
                    <p class="text-xs font-bold text-indigo-300 uppercase mb-1">Prediction</p>
                    <p class="text-sm font-medium">Incoming vessel traffic represents a <strong>15% increase</strong> for next week. Schedule maintenance for low-traffic windows on Tuesday.</p>
                </div>
            </div>
        </div>
    </div>
</div>
