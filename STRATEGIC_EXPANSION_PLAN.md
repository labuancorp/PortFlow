# PortFlow Strategic Expansion Plan: From Port Operator to Logistics Giant

**Document ID**: PF-STRAT-2026-01  
**Target Audience**: ASB Management, Petronas/Shell Stakeholders, Development Team  
**Date**: 26 December 2025  

---

## 1. Executive Summary & "The Pivot"

**Current State**: PortFlow is currently a robust **Port Management System (PMS)**. It effectively handles vessel traffic, billing, and basic infrastructure (berths/wharfs).  
**The Gap**: To become a "Giant Competitor" in the logistics space (comparable to Singapore's Jurong Port or Rotterdam), managing the *port* is not enough. We must manage the *supply chain*.  
**The Strategy**: We must pivot to a fully integrated **Supply Base Management System (SBMS)**. The "Holy Grail" for clients like Petronas and Shell is not just knowing *when* the boat arrives, but knowing *where their drilling equipment is* at every second.

**Key Value Proposition**: "Total Supply Chain Visibility — From Warehouse to Offshore."

---

## 2. Competitive Analysis: Why Tier-2?

| Feature Set | PortFlow (Current) | Tier-1 Global Competitors (Jurong/Rotterdam) |
| :--- | :--- | :--- |
| **Vessel Scheduling** | ✅ Excellent (Gantt, AI) | ✅ Excellent |
| **Billing** | ✅ Automated | ✅ Automated + ERP Integration |
| **Cargo Tracking** | ❌ **Non-Existent** | ✅ **Item-Level Tracking (RFID/QR)** |
| **Warehousing** | ❌ **Non-Existent** | ✅ **Integrated WMS** |
| **HSE/Safety** | ❌ Basic Logs | ✅ **Digital Permit-to-Work (PTW)** |
| **Waste Mgmt** | ❌ None | ✅ **Cradle-to-Grave Tracking** |

**Conclusion**: We are currently a "Parking Lot Manager" for vessels. To win Petronas/Shell contracts, we must become a "Logistics Partner" that ensures their materials flow efficiently and safely.

---

## 3. The "Giant" Strategy: Winning Petronas & Shell

### 3.1 What Do Oil Majors Actually Want?
1.  **Material Visibility**: "Where is my frantic request for a replacement valve? Is it on the truck? At the wharf? On the vessel?"
2.  **HSE Compliance**: "Did the crane operator have a valid permit? Was the lift plan approved?" O&G companies will pay a premium for safety compliance.
3.  **Cost Efficiency**: "Why are we renting 50 containers when we only use 10?" (Inventory optimization).

### 3.2 ROI Analysis (The Business Case)

**For the Port Operator (ASB):**
*   **New Revenue Streams**: Charge for "Digital Manifesting", "Inventory Storage", and "Safety Processing".
*   **stickiness**: Once Petronas integrates their SAP with our Manifest system, they cannot leave.

**For the Client (Petronas/Shell):**
*   **Reduced NPT (Non-Productive Time)**: If a drilling rig waits 1 hour for a tool, it costs ~$20,000. Real-time cargo tracking prevents this.
    *   *Calculation*: Prevent 50 hours of NPT/year = **$1,000,000 savings**.
*   **Inventory Reduction**: Better visibility allows reducing buffer stock by 10-20%.
*   **Paperless Operations**: Eliminating physical manifests saves ~15 mins per truck.
    *   *Calculation*: 100 trucks/day * 15 mins * $50/hr = **$450,000/year savings**.

---

## 4. Product Requirements Document (PRD) - Phase 4

### Feature 1: Digital Cargo Manifest (The "Amazon" of Offshore)
**Description**: A system to track individual cargo items (containers, baskets, pipes) as they move from user warehouse -> port security -> wharf -> vessel.
**Core Capabilities**:
*   **QR Code Scanning**: Mobile app integration for checkers.
*   **Manifest Builder**: Drag-and-drop creation of manifests.
*   **Dangerous Goods (DG) Auto-Check**: Automatic flagging of explosive/radioactive materials against segregation rules (IMDG Code).

### Feature 2: Integrated Warehouse Management (WMS)
**Description**: Visual management of open yards and covered warehouses within the port.
**Core Capabilities**:
*   **Yard Density Map**: Which areas are full? (Use our Heatmap tech).
*   **Aging Reports**: Alerts for cargo sitting >90 days (Revenue leakage for clients).

### Feature 3: Digital Permit-to-Work (HSE)
**Description**: Digitizing the safety approval process for hazardous activities.
**Core Capabilities**:
*   **Approval Workflow**: Contractor requests -> Safety Officer approves.
*   **Clash Detection**: Prevent "Welding" and "Fueling" happening at the same time/place.

---

## 5. System Requirements Specification (SRS) - Technical

### 5.1 Data Models (New)
*   **`CargoItem`**: `id`, `manifest_id`, `type` (Container/Pipe), `weight`, `dg_class`, `status` (AtGate, AtWharf, Loaded).
*   **`Manifest`**: `id`, `vessel_id`, `client_id`, `status`.
*   **`WorkPermit`**: `id`, `location_id`, `activity_type`, `valid_from`, `valid_to`, `approver_id`.

### 5.2 API Integrations
*   **Petronas EDI**: Ingest cargo lists directly from their SAP system.
*   **Customs System**: Auto-submit K2/K8 forms for bonded goods.

---

## 6. User Stories

1.  **As a** Logistics Coordinator (Petronas),
    **I want** to upload a CSV manifest of 50 items,
    **So that** I don't have to manually type them at the gate.

2.  **As a** Crane Operator (ASB),
    **I want** to scan a basket's QR code before lifting,
    **So that** the system automatically records it as "Loaded" and verifies it's not too heavy.

3.  **As a** Safety Officer,
    **I want** to see all active "Hot Work" permits on the map,
    **So that** I can stop a fuel barge from berthing nearby.

---

## 7. Draft User Manual (Cargo Module)

### 7.1 Creating a Manifest
1.  Navigate to **Logistics > Manifests**.
2.  Click **New Outbound Manifest**.
3.  Select **Vessel** and **Destination Rig**.
4.  Scanning Mode: Use the dedicated hand-held scanner or PortFlow Mobile App to scan cargo QR codes as they arrive at the gate.
5.  Click **Submit**. The Vessel Master will instantly receive a notification to review the load list.

### 7.2 Safety Checks
*   The system will automatically highlight in **RED** if you attempt to load Class 1 (Explosives) next to Class 3 (Flammable Liquids).
