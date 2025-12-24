@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        .berth-label {
            background: transparent;
            border: none;
            box-shadow: none;
            color: rgba(255,255,255,0.7);
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
             // Initialize Map centered on ASB Labuan
            var map = L.map('portMap').setView([5.263, 115.242], 15);

            // Satellite Tiles (Esri World Imagery)
            L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
            }).addTo(map);

            // Add Labels (Stamen Toner Lite - using CartoDB Light for demo accuracy)
             L.tileLayer('https://{s}.basemaps.cartocdn.com/light_only_labels/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; OpenStreetMap &copy; CARTO',
                subdomains: 'abcd',
                maxZoom: 20
            }).addTo(map);

            // Data from Livewire
            const vessels = @json($vessels);
            
            // Custom Icons
            const vesselIcon = (status) => {
                let color = status === 'alongside' ? '#22c55e' : (status === 'anchored' ? '#fbbf24' : '#3b82f6');
                return L.divIcon({
                    className: 'custom-div-icon',
                    html: `<div style="background-color: ${color}; width: 12px; height: 12px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 10px ${color};"></div>`,
                    iconSize: [12, 12],
                    iconAnchor: [6, 6]
                });
            };

            // Plot Vessels
            vessels.forEach(v => {
                let marker = L.marker([v.lat, v.lng], { icon: vesselIcon(v.status) }).addTo(map);
                
                // Click Event to update Alpine state
                marker.on('click', () => {
                    // Update AlpineJS component state
                    document.querySelector('[x-data]').__x.$data.activeVessel = v;
                });
            });

            // Draw Geofences for Berths
            const berthZones = [
                {name: 'Main Wharf', lat: 5.263, lng: 115.243, r: 150},
                {name: 'Alpha Jetty', lat: 5.261, lng: 115.241, r: 80}
            ];

            berthZones.forEach(z => {
                L.circle([z.lat, z.lng], {
                    color: 'white',
                    fillColor: '#fff',
                    fillOpacity: 0.1,
                    radius: z.r,
                    weight: 1,
                    dashArray: '5, 5'
                }).addTo(map).bindTooltip(z.name, {permanent: true, direction: "center", className: "berth-label"});
            });
        });
    </script>
@endpush

<div class="h-screen flex flex-col relative" x-data="{ activeVessel: null }">
    <!-- Map Header -->
    <div class="absolute top-4 left-4 z-[40] bg-white/90 backdrop-blur-md p-4 rounded-2xl shadow-xl border border-slate-200 max-w-sm">
        <h1 class="font-black text-slate-900 text-lg tracking-tight">PortFlow <span class="text-teal-600">GIS</span></h1>
        <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mb-2">Live Digital Twin • Labuan Base</p>
        <div class="flex gap-2">
            <span class="flex items-center gap-1 text-[10px] font-bold text-slate-600"><span class="w-2 h-2 rounded-full bg-green-500"></span> Alongside</span>
            <span class="flex items-center gap-1 text-[10px] font-bold text-slate-600"><span class="w-2 h-2 rounded-full bg-amber-400"></span> Anchored</span>
            <span class="flex items-center gap-1 text-[10px] font-bold text-slate-600"><span class="w-2 h-2 rounded-full bg-blue-500"></span> Approaching</span>
        </div>
    </div>

    <!-- Active Vessel Card (Floating) -->
    <template x-if="activeVessel">
        <div class="absolute bottom-8 left-4 z-[40] bg-slate-900 text-white p-6 rounded-2xl shadow-2xl max-w-xs transition-all"
             x-transition.duration.300ms>
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="font-bold text-xl" x-text="activeVessel.name"></h3>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest" x-text="activeVessel.type"></p>
                </div>
                <button @click="activeVessel = null" class="text-slate-400 hover:text-white">✕</button>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-400">Status</span>
                    <span class="font-bold uppercase" 
                          :class="activeVessel.status === 'alongside' ? 'text-green-400' : (activeVessel.status === 'anchored' ? 'text-amber-400' : 'text-blue-400')"
                          x-text="activeVessel.status"></span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-400">Location</span>
                    <span class="font-bold text-slate-200" x-text="activeVessel.berth"></span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-400">Coordinates</span>
                    <span class="font-mono text-xs text-slate-500" x-text="activeVessel.lat.toFixed(4) + ', ' + activeVessel.lng.toFixed(4)"></span>
                </div>
            </div>
             <a :href="'/'" class="block mt-4 w-full py-2 bg-indigo-600 hover:bg-indigo-500 rounded-lg text-center text-xs font-bold uppercase tracking-widest transition-colors">
                View Schedule
            </a>
        </div>
    </template>

    <!-- Map Container -->
    <div id="portMap" class="w-full h-full bg-slate-100 z-0"></div>
</div>
