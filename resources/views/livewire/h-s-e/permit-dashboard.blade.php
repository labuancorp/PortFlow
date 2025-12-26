<div class="p-8" x-data="{ 
    toast: { show: false, message: '', type: 'success' },
    showToast(message, type = 'success') {
        this.toast = { show: true, message, type };
        setTimeout(() => { this.toast.show = false; }, 3000);
    }
 }" @notify.window="showToast($event.detail.message, $event.detail.type || 'success')">

    <!-- Notification Toast -->
    <template x-if="toast.show">
        <div class="fixed top-8 right-8 z-[60] bg-slate-900 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-4 border border-slate-700"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-8"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 translate-x-8">
            <div :class="toast.type === 'error' ? 'bg-red-500/20 text-red-400' : 'bg-teal-500/20 text-teal-400'" class="p-2 rounded-lg">
                <svg x-show="toast.type === 'success'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <svg x-show="toast.type === 'error'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h4 class="font-bold text-sm tracking-wide uppercase" :class="toast.type === 'error' ? 'text-red-400' : 'text-teal-400'" x-text="toast.type === 'error' ? 'Error' : 'Success'"></h4>
                <p class="text-sm text-slate-300" x-text="toast.message"></p>
            </div>
        </div>
    </template>

    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">HSE Safety Console</h1>
            <p class="text-slate-500 mt-2">Manage electronic Permits-to-Work (e-PTW) and safety approvals.</p>
        </div>
        <a href="{{ route('hse.permits.create') }}" class="bg-indigo-600 hover:bg-indigo-500 text-white px-6 py-3 rounded-xl font-bold shadow-lg shadow-indigo-900/20 flex items-center gap-2 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Request Permit
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex gap-4">
             <select wire:model.live="filterStatus" class="bg-slate-50 border-slate-200 rounded-lg text-sm font-bold text-slate-700 focus:ring-indigo-500 focus:border-indigo-500">
                 <option value="all">All Statuses</option>
                 <option value="requested">Requested</option>
                 <option value="approved">Approved</option>
                 <option value="active">Active</option>
                 <option value="closed">Closed / Expired</option>
             </select>
        </div>
        
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-100 text-[10px] uppercase tracking-wider text-slate-500 font-bold">
                    <th class="px-6 py-4">Control No</th>
                    <th class="px-6 py-4">Type / Location</th>
                    <th class="px-6 py-4">Applicant</th>
                    <th class="px-6 py-4">Validity</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($permits as $permit)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 font-mono text-sm font-bold text-slate-700">{{ $permit->control_no }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                             @if($permit->type == 'hot_work' || $permit->type == 'electrical')
                                <div class="w-2 h-2 rounded-full bg-red-500"></div>
                             @else
                                <div class="w-2 h-2 rounded-full bg-blue-400"></div>
                             @endif
                             <span class="font-bold text-slate-900 text-sm capitalize">{{ str_replace('_', ' ', $permit->type) }}</span>
                        </div>
                        <div class="text-xs text-slate-500 mt-1 pl-4">{{ $permit->location }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-slate-700">
                        {{ $permit->applicant_name }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-xs font-bold text-slate-700">{{ $permit->valid_from->format('d M H:i') }}</div>
                        <div class="text-xs text-slate-400">to {{ $permit->valid_to->format('d M H:i') }}</div>
                    </td>
                    <td class="px-6 py-4">
                         <div class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide border 
                            {{ $permit->status === 'requested' ? 'bg-amber-100 text-amber-700 border-amber-200' : 
                               ($permit->status === 'approved' ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 
                               ($permit->status === 'rejected' ? 'bg-red-100 text-red-700 border-red-200' : 'bg-slate-100 text-slate-600 border-slate-200')) }}">
                            {{ $permit->status }}
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        @if($permit->status === 'requested')
                            <button wire:click="approve({{ $permit->id }})" class="text-emerald-600 hover:text-emerald-800 text-xs font-bold mr-3 uppercase hover:underline">Approve</button>
                            <button wire:click="reject({{ $permit->id }})" class="text-red-500 hover:text-red-700 text-xs font-bold uppercase hover:underline">Reject</button>
                        @elseif($permit->status === 'approved' || $permit->status === 'active')
                             <button wire:click="close({{ $permit->id }})" wire:confirm="Close this work permit?" class="text-slate-500 hover:text-slate-700 text-xs font-bold uppercase hover:underline">Close Permit</button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                        <p class="font-medium">No permits found matching criteria.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $permits->links() }}
        </div>
    </div>
</div>
