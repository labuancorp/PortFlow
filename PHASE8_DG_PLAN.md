# Phase 8.3: Dangerous Goods & Explosives Management Plan

## Overview
This phase addresses the critical safety and compliance requirements for handling Dangerous Goods (DG) and managing the Explosive Storage Bunker. It ensures strict adherence to IMDG codes, automated segregation checks, and permit-to-work workflows.

## Objectives
1.  **IMDG Compliance**: Classify all cargo against IMDG codes (Class 1 Explosives to Class 9 Misc).
2.  **Segregation Logic**: Prevent incompatible DG classes from being stored together or loaded adjacent to each other.
3.  **Explosive Bunker Management**: Specialized inventory tracking for Class 1 items with strict quantity limits (Net Explosive Quantity - NEQ).
4.  **Permit Integration**: Auto-trigger HSE permits for DG handling.

---

## 1. Database Schema Design

### 1.1 DG Classification
- **`dg_classes`**
    - `id`, `class_code` (e.g., "1.1", "3", "5.1")
    - `name` (e.g., "Explosives Mass Explosion Hazard", "Flammable Liquids")
    - `description`
    - `segregation_key` (Group ID for compatibility matrix)

- **`dg_segregation_matrix`**
    - `class_a_id`, `class_b_id`
    - `rule` (Allowed, Restricted (3m separation), Prohibited)

### 1.2 Dangerous Cargo
- **`dg_declarations`**
    - `id`, `cargo_item_id` (Link to CargoManifest or InventoryItem)
    - `un_number` (e.g., UN1203)
    - `proper_shipping_name`
    - `dg_class_id`
    - `packing_group` (I, II, III)
    - `flash_point` (for Class 3)
    - `neq_kg` (Net Explosive Quantity for Class 1)
    - `emergency_contact`

### 1.3 Explosive Bunker
- **`explosive_bunker_inventory`**
    - `id`, `dg_declaration_id`
    - `magazine_id` (Store/Cell ID)
    - `quantity_stored`
    - `expiry_date`
    - `security_seal_number`
    - `police_permit_number` (PDRM)

---

## 2. Implementation Steps

### Step 1: Foundation
- Migration: `create_dg_compliance_tables`.
- Models: `DgClass`, `DgSegregationRule`, `DgDeclaration`, `ExplosiveBunkerItem`.
- Seeder: Populate standard IMDG Classes (1-9) and Segregation Matrix.

### Step 2: Backend Logic (`DgComplianceService`)
- `checkSegregation($classA, $classB)`: Return 'Allowed', 'Separated', 'Prohibited'.
- `validateBunkerCapacity($newNEQ)`: Check total NEQ limit for the bunker.
- `triggerPermit($declaration)`: Create HSE Permit Draft if DG detected.

### Step 3: Frontend - Compliance Dashboard
- **DG Manifest List**: Filterable list of all current DG on port.
- **Bunker Status**: Visualization of the Explosive Bunker (Slots occupied, Total NEQ).
- **Segregation Calculator**: Tool to check if two UN numbers can be stored together.

---

## 3. Success Criteria
- [ ] System strictly forbids storing Class 1.1 (Explosives) with Class 3 (Flammable Liquids).
- [ ] Bunker inventory tracks NEQ (Net Explosive Quantity) totals to prevent licensing breaches.
- [ ] "High Risk" alert badge appears on any task involving Class 1 cargo.
