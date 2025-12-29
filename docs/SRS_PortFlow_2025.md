# System Requirements Specification (SRS)
# PortFlow 2.0 - Technical Specification

**Document Version:** 2.0  
**Date:** December 29, 2025  
**Project:** PortFlow 2.0  
**Classification:** Technical - Confidential  

---

## 1. System Architecture

### 1.1 High-Level Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                     Presentation Layer                       │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐      │
│  │ Web Browser  │  │ Mobile App   │  │  API Clients │      │
│  └──────────────┘  └──────────────┘  └──────────────┘      │
└─────────────────────────────────────────────────────────────┘
                            ▼
┌─────────────────────────────────────────────────────────────┐
│                    Application Layer                         │
│  ┌──────────────────────────────────────────────────────┐   │
│  │         Laravel 11 (PHP 8.2+)                        │   │
│  │  ┌────────────┐  ┌────────────┐  ┌────────────┐    │   │
│  │  │ Livewire 3 │  │  REST API  │  │ WebSockets │    │   │
│  │  └────────────┘  └────────────┘  └────────────┘    │   │
│  └──────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────┘
                            ▼
┌─────────────────────────────────────────────────────────────┐
│                     Business Logic Layer                     │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐      │
│  │   Services   │  │  Repositories│  │   Events     │      │
│  └──────────────┘  └──────────────┘  └──────────────┘      │
└─────────────────────────────────────────────────────────────┘
                            ▼
┌─────────────────────────────────────────────────────────────┐
│                      Data Layer                              │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐      │
│  │ PostgreSQL   │  │    Redis     │  │ File Storage │      │
│  │   + PostGIS  │  │              │  │              │      │
│  └──────────────┘  └──────────────┘  └──────────────┘      │
└─────────────────────────────────────────────────────────────┘
                            ▼
┌─────────────────────────────────────────────────────────────┐
│                   Integration Layer                          │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐      │
│  │  ERP (SAP)   │  │     AIS      │  │  Email/SMS   │      │
│  └──────────────┘  └──────────────┘  └──────────────┘      │
└─────────────────────────────────────────────────────────────┘
```

### 1.2 Technology Stack

**Backend Framework:**
- Laravel 11.x (PHP 8.2+)
- Livewire 3.x for reactive components
- Laravel Sanctum for API authentication
- Laravel Horizon for queue monitoring

**Database:**
- PostgreSQL 15+ (primary database)
- PostGIS extension for spatial data
- Redis 7+ (caching, sessions, queues)

**Frontend:**
- Livewire 3 (server-side rendering)
- Alpine.js 3.x (client-side interactions)
- Tailwind CSS 3.x (styling)
- Chart.js 4.x (data visualization)

**Infrastructure:**
- Docker & Docker Compose
- Nginx 1.24+ (web server)
- Supervisor (process manager)
- Certbot (SSL certificates)

---

## 2. Database Design

### 2.1 Core Tables

**organizations**
```sql
CREATE TABLE organizations (
    id BIGSERIAL PRIMARY KEY,
    code VARCHAR(20) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    type VARCHAR(50) NOT NULL, -- 'authority', 'agent', 'client'
    billing_address TEXT,
    warehouse_subscribed BOOLEAN DEFAULT FALSE,
    enabled_modules JSONB,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

**users**
```sql
CREATE TABLE users (
    id BIGSERIAL PRIMARY KEY,
    organization_id BIGINT REFERENCES organizations(id),
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL, -- 'admin', 'agent', 'hse', 'asset_manager'
    remember_token VARCHAR(100),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

**vessels**
```sql
CREATE TABLE vessels (
    id BIGSERIAL PRIMARY KEY,
    organization_id BIGINT REFERENCES organizations(id),
    imo_number VARCHAR(20) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    vessel_type VARCHAR(100),
    flag_country VARCHAR(100),
    loa_meters DECIMAL(10,2),
    draft_meters DECIMAL(10,2),
    grt DECIMAL(10,2),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

**berths**
```sql
CREATE TABLE berths (
    id BIGSERIAL PRIMARY KEY,
    code VARCHAR(20) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    max_loa DECIMAL(10,2),
    max_draft DECIMAL(10,2),
    status VARCHAR(50) DEFAULT 'active',
    latitude DECIMAL(10,8),
    longitude DECIMAL(11,8),
    color VARCHAR(20),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

**port_calls**
```sql
CREATE TABLE port_calls (
    id BIGSERIAL PRIMARY KEY,
    vessel_id BIGINT REFERENCES vessels(id),
    agent_id BIGINT REFERENCES organizations(id),
    assigned_berth_id BIGINT REFERENCES berths(id),
    reference_no VARCHAR(50) UNIQUE NOT NULL,
    status VARCHAR(50) NOT NULL,
    eta TIMESTAMP,
    etd TIMESTAMP,
    ata TIMESTAMP,
    atd TIMESTAMP,
    atb TIMESTAMP,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

**invoices**
```sql
CREATE TABLE invoices (
    id BIGSERIAL PRIMARY KEY,
    port_call_id BIGINT REFERENCES port_calls(id),
    organization_id BIGINT REFERENCES organizations(id),
    invoice_no VARCHAR(50) UNIQUE NOT NULL,
    total_amount DECIMAL(15,2),
    status VARCHAR(50) DEFAULT 'draft',
    issued_date DATE,
    due_date DATE,
    erp_status VARCHAR(50),
    erp_reference_id VARCHAR(100),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### 2.2 Spatial Tables

**warehouse_zones**
```sql
CREATE TABLE warehouse_zones (
    id BIGSERIAL PRIMARY KEY,
    zone_name VARCHAR(100) NOT NULL,
    zone_type VARCHAR(50), -- 'general', 'dg', 'refrigerated'
    capacity_m3 DECIMAL(10,2),
    current_usage_m3 DECIMAL(10,2) DEFAULT 0,
    geometry GEOMETRY(POLYGON, 4326),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

CREATE INDEX idx_warehouse_zones_geometry ON warehouse_zones USING GIST(geometry);
```

**vessel_positions**
```sql
CREATE TABLE vessel_positions (
    id BIGSERIAL PRIMARY KEY,
    vessel_id BIGINT REFERENCES vessels(id),
    latitude DECIMAL(10,8),
    longitude DECIMAL(11,8),
    speed_knots DECIMAL(5,2),
    heading DECIMAL(5,2),
    timestamp TIMESTAMP,
    created_at TIMESTAMP
);

CREATE INDEX idx_vessel_positions_location ON vessel_positions USING GIST(
    ST_SetSRID(ST_MakePoint(longitude, latitude), 4326)
);
```

---

## 3. API Specifications

### 3.1 RESTful API Endpoints

**Authentication**
```
POST   /api/auth/login
POST   /api/auth/logout
POST   /api/auth/refresh
GET    /api/auth/user
```

**Berths**
```
GET    /api/berths
GET    /api/berths/{id}
POST   /api/berths
PUT    /api/berths/{id}
DELETE /api/berths/{id}
GET    /api/berths/{id}/availability
```

**Port Calls**
```
GET    /api/port-calls
GET    /api/port-calls/{id}
POST   /api/port-calls
PUT    /api/port-calls/{id}
DELETE /api/port-calls/{id}
GET    /api/port-calls/active
GET    /api/port-calls/{id}/timeline
```

**Invoices**
```
GET    /api/invoices
GET    /api/invoices/{id}
POST   /api/invoices
PUT    /api/invoices/{id}
GET    /api/invoices/{id}/pdf
POST   /api/invoices/{id}/sync-erp
```

### 3.2 API Response Format

**Success Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Example"
  },
  "message": "Operation successful"
}
```

**Error Response:**
```json
{
  "success": false,
  "error": {
    "code": "VALIDATION_ERROR",
    "message": "Validation failed",
    "details": {
      "field": ["Error message"]
    }
  }
}
```

### 3.3 Rate Limiting

- **Authenticated Users:** 60 requests/minute
- **Unauthenticated:** 10 requests/minute
- **Admin Users:** 120 requests/minute

---

## 4. Security Requirements

### 4.1 Authentication & Authorization

**Multi-Factor Authentication (MFA):**
- TOTP-based (Google Authenticator, Authy)
- Mandatory for admin users
- Optional for other users

**Role-Based Access Control (RBAC):**
```php
'roles' => [
    'admin' => ['*'], // Full access
    'agent' => ['berth.request', 'manifest.create', 'invoice.view'],
    'hse' => ['permit.approve', 'incident.manage'],
    'asset_manager' => ['asset.manage', 'booking.approve'],
    'finance' => ['invoice.manage', 'billing.view']
]
```

### 4.2 Data Encryption

**In Transit:**
- TLS 1.3 minimum
- HSTS enabled
- Certificate pinning for mobile apps

**At Rest:**
- AES-256 encryption for sensitive fields
- Database column-level encryption for passwords
- Encrypted backups

### 4.3 Security Headers

```
Strict-Transport-Security: max-age=31536000; includeSubDomains
X-Frame-Options: DENY
X-Content-Type-Options: nosniff
X-XSS-Protection: 1; mode=block
Content-Security-Policy: default-src 'self'
Referrer-Policy: no-referrer-when-downgrade
```

---

## 5. Performance Requirements

### 5.1 Response Time Targets

| Operation | Target | Maximum |
|-----------|--------|---------|
| Page Load | < 1.5s | 2.5s |
| API Call | < 300ms | 500ms |
| Database Query | < 50ms | 100ms |
| Report Generation | < 5s | 10s |

### 5.2 Caching Strategy

**Redis Cache Layers:**
```
- Session Cache (TTL: 30 minutes)
- Query Cache (TTL: 5 minutes)
- View Cache (TTL: 1 hour)
- API Response Cache (TTL: 1 minute)
```

**Cache Invalidation:**
- Event-driven invalidation
- Tag-based cache clearing
- Automatic expiry

### 5.3 Database Optimization

**Indexing Strategy:**
```sql
-- Frequently queried columns
CREATE INDEX idx_port_calls_status ON port_calls(status);
CREATE INDEX idx_port_calls_dates ON port_calls(eta, etd);
CREATE INDEX idx_invoices_status ON invoices(status);

-- Composite indexes
CREATE INDEX idx_port_calls_agent_status ON port_calls(agent_id, status);
CREATE INDEX idx_cargo_items_manifest_zone ON cargo_items(manifest_id, zone_id);

-- Full-text search
CREATE INDEX idx_vessels_search ON vessels USING GIN(to_tsvector('english', name));
```

**Query Optimization:**
- Eager loading for relationships
- Query result pagination
- Database connection pooling
- Read replicas for reporting

---

## 6. Scalability & High Availability

### 6.1 Horizontal Scaling

**Load Balancing:**
```
                    ┌──────────────┐
                    │ Load Balancer│
                    │   (Nginx)    │
                    └──────────────┘
                           │
        ┌──────────────────┼──────────────────┐
        ▼                  ▼                  ▼
   ┌─────────┐        ┌─────────┐        ┌─────────┐
   │  App    │        │  App    │        │  App    │
   │ Server 1│        │ Server 2│        │ Server 3│
   └─────────┘        └─────────┘        └─────────┘
        │                  │                  │
        └──────────────────┼──────────────────┘
                           ▼
                    ┌──────────────┐
                    │  PostgreSQL  │
                    │   (Primary)  │
                    └──────────────┘
                           │
                    ┌──────────────┐
                    │  PostgreSQL  │
                    │  (Replica)   │
                    └──────────────┘
```

### 6.2 Failover Strategy

**Database Failover:**
- Automatic failover to read replica
- Maximum downtime: 30 seconds
- Data loss tolerance: 0 transactions

**Application Failover:**
- Health checks every 10 seconds
- Automatic server removal from pool
- Session persistence via Redis

---

## 7. Backup & Disaster Recovery

### 7.1 Backup Strategy

**Database Backups:**
- Full backup: Daily at 02:00 AM
- Incremental backup: Every 6 hours
- Transaction log backup: Every 15 minutes
- Retention: 30 days

**File Backups:**
- Document uploads: Daily
- System files: Weekly
- Retention: 90 days

### 7.2 Disaster Recovery Plan

**Recovery Time Objective (RTO):** 4 hours  
**Recovery Point Objective (RPO):** 15 minutes

**DR Procedures:**
1. Activate standby database
2. Restore application servers
3. Verify data integrity
4. Switch DNS to DR site
5. Notify users

---

## 8. Monitoring & Logging

### 8.1 Application Monitoring

**Metrics Collected:**
- Request rate and response times
- Error rates and types
- Database query performance
- Cache hit/miss ratios
- Queue depth and processing time

**Tools:**
- Laravel Telescope (development)
- Laravel Horizon (queue monitoring)
- Prometheus + Grafana (production)
- Sentry (error tracking)

### 8.2 Audit Logging

**Logged Events:**
```php
[
    'user_login',
    'user_logout',
    'berth_assignment',
    'invoice_generation',
    'permit_approval',
    'data_export',
    'configuration_change'
]
```

**Log Format:**
```json
{
  "timestamp": "2025-12-29T10:30:00Z",
  "user_id": 123,
  "ip_address": "192.168.1.100",
  "action": "invoice_generation",
  "resource": "invoice:456",
  "details": {
    "amount": 5000.00,
    "port_call_id": 789
  }
}
```

---

## 9. Integration Specifications

### 9.1 ERP Integration (SAP)

**Connection Method:** REST API / IDoc

**Data Mapping:**
```
PortFlow → SAP
-----------------
Invoice → FI Document
Organization → Customer Master (KNA1)
Invoice Item → Line Item (BSEG)
Payment → Incoming Payment (F-28)
```

**Sync Frequency:**
- Invoices: Real-time (on generation)
- Payments: Hourly batch
- Master data: Daily batch

### 9.2 AIS Integration

**Data Format:** NMEA 0183 / JSON

**Sample Payload:**
```json
{
  "mmsi": "533000001",
  "imo": "9123456",
  "latitude": 5.2630,
  "longitude": 115.2430,
  "speed": 8.5,
  "heading": 270,
  "timestamp": "2025-12-29T10:30:00Z"
}
```

---

## 10. Testing Requirements

### 10.1 Unit Testing

**Coverage Target:** 80%

**Test Framework:** PHPUnit 10.x

**Example Test:**
```php
public function test_invoice_generation()
{
    $portCall = PortCall::factory()->create([
        'status' => 'alongside',
        'atb' => now()->subHours(5)
    ]);
    
    $service = new BillingService();
    $invoice = $service->generateInvoice($portCall);
    
    $this->assertNotNull($invoice);
    $this->assertEquals('draft', $invoice->status);
    $this->assertGreaterThan(0, $invoice->total_amount);
}
```

### 10.2 Integration Testing

**Test Scenarios:**
- End-to-end berth booking flow
- Invoice generation and ERP sync
- Permit approval workflow
- Real-time billing calculation

### 10.3 Performance Testing

**Load Testing:**
- Tool: Apache JMeter / Locust
- Concurrent users: 100
- Duration: 30 minutes
- Success criteria: < 2% error rate

**Stress Testing:**
- Concurrent users: 200 (150% of expected)
- Duration: 15 minutes
- Success criteria: System remains stable

---

## 11. Deployment Specifications

### 11.1 Server Requirements

**Production Environment:**
```
OS: Ubuntu 22.04 LTS
CPU: 8 cores (Intel Xeon or equivalent)
RAM: 16GB minimum, 32GB recommended
Storage: 500GB SSD (RAID 10)
Network: 1Gbps
```

**Database Server:**
```
OS: Ubuntu 22.04 LTS
CPU: 8 cores
RAM: 32GB minimum
Storage: 1TB SSD (RAID 10)
PostgreSQL: 15.x
```

### 11.2 Docker Deployment

**docker-compose.yml:**
```yaml
version: '3.8'

services:
  app:
    image: portflow:2.0
    ports:
      - "80:80"
      - "443:443"
    environment:
      - APP_ENV=production
      - DB_HOST=postgres
      - REDIS_HOST=redis
    volumes:
      - ./storage:/var/www/html/storage
    depends_on:
      - postgres
      - redis

  postgres:
    image: postgis/postgis:15-3.3
    environment:
      - POSTGRES_DB=portflow
      - POSTGRES_USER=portflow
      - POSTGRES_PASSWORD=${DB_PASSWORD}
    volumes:
      - postgres_data:/var/lib/postgresql/data

  redis:
    image: redis:7-alpine
    volumes:
      - redis_data:/data

  horizon:
    image: portflow:2.0
    command: php artisan horizon
    depends_on:
      - redis

volumes:
  postgres_data:
  redis_data:
```

---

## 12. Maintenance & Support

### 12.1 Update Procedures

**Security Patches:**
1. Test in staging environment
2. Schedule maintenance window (off-peak)
3. Create database backup
4. Deploy patch
5. Verify functionality
6. Monitor for 24 hours

**Feature Updates:**
1. User acceptance testing (UAT)
2. Update documentation
3. Notify users (7 days advance)
4. Deploy during maintenance window
5. Provide training if needed

### 12.2 Support Tiers

**Tier 1 (Standard):**
- Email support
- Response time: 24 hours
- Resolution time: 72 hours
- Hours: Monday-Friday, 9AM-5PM

**Tier 2 (Premium):**
- Email + Phone support
- Response time: 4 hours
- Resolution time: 24 hours
- Hours: 24/7
- Dedicated account manager

---

## 13. Compliance & Standards

### 13.1 Code Standards

**PSR Compliance:**
- PSR-1: Basic Coding Standard
- PSR-4: Autoloading Standard
- PSR-12: Extended Coding Style

**Laravel Best Practices:**
- Repository pattern for data access
- Service classes for business logic
- Form requests for validation
- Events for decoupled actions

### 13.2 Database Standards

**Naming Conventions:**
- Tables: plural, snake_case (e.g., `port_calls`)
- Columns: snake_case (e.g., `created_at`)
- Indexes: `idx_table_column`
- Foreign keys: `fk_table_column`

---

## 14. Appendices

### Appendix A: Error Codes

| Code | Description | HTTP Status |
|------|-------------|-------------|
| AUTH_001 | Invalid credentials | 401 |
| AUTH_002 | Token expired | 401 |
| AUTH_003 | Insufficient permissions | 403 |
| VAL_001 | Validation error | 422 |
| RES_001 | Resource not found | 404 |
| SYS_001 | Internal server error | 500 |

### Appendix B: Environment Variables

```env
APP_NAME=PortFlow
APP_ENV=production
APP_DEBUG=false
APP_URL=https://portflow.example.com

DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=portflow
DB_USERNAME=portflow
DB_PASSWORD=

REDIS_HOST=localhost
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=ap-southeast-1
AWS_BUCKET=

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
```

---

**Document Control:**
- **Author**: PortFlow Development Team
- **Reviewed By**: Technical Architect, Security Officer
- **Approved By**: CTO
- **Next Review**: June 2026

---

*End of System Requirements Specification*
