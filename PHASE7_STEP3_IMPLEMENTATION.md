# ⚓ Phase 7.3: Anchorage Management - Implementation Guide

## 📋 **Overview**
**Goal:** Implement spatial management of anchorage zones, visualizing where waiting vessels are parked and managing capacity.

**Prerequisites:** Phase 7.2 completed (Map functional, Drag-and-drop functional).

---

## 🎯 **Step 3.1: Database Schema & Models**

### **Objective:**
Store anchorage zone boundaries and track vessel assignments.

### **Tasks:**
- [ ] Create `anchorage_zones` table.
    - Name (e.g., "Alpha Anchorage")
    - Boundary (JSON Polygon)
    - Capacity (Max vessels)
    - Min/Max Depth
- [ ] Update `PortCall` model.
    - Add `anchorage_zone_id` foreign key.
    - Add `anchored_at` timestamp.

### **Migration:**
```php
Schema::create('anchorage_zones', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->json('boundary_coordinates'); // [[lat,lng], [lat,lng]...]
    $table->integer('max_capacity')->default(5);
    $table->decimal('min_depth', 5, 2)->nullable();
    $table->timestamps();
});

Schema::table('port_calls', function (Blueprint $table) {
    $table->foreignId('anchorage_zone_id')->nullable()->constrained();
    $table->timestamp('anchored_at')->nullable();
});
```

---

## 🎯 **Step 3.2: Map Visualization (Frontend)**

### **Objective:**
Draw anchorage zones on the map and show vessels inside them.

### **Tasks:**
- [ ] Render polygons for each anchorage zone in `PortMap.js`.
- [ ] Style zones (dashed blue/gray lines).
- [ ] Add labels showing name + capacity (e.g., "Alpha: 2/5").

### **JS Logic:**
```javascript
// PortMap.js
addAnchorage(zone) {
    const polygon = L.polygon(zone.coordinates, {
         color: '#64748b',
         weight: 1,
         dashArray: '5, 10',
         fillColor: '#94a3b8',
         fillOpacity: 0.1
    }).bindTooltip(`${zone.name} (${zone.current}/${zone.capacity})`);
    
    // Add dragover/drop listeners (similar to berths)
}
```

---

## 🎯 **Step 3.3: Anchorage Assignment Logic**

### **Objective:**
Allow dragging a vessel from the "Incoming" queue to an "Anchorage Zone".

### **Tasks:**
- [ ] Enable drop on Anchorage polygons.
- [ ] Backend: Update `PortCall` status to 'anchored' and set `anchorage_zone_id`.
- [ ] Update "Queue" sidebar to show "Anchored" vs "Incoming" vessels.

### **Logic:**
1.  **Incoming Vessel** -> Drag to **Anchorage** -> Status: `anchored`.
2.  **Anchored Vessel** -> Drag to **Berth** -> Status: `alongside`.

---

## 🎯 **Step 3.4: Capacity Monitoring**

### **Objective:**
Prevent overfilling anchorage zones.

### **Tasks:**
- [ ] Backend check: `Count(vessels) < MaxCapacity`.
- [ ] Visual flashback (Red zone) if full.

---
