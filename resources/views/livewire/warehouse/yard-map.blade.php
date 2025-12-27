<div class="h-[calc(100vh-64px)] flex flex-col md:flex-row font-sans">
    
    <!-- Sidebar -->
    <div class="w-full md:w-80 bg-white border-r border-slate-200 flex flex-col z-20 shadow-xl overflow-hidden md:h-full">
        <div class="p-6 border-b border-slate-100">
            <h1 class="text-xl font-black text-slate-900 tracking-tight">Port Yard Map</h1>
            <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mt-1">Real-time Cargo Tracking</p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 divide-x divide-slate-100 border-b border-slate-100 bg-slate-50">
            <div class="p-4 text-center">
                <div class="text-2xl font-black text-indigo-600">{{ $warehouses->sum(fn($w) => $w->zones->sum('current_utilization_m3')) }}</div>
                <div class="text-[10px] uppercase font-bold text-slate-400">Total Volume (m³)</div>
            </div>
             <div class="p-4 text-center">
                <div class="text-2xl font-black text-amber-500">{{ $warehouses->sum(fn($w) => $w->zones->sum(fn($z) => $z->items->where('dg_class', '!=', null)->count())) }}</div>
                <div class="text-[10px] uppercase font-bold text-slate-400">DG Containers</div>
            </div>
        </div>

        <!-- Zone List -->
        <div class="flex-1 overflow-y-auto p-4 space-y-4">
            @foreach($warehouses as $warehouse)
            <div class="space-y-2">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider bg-slate-100 px-2 py-1 rounded">{{ $warehouse->name }}</h3>
                @foreach($warehouse->zones as $zone)
                <div class="group p-3 rounded-lg border border-slate-200 hover:border-indigo-400 hover:shadow-md transition-all cursor-pointer bg-white"
                     onclick="focusZone({{ $zone->id }})"> 
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-bold text-sm text-slate-700">{{ $zone->name }}</span>
                         @if($zone->is_dg_allowed)
                            <span class="bg-red-100 text-red-700 text-[10px] font-black px-1.5 py-0.5 rounded">DG ZONE</span>
                         @endif
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2 mb-1 overflow-hidden">
                        <div class="bg-indigo-500 h-2 rounded-full" style="width: {{ min(100, ($zone->current_utilization_m3 / $zone->capacity_limit_m3) * 100) }}%"></div>
                    </div>
                    <div class="flex justify-between text-[10px] text-slate-400 font-medium">
                        <span>{{ $zone->items->count() }} Items</span>
                        <span>{{ $zone->current_utilization_m3 }} / {{ $zone->capacity_limit_m3 }} m³</span>
                    </div>
                </div>
                @endforeach
            </div>
            @endforeach
        </div>

        <!-- Aging Alerts -->
        <div class="bg-red-50 p-4 border-t border-red-100">
            <h4 class="text-xs font-bold text-red-700 uppercase tracking-widest mb-3 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                Aging Cargo (>90 Days)
            </h4>
            <div class="space-y-2 max-h-40 overflow-y-auto">
                @foreach($agingItems as $item)
                <div class="flex justify-between items-center text-xs bg-white p-2 rounded border border-red-100 shadow-sm">
                    <span class="font-bold text-slate-700">{{ $item->tracking_number }}</span>
                    <span class="text-red-500 font-mono">{{ $item->created_at->diffInDays() }} days</span>
                </div>
                @endforeach
                 @if($agingItems->isEmpty())
                    <p class="text-xs text-slate-400 italic">No aging cargo.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Map Container -->
    <div id="yard-map" class="flex-1 bg-slate-200 h-[50vh] md:h-full z-10 w-full" wire:ignore></div>
</div>

<script>
    // Load Leaflet CSS first
    if (!document.getElementById('leaflet-css')) {
        const link = document.createElement('link');
        link.id = 'leaflet-css';
        link.rel = 'stylesheet';
        link.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
        link.integrity = 'sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=';
        link.crossOrigin = '';
        document.head.appendChild(link);
    }

    // Load Leaflet JS
    function loadLeaflet(callback) {
        if (window.L) {
            callback();
            return;
        }
        
        const script = document.createElement('script');
        script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
        script.integrity = 'sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=';
        script.crossOrigin = '';
        script.onload = callback;
        script.onerror = () => console.error('Failed to load Leaflet');
        document.head.appendChild(script);
    }

    // Initialize map after everything is ready
    function initYardMap() {
        const mapElement = document.getElementById('yard-map');
        if (!mapElement) {
            console.error('Map container not found');
            return;
        }

        // Clear any existing map
        mapElement.innerHTML = '';
        
        try {
            const map = L.map('yard-map').setView([5.2630, 115.2430], 17);

            L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
                subdomains: 'abcd',
                maxZoom: 20
            }).addTo(map);

            const zones = {
                'A1': { coords: [[5.2635, 115.2425], [5.2635, 115.2435], [5.2625, 115.2435], [5.2625, 115.2425]], color: '#6366f1' },
                'A2': { coords: [[5.2635, 115.2436], [5.2635, 115.2446], [5.2625, 115.2446], [5.2625, 115.2436]], color: '#6366f1' },
                'A3': { coords: [[5.2620, 115.2425], [5.2620, 115.2446], [5.2615, 115.2446], [5.2615, 115.2425]], color: '#ef4444' },
                'B1': { coords: [[5.2640, 115.2425], [5.2645, 115.2425], [5.2645, 115.2440], [5.2640, 115.2440]], color: '#10b981' },
                'B2': { coords: [[5.2640, 115.2441], [5.2645, 115.2441], [5.2645, 115.2450], [5.2640, 115.2450]], color: '#ef4444' },
            };

            @foreach($warehouses as $w)
                @foreach($w->zones as $z)
                    if(zones['{{ $z->code }}']) {
                        const poly = L.polygon(zones['{{ $z->code }}'].coords, {
                            color: zones['{{ $z->code }}'].color,
                            fillColor: zones['{{ $z->code }}'].color,
                            fillOpacity: 0.2,
                            weight: 2
                        }).addTo(map);

                        poly.bindTooltip(`<b>{{ $z->name }}</b><br>Util: {{ $z->current_utilization_m3 }}m³`, { 
                            permanent: true, 
                            direction: "center", 
                            className: "bg-white/80 border-0 text-xs font-bold shadow-sm" 
                        });
                        
                        @foreach($z->items as $item)
                        {
                            const itemBounds = poly.getBounds();
                            const itemCenter = itemBounds.getCenter();
                            const latJitter = (Math.random() - 0.5) * 0.0008;
                            const lngJitter = (Math.random() - 0.5) * 0.0008;
                            
                            const markerColor = '{{ $item->dg_class ? "red" : "blue" }}';
                            
                            L.circleMarker([itemCenter.lat + latJitter, itemCenter.lng + lngJitter], {
                                radius: 4,
                                fillColor: markerColor,
                                color: "#fff",
                                weight: 1,
                                opacity: 1,
                                fillOpacity: 0.8
                            }).addTo(map).bindPopup(`
                                <div class="text-xs">
                                    <strong class="block mb-1">{{ $item->tracking_number }}</strong>
                                    <span class="text-slate-500">{{ $item->description }}</span>
                                    <div class="mt-1">
                                        ${ '{{ $item->dg_class }}' ? '<span class="bg-red-100 text-red-700 px-1 rounded font-bold">Class {{ $item->dg_class }}</span>' : '<span class="bg-slate-100 text-slate-600 px-1 rounded">General</span>' }
                                    </div>
                                </div>
                            `);
                        }
                        @endforeach
                    }
                @endforeach
            @endforeach

            window.focusZone = function(zoneId) {
                // map.flyTo([5.2630, 115.2430], 18);
            };

            // Force map to resize after initialization
            setTimeout(() => {
                map.invalidateSize();
            }, 100);

        } catch (error) {
            console.error('Error initializing map:', error);
        }
    }

    // Wait for both DOM and Livewire to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            loadLeaflet(initYardMap);
        });
    } else {
        loadLeaflet(initYardMap);
    }
</script>
