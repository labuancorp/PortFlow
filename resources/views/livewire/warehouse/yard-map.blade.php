<div class="h-[calc(100vh-64px)] flex flex-col md:flex-row font-sans">
    
@if(!$subscribed && auth()->user()->role === 'agent')
    <!-- Subscription Required Screen -->
    <div class="flex-1 flex items-center justify-center bg-gradient-to-br from-slate-50 to-slate-100 p-8">
        <div class="max-w-md w-full bg-white rounded-3xl shadow-2xl p-8 border border-slate-200">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <h2 class="text-2xl font-black text-slate-900">Warehouse Service</h2>
                <p class="text-slate-500 mt-2">Subscribe to access yard mapping & cargo tracking</p>
            </div>
            
            <div class="space-y-3 mb-6">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-teal-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span class="text-sm text-slate-700">Real-time yard space availability</span>
                </div>
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-teal-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span class="text-sm text-slate-700">Track your cargo location</span>
                </div>
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-teal-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span class="text-sm text-slate-700">See occupied zones (owner names only)</span>
                </div>
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-teal-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span class="text-sm text-slate-700">Aging cargo alerts</span>
                </div>
            </div>

            <a href="{{ route('settings') }}" class="block w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-center transition-colors">
                Contact Admin to Subscribe
            </a>
            <p class="text-xs text-slate-400 text-center mt-4">Subscription managed by port administration</p>
        </div>
    </div>
@else
    <!-- Sidebar -->
    <div class="w-full md:w-80 bg-white border-r border-slate-200 flex flex-col z-20 shadow-xl overflow-hidden md:h-full" x-data="{ sidebarTab: 'zones' }">
        <div class="p-6 border-b border-slate-100">
            <h1 class="text-xl font-black text-slate-900 tracking-tight">Port Yard Map</h1>
            <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mt-1">Real-time Cargo Tracking</p>
        </div>
 
        @if(auth()->user()->role === 'agent')
        <!-- Tabs for Agents -->
        <div class="flex border-b border-slate-100 bg-slate-50">
            <button @click="sidebarTab = 'zones'" :class="sidebarTab === 'zones' ? 'border-b-2 border-indigo-600 text-indigo-600 bg-white' : 'text-slate-500 hover:bg-slate-100'" class="flex-1 py-3 text-[10px] font-black uppercase tracking-widest transition-all">Zones</button>
            <button @click="sidebarTab = 'requests'" :class="sidebarTab === 'requests' ? 'border-b-2 border-indigo-600 text-indigo-600 bg-white' : 'text-slate-500 hover:bg-slate-100'" class="flex-1 py-3 text-[10px] font-black uppercase tracking-widest transition-all">My Requests</button>
            <button @click="sidebarTab = 'inventory'" :class="sidebarTab === 'inventory' ? 'border-b-2 border-indigo-600 text-indigo-600 bg-white' : 'text-slate-500 hover:bg-slate-100'" class="flex-1 py-3 text-[10px] font-black uppercase tracking-widest transition-all">In Yard</button>
        </div>
        @endif
 
        <!-- Zones Tab -->
        <div x-show="sidebarTab === 'zones'" class="flex-1 overflow-y-auto flex flex-col">
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
            <div class="p-4 space-y-4">
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
                            <div class="bg-indigo-500 h-2 rounded-full" style="width: {{ min(100, ($zone->capacity_limit_m3 > 0 ? ($zone->current_utilization_m3 / $zone->capacity_limit_m3) * 100 : 0)) }}%"></div>
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
        </div>
 
        <!-- Requests Tab (Agents Only) -->
        <div x-show="sidebarTab === 'requests'" class="flex-1 overflow-y-auto p-4 space-y-4 bg-slate-50/50">
            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Pending Yard Allocation</h3>
            @forelse($pendingRequests as $manifest)
                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm space-y-3">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest leading-none mb-1">{{ $manifest->reference_no }}</p>
                            <p class="text-sm font-bold text-slate-900">{{ $manifest->vessel->name }}</p>
                        </div>
                        <span class="bg-amber-100 text-amber-700 px-2 py-0.5 rounded-[4px] text-[9px] font-black uppercase tracking-wider">Pending</span>
                    </div>
                    <div class="pt-3 border-t border-slate-100">
                        <p class="text-[10px] text-slate-500 mb-2">Preferred: <span class="font-bold text-slate-700 uppercase">{{ str_replace('_', ' ', $manifest->preferred_zone_type) }}</span></p>
                        <ul class="space-y-1">
                            @foreach($manifest->items as $item)
                                <li class="text-[11px] text-slate-600 flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                                    {{ $item->description }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @empty
                <div class="text-center py-10">
                    <p class="text-xs text-slate-400 italic">No pending yard requests.</p>
                </div>
            @endforelse
        </div>
 
        <!-- Inventory Tab (Agents Only) -->
        <div x-show="sidebarTab === 'inventory'" class="flex-1 overflow-y-auto p-4 space-y-4 bg-slate-50/50">
             <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">My Yard Inventory</h3>
             @forelse($myInventory as $item)
                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm hover:border-indigo-300 transition-all cursor-pointer" onclick="focusZone({{ $item->warehouse_zone_id }})">
                    <div class="flex justify-between items-start mb-2">
                        <p class="font-bold text-slate-900 text-sm leading-tight">{{ $item->description }}</p>
                        <span class="bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded-[4px] text-[9px] font-black uppercase tracking-wider">Allocated</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <div class="flex items-center gap-1.5">
                            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span class="text-[11px] font-bold text-indigo-600">{{ $item->zone->name }}</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span class="text-[10px] text-slate-500 font-mono">{{ $item->tracking_number }}</span>
                        </div>
                    </div>
                </div>
             @empty
                <div class="text-center py-10">
                    <p class="text-xs text-slate-400 italic">You have no items in the yard.</p>
                </div>
             @endforelse
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
                        // Heatmap Logic
                        const utilPct = {{ $z->capacity_limit_m3 > 0 ? ($z->current_utilization_m3 / $z->capacity_limit_m3) * 100 : 0 }};
                        let heatColor = zones['{{ $z->code }}'].color;
                        let heatOpacity = 0.2;
                        let statusText = 'Normal';

                        if (utilPct > 80) {
                            heatColor = '#ef4444'; // Red (Congested)
                            heatOpacity = 0.6;
                            statusText = 'Congested';
                        } else if (utilPct < 10 && {{ $z->capacity_limit_m3 }} > 0) {
                            heatColor = '#94a3b8'; // Gray (Dead Zone)
                            heatOpacity = 0.1;
                            statusText = 'Dead Zone (Under-utilized)';
                        } else if (utilPct > 50) {
                             heatOpacity = 0.4;
                        }

                        const poly = L.polygon(zones['{{ $z->code }}'].coords, {
                            color: zones['{{ $z->code }}'].color, // Keeping border color constant for identity
                            fillColor: heatColor,
                            fillOpacity: heatOpacity,
                            weight: 2
                        }).addTo(map);

                        poly.bindTooltip(`
                            <div class="text-xs font-sans">
                                <b>{{ $z->name }}</b>
                                <div class="mt-1">Util: {{ $z->current_utilization_m3 }}m³ (${utilPct.toFixed(0)}%)</div>
                                <div class="text-[9px] uppercase font-bold text-slate-500 mt-1">${statusText}</div>
                            </div>
                        `, { 
                            permanent: true, 
                            direction: "center", 
                            className: "bg-white/90 border-0 shadow-lg rounded-lg p-1" 
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
@endif
</div>
