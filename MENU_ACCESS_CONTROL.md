# PortFlow Menu Access Control Matrix

## 🎯 Overview
This document defines what menu items each user role can see and access in the PortFlow system.

---

## 👥 User Roles

### 1. **Admin** (`role: 'admin'`)
- **Organization**: Asian Supply Base Authority
- **Example User**: admin@asb.com
- **Full System Access**: YES

### 2. **Agent** (`role: 'agent'`)
- **Organization**: Shipping Agents (e.g., Baram Shipyard Agents)
- **Example User**: agent@baram.com
- **Limited Access**: Can manage bookings for their clients

### 3. **Client** (`role: 'client'`)
- **Organization**: Oil Companies (e.g., Petronas, Shell)
- **Example User**: petronas@fleet.com (to be created)
- **Portal Only**: Can only view their own vessels via `/portal`

---

## 📋 Menu Access Matrix

| Menu Item | Route | Admin | Agent | Client | Description |
|-----------|-------|-------|-------|--------|-------------|
| **Command Center** | `/dashboard` | ✅ | ✅ | ❌ | Executive overview with KPIs |
| **GIS Map View** | `/map` | ✅ | ✅ | ❌ | Satellite map with vessel tracking |
| **Crew Terminal** | `/crew/terminal` | ✅ | ✅ | ❌ | Immigration & security checkpoint |
| **Analytics** | `/analytics` | ✅ | ✅ | ❌ | Performance metrics & reports |
| **Berth Planner** | `/home` | ✅ | ✅ | ❌ | Main scheduling interface (Gantt chart) |
| **Vessels** | `/vessels` | ✅ | ✅ | ❌ | Vessel database & registry |
| **Agents** | `/agents` | ✅ | ❌ | ❌ | Shipping agent management (Admin only) |
| **Wharf Registry** | `/wharfs` | ✅ | ❌ | ❌ | Port infrastructure management (Admin only) |
| **Billing** | `/billing` | ✅ | ✅ | ❌ | Invoice management |
| **System Health** | `/admin/health` | ✅ | ❌ | ❌ | System monitoring (Admin only) |
| **Client Portal View** | `/portal` | ✅ | ✅ | ✅ | External client view (All can access) |

---

## 🔐 Access Rules by Role

### **Admin** (Full Access)
```
✅ Command Center
✅ GIS Map View
✅ Crew Terminal
✅ Analytics
✅ Berth Planner
✅ Vessels
✅ Agents (Admin Only)
✅ Wharf Registry (Admin Only)
✅ Billing
✅ System Health (Admin Only)
✅ Client Portal View
```

### **Agent** (Operational Access)
```
✅ Command Center
✅ GIS Map View
✅ Crew Terminal
✅ Analytics
✅ Berth Planner
✅ Vessels
❌ Agents (Hidden)
❌ Wharf Registry (Hidden)
✅ Billing
❌ System Health (Hidden)
✅ Client Portal View
```

**Rationale**: Agents need operational tools to manage bookings, track vessels, and handle billing for their clients. They don't need to manage other agents or modify port infrastructure.

### **Client** (Portal Only)
```
❌ All sidebar menu items are HIDDEN
✅ Client Portal ONLY (/portal)
```

**Rationale**: Clients (Petronas, Shell) should only see their own vessels through the dedicated portal. They don't need access to the internal operations dashboard.

---

## 🎨 Implementation Strategy

### **Blade Template Changes**
File: `resources/views/components/layouts/app.blade.php`

#### 1. **Hide Entire Sidebar for Clients**
```blade
@if(auth()->user()->role !== 'client')
    <!-- Sidebar -->
    <aside class="w-64 bg-slate-900...">
        <!-- Navigation -->
    </aside>
@endif
```

#### 2. **Agent-Specific Menu Hiding**
```blade
<!-- Hide Agents menu from non-admins -->
@if(auth()->user()->role === 'admin')
    <li>
        <a href="{{ route('agents.index') }}">Agents</a>
    </li>
@endif

<!-- Hide Wharf Registry from non-admins -->
@if(auth()->user()->role === 'admin')
    <li>
        <a href="{{ route('wharfs.index') }}">Wharf Registry</a>
    </li>
@endif
```

#### 3. **Redirect Clients to Portal**
Add middleware to redirect clients attempting to access admin routes:

```php
// In routes/web.php or middleware
if (auth()->user()->role === 'client' && !request()->routeIs('portal')) {
    return redirect()->route('agent.portal');
}
```

---

## 🚦 Route Protection

### **Middleware Groups**
```php
// routes/web.php

// Admin Only Routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/agents', [AgentController::class, 'index'])->name('agents.index');
    Route::get('/wharfs', [WharfController::class, 'index'])->name('wharfs.index');
    Route::get('/admin/health', [AdminController::class, 'health'])->name('admin.health');
});

// Admin + Agent Routes
Route::middleware(['auth', 'role:admin,agent'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/map', MapView::class)->name('map.index');
    Route::get('/crew/terminal', CrewTerminal::class)->name('crew.terminal');
    Route::get('/analytics', Analytics::class)->name('analytics');
    Route::get('/home', BerthPlanner::class)->name('home');
    Route::get('/vessels', VesselList::class)->name('vessels.index');
    Route::get('/billing', BillingIndex::class)->name('billing.index');
});

// All Authenticated Users (Including Clients)
Route::middleware(['auth'])->group(function () {
    Route::get('/portal', AgentPortal::class)->name('agent.portal');
});
```

---

## 📱 Mobile Ops Access

**Route**: `/ops`

| Role | Access | Notes |
|------|--------|-------|
| Admin | ✅ | Full access |
| Agent | ✅ | Can update vessel status |
| Client | ❌ | Not intended for clients |

This is the field operations interface for ground crew. Clients don't need this.

---

## 🎯 User Experience by Role

### **Admin Login Flow**
```
1. Login → Dashboard
2. See full sidebar with all menu items
3. Can access System Health
4. Can manage Agents and Wharfs
5. Can view Client Portal to see client perspective
```

### **Agent Login Flow**
```
1. Login → Dashboard
2. See sidebar WITHOUT:
   - Agents
   - Wharf Registry
   - System Health
3. Can manage bookings and billing
4. Can view Client Portal
```

### **Client Login Flow**
```
1. Login → Automatically redirected to /portal
2. NO sidebar shown
3. See only their organization's vessels
4. Can request new berths
5. Can download invoices
```

---

## ✅ Implementation Checklist

- [x] Update `app.blade.php` to hide sidebar for clients
- [x] Add `@if` conditions for admin-only menu items
- [x] Add `@if` conditions for agent-restricted items
- [x] Create role middleware (`app/Http/Middleware/CheckRole.php`)
- [x] Update routes with middleware groups
- [x] Add automatic redirect for clients to portal
- [ ] Test each role's access
- [ ] Update documentation

---

## 🔒 Security Notes

1. **Never rely on UI hiding alone** - Always protect routes with middleware
2. **Validate on backend** - Check user role in controllers
3. **Audit logging** - Track who accesses what
4. **Session management** - Auto-logout after inactivity
5. **CSRF protection** - Enabled by default in Laravel

---

## 📊 Summary

| Role | Menu Items Visible | Primary Interface |
|------|-------------------|-------------------|
| **Admin** | 11 items (All) | Full Dashboard |
| **Agent** | 8 items | Operational Dashboard |
| **Client** | 0 items (Portal only) | Client Portal (`/portal`) |

**Key Principle**: *Least Privilege Access* - Users only see what they need to do their job.
