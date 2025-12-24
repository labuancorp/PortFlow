# PortFlow
> **The Next-Generation Port Operating System (POS)**

PortFlow is a state-of-the-art enterprise solution designed to modernize maritime logistics for the **Asian Supply Base (ASB)**. It replaces legacy manual processes with an intelligent, "Glass Port" ecosystem that integrates berth planning, vessel tracking, improved safety compliance, and automated revenue assurance.

---

## 🚀 Key Modules & Features

### 1. 🏗️ Intelligent Berth Planning (The Berth Planner)
*   **Visual Interface**: A Gantt-chart style interactive timeline for managing vessel schedules.
*   **Algorithmic Optimization**: `BerthOptimizationService` analyzes vessel dimensions (LOA, Draft) and suggests the most efficient berth available.
*   **Drag-and-Drop Operations**: Seamlessly move bookings between wharfs.

### 2. 🛡️ Automated Compliance & Risk Management
*   **Gatekeeper Logic**: `ComplianceService` acts as a digital safety net.
*   **Physics Engine**: Automatically blocks unsafe assignments (e.g., assigning a 10m draft vessel to a 5m berth).
*   **Conflict Detection**: Prevents schedule overlaps and double bookings.
*   **Maintenance Awareness**: Locks down berths flagged for repair.

### 3. 💸 Integrated Revenue Assurance (Zero-Leakage Billing)
*   **Live Billing Engine**: Invoices are generated in real-time as operations happen.
*   **Dynamic Rating**: `BillingService` calculates fees based on LOA, Duration (Hour-based), and Fixed Charges (Wharfage).
*   **Order-to-Cash**: Service requests (Fuel, Water) from the ground crew are instantly added to the active invoice.
*   **Status Triggers**:
    *   `Alongisde` -> Starts the Billing Clock.
    *   `Departed` -> Finalizes and Issues the Invoice.

### 4. 📱 Mobile-First "Edge" Operations
*   **Ground Crew App**: A dedicated, high-contrast mobile interface (`/ops`) for field operators.
*   **Real-Time Data**: No paper logbooks. One-tap status updates (Secure Lines, Release Lines).
*   **Service Fulfillment**: Request utilities (Fresh Water, Fuel) directly from the wharf side.

### 5. 🔮 "Glass Port" Client Transparency
*   **Agent Portal**: A dedicated view (`/portal`) for Shipping Agents and Clients.
*   **Self-Service**: Clients track their own vessels' status (Approaching, Alongside, Completed) without calling the control room.
*   **Visual Timeline**: Progress bars visualize operations in real-time (Uber/Grab style).

### 6. 📊 Command Center Dashboard
*   **Live KPIs**: Real-time stats on Berth Occupancy, Incoming Traffic, and Turnaround Times.
*   **Pilotage Simulator**: A visual interactive tool to simulate pilot dispatch and vessel docking.
*   **Alerts System**: System-wide notifications for conflicting schedules or safety risks.

### 7. 🌍 GIS Digital Twin (Phase 1)
*   **Satellite Map Integration**: Fully interactive map of Labuan ASB (`/map`).
*   **Live Traffic**: Visualizes vessel positions based on their operational status.
*   **Berth Geofencing**: Virtual perimeters around Main Wharf and Jetties.


---

## 🛠️ Technology Stack

*   **Framework**: Laravel 11.x
*   **Frontend**: Livewire 3 (Full-Stack Reactivity) & Blade
*   **Styling**: TailwindCSS (Custom "Premium" Design System)
*   **Database**: SQLite (Configurable for MySQL/PostgreSQL)
*   **Build Tool**: Vite

---

## 📦 Installation & Setup

1.  **Clone the Repository**
    ```bash
    git clone https://github.com/labuan-corp/portflow.git
    cd portflow
    ```

2.  **Install Dependencies**
    ```bash
    composer install
    npm install
    ```

3.  **Environment Setup**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
    *Ensure your `.env` is set to use SQLite or your preferred database driver.*

4.  **Database Seeding**
    *Populate the system with demo data (Wharfs, Vessels, Agents, Active Bookings).*
    ```bash
    php artisan migrate:fresh --seed
    ```

5.  **Run the Application**
    *In two separate terminals run:*
    ```bash
    php artisan serve
    ```
    ```bash
    npm run dev
    ```

---

## 🗺️ Navigation Map

| Module | URL Route | Description |
| :--- | :--- | :--- |
| **Command Center** | `/dashboard` | High-level executive overview. |
| **Berth Planner** | `/` or `/home` | Main scheduling interface. |
| **Mobile Ops** | `/ops` | Field operations app (Mobile optimized). |
| **Client Portal** | `/portal` | External view for agents/customers. |
| **Billing** | `/billing` | Finance & Invoice management. |
| **Vessels** | `/vessels` | Vessel database & registry. |
| **Agents** | `/agents` | Shipping Agent CRUD management. |
| **GIS Map** | `/map` | Asset tracking via Satellite Digital Twin. |
| **Wharfs** | `/wharfs` | Port Infrastructure management. |

---

## 🧪 Testing Scenarios

1.  **Safety Check**: Try to book a Large Vessel (e.g., *MV Nautica Gamble*) into a Small Berth (e.g., *Alpha Jetty*). The system will block it.
2.  **Live Billing**: Open `/ops` on one screen and `/billing` on another. Tap "Secure Lines" in Mobile Ops -> Watch the invoice appear in Billing. Tap "Request Water" -> Watch line item add.
3.  **Client View**: Log in as an Admin and visit `/portal`. You will see the port from the perspective of "Baram Shipyard Agents".

---

*Verified for Production Readiness - Dec 2025*