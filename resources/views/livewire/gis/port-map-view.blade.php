<div class="p-8 bg-slate-50 min-h-screen" wire:poll.5s="updateMapPositions">
    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-slate-900">Port Map</h1>
        <p class="text-slate-500 mt-1">Interactive berth allocation and vessel tracking</p>
    </div>

    @if(request()->has('debug'))
    <div class="bg-gray-100 p-4 mb-4 rounded border font-mono text-xs overflow-auto max-h-48">
        <strong>DEBUG DATA:</strong> {{ json_encode($berthsData) }}
    </div>
    @endif
    
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
        <div class="col-span-3" x-data="{ tab: 'berths' }">
            <div class="bg-white rounded-xl shadow-sm p-4 h-[700px] flex flex-col border border-slate-200">
                <!-- Sidebar Tabs -->
                <div class="flex gap-2 p-1 bg-slate-100 rounded-lg mb-4">
                    <button @click="tab = 'berths'" 
                            :class="{ 'bg-white text-slate-900 shadow-sm': tab === 'berths', 'text-slate-500 hover:text-slate-700': tab !== 'berths' }"
                            class="flex-1 py-1.5 px-3 text-sm font-medium rounded-md transition-all">
                        Berths
                    </button>
                    <button @click="tab = 'queue'" 
                            :class="{ 'bg-white text-slate-900 shadow-sm': tab === 'queue', 'text-slate-500 hover:text-slate-700': tab !== 'queue' }"
                            class="flex-1 py-1.5 px-3 text-sm font-medium rounded-md transition-all flex items-center justify-center gap-2">
                        Queue
                        @if($waitingVessels->count() > 0)
                        <span class="bg-blue-100 text-blue-700 text-xs px-1.5 py-0.5 rounded-full">{{ $waitingVessels->count() }}</span>
                        @endif
                    </button>
                </div>
                
                <!-- Berths Tab -->
                <div x-show="tab === 'berths'" class="space-y-3 overflow-y-auto flex-1 pr-2 custom-scrollbar">
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

                <!-- Queue Tab -->
                <div x-show="tab === 'queue'" style="display: none;" class="space-y-3 overflow-y-auto flex-1 pr-2 custom-scrollbar">
                    <p class="text-xs text-slate-500 mb-2 px-1">Drag vessels to available berths on map.</p>
                    
                    @forelse($waitingVessels as $portCall)
                    <div draggable="true"
                         ondragstart="window.currentDragVessel = {id: '{{ $portCall->vessel->id }}', loa: {{ $portCall->vessel->loa_meters }}, draft: {{ $portCall->vessel->draft_meters }} }; event.dataTransfer.setData('vessel_data', JSON.stringify(window.currentDragVessel))"
                         ondragend="window.currentDragVessel = null"
                         class="p-4 rounded-xl border border-slate-200 bg-white hover:border-blue-300 hover:shadow-md transition-all cursor-move group">
                        
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <div class="bg-blue-100 p-1.5 rounded text-blue-600">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
                                </div>
                                <span class="font-bold text-slate-800">{{ $portCall->vessel->name }}</span>
                            </div>
                            <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-600 border border-slate-200">{{ $portCall->status }}</span>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-2 text-xs text-slate-500 mt-2 bg-slate-50 p-2 rounded-lg">
                            <div>
                                <span class="block text-slate-400 text-[10px]">LOA</span>
                                <span class="font-medium text-slate-700">{{ $portCall->vessel->loa_meters }}m</span>
                            </div>
                            <div>
                                <span class="block text-slate-400 text-[10px]">Draft</span>
                                <span class="font-medium text-slate-700">{{ $portCall->vessel->draft_meters }}m</span>
                            </div>
                            <div>
                                <span class="block text-slate-400 text-[10px]">Type</span>
                                <span class="font-medium text-slate-700">{{ ucfirst($portCall->vessel->vessel_type) }}</span>
                            </div>
                            <div>
                                <span class="block text-slate-400 text-[10px]">IMO</span>
                                <span class="font-medium text-slate-700">{{ $portCall->vessel->imo_number }}</span>
                            </div>
                        </div>
                    </div>
                    @empty
                        <div class="text-center py-12 flex flex-col items-center justify-center text-slate-400">
                            <svg class="w-12 h-12 mb-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <p>No waiting vessels.</p>
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
        // Listen for berth drop (drag and drop)
        window.addEventListener('berth-drop', event => {
             const detail = event.detail;
             let vesselId = detail.vesselId;
             
             // If we passed complex data, extract ID
             if (!vesselId && detail.vesselData) {
                 try {
                     const data = typeof detail.vesselData === 'string' ? JSON.parse(detail.vesselData) : detail.vesselData;
                     vesselId = data.id;
                 } catch(e) { console.error('Error parsing vessel data', e); }
             }

             if (vesselId && detail.berthId) {
                 console.log('Dispatching assignVessel', vesselId, detail.berthId);
                 @this.assignVessel(vesselId, detail.berthId);
             }
        });

        window.addEventListener('anchorage-drop', event => {
             const detail = event.detail;
             let vesselId = null;
             
             if (detail.vesselData) {
                 try {
                     const data = typeof detail.vesselData === 'string' ? JSON.parse(detail.vesselData) : detail.vesselData;
                     vesselId = data.id;
                 } catch(e) { console.error('Error parsing vessel data', e); }
             }

             if (vesselId && detail.zoneId) {
                 @this.assignToAnchorage(vesselId, detail.zoneId);
             }
        });

        window.addEventListener('update-vessel-positions', event => {
             const positions = Array.isArray(event.detail) ? event.detail[0] : event.detail;
             if (window.portMapInstance && positions) {
                 window.portMapInstance.updateVesselPositions(positions);
             }
        });

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
        
        // Listen for alerts from PHP (e.g. invalid drop)
        window.addEventListener('alert', event => {
            const detail = Array.isArray(event.detail) ? event.detail[0] : event.detail;
            // You can use a library like SweetAlert here or a custom toast
            if (typeof renderToast === 'function') { // If renderToast exists globally
                 renderToast({title: detail.type.toUpperCase(), message: detail.message, type: detail.type});
            } else {
                alert(`${detail.type.toUpperCase()}: ${detail.message}`);
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
                 const anchoragesData = @json($anchorageData ?? []);
                 console.log('Initializing Map with Berths:', berthsData);
                 console.log('Initializing Anchorages:', anchoragesData);
                 
                 const bounds = [];
                 berthsData.forEach(berth => {
                     if (berth.lat && berth.lng) {
                         portMap.addBerth(berth);
                         bounds.push([berth.lat, berth.lng]);
                     }
                 });

                 // Add Anchorages
                 anchoragesData.forEach(zone => {
                     if (zone.coordinates) {
                         portMap.addAnchorage(zone);
                         // Add to bounds so we see them
                         zone.coordinates.forEach(coord => bounds.push([coord.lat, coord.lng]));
                     }
                 });
                 
                 // Fit map
                 if (bounds.length > 0) {
                     portMap.fitBounds(bounds);
                     // portMap.map.fitBounds(bounds, { padding: [50, 50] }); // Add padding if needed
                 }
                 
                 window.portMapInstance = portMap;
            }
        }
    </script>
    <style>
        .berth-marker .berth-icon {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.3);
            transition: transform 0.2s;
        }

        .berth-marker:hover .berth-icon {
            transform: scale(1.1);
        }

        .anchorage-label {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid #94a3b8;
            border-radius: 4px;
            padding: 2px 6px;
            font-size: 11px;
            font-weight: bold;
            color: #475569;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
            text-align: center;
        }

        .drag-hover-valid {
            filter: drop-shadow(0 0 10px rgba(16, 185, 129, 0.8));
            transform: scale(1.5);
            z-index: 1000 !important;
        }
        .drag-hover-invalid {
            filter: drop-shadow(0 0 10px rgba(239, 68, 68, 0.8));
            transform: scale(1.5);
            z-index: 1000 !important;
            cursor: not-allowed;
        }
    </style>
</div>
