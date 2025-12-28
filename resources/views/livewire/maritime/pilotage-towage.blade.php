<div class="px-6 py-8 bg-slate-50 min-h-screen">
    <!-- Soft Pastel Header -->
    <div class="mb-8 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl p-8 border border-indigo-200 shadow-sm">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-white rounded-xl shadow-sm">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Pilotage & Towage Services</h1>
                    <p class="text-indigo-700/70 text-sm mt-1 font-medium">Coordinate pilots and tugboats for vessel operations</p>
                </div>
            </div>
            <div class="flex gap-3">
                @if($activeTab === 'pilotage')
                <button wire:click="openPilotageModal" class="px-6 py-3 bg-slate-900 text-white rounded-xl font-bold hover:bg-slate-800 transition-all shadow-sm">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    New Pilotage Request
                </button>
                @elseif($activeTab === 'towage')
                <button wire:click="openTowageModal" class="px-6 py-3 bg-slate-900 text-white rounded-xl font-bold hover:bg-slate-800 transition-all shadow-sm">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    New Towage Request
                </button>
                @elseif($activeTab === 'pilots')
                <button wire:click="openPilotModal" class="px-6 py-3 bg-slate-900 text-white rounded-xl font-bold hover:bg-slate-800 transition-all shadow-sm">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Add Pilot
                </button>
                @elseif($activeTab === 'tugboats')
                <button wire:click="openTugboatModal" class="px-6 py-3 bg-slate-900 text-white rounded-xl font-bold hover:bg-slate-800 transition-all shadow-sm">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Add Tugboat
                </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if (session()->has('success'))
        <div class="bg-emerald-50 text-emerald-700 px-4 py-3 rounded-xl border border-emerald-200 font-bold mb-6 flex items-center gap-2 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Tab Navigation -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm mb-6">
        <div class="flex gap-3 overflow-x-auto">
            <button wire:click="setTab('pilotage')" class="px-4 py-2 rounded-lg font-bold text-sm transition-all whitespace-nowrap {{ $activeTab === 'pilotage' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                ⚓ Pilotage Requests
            </button>
            <button wire:click="setTab('towage')" class="px-4 py-2 rounded-lg font-bold text-sm transition-all whitespace-nowrap {{ $activeTab === 'towage' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                🚢 Towage Requests
            </button>
            <button wire:click="setTab('pilots')" class="px-4 py-2 rounded-lg font-bold text-sm transition-all whitespace-nowrap {{ $activeTab === 'pilots' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                👨‍✈️ Pilot Registry
            </button>
            <button wire:click="setTab('tugboats')" class="px-4 py-2 rounded-lg font-bold text-sm transition-all whitespace-nowrap {{ $activeTab === 'tugboats' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                🚤 Tugboat Fleet
            </button>
        </div>
    </div>

    <!-- Pilotage Requests Tab -->
    @if($activeTab === 'pilotage')
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-black text-slate-600 uppercase tracking-wider">Vessel</th>
                    <th class="px-4 py-3 text-left text-xs font-black text-slate-600 uppercase tracking-wider">Service Type</th>
                    <th class="px-4 py-3 text-left text-xs font-black text-slate-600 uppercase tracking-wider">Pilot</th>
                    <th class="px-4 py-3 text-left text-xs font-black text-slate-600 uppercase tracking-wider">Scheduled</th>
                    <th class="px-4 py-3 text-left text-xs font-black text-slate-600 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-black text-slate-600 uppercase tracking-wider">Fee</th>
                    <th class="px-4 py-3 text-right text-xs font-black text-slate-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($pilotageRequests as $request)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-4 py-3">
                        <div class="font-bold text-slate-900">{{ $request->portCall->vessel->name ?? 'N/A' }}</div>
                        <div class="text-xs text-slate-500">{{ $request->portCall->reference_no ?? 'N/A' }}</div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-lg text-xs font-bold
                            {{ $request->service_type === 'inbound' ? 'bg-emerald-100 text-emerald-700' : 
                               ($request->service_type === 'outbound' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700') }}">
                            {{ ucfirst($request->service_type) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        @if($request->pilot)
                        <div class="font-bold text-slate-900">{{ $request->pilot->name }}</div>
                        <div class="text-xs text-slate-500">{{ $request->pilot->license_number }}</div>
                        @else
                        <span class="text-slate-400 italic">Not assigned</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="text-sm text-slate-900">{{ $request->scheduled_time?->format('d M Y') }}</div>
                        <div class="text-xs text-slate-500">{{ $request->scheduled_time?->format('H:i') }}</div>
                    </td>
                    <td class="px-4 py-3">
                        @php
                            $statusColors = [
                                'requested' => 'bg-amber-100 text-amber-700',
                                'assigned' => 'bg-blue-100 text-blue-700',
                                'in_progress' => 'bg-emerald-100 text-emerald-700',
                                'completed' => 'bg-slate-100 text-slate-700',
                                'cancelled' => 'bg-rose-100 text-rose-700',
                            ];
                        @endphp
                        <span class="px-2 py-1 rounded-lg text-xs font-bold {{ $statusColors[$request->status] ?? 'bg-slate-100 text-slate-700' }}">
                            {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        @if($request->calculated_fee)
                        <div class="font-bold text-emerald-600">RM {{ number_format($request->calculated_fee, 2) }}</div>
                        @else
                        <span class="text-slate-400">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex justify-end gap-2">
                            @if($request->status === 'requested' || $request->status === 'assigned')
                            <button wire:click="openPilotageModal({{ $request->id }})" class="px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-bold transition-all">
                                Edit
                            </button>
                            @endif
                            @if($request->status === 'assigned')
                            <button wire:click="startPilotage({{ $request->id }})" class="px-3 py-1 bg-emerald-100 hover:bg-emerald-200 text-emerald-700 rounded-lg text-xs font-bold transition-all">
                                Start
                            </button>
                            @endif
                            @if($request->status === 'in_progress')
                            <button wire:click="completePilotage({{ $request->id }})" class="px-3 py-1 bg-slate-900 hover:bg-slate-800 text-white rounded-lg text-xs font-bold transition-all">
                                Complete
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-12 text-center">
                        <div class="text-slate-400 text-sm">No pilotage requests found. Create one to get started.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-slate-200">
            {{ $pilotageRequests->links() }}
        </div>
    </div>
    @endif

    <!-- Towage Requests Tab -->
    @if($activeTab === 'towage')
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-black text-slate-600 uppercase tracking-wider">Vessel</th>
                    <th class="px-4 py-3 text-left text-xs font-black text-slate-600 uppercase tracking-wider">Service Type</th>
                    <th class="px-4 py-3 text-left text-xs font-black text-slate-600 uppercase tracking-wider">Tugboat</th>
                    <th class="px-4 py-3 text-left text-xs font-black text-slate-600 uppercase tracking-wider">Tugs Required</th>
                    <th class="px-4 py-3 text-left text-xs font-black text-slate-600 uppercase tracking-wider">Scheduled</th>
                    <th class="px-4 py-3 text-left text-xs font-black text-slate-600 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-black text-slate-600 uppercase tracking-wider">Fee</th>
                    <th class="px-4 py-3 text-right text-xs font-black text-slate-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($towageRequests as $request)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-4 py-3">
                        <div class="font-bold text-slate-900">{{ $request->portCall->vessel->name ?? 'N/A' }}</div>
                        <div class="text-xs text-slate-500">{{ $request->portCall->reference_no ?? 'N/A' }}</div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-lg text-xs font-bold
                            {{ $request->service_type === 'berthing' ? 'bg-emerald-100 text-emerald-700' : 
                               ($request->service_type === 'unberthing' ? 'bg-blue-100 text-blue-700' : 
                               ($request->service_type === 'shifting' ? 'bg-purple-100 text-purple-700' : 'bg-amber-100 text-amber-700')) }}">
                            {{ ucfirst($request->service_type) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        @if($request->tugboat)
                        <div class="font-bold text-slate-900">{{ $request->tugboat->name }}</div>
                        <div class="text-xs text-slate-500">{{ $request->tugboat->bollard_pull_tons }}T Pull</div>
                        @else
                        <span class="text-slate-400 italic">Not assigned</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-bold">
                            {{ $request->tugboats_required }} Tug{{ $request->tugboats_required > 1 ? 's' : '' }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="text-sm text-slate-900">{{ $request->scheduled_time?->format('d M Y') }}</div>
                        <div class="text-xs text-slate-500">{{ $request->scheduled_time?->format('H:i') }}</div>
                    </td>
                    <td class="px-4 py-3">
                        @php
                            $statusColors = [
                                'requested' => 'bg-amber-100 text-amber-700',
                                'assigned' => 'bg-blue-100 text-blue-700',
                                'in_progress' => 'bg-emerald-100 text-emerald-700',
                                'completed' => 'bg-slate-100 text-slate-700',
                                'cancelled' => 'bg-rose-100 text-rose-700',
                            ];
                        @endphp
                        <span class="px-2 py-1 rounded-lg text-xs font-bold {{ $statusColors[$request->status] ?? 'bg-slate-100 text-slate-700' }}">
                            {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        @if($request->calculated_fee)
                        <div class="font-bold text-emerald-600">RM {{ number_format($request->calculated_fee, 2) }}</div>
                        @else
                        <span class="text-slate-400">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex justify-end gap-2">
                            @if($request->status === 'requested' || $request->status === 'assigned')
                            <button wire:click="openTowageModal({{ $request->id }})" class="px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-bold transition-all">
                                Edit
                            </button>
                            @endif
                            @if($request->status === 'assigned')
                            <button wire:click="startTowage({{ $request->id }})" class="px-3 py-1 bg-emerald-100 hover:bg-emerald-200 text-emerald-700 rounded-lg text-xs font-bold transition-all">
                                Start
                            </button>
                            @endif
                            @if($request->status === 'in_progress')
                            <button wire:click="completeTowage({{ $request->id }})" class="px-3 py-1 bg-slate-900 hover:bg-slate-800 text-white rounded-lg text-xs font-bold transition-all">
                                Complete
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-12 text-center">
                        <div class="text-slate-400 text-sm">No towage requests found. Create one to get started.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-slate-200">
            {{ $towageRequests->links() }}
        </div>
    </div>
    @endif

    <!-- Pilots Registry Tab -->
    @if($activeTab === 'pilots')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($pilots as $pilot)
        <div class="bg-gradient-to-br {{ $pilot->isAvailable() ? 'from-emerald-50 to-teal-50 border-emerald-200' : 'from-slate-50 to-slate-100 border-slate-200' }} rounded-2xl border p-6 shadow-sm">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center text-2xl shadow-sm">
                    👨‍✈️
                </div>
                <span class="px-2 py-1 rounded-lg text-xs font-bold
                    {{ $pilot->status === 'available' ? 'bg-emerald-100 text-emerald-700' : 
                       ($pilot->status === 'on_duty' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-700') }}">
                    {{ ucfirst(str_replace('_', ' ', $pilot->status)) }}
                </span>
            </div>
            <h3 class="text-lg font-black text-slate-900 mb-1">{{ $pilot->name }}</h3>
            <p class="text-sm text-slate-500 mb-3">License: {{ $pilot->license_number }}</p>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-600">Rate:</span>
                    <span class="font-bold text-slate-900">RM {{ number_format($pilot->rate_per_hour, 2) }}/hr</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">License Expiry:</span>
                    <span class="font-bold {{ $pilot->isLicenseValid() ? 'text-emerald-600' : 'text-rose-600' }}">
                        {{ $pilot->license_expiry->format('d M Y') }}
                    </span>
                </div>
            </div>
            <div class="mt-4 flex gap-2">
                <button wire:click="openPilotModal({{ $pilot->id }})" class="flex-1 px-3 py-2 bg-white text-slate-700 font-bold rounded-lg hover:bg-slate-50 transition-all text-xs border border-slate-200">
                    Edit
                </button>
                <button wire:click="deletePilot({{ $pilot->id }})" wire:confirm="Are you sure you want to delete this pilot?" class="px-3 py-2 bg-rose-100 text-rose-700 font-bold rounded-lg hover:bg-rose-200 transition-all text-xs border border-rose-200">
                    Delete
                </button>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12 text-slate-400">
            No pilots registered yet.
        </div>
        @endforelse
    </div>
    <div class="mt-6">
        {{ $pilots->links() }}
    </div>
    @endif

    <!-- Tugboats Fleet Tab -->
    @if($activeTab === 'tugboats')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($tugboats as $tugboat)
        <div class="bg-gradient-to-br {{ $tugboat->isAvailable() ? 'from-cyan-50 to-blue-50 border-cyan-200' : 'from-slate-50 to-slate-100 border-slate-200' }} rounded-2xl border p-6 shadow-sm">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center text-2xl shadow-sm">
                    🚤
                </div>
                <span class="px-2 py-1 rounded-lg text-xs font-bold
                    {{ $tugboat->status === 'available' ? 'bg-emerald-100 text-emerald-700' : 
                       ($tugboat->status === 'in_service' ? 'bg-blue-100 text-blue-700' : 
                       ($tugboat->status === 'maintenance' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700')) }}">
                    {{ ucfirst(str_replace('_', ' ', $tugboat->status)) }}
                </span>
            </div>
            <h3 class="text-lg font-black text-slate-900 mb-1">{{ $tugboat->name }}</h3>
            <p class="text-sm text-slate-500 mb-3">{{ $tugboat->registration_number }}</p>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-600">Bollard Pull:</span>
                    <span class="font-bold text-slate-900">{{ $tugboat->bollard_pull_tons }} Tons</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Rate:</span>
                    <span class="font-bold text-slate-900">RM {{ number_format($tugboat->rate_per_hour, 2) }}/hr</span>
                </div>
                @if($tugboat->certificate_expiry)
                <div class="flex justify-between">
                    <span class="text-slate-600">Certificate:</span>
                    <span class="font-bold {{ $tugboat->isCertificateValid() ? 'text-emerald-600' : 'text-rose-600' }}">
                        {{ $tugboat->certificate_expiry->format('d M Y') }}
                    </span>
                </div>
                @endif
            </div>
            <div class="mt-4 flex gap-2">
                <button wire:click="openTugboatModal({{ $tugboat->id }})" class="flex-1 px-3 py-2 bg-white text-slate-700 font-bold rounded-lg hover:bg-slate-50 transition-all text-xs border border-slate-200">
                    Edit
                </button>
                <button wire:click="deleteTugboat({{ $tugboat->id }})" wire:confirm="Are you sure you want to delete this tugboat?" class="px-3 py-2 bg-rose-100 text-rose-700 font-bold rounded-lg hover:bg-rose-200 transition-all text-xs border border-rose-200">
                    Delete
                </button>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12 text-slate-400">
            No tugboats registered yet.
        </div>
        @endforelse
    </div>
    <div class="mt-6">
        {{ $tugboats->links() }}
    </div>
    @endif

    <!-- Pilot Modal -->
    @if($showModal && $modalType === 'pilot')
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" wire:click="closeModal"></div>
        <div class="relative bg-white w-full max-w-2xl rounded-2xl shadow-2xl border border-slate-200 overflow-hidden transform transition-all">
            <div class="p-8">
                <h3 class="text-2xl font-bold text-slate-900 mb-6">{{ $pilotId ? 'Edit' : 'Add' }} Pilot</h3>
                <form wire:submit.prevent="savePilot">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Name *</label>
                            <input type="text" wire:model="pilot_name" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                            @error('pilot_name') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">License Number *</label>
                            <input type="text" wire:model="pilot_license_number" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                            @error('pilot_license_number') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Phone</label>
                            <input type="text" wire:model="pilot_phone" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                            @error('pilot_phone') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Email</label>
                            <input type="email" wire:model="pilot_email" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                            @error('pilot_email') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">License Expiry *</label>
                            <input type="date" wire:model="pilot_license_expiry" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                            @error('pilot_license_expiry') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Rate per Hour (RM) *</label>
                            <input type="number" wire:model="pilot_rate_per_hour" min="0" step="0.01" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                            @error('pilot_rate_per_hour') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="mt-6 flex gap-3">
                        <button type="button" wire:click="closeModal" class="flex-1 px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold transition-all">
                            Cancel
                        </button>
                        <button type="submit" class="flex-1 px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-bold transition-all">
                            {{ $pilotId ? 'Update' : 'Add' }} Pilot
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Tugboat Modal -->
    @if($showModal && $modalType === 'tugboat')
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" wire:click="closeModal"></div>
        <div class="relative bg-white w-full max-w-2xl rounded-2xl shadow-2xl border border-slate-200 overflow-hidden transform transition-all">
            <div class="p-8">
                <h3 class="text-2xl font-bold text-slate-900 mb-6">{{ $tugboatId ? 'Edit' : 'Add' }} Tugboat</h3>
                <form wire:submit.prevent="saveTugboat">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Name *</label>
                            <input type="text" wire:model="tugboat_name" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                            @error('tugboat_name') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Registration Number *</label>
                            <input type="text" wire:model="tugboat_registration_number" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                            @error('tugboat_registration_number') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Bollard Pull (Tons) *</label>
                            <input type="number" wire:model="tugboat_bollard_pull_tons" min="0" step="0.1" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                            @error('tugboat_bollard_pull_tons') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Rate per Hour (RM) *</label>
                            <input type="number" wire:model="tugboat_rate_per_hour" min="0" step="0.01" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                            @error('tugboat_rate_per_hour') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Certificate Expiry</label>
                            <input type="date" wire:model="tugboat_certificate_expiry" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                            @error('tugboat_certificate_expiry') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Captain Name</label>
                            <input type="text" wire:model="tugboat_captain_name" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                            @error('tugboat_captain_name') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Captain Phone</label>
                            <input type="text" wire:model="tugboat_captain_phone" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                            @error('tugboat_captain_phone') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="mt-6 flex gap-3">
                        <button type="button" wire:click="closeModal" class="flex-1 px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold transition-all">
                            Cancel
                        </button>
                        <button type="submit" class="flex-1 px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-bold transition-all">
                            {{ $tugboatId ? 'Update' : 'Add' }} Tugboat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Pilotage Request Modal (keeping existing) -->
    @if($showModal && $modalType === 'pilotage_request')
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" wire:click="closeModal"></div>
        <div class="relative bg-white w-full max-w-2xl rounded-2xl shadow-2xl border border-slate-200 overflow-hidden transform transition-all max-h-[90vh] overflow-y-auto">
            <div class="p-8">
                <h3 class="text-2xl font-bold text-slate-900 mb-6">{{ $pilotageId ? 'Edit' : 'New' }} Pilotage Request</h3>
                <form wire:submit.prevent="savePilotageRequest">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Port Call *</label>
                            <select wire:model="pilotage_port_call_id" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                                <option value="">Select port call...</option>
                                @foreach($portCalls as $portCall)
                                <option value="{{ $portCall->id }}">{{ $portCall->vessel->name ?? 'Unknown' }} - {{ $portCall->reference_no }}</option>
                                @endforeach
                            </select>
                            @error('pilotage_port_call_id') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Service Type *</label>
                                <select wire:model="pilotage_service_type" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                                    <option value="inbound">Inbound</option>
                                    <option value="outbound">Outbound</option>
                                    <option value="shifting">Shifting</option>
                                </select>
                                @error('pilotage_service_type') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Assign Pilot</label>
                                <select wire:model="pilotage_pilot_id" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                                    <option value="">Assign later...</option>
                                    @foreach($availablePilots as $pilot)
                                    <option value="{{ $pilot->id }}">{{ $pilot->name }} ({{ $pilot->license_number }})</option>
                                    @endforeach
                                </select>
                                @error('pilotage_pilot_id') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Scheduled Time *</label>
                                <input type="datetime-local" wire:model="pilotage_scheduled_time" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                                @error('pilotage_scheduled_time') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Boarding Point</label>
                                <input type="text" wire:model="pilotage_boarding_point" placeholder="e.g., Pilot Station Alpha" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                                @error('pilotage_boarding_point') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Weather Condition</label>
                            <select wire:model="pilotage_weather_condition" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                                <option value="">Select...</option>
                                <option value="calm">Calm</option>
                                <option value="moderate">Moderate</option>
                                <option value="rough">Rough</option>
                                <option value="stormy">Stormy</option>
                                <option value="poor_visibility">Poor Visibility</option>
                            </select>
                            @error('pilotage_weather_condition') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Notes</label>
                            <textarea wire:model="pilotage_notes" rows="3" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent"></textarea>
                            @error('pilotage_notes') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex gap-3">
                        <button type="button" wire:click="closeModal" class="flex-1 px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold transition-all">
                            Cancel
                        </button>
                        <button type="submit" class="flex-1 px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-bold transition-all">
                            {{ $pilotageId ? 'Update' : 'Create' }} Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Towage Request Modal (keeping existing) -->
    @if($showModal && $modalType === 'towage_request')
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" wire:click="closeModal"></div>
        <div class="relative bg-white w-full max-w-2xl rounded-2xl shadow-2xl border border-slate-200 overflow-hidden transform transition-all max-h-[90vh] overflow-y-auto">
            <div class="p-8">
                <h3 class="text-2xl font-bold text-slate-900 mb-6">{{ $towageId ? 'Edit' : 'New' }} Towage Request</h3>
                <form wire:submit.prevent="saveTowageRequest">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Port Call *</label>
                            <select wire:model="towage_port_call_id" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                                <option value="">Select port call...</option>
                                @foreach($portCalls as $portCall)
                                <option value="{{ $portCall->id }}">{{ $portCall->vessel->name ?? 'Unknown' }} - {{ $portCall->reference_no }}</option>
                                @endforeach
                            </select>
                            @error('towage_port_call_id') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Service Type *</label>
                                <select wire:model="towage_service_type" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                                    <option value="berthing">Berthing</option>
                                    <option value="unberthing">Unberthing</option>
                                    <option value="shifting">Shifting</option>
                                    <option value="escort">Escort</option>
                                </select>
                                @error('towage_service_type') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Tugboats Required *</label>
                                <input type="number" wire:model="towage_tugboats_required" min="1" max="4" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                                @error('towage_tugboats_required') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Assign Tugboat</label>
                            <select wire:model="towage_tugboat_id" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                                <option value="">Assign later...</option>
                                @foreach($availableTugboats as $tugboat)
                                <option value="{{ $tugboat->id }}">{{ $tugboat->name }} ({{ $tugboat->bollard_pull_tons }}T Pull)</option>
                                @endforeach
                            </select>
                            @error('towage_tugboat_id') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">From Location</label>
                                <input type="text" wire:model="towage_from_location" placeholder="e.g., Anchorage A" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                                @error('towage_from_location') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">To Location</label>
                                <input type="text" wire:model="towage_to_location" placeholder="e.g., Berth 1" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                                @error('towage_to_location') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Scheduled Time *</label>
                                <input type="datetime-local" wire:model="towage_scheduled_time" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                                @error('towage_scheduled_time') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Weather Condition</label>
                                <select wire:model="towage_weather_condition" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent">
                                    <option value="">Select...</option>
                                    <option value="calm">Calm</option>
                                    <option value="moderate">Moderate</option>
                                    <option value="rough">Rough</option>
                                    <option value="stormy">Stormy</option>
                                    <option value="poor_visibility">Poor Visibility</option>
                                </select>
                                @error('towage_weather_condition') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Notes</label>
                            <textarea wire:model="towage_notes" rows="3" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-transparent"></textarea>
                            @error('towage_notes') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex gap-3">
                        <button type="button" wire:click="closeModal" class="flex-1 px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold transition-all">
                            Cancel
                        </button>
                        <button type="submit" class="flex-1 px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-bold transition-all">
                            {{ $towageId ? 'Update' : 'Create' }} Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
