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
        <div class="flex gap-2">
             <button wire:click="createTestConflict" class="bg-red-600 hover:bg-red-500 text-white px-4 py-3 rounded-xl font-bold shadow-lg shadow-red-900/20 flex items-center gap-2 transition-all text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                Simulate Conflict (Demo)
            </button>
            <a href="{{ route('hse.permits.create') }}" class="bg-indigo-600 hover:bg-indigo-500 text-white px-6 py-3 rounded-xl font-bold flex items-center gap-2 shadow-lg shadow-indigo-900/20 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Request Permit
            </a>
        </div>
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
                        @if(auth()->user()->role === 'admin')
                            <button wire:click="openReview({{ $permit->id }})" class="bg-slate-900 text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-slate-800 transition-all">Review</button>
                        @else
                            <button wire:click="openReview({{ $permit->id }})" class="bg-indigo-50 text-indigo-600 border border-indigo-100 px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-indigo-100 transition-all">View Details</button>
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
    <!-- Review Modal -->
    @if($showReviewModal && $selectedPermit)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="closeReviewModal"></div>
        <div class="relative bg-white w-full max-w-2xl rounded-3xl shadow-2xl border border-slate-200 overflow-hidden transform transition-all">
            <div class="p-8">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest leading-none mb-1">{{ $selectedPermit->control_no }}</p>
                        <h3 class="text-2xl font-black text-slate-900 tracking-tight">Permit Dossier & Review</h3>
                    </div>
                    <button wire:click="closeReviewModal" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
 
                <div class="grid grid-cols-2 gap-8 mb-8">
                    <div class="space-y-4">
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Type / Risk Level</label>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="px-2 py-0.5 rounded bg-red-50 text-red-700 text-[10px] font-black border border-red-200 uppercase">{{ str_replace('_', ' ', $selectedPermit->type) }}</span>
                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-[10px] font-black border border-slate-200 uppercase">HIGH RISK</span>
                            </div>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Work Description</label>
                            <p class="text-sm text-slate-600 font-medium leading-relaxed mt-1">{{ $selectedPermit->description ?: 'No description provided.' }}</p>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Location</label>
                            <p class="text-sm font-bold text-slate-900 mt-1">{{ $selectedPermit->location }}</p>
                        </div>
                    </div>
 
                    <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 block">Digital Safety Proof</label>
                        <div class="space-y-3">
                            @foreach($safetyChecklist as $check => $value)
                            <div class="flex items-center justify-between">
                                <span class="text-[11px] font-bold text-slate-600">{{ $check }}</span>
                                @if(is_bool($value))
                                    @if($value)
                                        <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                    @else
                                        <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                                    @endif
                                @else
                                    <span class="text-[10px] font-black text-indigo-600">{{ $value }}</span>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
 
                <div class="flex gap-3 pt-6 border-t border-slate-100">
                    <button wire:click="closeReviewModal" class="px-6 py-2.5 rounded-xl border border-slate-200 font-bold text-slate-500 hover:bg-slate-50 transition-colors text-sm">Close</button>
                    
                    @if(auth()->user()->role === 'admin')
                        @if($selectedPermit->status === 'requested')
                            <div class="flex-1 flex gap-2">
                                <button wire:click="reject({{ $selectedPermit->id }})" class="flex-1 px-6 py-2.5 bg-red-100 text-red-700 hover:bg-red-200 rounded-xl font-bold uppercase tracking-widest text-xs transition-all">Reject Permit</button>
                                <button wire:click="approve({{ $selectedPermit->id }})" class="flex-2 px-8 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl font-bold uppercase tracking-widest text-xs shadow-lg shadow-emerald-900/20 transition-all">Approve & Issue</button>
                            </div>
                        @elseif(in_array($selectedPermit->status, ['approved', 'active']))
                            <button wire:click="close({{ $selectedPermit->id }})" class="flex-1 px-8 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-bold uppercase tracking-widest text-xs transition-all">Complete & Close Work</button>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
