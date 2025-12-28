# 📡 Phase 7.4: Vessel Tracking & Movement History - Implementation Guide

## 📋 **Overview**
**Goal:** Implement real-time vessel tracking visualization and historical movement playback. Since live AIS hardware is not available, we will implement a simulation engine for demonstration.

**Prerequisites:** Phase 7.3 completed.

---

## 🎯 **Step 4.1: Database Schema**

### **Objective:**
Store historical vessel positions (AIS Data).

### **Tasks:**
- [ ] Create `vessel_positions` table.
    - `vessel_id` (FK)
    - `latitude`, `longitude` (Decimal)
    - `speed` (Knots)
    - `heading` (Degrees)
    - `status` (String: "Underway", "Moored")
    - `timestamp`

### **Migration:**
```php
Schema::create('vessel_positions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('vessel_id')->constrained();
    $table->decimal('latitude', 10, 7);
    $table->decimal('longitude', 10, 7);
    $table->decimal('speed', 5, 2)->default(0);
    $table->decimal('heading', 5, 2)->default(0);
    $table->string('status')->nullable();
    $table->timestamp('recorded_at');
    $table->timestamps();
    
    // Index for fast time-range queries
    $table->index(['vessel_id', 'recorded_at']);
});
```

---

## 🎯 **Step 4.2: Movement Simulation (Backend)**

### **Objective:**
Create a command to simulate vessels moving from Anchorage to Berth.

### **Tasks:**
- [ ] Create command `gis:simulate-traffic`.
- [ ] Logic:
    1.  Pick a vessel in 'Anchorage'.
    2.  Calculate path to 'Berth'.
    3.  Generate intermediate points over time.
    4.  Insert into `vessel_positions`.
    5.  Update `PortCall` current position.

---

## 🎯 **Step 4.3: Real-Time Visualization (Frontend)**

### **Objective:**
Update map markers smoothly without full page reload.

### **Tasks:**
- [ ] Add `pollVesselPositions` method to `PortMap.js`.
- [ ] Implement Livewire polling (every 5s) to fetch new positions.
- [ ] Animate markers:
    ```javascript
    marker.setLatLng([newLat, newLng]);
    marker.setRotationAngle(newHeading); // Requires Leaflet.RotatedMarker plugin
    ```

---

## 🎯 **Step 4.4: Historical Tracks**

### **Objective:**
Show where a vessel has been.

### **Tasks:**
- [ ] Add "Show Track" toggle in vessel popup.
- [ ] Draw `L.polyline` connecting historical points.
- [ ] Color code by speed (Red=Stop, Green=Moving).

---
