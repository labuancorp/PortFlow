# Phase 6 - Step 2 Complete: Model Configuration Summary

## ✅ **100% Complete - All 8 Models Fully Configured**

---

## 📦 **Model Details**

### **1. Pilot Model**
**Purpose:** Manage pilot registry and availability

**Key Features:**
- License tracking with expiry validation
- Certification for different vessel types (JSON array)
- Availability status (available, on_duty, off_duty, on_leave)
- Hourly rate configuration

**Helper Methods:**
- `isAvailable()` - Check if pilot is free
- `isLicenseValid()` - Verify license not expired
- `canPilotVessel($vesselType)` - Check certification for vessel type
- `scopeAvailable()` - Query only available pilots with valid licenses

**Business Logic:**
- Pilots can only be assigned if status = 'available' AND license not expired
- Certifications stored as JSON array (e.g., ['Tanker', 'OSV', 'Container'])

---

### **2. Tugboat Model**
**Purpose:** Manage tugboat fleet and capabilities

**Key Features:**
- Bollard pull capacity (towing power in tons)
- Availability status (available, in_service, maintenance, out_of_service)
- Certificate expiry tracking
- Captain contact information

**Helper Methods:**
- `isAvailable()` - Check if tugboat is free
- `isCertificateValid()` - Verify certificate not expired
- `canTowVessel($vesselGRT)` - Calculate if tugboat has sufficient power
  - **Rule:** Bollard pull must be ≥ 10% of vessel GRT
- `scopeAvailable()` - Query available tugboats with valid certificates
- `scopeCapableOf($requiredPull)` - Find tugboats with minimum bollard pull

**Business Logic:**
- Tugboats can only be assigned if status = 'available' AND certificate valid
- Bollard pull calculation ensures safe towing operations

---

### **3. PilotageRequest Model**
**Purpose:** Manage pilot dispatch and billing

**Key Features:**
- Service types: inbound, outbound, shifting
- Status workflow: requested → assigned → in_progress → completed
- Boarding point tracking
- Weather condition logging
- Automated fee calculation

**Helper Methods:**
- `calculateFee()` - Auto-calculate pilotage charges with surcharges:
  - **Base:** hours × pilot_rate_per_hour
  - **Night surcharge:** +50% (22:00-06:00)
  - **Weather surcharge:** +30% (rough/stormy/poor visibility)
- `complete()` - Mark service complete, calculate fee, free up pilot

**Business Logic:**
- Pilot status changes to 'on_duty' when assigned
- Pilot status returns to 'available' when service completed
- Fees auto-calculated on completion

---

### **4. TowageRequest Model**
**Purpose:** Manage tugboat dispatch and billing

**Key Features:**
- Service types: berthing, unberthing, shifting, escort
- Multiple tugboats support (tugboats_required field)
- Location tracking (from/to)
- Weather condition logging
- Automated fee calculation

**Helper Methods:**
- `calculateFee()` - Auto-calculate towage charges with surcharges:
  - **Base:** hours × tugboat_rate_per_hour × tugboats_required
  - **Night surcharge:** +50% (22:00-06:00)
  - **Weather surcharge:** +40% (rough/stormy/poor visibility)
  - **Escort premium:** +25% (for escort services)
- `complete()` - Mark service complete, calculate fee, free up tugboat

**Business Logic:**
- Tugboat status changes to 'in_service' when assigned
- Tugboat status returns to 'available' when service completed
- Multiple tugboats can be assigned to one request

---

### **5. BunkerRequest Model**
**Purpose:** Manage fuel/water/provisions delivery and billing

**Key Features:**
- Fuel types: diesel, mgo, hfo, lng, freshwater, provisions
- Quantity tracking (requested vs. delivered)
- Delivery scheduling
- Supplier and delivery method tracking
- Automated cost calculation

**Helper Methods:**
- `calculateCost()` - Auto-calculate bunker charges:
  - **Base:** actual_quantity_delivered × unit_price
  - **Barge surcharge:** +RM 500 (fixed)
  - **Truck surcharge:** +RM 200 (fixed)
- `complete($actualQuantity)` - Mark delivery complete, deduct from inventory
- `approve()` - Check inventory availability before approval

**Business Logic:**
- System checks FuelInventory before approving request
- Inventory auto-deducted on delivery completion
- Actual quantity may differ from requested (recorded separately)

---

### **6. FuelInventory Model**
**Purpose:** Track fuel stock levels and pricing

**Key Features:**
- Fuel types: diesel, mgo, hfo, lng, freshwater
- Current stock vs. minimum threshold
- Maximum capacity tracking
- Price per unit management
- Last restock timestamp

**Helper Methods:**
- `isLowStock()` - Check if stock ≤ minimum_threshold
- `isCriticalStock()` - Check if stock ≤ 50% of minimum_threshold
- `getStockPercentage()` - Calculate % of capacity used
- `canFulfillOrder($quantity)` - Check if sufficient stock available
- `restock($quantity, $pricePerUnit)` - Add stock (capped at max capacity)
- `scopeLowStock()` - Query all low-stock items
- `scopeCriticalStock()` - Query critical-stock items

**Business Logic:**
- Low-stock alerts trigger when current_stock ≤ minimum_threshold
- Critical alerts trigger at 50% of minimum threshold
- Restocking cannot exceed maximum_capacity

---

### **7. MooringService Model**
**Purpose:** Manage mooring gang scheduling and billing

**Key Features:**
- Service types: mooring, unmooring, line_handling, line_boat
- Gang size tracking (number of crew)
- Supervisor assignment with contact
- Line boat requirement flag
- Automated fee calculation

**Helper Methods:**
- `calculateFee()` - Auto-calculate mooring charges with surcharges:
  - **Base:** hours × gang_size × RM 50/crew/hour
  - **Night surcharge:** +50% (22:00-06:00)
  - **Line boat fee:** +RM 300/hour
  - **Weekend surcharge:** +30%
- `complete()` - Mark service complete, calculate fee

**Business Logic:**
- Gang size typically 4-8 crew members
- Line boat adds significant cost (RM 300/hour)
- Weekend operations cost 30% more

---

### **8. StsOperation Model**
**Purpose:** Manage Ship-to-Ship transfer operations with safety compliance

**Key Features:**
- Two vessel tracking (source + receiving)
- Cargo types: crude_oil, lng, lpg, chemicals, fuel, other
- Safety zone radius (default 500m)
- Weather monitoring (wave height, wind speed)
- Permit approval workflow
- Auto-generated reference numbers (STS-YYYYMMDD-XXXXXX)

**Helper Methods:**
- `isSafeWeather()` - Check if conditions safe for STS:
  - **Max wave height:** 1.5 meters
  - **Max wind speed:** 15 knots
- `areVesselsCompatible()` - Check if vessels can safely transfer:
  - **Rule:** GRT difference must be ≤ 50% of average GRT
- `calculateFee()` - Auto-calculate STS charges with surcharges:
  - **Base:** quantity × RM 5/ton
  - **Hazardous cargo:** +50% (LNG/LPG/chemicals)
  - **Night operation:** +40% (22:00-06:00)
  - **Large quantity:** +RM 5000 (>1000 tons)
- `approvePermit($approverName)` - Approve only if weather safe AND vessels compatible
- `complete()` - Mark operation complete, calculate fee

**Business Logic:**
- Permit cannot be approved if weather unsafe
- Permit cannot be approved if vessels incompatible
- Reference number auto-generated on creation
- Hazardous cargo (LNG/LPG/chemicals) costs 50% more

---

## 🔗 **Relationships Configured**

### **PortCall Relationships (Updated)**
```php
$portCall->pilotageRequests  // All pilot services for this port call
$portCall->towageRequests    // All tugboat services
$portCall->bunkerRequests    // All fuel deliveries
$portCall->mooringServices   // All mooring services
```

### **Service Relationships**
```php
$pilotageRequest->portCall   // Parent port call
$pilotageRequest->pilot      // Assigned pilot

$towageRequest->portCall     // Parent port call
$towageRequest->tugboat      // Assigned tugboat

$bunkerRequest->portCall     // Parent port call

$mooringService->portCall    // Parent port call

$stsOperation->sourceVessel      // Vessel transferring FROM
$stsOperation->receivingVessel   // Vessel transferring TO
$stsOperation->agent             // Agent organization
```

---

## 💰 **Automated Fee Calculation Summary**

### **Pilotage Fees**
- **Base:** hours × pilot_rate_per_hour
- **Night:** +50%
- **Bad weather:** +30%

### **Towage Fees**
- **Base:** hours × tugboat_rate_per_hour × tugboats_required
- **Night:** +50%
- **Bad weather:** +40%
- **Escort service:** +25%

### **Bunker Costs**
- **Base:** actual_quantity × unit_price
- **Barge delivery:** +RM 500
- **Truck delivery:** +RM 200

### **Mooring Fees**
- **Base:** hours × gang_size × RM 50/crew
- **Night:** +50%
- **Line boat:** +RM 300/hour
- **Weekend:** +30%

### **STS Fees**
- **Base:** quantity × RM 5/ton
- **Hazardous cargo:** +50%
- **Night operation:** +40%
- **Large quantity (>1000 tons):** +RM 5000

---

## 🎯 **Business Rules Implemented**

### **Safety Rules**
1. ✅ Pilots cannot be assigned if license expired
2. ✅ Tugboats cannot be assigned if certificate expired
3. ✅ Tugboat must have sufficient bollard pull (≥10% of vessel GRT)
4. ✅ Bunker requests rejected if insufficient inventory
5. ✅ STS permits rejected if weather unsafe (wave >1.5m OR wind >15 knots)
6. ✅ STS permits rejected if vessels incompatible (GRT difference >50%)

### **Inventory Rules**
1. ✅ Fuel inventory deducted on bunker delivery completion
2. ✅ Low-stock alerts when stock ≤ minimum_threshold
3. ✅ Critical alerts when stock ≤ 50% of minimum
4. ✅ Restocking capped at maximum_capacity

### **Billing Rules**
1. ✅ All fees auto-calculated on service completion
2. ✅ Night operations (22:00-06:00) cost 50% more
3. ✅ Bad weather operations cost 30-40% more
4. ✅ Weekend operations cost 30% more
5. ✅ Hazardous cargo costs 50% more

---

## 📊 **Status Workflows**

### **Pilotage/Towage/Mooring**
requested → assigned → in_progress → completed/cancelled

### **Bunker Requests**
requested → approved → scheduled → in_progress → completed/cancelled

### **STS Operations**
requested → approved → in_progress → completed/cancelled

---

## 🚀 **Next Steps (Step 3: Livewire Components)**

Now that all models are configured, we need to create the UI:

1. **Pilotage & Towage Dashboard** - Dispatch center
2. **Bunker Management** - Fuel requests and inventory
3. **Mooring Coordinator** - Gang scheduling
4. **STS Operations Manager** - Permit workflow

---

**Status:** 35% Complete (Database + Models fully configured)
**Next Action:** Create Livewire components for user interfaces
