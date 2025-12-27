<div class="p-8 max-w-5xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('cargo.manifests.index') }}" class="p-2 rounded-lg hover:bg-slate-200 text-slate-500 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Create Manifest</h1>
            <p class="text-slate-500 mt-1">Register new inbound or outbound cargo.</p>
        </div>
    </div>

    <form wire:submit="save" class="space-y-8">
        
        <!-- Manifest Details -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                <h2 class="text-lg font-bold text-slate-800">Manifest Information</h2>
            </div>
            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                 <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Reference No</label>
                    <input type="text" wire:model="reference_no" class="w-full bg-slate-50 border-slate-200 rounded-lg font-mono font-bold text-slate-700 focus:ring-indigo-500 focus:border-indigo-500">
                    @error('reference_no') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                     <label class="block text-sm font-bold text-slate-700 mb-1">Movement Type</label>
                     <div class="grid grid-cols-2 gap-4">
                         <label class="cursor-pointer">
                             <input type="radio" wire:model.live="type" value="inbound" class="peer sr-only">
                             <div class="text-center py-2 rounded-lg border border-slate-200 font-bold text-slate-500 peer-checked:bg-teal-50 peer-checked:text-teal-700 peer-checked:border-teal-200 transition-all">Inbound (Import)</div>
                         </label>
                         <label class="cursor-pointer">
                             <input type="radio" wire:model.live="type" value="outbound" class="peer sr-only">
                             <div class="text-center py-2 rounded-lg border border-slate-200 font-bold text-slate-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 peer-checked:border-blue-200 transition-all">Outbound (Export)</div>
                         </label>
                     </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Vessel</label>
                    <select wire:model="vessel_id" class="w-full border-slate-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select Vessel...</option>
                        @foreach($vessels as $v)
                            <option value="{{ $v->id }}">{{ $v->name }}</option>
                        @endforeach
                    </select>
                    @error('vessel_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Shipping Agent</label>
                     <select wire:model="agent_id" class="w-full border-slate-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select Agent...</option>
                        @foreach($agents as $a)
                            <option value="{{ $a->id }}">{{ $a->name }}</option>
                        @endforeach
                    </select>
                    @error('agent_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">ETA / ETD</label>
                    <input type="datetime-local" wire:model="eta_etd" class="w-full border-slate-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    @error('eta_etd') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Cargo Items -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <h2 class="text-lg font-bold text-slate-800">Cargo Items</h2>
                <button type="button" wire:click="addItem" class="text-xs font-bold bg-slate-900 text-white px-3 py-1.5 rounded-lg hover:bg-slate-800 transition-colors">
                    + Add Item
                </button>
            </div>
            <div class="p-8">
                @error('items') <span class="text-red-500 text-sm font-bold block mb-4">{{ $message }}</span> @enderror

                <div class="space-y-4">
                    @foreach($items as $index => $item)
                    <div class="grid grid-cols-12 gap-4 items-start p-4 border border-slate-100 rounded-xl bg-slate-50/30 hover:bg-slate-50 transition-colors relative group">
                        <div class="col-span-12 md:col-span-3">
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-1">Tracking #</label>
                            <input type="text" wire:model="items.{{ $index }}.tracking_number" class="w-full p-2 text-sm border-slate-200 rounded-lg font-mono font-bold text-slate-700 focus:ring-indigo-500 focus:border-indigo-500 uppercase" placeholder="TRK-XXXX">
                            @error("items.{$index}.tracking_number") <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-span-12 md:col-span-4">
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-1">Description</label>
                            <input type="text" wire:model="items.{{ $index }}.description" class="w-full p-2 text-sm border-slate-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="e.g. Drilling Pipe Set">
                            @error("items.{$index}.description") <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-span-6 md:col-span-2">
                             <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-1">Weight (KG)</label>
                            <input type="number" wire:model="items.{{ $index }}.weight_kg" step="0.01" class="w-full p-2 text-sm border-slate-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                             @error("items.{$index}.weight_kg") <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-span-6 md:col-span-2">
                             <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-1">DG Class</label>
                             <select wire:model.live="items.{{ $index }}.dg_class" class="w-full p-2 text-sm border-slate-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                 <option value="">None (General)</option>
                                 <option value="1">Class 1 (Explosives)</option>
                                 <option value="2">Class 2 (Gases)</option>
                                 <option value="3">Class 3 (Flammable Liquids)</option>
                                 <option value="8">Class 8 (Corrosives)</option>
                             </select>
                             @if($item['dg_class'])
                                <div class="mt-1 flex items-center gap-1 text-[10px] uppercase font-black text-red-600 animate-pulse">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    Safety Permit Req.
                                </div>
                             @endif
                        </div>
                        <div class="col-span-12 md:col-span-1 flex items-center justify-end h-full pt-4">
                            @if(count($items) > 1)
                            <button type="button" wire:click="removeItem({{ $index }})" class="text-red-400 hover:text-red-600 p-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="mt-4">
                     <button type="button" wire:click="addItem" class="w-full py-3 border-2 border-dashed border-slate-200 rounded-xl text-slate-500 font-bold hover:border-indigo-300 hover:text-indigo-500 hover:bg-indigo-50 transition-all">
                        + Add Another Item
                    </button>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-4 pt-4">
            <a href="{{ route('cargo.manifests.index') }}" class="px-6 py-3 rounded-xl border border-slate-200 font-bold text-slate-500 hover:bg-slate-50">Cancel</a>
            <button type="submit" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-bold shadow-lg shadow-indigo-900/20 transition-all">
                Submit Manifest
            </button>
        </div>
    </form>
</div>
