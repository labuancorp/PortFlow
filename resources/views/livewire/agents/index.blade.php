<div class="p-8 font-sans min-h-screen relative" x-data="{ 
    toast: { show: false, message: '', type: 'success' },
    showToast(message, type = 'success') {
        this.toast = { show: true, message, type };
        setTimeout(() => { this.toast.show = false; }, 4000);
    }
}"
@notify.window="showToast($event.detail.message, $event.detail.type || 'success')">

    <!-- Header -->
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Shipping Agents</h1>
            <p class="text-slate-500 mt-1">Registry of authorized agents and handling companies</p>
        </div>
        <button wire:click="openCreateModal" class="px-5 py-3 bg-teal-600 hover:bg-teal-500 text-white rounded-xl font-bold text-sm shadow-lg shadow-teal-900/20 flex items-center gap-2 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Register Agent
        </button>
    </div>

    <!-- Content Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        
        <!-- Toolbar -->
        <div class="p-6 border-b border-slate-50 flex items-center gap-4 bg-slate-50/30">
            <div class="relative flex-1">
                <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" wire:model.live="search" placeholder="Search agents by name or code..." class="w-full pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-700 placeholder-slate-400 focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
            </div>
            <div class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-wider px-4">
                <span>{{ $agents->total() }} Records Found</span>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[10px] font-black uppercase tracking-widest text-slate-400">
                        <th class="px-8 py-4">Agent Name</th>
                        <th class="px-6 py-4">Code</th>
                        <th class="px-6 py-4">Billing Address</th>
                        <th class="px-6 py-4 text-center">Associations</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($agents as $agent)
                    <tr class="group hover:bg-slate-50/50 transition-colors">
                        <td class="px-8 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-bold shadow-sm">
                                    {{ substr($agent->name, 0, 1) }}
                                </div>
                                <span class="font-bold text-slate-700 text-sm">{{ $agent->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                             <span class="inline-block px-2 py-1 bg-slate-100 rounded text-[10px] font-mono font-bold text-slate-600 border border-slate-200">
                                {{ $agent->code }}
                             </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-500 max-w-xs truncate">
                            {{ $agent->billing_address }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2 flex-wrap">
                                <span class="bg-blue-50 text-blue-600 px-2 py-1 rounded text-[10px] font-bold" title="Registered Vessels">
                                    {{ $agent->vessels()->count() }} Vessels
                                </span>
                                <span class="bg-emerald-50 text-emerald-600 px-2 py-1 rounded text-[10px] font-bold" title="Total Port Calls">
                                    {{ $agent->portCalls()->count() }} Calls
                                </span>
                                <button wire:click="toggleWarehouseSubscription({{ $agent->id }})" 
                                        class="px-2 py-1 rounded text-[10px] font-bold transition-all {{ $agent->warehouse_subscribed ? 'bg-purple-100 text-purple-700 hover:bg-purple-200' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}" 
                                        title="{{ $agent->warehouse_subscribed ? 'Warehouse: Active' : 'Warehouse: Inactive' }}">
                                    @if($agent->warehouse_subscribed)
                                        <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a5 5 0 00-5 5v2a2 2 0 00-2 2v5a2 2 0 002 2h10a2 2 0 002-2v-5a2 2 0 00-2-2H7V7a3 3 0 015.905-.75 1 1 0 001.937-.5A5.002 5.002 0 0010 2z"></path></svg>
                                        Warehouse ✓
                                    @else
                                        <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
                                        Warehouse
                                    @endif
                                </button>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button wire:click="edit({{ $agent->id }})" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                <button wire:click="delete({{ $agent->id }})" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-16 text-center text-slate-400">
                            No agents found matching your search.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($agents->hasPages())
        <div class="px-6 py-4 border-t border-slate-50 bg-slate-50/30">
            {{ $agents->links() }}
        </div>
        @endif
    </div>

    <!-- Modal Form -->
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" wire:click="$set('showModal', false)"></div>
        <div class="relative bg-white w-full max-w-lg rounded-3xl shadow-2xl border border-slate-200 overflow-hidden transform transition-all">
            <div class="p-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight">{{ $isEdit ? 'Edit Profile' : 'New Agent Registration' }}</h3>
                    <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">Company Name</label>
                        <input type="text" wire:model="name" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-700 focus:ring-teal-500 focus:border-teal-500 p-3" placeholder="e.g. Baram Shipyard">
                        @error('name') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">Agent Code</label>
                        <input type="text" wire:model="code" class="w-full bg-slate-50 border-slate-200 rounded-xl font-mono font-bold text-slate-700 focus:ring-teal-500 focus:border-teal-500 p-3 uppercase" placeholder="e.g. BSA">
                        @error('code') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                         <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">Billing Address</label>
                        <textarea wire:model="billing_address" rows="3" class="w-full bg-slate-50 border-slate-200 rounded-xl font-medium text-slate-700 focus:ring-teal-500 focus:border-teal-500 p-3"></textarea>
                        @error('billing_address') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex gap-3">
                         <button type="button" wire:click="$set('showModal', false)" class="px-6 py-3 rounded-xl border border-slate-200 font-bold text-slate-500 hover:bg-slate-50 transition-colors">Cancel</button>
                         <button type="submit" class="flex-1 px-6 py-3 bg-teal-600 hover:bg-teal-500 text-white rounded-xl font-bold uppercase tracking-widest shadow-lg shadow-teal-900/20 transition-all">
                            {{ $isEdit ? 'Update Agent' : 'Register Agent' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Toast Component -->
    <template x-if="toast.show">
        <div class="fixed top-8 right-8 z-[60] bg-slate-900 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-4 border border-slate-700"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-8"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 translate-x-8">
            <div :class="toast.type === 'error' ? 'bg-red-500/20 text-red-400' : 'bg-teal-500/20 text-teal-400'" class="p-2 rounded-lg">
                <svg x-show="toast.type !== 'error'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <svg x-show="toast.type === 'error'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <div>
                <h4 class="font-bold text-sm tracking-wide uppercase" :class="toast.type === 'error' ? 'text-red-400' : 'text-teal-400'" x-text="toast.type === 'error' ? 'Error' : 'Success'"></h4>
                <p class="text-sm text-slate-300" x-text="toast.message"></p>
            </div>
        </div>
    </template>
</div>
