# 📘 PortFlow User Manual
**Version:** 1.0  
**Date:** 2025-12-25  
**System:** Port Operating System (POS) for ASB Labuan  

---

## 📖 Table of Contents
1.  [Introduction](#1-introduction)
2.  [Getting Started](#2-getting-started)
3.  [For Shipping Agents (Client Portal)](#3-for-shipping-agents)
    *   3.1 Registration & Onboarding
    *   3.2 Fleet Management
    *   3.3 Requesting a Berth
    *   3.4 Tracking Live Status
4.  [For Port Authorities (Command Center)](#4-for-port-authorities)
    *   4.1 Dashboard Overview
    *   4.2 Berth Planning & Scheduling
    *   4.3 GIS Digital Twin Map
    *   4.4 Data Registry (Vessels, Agents, Wharfs)
5.  [For Ground Operations (Mobile App)](#5-for-ground-operations)
    *   5.1 Managing Active Jobs
    *   5.2 Requesting Services (Fuel/Water)
6.  [For Security & Immigration (Crew Terminal)](#6-for-security--immigration)
    *   6.1 Crew Transfer Processing
    *   6.2 Flagging Risks
7.  [Billing & Finance](#7-billing--finance)
    *   7.1 Automated Invoicing

---

## 1. Introduction
**PortFlow** is the central nervous system for the Asian Supply Base (ASB). It replaces manual paper logs, whiteboards, and Excel sheets with a unified digital platform. It connects Agents, Operations, Security, and Finance in real-time.

---

## 2. Getting Started
### System Access
*   **Web URL:** `http://localhost:8000` (Internal Network)
*   **Browser Support:** Chrome, Edge, Safari (Latest Versions)

### User Roles
1.  **Admin / Authority:** Full access to the Command Center.
2.  **Shipping Agent:** Access to the Self-Service Client Portal only.
3.  **Ground Ops:** Access to the Mobile Operations view.

---

## 3. For Shipping Agents
*Target Audience: Vessel Owners, Logistics Coordinators*

### 3.1 Registration & Onboarding
New to PortFlow? You can register your agency immediately.
1.  Go to the **Login Page** (`/login`).
2.  Click **"New Agent? Register here"**.
3.  Fill in your **Personal Details** and **Organization Info** (Company Name, Billing Address).
4.  *Note:* Your "Agent Code" (e.g., `MSK` for Maersk) must be unique.

### 3.2 Fleet Management
Before requesting a berth, you must register your vessel.
1.  Log in to the **Agent Portal** (`/portal`).
2.  Click **"Register New Vessel to Fleet"** (Top Right).
3.  Enter Vessel Details:
    *   **Name**: e.g., "MV Nautica Gamble"
    *   **IMO Number**: 7-digit unique identifier.
    *   **LOA / Draft**: Critical for berth allocation.
4.  Click **"Add Vessel"**. It is now instantly available for booking.

### 3.3 Requesting a Berth
No need to call the control room.
1.  On the Portal Dashboard, click the large **"Request New Berth"** button.
2.  **Select Vessel**: Choose from your registered fleet.
3.  **ETA / ETD**: Select your Estimated Time of Arrival and Departure.
4.  Click **"Submit Request"**.
5.  Check the **"Scheduled"** tab to see your request status change from `REQUESTED` to `APPROVED`.

### 3.4 Tracking Live Status
*   **"Live Operations" Tab**: Shows vessels currently Alongside or Anchored.
*   **Progress Bar**: Visual indicator of how much time is left before the vessel must depart (ETD).
*   **Billing Badge**: A "Active Billing" badge appears when the vessel initiates chargeable events.

---

## 4. For Port Authorities
*Target Audience: Port Managers, Control Room Officers*

### 4.1 Dashboard Overview
The **Command Center** (`/dashboard`) is your morning coffee view.
*   **KPI Cards**: See Revenue Today, Active Vessels, and Occupancy Rate at a glance.
*   **Recent Activity**: An audit trail of every action taken in the port (e.g., "Agent Baram requested a berth").

### 4.2 Berth Planner
The core engine for scheduling.
*   **Timeline View**: A GANTT chart showing all wharfs (MW1, MW2, etc.).
*   **Conflict Detection**: Visual overlaps indicate double-bookings.
*   **Usage**: Use this to plan 24–48 hours ahead.

### 4.3 GIS Digital Twin Map
A satellite view for situational awareness (`/map`).
*   **Vessel Markers**: Color-coded dots (Green=Alongside, Amber=Anchored, Blue=Approaching).
*   **Click-to-View**: Click any vessel to see its Name, Type, and exact Coordinates.
*   **Geofences**: White circles indicate the official boundaries of the Wharfs.

### 4.4 Data Registry
Manage the master data of the port.
*   **Vessels (`/vessels`)**: View database of all ships calling at the port.
*   **Agents (`/agents`)**: Manage approved agencies.
*   **Wharfs (`/wharfs`)**: Configure berth lengths and depths.

---

## 5. For Ground Operations
*Target Audience: Wharfsinger, Mooring Gangs, Foremen*

**Access:** Navigate to `/ops` on a tablet or smartphone.

### 5.1 Managing Active Jobs
1.  The screen shows a list of **"Active Tasks"** (Vessels alongside).
2.  **Update Status**:
    *   Tap **"Confirm Line"**: Vessel status changes from Approaching -> Alongside. Invoice timer starts.
    *   Tap **"Complete"**: Vessel status changes to Completed. Invoice timer stops.

### 5.2 Requesting Services
Don't write fuel requests on paper.
1.  Tap the **"Blue Plus (+)"** button on a vessel card.
2.  Select Service: **Fresh Water** or **Fuel Bunkering**.
3.  System logs the request and adds the cost to the final invoice instantly.

---

## 6. For Security & Immigration
*Target Audience: Auxiliary Police, Immigration Officers*

**Access:** Navigate to `/terminal` (The Kiosk Interface).

### 6.1 Crew Transfer Processing
1.  **Select Vessel**: Choose the ship currently at the Crew Jetty.
2.  **Scan Passport**: Type the Passport Number (or use a barcode scanner) into the "Ready to Scan" field.
3.  **Auto-Process**:
    *   *Scan 1*: **Security Cleared** (Yellow)
    *   *Scan 2*: **Immigration Cleared** (Blue)
    *   *Scan 3*: **Terminal Gate Pass** (Green/Completed)

### 6.2 Flagging Risks
*   If a crew member is blacklisted or has invalid papers, click the **"Flag Risk"** (Red) button.
*   This halts processing and alerts the Control Room.

---

## 7. Billing & Finance
*Target Audience: Accounts Department*

### 7.1 Automated Invoicing
Navigate to `/billing`.
*   **Zero-Touch Invoicing**: Invoices are generated automatically when Operations marks a vessel as "Completed".
*   **Line Items**: The invoice automatically calculates:
    *   **Dockage**: Based on LOA x Hours.
    *   **Wharfage**: Fixed docking fees.
    *   **Services**: Water/Fuel requests logged by Ground Ops.
*   **Status**: Track invoices from `Pending` -> `Paid`.

---
## 8.0. Investment Schedule

### Option A: Enterprise Perpetual License (Recommended)
*One-time acquisition of the software assets for dedicated on-premise or private cloud usage.*

| Item | Description | Cost (MYR) |
| :--- | :--- | :--- |
| **Core Platform License** | Single Server License for ASB Labuan. Includes all modules (Ops, Billing, Map). | **RM 350,000.00** |
| **Modules Pack** | Crew Terminal, Agent Portal, Mobile Ops App. | **Included** |
| **Implementation** | Server setup, Data Migration, UAT, and Go-Live support. | **RM 50,000.00** |
| **Training** | 3 Days onsite training for Ops, Admin, and Agents. | **RM 15,000.00** |
| **Total One-Time Cost** | | **RM 415,000.00** |

**Annual Recurring (Starting Year 2):**
*   **Standard SLA (9x5):** RM 62,250.00 / year (15% of License).
*   **Critical SLA (24/7):** RM 83,000.00 / year (20% of License).

---

### Option B: OEM / Reseller Rights (Source Code Transfer)
*For System Integrators wishing to resell the solution to other ports (e.g., Kemaman, Tok Bali).*

*   **Investment:** **RM 1,500,000.00**
*   **Rights Included:**
    *   Full Source Code Access.
    *   White-label Rights (Remove PortFlow branding).
    *   Unlimited redistribution to unlimited clients.
    *   3 Months Knowledge Transfer & Engineering Support.

---

## 3. Return on Investment (ROI) Projection
Based on ASB's estimated traffic of 100+ vessels/month:

1.  **Revenue Assurance:** Preventing 1 "Lost Invoice" or unbilled water request per week saves ~RM 50,000 / year.
2.  **Berth Optimization:** Increasing throughput by 1 vessel per week via the Planner Algorithm adds ~RM 250,000 / year in dockage fees.
3.  **Labor Efficiency:** Reducing "Phone Call Coordination" saves ~RM 100,000 / year in man-hours.

**Projected ROI:** The system pays for itself within **10-12 Months**.

---

## 4. Payment Terms (Option A)
*   **50%** upon Signing (Mobilization).
*   **30%** upon UAT Sign-off (User Acceptance).
*   **20%** upon Go-Live Commissioning.

---

**Prepared by:**  
Technology Partner  
*PortFlow Solutions*

## 🆘 Support & Troubleshooting
*   **System Admin Contact:** IT Department (Ext. 101)
*   **Emergency Mode:** If system goes offline, revert to "Form-001 Manual Log" until connectivity is restored. Data must be back-entered later.
