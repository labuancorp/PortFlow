# Phase 8.2: MHE Fleet Management Implementation Plan

## Overview
This phase implements a management system for Material Handling Equipment (MHE) such as cranes, forklifts, and prime movers. The goal is to maximize fleet utilization, ensure safety compliance through maintenance tracking, and streamline operator dispatch.

## Objectives
1.  **Fleet Visibility**: Real-time status tracking (Available, In-Use, Breakdown, Maintenance).
2.  **Maintenance Logic**: Automated scheduling of Preventive Maintenance (PM) based on engine hours or calendar intervals.
3.  **Operator Compliance**: Preventing dispatch of operators without valid/active licenses for specific equipment types.
4.  **Job Scheduling**: Booking equipment for specific tasks (Vessel ops, Yard moves).

---

## 1. Database Schema Design

### 1.1 Equipment Registry
- **`mhe_equipment`**
    - `id`, `name` (e.g., "Forklift 05")
    - `asset_code` (unique internal ID)
    - `type` (Forklift, Mobile Crane, Reach Stacker, Prime Mover)
    - `model`, `manufacturer`, `year`
    - `status` (available, partitioned (in-use), maintenance, breakdown)
    - `current_hour_meter` (decimal)
    - `next_pm_due_date` (date)
    - `next_pm_due_hours` (decimal)
    - `location` (Text or Zone ID)

### 1.2 Maintenance & Health
- **`mhe_maintenance_logs`**
    - `id`, `equipment_id`
    - `type` (PM, CM - Corrective, BM - Breakdown)
    - `description`
    - `parts_cost`, `labor_cost`
    - `start_time`, `end_time`
    - `technician_name`
    - `meter_reading_at_service`
    
- **`mhe_incidents`** (Breakdown Reports)
    - `id`, `equipment_id`
    - `reported_by`
    - `issue_description`
    - `severity` (low, medium, critical)
    - `status` (open, investigating, resolved)

### 1.3 Operations (Dispatch)
- **`mhe_operator_licenses`**
    - `id`, `user_id` (Link to Users table)
    - `license_type` (e.g., "Class F - Forklift")
    - `license_number`
    - `expiry_date`
    - `status` (active, expired, suspended)

- **`mhe_bookings`**
    - `id`, `equipment_id`
    - `operator_id` (User ID)
    - `job_type` (Vessel Operation, Yard Transfer, Maintenance)
    - `reference_id` (e.g., PortCall ID or CargoManifest ID)
    - `start_time`, `end_time`
    - `status` (scheduled, active, completed, cancelled)

---

## 2. Implementation Steps

### Step 1: Foundation (Database & Models)
- Create `create_mhe_tables` migration.
- Generate Models: `MheEquipment`, `MheMaintenanceLog`, `MheOperatorLicense`, `MheBooking`.
- Seeder: Populate fleet with 10-15 typical ASB assets (Cranes, Forklifts).

### Step 2: Backend Logic
- **`MheService`**:
    - `checkAvailability($type, $start, $end)`: Find free assets.
    - `validateOperator($user, $equipment_type)`: Check license.
    - `recordBreakdown($equipment, $description)`: Change status to breakdown, alert Admins.
    - `completeJob($booking, $finalHours)`: Update equipment hour meter.

### Step 3: Frontend - Fleet Dashboard
- **Live Status Board**:
    - Grid view of all assets with color-coded status banners.
    - "Quick Actions": Report Breakdown, Book Now.
- **Scheduler View**: A timeline/calendar view of bookings.

### Step 4: Maintenance Workflow
- List of "Due for Service" machines (Red/Yellow alerts).
- Form to log completed maintenance (resets next PM date).

---

## 3. Success Criteria
- [ ] Admins can instantly see which machines are down vs available.
- [ ] System prevents booking a crane if the operator only has a forklift license.
- [ ] Maintenance logs accumulate cost data for Asset Performance analysis.
