<div class="p-8 bg-slate-50 min-h-screen">
    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-slate-900">Port Map</h1>
        <p class="text-slate-500 mt-1">Interactive berth allocation and vessel tracking</p>
    </div>
    
    <div class="grid grid-cols-12 gap-6">
        <!-- Map Container -->
        <div class="col-span-9">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden relative h-[700px] border border-slate-200">
                <div id="port-map" class="w-full h-full z-0" wire:ignore></div>
                
                <!-- Map Legend -->
                <div class="absolute bottom-6 right-6 bg-white/95 backdrop-blur-sm p-4 rounded-xl shadow-lg z-[500] border border-slate-200 w-48">
                    <h4 class="text-xs uppercase tracking-wider font-bold text-slate-500 mb-3">Berth Status</h4>
                    <div class="space-y-2">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 rounded-full bg-green-500 ring-2 ring-green-200"></div>
                            <span class="text-sm text-slate-700 font-medium">Available</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 rounded-full bg-red-500 ring-2 ring-red-200"></div>
                            <span class="text-sm text-slate-700 font-medium">Occupied</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 rounded-full bg-orange-500 ring-2 ring-orange-200"></div>
                            <span class="text-sm text-slate-700 font-medium">Maintenance</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-span-3">
            <div class="bg-white rounded-xl shadow-sm p-4 h-[700px] flex flex-col border border-slate-200">
                <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2 px-2">
                    <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Berths & Vessels
                </h3>
                
                <div class="space-y-3 overflow-y-auto flex-1 pr-2 custom-scrollbar">
                    @forelse($berthsData as $berth)
                    <div wire:click="selectBerth({{ $berth['id'] }})"
                         class="p-4 rounded-xl border transition-all cursor-pointer group relative overflow-hidden
                                {{ $selectedBerth && $selectedBerth->id === $berth['id'] ? 'border-blue-500 bg-blue-50 ring-1 ring-blue-200' : 'border-slate-100 hover:border-slate-300 hover:bg-slate-50 hover:shadow-sm' }}">
                        
                        <div class="absolute top-0 right-0 w-1 h-full
                            {{ $berth['status'] === 'available' ? 'bg-green-500' : '' }}
                            {{ $berth['status'] === 'occupied' ? 'bg-red-500' : '' }}
                            {{ $berth['status'] === 'maintenance' ? 'bg-orange-500' : '' }}
                        "></div>

                        <div class="flex items-center justify-between mb-2">
                            <span class="font-bold text-slate-800 group-hover:text-blue-700 transition-colors">{{ $berth['name'] }}</span>
                        </div>
                        
                        <div class="text-xs text-slate-500 space-y-1 mb-2">
                            <div class="flex justify-between">
                                <span>Max LOA:</span>
                                <span class="font-medium text-slate-700">{{ $berth['length'] }}m</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Max Draft:</span>
                                <span class="font-medium text-slate-700">{{ $berth['draft'] }}m</span>
                            </div>
                        </div>

                        @if($berth['vessel'])
                        <div class="pt-2 border-t border-slate-200/60 mt-2">
                            <div class="flex items-center gap-2">
                                <div class="bg-indigo-100 p-1.5 rounded-lg text-indigo-600">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                </div>
                                <span class="text-sm font-bold text-indigo-900 truncate">{{ $berth['vessel']['name'] }}</span>
                            </div>
                            <div class="text-[10px] text-slate-400 mt-0.5 ml-8">IMO: {{ $berth['vessel']['imo'] }}</div>
                        </div>
                        @else
                        <div class="pt-2 border-t border-slate-200/60 mt-2">
                             <div class="text-xs text-slate-400 italic py-1.5 px-2 text-center bg-slate-50 rounded-lg">Empty Berth</div>
                        </div>
                        @endif
                    </div>
                    @empty
                        <div class="text-center py-8 text-slate-500">
                            No berths found.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('livewire:navigated', () => {
             // Re-initialize map if needed, but wire:ignore should handle usually
             // If we want to fully support navigation away and back, we might need cleanup
             initMap();
        });
        
        document.addEventListener('DOMContentLoaded', () => {
             initMap();
        });

        // Listen for berth selection from Livewire
        // Listen for berth selection from Livewire
        document.addEventListener('berth-selected', event => {
            console.log('Event received:', event.detail);
            
            // Handle different Livewire event structures
            // Sometimes it's event.detail[0], sometimes event.detail directly
            let berth = Array.isArray(event.detail) ? event.detail[0] : event.detail;
            
            // If strictly wrapped in another array (some Livewire versions)
            if (Array.isArray(berth)) berth = berth[0];

            console.log('Parsed berth data:', berth);

            if (window.portMapInstance && window.portMapInstance.map && berth && berth.lat && berth.lng) {
                console.log('Flying to:', berth.lat, berth.lng);
                window.portMapInstance.map.setView([berth.lat, berth.lng], 16, {
                    animate: true,
                    duration: 1.5
                });
                
                // Try to find and open popup
                // Iterate layers to find matching marker
                window.portMapInstance.layers.berths.eachLayer(layer => {
                    const latLng = layer.getLatLng();
                    // Simple fuzzy match for float coordinates
                    if (Math.abs(latLng.lat - berth.lat) < 0.0001 && Math.abs(latLng.lng - berth.lng) < 0.0001) {
                        layer.openPopup();
                    }
                });
            } else {
                console.warn('Map instance or coordinates missing');
            }
        });

        function initMap() {
            const mapId = 'port-map';
            const mapEl = document.getElementById(mapId);

            if (mapEl) {
                 // Check if map is already initialized to avoid "Map container is already initialized" error
                 if (mapEl._leaflet_id) {
                     return; 
                 }

                 // Check if PortMap is loaded
                 if (typeof PortMap === 'undefined') {
                     console.warn('PortMap class not found. Waiting for app.js...');
                     setTimeout(initMap, 500); // Retry if JS not loaded yet
                     return;
                 }

                 const portMap = new PortMap(mapId);
                 portMap.init();
                 
                 // Load berths data from PHP
                 const berthsData = @json($berthsData);
                 
                 const bounds = [];
                 berthsData.forEach(berth => {
                     if (berth.lat && berth.lng) {
                         portMap.addBerth(berth);
                         bounds.push([berth.lat, berth.lng]);
                     }
                 });
                 
                 // Fit map
                 if (bounds.length > 0) {
                     portMap.fitBounds(bounds);
                 }
                 
                 window.portMapInstance = portMap;
            }
        }
    </script>
</div>
