<div class="p-8 bg-slate-50 min-h-screen">
    <!-- Soft Pastel Header -->
    <div class="mb-8 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl p-8 border border-indigo-200 shadow-sm">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-white rounded-xl shadow-sm">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Executive Analytics</h1>
                    <p class="text-indigo-700/70 text-sm mt-1 font-medium">Real-time Tier-3 Port Performance Intelligence</p>
                </div>
            </div>
            <div class="flex gap-2">
                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold uppercase tracking-wide flex items-center">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2 animate-pulse"></span>
                    System Healthy
                </span>
            </div>
        </div>
    </div>

    <!-- Pastel KPI Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Revenue -->
        <div class="bg-gradient-to-br from-emerald-50 to-teal-50 p-6 rounded-2xl border border-emerald-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-white rounded-lg shadow-sm">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="text-right">
                    <div class="text-xs text-emerald-600/70 font-bold uppercase tracking-widest">Total Revenue (YTD)</div>
                    <div class="text-3xl font-black text-emerald-900 tabular-nums">RM {{ number_format($totalRevenue, 2) }}</div>
                </div>
            </div>
            <div class="text-xs font-bold text-emerald-700 flex items-center">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                +12.5% vs Target
            </div>
        </div>

        <!-- Vessels -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-6 rounded-2xl border border-blue-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-white rounded-lg shadow-sm">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <div class="text-right">
                    <div class="text-xs text-blue-600/70 font-bold uppercase tracking-widest">Active Vessels</div>
                    <div class="text-3xl font-black text-blue-900 tabular-nums">{{ $activeVessels }}</div>
                </div>
            </div>
            <div class="text-xs text-blue-700 font-medium">Currently Alongside</div>
        </div>

        <!-- Operations -->
        <div class="bg-gradient-to-br from-purple-50 to-pink-50 p-6 rounded-2xl border border-purple-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-white rounded-lg shadow-sm">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <div class="text-right">
                    <div class="text-xs text-purple-600/70 font-bold uppercase tracking-widest">Yard Density</div>
                    <div class="text-3xl font-black text-purple-900 tabular-nums">{{ number_format($yardUtilization, 1) }}%</div>
                </div>
            </div>
            <div class="w-full bg-white/60 rounded-full h-2 overflow-hidden border border-white/40">
                <div class="bg-purple-500 h-2 rounded-full transition-all" style="width: {{ $yardUtilization }}%"></div>
            </div>
        </div>

        <!-- Safety -->
        <div class="bg-gradient-to-br from-amber-50 to-orange-50 p-6 rounded-2xl border border-amber-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-white rounded-lg shadow-sm">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <div class="text-right">
                    <div class="text-xs text-amber-600/70 font-bold uppercase tracking-widest">Active HSE Permits</div>
                    <div class="text-3xl font-black text-amber-900 tabular-nums">{{ $activePermits }}</div>
                </div>
            </div>
            <div class="text-xs text-amber-700 font-medium">High Risk Operations</div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Revenue Trend -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
            <h3 class="font-bold text-slate-900 mb-6 text-base">Revenue Trend (6 Months)</h3>
            <div id="revenueChart" class="h-64"></div>
        </div>

        <!-- Vessel Traffic -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
            <h3 class="font-bold text-slate-900 mb-6 text-base">Vessel Traffic Volume</h3>
            <div id="vesselChart" class="h-64"></div>
        </div>
    </div>

    <!-- AI Insights Box with Pastel Design -->
    <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl p-8 border border-indigo-200 shadow-sm">
        <div class="flex items-center gap-3 mb-6">
            <div class="p-2 bg-white rounded-lg shadow-sm">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-slate-900">PortFlow AI Predictions</h3>
        </div>
        
        <div class="space-y-3">
            @forelse($aiInsights as $insight)
            <div class="flex items-start gap-3 p-4 rounded-xl bg-white/60 border border-white/40">
                <span class="mt-0.5">
                    @if($insight['type'] === 'critical')
                        <div class="p-1.5 bg-rose-100 rounded-lg">
                            <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                    @elseif($insight['type'] === 'warning')
                        <div class="p-1.5 bg-amber-100 rounded-lg">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    @else
                        <div class="p-1.5 bg-emerald-100 rounded-lg">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    @endif
                </span>
                <div>
                    <p class="text-sm font-medium text-slate-700">{{ $insight['message'] }}</p>
                </div>
            </div>
            @empty
            <p class="text-indigo-700/70 italic text-sm">No critical anomalies detected in the forecast model.</p>
            @endforelse
        </div>
    </div>
</div>

<!-- ApexCharts via CDN -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    document.addEventListener('livewire:initialized', () => {
        // Revenue Chart with Pastel Colors
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
                    opacityFrom: 0.4,
                    opacityTo: 0.1,
                    stops: [0, 90, 100]
                }
            },
            colors: ['#10b981'], // Emerald-500
            xaxis: {
                categories: @json($chartLabels),
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: {
                    style: {
                        colors: '#64748b',
                        fontSize: '12px',
                        fontWeight: 500
                    }
                }
            },
            yaxis: { 
                show: true,
                labels: {
                    style: {
                        colors: '#64748b',
                        fontSize: '12px',
                        fontWeight: 500
                    },
                    formatter: function(val) {
                        return 'RM ' + val.toLocaleString();
                    }
                }
            },
            grid: { borderColor: '#e2e8f0', strokeDashArray: 4 },
            dataLabels: { enabled: false },
            tooltip: {
                theme: 'light',
                y: {
                    formatter: function(val) {
                        return 'RM ' + val.toLocaleString();
                    }
                }
            }
        };

        const revChart = new ApexCharts(document.querySelector("#revenueChart"), revenueOptions);
        revChart.render();

        // Vessel Chart with Pastel Colors
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
            colors: ['#6366f1'], // Indigo-500
            plotOptions: {
                bar: { 
                    borderRadius: 8, 
                    columnWidth: '50%',
                    distributed: false
                }
            },
            xaxis: {
                categories: @json($chartLabels),
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: {
                    style: {
                        colors: '#64748b',
                        fontSize: '12px',
                        fontWeight: 500
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#64748b',
                        fontSize: '12px',
                        fontWeight: 500
                    }
                }
            },
            grid: { borderColor: '#e2e8f0', strokeDashArray: 4 },
            dataLabels: { enabled: false },
            tooltip: {
                theme: 'light'
            }
        };

        const vesChart = new ApexCharts(document.querySelector("#vesselChart"), vesselOptions);
        vesChart.render();
    });
</script>
