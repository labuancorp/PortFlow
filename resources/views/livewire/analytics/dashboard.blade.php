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
            <h3 class="text-xl font-bold mb-2 flex items-center gap-2">
                <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                PortFlow AI Insights
            </h3>
            <p class="text-indigo-200 mb-4 max-w-2xl">Based on current predictive models, Yard Density is expected to increase by 15% next week due to incoming heavy-lift vessels. Recommendation: Expedite manifest clearance for Zone A2.</p>
            <button class="bg-white text-indigo-900 px-4 py-2 rounded-lg text-sm font-bold hover:bg-indigo-50 transition-colors">View Detailed Report</button>
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
