# Phase 8.4: CCU & Container Tracking Implementation Plan

## Overview
This phase implements a dedicated tracker for Cargo Carrying Units (CCUs) and standard shipping containers. It addresses ASB's need to manage unit integrity (inspections), reefer monitoring, and demurrage billing for units staying too long in the yard.

## Objectives
1.  **Unit Lifecycle Tracking**: Gate-In -> Inspection -> Yard Stacking -> Vessel Load -> Vessel Discharge -> Gate-Out.
2.  **Condition Monitoring**: Recording damage, validity of sling certificates (for lifting), and reefer temperatures.
3.  **Demurrage Calculation**: Automated tracking of dwell time against free days to calculate detention fees.

---

## 1. Database Schema Design

### 1.1 Asset Registry
- **`ccu_containers`**
    - `id`, `container_number` (Unique, e.g., MSDU1234567)
    - `type` (Dry 20, Reefer 40, Open Top, CCU Ski, CCU Basket)
    - `size` (10ft, 20ft, 40ft)
    - `owner` (Client Name or Leasing Co)
    - `status` (in_yard, on_vessel, gate_out, maintenance)
    - `location_yard_zone` (Zone A, B, etc.)
    - `current_vessel_id` (If loaded)
    - `last_inspection_date`
    - `sling_cert_expiry` (Critical for CCUs)

### 1.2 Operations
- **`ccu_movements`**
    - `id`, `container_id`
    - `movement_type` (GATE_IN, GATE_OUT, LOAD, DISCHARGE)
    - `location_from`, `location_to`
    - `vessel_id`, `truck_plate`
    - `timestamp`
    - `handled_by`

- **`ccu_inspections`**
    - `id`, `container_id`
    - `inspection_type` (Inbound, Outbound, Periodic)
    - `condition_status` (Good, Damaged, Minor Scratches)
    - `notes`, `photos_json`
    - `inspector_name`
    - `passed` (boolean)

---

## 2. Implementation Steps

### Step 1: Foundation
- Migration: `create_ccu_tables`.
- Models: `CcuContainer`, `CcuMovement`, `CcuInspection`.
- Seeder: 10-20 containers, some Reefers, some with expired sling certs.

### Step 2: Backend Logic (`CcuService`)
- `registerGateIn($number, $type, $truck)`: Create record or update status.
- `calculateDemurrage($container)`: Logic: `(Now - GateInDate) - FreeDays * DailyRate`.
- `recordInspection($container, $status)`: Update asset health.

### Step 3: Frontend - CCU Dashboard
- **Yard Overview**: List of containers currently in port.
- **Demurrage Alert**: Highlight containers exceeding free days (e.g., > 14 days).
- **Quick Gate Form**: Simple specific input to "Receive" a container.

---

## 3. ASB Specifics
- **Sling Certificates**: Highlights expired certs (cannot lift offshore without valid sling cert).
- **Reefer**: Simple log field for temperature check.

