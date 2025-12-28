<div class="p-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Executive Analytics</h1>
            <p class="text-slate-500 mt-2">Real-time Tier-3 Port Performance Intelligence.</p>
        </div>
        <div class="flex gap-2">
            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold uppercase tracking-wide flex items-center">
                <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2 animate-pulse"></span>
                System Healthy
            </span>
        </div>
    </div>

    <!-- KPI Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Revenue -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Total Revenue (YTD)</div>
            <div class="text-2xl font-black text-slate-900">RM {{ number_format($totalRevenue, 2) }}</div>
            <div class="mt-2 text-xs font-bold text-emerald-600 flex items-center">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                +12.5% vs Target
            </div>
        </div>

        <!-- Vessels -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Active Vessels</div>
            <div class="text-2xl font-black text-indigo-600">{{ $activeVessels }}</div>
            <div class="mt-2 text-xs font-medium text-slate-500">Currently Alongside</div>
        </div>

        <!-- Operations -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Yard Density</div>
            <div class="text-2xl font-black text-blue-500">{{ number_format($yardUtilization, 1) }}%</div>
            <div class="mt-2 w-full bg-slate-100 rounded-full h-1.5">
                <div class="bg-blue-500 h-1.5 rounded-full" style="width: {{ $yardUtilization }}%"></div>
            </div>
        </div>

        <!-- Safety -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Active HSE Permits</div>
            <div class="text-2xl font-black text-amber-500">{{ $activePermits }}</div>
            <div class="mt-2 text-xs font-medium text-slate-500">High Risk Operations</div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Revenue Trend -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
            <h3 class="font-bold text-slate-900 mb-6">Revenue Trend (6 Months)</h3>
            <div id="revenueChart" class="h-64"></div>
        </div>

        <!-- Vessel Traffic -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
            <h3 class="font-bold text-slate-900 mb-6">Vessel Traffic Volume</h3>
            <div id="vesselChart" class="h-64"></div>
        </div>
    </div>

    <!-- AI Insights Box -->
    <div class="bg-indigo-900 rounded-2xl p-8 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 p-32 bg-white/5 rounded-full blur-3xl -mr-16 -mt-16"></div>
        <div class="relative z-10">
            <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
                <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                PortFlow AI Predicitons
            </h3>
            
            <div class="space-y-4">
                @forelse($aiInsights as $insight)
                <div class="flex items-start gap-3 p-3 rounded-lg border border-indigo-700 bg-indigo-800/50">
                    <span class="mt-0.5">
                        @if($insight['type'] === 'critical')
                            <svg class="w-5 h-5 text-red-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        @elseif($insight['type'] === 'warning')
                            <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        @else
                            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        @endif
                    </span>
                    <div>
                        <p class="text-sm font-medium text-indigo-100">{{ $insight['message'] }}</p>
                    </div>
                </div>
                @empty
                <p class="text-indigo-300 italic text-sm">No critical anomalies detected in the forecast model.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- ApexCharts via CDN -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    document.addEventListener('livewire:initialized', () => {
        // Revenue Chart
        const revenueOptions = {
            series: [{
                name: 'Revenue (RM)',
                data: @json($revenueData)
            }],
            chart: {
                type: 'area',
                height: 300,
                toolbar: { show: false },
                fontFamily: 'inherit'
            },
            stroke: { curve: 'smooth', width: 2 },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.1,
                    stops: [0, 90, 100]
                }
            },
            colors: ['#4f46e5'],
            xaxis: {
                categories: @json($chartLabels),
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: { show: false },
            grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
            dataLabels: { enabled: false }
        };

        const revChart = new ApexCharts(document.querySelector("#revenueChart"), revenueOptions);
        revChart.render();

        // Vessel Chart
        const vesselOptions = {
            series: [{
                name: 'Vessel Calls',
                data: @json($vesselData)
            }],
            chart: {
                type: 'bar',
                height: 300,
                toolbar: { show: false },
                fontFamily: 'inherit'
            },
            colors: ['#0ea5e9'],
            plotOptions: {
                bar: { borderRadius: 4, columnWidth: '40%' }
            },
            xaxis: {
                categories: @json($chartLabels),
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
            dataLabels: { enabled: false }
        };

        const vesChart = new ApexCharts(document.querySelector("#vesselChart"), vesselOptions);
        vesChart.render();
    });
</script>
