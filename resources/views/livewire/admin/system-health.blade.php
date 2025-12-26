<div class="p-8 bg-slate-900 min-h-screen text-slate-100 font-sans">
    <!-- Header -->
    <div class="flex justify-between items-end mb-8">
        <div>
            <span class="text-xs font-bold text-red-500 uppercase tracking-widest border border-red-500/20 bg-red-500/10 px-2 py-1 rounded">Restricted Access</span>
            <h1 class="text-3xl font-black text-white tracking-tight mt-2">System Health & Security</h1>
            <p class="text-slate-400 mt-1">Admin Control Panel • v2.1.0-beta</p>
        </div>
        <div class="flex items-center gap-4">
             <div class="flex items-center gap-2 px-4 py-2 bg-green-500/10 border border-green-500/20 rounded-lg">
                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                <span class="text-xs font-bold text-green-400">Services Operational</span>
             </div>
        </div>
    </div>

    <!-- Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="p-6 bg-slate-800 rounded-2xl border border-slate-700">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Database</p>
            <h3 class="text-2xl font-bold text-white mt-1">{{ $dbSize }}</h3>
            <p class="text-xs text-slate-500 mt-2">SQLite Mode</p>
        </div>
        <div class="p-6 bg-slate-800 rounded-2xl border border-slate-700">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Active Users</p>
            <h3 class="text-2xl font-bold text-white mt-1">{{ $activeUsers }}</h3>
            <p class="text-xs text-slate-500 mt-2">Registered Accounts</p>
        </div>
        <div class="p-6 bg-slate-800 rounded-2xl border border-slate-700">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Error Rate</p>
            <h3 class="text-2xl font-bold text-green-400 mt-1">{{ $errorCount }}%</h3>
            <p class="text-xs text-slate-500 mt-2">Last 24 Hours</p>
        </div>
        <div class="p-6 bg-slate-800 rounded-2xl border border-slate-700">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Security Score</p>
            <h3 class="text-2xl font-bold text-blue-400 mt-1">A+</h3>
            <p class="text-xs text-slate-500 mt-2">Audit Logs Active</p>
        </div>
    </div>

    <!-- Audit Logs -->
    <div class="bg-slate-800 rounded-2xl border border-slate-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-700 bg-slate-800/50 flex justify-between items-center">
            <h3 class="font-bold text-white">Recent Audit Activity</h3>
            <span class="text-xs text-slate-400 font-mono">/var/log/audit.log</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-400">
                <thead class="bg-slate-900/50 text-xs uppercase font-medium text-slate-500">
                    <tr>
                        <th class="px-6 py-3">Time</th>
                        <th class="px-6 py-3">User</th>
                        <th class="px-6 py-3">Action</th>
                        <th class="px-6 py-3">Module</th>
                        <th class="px-6 py-3">Details</th>
                        <th class="px-6 py-3 text-right">IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @forelse($recentLogs as $log)
                    <tr class="hover:bg-slate-700/50 transition-colors">
                        <td class="px-6 py-4 font-mono text-xs">{{ $log->created_at->format('H:i:s') }}</td>
                        <td class="px-6 py-4 font-bold text-white">{{ $log->user->name ?? 'System' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 rounded text-[10px] uppercase font-bold
                                {{ $log->action === 'delete' ? 'bg-red-500/20 text-red-400' :
                                   ($log->action === 'create' ? 'bg-green-500/20 text-green-400' : 
                                   'bg-blue-500/20 text-blue-400') }}">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="px-6 py-4 uppercase text-xs tracking-wider">{{ $log->module }}</td>
                        <td class="px-6 py-4 truncate max-w-xs text-slate-500">{{ Str::limit($log->details, 50) }}</td>
                        <td class="px-6 py-4 text-right font-mono text-xs text-slate-600">{{ $log->ip_address }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-600 italic">No logs found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
