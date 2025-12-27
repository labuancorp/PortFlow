# Warehouse Billing System

## Overview
The warehouse billing system provides **live, real-time billing** for cargo stored in the port yard, similar to the berth planner's live billing feature.

## Billing Formula

### Base Calculation
```
Daily Charge = Volume (m³) × Rate per m³ per day × Days Stored
```

### Dangerous Goods Surcharge
```
DG Surcharge = Base Charge × DG Surcharge Percentage (default: 50%)
```

### Total Charge
```
Total = MAX(Minimum Charge, Base Charge + DG Surcharge)
```

## Default Rates
- **Base Rate**: RM 5.00 per m³ per day
- **DG Surcharge**: +50% for dangerous goods
- **Minimum Charge**: RM 50.00 per day

## Features

### For Agents
- **Live Billing Widget** on dashboard (if subscribed)
- Shows:
  - Total current charges (RM)
  - Number of items in storage
  - Breakdown by item (tracking number, volume, days, charges)
- **Auto-calculated** from cargo arrival until discharge

### For Admins
- **Organization Summary** on dashboard
- Shows all subscribed agents' charges
- Real-time aggregation across all organizations
- Detailed breakdown available

## How It Works

### 1. Cargo Arrival
- When cargo manifest is created and items are placed in zones
- Billing starts automatically from `created_at` timestamp

### 2. Daily Calculation
```php
Days Stored = Current Date - Cargo Arrival Date (minimum 1 day)
```

### 3. Discharge
- When cargo status changes to 'discharged' or 'completed'
- Item is excluded from live billing
- Final charge is calculated for invoicing

### 4. Live Updates
- Recalculated on every dashboard load
- No cron jobs needed - always current

## Database Structure

### `warehouse_billing_rates` Table
- `zone_type`: general, dg, refrigerated
- `rate_per_m3_per_day`: Base rate
- `dg_surcharge_percentage`: DG surcharge %
- `minimum_charge`: Minimum daily charge
- `is_active`: Enable/disable rate

### `cargo_items` Table (existing)
- `volume_m3`: Volume for billing
- `dg_class`: Triggers DG surcharge if set
- `created_at`: Start of billing period
- `status`: 'discharged'/'completed' stops billing

## Admin Controls

### Subscription Management
```php
// Enable warehouse service for an agent
Organization::find($id)->update(['warehouse_subscribed' => true]);

// Disable
Organization::find($id)->update(['warehouse_subscribed' => false]);
```

### Rate Management
```php
// Update rates in warehouse_billing_rates table
DB::table('warehouse_billing_rates')
    ->where('zone_type', 'general')
    ->update([
        'rate_per_m3_per_day' => 7.50,  // Increase rate
        'dg_surcharge_percentage' => 75.00  // Increase DG surcharge
    ]);
```

## Example Calculation

### Scenario
- **Cargo**: 10 m³ general cargo
- **Storage**: 5 days
- **DG Class**: None

### Calculation
```
Base Charge = 10 m³ × RM 5.00 × 5 days = RM 250.00
DG Surcharge = RM 0 (no DG)
Total = RM 250.00
```

### With DG
- **Cargo**: 10 m³ dangerous goods (Class 3)
- **Storage**: 5 days

```
Base Charge = 10 m³ × RM 5.00 × 5 days = RM 250.00
DG Surcharge = RM 250.00 × 50% = RM 125.00
Total = RM 375.00
```

## Integration Points

### Dashboard (Agent View)
- Widget shows live charges if subscribed
- Click to view detailed breakdown
- Links to warehouse map

### Dashboard (Admin View)
- Summary table of all agents
- Total revenue from warehouse
- Click agent to see details

### Billing Module
- Generate invoices from live charges
- Export billing reports
- Historical charge tracking

## Future Enhancements
1. Zone-specific rates (refrigerated, hazmat)
2. Volume discounts for bulk storage
3. Long-term storage penalties (>30 days)
4. Automated invoice generation
5. Payment integration
