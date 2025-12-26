# User Acceptance Testing (UAT) Plan: Logistics & Security Module

**Project**: PortFlow - Strategic Expansion (Phase 3 & 4)  
**Version**: 1.0  
**Date**: 26 December 2025  
**Tester**: [User Name]  

---

## 1. Overview
This UAT cycle focuses on the newly implemented **Cargo Logistics Module** (Manifests, Items, QR Codes) and the **Enhanced Security Features** (2FA, Audit Export).

---

## 2. Test Cases

### A. Logistics & Cargo Manifests

| Test Case ID | Feature | Scenario | Steps | Expected Result | Pass/Fail |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **LOG-01** | **Create Manifest** | Create a new Inbound manifest with multiple items | 1. Navigate to **Cargo Logistics**. <br> 2. Click **New Manifest**. <br> 3. Select Vessel & Agent. <br> 4. Add Item 1 (General). <br> 5. Click **+ Add Item**. <br> 6. Add Item 2 (Dangerous Goods, Class 3). <br> 7. Click **Submit**. | System redirects to Index. Manifest appears in list with correct item count. | |
| **LOG-02** | **View Manifest** | View manifest details and QR Codes | 1. Click the **Eye Icon** on the newly created manifest. | Detail page opens. **QR Codes** are visible for both items. **Dangerous Goods Warning** banner is displayed (Red). | |
| **LOG-03** | **Search** | Search for a specific manifest | 1. In Manifest Index, type "MNF-IN-2025-001" in the search bar. | Table filters to show only the matching manifest. | |
| **LOG-04** | **Status Workflow** | Advance manifest status | 1. In Detail View, click **Advance Status**. | Status changes (e.g., Draft -> Submitted). Progress bar updates. | |
| **LOG-05** | **Delete Draft** | Delete a draft manifest | 1. Find a manifest with status "Draft". <br> 2. Click **Delete (Trash Icon)**. | Manifest is removed from the list. Success notification appears. | |
| **LOG-06** | **Validation** | Attempt to submit empty manifest | 1. Go to New Manifest. <br> 2. Delete all items. <br> 3. Click **Submit**. | Form is NOT submitted. Error message "Items must contain at least 1 item" appears. | |

### B. Security & Audit

| Test Case ID | Feature | Scenario | Steps | Expected Result | Pass/Fail |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **SEC-01** | **Audit Export** | Export Audit Logs to CSV | 1. Navigate to **Administration > Audit Trail**. <br> 2. Click **Export CSV**. | Browser downloads a `.csv` file containing audit records. | |
| **SEC-02** | **Profile Update** | Update User Profile Name | 1. Navigate to **User Icon > Settings** (Sidebar footer). <br> 2. Change Name. <br> 3. Click **Save Changes**. | Success toast appears. Name is updated in Sidebar. | |
| **SEC-03** | **2FA Toggle** | Enable 2FA | 1. Navigate to **Settings**. <br> 2. Scroll to 2FA section. <br> 3. Click **Enable 2FA**. | Button changes to "Disable 2FA". System logs "Enabled Two-Factor Authentication". | |

---

## 3. Demo Data Verification
The following data has been seeded for quick verification:

*   **Manifest `MNF-IN-2025-001`**: Contains **Flammable Solvent** (Class 3). Should trigger Red Alert.
*   **Manifest `MNF-IN-2025-002`**: Status `Loaded`. Contains **Explosives** (Class 1).
*   **Manifest `MNF-DRAFT-099`**: Status `Draft`. Useful for testing Delete function.

---

## 4. Sign-Off
**Tester Signature**: ________________________  
**Date**: ________________________  
**Status**: [ ] Approved for Production  [ ] Changes Required
