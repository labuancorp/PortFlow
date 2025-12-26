# PortFlow Access Model: Petronas/Shell Login Strategy

## 🔐 Current Implementation

### **YES - Petronas/Shell Need Login Credentials**

Here's why and how it works:

---

## 📊 Two-Tier Access Model

### **Tier 1: Public Information (No Login Required)**
**URL**: `/` (Home page / Berth Planner)

**What Anyone Can See**:
- ❌ **NOTHING** - The main berth planner requires login
- This is intentional for security and data privacy

**Why No Public Access?**
- Berth schedules contain **commercial sensitive information**
- Vessel movements can be **security risks**
- Pricing and billing are **confidential**
- Competitor intelligence protection

---

### **Tier 2: Client Portal (Login Required)** ✅
**URL**: `/portal`  
**Component**: `AgentPortal.php`

**Who Gets Access**:
1. **Shipping Agents** (e.g., Baram Shipyard Agents)
   - Login: `agent@baram.com`
   - Password: `password`
   - Role: `agent`

2. **Oil Companies** (e.g., Petronas, Shell)
   - Login: `petronas@fleet.com`
   - Password: `password`
   - Role: `client`

3. **ASB Admin** (for demo purposes)
   - Login: `admin@asb.com`
   - Password: `password`
   - Role: `admin` (can view all agent portals)

---

## 🎯 What Petronas Sees After Login

### **Dashboard Overview**
```
┌─────────────────────────────────────────┐
│ PortFlow Connect                        │
│ Organization: Petronas Carigali         │
├─────────────────────────────────────────┤
│                                         │
│ FLEET OVERVIEW                          │
│                                         │
│ ┌─────────┐ ┌─────────┐ ┌───────────┐ │
│ │ Live    │ │ Incoming│ │ Request   │ │
│ │ Vessels │ │ 24h     │ │ New Berth │ │
│ │   2     │ │   3     │ │    +      │ │
│ └─────────┘ └─────────┘ └───────────┘ │
│                                         │
│ TABS: [Live] [Scheduled] [History]     │
│                                         │
│ 🚢 OSV Explorer                         │
│ Status: ALONGSIDE                       │
│ Berth: Main Wharf 2                    │
│ ETA: 26 Dec, 14:00                     │
│ [Active Billing]                        │
│ ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ │
│                                         │
│ 🚢 MV Nautica Gamble                   │
│ Status: SCHEDULED                       │
│ ETA: 27 Dec, 08:00                     │
│ Berth: Main Wharf 3                    │
│ ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ │
└─────────────────────────────────────────┘
```

---

## 🔑 Login Flow

### **Step 1: User Visits `/portal`**
```
Browser → http://localhost:8080/portal
↓
System checks: Is user logged in?
├─ YES → Show their organization's vessels
└─ NO  → Redirect to /login
```

### **Step 2: Login Page**
```
┌─────────────────────────────────────┐
│ PortFlow Login                      │
├─────────────────────────────────────┤
│ Email: petronas@fleet.com           │
│ Password: ••••••••                  │
│                                     │
│ [Sign In]                           │
│                                     │
│ Don't have an account?              │
│ Contact ASB: admin@asb.com          │
└─────────────────────────────────────┘
```

### **Step 3: Authentication**
```php
// AgentPortal.php - mount() method
$user = Auth::user();

if ($user && $user->organization_id) {
    // Show ONLY vessels belonging to this organization
    $this->agentId = $user->organization_id;
}
```

### **Step 4: Data Filtering**
```php
// Only show vessels for Petronas
$portCalls = PortCall::where('agent_id', $this->agentId)
    ->with(['vessel', 'berth', 'invoice'])
    ->get();

// Petronas CANNOT see Shell's vessels
// Shell CANNOT see Petronas's vessels
```

---

## 🏢 Organization Structure

### **Database Schema**
```sql
users
├─ id
├─ name
├─ email
├─ password
├─ role (admin, agent, client)
└─ organization_id → links to organizations table

organizations
├─ id
├─ name (e.g., "Petronas Carigali")
├─ type (authority, agent, client, vendor)
└─ code (e.g., "PETRONAS")

port_calls
├─ id
├─ vessel_id
├─ agent_id → links to organizations (Petronas)
└─ status
```

### **Access Control**
```php
// Petronas user logs in
User {
  email: "petronas@fleet.com",
  organization_id: 3, // Petronas Carigali
  role: "client"
}

// System shows ONLY port calls where:
PortCall::where('agent_id', 3) // Petronas's ID
```

---

## 🎯 Why Login is Required

### **1. Data Privacy** 🔒
- Petronas shouldn't see Shell's vessel schedules
- Shell shouldn't see Petronas's pricing
- Competitors can't spy on each other

### **2. Personalization** 🎨
- Dashboard shows "YOUR vessels" not "ALL vessels"
- Notifications sent to YOUR email
- Invoices linked to YOUR account

### **3. Audit Trail** 📝
- Track who requested which berth
- Log who viewed which invoice
- Accountability for bookings

### **4. Security** 🛡️
- Prevent unauthorized berth requests
- Protect commercial sensitive data
- Comply with data protection regulations

### **5. Billing Integration** 💰
- Invoices sent to correct organization
- Payment tracking per client
- Credit limit enforcement

---

## 🚀 Account Creation Process

### **Option 1: ASB Admin Creates Accounts**
```
1. Petronas contacts ASB: "We want portal access"
2. ASB Admin logs into PortFlow
3. Admin creates organization: "Petronas Carigali"
4. Admin creates user:
   - Email: petronas@fleet.com
   - Role: client
   - Organization: Petronas Carigali
5. System sends welcome email with temp password
6. Petronas logs in and changes password
```

### **Option 2: Self-Registration (Future Enhancement)**
```
1. Petronas visits /register
2. Fills form:
   - Company: Petronas Carigali
   - Email: petronas@fleet.com
   - Tax ID: 123456789
3. System sends to ASB Admin for approval
4. Admin approves → Account activated
5. Petronas receives activation email
```

---

## 📱 What Petronas Can Do After Login

### **1. View Live Vessels** 👀
- See all their vessels currently in port
- Real-time status updates
- Berth assignments
- Active billing information

### **2. Request New Berths** 📝
```
Click "Request New Berth"
↓
Modal opens:
- Select Vessel: [OSV Explorer]
- ETA: [26 Dec 2025, 14:00]
- ETD: [26 Dec 2025, 18:00]
- [Submit Request]
↓
System validates:
✅ Vessel exists in their fleet
✅ ETA is in the future
✅ ETD is after ETA
✅ No conflicts
↓
Booking created with status: "requested"
↓
ASB Admin approves → Status: "approved"
↓
Berth assigned → Status: "scheduled"
```

### **3. Register New Vessels** 🚢
```
Click "Register New Vessel"
↓
Modal opens:
- Vessel Name: [MV SEALINK 178]
- IMO Number: [9123456]
- Type: [Offshore Support Vessel]
- LOA: [55.0 m]
- Draft: [5.0 m]
- [Add Vessel]
↓
Vessel added to Petronas's fleet
↓
Can now request berths for this vessel
```

### **4. View History** 📊
```
Click "History" tab
↓
See all completed port calls:
- Vessel name
- Dates
- Duration
- Total cost
- Invoice download link
```

### **5. Download Invoices** 💳
```
Click on completed port call
↓
See invoice details:
- Line items
- Timestamps (GPS-verified)
- Total amount
- [Download PDF] button
```

---

## 🔐 Security Features

### **1. Role-Based Access Control (RBAC)**
```php
// Middleware checks
if ($user->role === 'client') {
    // Can only see own vessels
    // Cannot access admin functions
    // Cannot see other clients' data
}

if ($user->role === 'admin') {
    // Can see everything
    // Can approve/reject requests
    // Can manage all organizations
}
```

### **2. Data Isolation**
```php
// Every query is filtered by organization
PortCall::where('agent_id', Auth::user()->organization_id)

// Petronas CANNOT do:
PortCall::all() // Would see everyone's data
```

### **3. Session Management**
- Auto-logout after 2 hours of inactivity
- Secure session tokens
- CSRF protection

### **4. Password Security**
- Bcrypt hashing
- Minimum 8 characters
- Password reset via email

---

## 🎯 The "Public-Facing Page" Misconception

### **Why NOT Public?**

**Scenario**: If `/portal` was public (no login):
```
❌ Shell could see Petronas's vessel schedules
❌ Competitors could see pricing
❌ Anyone could request berths (spam)
❌ No accountability
❌ No personalization
❌ No invoice tracking
❌ Security nightmare
```

**Correct Approach**: Login Required
```
✅ Petronas sees ONLY their vessels
✅ Pricing is confidential
✅ Requests are authenticated
✅ Full audit trail
✅ Personalized dashboard
✅ Secure invoice access
✅ Data protection compliance
```

---

## 📧 Sample Welcome Email

```
Subject: Your PortFlow Portal Access

Dear Petronas Fleet Manager,

Welcome to PortFlow Connect - ASB's digital port platform!

Your account has been created:
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Login URL: https://portflow.asb.com/portal
Email: petronas@fleet.com
Temporary Password: TempPass123!
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

What you can do:
✅ Track your vessels in real-time
✅ Request berths online (24/7)
✅ Download invoices instantly
✅ View historical data
✅ Register new vessels

Please log in and change your password.

Need help? Contact: support@asb.com

Best regards,
ASB Digital Team
```

---

## ✅ Summary

### **Do Petronas/Shell Need Login?**
**YES - Absolutely Required**

### **Why?**
1. ✅ **Data Privacy** - See only YOUR vessels
2. ✅ **Security** - Prevent unauthorized access
3. ✅ **Personalization** - Tailored dashboard
4. ✅ **Audit Trail** - Track all actions
5. ✅ **Billing** - Link invoices to accounts

### **Is Public Access Enough?**
**NO - Would Be a Security Disaster**

### **How Do They Get Access?**
1. Contact ASB
2. ASB creates account
3. Receive welcome email
4. Log in and start using

### **What's the User Experience?**
```
Visit /portal → Login → See YOUR vessels → Request berths → Track in real-time → Download invoices
```

**It's like online banking - you wouldn't want that to be public either!** 🏦🔒
