# Product Requirements Document (PRD)

## 1.1 Executive Summary
PortFlow is a centralized maritime logistics platform designed to digitize the scheduling, berthing, and billing operational lifecycle of the Asian Supply Base. It replaces manual scheduling with a transparent, slot-based booking system.

## 1.2 Core Goals
- **Reduce Idle Time**: Cut vessel turnaround time by 15% via efficient slot planning.
- **Paperless Operations**: Digitize the "Arrival Notification" and "Service Request" forms.
- **Automated Billing**: Auto-calculate dockage & wharfage fees based on timestamps.

## 1.3 User Roles
- **Admin (ASB Control Room)**: Full view, drag-and-drop berth planning, override authority.
- **Shipping Agent**: Can request bookings, view their vessel status, upload crew manifests.
- **Ground Ops (Foreman)**: Mobile view. Confirms "Line Secured" (docked) and "Line Released" (departed).
- **Client (e.g., Petronas Rep)**: Read-only view of their cargo/vessels.

# 2. User Stories
- **Shipping Agent**: Submit a "Notice of Arrival" (NOA) with vessel specs (length, draft) so that I can secure a berth slot before my vessel arrives at Labuan waters.
- **Control Room**: See a visual Timeline (Gantt chart) of all berths so that I can spot gaps and squeeze in more vessels to maximize revenue.
- **Ground Ops**: Tap a "Start Operation" button on my tablet so that the system timestamps the exact billing start time, avoiding disputes.
- **Finance Officer**: Generate a Proforma Invoice instantly upon departure so that we can bill the client immediately without waiting for paper logs.

# 3. UI/UX Strategy (The "Smart & Clean" Look)
## Visual Style:
- **Backgrounds**: `bg-slate-50` (light mode) / `bg-slate-900` (dark mode).
- **Cards**: Pure white `bg-white` with subtle borders `border-slate-200`. No heavy shadows.
- **Accents**:
    - **Primary**: Deep Ocean Blue (`#0f172a` - Slate 900) for headers/nav.
    - **Action**: Electric Teal (`#0d9488` - Teal 600) for buttons (implies safety/marine).
- **Status Indicators**: Small, solid dots. Red (Anchorage), Amber (Approaching), Green (Alongside).
- **Typography**: Inter or Public Sans. Tight tracking for data tables.

## Key Interface Element: The "Berth Board"
- Instead of a simple list, build a Livewire Gantt Component.
- **Y-Axis**: Berth Names (Berth 1, Berth 2, Alpha Wharf).
- **X-Axis**: Time (Current time marked with a vertical red line).
- **Blocks**: Vessels. Click to expand details. Drag to reschedule.

# 4. Database Schema (ERD)

```sql
-- 1. Organizations (Agents, Oil Majors, ASB Depts)
CREATE TABLE organizations (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),
    type ENUM('authority', 'agent', 'client', 'vendor'),
    code VARCHAR(50) UNIQUE, -- e.g., PET, SHELL
    billing_address TEXT,
    created_at TIMESTAMP
);

-- 2. Vessels (The core asset)
CREATE TABLE vessels (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    organization_id BIGINT, -- Owner/Charterer
    name VARCHAR(255),
    imo_number VARCHAR(20) UNIQUE, -- Global ID
    flag_country VARCHAR(50),
    loa_meters DECIMAL(5,2), -- Length Overall (Crucial for berthing)
    draft_meters DECIMAL(4,2), -- Depth (Crucial for dredging limits)
    vessel_type VARCHAR(50) -- OSV, Barge, Tanker
);

-- 3. Berths (The ASB Assets)
CREATE TABLE berths (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50), -- e.g., "Main Wharf 3"
    code VARCHAR(20),
    max_loa DECIMAL(5,2),
    max_draft DECIMAL(4,2),
    status ENUM('active', 'maintenance', 'occupied')
);

-- 4. Port Calls (The main transaction)
CREATE TABLE port_calls (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    vessel_id BIGINT,
    agent_id BIGINT, -- Linked to organizations
    status ENUM('requested', 'approved', 'anchored', 'alongside', 'completed', 'cancelled'),
    eta TIMESTAMP NULL, -- Estimated Time Arrival
    etd TIMESTAMP NULL, -- Estimated Time Departure
    ata TIMESTAMP NULL, -- Actual Time Arrival (Anchor)
    atb TIMESTAMP NULL, -- Actual Time Berthing (Docked - Billing Starts)
    atd TIMESTAMP NULL, -- Actual Time Departure (Billing Ends)
    assigned_berth_id BIGINT NULL,
    reference_no VARCHAR(50) UNIQUE -- e.g., PC-2025-001
);

-- 5. Service Requests (Add-ons)
CREATE TABLE service_requests (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    port_call_id BIGINT,
    service_type ENUM('water', 'fuel', 'crane', 'forklift', 'crew_change'),
    quantity DECIMAL(10,2),
    unit VARCHAR(20), -- MT, Liters, Hours
    status ENUM('pending', 'delivered'),
    requested_at TIMESTAMP
);
```

# 5. Check-in Flow (Ground Ops)
1. **Login**: Foreman logs into the tablet with 'Ground Ops' role.
2. **Dashboard**: Sees a list of 'Expected Arrivals' for the day.
3. **Action**:
    - When vessel calls 'In Range', Foreman taps 'Vessel Approaching' (Status: approaching).
    - When vessel anchors, Foreman taps 'Anchored' (Status: anchored). Action triggers 'ATA' timestamp.
    - When vessel is tied up, Foreman taps 'Line Secured' (Status: alongside). Action triggers 'ATB' timestamp (Billing Start).
4. **Validation**: System checks GPS geofence (future feature) or simply confirms with a modal 'Confirm Vessel [Name] at [Berth X]?'.
5. **Completion**: When departing, Foreman taps 'Line Released' (Status: completed). triggers 'ATD' timestamp (Billing End).

# 6. Pilot Proposal
**Phase 1: Berth Scheduling (Weeks 1-4)**
- **Goal**: Replace Excel with PortFlow for purely scheduling.
- **Users**: Control Room only.
- **Data**: Manual entry of bookings.
- **Success Metric**: 100% of berth slots are visualized on the Board.

**Phase 2: Mobile Check-in (Weeks 5-8)**
- **Goal**: Real-time timestamps.
- **Users**: Ground Ops + Control Room.
- **Feature**: Tablet view for 'Line Secured'.
- **Success Metric**: Billing timestamps match physical logs within 5 minutes.


## Currency
All financial transactions, billing, and pricing in PortFlow are denominated in **Malaysian Ringgit (RM/MYR)**.
