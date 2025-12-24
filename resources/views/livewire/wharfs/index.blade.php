<div class="p-8 bg-slate-50 min-h-screen font-sans">
    <!-- Header -->
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Wharf Management</h1>
            <p class="text-slate-500 mt-1">Infrastructure specifications and status control.</p>
        </div>
        <button type="button" wire:click="create" class="px-5 py-3 bg-teal-600 hover:bg-teal-500 text-white rounded-xl font-bold text-sm shadow-lg shadow-teal-900/20 flex items-center gap-2 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Commission New Wharf
        </button>
    </div>

    <!-- Toolbar -->
    <div class="mb-6">
        <div class="relative max-w-md">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input wire:model.live="search" type="text" class="w-full pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-xl font-bold text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all shadow-sm" placeholder="Search infrastructure...">
        </div>
    </div>

    <!-- Registry Grid/Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Asset Code</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Operational Status</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Technical Limits</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Controls</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($wharfs as $wharf)
                    <tr class="group hover:bg-slate-50/80 transition-colors">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-teal-50 flex items-center justify-center text-teal-600 font-black text-lg shadow-sm">
                                    {{ substr($wharf->name, 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-900 group-hover:text-teal-600 transition-colors">{{ $wharf->name }}</h3>
                                    <p class="text-xs text-slate-500 font-mono mt-0.5">Code: <span class="text-slate-700 font-bold">{{ $wharf->code ?? 'N/A' }}</span></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wide
                                {{ $wharf->status === 'active' ? 'bg-green-100 text-green-700' : 
                                   ($wharf->status === 'maintenance' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">
                                {{ $wharf->status }}
                            </span>
                        </td>
                        <td class="px-8 py-5">
                             <div class="flex gap-8">
                                <div>
                                    <span class="block text-[9px] text-slate-400 font-bold uppercase tracking-wider">Max LOA</span>
                                    <span class="text-sm font-bold text-slate-800">{{ $wharf->max_loa }}m</span>
                                </div>
                                <div>
                                    <span class="block text-[9px] text-slate-400 font-bold uppercase tracking-wider">Max Draft</span>
                                    <span class="text-sm font-bold text-slate-800">{{ $wharf->max_draft }}m</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button wire:click="edit({{ $wharf->id }})" class="p-2 text-slate-400 hover:text-teal-600 hover:bg-teal-50 rounded-lg transition-all" title="Edit Specs">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                <button wire:click="delete({{ $wharf->id }})" 
                                        wire:confirm="Decommission this wharf? Doing so may affect historical records."
                                        class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Decommission">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-16 text-center">
                            <h3 class="text-lg font-bold text-slate-900">No Infrastructure Found</h3>
                            <button wire:click="create" class="mt-4 text-teal-600 font-bold text-sm hover:underline">Commission New Wharf</button>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($wharfs->hasPages())
        <div class="px-8 py-6 border-t border-slate-100 bg-slate-50">
            {{ $wharfs->links() }}
        </div>
        @endif
    </div>

    <!-- Modal: Register/Edit -->
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="$set('showModal', false)"></div>
        <div class="relative bg-white w-full max-w-lg rounded-[2rem] shadow-2xl overflow-hidden">
            <div class="p-8">
                <div class="flex justify-between items-center mb-8">
                     <div>
                        <span class="text-[10px] font-black text-teal-500 uppercase tracking-widest mb-1 block">Infrastructure Plans</span>
                        <h3 class="text-2xl font-black text-slate-900 tracking-tight">{{ $isEditing ? 'Modify Wharf Specs' : 'Commission New Wharf' }}</h3>
                     </div>
                    <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">Wharf Name</label>
                            <input type="text" wire:model="wharf_form.name" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-900 focus:ring-teal-500 focus:border-teal-500 p-3">
                            @error('wharf_form.name') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">Code</label>
                            <input type="text" wire:model="wharf_form.code" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-900 focus:ring-teal-500 focus:border-teal-500 p-3">
                            @error('wharf_form.code') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">Status</label>
                            <select wire:model="wharf_form.status" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-900 focus:ring-teal-500 focus:border-teal-500 p-3">
                                <option value="active">Active</option>
                                <option value="maintenance">Maintenance</option>
                                <option value="occupied">Occupied (Manual)</option>
                            </select>
                            @error('wharf_form.status') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="bg-teal-50 rounded-2xl p-6">
                        <h4 class="text-xs font-black text-teal-900 uppercase tracking-widest mb-4">Capacity Limits</h4>
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-teal-400 mb-1">Max LOA (Meters)</label>
                                <input type="number" step="0.01" wire:model="wharf_form.max_loa" class="w-full bg-white border-teal-100 rounded-xl font-bold text-teal-900 focus:ring-teal-500 focus:border-teal-500 p-3">
                                @error('wharf_form.max_loa') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-teal-400 mb-1">Max Draft (Meters)</label>
                                <input type="number" step="0.01" wire:model="wharf_form.max_draft" class="w-full bg-white border-teal-100 rounded-xl font-bold text-teal-900 focus:ring-teal-500 focus:border-teal-500 p-3">
                                @error('wharf_form.max_draft') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4 mt-8">
                    <button wire:click="$set('showModal', false)" class="px-6 py-4 rounded-xl border border-slate-200 font-bold text-slate-500 hover:bg-slate-50">Cancel</button>
                    <button wire:click="save" class="flex-1 px-6 py-4 bg-teal-600 hover:bg-teal-500 text-white rounded-xl font-bold uppercase tracking-widest shadow-xl shadow-teal-900/20 transition-all">
                        {{ $isEditing ? 'Update Wharf' : 'Commission Wharf' }}
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
             class="fixed top-8 right-8 z-[60] bg-slate-900 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-4 border border-slate-700">
            <div class="bg-teal-500/20 p-2 rounded-lg">
                <svg class="w-6 h-6 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <div>
                <h4 class="font-bold text-sm tracking-wide uppercase text-teal-400">System Notification</h4>
                <p class="text-sm text-slate-300">{{ session('success') }}</p>
            </div>
        </div>
    @endif
</div>
