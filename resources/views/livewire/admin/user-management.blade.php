<div class="px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">User Management</h1>
            <p class="text-slate-500">Manage system users, roles, and access controls.</p>
        </div>
        <button wire:click="openModal" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-bold uppercase tracking-widest shadow-lg shadow-indigo-900/20 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add New User
        </button>
    </div>

    <!-- Search & Filters -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm mb-6 flex items-center gap-4">
        <div class="flex-1 relative">
            <input type="text" wire:model.live="search" placeholder="Search users by name or email..." class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-700 pl-10 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
            <svg class="w-5 h-5 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
    </div>

    <!-- Success Message -->
    @if (session()->has('success'))
        <div class="bg-emerald-50 text-emerald-600 px-4 py-3 rounded-xl border border-emerald-100 font-bold mb-6 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Users Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-xs border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">User Details</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4">Organization</th>
                        <th class="px-6 py-4">Joined</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm">
                                    {{ substr($user->name, 0, 2) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-900">{{ $user->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $roleColors = [
                                    'admin' => 'bg-purple-100 text-purple-700',
                                    'agent' => 'bg-emerald-100 text-emerald-700',
                                    'hse' => 'bg-red-100 text-red-700',
                                    'asset_manager' => 'bg-amber-100 text-amber-700',
                                ];
                                $color = $roleColors[$user->role] ?? 'bg-slate-100 text-slate-700';
                            @endphp
                            <span class="px-2.5 py-1 rounded-lg text-xs font-black uppercase tracking-wide {{ $color }}">
                                {{ str_replace('_', ' ', $user->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($user->organization)
                                <span class="font-bold text-slate-700">{{ $user->organization->name }}</span>
                            @else
                                <span class="text-slate-400 italic">-- Internal --</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm font-bold text-slate-500">
                            {{ $user->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button wire:click="editUser({{ $user->id }})" class="p-2 text-slate-400 hover:text-indigo-600 transition-colors" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                @if(auth()->id() !== $user->id)
                                <button wire:click="deleteUser({{ $user->id }})" wire:confirm="Are you sure you want to delete this user?" class="p-2 text-slate-400 hover:text-red-600 transition-colors" title="Delete">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                            No users found matching your search.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Create/Edit Modal -->
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" wire:click="$set('showModal', false)"></div>
        <div class="relative bg-white w-full max-w-lg rounded-3xl shadow-2xl border border-slate-200 overflow-hidden transform transition-all">
            <div class="p-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight">{{ $isEdit ? 'Edit User' : 'Create User' }}</h3>
                    <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="space-y-5">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">Full Name</label>
                        <input type="text" wire:model="name" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-700 focus:ring-indigo-500 focus:border-indigo-500 p-3" placeholder="John Doe">
                        @error('name') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">Email Address</label>
                        <input type="email" wire:model="email" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-700 focus:ring-indigo-500 focus:border-indigo-500 p-3" placeholder="john@example.com">
                        @error('email') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">Password</label>
                        <input type="password" wire:model="password" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-700 focus:ring-indigo-500 focus:border-indigo-500 p-3" placeholder="{{ $isEdit ? 'Leave blank to keep current' : 'Enter password' }}">
                        @error('password') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">Role</label>
                            <select wire:model.live="role" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-700 focus:ring-indigo-500 focus:border-indigo-500 p-3">
                                <option value="agent">Agent</option>
                                <option value="admin">Administrator</option>
                                <option value="hse">HSE Officer</option>
                                <option value="asset_manager">Asset Manager</option>
                            </select>
                            @error('role') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">Organization</label>
                            <select wire:model="organization_id" class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-700 focus:ring-indigo-500 focus:border-indigo-500 p-3" {{ $role === 'admin' || $role === 'hse' || $role === 'asset_manager' ? 'disabled' : '' }}>
                                <option value="">-- None (Internal) --</option>
                                @foreach($organizations as $org)
                                    <option value="{{ $org->id }}">{{ $org->name }}</option>
                                @endforeach
                            </select>
                            <p class="text-[10px] text-slate-400 mt-1">*Required for Agents</p>
                            @error('organization_id') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex gap-3">
                        <button type="button" wire:click="$set('showModal', false)" class="px-6 py-3 rounded-xl border border-slate-200 font-bold text-slate-500 hover:bg-slate-50 transition-colors">Cancel</button>
                        <button type="submit" class="flex-1 px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-bold uppercase tracking-widest shadow-lg shadow-indigo-900/20 transition-all">
                            {{ $isEdit ? 'Update User' : 'Create User' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
