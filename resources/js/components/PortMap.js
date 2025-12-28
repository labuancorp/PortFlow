import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

export default class PortMap {
    constructor(containerId, options = {}) {
        this.containerId = containerId;
        this.options = {
            center: options.center || [5.2831, 115.2308], // Labuan coordinates
            zoom: options.zoom || 14,
            minZoom: 12,
            maxZoom: 18
        };

        this.map = null;
        this.layers = {
            berths: null,
            vessels: null,
            anchorages: null
        };
    }

    init() {
        // Initialize map
        this.map = L.map(this.containerId).setView(
            this.options.center,
            this.options.zoom
        );

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 18
        }).addTo(this.map);

        // Initialize layer groups
        this.layers.berths = L.layerGroup().addTo(this.map);
        this.layers.vessels = L.layerGroup().addTo(this.map);
        this.layers.anchorages = L.layerGroup().addTo(this.map);

        // Fix for missing marker icons in Leaflet + Webpack/Vite
        this.fixMarkerIcons();

        return this;
    }

    fixMarkerIcons() {
        delete L.Icon.Default.prototype._getIconUrl;
        L.Icon.Default.mergeOptions({
            iconRetinaUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon-2x.png',
            iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
        });
    }

    addBerth(berth) {
        // Simple marker for now, can be improved with custom DivIcon later
        const marker = L.marker([berth.lat, berth.lng], {
            // icon: this.getBerthIcon(berth.status) // Using default for now to ensure visibility
        }).bindPopup(this.getBerthPopup(berth));

        // If we want custom icons, we need to implement getBerthIcon
        if (this.getBerthIcon) {
            marker.setIcon(this.getBerthIcon(berth.status));
        }

        this.layers.berths.addLayer(marker);
        return marker;
    }

    getBerthIcon(status) {
        const colors = {
            'available': '#10b981',
            'occupied': '#ef4444',
            'maintenance': '#f59e0b',
            // Fallback
            'default': '#3b82f6'
        };

        const color = colors[status] || colors['default'];

        return L.divIcon({
            className: `berth-marker berth-${status}`,
            html: `<div class="berth-icon" style="background-color: ${color}"></div>`,
            iconSize: [24, 24],
            iconAnchor: [12, 12]
        });
    }

    getBerthPopup(berth) {
        return `
            <div class="berth-popup">
                <h3 class="font-bold text-lg">${berth.name}</h3>
                <p><strong>Status:</strong> ${berth.status.toUpperCase()}</p>
                <p><strong>Length:</strong> ${berth.length}m</p>
                <p><strong>Draft:</strong> ${berth.draft}m</p>
                ${berth.vessel ? `<div class="mt-2 text-sm bg-gray-100 p-1 rounded"><strong>Vessel:</strong> ${berth.vessel.name}</div>` : ''}
            </div>
        `;
    }

    clearBerths() {
        this.layers.berths.clearLayers();
    }

    fitBounds(bounds) {
        if (bounds && bounds.length > 0) {
            this.map.fitBounds(bounds);
        }
    }
}
