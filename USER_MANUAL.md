# PortFlow: The Definitive OSB & Supply Base Management Manual
## Version 1.5 | Digital Transformation in Oil & Gas & Logistics
---

## 🔑 1. Secure Access & User Roles
PortFlow utilizes an **Identity-First** security model. Access is restricted based on the "Least Privilege" principle.

| Role | Primary Endpoint | Access level | Key Responsibilities |
| :--- | :--- | :--- | :--- |
| **Port Admin** | `admin@asb.com` | Full Administrative | Global oversight, financial audits, resource allocation. |
| **HSE Officer** | `hse@asb.com` | Safety Enforcement | High-risk permit approval, incident investigation, regulatory compliance. |
| **Shipping Agent** | `agent@baram.com` | Logistics Partner | Fleet operations, manifest submission, cargo tracking. |

**Security Note:** All logins are protected by **2FA (Two-Factor Authentication)** and brute-force rate limiting.

---

## � 3. Commercial & License Pricing
PortFlow offers a flexible pricing model tailored for single-port operators or large regional logistics aggregators.

### 🏢 Single License (Enterprise)
*For a single Supply Base or Terminal.*
*   **Cost:** RM 100,000 / Year (SaaS) or RM 550,000 (One-time Perpetual).[Negotiable]
*   **Includes:** Unlimited users, 24/7 technical support, and monthly updates.
*   **Onboarding:** Professional deployment and data migration included.

### 🏢 Reseller / OEM / White-Label
*For maritime consultants and tech providers.*
*   **Cost:** RM 495,000 / Year (Bulk License Pack - Up to 5 Ports). [Negotiable]
*   **Markup Potential:** 100% (Resellers can set their own end-user pricing).
*   **Customization:** Full white-labeling (Your Logo, Your Colors) and API access for custom integrations.

---

## �🛠️ 4. Comprehensive Module Guide (Menu-by-Menu)

### 🟦 ADMINISTRATION & OPERATIONS (For Admins)

#### 🚢 **Berth Planner (The Digital Twin of your Wharf)**
*   **Purpose:** Visual scheduling of vessel arrivals and departures.
*   **Usage:** Click an empty grid cell to initiate a **Port Call Request**.
*   **Admin Power:** Admins can Drag-and-Drop port calls to optimize berth utilization. 
*   **Financial Trigger:** Setting an **ATB (Actual Time of Berthing)** initiates the automated billing clock.

#### 📊 **Analytics Dashboard**
*   **Purpose:** Executive intelligence for decision makers.
*   **KPIs Tracked:** 
    *   **MTT (Mean Turnaround Time):** Measure efficiency in vessel servicing.
    *   **Berth Occupancy:** Identify bottlenecks in port infrastructure.
    *   **Revenue Heatmap:** See which clients (Shell, Petronas, etc.) generate the highest margin.

#### 📦 **Cargo Logistics & Manifests**
*   **Purpose:** End-to-end tracking of "Total Cargo".
*   **Workflow:** Review manifests submitted by agents. Confirm weight, volume, and **DG (Dangerous Goods)** class.
*   **Inventory:** Items are tracked in real-time from "On Vessel" -> "In Transit" -> "Stored in Yard".

#### 🗺️ **GIS & Warehouse Map**
*   **Purpose:** Spatial awareness of the supply base.
*   **Feature:** Zoom into specific warehouse zones. Hover over cargo icons to see tracking numbers and aging data (how long it has been sitting in your yard).

#### 👥 **Crew Terminal**
*   **Purpose:** ISPS-compliant personnel management.
*   **Function:** Log crew changes, shore leave, and visitor access. Ensures every soul on the port is accounted for in case of emergency.

---

### �️ 3. HSE GUARDIAN SUITE (End-to-End Safety)
PortFlow treats safety as a **Revenue Protector**, not just a cost center.

#### � **PTW Safety Console (Permit-to-Work)**
*   **Usage:** Central hub for high-risk activity management (Hot Work, Cold Work, Confined Space).
*   **Logic:** The system utilizes **Clash Detection**. It will alert you if a maintenance permit overlaps with a vessel refueling operation at the same location.
*   **Review Process:** HSE Officers must verify JSAs (Job Safety Analysis) and PPE requirements digitally before "Issuing" the permit.

#### � **Safety Intelligence (Incident & Near-Miss Reporter)**
*   **Usage:** A proactive hazard identification tool.
*   **Reporting:** Anyone on the port can snap a photo of a hazard and report it via mobile.
*   **Investigation:** HSE Officers use the **Investigation Dossier** to log Root Cause Analysis and define "Corrective Actions."
*   **Status Tracking:** Reports move through "Open" -> "Investigating" -> "Resolved" -> "Closed" with full timestamps.

---

### ⚓ 4. AGENT PORTAL (The Partner Interface)
Designed to reduce the administrative burden on port partners.

#### 💻 **Management Console**
*   **Dashboard:** Real-time view of the agent's fleet and active cargo. 
*   **Self-Service:** Agents can add new vessels and register manifests without calling the port office.

#### 🆘 **Permit Requests**
*   **Workflow:** Agents apply for permits for their crews. They receive **Live Status Notifications** (Approved/Rejected/More Info) directly on their mobile devices.

---

## 🏗️ 5. Engineering Design & Documentation (URS / SRS)
This section outlines the rigorous technical and functional framework used to build PortFlow for the **Asian Supply Base (ASB)**.

### 📋 5.1 User Requirement Specification (URS)
*What the business needs to achieve.*
1.  **Requirement 101:** The system must provide a real-time visual "Digital Twin" of the wharf to eliminate berthing overlaps.
2.  **Requirement 102:** The system must detect safety "clashes" between cargo operations and maintenance work.
3.  **Requirement 103:** Automation of the billing clock based on Actual Time of Berthing (ATB) and Actual Time of Unberthing (ATU).
4.  **Requirement 104:** Role-based access control (RBAC) specifically separating Port Authority, Safety, and Third-party Agents.
5.  **Requirement 105:** Automated metered billing for heavy equipment (Cranes/Forklifts) and specialized facilities.

### ⚙️ 5.2 Software Requirement Specification (SRS)
*How the software delivers the URS.*
*   **Architectural Stack:** TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire) for high-reactivity without page reloads.
*   **Security Layer:** Middleware-based RBAC & Rate Limiting (6 req/min) to prevent brute-force attacks.
*   **Database Engine:** Transactional-safe SQL for financial billing integrity.
*   **Edge Capability:** Mobile-responsive layout for field ops with image-compression for low-bandwidth zones.

### 📖 5.3 User Stories (The Human Perspective)
*   **As a Port Admin,** I want to see a thermal map of berth occupancy so I can maximize wharf revenue and reduce idle time.
*   **As an HSE Officer,** I want to receive instant photo-evidence of hazards so I can investigate and close safety gaps within 4 hours.
*   **As a Shipping Agent,** I want to book a 250T Crane autonomously so I can proceed with urgent offshore tubular loading without manual paperwork.

---

## 💡 6. Problem vs. Solution Matrix
*The core operational challenges PortFlow solves for ASB.*

| The Problem | PortFlow’s Digital Solution |
| :--- | :--- |
| **"Phantom Billing"**: Berthing time is rounded off manually, leading to thousands in lost income. | **Automatic Timers**: Invoices are generated based on exact server-logged berthing timestamps. |
| **"Communication Black-holes"**: Agents use paper manifests that get lost or misfiled. | **Digital Manifest Portal**: One-click upload with instant QR tracking for every single cargo item. |
| **"Reactive Safety"**: Safety officers only find out about hazards *after* an accident occurs. | **Safety Intelligence**: Real-time reporting of "Hazard Observations" to enable preventative action. |
| **"Berth Clashes"**: Two vessels scheduled for one slot due to spreadsheet errors. | **Visual GIS Grid**: Collision detection system prevents overlapping bookings in the scheduler. |
| **"Equipment Under-utilization"**: Cranes and forklifts sitting idle or used without billing. | **Resource Marketplace**: Digital metered booking ensures every asset hour is captured and billed. |
| **"Audit Pain"**: Spent weeks compiling records for Petronas/Shell safety audits. | **One-Click Compliance**: A complete, unalterable Audit Trail is exported in seconds. |

---

## 💰 7. Return on Investment (ROI) Analysis
*Investing in PortFlow isn't a cost; it's a strategic capital optimization.*

### 📈 ROI Factors:
1.  **Revenue Leakage Elimination (15-20% Increase):** Manual billing often misses "extra hours" and "auxiliary services" (Water, Trash, Crane time). PortFlow's automated timers ensure 100% billing accuracy.
2.  **Incident Cost Mitigation (Millions Saved):** The average O&L incident (explosion/fire) costs upwards of RM 5M in fines and downtime. PortFlow's **Safety Clash Logic** reduces this risk by ~90%.
3.  **Efficiency Gains (30% Faster Turnaround):** Real-time cargo tracking reduces "Lost Item Searches" from hours to seconds.

### ⏱️ The ROI Timeline:
*   **Months 1-3:** System familiarization; Initial revenue uplift from accurate billing discovered.
*   **Months 4-6:** **Full Investment Recovery (Break-Even)** achieved through saved man-hours and zero billing leakage.
*   **Month 12+:** Pure profit scaling through optimized berth occupancy and high-value data analytics.

---

## 🚀 8. Future HSE Roadmap (What's Next?)
We are committed to making PortFlow the most advanced HSE OS in the world.

*   **Phase 2: Digital Induction Portal:** Automated safety videos and quizzes for contractors before gate access is granted.
*   **Phase 3: AI-PPE Monitoring:** Integration with existing CCTV to automatically detect personnel not wearing hard-hats or high-vis vests.
*   **Phase 4: HSE Contractor Scorecard:** A ranking system that rewards safe agents with "Priority Berth Access" and penalizes repeat offenders.
*   **Phase 5: Drone Integration:** Automated aerial inspections for warehouse roof integrity and perimeter security.

---

## ⚡ 9. Technology Architecture (The Engine)
PortFlow is engineered using the **TALL Stack**, a high-performance modern web architecture designed for real-time reactivity and mission-critical reliability.

### 🛠️ Core Technology Stack:
*   **Backend (Laravel 11):** The world's most robust PHP framework, providing Enterprise-grade routing, ORM (Eloquent), and deep security layers.
*   **Reactivity (Livewire 3):** Allows the system to update UI components (like the Berth Planner or Safety Dashboard) in real-time without refreshing the page.
*   **Interactivity (Alpine.js):** Lightweight JavaScript for fluid client-side interactions (like the Password Eye toggle).
*   **Design System (Tailwind CSS):** A utility-first CSS framework that ensures a stunning, responsive, and mobile-first experience for field operators.
*   **Data Layer (MySQL/MariaDB):** Transactional-safe relational database ensuring zero-loss billing and audit logging.

### 📡 System Resilience:
*   **PWA (Progressive Web App):** PortFlow can be "installed" on mobile devices, providing a native app experience for ground crews at the wharf.
*   **Edge Ops:** Optimized for low-bandwidth satellite connections common in remote port zones.

---

## 🛡️ 10. Cybersecurity & Data Hardening
We have implemented a **"Hard as Rock"** security posture to protect ASB's operational data and financial integrity.

### 🔐 10.1 Perimeter Defense:
*   **CSP (Content Security Policy):** Strict browser-level instructions that block unauthorized scripts and data-injection attacks (XSS).
*   **HSTS & SSL:** All traffic is forced through 256-bit encrypted tunnels.
*   **Harden Headers:** Enforced `X-Frame-Options: DENY` and `X-Content-Type: nosniff` to prevent clickjacking and MIME-type sniffing.

### 👤 10.2 Identity & Auth Security:
*   **2FA (Multi-Factor Authentication):** Critical actions require a secondary verification code, protecting against stolen credentials.
*   **Password Entropy:** Enforced high-complexity password rules and leak-detection (checking against known breached passwords).
*   **Route Throttling:** Intelligent rate-limiting (e.g., 6 req/min on login) to neutralize brute-force automation.

### 🗒️ 10.3 Immutable Audit Trails:
*   Every change in the system—from permit approvals to invoice adjustments—is logged with a **User ID, IP Address, and Timestamp**. 
*   These logs are immutable and serve as the "Black Box" for port safety investigations and financial audits.

---

**Confidentiality Notice:** This manual contains proprietary business logic for PortFlow.
**Support:** support@labuan.dev | +60198691289
