# PortFlow Strategic Master Plan: From Port Operator to Logistics Giant

**Document ID**: PF-MASTER-2026-FINAL  
**Version**: 2.0 (Post-Implementation Update)  
**Date**: 26 December 2025  
**Prepared For**: ASB Management, Petronas/Shell Stakeholders  

---

## 1. Executive Summary & Value Proposition
**PortFlow** has evolved from a traditional Port Management System (PMS) into a Comprehensive **Integrated Supply Base Management System (ISBMS)**. This strategic pivot positions ASB not just as a landlord of berths, but as a critical partner in the Oil & Gas supply chain.

### The Problem
Major O&G operators (Petronas, Shell) suffer from **"Black Hole Logistics"**: once cargo leaves their warehouse, they lose visibility until it reaches the rig. This results in:
*   **High Non-Productive Time (NPT)**: Rigs waiting for tools ($20k/hr loss).
*   **Inventory Bloat**: 20% excess buffer stock due to uncertainty.
*   **Safety Risks**: Manual permits leading to conflicting activities (e.g., Hot Work near Fueling).

### The Solution: PortFlow Logistics Core
We provide **Total Visibility & Control**:
1.  **Digital Manifests**: End-to-end tracking of every pipe and valve via QR codes.
2.  **Smart Warehousing**: Real-time heatmaps of yard utilization.
3.  **Digital Safety**: Automated Permit-to-Work (e-PTW) with clash detection.

### ROI Analysis (The Business Case)
| Metric | Current Manual Process | PortFlow Digital Process | Savings / Gain |
| :--- | :--- | :--- | :--- |
| **Truck Turnaround** | 45 mins (Paper checks) | 15 mins (QR Scan) | **66% faster** |
| **Manifest Errors** | 5-10% (Typing errors) | 0% (Digital Integration) | **$200k/yr admin savings** |
| **Rig NPT Avoided** | ~50 hrs/year | ~5 hrs/year | **$900,000/year** (Client Value) |
| **Yard Utilization** | 60% (Poor visibility) | 85% (Heatmap optimized) | **+25% Revenue Capacity** |

---

## 2. Product Requirements Document (PRD)

### Module 1: Cargo Logistics (Implemented)
**Objective**: Digitize the movement of goods from Gate to Vessel.
*   **Features**:
    *   **Inbound/Outbound Manifests**: Create, Edit, Submit manifests digitally.
    *   **Dynamic Line Items**: Support for 100+ items per manifest with weight/volume tracking.
    *   **QR Code Tracking**: Auto-generation of unique tracking IDs (`TRK-XXXX`) with printable QR labels.
    *   **Dangerous Goods (DG) Alert**: Visual warning banner for Class 1-9 hazardous materials.
    *   **Status Workflow**: Draft -> Submitted -> Approved -> Loaded -> Discharged.

### Module 2: Warehouse Management (Implemented)
**Objective**: Optimize storage space and prevent cargo stagnation.
*   **Features**:
    *   **Visual Yard Map**: Interactive grid showing Warehouse/Zone layout.
    *   **Utilization Heatmap**:
        *   🟢 <50% (Available)
        *   🟡 50-80% (Moderate)
        *   🔴 >80% (Critical/Full)
    *   **Aging Cargo Report**: Auto-list items sitting >90 days to prevent revenue leakage.
    *   **Zone Rules**: Enforce "Explosives Only" or "Open Yard" storage rules.

### Module 3: HSE Safety Console (Implemented)
**Objective**: Ensure zero-incident operations through digital controls.
*   **Features**:
    *   **e-PTW System**: Digital request form for Hot Work, Cold Work, Height, Confined Space.
    *   **Clash Detection Engine**: *Automatically blocks* permits that conflict in Location & Time (e.g., preventing Welding at Berth 1 if painting is scheduled).
    *   **Approval Workflow**: Digital signature chain (Applicant -> Safety Officer -> Approval).

---

## 3. System Requirements Specification (SRS) - Technical

### 3.1 Architecture
*   **Framework**: Laravel 11 (PHP 8.4) + Livewire 3 (Full Stack Reactive).
*   **Database**: MySQL / MariaDB.
*   **Authorization**: Role-Based Access Control (RBAC) via custom middleware.

### 3.2 Key Data Models
*   `CargoManifest`: `id`, `vessel_id`, `agent_id`, `status` (enum), `type` (in/out).
*   `CargoItem`: `id`, `manifest_id`, `tracking_number`, `dg_class`, `warehouse_zone_id`.
*   `WorkPermit`: `id`, `control_no`, `type`, `location`, `valid_from`, `valid_to`, `status`.
*   `WarehouseZone`: `id`, `capacity_limit_m3`, `current_utilization`.

### 3.3 Security & Compliance
*   **Two-Factor Authentication (2FA)**: Time-based One-Time Password (TOTP) logic readiness.
*   **Audit Trail**: Immutable log of all `Create`, `Update`, `Delete` actions, exportable to CSV.

---

## 4. User Manual (Quick Start)

### 4.1 Logistics Officer (Agent)
**How to Submit a Manifest:**
1.  Go to **Cargo Logistics > Manifests**.
2.  Click **New Manifest**.
3.  Select Vessel `Amanda (OSV)` and Agent `Barakah Offshore`.
4.  Add Items: Enter Description, Weight, and select **DG Class** if hazardous.
5.  Click **Submit**. Print the page to get QR Codes for stickers.

### 4.2 Warehouse Supervisor
**How to Check Yard Capacity:**
1.  Go to **Yard / Warehouse Map**.
2.  Look for **Red Zones** (Critical). Move cargo from Red to Green zones if needed.
3.  Check the **Aging Cargo** sidebar. Any item >90 days should be flagged to the client for billing or removal.

### 4.3 Safety Officer
**How to Approve a Permit:**
1.  Go to **HSE Safety Console**.
2.  Review "Requested" permits.
3.  Check for **Clash Conflicts** (System will auto-alert if one exists).
4.  Click **Approve** to activate the permit or **Reject** with reasons.

---

## 5. Strategic Roadmap (Next Steps)

*   **Phase 5 (Future)**: Mobile App for handheld scanners (Zebra/Honeywell) to scan QR codes on the ground, updating status to "Loaded" instantly via API.
*   **Phase 6 (Future)**: IoT Integration for automated Berth Occupancy sensors.

---

**End of Document**
