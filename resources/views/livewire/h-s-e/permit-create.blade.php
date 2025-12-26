<div class="p-8 max-w-2xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-black text-slate-900 tracking-tight">Request Work Permit</h1>
        <p class="text-slate-500 mt-2">Submit a digital Permit-to-Work (PTW) for hazardous activities.</p>
    </div>

    <form wire:submit="save" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-8 space-y-6">
            
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Permit Type</label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach(['hot_work' => 'Hot Work (Welding)', 'working_at_height' => 'Working at Height', 'confined_space' => 'Confined Space', 'electrical' => 'Electrical', 'cold_work' => 'Cold Work'] as $val => $label)
                    <label class="cursor-pointer">
                        <input type="radio" wire:model.live="type" value="{{ $val }}" class="peer sr-only">
                        <div class="text-center py-3 px-2 rounded-lg border border-slate-200 text-xs font-bold text-slate-500 peer-checked:bg-slate-900 peer-checked:text-white peer-checked:border-slate-900 transition-all">
                            {{ $label }}
                        </div>
                    </label>
                    @endforeach
                </div>
                @error('type') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Specific Location</label>
                <input type="text" wire:model="location" placeholder="e.g. Berth 3, Crane 4, Workshop Area B" class="w-full rounded-lg border-slate-300 focus:ring-teal-500 focus:border-teal-500">
                <p class="text-xs text-slate-400 mt-1">This will be checked against other permits for clashes.</p>
                @error('location') 
                    <div class="mt-2 bg-red-50 text-red-600 text-sm font-bold p-3 rounded-lg border border-red-200 flex items-center gap-2">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Applicant / Contractor</label>
                <input type="text" wire:model="applicant_name" class="w-full rounded-lg border-slate-300 focus:ring-teal-500 focus:border-teal-500">
                @error('applicant_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                     <label class="block text-sm font-bold text-slate-700 mb-1">Valid From</label>
                     <input type="datetime-local" wire:model="valid_from" class="w-full rounded-lg border-slate-300 focus:ring-teal-500 focus:border-teal-500">
                     @error('valid_from') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                     <label class="block text-sm font-bold text-slate-700 mb-1">Valid Until</label>
                     <input type="datetime-local" wire:model="valid_to" class="w-full rounded-lg border-slate-300 focus:ring-teal-500 focus:border-teal-500">
                     @error('valid_to') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
            
            <div>
                 <label class="block text-sm font-bold text-slate-700 mb-1">Description of Work</label>
                 <textarea wire:model="description" rows="3" class="w-full rounded-lg border-slate-300 focus:ring-teal-500 focus:border-teal-500"></textarea>
            </div>
        </div>

        <div class="p-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-4">
            <a href="{{ route('hse.permits.dashboard') }}" class="px-6 py-2 rounded-lg font-bold text-slate-500 hover:text-slate-700">Cancel</a>
            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg font-bold hover:bg-indigo-500 transition-all shadow-lg shadow-indigo-900/20">
                Submit Request
            </button>
        </div>
    </form>
</div>
