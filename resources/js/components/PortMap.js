
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

export default class PortMap {
    constructor(containerId, options = {}) {
        this.containerId = containerId;
        this.options = {
            center: options.center || [5.2770, 115.2410], // Centered on Port
            zoom: options.zoom || 15,
            minZoom: 13,
            maxZoom: 18
        };

        this.map = null;
        this.layers = {
            berths: null,
            vessels: null,
            anchorages: null
        };

        this.berthsData = []; // Store raw data for distance calcs
        this.dropZones = {}; // Map berthId -> L.circle object
        this.activeDropBerth = null; // Currently hovered berth
        this.vesselMarkers = {}; // Map vesselId -> L.marker
        this.trackPolylines = {}; // Map vesselId -> L.polyline (for history)
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

        this.fixMarkerIcons();
        this.setupGlobalDragDrop();

        return this;
    }

    setupGlobalDragDrop() {
        const container = this.map.getContainer();

        container.addEventListener('dragover', (e) => {
            e.preventDefault(); // Must enable drop

            if (!window.currentDragVessel) return;

            // 1. Get LatLng from mouse position
            const containerPoint = this.map.mouseEventToContainerPoint(e);
            const latLng = this.map.containerPointToLatLng(containerPoint);

            // 2. Find closest berth within range (e.g. 60 meters)
            let closestBerth = null;
            let minDist = Infinity;
            const thresholdMeters = 60;

            this.berthsData.forEach(berth => {
                const status = (berth.status || '').toLowerCase();
                if (status !== 'available') return; // Only available berths

                const berthLatLng = L.latLng(berth.lat, berth.lng);
                const dist = latLng.distanceTo(berthLatLng); // in meters

                if (dist < thresholdMeters && dist < minDist) {
                    minDist = dist;
                    closestBerth = berth;
                }
            });

            // 3. Handle State Change
            if (this.activeDropBerth && (!closestBerth || closestBerth.id !== this.activeDropBerth.id)) {
                // We moved OUT of the previous berth
                this.resetDropZone(this.activeDropBerth.id);
                this.activeDropBerth = null;
            }

            if (closestBerth) {
                this.activeDropBerth = closestBerth;
                this.highlightDropZone(closestBerth, window.currentDragVessel);
            }
        });

        container.addEventListener('dragleave', (e) => {
            // Only if leaving the map entirely
            const rect = container.getBoundingClientRect();
            if (
                e.clientX < rect.left ||
                e.clientX >= rect.right ||
                e.clientY < rect.top ||
                e.clientY >= rect.bottom
            ) {
                if (this.activeDropBerth) {
                    this.resetDropZone(this.activeDropBerth.id);
                    this.activeDropBerth = null;
                }
            }
        });

        container.addEventListener('drop', (e) => {
            e.preventDefault();

            if (this.activeDropBerth) {
                // Drop on valid target
                const vesselDataStr = e.dataTransfer.getData('vessel_data');

                // Visual reset
                this.resetDropZone(this.activeDropBerth.id);
                const berthId = this.activeDropBerth.id;
                this.activeDropBerth = null;

                if (vesselDataStr) {
                    window.dispatchEvent(new CustomEvent('berth-drop', {
                        detail: { berthId: berthId, vesselData: vesselDataStr }
                    }));
                }
            }
        });
    }

    highlightDropZone(berth, vessel) {
        const circle = this.dropZones[berth.id];
        if (!circle) return;

        // Check validation
        let isValid = true;

        // Ensure numbers
        const vesselLoa = parseFloat(vessel.loa || 0);
        const vesselDraft = parseFloat(vessel.draft || 0);
        const berthLoa = parseFloat(berth.length || 0);
        const berthDraft = parseFloat(berth.draft || 0);

        if (berthLoa > 0 && vesselLoa > berthLoa) isValid = false;
        if (berthDraft > 0 && vesselDraft > berthDraft) isValid = false;

        if (isValid) {
            circle.setStyle({ fillColor: '#10b981', color: '#10b981', fillOpacity: 0.5, weight: 3 });
        } else {
            circle.setStyle({ fillColor: '#ef4444', color: '#ef4444', fillOpacity: 0.5, weight: 3 });
        }
    }

    resetDropZone(berthId) {
        const circle = this.dropZones[berthId];
        if (circle) {
            circle.setStyle({
                color: '#3b82f6',
                fillColor: '#3b82f6',
                fillOpacity: 0.1,
                weight: 1,
                dashArray: '5, 5'
            });
        }
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
        // Store data
        this.berthsData.push(berth);

        const status = (berth.status || 'default').toLowerCase();

        // Marker
        const marker = L.marker([berth.lat, berth.lng])
            .bindPopup(this.getBerthPopup(berth))
            .addTo(this.layers.berths);

        if (this.getBerthIcon) {
            marker.setIcon(this.getBerthIcon(status));
        }

        // Circle Zone
        const dropZone = L.circle([berth.lat, berth.lng], {
            radius: 40,
            color: '#3b82f6',
            fillColor: '#3b82f6',
            fillOpacity: 0.1, // Visible by default
            weight: 1,
            dashArray: '5, 5',
            interactive: false // IMPORTANT: Let the container handler manage events based on distance
        }).addTo(this.layers.berths);

        // Store reference for visual updates
        this.dropZones[berth.id] = dropZone;

        return marker;
    }

    getBerthIcon(status) {
        status = (status || 'default').toLowerCase();

        const colors = {
            'available': '#10b981',
            'occupied': '#ef4444',
            'maintenance': '#f59e0b',
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
        const status = (berth.status || '').toUpperCase();
        return `
            <div class="berth-popup">
                <h3 class="font-bold text-lg">${berth.name}</h3>
                <p><strong>Status:</strong> ${status}</p>
                <p><strong>Length:</strong> ${berth.length}m</p>
                <p><strong>Draft:</strong> ${berth.draft}m</p>
                ${berth.vessel ? `<div class="mt-2 text-sm bg-gray-100 p-1 rounded"><strong>Vessel:</strong> ${berth.vessel.name}</div>` : ''}
            </div>
        `;
    }

    addAnchorage(zone) {
        // Convert coords objects to array if needed [ {lat,lng} ] -> [ [lat,lng] ]
        // Leaflet handles [{lat, lng}, ...] fine too usually.

        const polygon = L.polygon(zone.coordinates, {
            color: '#64748b',
            weight: 1,
            dashArray: '5, 10',
            fillColor: '#94a3b8',
            fillOpacity: 0.1,
            className: 'anchorage-polygon'
        }).addTo(this.layers.anchorages);

        // Tooltip: Name + Capacity
        polygon.bindTooltip(`${zone.name}<br>Occupancy: ${zone.current}/${zone.capacity}`, {
            permanent: true,
            direction: 'center',
            className: 'anchorage-label'
        });

        // Events
        polygon.on('add', () => {
            const path = polygon.getElement();
            if (path) {
                path.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    path.setAttribute('fill-opacity', '0.3');
                    path.setAttribute('fill', '#10b981'); // Green highlight
                });

                path.addEventListener('dragleave', () => {
                    path.setAttribute('fill-opacity', '0.1');
                    path.setAttribute('fill', '#94a3b8'); // Reset
                });

                path.addEventListener('drop', (e) => {
                    e.preventDefault();
                    path.setAttribute('fill-opacity', '0.1');
                    path.setAttribute('fill', '#94a3b8'); // Reset

                    const vesselDataStr = e.dataTransfer.getData('vessel_data');
                    if (vesselDataStr) {
                        window.dispatchEvent(new CustomEvent('anchorage-drop', {
                            detail: { zoneId: zone.id, vesselData: vesselDataStr }
                        }));
                    }
                });
            }
        });

        return polygon;
    }

    addWarehouse(zone) {
        if (!zone.coordinates) return;

        L.polygon(zone.coordinates, {
            color: '#475569',
            weight: 2,
            fillColor: '#cbd5e1',
            fillOpacity: 0.5,
            className: 'warehouse-polygon'
        })
            .bindTooltip(`Warehouse: ${zone.name}`, { direction: 'center', permanent: false })
            .addTo(this.map);
    }

    drawCargoFlows(flows) {
        if (!flows) return;

        // Clear existing flows if any? (Ideally keep reference in this.layers)
        // For now simple adding

        flows.forEach(flow => {
            const line = L.polyline([flow.from, flow.to], {
                color: '#6366f1', // Indigo
                weight: 5,
                dashArray: '10, 10',
                className: 'cargo-flow-line',
                opacity: 0.8
            }).addTo(this.map);

            // Simple animation via CSS class 'cargo-flow-line' (defined in blade)
        });
    }

    updateVesselPositions(positions) {
        if (!positions || !Array.isArray(positions)) return;

        positions.forEach(pos => {
            const lat = parseFloat(pos.lat);
            const lng = parseFloat(pos.lng);
            const heading = parseFloat(pos.heading || 0);

            // Determine Color
            let color = '#3b82f6'; // Blue (Default/Moored)
            if (pos.status === 'Underway') color = '#22c55e'; // Green
            if (pos.status === 'Anchored') color = '#f59e0b'; // Orange

            // Boat Icon SVG
            const svgIcon = `
                <div style="transform: rotate(${heading}deg); transition: transform 0.5s ease;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2L2 22L12 18L22 22L12 2Z" fill="${color}" stroke="white" stroke-width="2"/>
                    </svg>
                </div>
            `;

            const icon = L.divIcon({
                className: 'vessel-track-marker',
                html: svgIcon,
                iconSize: [24, 24],
                iconAnchor: [12, 12]
            });

            if (this.vesselMarkers[pos.vessel_id]) {
                // Update existing
                const marker = this.vesselMarkers[pos.vessel_id];
                marker.setLatLng([lat, lng]);
                marker.setIcon(icon);
                marker.setPopupContent(`
                    <strong>${pos.name}</strong><br>
                    Status: ${pos.status}<br>
                    Speed: ${pos.speed} kts<br>
                    Heading: ${pos.heading}°
                `);
            } else {
                // Create New
                const marker = L.marker([lat, lng], { icon: icon })
                    .bindPopup(`
                        <strong>${pos.name}</strong><br>
                        Status: ${pos.status}<br>
                        Speed: ${pos.speed} kts<br>
                        Heading: ${pos.heading}°
                    `)
                    .addTo(this.layers.vessels);

                this.vesselMarkers[pos.vessel_id] = marker;
            }
        });
    }

    fitBounds(bounds) {
        if (bounds && bounds.length > 0) {
            this.map.fitBounds(bounds, { padding: [50, 50] });
        }
    }
}
