# PortFlow: Complete Port & Supply Base Management System
## Version 2.0 | Digital Transformation for Maritime Logistics
**Last Updated:** December 2025

---

## 📋 Table of Contents
1. [Executive Summary](#executive-summary)
2. [Problem Statement & Solution](#problem-statement--solution)
3. [User Roles & Access Control](#user-roles--access-control)
4. [Complete Feature Guide](#complete-feature-guide)
5. [URS - User Requirement Specification](#urs---user-requirement-specification)
6. [SRS - Software Requirement Specification](#srs---software-requirement-specification)
7. [User Stories](#user-stories)
8. [Commercial Licensing](#commercial-licensing)
9. [ROI Analysis](#roi-analysis)
10. [Technical Architecture](#technical-architecture)
11. [Security & Compliance](#security--compliance)

---

## 🎯 Executive Summary

**PortFlow** is a comprehensive port management system designed to solve critical operational challenges faced by supply bases, offshore support bases (OSB), and maritime terminals. Built on modern web technologies, it provides real-time visibility, automated billing, safety compliance, and resource optimization.

### Key Metrics:
- **15-20% Revenue Increase** through billing accuracy
- **30% Faster Turnaround** via real-time tracking
- **90% Risk Reduction** through automated safety compliance
- **100% Audit Readiness** with immutable logs

---

## 🔴 Problem Statement & Solution

### The Maritime Operations Crisis

Modern ports face critical challenges that result in millions in lost revenue, safety incidents, and operational inefficiencies:

| **Problem** | **Business Impact** | **PortFlow Solution** |
|-------------|---------------------|----------------------|
| **Manual Billing Errors** | RM 500K+ annual revenue leakage from rounded/missed charges | **Automated Real-Time Billing**: Server-logged timestamps calculate exact berthing hours, yard storage days, and equipment rental periods |
| **Berth Scheduling Conflicts** | Double-bookings cause vessel delays costing RM 50K per incident | **Visual Berth Planner**: Drag-and-drop scheduler with collision detection prevents overlapping assignments |
| **Lost Cargo & Manifests** | Paper manifests get lost; 15% of cargo items have location disputes | **Digital Manifest System**: QR-coded tracking from vessel → yard → discharge with real-time location updates |
| **Overdue Operations** | Vessels overstay without notification; agents dispute charges | **Automated Notification System**: 30-minute warnings before deadlines + overdue alerts to agents and admins |
| **Equipment Underutilization** | 40% of assets sit idle; no visibility on availability | **Resource Marketplace**: Real-time asset booking with live availability and automated billing |
| **Safety Compliance Gaps** | Expired certifications go unnoticed until audits/incidents | **Safety Interlocks**: System blocks booking of expired equipment; auto-alerts for permit deadlines |
| **Audit Nightmares** | Weeks spent compiling records for Petronas/Shell audits | **Immutable Audit Trail**: One-click export of all operations with timestamps and user accountability |
| **Role Confusion** | HSE officers see irrelevant menus; agents access admin functions | **Role-Based Access Control (RBAC)**: Custom menus per role (Admin/Agent/HSE/Asset Manager) |
| **Communication Delays** | Agents unaware of approaching deadlines until it's too late | **Notification Centre**: Real-time alerts for warnings, overdues, and critical operations |
| **Billing Disputes** | "We only used it for 2 hours, why charge full day?" | **Granular Time Tracking**: Hourly/daily billing based on actual check-in/out times with photo evidence |

---

## 🔑 User Roles & Access Control

PortFlow implements **Role-Based Access Control (RBAC)** to ensure users only see relevant features and data.

### Default User Accounts

| Role | Email | Password | Access Level | Primary Functions |
|------|-------|----------|--------------|-------------------|
| **Port Administrator** | `admin@asb.com` | `password` | Full System Access | Berth planning, billing, analytics, user management, system health |
| **HSE Officer** | `hse@asb.com` | `password` | Safety & Assets | Permit approvals, incident management, asset inventory, safety compliance |
| **Shipping Agent** | `agent@baram.com` | `password` | Client Portal | Vessel registration, berth requests, cargo manifests, financial dashboard |
| **Asset Manager** | (Created via User Management) | (Set by admin) | Asset Operations Only | Asset inventory, equipment bookings, maintenance logs |

### Role-Specific Menu Visibility

#### **Administrator** (Full Access)
- ✅ Command Centre Dashboard
- ✅ Berth Planner
- ✅ Analytics
- ✅ Vessels & Cargo Logistics
- ✅ Yard Operations
- ✅ Crew Terminal
- ✅ GIS Map View
- ✅ Gate Scanner
- ✅ Mobile Ops
- ✅ Asset Inventory & Rentals
- ✅ Space Lease Management
- ✅ Wharf Registry
- ✅ Safety & Compliance (Permits, Incidents)
- ✅ Billing & Invoices
- ✅ Agent Registry
- ✅ System Health
- ✅ Audit Trail
- ✅ **User Management** (NEW)
- ✅ Notifications

#### **HSE Officer** (Safety-Focused)
- ❌ Command Centre (hidden)
- ❌ Berth Planner (hidden)
- ❌ Vessels & Cargo (hidden)
- ✅ **Asset Inventory** (for safety inspections)
- ✅ **Space Lease** (for compliance checks)
- ✅ Rentals & Booking
- ✅ **Permit to Work** (primary function)
- ✅ **Safety Intel** (incident management)
- ❌ Billing (hidden)
- ✅ Notifications

#### **Asset Manager** (Equipment-Only)
- ❌ Command Centre (hidden)
- ❌ Berth Planner (hidden)
- ❌ Vessels & Cargo (hidden)
- ❌ Yard Operations (hidden)
- ✅ **Asset Inventory** (primary function)
- ✅ **Space Lease**
- ✅ **Rentals & Booking**
- ✅ Wharf Registry
- ❌ Safety & Compliance (hidden)
- ❌ Billing (hidden)
- ✅ Notifications

#### **Shipping Agent** (Client View)
- ✅ **Agent Portal** (dedicated interface)
- ✅ Vessel Registration
- ✅ Berth Requests
- ✅ Cargo Manifests
- ✅ Financial Dashboard (live exposure)
- ✅ Resource Marketplace
- ✅ Notifications
- ❌ Admin Functions (all hidden)

---

## 📚 Complete Feature Guide

### 🚢 **1. Berth Planner** (Admin Only)
**Problem Solved:** Double-bookings and scheduling conflicts

**Features:**
- Visual grid showing all berths and time slots
- Drag-and-drop port call assignments
- Collision detection prevents overlapping
- Color-coded status: Requested (Yellow), Approved (Blue), Alongside (Green), Completed (Gray)
- Click empty slot to create new port call
- Setting ATB (Actual Time Berthing) starts billing clock

**Workflow:**
1. Agent submits berth request via Agent Portal
2. Admin sees request in Berth Planner (yellow highlight)
3. Admin approves and assigns berth
4. Vessel arrives → Admin sets ATB → Billing starts
5. Vessel departs → Admin sets ATD → Invoice auto-generated

---

### 📊 **2. Command Centre Dashboard** (Admin & Agent)
**Problem Solved:** Lack of real-time operational visibility

**Admin View:**
- **Live Port Charges**: Real-time calculation of all active operations
  - Marine/Berthing: RM X (Y active vessels)
  - Yard/Storage: RM X (Y items stored)
  - Assets/Equipment: RM X (Y active rentals)
- **KPI Cards**: Active vessels, pending requests, unpaid invoices
- **Recent Activity**: Latest port calls, cargo movements, bookings
- **Pending Approvals**: Asset bookings, berth requests

**Agent View:**
- **Live Exposure Dashboard**: Real-time charges accumulating
- **Fleet Activity**: All vessels (live, scheduled, historical)
- **Financial Summary**: Outstanding invoices, payment status

**NEW:** Both dashboards now use **BillingService** for consistent real-time calculations (no more discrepancies between pages)

---

### 🔔 **3. Notification Centre** (All Users) ⭐ NEW
**Problem Solved:** Agents unaware of approaching deadlines; admins can't pursue overdues proactively

**Features:**
- **30-Minute Warnings**: Automatic alerts before:
  - Vessel ETD (Expected Time of Departure)
  - Equipment rental return deadline
  - Yard storage penalty threshold (25 days)
- **Overdue Alerts**: Notifications when:
  - Vessel exceeds scheduled departure
  - Equipment not returned on time (late fees apply)
  - Cargo stored beyond 30 days (penalty charges)
- **Filter Tabs**: All, Unread, Warnings, Overdue
- **Visual Indicators**: Color-coded by urgency (Yellow = Warning, Red = Overdue)
- **Category Icons**: ⚓ Marine, 📦 Yard, 🚜 Assets
- **Admin Mirroring**: All agent alerts copied to admin for follow-up
- **Mark as Read**: Individual or bulk actions

**Workflow:**
1. System checks operations every time Notification Centre is accessed
2. Creates warning 30 minutes before deadline
3. Creates overdue alert when deadline passes
4. Agent sees notification in sidebar bell icon (red badge)
5. Admin receives copy to pursue payment/action
6. Click notification to view details and mark as read

**Business Value:** Eliminates billing disputes ("we didn't know it was overdue") and ensures proactive revenue collection

---

### 👥 **4. User Management** (Admin Only) ⭐ NEW
**Problem Solved:** Manual user provisioning; no role-based menu control

**Features:**
- **Create/Edit/Delete Users**: Full CRUD operations
- **Role Assignment**: Admin, Agent, HSE Officer, Asset Manager
- **Organization Linking**: Assign agents to specific companies
- **Search & Filter**: Find users by name or email
- **Password Management**: Reset passwords for users
- **Audit Trail**: Track user creation and modifications

**Workflow:**
1. Admin navigates to System & Admin → User Management
2. Click "Add New User"
3. Enter name, email, password
4. Select role (determines menu visibility)
5. For agents: Select organization
6. Save → User can immediately log in with assigned role

**Security:** Admins/HSE/Asset Managers are "internal" (no organization), Agents must be linked to an organization

---

### 🚢 **5. Vessels & Fleet Registry** (Admin & Agent)
**Problem Solved:** Duplicate vessel registrations; incomplete vessel data

**Features:**
- **Global Fleet Database**: All authorized vessels
- **Vessel Details**: Name, IMO, Flag, Type (OSV/Tanker/Supply), GRT, Agent
- **Search**: By name, IMO, or flag
- **Agent Portal Integration**: Agents can register their own vessels
- **Validation**: Prevents duplicate IMO numbers

---

### 📦 **6. Cargo Logistics & Manifests** (Admin & Agent)
**Problem Solved:** Lost manifests; cargo location disputes

**Features:**
- **Digital Manifest Submission**: Agents upload cargo lists
- **QR Code Tracking**: Each cargo item gets unique QR code
- **Status Tracking**: Pending → Received → Stored → Discharged
- **Zone Assignment**: Cargo allocated to specific warehouse zones
- **Weight & Volume**: Tracked for billing and capacity planning
- **Dangerous Goods (DG) Class**: Flagged for safety compliance
- **Aging Reports**: How long cargo has been in yard

**Workflow:**
1. Agent creates manifest for incoming vessel
2. Admin reviews and approves
3. Cargo received → QR codes generated
4. Items stored in assigned zones
5. Real-time location visible on GIS map
6. Discharge logged when cargo leaves

---

### 🗺️ **7. GIS Map & Yard Operations** (Admin & Subscribed Agents)
**Problem Solved:** No spatial awareness of cargo locations

**Features:**
- **Interactive Warehouse Map**: Visual layout of storage zones
- **Cargo Icons**: Hover to see tracking number, description, days stored
- **Zone Utilization**: Color-coded by capacity (Red = >80%, Green = optimal)
- **Spatial Lease Management**: Allocate specific zones to clients
- **Heatmap Analytics**: Identify congested vs. underutilized areas

---

### 🔧 **8. Asset Inventory & Rentals** (Admin, HSE, Asset Manager)
**Problem Solved:** Equipment underutilization; billing leakage; expired certifications

**Features:**
- **Asset Database**: Cranes, forklifts, generators, containers, etc.
- **Real-Time Availability**: See which assets are free/booked/under maintenance
- **Booking System**: Agents request equipment via Resource Marketplace
- **Automated Billing**: Hourly/daily rates calculated from check-in/out times
- **Safety Interlocks**: System blocks booking if:
  - Safety certificate expired
  - Asset under maintenance
  - Asset already booked
- **Maintenance Logs**: Track repairs, inspections, costs
- **QR Code Generation**: Each asset has scannable tag for mobile ops
- **Photo Evidence**: Mandatory photos at check-out/in for damage disputes

**Workflow:**
1. Agent browses Resource Marketplace
2. Selects asset (e.g., 250T Crane)
3. Chooses start/end time
4. System checks availability and safety status
5. Booking approved → Asset reserved
6. Ground crew checks out asset (photo required)
7. Billing clock starts
8. Asset returned → Photo taken → Billing stops
9. Invoice auto-generated

---

### 🛑 **9. Safety & Compliance** (All Users)

#### **9.1 Permit to Work** (HSE Primary)
**Problem Solved:** Unsafe work proceeds without proper authorization

**Features:**
- **Digital Permit Requests**: Hot work, confined space, height work, etc.
- **Risk Assessment**: Mandatory hazard identification
- **Approval Workflow**: HSE officer reviews and approves/rejects
- **Expiry Tracking**: Permits auto-expire after set duration
- **Dashboard**: Live view of active permits

#### **9.2 Safety Intel (Incident Management)** (HSE Primary)
**Problem Solved:** Incidents not reported; no trend analysis

**Features:**
- **Incident Reporting**: Near-miss, injury, environmental, property damage
- **Severity Classification**: Minor, Moderate, Severe, Critical
- **Investigation Tracking**: Assign investigators, set deadlines
- **Photo Evidence**: Upload incident scene photos
- **Status Workflow**: Open → Under Investigation → Closed
- **Analytics**: Incident trends by type, location, time

---

### 💰 **10. Billing & Invoices** (Admin & Agent)
**Problem Solved:** Manual billing errors; revenue leakage; billing disputes

**Features:**
- **Automated Invoice Generation**: Triggered by:
  - Vessel departure (ATD set)
  - Asset return (check-in completed)
  - Yard storage milestones
- **Consolidated Billing**: Single invoice for:
  - Marine (berthing fees)
  - Yard (storage fees)
  - Assets (equipment rental)
- **Real-Time Exposure**: Agents see live charges accumulating
- **Status Tracking**: Draft → Pending → Paid
- **PDF Export**: Professional invoice printouts
- **Payment Recording**: Admin marks invoices as paid

**Billing Logic:**
- **Berthing**: Calculated from ATB to ATD (hourly/daily rates)
- **Yard Storage**: Daily rate × days stored (penalties after 30 days)
- **Assets**: Hourly/daily rate × duration (check-out to check-in)

**NEW:** Dashboard and Agent Portal now use identical **BillingService** for consistent calculations

---

### 📱 **11. Mobile Ops** (Admin Only - Field Crew)
**Problem Solved:** Paper-based handover; no field access to asset data

**Features:**
- **QR Code Scanner**: Scan asset tags to access details
- **Asset Check-Out/In**: Digital handover with photo evidence
- **Maintenance Logging**: Record repairs on-site
- **Offline Capability**: Works without internet (PWA)
- **Touch-Optimized**: Designed for tablets and phones

---

### 🚪 **12. Gate Scanner** (Admin Only)
**Problem Solved:** Manual gate logs; security gaps

**Features:**
- **QR Code Scanning**: Verify crew, visitors, cargo
- **Access Control**: Check authorization before entry
- **Time Logging**: Record entry/exit times
- **Visitor Management**: Track all personnel on-site

---

### 👷 **13. Crew Terminal** (Admin Only)
**Problem Solved:** ISPS compliance; crew change tracking

**Features:**
- **Crew Database**: All personnel on vessels
- **Crew Changes**: Log arrivals/departures
- **Shore Leave**: Track crew movements
- **Visitor Access**: Manage non-crew personnel
- **Emergency Muster**: Know exactly who's on-site

---

### 📈 **14. Analytics Dashboard** (Admin Only)
**Problem Solved:** No executive intelligence for decision-making

**Features:**
- **Revenue Heatmap**: Which clients generate highest margin
- **Berth Occupancy**: Utilization rates by berth
- **Mean Turnaround Time (MTT)**: Vessel efficiency metrics
- **Asset Utilization**: Which equipment is most profitable
- **Trend Analysis**: Month-over-month comparisons

---

### 🏢 **15. Agent Registry** (Admin Only)
**Problem Solved:** Disorganized client database

**Features:**
- **Organization Management**: All shipping agents/clients
- **Contact Details**: Email, phone, address
- **Subscription Tracking**: Warehouse access, special services
- **User Association**: Link users to organizations
- **Financial Summary**: Total invoices, outstanding balance

---

### 🔍 **16. System Health** (Admin Only)
**Problem Solved:** No visibility into system performance

**Features:**
- **Server Metrics**: CPU, memory, disk usage
- **Database Status**: Connection health, query performance
- **Error Logs**: Recent system errors
- **Uptime Tracking**: System availability percentage

---

### 📜 **17. Audit Trail** (Admin Only)
**Problem Solved:** No accountability; audit nightmares

**Features:**
- **Immutable Logs**: Every action logged with:
  - User ID
  - IP Address
  - Timestamp
  - Action description
- **Search & Filter**: Find specific events
- **Export**: One-click compliance reports
- **Retention**: Logs never deleted (regulatory requirement)

---

## 📋 URS - User Requirement Specification

### What the Business Needs to Achieve

#### **URS-001: Real-Time Berth Visibility**
**Problem:** Manual scheduling causes double-bookings and vessel delays  
**Requirement:** System must provide visual "digital twin" of wharf with drag-and-drop scheduling and collision detection  
**Success Criteria:** Zero double-bookings; 95% berth utilization

#### **URS-002: Automated Billing Accuracy**
**Problem:** Manual billing loses RM 500K+ annually through rounding errors and missed charges  
**Requirement:** System must auto-calculate charges based on server-logged timestamps (ATB, ATD, check-in/out)  
**Success Criteria:** 100% billing accuracy; 15-20% revenue increase

#### **URS-003: Proactive Deadline Management**
**Problem:** Agents unaware of approaching deadlines; disputes over overdue charges  
**Requirement:** System must send automated warnings 30 minutes before deadlines and overdue alerts  
**Success Criteria:** 90% reduction in billing disputes; 100% agent notification rate

#### **URS-004: Role-Based Access Control**
**Problem:** Users see irrelevant menus; security risks from over-permissioned access  
**Requirement:** System must restrict menu visibility and data access based on user role (Admin/Agent/HSE/Asset Manager)  
**Success Criteria:** Each role sees only relevant features; zero unauthorized access

#### **URS-005: Asset Lifecycle Management**
**Problem:** Equipment booked despite expired certifications; no maintenance tracking  
**Requirement:** System must block booking of expired/under-maintenance assets and track full maintenance history  
**Success Criteria:** Zero safety incidents from expired equipment; 100% maintenance compliance

#### **URS-006: Cargo Traceability**
**Problem:** 15% of cargo items have location disputes; paper manifests get lost  
**Requirement:** System must provide QR-coded tracking from vessel → yard → discharge with real-time location  
**Success Criteria:** 100% cargo traceability; zero lost manifests

#### **URS-007: Safety Compliance Automation**
**Problem:** Expired permits and unsafe work go unnoticed until incidents occur  
**Requirement:** System must auto-block unsafe operations and alert HSE officers of compliance gaps  
**Success Criteria:** 90% reduction in safety incidents; 100% permit compliance

#### **URS-008: Audit Readiness**
**Problem:** Weeks spent compiling records for Petronas/Shell audits  
**Requirement:** System must maintain immutable audit trail with one-click export  
**Success Criteria:** Audit preparation time reduced from weeks to hours

#### **URS-009: Mobile Field Operations**
**Problem:** Paper-based handover causes disputes; no field access to data  
**Requirement:** System must provide mobile-optimized interface with QR scanning and photo evidence  
**Success Criteria:** 100% digital handover; zero paper forms

#### **URS-010: Financial Transparency**
**Problem:** Agents surprised by invoice amounts; no visibility into accumulating charges  
**Requirement:** System must show agents real-time exposure dashboard with live charge calculations  
**Success Criteria:** Zero invoice disputes; 95% agent satisfaction

---

## ⚙️ SRS - Software Requirement Specification

### How the Software Delivers the URS

#### **SRS-001: Architecture**
**Technology Stack:** TALL Stack (Tailwind CSS, Alpine.js, Laravel 12, Livewire 3)  
**Rationale:** High-reactivity without page reloads; real-time UI updates; mobile-first responsive design

#### **SRS-002: Database Design**
**Engine:** MySQL/MariaDB with transactional integrity  
**Schema:**
- `users` - Authentication and role assignment
- `organizations` - Client/agent companies
- `port_calls` - Vessel berthing records
- `cargo_items` - Manifest tracking
- `port_assets` - Equipment inventory
- `asset_bookings` - Rental reservations
- `invoices` - Billing records
- `notifications` - Alert system (NEW)
- `safety_incidents` - HSE tracking
- `audit_logs` - Immutable event log

#### **SRS-003: Security Layer**
**Authentication:** Laravel Breeze with 2FA support  
**Authorization:** Middleware-based RBAC (`role:admin,agent,hse,asset_manager`)  
**Rate Limiting:** 6 requests/minute on login to prevent brute-force  
**Encryption:** HTTPS enforced; passwords hashed with bcrypt  
**Headers:** CSP, HSTS, X-Frame-Options, X-Content-Type-Options

#### **SRS-004: Billing Engine**
**Service:** `App\Services\BillingService`  
**Logic:**
- **Marine:** `(ATD - ATB) × berth_rate_per_hour`
- **Yard:** `(current_date - received_date) × zone_rate_per_day`
- **Assets:** `(check_in_time - check_out_time) × asset_rate_per_hour`
- **Penalties:** 2× rate after 30 days storage
**Triggers:** Automated on ATD set, asset check-in, daily cron job

#### **SRS-005: Notification Engine**
**Service:** `App\Services\NotificationManager`  
**Logic:**
- Check all active operations on page load
- Create warning if deadline within 30 minutes
- Create overdue alert if deadline passed
- Prevent duplicate notifications (1-hour cooldown for warnings, 24-hour for overdues)
- Mirror all agent alerts to admin users
**Storage:** `notifications` table with `is_read` flag

#### **SRS-006: Role-Based UI Rendering**
**Implementation:** Blade directives in `app.blade.php`
```php
@if(auth()->user()->role === 'admin') // Show menu
@if(!in_array(auth()->user()->role, ['hse', 'asset_manager'])) // Hide menu
```
**Menu Visibility Matrix:**
- Admin: All menus
- HSE: Assets, Safety, Notifications only
- Asset Manager: Assets only
- Agent: Agent Portal, Notifications only

#### **SRS-007: Real-Time Calculations**
**Implementation:** Both Dashboard and Agent Portal use `BillingService->generateInvoice()`  
**Rationale:** Ensures consistent charges across all pages; eliminates discrepancies  
**Update Frequency:** On-demand (calculated when page loads)

#### **SRS-008: Mobile Optimization**
**PWA:** Installable as native app  
**Responsive:** Tailwind breakpoints (sm, md, lg, xl)  
**Offline:** QR code generation works without internet  
**Touch:** Large tap targets, swipe gestures

#### **SRS-009: Data Integrity**
**Transactions:** All financial operations wrapped in DB transactions  
**Validation:** Server-side validation on all inputs  
**Constraints:** Foreign keys enforce referential integrity  
**Backups:** Daily automated backups (implementation-dependent)

#### **SRS-010: Performance**
**Caching:** Query results cached where appropriate  
**Eager Loading:** Relationships loaded with `with()` to prevent N+1 queries  
**Pagination:** Large datasets paginated (15 items/page)  
**Indexing:** Database indexes on frequently queried columns

---

## 📖 User Stories

### The Human Perspective - Problems to Solutions

#### **Story 1: The Revenue Leakage Problem**
**As a Port Administrator,**  
I was losing RM 50,000 monthly because manual billing rounded vessel berthing times to the nearest day, missing fractional hours.  
**I want** the system to auto-calculate charges based on exact server timestamps,  
**So that** I capture every billable hour and increase revenue by 15-20%.  
**Solution:** PortFlow's BillingService logs ATB/ATD to the second and calculates `hours × rate` automatically.

---

#### **Story 2: The Double-Booking Disaster**
**As a Port Administrator,**  
I accidentally assigned two vessels to the same berth because I was using Excel spreadsheets, causing a RM 50K delay penalty.  
**I want** a visual scheduler that prevents overlapping bookings,  
**So that** I never face double-booking incidents again.  
**Solution:** PortFlow's Berth Planner has collision detection that blocks overlapping assignments.

---

#### **Story 3: The Overdue Dispute**
**As a Shipping Agent,**  
I was charged RM 20,000 for "overstaying" but I was never notified the vessel was overdue.  
**I want** to receive automatic warnings 30 minutes before my vessel's scheduled departure,  
**So that** I can avoid late fees and disputes.  
**Solution:** PortFlow's Notification Centre sends 30-min warnings and overdue alerts to agents and admins.

---

#### **Story 4: The Lost Cargo Nightmare**
**As a Shipping Agent,**  
15% of my cargo items had location disputes because paper manifests got lost or misfiled.  
**I want** digital tracking with QR codes for every cargo item,  
**So that** I always know exactly where my cargo is located.  
**Solution:** PortFlow generates unique QR codes for each cargo item with real-time location on GIS map.

---

#### **Story 5: The Expired Equipment Incident**
**As an HSE Officer,**  
A crane with an expired safety certificate was booked and used, resulting in a RM 2M incident.  
**I want** the system to automatically block booking of expired equipment,  
**So that** unsafe operations never proceed.  
**Solution:** PortFlow's Safety Interlocks check certificate expiry and block bookings with visual "CERT. EXPIRED" badges.

---

#### **Story 6: The Audit Panic**
**As a Port Administrator,**  
I spent 3 weeks compiling records for a Petronas audit, digging through paper files and Excel sheets.  
**I want** one-click export of all operations with timestamps and user accountability,  
**So that** I'm always audit-ready.  
**Solution:** PortFlow's Audit Trail logs every action with user/IP/timestamp and exports in seconds.

---

#### **Story 7: The Equipment Handover Dispute**
**As a Ground Crew Supervisor,**  
An agent claimed our forklift was damaged during rental, but we had no proof of its condition at check-out.  
**I want** mandatory photo evidence at check-out and check-in,  
**So that** I have visual proof to resolve damage disputes.  
**Solution:** PortFlow's Mobile Ops requires photos at both handover points, creating an immutable record.

---

#### **Story 8: The Idle Asset Problem**
**As a Port Administrator,**  
40% of my cranes and forklifts sat idle because agents didn't know they were available.  
**I want** a real-time marketplace showing available equipment,  
**So that** I maximize asset utilization and rental revenue.  
**Solution:** PortFlow's Resource Marketplace shows live availability with instant booking.

---

#### **Story 9: The Role Confusion**
**As an HSE Officer,**  
I was overwhelmed by irrelevant menus (billing, analytics, berth planning) that I never use.  
**I want** to see only safety-related features (permits, incidents, asset inspections),  
**So that** I can focus on my core responsibilities.  
**Solution:** PortFlow's RBAC shows HSE officers only Assets, Safety, and Notifications menus.

---

#### **Story 10: The Billing Surprise**
**As a Shipping Agent,**  
I received a RM 100,000 invoice with no prior warning about accumulating charges.  
**I want** to see real-time exposure as charges accumulate,  
**So that** I'm never surprised by invoice amounts.  
**Solution:** PortFlow's Agent Portal shows live Financial Dashboard with running totals for marine, yard, and assets.

---

#### **Story 11: The User Provisioning Bottleneck**
**As a Port Administrator,**  
I had to manually edit database records to create new users and assign roles.  
**I want** a user-friendly interface to create/edit users and assign roles,  
**So that** I can onboard new agents and staff in minutes.  
**Solution:** PortFlow's User Management provides full CRUD with role assignment and organization linking.

---

#### **Story 12: The Deadline Blindness**
**As a Port Administrator,**  
I had no way to know which agents had overdue operations until I manually checked invoices.  
**I want** automatic notifications when agents exceed deadlines,  
**So that** I can pursue payment proactively.  
**Solution:** PortFlow's Notification Centre sends overdue alerts to both agents and admins for follow-up.

---

## 💼 Commercial Licensing

### 🏢 Single License (Enterprise)
**For:** Single supply base or terminal  
**Cost:** RM 100,000/year (SaaS) or RM 550,000 (Perpetual License)  
**Includes:**
- Unlimited users
- 24/7 technical support
- Monthly feature updates
- Professional deployment
- Data migration assistance
- Training for 10 users

### 🏢 Reseller / OEM / White-Label
**For:** Maritime consultants, tech providers, port authorities managing multiple terminals  
**Cost:** RM 495,000/year (Bulk License Pack - Up to 5 Ports)  
**Includes:**
- 100% markup potential (set your own pricing)
- Full white-labeling (your logo, colors, branding)
- API access for custom integrations
- Priority feature requests
- Dedicated account manager

### 💡 Custom Enterprise
**For:** Large port operators (10+ terminals)  
**Cost:** Contact for quote  
**Includes:**
- On-premise deployment option
- Custom feature development
- Integration with existing ERP/SAP systems
- Dedicated development team
- SLA guarantees (99.9% uptime)

---

## 💰 ROI Analysis

### Investment Recovery Timeline

#### **Months 1-3: System Familiarization**
- **Revenue Uplift:** 5-10% from discovered billing leakage
- **Efficiency Gains:** 15% reduction in manual data entry
- **Cost Savings:** RM 30,000 in eliminated overtime for billing staff

#### **Months 4-6: Full Investment Recovery (Break-Even)**
- **Revenue Uplift:** 15% from accurate billing and reduced disputes
- **Efficiency Gains:** 25% faster turnaround (MTT improvement)
- **Cost Savings:** RM 100,000 in prevented incidents (safety compliance)
- **Total Savings:** RM 200,000+ (SaaS license paid off)

#### **Month 12+: Pure Profit Scaling**
- **Revenue Uplift:** 20% sustained through optimized berth occupancy
- **Efficiency Gains:** 30% faster operations (real-time tracking)
- **Cost Savings:** RM 500,000 annually in prevented revenue leakage
- **ROI:** 400-500% annually

### Quantified Benefits

| Benefit Category | Annual Value | How PortFlow Delivers |
|------------------|--------------|----------------------|
| **Revenue Recovery** | RM 500,000 | Automated billing captures every hour/day |
| **Incident Prevention** | RM 2,000,000 | Safety interlocks block unsafe operations |
| **Efficiency Gains** | RM 300,000 | 30% faster turnaround = 30% more throughput |
| **Audit Savings** | RM 50,000 | Weeks → hours for compliance reporting |
| **Dispute Resolution** | RM 100,000 | Photo evidence eliminates damage claims |
| **Asset Utilization** | RM 200,000 | 40% idle → 80% utilized equipment |
| **TOTAL ANNUAL VALUE** | **RM 3,150,000** | **31× ROI on SaaS license** |

---

## 🛠️ Technical Architecture

### Core Technology Stack

#### **Backend Framework**
- **Laravel 12**: Enterprise PHP framework
- **Eloquent ORM**: Database abstraction
- **Middleware**: RBAC, rate limiting, CORS
- **Queues**: Background job processing
- **Scheduler**: Automated tasks (billing, notifications)

#### **Frontend Stack**
- **Livewire 3**: Real-time reactive components
- **Alpine.js**: Lightweight JavaScript interactivity
- **Tailwind CSS**: Utility-first styling
- **Blade Templates**: Server-side rendering

#### **Database**
- **MySQL 8.0 / MariaDB 10.6**: Transactional integrity
- **Indexes**: Optimized query performance
- **Foreign Keys**: Referential integrity
- **Migrations**: Version-controlled schema

#### **Services Architecture**
```
App\Services\
├── BillingService.php          # Invoice generation
├── WarehouseBillingService.php # Yard storage calculations
├── NotificationManager.php     # Alert system (NEW)
└── NotificationService.php     # Pending task counts
```

#### **Security Layer**
- **Authentication**: Laravel Breeze + 2FA
- **Authorization**: Policy-based + Middleware RBAC
- **Encryption**: bcrypt passwords, HTTPS enforced
- **Headers**: CSP, HSTS, X-Frame-Options
- **Rate Limiting**: Throttle middleware

#### **Deployment**
- **Server**: Linux (Ubuntu 22.04 LTS recommended)
- **Web Server**: Nginx or Apache
- **PHP**: 8.2+
- **Composer**: Dependency management
- **Node.js**: Asset compilation (Vite)

---

## 🛡️ Security & Compliance

### Perimeter Defense

#### **Network Security**
- **HTTPS Only**: TLS 1.3 encryption
- **CSP**: Content Security Policy blocks XSS
- **HSTS**: HTTP Strict Transport Security
- **Firewall**: IP whitelisting for admin access

#### **Application Security**
- **Input Validation**: Server-side validation on all forms
- **SQL Injection**: Eloquent ORM prevents raw queries
- **XSS Protection**: Blade escaping by default
- **CSRF Protection**: Token-based form validation

### Identity & Access Management

#### **Authentication**
- **2FA**: Time-based OTP (TOTP) support
- **Password Policy**: Minimum 8 characters, complexity enforced
- **Breach Detection**: Check against known leaked passwords
- **Session Management**: Secure cookies, timeout after 2 hours idle

#### **Authorization**
- **RBAC**: Role-based menu visibility and data access
- **Middleware**: Route protection (`role:admin,agent`)
- **Policies**: Model-level authorization checks
- **Audit Trail**: Every action logged with user/IP/timestamp

### Data Protection

#### **Encryption**
- **At Rest**: Database encryption (implementation-dependent)
- **In Transit**: TLS 1.3 for all connections
- **Passwords**: bcrypt hashing (cost factor 12)
- **Sensitive Data**: Encrypted columns for PII

#### **Backup & Recovery**
- **Daily Backups**: Automated database dumps
- **Retention**: 30-day backup history
- **Disaster Recovery**: Restore procedures documented
- **Testing**: Quarterly backup restoration tests

### Compliance

#### **Audit Readiness**
- **Immutable Logs**: All actions recorded in `audit_logs`
- **Timestamp Precision**: Microsecond accuracy
- **User Accountability**: User ID + IP address logged
- **Export**: One-click compliance reports (CSV/PDF)

#### **Regulatory Alignment**
- **ISPS Code**: Crew terminal for personnel tracking
- **MARPOL**: Environmental incident reporting
- **OSHA**: Safety permit and incident management
- **ISO 9001**: Quality management through audit trails

---

## 📞 Support & Contact

**Technical Support:** support@labuan.dev  
**Sales Inquiries:** sales@labuan.dev  
**Phone:** +60 19-869 1289  
**Documentation:** This manual + in-app help tooltips

**Business Hours:** Monday-Friday, 9:00 AM - 6:00 PM (GMT+8)  
**Emergency Support:** 24/7 for Enterprise license holders

---

## 📝 Version History

| Version | Date | Changes |
|---------|------|---------|
| 1.0 | Jan 2024 | Initial release: Berth planning, cargo tracking, basic billing |
| 1.5 | Jun 2024 | Added: Asset lifecycle, mobile ops, safety interlocks, QR codes |
| 2.0 | Dec 2024 | **Added: RBAC, User Management, Notification Centre, Real-time billing sync** |

---

**Confidentiality Notice:** This manual contains proprietary business logic for PortFlow. Unauthorized distribution is prohibited.

**Copyright © 2024-2025 PortFlow Development Team. All rights reserved.**
