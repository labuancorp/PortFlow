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
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Incident & Near-Miss Intelligence</h1>
            <p class="text-slate-500 mt-2">Active hazard monitoring and investigative safety workflows.</p>
        </div>
        <button wire:click="openReportModal" class="bg-red-600 hover:bg-red-500 text-white px-6 py-3 rounded-xl font-bold flex items-center gap-2 shadow-lg shadow-red-900/20 transition-all transform active:scale-95">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
             Report Safety Issue
        </button>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Open Cases</p>
            <p class="text-3xl font-black text-slate-900">{{ \App\Models\SafetyIncident::where('status', 'open')->count() }}</p>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Severity: High/Crit</p>
            <p class="text-3xl font-black text-red-600">{{ \App\Models\SafetyIncident::whereIn('severity', ['high', 'critical'])->count() }}</p>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Avg Resolution</p>
            <p class="text-3xl font-black text-slate-900">4.2h</p>
        </div>
        <div class="bg-emerald-600 p-6 rounded-2xl shadow-lg shadow-emerald-900/20 text-white">
            <p class="text-[10px] font-black text-emerald-200 uppercase tracking-widest mb-1">Total Observations</p>
            <p class="text-3xl font-black">{{ \App\Models\SafetyIncident::count() }}</p>
        </div>
    </div>

    <!-- List View -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-100 text-[10px] uppercase tracking-wider text-slate-500 font-bold">
                    <th class="px-6 py-4">Identification</th>
                    <th class="px-6 py-4">Status / Severity</th>
                    <th class="px-6 py-4">Reporter</th>
                    <th class="px-6 py-4">Timeline</th>
                    <th class="px-6 py-4 text-right">Protocol</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($incidents as $incident)
                <tr class="hover:bg-slate-50/80 transition-colors group">
                    <td class="px-6 py-5">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center overflow-hidden border border-slate-200">
                                @if($incident->image_path)
                                    <img src="{{ Storage::url($incident->image_path) }}" class="object-cover w-full h-full">
                                @else
                                    <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-black text-slate-900 capitalize">{{ str_replace('_', ' ', $incident->type) }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">{{ $incident->location }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-5">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase border
                                {{ $incident->status === 'open' ? 'bg-red-50 text-red-600 border-red-100' : 
                                   ($incident->status === 'investigating' ? 'bg-amber-50 text-amber-600 border-amber-100' : 
                                   'bg-emerald-50 text-emerald-600 border-emerald-100') }}">
                                {{ $incident->status }}
                            </span>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $incident->severity }} RISK</span>
                        </div>
                    </td>
                    <td class="px-6 py-5">
                        <p class="text-sm font-bold text-slate-800">{{ $incident->reporter->name }}</p>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">{{ $incident->organization->name }}</p>
                    </td>
                    <td class="px-6 py-5">
                        <p class="text-xs font-black text-slate-700">{{ $incident->reported_at->format('d M') }}</p>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">{{ $incident->reported_at->format('H:i') }} HRS</p>
                    </td>
                    <td class="px-6 py-5 text-right">
                        @if(in_array(auth()->user()->role, ['admin', 'hse']))
                            <button wire:click="openInvestigation({{ $incident->id }})" class="bg-slate-900 text-white px-4 py-2 rounded-xl text-xs font-black hover:bg-slate-800 transition-all uppercase tracking-widest">Investigate</button>
                        @else
                            <button class="text-slate-400 cursor-not-allowed italic text-xs">Awaiting HSE Review</button>
                        @endif
                    </td>
                </tr>
                @empty
                 <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <p class="text-slate-400 font-bold uppercase tracking-widest text-sm">No safety incidents reported.</p>
                    </td>
                 </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $incidents->links() }}
        </div>
    </div>

    <!-- Report Modal -->
    @if($showReportModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="$set('showReportModal', false)"></div>
        <div class="relative bg-white w-full max-w-xl rounded-3xl shadow-2xl border border-white/20 overflow-hidden transform transition-all animate-in zoom-in duration-200">
            <div class="p-8">
                <div class="flex justify-between items-start mb-8">
                    <div>
                         <h3 class="text-2xl font-black text-slate-900 tracking-tight">Report Safety Intelligence</h3>
                         <p class="text-sm text-slate-500 mt-1">Snapshot evidence of hazards or incidents.</p>
                    </div>
                    <button wire:click="$set('showReportModal', false)" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form wire:submit.prevent="submitReport" class="space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Report Type</label>
                            <select wire:model="type" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-700 text-sm p-3 focus:ring-red-500 focus:border-red-500 transition-all">
                                <option value="hazard_observation">Hazard Observation</option>
                                <option value="near_miss">Near Miss</option>
                                <option value="incident">Actual Incident</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Potential Severity</label>
                            <select wire:model="severity" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-700 text-sm p-3 focus:ring-red-500 focus:border-red-500 transition-all">
                                <option value="low">Low (Minor Risk)</option>
                                <option value="medium">Medium (Requires Correction)</option>
                                <option value="high">High (Stop Work Likely)</option>
                                <option value="critical">Critical (Immediate Danger)</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Location Detail / Zone</label>
                        <input type="text" wire:model="location" placeholder="e.g. Jetty 3 - Crane Loading Area" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-900 text-sm p-3 focus:ring-red-500 focus:border-red-500 transition-all">
                        @error('location') <span class="text-red-500 text-[10px] font-bold mt-1 block uppercase">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Chronicle / Description</label>
                        <textarea wire:model="description" rows="4" placeholder="Describe the hazard or incident in detail..." class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-900 text-sm p-3 focus:ring-red-500 focus:border-red-500 transition-all"></textarea>
                        @error('description') <span class="text-red-500 text-[10px] font-bold mt-1 block uppercase">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Photo Evidence (Optional)</label>
                        <input type="file" wire:model="image" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:bg-slate-900 file:text-white hover:file:bg-slate-800 cursor-pointer">
                        @error('image') <span class="text-red-500 text-[10px] font-bold mt-1 block uppercase">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-6 border-t border-slate-100">
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-500 text-white py-4 rounded-2xl font-black uppercase tracking-[0.2em] shadow-xl shadow-red-900/20 transition-all active:scale-[0.98]">
                            Establish Safety Protocol
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Investigation Modal -->
    @if($showInvestigationModal && $selectedIncident)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="$set('showInvestigationModal', false)"></div>
        <div class="relative bg-white w-full max-w-2xl rounded-3xl shadow-2xl border border-white/20 overflow-hidden transform transition-all animate-in zoom-in duration-200">
            <div class="p-8">
                 <div class="flex justify-between items-start mb-8">
                    <div>
                         <span class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.3em] mb-1 block">Officer Review Panel</span>
                         <h3 class="text-2xl font-black text-slate-900 tracking-tight">HSE Investigation Dossier</h3>
                    </div>
                    <button wire:click="$set('showInvestigationModal', false)" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="grid grid-cols-2 gap-8 mb-8">
                    <div class="space-y-4">
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Reporter Insight</p>
                            <p class="text-xs text-slate-700 font-medium leading-relaxed">{{ $selectedIncident->description }}</p>
                        </div>
                        @if($selectedIncident->image_path)
                        <div class="rounded-2xl overflow-hidden border border-slate-200 shadow-sm">
                            <img src="{{ Storage::url($selectedIncident->image_path) }}" class="w-full h-48 object-cover">
                        </div>
                        @endif
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Protocol Status</label>
                            <select wire:model="status" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-900 text-sm p-3 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                <option value="open">Open (Initial Report)</option>
                                <option value="investigating">Under Investigation</option>
                                <option value="resolved">Resolved / Fixed</option>
                                <option value="closed">Closed / Logged</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Corrective Actions & Findings</label>
                            <textarea wire:model="corrective_action" rows="8" placeholder="Log evidence, root causes, and corrective actions taken..." class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-900 text-sm p-3 focus:ring-indigo-500 focus:border-indigo-500 transition-all"></textarea>
                        </div>

                        <div class="pt-2">
                             <button wire:click="saveInvestigation" class="w-full bg-slate-900 hover:bg-slate-800 text-white py-4 rounded-2xl font-black uppercase tracking-[0.2em] shadow-xl shadow-slate-900/20 transition-all active:scale-[0.98]">
                                Update Record
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
