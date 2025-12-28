# 🗺️ Phase 7: GIS Port Management - Step-by-Step Implementation Guide

## 📋 **Overview**

**Goal:** Implement GIS-enabled port management with interactive maps, berth allocation, vessel tracking, and spatial analytics.

**Duration:** 20 weeks (5 phases × 4 weeks each)

**Current Status:** 📝 Planning Phase

---

## 🎯 **Phase 7.1: Foundation & Basic Map (Weeks 1-4)**

### **Objective:** Set up GIS infrastructure and display basic port map

---

### **Step 1.1: Database Setup (Week 1, Days 1-2)**

#### **Tasks:**
- [ ] Install PostGIS extension for PostgreSQL
- [ ] Create spatial tables
- [ ] Add spatial columns to existing tables
- [ ] Create spatial indexes

#### **Database Migrations:**

**Migration 1: Install PostGIS**
```bash
# File: database/migrations/2025_01_01_000001_enable_postgis.php
php artisan make:migration enable_postgis
```

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Enable PostGIS extension
        DB::statement('CREATE EXTENSION IF NOT EXISTS postgis');
        DB::statement('CREATE EXTENSION IF NOT EXISTS postgis_topology');
    }

    public function down(): void
    {
        DB::statement('DROP EXTENSION IF EXISTS postgis_topology');
        DB::statement('DROP EXTENSION IF EXISTS postgis');
    }
};
```

**Migration 2: Add Spatial Columns to Berths**
```php
<?php
// File: database/migrations/2025_01_01_000002_add_spatial_columns_to_berths.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('berths', function (Blueprint $table) {
            // Add spatial columns using raw SQL
        });
        
        // Add geometry columns (PostGIS specific)
        DB::statement("SELECT AddGeometryColumn('berths', 'location', 4326, 'POINT', 2)");
        DB::statement("SELECT AddGeometryColumn('berths', 'boundary', 4326, 'POLYGON', 2)");
        
        // Create spatial index
        DB::statement('CREATE INDEX berths_location_idx ON berths USING GIST(location)');
        DB::statement('CREATE INDEX berths_boundary_idx ON berths USING GIST(boundary)');
    }

    public function down(): void
    {
        Schema::table('berths', function (Blueprint $table) {
            $table->dropColumn(['location', 'boundary']);
        });
    }
};
```

**Migration 3: Create Port Features Table**
```php
<?php
// File: database/migrations/2025_01_01_000003_create_port_features_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('port_features', function (Blueprint $table) {
            $table->id();
            $table->string('feature_type', 50); // 'berth', 'anchorage', 'warehouse', 'crane'
            $table->string('name');
            $table->json('properties')->nullable(); // Flexible metadata
            $table->enum('status', ['active', 'inactive', 'maintenance'])->default('active');
            $table->timestamps();
        });
        
        // Add geometry column
        DB::statement("SELECT AddGeometryColumn('port_features', 'geometry', 4326, 'GEOMETRY', 2)");
        DB::statement('CREATE INDEX port_features_geometry_idx ON port_features USING GIST(geometry)');
    }

    public function down(): void
    {
        Schema::dropIfExists('port_features');
    }
};
```

#### **Run Migrations:**
```bash
php artisan migrate
```

#### **Success Criteria:**
- ✅ PostGIS extension installed
- ✅ Spatial columns added to berths table
- ✅ Port features table created
- ✅ Spatial indexes created

---

### **Step 1.2: Frontend Setup (Week 1, Days 3-5)**

#### **Tasks:**
- [ ] Install Leaflet.js
- [ ] Create base map component
- [ ] Configure map tiles (OpenStreetMap)
- [ ] Set up map container

#### **Installation:**
```bash
npm install leaflet
npm install @types/leaflet --save-dev
```

#### **Create Map Component:**

**File: `resources/js/components/PortMap.js`**
```javascript
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
        
        return this;
    }
    
    addBerth(berth) {
        const marker = L.marker([berth.lat, berth.lng], {
            icon: this.getBerthIcon(berth.status)
        }).bindPopup(this.getBerthPopup(berth));
        
        this.layers.berths.addLayer(marker);
        return marker;
    }
    
    getBerthIcon(status) {
        const colors = {
            'available': 'green',
            'occupied': 'red',
            'maintenance': 'orange'
        };
        
        return L.divIcon({
            className: `berth-marker berth-${status}`,
            html: `<div class="berth-icon" style="background-color: ${colors[status]}"></div>`,
            iconSize: [30, 30]
        });
    }
    
    getBerthPopup(berth) {
        return `
            <div class="berth-popup">
                <h3>${berth.name}</h3>
                <p><strong>Status:</strong> ${berth.status}</p>
                <p><strong>Length:</strong> ${berth.length}m</p>
                <p><strong>Draft:</strong> ${berth.draft}m</p>
                ${berth.vessel ? `<p><strong>Vessel:</strong> ${berth.vessel.name}</p>` : ''}
            </div>
        `;
    }
    
    clearBerths() {
        this.layers.berths.clearLayers();
    }
    
    fitBounds(bounds) {
        this.map.fitBounds(bounds);
    }
}
```

#### **Create Livewire Component:**

**File: `app/Livewire/GIS/PortMapView.php`**
```php
<?php

namespace App\Livewire\GIS;

use Livewire\Component;
use App\Models\Berth;
use App\Models\PortCall;
use Illuminate\Support\Facades\DB;

class PortMapView extends Component
{
    public $selectedBerth = null;
    public $selectedVessel = null;
    
    public function mount()
    {
        //
    }
    
    public function getBerthsData()
    {
        $berths = Berth::with(['currentPortCall.vessel'])
            ->get()
            ->map(function($berth) {
                // Extract coordinates from PostGIS point
                $location = $this->extractCoordinates($berth->location);
                
                return [
                    'id' => $berth->id,
                    'name' => $berth->name,
                    'lat' => $location['lat'] ?? null,
                    'lng' => $location['lng'] ?? null,
                    'status' => $berth->status,
                    'length' => $berth->length,
                    'draft' => $berth->draft,
                    'vessel' => $berth->currentPortCall ? [
                        'name' => $berth->currentPortCall->vessel->name,
                        'imo' => $berth->currentPortCall->vessel->imo_number
                    ] : null
                ];
            });
        
        return $berths;
    }
    
    private function extractCoordinates($geometryString)
    {
        if (!$geometryString) return null;
        
        // Parse PostGIS POINT format: "POINT(lng lat)"
        preg_match('/POINT\(([-\d.]+)\s+([-\d.]+)\)/', $geometryString, $matches);
        
        if (count($matches) === 3) {
            return [
                'lng' => (float)$matches[1],
                'lat' => (float)$matches[2]
            ];
        }
        
        return null;
    }
    
    public function selectBerth($berthId)
    {
        $this->selectedBerth = Berth::with(['currentPortCall.vessel'])->find($berthId);
    }
    
    public function render()
    {
        return view('livewire.gis.port-map-view', [
            'berthsData' => $this->getBerthsData()
        ]);
    }
}
```

#### **Create Blade View:**

**File: `resources/views/livewire/gis/port-map-view.blade.php`**
```html
<div class="p-8 bg-slate-50 min-h-screen">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-slate-900">Port Map</h1>
        <p class="text-slate-500 mt-1">Interactive berth allocation and vessel tracking</p>
    </div>
    
    <div class="grid grid-cols-12 gap-6">
        <!-- Map Container -->
        <div class="col-span-9">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div id="port-map" class="w-full h-[700px]"></div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-span-3">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Berth Status</h3>
                
                <div class="space-y-2">
                    @foreach($berthsData as $berth)
                    <div wire:click="selectBerth({{ $berth['id'] }})" 
                         class="p-3 rounded-lg border cursor-pointer hover:bg-slate-50 transition-colors
                                {{ $selectedBerth && $selectedBerth->id === $berth['id'] ? 'border-blue-500 bg-blue-50' : 'border-slate-200' }}">
                        <div class="flex items-center justify-between">
                            <span class="font-medium text-slate-900">{{ $berth['name'] }}</span>
                            <span class="px-2 py-1 text-xs rounded-full
                                {{ $berth['status'] === 'available' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $berth['status'] === 'occupied' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $berth['status'] === 'maintenance' ? 'bg-orange-100 text-orange-800' : '' }}">
                                {{ ucfirst($berth['status']) }}
                            </span>
                        </div>
                        @if($berth['vessel'])
                        <div class="mt-1 text-sm text-slate-600">
                            {{ $berth['vessel']['name'] }}
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script type="module">
        import PortMap from '/resources/js/components/PortMap.js';
        
        document.addEventListener('DOMContentLoaded', function() {
            const portMap = new PortMap('port-map').init();
            
            // Load berths data
            const berthsData = @json($berthsData);
            
            berthsData.forEach(berth => {
                if (berth.lat && berth.lng) {
                    portMap.addBerth(berth);
                }
            });
            
            // Fit map to show all berths
            const bounds = berthsData
                .filter(b => b.lat && b.lng)
                .map(b => [b.lat, b.lng]);
            
            if (bounds.length > 0) {
                portMap.fitBounds(bounds);
            }
        });
    </script>
    @endpush
</div>

<style>
    .berth-icon {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 3px solid white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }
    
    .berth-popup h3 {
        margin: 0 0 8px 0;
        font-size: 16px;
        font-weight: bold;
    }
    
    .berth-popup p {
        margin: 4px 0;
        font-size: 14px;
    }
</style>
```

#### **Add Route:**

**File: `routes/web.php`**
```php
Route::get('/gis/port-map', App\Livewire\GIS\PortMapView::class)
    ->middleware(['auth', 'role:admin'])
    ->name('gis.port-map');
```

#### **Success Criteria:**
- ✅ Leaflet.js installed and configured
- ✅ Base map displays with OpenStreetMap tiles
- ✅ Map centered on port location
- ✅ Map component created and functional

---

### **Step 1.3: Seed Berth Coordinates (Week 2, Days 1-2)**

#### **Tasks:**
- [ ] Create seeder for berth locations
- [ ] Add sample coordinates for existing berths
- [ ] Test coordinate display on map

#### **Create Seeder:**

**File: `database/seeders/BerthCoordinatesSeeder.php`**
```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Berth;
use Illuminate\Support\Facades\DB;

class BerthCoordinatesSeeder extends Seeder
{
    public function run(): void
    {
        // Sample berth coordinates for Labuan Port
        // These are example coordinates - replace with actual berth locations
        $berthCoordinates = [
            'Berth 1' => ['lat' => 5.2831, 'lng' => 115.2308],
            'Berth 2' => ['lat' => 5.2835, 'lng' => 115.2315],
            'Berth 3' => ['lat' => 5.2839, 'lng' => 115.2322],
            'Berth 4' => ['lat' => 5.2843, 'lng' => 115.2329],
            'Berth 5' => ['lat' => 5.2847, 'lng' => 115.2336],
        ];
        
        foreach ($berthCoordinates as $berthName => $coords) {
            $berth = Berth::where('name', $berthName)->first();
            
            if ($berth) {
                // Update berth with PostGIS point
                DB::statement("
                    UPDATE berths 
                    SET location = ST_SetSRID(ST_MakePoint(?, ?), 4326)
                    WHERE id = ?
                ", [$coords['lng'], $coords['lat'], $berth->id]);
                
                $this->command->info("Updated {$berthName} with coordinates");
            }
        }
    }
}
```

#### **Run Seeder:**
```bash
php artisan db:seed --class=BerthCoordinatesSeeder
```

#### **Success Criteria:**
- ✅ All berths have coordinates
- ✅ Berths display on map
- ✅ Clicking berth shows popup with details

---

### **Step 1.4: Add Berth Status Indicators (Week 2, Days 3-5)**

#### **Tasks:**
- [ ] Color-code berths by status
- [ ] Add legend to map
- [ ] Implement real-time status updates

#### **Update CSS:**

**File: `resources/css/app.css`**
```css
/* Berth marker styles */
.berth-marker {
    background: transparent;
    border: none;
}

.berth-icon {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    border: 3px solid white;
    box-shadow: 0 2px 6px rgba(0,0,0,0.3);
    transition: transform 0.2s;
}

.berth-marker:hover .berth-icon {
    transform: scale(1.2);
}

/* Status colors */
.berth-available .berth-icon {
    background-color: #10b981; /* Green */
}

.berth-occupied .berth-icon {
    background-color: #ef4444; /* Red */
}

.berth-maintenance .berth-icon {
    background-color: #f59e0b; /* Orange */
}

/* Map legend */
.map-legend {
    position: absolute;
    bottom: 20px;
    right: 20px;
    background: white;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    z-index: 1000;
}

.legend-item {
    display: flex;
    align-items: center;
    margin-bottom: 8px;
}

.legend-item:last-child {
    margin-bottom: 0;
}

.legend-color {
    width: 16px;
    height: 16px;
    border-radius: 50%;
    margin-right: 8px;
    border: 2px solid white;
    box-shadow: 0 1px 3px rgba(0,0,0,0.2);
}
```

#### **Add Legend to Map:**

Update `resources/views/livewire/gis/port-map-view.blade.php`:
```html
<!-- Add inside map container -->
<div class="map-legend">
    <h4 class="text-sm font-bold text-slate-900 mb-2">Berth Status</h4>
    <div class="legend-item">
        <div class="legend-color" style="background-color: #10b981;"></div>
        <span class="text-sm text-slate-700">Available</span>
    </div>
    <div class="legend-item">
        <div class="legend-color" style="background-color: #ef4444;"></div>
        <span class="text-sm text-slate-700">Occupied</span>
    </div>
    <div class="legend-item">
        <div class="legend-color" style="background-color: #f59e0b;"></div>
        <span class="text-sm text-slate-700">Maintenance</span>
    </div>
</div>
```

#### **Success Criteria:**
- ✅ Berths color-coded by status
- ✅ Legend displayed on map
- ✅ Status updates reflected immediately

---

### **Step 1.5: Testing & Documentation (Week 3-4)**

#### **Tasks:**
- [ ] Test map on different browsers
- [ ] Test mobile responsiveness
- [ ] Document API endpoints
- [ ] Create user guide

#### **Testing Checklist:**
- [ ] Map loads correctly
- [ ] Berths display at correct locations
- [ ] Popups show correct information
- [ ] Status colors are accurate
- [ ] Map is responsive on mobile
- [ ] No console errors

#### **Success Criteria:**
- ✅ All tests passing
- ✅ Documentation complete
- ✅ Ready for Phase 7.2

---

## 📊 **Phase 7.1 Completion Checklist**

- [ ] PostGIS installed and configured
- [ ] Spatial tables created
- [ ] Leaflet.js integrated
- [ ] Base map displaying
- [ ] Berths showing on map
- [ ] Status indicators working
- [ ] Legend added
- [ ] Tests passing
- [ ] Documentation complete

---

## 🚀 **Next Phase Preview: Phase 7.2 - Interactive Berth Allocation**

**Coming Next:**
- Drag-and-drop vessel assignment
- Berth compatibility checking
- Visual conflict detection
- Berth utilization dashboard

---

**Status:** 📝 **READY TO START**  
**Estimated Duration:** 4 weeks  
**Prerequisites:** PortFlow Phase 6 complete, PostgreSQL with PostGIS support

---

*This is a living document. Update progress as you complete each step.*
