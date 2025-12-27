<div class="p-8 bg-slate-50 min-h-screen font-sans">
    <!-- Header -->
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Fleet Registry</h1>
            <p class="text-slate-500 mt-1">Global database of authorized vessels.</p>
        </div>
        <button type="button" wire:click="create" class="px-5 py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-bold text-sm shadow-lg shadow-indigo-900/20 flex items-center gap-2 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Register Asset
        </button>
    </div>

    <!-- Toolbar -->
    <div class="flex flex-col md:flex-row gap-4 mb-6">
        <div class="relative flex-1">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input wire:model.live="search" type="text" class="w-full pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-xl font-bold text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all shadow-sm" placeholder="Search fleet by Name, IMO, or Flag...">
        </div>
        <div class="w-full md:w-48">
             <select wire:model.live="typeFilter" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm cursor-pointer">
                <option value="">All Vessel Types</option>
                <option value="OSV">OSV</option>
                <option value="Barge">Barge</option>
                <option value="Tanker">Tanker</option>
                <option value="Tug">Tug</option>
            </select>
        </div>
    </div>

    <!-- Registry Grid/Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Identification</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Classification</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Ownership</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Specs</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Controls</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($vessels as $vessel)
                    <tr class="group hover:bg-slate-50/80 transition-colors">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 font-black text-lg group-hover:scale-110 transition-transform duration-300 shadow-sm">
                                    {{ substr($vessel->name, 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">{{ $vessel->name }}</h3>
                                    <p class="text-xs text-slate-500 font-mono mt-0.5">IMO: <span class="text-slate-700 font-bold">{{ $vessel->imo_number }}</span></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex flex-col items-start gap-1">
                                <span class="px-2.5 py-1 rounded-lg bg-indigo-100 text-indigo-700 text-[10px] font-bold uppercase tracking-wide">
                                    {{ $vessel->vessel_type }}
                                </span>
                                <span class="text-xs font-bold text-slate-500 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-8a2 2 0 012-2h14a2 2 0 012 2v8M12 3v18"></path></svg>
                                    {{ $vessel->flag_country }}
                                </span>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <span class="font-bold text-slate-700 text-sm">{{ $vessel->organization->name ?? 'Unknown' }}</span>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">Charterer / Agent</p>
                        </td>
                        <td class="px-8 py-5">
                             <div class="flex gap-4">
                                <div>
                                    <span class="block text-[9px] text-slate-400 font-bold uppercase tracking-wider">Length</span>
                                    <span class="text-sm font-bold text-slate-800">{{ $vessel->loa_meters }}m</span>
                                </div>
                                <div>
                                    <span class="block text-[9px] text-slate-400 font-bold uppercase tracking-wider">Draft</span>
                                    <span class="text-sm font-bold text-slate-800">{{ $vessel->draft_meters }}m</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button wire:click="edit({{ $vessel->id }})" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all" title="Edit Properties">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                <button wire:click="delete({{ $vessel->id }})" 
                                        wire:confirm="Are you sure you want to maintain deletion of this vessel? This cannot be undone."
                                        class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Decommission (Delete)">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-16 text-center">
                            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                                <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900">No Vessels Found</h3>
                            <p class="text-slate-500 mt-1 max-w-sm mx-auto">The registry is empty based on your current filters.</p>
                            <button wire:click="create" class="mt-6 text-indigo-600 font-bold text-sm hover:underline">Register New Vessel</button>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($vessels->hasPages())
        <div class="px-8 py-6 border-t border-slate-100 bg-slate-50">
            {{ $vessels->links() }}
        </div>
        @endif
    </div>

    <!-- Modal: Register/Edit -->
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="$set('showModal', false)"></div>
        <div class="relative bg-white w-full max-w-2xl rounded-[2rem] shadow-2xl overflow-hidden">
            <div class="p-8">
                <div class="flex justify-between items-center mb-8">
                     <div>
                        <span class="text-[10px] font-black text-indigo-500 uppercase tracking-widest mb-1 block">Fleet Management</span>
                        <h3 class="text-2xl font-black text-slate-900 tracking-tight">{{ $isEditing ? 'Modify Asset Specs' : 'Register New Asset' }}</h3>
                     </div>
                    <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div class="col-span-2">
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">Vessel Name</label>
                        <input type="text" wire:model="vessel_form.name" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-900 focus:ring-indigo-500 focus:border-indigo-500 p-3">
                        @error('vessel_form.name') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                     <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">IMO Number</label>
                        <input type="text" wire:model="vessel_form.imo_number" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-900 focus:ring-indigo-500 focus:border-indigo-500 p-3">
                        @error('vessel_form.imo_number') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                     <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">Flag State</label>
                        <input type="text" wire:model="vessel_form.flag_country" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-900 focus:ring-indigo-500 focus:border-indigo-500 p-3">
                        @error('vessel_form.flag_country') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                     <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">Vessel Type</label>
                        <select wire:model="vessel_form.vessel_type" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-900 focus:ring-indigo-500 focus:border-indigo-500 p-3">
                            <option value="OSV">OSV</option>
                            <option value="Barge">Barge</option>
                            <option value="Tanker">Tanker</option>
                            <option value="Tug">Tug</option>
                            <option value="Other">Other</option>
                        </select>
                        @error('vessel_form.vessel_type') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                     <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">Owner / Principal</label>
                        @if(auth()->user()->role === 'agent')
                            <input type="text" value="{{ auth()->user()->organization->name ?? 'Current Organization' }}" class="w-full bg-slate-100 border-slate-200 rounded-xl font-bold text-slate-500 cursor-not-allowed" disabled>
                            <!-- Hidden input managed by backend logic, but we can verify display -->
                        @else
                            <select wire:model="vessel_form.organization_id" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-900 focus:ring-indigo-500 focus:border-indigo-500 p-3">
                                <option value="">Select Organization...</option>
                                @foreach($organizations as $org)
                                    <option value="{{ $org->id }}">{{ $org->name }}</option>
                                @endforeach
                            </select>
                            @error('vessel_form.organization_id') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                        @endif
                    </div>
                </div>

                <div class="bg-indigo-50 rounded-2xl p-6 mb-6">
                    <h4 class="text-xs font-black text-indigo-900 uppercase tracking-widest mb-4">Technical Dimensions</h4>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-indigo-400 mb-1">LOA (Meters)</label>
                            <input type="number" step="0.01" wire:model="vessel_form.loa_meters" class="w-full bg-white border-indigo-100 rounded-xl font-bold text-indigo-900 focus:ring-indigo-500 focus:border-indigo-500 p-3">
                            @error('vessel_form.loa_meters') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-indigo-400 mb-1">Max Draft (Meters)</label>
                            <input type="number" step="0.01" wire:model="vessel_form.draft_meters" class="w-full bg-white border-indigo-100 rounded-xl font-bold text-indigo-900 focus:ring-indigo-500 focus:border-indigo-500 p-3">
                             @error('vessel_form.draft_meters') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="flex gap-4">
                    <button wire:click="$set('showModal', false)" class="px-6 py-4 rounded-xl border border-slate-200 font-bold text-slate-500 hover:bg-slate-50">Cancel</button>
                    <button wire:click="save" class="flex-1 px-6 py-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-bold uppercase tracking-widest shadow-xl shadow-indigo-900/20 transition-all">
                        {{ $isEditing ? 'Save Changes' : 'Register Vessel' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

     <!-- Toast Notification -->
    @if (session()->has('success'))
        <div x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 5000)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 lg:translate-x-full"
             x-transition:enter-end="opacity-100 lg:translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 lg:translate-x-0"
             x-transition:leave-end="opacity-0 lg:translate-x-full"
             class="fixed top-8 right-8 z-[60] bg-slate-900 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-4 border border-slate-700">
            <div class="bg-indigo-500/20 p-2 rounded-lg">
                <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <div>
                <h4 class="font-bold text-sm tracking-wide uppercase text-indigo-400">System Notification</h4>
                <p class="text-sm text-slate-300">{{ session('success') }}</p>
            </div>
        </div>
    @endif
</div>
