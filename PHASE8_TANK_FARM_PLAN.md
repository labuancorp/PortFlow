# Phase 8.1: Tank Farm & Bulk Storage Management Implementation Plan

## Overview
This phase aims to implement a specialized module for managing liquid mud plants and bulk storage facilities, a core business requirement for ASB. The system will handle tank capacity monitoring, product compatibility safety checks, weighbridge integration, and automated volume-based billing.

## Objectives
1.  **Tank Capacity Monitoring**: Real-time tracking of tank levels, available capacity, and status.
2.  **Product Compatibility Matrix**: Automated validation to prevent cross-contamination during transfers.
3.  **Bulk Movement Tracking**: Recording inflow/outflow via pipelines or trucks, integrated with weighbridge data.
4.  **Automated Billing**: Generating charges based on stored volume, throughput, and handling services.

---

## 1. Database Schema Design

### 1.1 Product Management
- **`product_types`**
    - `id`, `name` (e.g., "Liquid Mud", "Brine", "Drill Water")
    - `code`, `color_code` (for UI visualization)
    - `specific_gravity` (default density for volume-to-weight conversion)
    - `hazard_class` (explosive, flammable, etc.)
    - `requires_cleaning` (boolean - if tank needs cleaning after this product)

- **`product_compatibility`**
    - `id`
    - `product_a_id`, `product_b_id`
    - `is_compatible` (boolean)
    - `notes` (e.g., "Requires flush before switch")

### 1.2 Tank Infrastructure
- **`tanks`**
    - `id`, `name` (e.g., "T-101")
    - `zone_id` (link to WarehouseZone or dedicated TankFarmZone)
    - `capacity_volume` (max m3)
    - `current_volume` (current m3)
    - `current_product_id` (nullable)
    - `status` (active, maintenance, cleaning, contaminated)
    - `last_cleaned_at` (timestamp)
    - `gis_coordinates` (for map placement)

- **`tank_readings`**
    - `id`, `tank_id`
    - `reading_value` (level or volume)
    - `temperature` (optional)
    - `pressure` (optional)
    - `source` (manual, sensor)
    - `recorded_at`

### 1.3 Operations & Movements
- **`weighbridge_tickets`**
    - `id`, `ticket_number`
    - `truck_plate_number`
    - `gross_weight`, `tare_weight`
    - `net_weight`
    - `status` (open, closed)
    - `issued_at`

- **`bulk_movements`**
    - `id`, `type` (inbound, outbound, internal_transfer)
    - `product_id`
    - `source_tank_id` (nullable for inbound)
    - `destination_tank_id` (nullable for outbound)
    - `vessel_id` (optional linkage to marine ops)
    - `weighbridge_ticket_id` (optional linkage)
    - `planned_volume`, `actual_volume`
    - `start_time`, `end_time`
    - `status` (planned, in_progress, completed, cancelled)

---

## 2. Implementation Steps

### Step 1: Foundation (Schema & Models)
- Create migrations for all new tables.
- Generate Eloquent models with relationships.
- Create Seeders for:
    - Common Oil & Gas liquid products (OBM, WBM, Brine, Base Oil).
    - Demo Tank Farm setup (10-15 tanks).
    - Compatibility rules.

### Step 2: Backend Logic & Services
- **`TankService`**:
    - `updateLevel(tank, amount)`: Handle volume changes.
    - `checkCompatibility(tank, product)`: Check against matrix + cleaning history.
    - `validateTransfer(source, dest, volume)`: Ensure capacity and compatibility.
- **`VolumeCalculator`**:
    - Conversion helpers (Volume <-> Weight using Specific Gravity).
- **`BillingIntegration`**:
    - Extend `BillingService` to calculate fees based on `bulk_movements` (Throughput Fee) and average `tank_readings` (Storage Fee).

### Step 3: Frontend - Tank Farm Dashboard
- **Visual Tank Grid**: Livewire component showing tanks as visual cylinders.
    - CSS-based fill levels.
    - Color-coding based on Product.
    - Tooltips for details (Product, Volume %, Status).
- **Update Modal**: Form to input manual dip readings or sensor data.

### Step 4: Operations - Movement Control
- **Transfer Wizard**:
    - Select Operation Type (Vessel-to-Tank, Tank-to-Tank, Tank-to-Truck).
    - Compatibility Warning (Red alert if products clash).
    - Capacity Check (Prevent overfill).
    - Weighbridge Input form for Truck ops.
- **Movement Log**: History of all transfers.

### Step 5: GIS Integration (Bonus)
- Render Tanks on the `PortMapView` using `L.circle` or custom markers.
- Click to view Tank details.

---

## 3. Success Criteria
- [ ] Users can view all tanks and their current levels/products at a glance.
- [ ] System blocks putting Product A into a tank containing Product B if incompatible.
- [ ] Inbound/Outbound records automatically calculate Net Weight/Volume.
- [ ] Monthly storage charges can be generated from daily volume averages.
