# PortFlow Demo Data - Complete Setup

## 🎯 Overview
Comprehensive demo database seeded with realistic operational data across all modules for both **Admin** and **Agent** views.

## 📊 Demo Data Summary

### Organizations (5)
- **ASB** - Asian Supply Base Authority (Port Authority)
- **BARAM** - Baram Shipyard Agents (Primary Agent)
- **OCEANIC** - Oceanic Maritime Services (Secondary Agent)
- **PCSB** - Petronas Carigali Sdn Bhd (Client)
- **SHELL-MY** - Shell Malaysia Exploration (Client)

### User Accounts (4)
| Role | Email | Password | Organization |
|------|-------|----------|--------------|
| Admin | admin@asb.com | password | ASB Authority |
| Agent | agent@baram.com | password | Baram Shipyard |
| Agent | agent@oceanic.com | password | Oceanic Maritime |
| HSE Officer | hse@asb.com | password | ASB Authority |

### Marine Operations

#### Vessels (6)
- MV Nautica Gamble (OSV) - Currently alongside MW1
- Barge Alpha One - Currently alongside MW2
- OSV Explorer - Scheduled arrival
- Pacific Carrier (Supply Vessel) - Scheduled
- Sea Dragon (OSV) - Completed
- Ocean Pioneer (Anchor Handling Tug) - Completed

#### Port Calls (6)
- **2 Active** (Alongside): Generating live berthing charges
- **2 Scheduled** (Approved): Future arrivals
- **2 Completed**: Historical data with invoices

#### Berths (5)
- Main Wharf 1, 2, 3
- Alpha Jetty
- Bravo Jetty

### Yard & Warehouse Operations

#### Warehouses (2)
- Main Yard A (Open Yard) - 5,000 m³
- Covered Storage B - 3,000 m³

#### Warehouse Zones (5)
- A1 - General Cargo
- A2 - Pipes & Tubulars
- A3 - DG Storage (Dangerous Goods)
- B1 - Electronics
- B2 - Chemicals (DG)

#### Cargo Manifests (5)
- **3 Discharged**: Active cargo in yard generating storage charges
- **1 Submitted**: Pending approval
- **1 Old Cargo**: 95+ days (triggers aging alerts)

#### Cargo Items (6)
- Drill Pipes, Hydraulic Pumps, Electronics
- Diesel Fuel Drums (Class 3 DG)
- Explosive Bolts (Class 1.4 DG)
- Abandoned Containers (Aging)

### Asset Rentals

#### Port Assets (5)
- Crawler Crane 250T - Available
- **Mobile Crane 50T** - Occupied (Active rental)
- **Heavy Forklift 15T** - Occupied (Active rental)
- Chemical Warehouse Bay A1 - Available
- Reach Stacker 45T - Available

#### Asset Bookings (4)
- **2 Active**: Currently generating rental charges
  - Mobile Crane: 6 hours ongoing (Baram)
  - Heavy Forklift: 3 hours ongoing (Oceanic)
- **1 Completed**: Historical booking
- **1 Requested**: Pending approval

### Financial Data

#### Invoices (2)
- **INV-2024-099**: RM 15,750.00 - PAID - ERP Synced
- **INV-2024-098**: RM 22,400.00 - ISSUED - Unpaid

#### Live Billing Exposure
- **Marine (Berthing)**: 2 vessels alongside accumulating charges
- **Yard (Storage)**: 6 cargo items in storage accumulating daily fees
- **Assets (Rentals)**: 2 active equipment rentals accumulating hourly charges

### HSE & Safety

#### Work Permits (5)
- 2 Approved (Hot Work, Confined Space)
- 2 Requested (Working at Height, Hot Work)
- 1 Closed (Historical)

### Service Requests (5)
- Water supply, Fuel bunker, Crane services
- Mix of delivered and pending statuses

### Supporting Data
- **Crew Members**: 5 personnel with transfers
- **IoT Sensors**: 5 active sensors (tide, wind, fuel flow, water flow, temperature)
- **Audit Logs**: 5 recent system actions
- **Gate Entries**: Seeded via GateEntrySeeder

## 🎬 Demo Scenarios

### Admin Dashboard View
1. **Consolidated Pending Billing**: Shows breakdown across Marine, Yard, and Assets
2. **Unpaid Invoices Modal**: Displays invoices with type indicators (⚓📦🚜)
3. **Live Operations**: 2 vessels alongside, active service requests
4. **Pending Approvals**: Berth requests, work permits, asset bookings

### Agent Portal View (Baram)
1. **Live Vessels**: 1 vessel alongside (MV Nautica Gamble)
2. **Financial Dashboard**: 
   - Marine charges accumulating
   - Yard storage fees for their cargo
   - Active crane rental (6 hours)
3. **Scheduled Operations**: OSV Explorer arriving soon
4. **History**: Completed port calls and invoices

### Agent Portal View (Oceanic)
1. **Live Vessels**: 1 vessel alongside (Barge Alpha One)
2. **Financial Dashboard**:
   - Marine charges accumulating
   - Yard storage for DG cargo
   - Active forklift rental (3 hours)
3. **Invoices**: Can view but cannot mark paid or sync SAP

## 🔄 Live Data Features

### Real-Time Calculations
- **Berthing charges**: Calculated based on vessel LOA × hours alongside
- **Warehouse storage**: Daily accumulation based on volume × days stored
- **Asset rentals**: Hourly/daily rates × duration

### Aging Alerts
- Cargo item "MF-2024-050" is 95+ days old (triggers alerts)

### ERP Integration Demo
- Invoice INV-2024-099 shows "SAP Synced" status
- Invoice INV-2024-098 shows "Pending" - can be synced by admin

## 📱 Access Points

### For Admins
- Full dashboard with all KPIs
- Invoice management (approve, mark paid, sync SAP)
- Berth planner and resource management
- Analytics and audit trails

### For Agents
- Agent Portal with consolidated operations
- Financial dashboard (view-only for invoices)
- Cargo manifest submission
- Asset booking requests
- Cannot mark invoices paid or sync to SAP

## 🚀 Quick Start

```bash
# Reset and seed database
php artisan migrate:fresh --seed

# Start server
php artisan serve

# Login as Admin
Email: admin@asb.com
Password: password

# Login as Agent (Baram)
Email: agent@baram.com
Password: password
```

## ✅ Demo Checklist

- [x] Marine operations with live berthing charges
- [x] Yard/Warehouse with storage fees
- [x] Asset rentals with active bookings
- [x] Cargo manifests with DG classification
- [x] Invoices with ERP sync status
- [x] Role-based access control (Admin vs Agent)
- [x] HSE work permits
- [x] Service requests
- [x] IoT sensor monitoring
- [x] Audit logging
- [x] Consolidated financial dashboard
- [x] Visual invoice categorization (Marine/Yard/Asset)

---

**Last Updated**: 2025-12-28
**Status**: ✅ Production Ready for Demo
