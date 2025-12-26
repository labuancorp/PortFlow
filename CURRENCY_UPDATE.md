# Currency Update Summary

## Overview
All transactions in PortFlow have been updated to use **Malaysian Ringgit (RM/MYR)** instead of USD ($).

## Files Modified

### 1. View Files (Blade Templates)
**File**: `resources/views/livewire/view-booking.blade.php`
- **Line 108**: Changed unit price display from `$` to `RM`
- **Line 110**: Changed item total price display from `$` to `RM`
- **Line 117**: Changed invoice total amount display from `$` to `RM`

**File**: `resources/views/livewire/billing/index.blade.php`
- **Line 127**: Already correctly displays `RM` (no change needed)

### 2. Service Layer
**File**: `app/Services/BillingService.php`
- **Lines 15, 18, 19**: Updated rate comments from `$` to `RM`
- **Line 61**: Updated dockage fee description from `\$` to `RM`

**File**: `app/Livewire/Billing/Index.php`
- **Line 20**: Already correctly uses `RM` in comment (no change needed)
- **Line 62**: Already correctly uses `RM` in description (no change needed)

### 3. Documentation
**File**: `README.md`
- Added currency specification in the "Integrated Revenue Assurance" section
- New bullet point: "**Currency**: All transactions are in **Malaysian Ringgit (RM/MYR)**."

**File**: `PRD.md`
- Added new section at the end documenting currency usage

## Rate Card (All in RM)
Current rates configured in the system:
- Dockage: RM 1.50 per meter of LOA per hour
- Wharfage (Fixed): RM 500.00
- Line Handling: RM 250.00
- Fuel: RM 1.20 per liter
- Water: RM 5.00 per MT

## Testing Recommendations
1. Navigate to the Berth Planner and view a booking with an invoice
2. Verify all amounts display with "RM" prefix
3. Generate a new invoice from the Billing page
4. Confirm all line items and totals show "RM" currency

## Notes
- All database fields remain as DECIMAL types (no schema changes needed)
- The currency symbol is purely presentational (view layer)
- Future enhancement: Consider adding a currency configuration setting for multi-currency support
