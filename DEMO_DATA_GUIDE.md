# PortFlow Demo Data - ASB Management Presentation Guide

## 🎯 Overview
This document describes the comprehensive demo data that has been seeded into PortFlow to demonstrate all features to ASB Management.

## 📊 Demo Data Summary

### 1. **Organizations** (6 total)
- **ASB** (Asian Supply Base Authority) - Port Authority
- **Baram Shipyard Agents** - Shipping Agent #1
- **Oceanic Maritime Services** - Shipping Agent #2
- **Petronas Carigali Sdn Bhd** - Oil & Gas Client #1
- **Shell Malaysia Exploration** - Oil & Gas Client #2
- **Murphy Oil Corporation** - Oil & Gas Client #3

### 2. **Users** (4 total)
| Email | Name | Role | Organization | Password |
|-------|------|------|--------------|----------|
| admin@asb.com | Sarah Ahmad | Admin | ASB | password |
| agent@baram.com | John Tan | Agent | Baram | password |
| agent@oceanic.com | Maria Santos | Agent | Oceanic | password |
| hse@asb.com | Ahmad Razak | Admin/HSE | ASB | password |

### 3. **Vessels** (6 total)
1. **MV Nautica Gamble** (IMO: 9123456) - OSV, 60.5m, Petronas
2. **Barge Alpha One** (IMO: 9234567) - Barge, 85m, Shell
3. **OSV Explorer** (IMO: 9345678) - OSV, 55m, Petronas
4. **Pacific Carrier** (IMO: 9456789) - Supply Vessel, 70m, Murphy
5. **Sea Dragon** (IMO: 9567890) - OSV, 65m, Shell
6. **Ocean Pioneer** (IMO: 9678901) - Anchor Handling Tug, 75m, Petronas

### 4. **Berths/Wharfs** (5 total)
- **MW1** - Main Wharf 1 (100m, 10m draft) - GREEN
- **MW2** - Main Wharf 2 (100m, 10m draft) - YELLOW
- **MW3** - Main Wharf 3 (120m, 12m draft) - BLUE
- **AJ1** - Alpha Jetty (80m, 8m draft) - GREEN
- **BJ1** - Bravo Jetty (90m, 9m draft) - ORANGE

### 5. **Port Calls** (6 total)
#### Currently Alongside:
- **PC-2025-001**: MV Nautica Gamble @ MW1 (Baram Agent)
- **PC-2025-002**: Barge Alpha One @ MW2 (Oceanic Agent)

#### Scheduled/Upcoming:
- **PC-2025-003**: OSV Explorer @ MW3 (arriving in 8 hours)
- **PC-2025-004**: Pacific Carrier @ AJ1 (arriving tomorrow)

#### Completed/Departed:
- **PC-2024-099**: Sea Dragon (departed 4 days ago)
- **PC-2024-098**: Ocean Pioneer (departed 6 days ago)

### 6. **Warehouses & Zones** (2 warehouses, 5 zones)
#### Main Yard A (Open Yard - 5000m³):
- **Zone A1** - General Cargo (1000m³)
- **Zone A2** - Pipes & Tubulars (1500m³)
- **Zone A3** - DG Storage (800m³) ⚠️ Dangerous Goods Allowed

#### Covered Storage B (3000m³):
- **Zone B1** - Electronics (1000m³)
- **Zone B2** - Chemicals/DG (600m³) ⚠️ Dangerous Goods Allowed

### 7. **Cargo Manifests & Items** (4 manifests, 7 items)
#### MF-2025-001 (Inbound - In Yard):
- Drill Pipes (20x 6m) - 5000kg, 120m³ → Zone A2
- Hydraulic Pumps (5 units) - 800kg, 15m³ → Zone B1
- Safety Equipment Crates - 300kg, 25m³ → Zone A1

#### MF-2025-002 (Inbound - In Yard):
- Diesel Fuel Drums (Class 3 DG) ⚠️ - 2000kg, 80m³ → Zone A3
- Lubricating Oil Barrels - 1200kg, 50m³ → Zone A1

#### MF-2025-003 (Outbound - Pending):
- Used Equipment Return - 1500kg, 60m³ → Zone A1

#### MF-2024-050 (Inbound - **AGING CARGO** 🚨):
- Abandoned Containers - 3000kg, 150m³ → Zone A1
- **Note**: This cargo is 95 days old - triggers aging alert!

### 8. **Work Permits (HSE)** (4 total)
#### Approved:
- **PTW-2025-001**: Hot Work - Welding @ MW1 (Today, 2-6 hours)
- **PTW-2025-002**: Confined Space - Tank Inspection @ Zone A3 (Today, 4-8 hours)

#### Pending:
- **PTW-2025-003**: Work at Height - Crane Maintenance @ MW2 (Tomorrow)

#### Completed:
- **PTW-2024-099**: Hot Work - Fender Replacement @ BJ1 (3 days ago)

### 9. **Service Requests** (5 total)
- **Freshwater Supply** (50 MT) - PC-2025-001 - ✅ Completed
- **Bunker Fuel** (30 MT) - PC-2025-001 - 🔄 In Progress
- **Waste Disposal** (5 M³) - PC-2025-002 - ✅ Completed
- **Freshwater Supply** (40 MT) - PC-2025-002 - ✅ Completed
- **Bunker Fuel** (25 MT) - PC-2025-003 - ⏳ Pending

### 10. **Invoices & Billing** (2 total)
#### INV-2024-099 (Shell - PAID ✅):
- Berth Occupancy (24 hours) @ RM250/hr = RM6,000
- Freshwater Supply (45 MT) @ RM80/MT = RM3,600
- Waste Disposal (6 M³) @ RM150/M³ = RM900
- Mooring Services = RM2,000
- **Total: RM15,750** - ERP Synced ✅

#### INV-2024-098 (Petronas - PENDING ⏳):
- Berth Occupancy (36 hours) @ RM250/hr = RM9,000
- Bunker Fuel Supply (50 MT) @ RM180/MT = RM9,000
- Cargo Handling (120 units) @ RM45/unit = RM5,400
- Warehouse Storage (7 days) @ RM200/day = RM1,400
- **Total: RM22,400** - Due in 25 days

### 11. **Crew Members & Transfers** (5 crew, 2 transfers)
#### Crew Database:
- Captain James Lee (Malaysian, M12345678)
- Chief Engineer Wong (Malaysian, M23456789)
- AB Seaman Raju (Indian, I34567890)
- Cook Maria Cruz (Filipino, P45678901)
- Deck Officer Ali (Malaysian, M56789012)

#### Recent Transfers:
- Captain James Lee - **Sign On** @ PC-2025-001 (2 hours ago)
- AB Seaman Raju - **Sign Off** @ PC-2025-001 (1.5 hours ago)

### 12. **IoT Sensors (Smart Port)** (5 sensors)
- **TIDE-01**: Main Channel Tide Gauge - 2.3m (Normal ✅)
- **WIND-01**: Port Wind Speed Monitor - 12.5 knots (Normal ✅)
- **FUEL-01**: Bunker Fuel Flow Meter - 15.2 MT/hr (Normal ✅)
- **WATER-01**: Freshwater Flow Meter - 8.7 MT/hr (Normal ✅)
- **TEMP-DG01**: DG Zone Temperature - 32°C (Normal ✅)

### 13. **Audit Logs** (5 recent actions)
- Sarah Ahmad created port call PC-2025-001 (3 hours ago)
- John Tan updated manifest status to in_yard (2 hours ago)
- Ahmad Razak approved hot work permit PTW-2025-001 (1 hour ago)
- Sarah Ahmad generated invoice INV-2024-099 (3 days ago)
- Maria Santos requested freshwater service (45 minutes ago)

---

## 🎬 Demo Flow for ASB Management

### 1. **Dashboard** (`/dashboard`)
- Show live port activity
- 2 vessels currently alongside
- 2 upcoming arrivals
- Revenue metrics from invoices
- Active safety permits

### 2. **Berth Planner** (`/`)
- Visual timeline showing:
  - MW1: MV Nautica Gamble (alongside now)
  - MW2: Barge Alpha One (alongside now)
  - MW3: OSV Explorer (arriving in 8 hours)
- Demonstrate drag-and-drop optimization

### 3. **Vessel Registry** (`/vessels`)
- Show 6 vessels from major O&G operators
- Filter by client (Petronas, Shell, Murphy)
- View vessel specifications

### 4. **Cargo Logistics** (`/cargo/manifests`)
- Show 4 manifests (inbound/outbound)
- **Highlight**: Dangerous Goods (DG) tracking
- **Highlight**: Aging cargo alert (95 days old!)
- Click manifest to see QR codes for tracking

### 5. **Warehouse Map** (`/warehouse/map`)
- Visual heatmap showing zone utilization
- **Zone A1**: Medium density (general cargo)
- **Zone A2**: High density (drill pipes)
- **Zone A3**: DG storage with safety indicators
- **Highlight**: Aging cargo sidebar

### 6. **HSE Safety Console** (`/hse/permits`)
- Show 4 permits (approved, pending, completed)
- **Demonstrate**: Clash detection
  - Try to create conflicting hot work permit
  - System blocks due to location/time conflict
- Show audit trail for safety compliance

### 7. **Billing Module** (`/billing`)
- Show 2 invoices totaling RM38,150
- **INV-2024-099**: Paid & ERP Synced ✅
- **INV-2024-098**: Pending payment
- Demonstrate automatic calculation from services

### 8. **Crew Terminal** (`/terminal`)
- Show crew transfer history
- Scan simulation for sign-on/sign-off
- Immigration compliance tracking

### 9. **Analytics Dashboard** (`/analytics`)
- Port utilization trends
- Revenue by client
- Service request patterns
- Safety permit statistics

### 10. **Mobile Operations** (`/ops`)
- Show mobile-friendly interface
- Ground staff can update status in real-time
- QR code scanning for cargo tracking

---

## 🔑 Key Selling Points for ASB Management

### 1. **End-to-End Visibility**
- Track cargo from gate-in to gate-out
- QR codes link physical items to digital records
- Real-time status updates

### 2. **Automated Safety (HSE)**
- Digital Permit-to-Work system
- **Clash Detection**: Prevents conflicting hazardous activities
- Immutable audit trail for compliance

### 3. **Revenue Assurance**
- Zero-leakage billing
- Automatic invoice generation from operations
- ERP integration ready

### 4. **Warehouse Optimization**
- Visual heatmaps show utilization
- **Aging cargo alerts** prevent yard clogging
- DG (Dangerous Goods) segregation enforcement

### 5. **Smart Port (IoT Ready)**
- Real-time environmental monitoring
- Flow meters for accurate billing
- Predictive maintenance alerts

### 6. **Client Self-Service**
- Agents can view their port calls
- Request services digitally
- Track cargo in real-time

---

## 🚀 Running the Demo

### Option 1: Use Existing Data
The seeder uses `updateOrCreate`, so running it multiple times is safe. Current data should already be in the database.

### Option 2: Fresh Start
```bash
php artisan migrate:fresh --seed
```

### Option 3: Add More Data
Run the seeder again to update/add data:
```bash
php artisan db:seed
```

---

## 📞 Support

For any issues during the demo:
1. Check that `php artisan serve` is running
2. Login with: `admin@asb.com` / `password`
3. All features are accessible from the main navigation

**Good luck with your ASB Management presentation!** 🎯
