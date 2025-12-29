# User Requirements Specification (URS)
# PortFlow 2.0 - Integrated Port Management System

**Document Version:** 2.0  
**Date:** December 29, 2025  
**Project:** PortFlow 2.0 Implementation  
**Classification:** Internal Use Only  

---

## Document Control

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | Dec 2024 | Product Team | Initial draft |
| 2.0 | Dec 2025 | Product Team | Updated with Phase 2 features |

**Approval:**
- [ ] Port Operations Manager
- [ ] IT Director
- [ ] Finance Director
- [ ] HSE Manager
- [ ] CEO

---

## 1. Introduction

### 1.1 Purpose

This User Requirements Specification (URS) document defines the functional and non-functional requirements for PortFlow 2.0 from the end-user perspective. It serves as the foundation for system design, development, testing, and acceptance criteria.

### 1.2 Scope

This URS covers all user-facing requirements for:
- Port operations staff
- Shipping agents and clients
- HSE officers
- Finance and billing teams
- Asset managers
- System administrators

### 1.3 Definitions

| Term | Definition |
|------|------------|
| ATB | Actual Time of Berthing |
| ATD | Actual Time of Departure |
| DG | Dangerous Goods |
| ERP | Enterprise Resource Planning |
| ETA | Estimated Time of Arrival |
| ETD | Estimated Time of Departure |
| HSE | Health, Safety & Environment |
| IMO | International Maritime Organization |
| LOA | Length Overall (vessel measurement) |
| MHE | Mobile Handling Equipment |
| OSV | Offshore Support Vessel |
| PTW | Permit to Work |
| STS | Ship-to-Ship |

---

## 2. User Roles & Personas

### 2.1 Administrator

**Profile:**
- Port Operations Manager / IT Administrator
- 10+ years port experience
- Tech-savvy, manages system configuration

**Primary Goals:**
- Configure system settings
- Manage user accounts
- Generate operational reports
- Monitor system health

**Key Requirements:**
- UR-ADM-001: Shall be able to create, edit, and deactivate user accounts
- UR-ADM-002: Shall be able to assign role-based permissions
- UR-ADM-003: Shall be able to configure tariff rates and pricing rules
- UR-ADM-004: Shall be able to view complete audit logs
- UR-ADM-005: Shall be able to export data in multiple formats (CSV, Excel, PDF)
- UR-ADM-006: Shall be able to schedule automated reports
- UR-ADM-007: Shall be able to manage system integrations (ERP, AIS, etc.)
- UR-ADM-008: Shall be able to configure email/SMS notification templates

### 2.2 Port Operator

**Profile:**
- Berth Planner / Operations Coordinator
- 5+ years port operations experience
- Manages daily vessel scheduling

**Primary Goals:**
- Optimize berth utilization
- Minimize vessel waiting time
- Coordinate vessel movements
- Manage operational conflicts

**Key Requirements:**
- UR-OPS-001: Shall be able to view real-time berth occupancy status
- UR-OPS-002: Shall be able to drag-and-drop vessels to assign berths
- UR-OPS-003: Shall receive automatic conflict warnings when scheduling overlaps
- UR-OPS-004: Shall be able to view vessel queue and waiting list
- UR-OPS-005: Shall be able to approve or reject berth requests
- UR-OPS-006: Shall be able to update vessel arrival/departure times
- UR-OPS-007: Shall be able to view vessel details (LOA, draft, cargo type)
- UR-OPS-008: Shall be able to generate daily berthing schedules
- UR-OPS-009: Shall be able to communicate with agents via system notifications
- UR-OPS-010: Shall be able to view historical berth utilization analytics

### 2.3 Shipping Agent

**Profile:**
- Agent representing vessel owners
- Manages multiple vessels
- Requires quick access to vessel status and billing

**Primary Goals:**
- Track vessel status in real-time
- Submit berth requests efficiently
- Monitor live billing charges
- Access invoices and payment history

**Key Requirements:**
- UR-AGT-001: Shall be able to register new vessels to their fleet
- UR-AGT-002: Shall be able to submit berth booking requests
- UR-AGT-003: Shall receive AI-powered berth recommendations
- UR-AGT-004: Shall be able to view live vessel positions on map
- UR-AGT-005: Shall be able to see real-time billing charges for active vessels
- UR-AGT-006: Shall be able to request maritime services (pilotage, bunker, water)
- UR-AGT-007: Shall be able to submit cargo manifests digitally
- UR-AGT-008: Shall be able to download invoices in PDF format
- UR-AGT-009: Shall be able to view payment history and outstanding balances
- UR-AGT-010: Shall receive automated notifications for vessel milestones

### 2.4 HSE Officer

**Profile:**
- Safety and compliance specialist
- Manages permits and incident reporting
- Ensures regulatory compliance

**Primary Goals:**
- Approve work permits efficiently
- Track safety incidents
- Ensure DG cargo compliance
- Generate safety reports

**Key Requirements:**
- UR-HSE-001: Shall be able to review and approve permit-to-work requests
- UR-HSE-002: Shall be able to reject permits with mandatory comments
- UR-HSE-003: Shall be able to report safety incidents with photo evidence
- UR-HSE-004: Shall be able to track incident investigation status
- UR-HSE-005: Shall be able to view DG cargo locations and segregation compliance
- UR-HSE-006: Shall be able to generate safety KPI dashboards
- UR-HSE-007: Shall be able to schedule safety inspections
- UR-HSE-008: Shall receive alerts for permit expirations
- UR-HSE-009: Shall be able to view safety audit trails
- UR-HSE-010: Shall be able to export compliance reports for authorities

### 2.5 Finance Officer

**Profile:**
- Billing and accounts receivable specialist
- Manages invoicing and revenue collection
- Requires accurate financial reporting

**Primary Goals:**
- Generate accurate invoices
- Track payment status
- Reconcile ERP data
- Analyze revenue trends

**Key Requirements:**
- UR-FIN-001: Shall be able to generate invoices automatically for completed port calls
- UR-FIN-002: Shall be able to view live billing charges for ongoing operations
- UR-FIN-003: Shall be able to manually adjust invoice items with audit trail
- UR-FIN-004: Shall be able to mark invoices as paid
- UR-FIN-005: Shall be able to sync invoices to ERP system (SAP/Oracle)
- UR-FIN-006: Shall be able to download ERP-compatible XML/CSV files
- UR-FIN-007: Shall be able to view aging reports for outstanding invoices
- UR-FIN-008: Shall be able to generate revenue reports by service type
- UR-FIN-009: Shall be able to view consolidated billing for multi-service operations
- UR-FIN-010: Shall be able to configure tariff rates and apply discounts

### 2.6 Asset Manager

**Profile:**
- Manages port equipment and facilities
- Handles rental bookings and maintenance
- Optimizes asset utilization

**Primary Goals:**
- Track equipment availability
- Manage rental bookings
- Schedule maintenance
- Maximize rental revenue

**Key Requirements:**
- UR-AST-001: Shall be able to view real-time equipment availability
- UR-AST-002: Shall be able to approve or reject rental booking requests
- UR-AST-003: Shall be able to schedule preventive maintenance
- UR-AST-004: Shall be able to record equipment breakdowns
- UR-AST-005: Shall be able to view asset utilization analytics
- UR-AST-006: Shall be able to calculate rental charges automatically
- UR-AST-007: Shall be able to track equipment location (for mobile assets)
- UR-AST-008: Shall be able to generate maintenance cost reports
- UR-AST-009: Shall be able to manage equipment certifications and expiry dates
- UR-AST-010: Shall be able to view rental revenue by asset category

### 2.7 Yard Manager

**Profile:**
- Manages cargo storage and yard operations
- Coordinates cargo movements
- Ensures optimal space utilization

**Primary Goals:**
- Allocate storage zones efficiently
- Track cargo inventory
- Ensure DG cargo safety
- Maximize yard revenue

**Key Requirements:**
- UR-YRD-001: Shall be able to view real-time yard occupancy by zone
- UR-YRD-002: Shall be able to allocate cargo to specific zones
- UR-YRD-003: Shall be able to track cargo receipt and discharge dates
- UR-YRD-004: Shall be able to view DG cargo segregation compliance
- UR-YRD-005: Shall be able to calculate storage charges automatically
- UR-YRD-006: Shall be able to generate cargo inventory reports
- UR-YRD-007: Shall be able to search cargo by tracking number
- UR-YRD-008: Shall be able to view storage duration for each cargo item
- UR-YRD-009: Shall be able to flag overdue cargo for removal
- UR-YRD-010: Shall be able to view yard utilization trends

---

## 3. Functional Requirements

### 3.1 Berth Planning & Scheduling

#### 3.1.1 Visual Berth Planner

**UR-BRT-001: Real-Time Berth Status Display**
- **Priority:** Critical
- **Description:** The system shall display all berths with current occupancy status in real-time
- **Acceptance Criteria:**
  - Berth status updates within 2 seconds of any change
  - Color-coded status: Green (Available), Yellow (Reserved), Red (Occupied)
  - Display vessel name, ETA/ETD, and remaining time
- **User Story:** As a Port Operator, I want to see all berth statuses at a glance so I can make quick scheduling decisions

**UR-BRT-002: Drag-and-Drop Berth Assignment**
- **Priority:** Critical
- **Description:** The system shall allow drag-and-drop assignment of vessels to berths
- **Acceptance Criteria:**
  - Smooth drag-and-drop interface
  - Automatic conflict detection
  - Visual feedback during drag operation
  - Confirmation dialog before finalizing assignment
- **User Story:** As a Port Operator, I want to assign vessels to berths by dragging so I can schedule efficiently

**UR-BRT-003: Conflict Detection**
- **Priority:** Critical
- **Description:** The system shall detect and prevent scheduling conflicts
- **Acceptance Criteria:**
  - Detect time overlaps for same berth
  - Detect LOA/draft incompatibility
  - Display clear warning messages
  - Prevent saving conflicting schedules
- **User Story:** As a Port Operator, I want to be warned of conflicts so I can avoid double-booking

**UR-BRT-004: AI Berth Recommendations**
- **Priority:** High
- **Description:** The system shall provide AI-powered berth recommendations
- **Acceptance Criteria:**
  - Analyze vessel characteristics (LOA, draft, cargo type)
  - Consider berth availability and suitability
  - Provide top 3 recommendations with confidence scores
  - Explain reasoning for each recommendation
- **User Story:** As a Shipping Agent, I want AI recommendations so I can get the best berth for my vessel

#### 3.1.2 Vessel Management

**UR-VES-001: Vessel Registry**
- **Priority:** Critical
- **Description:** The system shall maintain a comprehensive vessel database
- **Acceptance Criteria:**
  - Store vessel details: Name, IMO, flag, type, LOA, draft
  - Link vessels to organizations
  - Validate IMO number format
  - Support bulk import via CSV
- **User Story:** As an Administrator, I want to maintain vessel records so I can ensure data accuracy

**UR-VES-002: Vessel Search**
- **Priority:** High
- **Description:** The system shall provide fast vessel search capabilities
- **Acceptance Criteria:**
  - Search by name, IMO, or organization
  - Auto-complete suggestions
  - Display results within 1 second
  - Show vessel history and statistics
- **User Story:** As a Port Operator, I want to search vessels quickly so I can find information fast

#### 3.1.3 Berth Request Workflow

**UR-REQ-001: Online Berth Request Submission**
- **Priority:** Critical
- **Description:** Agents shall be able to submit berth requests online
- **Acceptance Criteria:**
  - Select vessel from registered fleet
  - Specify ETA and ETD
  - Receive AI berth suggestions
  - Submit request with one click
  - Receive confirmation email
- **User Story:** As a Shipping Agent, I want to submit berth requests online so I can save time

**UR-REQ-002: Request Approval Workflow**
- **Priority:** Critical
- **Description:** Port operators shall be able to approve/reject requests
- **Acceptance Criteria:**
  - View pending requests in queue
  - See request details and vessel information
  - Approve with berth assignment
  - Reject with mandatory reason
  - Send automated notification to agent
- **User Story:** As a Port Operator, I want to approve requests efficiently so I can manage the queue

---

### 3.2 Cargo & Yard Management

#### 3.2.1 Manifest Management

**UR-MAN-001: Digital Manifest Submission**
- **Priority:** Critical
- **Description:** Agents shall be able to submit cargo manifests digitally
- **Acceptance Criteria:**
  - Link manifest to vessel/port call
  - Add multiple cargo items
  - Specify cargo details: description, weight, volume, DG classification
  - Upload supporting documents (PDF, images)
  - Receive manifest number upon submission
- **User Story:** As a Shipping Agent, I want to submit manifests online so I can reduce paperwork

**UR-MAN-002: DG Cargo Flagging**
- **Priority:** Critical
- **Description:** The system shall automatically flag dangerous goods cargo
- **Acceptance Criteria:**
  - Detect DG cargo based on UN number or classification
  - Display prominent DG warning icon
  - Require additional safety information
  - Alert HSE officer automatically
- **User Story:** As an HSE Officer, I want DG cargo flagged automatically so I can ensure safety compliance

#### 3.2.2 Yard Storage Allocation

**UR-YRD-011: Zone-Based Storage**
- **Priority:** Critical
- **Description:** The system shall support zone-based cargo storage
- **Acceptance Criteria:**
  - Define storage zones with capacity limits
  - Allocate cargo to specific zones
  - Track available capacity in real-time
  - Prevent over-allocation
- **User Story:** As a Yard Manager, I want to allocate cargo to zones so I can organize the yard efficiently

**UR-YRD-012: DG Cargo Segregation**
- **Priority:** Critical
- **Description:** The system shall enforce DG cargo segregation rules
- **Acceptance Criteria:**
  - Define segregation matrix for DG classes
  - Prevent incompatible cargo in same zone
  - Display segregation violations
  - Require HSE approval for exceptions
- **User Story:** As an HSE Officer, I want DG segregation enforced so I can prevent incidents

#### 3.2.3 Storage Billing

**UR-STR-001: Automated Storage Charge Calculation**
- **Priority:** Critical
- **Description:** The system shall calculate storage charges automatically
- **Acceptance Criteria:**
  - Calculate based on volume (m³) and duration (days)
  - Apply tiered pricing (free days, then daily rate)
  - Update charges in real-time
  - Include DG surcharges if applicable
- **User Story:** As a Finance Officer, I want storage charges calculated automatically so I can ensure accuracy

---

### 3.3 Maritime Services

#### 3.3.1 Service Request Management

**UR-SVC-001: Online Service Requests**
- **Priority:** High
- **Description:** Agents shall be able to request maritime services online
- **Acceptance Criteria:**
  - Select service type: Pilotage, Towage, Bunker, Water, Waste
  - Specify quantity and required date/time
  - Link to port call
  - Receive request confirmation
  - Track request status
- **User Story:** As a Shipping Agent, I want to request services online so I can coordinate vessel needs

**UR-SVC-002: Service Dispatch**
- **Priority:** High
- **Description:** Port operators shall be able to dispatch services
- **Acceptance Criteria:**
  - View pending service requests
  - Assign resources (pilot, tugboat, etc.)
  - Update service status (Pending → Assigned → Delivered)
  - Record actual quantities delivered
  - Calculate service charges
- **User Story:** As a Port Operator, I want to dispatch services efficiently so I can meet vessel needs

#### 3.3.2 Pilotage & Towage

**UR-PIL-001: Pilotage Coordination**
- **Priority:** High
- **Description:** The system shall manage pilotage operations
- **Acceptance Criteria:**
  - Maintain pilot registry with certifications
  - Assign pilots to port calls
  - Track pilot boarding/disembarking times
  - Calculate pilotage fees based on GRT and distance
  - Generate pilot dispatch reports
- **User Story:** As a Marine Superintendent, I want to coordinate pilotage so I can ensure safe vessel movements

**UR-TOW-001: Towage Management**
- **Priority:** High
- **Description:** The system shall manage towage operations
- **Acceptance Criteria:**
  - Maintain tugboat fleet registry
  - Assign tugboats to port calls
  - Track tug deployment and return times
  - Calculate towage fees based on vessel size and tugs required
  - Monitor tugboat availability
- **User Story:** As a Port Operator, I want to manage towage so I can optimize tug utilization

---

### 3.4 HSE & Safety

#### 3.4.1 Permit-to-Work System

**UR-PTW-001: Digital Permit Submission**
- **Priority:** Critical
- **Description:** Contractors shall be able to submit permit requests digitally
- **Acceptance Criteria:**
  - Select permit type: Hot Work, Confined Space, Working at Height, etc.
  - Specify location, duration, and work description
  - Upload risk assessment and method statement
  - Submit for approval
  - Receive permit number
- **User Story:** As a Contractor, I want to submit permits online so I can start work faster

**UR-PTW-002: Permit Approval Workflow**
- **Priority:** Critical
- **Description:** HSE officers shall be able to approve permits digitally
- **Acceptance Criteria:**
  - View pending permits in queue
  - Review permit details and attachments
  - Approve with validity period
  - Reject with mandatory comments
  - Send automated notification to applicant
  - Generate permit certificate (PDF)
- **User Story:** As an HSE Officer, I want to approve permits digitally so I can reduce approval time

**UR-PTW-003: Permit Expiry Alerts**
- **Priority:** High
- **Description:** The system shall send alerts for expiring permits
- **Acceptance Criteria:**
  - Send email alert 2 hours before expiry
  - Display expiring permits on HSE dashboard
  - Allow permit extension with approval
  - Automatically close expired permits
- **User Story:** As an HSE Officer, I want expiry alerts so I can ensure permits are valid

#### 3.4.2 Incident Management

**UR-INC-001: Incident Reporting**
- **Priority:** Critical
- **Description:** Users shall be able to report safety incidents
- **Acceptance Criteria:**
  - Select incident type: Near Miss, First Aid, Medical Treatment, LTI, Fatality
  - Specify location, date/time, and description
  - Upload photos and evidence
  - Assign severity level
  - Submit for investigation
- **User Story:** As an HSE Officer, I want to report incidents immediately so I can initiate investigation

**UR-INC-002: Incident Investigation**
- **Priority:** High
- **Description:** The system shall support incident investigation workflow
- **Acceptance Criteria:**
  - Assign investigator
  - Record investigation findings
  - Identify root causes
  - Define corrective actions with deadlines
  - Track action completion
  - Close incident with final report
- **User Story:** As an HSE Manager, I want to track investigations so I can prevent recurrence

---

### 3.5 Financial Management

#### 3.5.1 Automated Billing

**UR-BIL-001: Real-Time Charge Calculation**
- **Priority:** Critical
- **Description:** The system shall calculate charges in real-time
- **Acceptance Criteria:**
  - Calculate dockage based on LOA × hours × rate
  - Calculate wharfage and line handling (fixed fees)
  - Calculate storage based on volume × days × rate
  - Calculate service charges based on quantity × rate
  - Update totals every 30 seconds
  - Display running charges on dashboard
- **User Story:** As a Finance Officer, I want real-time charges so I can monitor revenue

**UR-BIL-002: Invoice Generation**
- **Priority:** Critical
- **Description:** The system shall generate invoices automatically
- **Acceptance Criteria:**
  - Generate invoice when vessel departs (ATD recorded)
  - Consolidate all charges (berth, services, storage)
  - Assign unique invoice number
  - Calculate totals and taxes
  - Generate PDF invoice
  - Send email to agent
- **User Story:** As a Finance Officer, I want automated invoices so I can eliminate manual work

**UR-BIL-003: Invoice Adjustment**
- **Priority:** High
- **Description:** Finance officers shall be able to adjust invoices
- **Acceptance Criteria:**
  - Edit invoice items (description, quantity, price)
  - Add manual line items
  - Apply discounts
  - Record adjustment reason (mandatory)
  - Maintain audit trail
  - Regenerate PDF
- **User Story:** As a Finance Officer, I want to adjust invoices so I can handle special cases

#### 3.5.2 ERP Integration

**UR-ERP-001: SAP/Oracle Sync**
- **Priority:** High
- **Description:** The system shall sync invoices to ERP systems
- **Acceptance Criteria:**
  - Generate XML payload in ERP format
  - Map PortFlow fields to ERP fields
  - Send via API or file export
  - Receive confirmation from ERP
  - Update sync status (Pending → Synced → Failed)
  - Retry failed syncs automatically
- **User Story:** As a Finance Director, I want ERP integration so I can streamline accounting

---

### 3.6 Asset Management

#### 3.6.1 Equipment Rental

**UR-RNT-001: Online Booking**
- **Priority:** High
- **Description:** Clients shall be able to book equipment online
- **Acceptance Criteria:**
  - Browse available equipment with photos
  - Check availability calendar
  - Select rental period (start/end date-time)
  - Submit booking request
  - Receive confirmation email
- **User Story:** As a Client, I want to book equipment online so I can secure resources in advance

**UR-RNT-002: Rental Approval**
- **Priority:** High
- **Description:** Asset managers shall be able to approve bookings
- **Acceptance Criteria:**
  - View pending booking requests
  - Check equipment availability
  - Approve with rental agreement
  - Reject with reason
  - Send notification to client
- **User Story:** As an Asset Manager, I want to approve bookings so I can manage equipment allocation

**UR-RNT-003: Rental Billing**
- **Priority:** Critical
- **Description:** The system shall calculate rental charges automatically
- **Acceptance Criteria:**
  - Calculate based on hourly/daily rate
  - Track actual usage time
  - Apply minimum charge periods
  - Include delivery/pickup fees if applicable
  - Update charges in real-time
- **User Story:** As a Finance Officer, I want rental charges calculated automatically so I can ensure accuracy

#### 3.6.2 Maintenance Management

**UR-MNT-001: Preventive Maintenance Scheduling**
- **Priority:** High
- **Description:** The system shall support preventive maintenance scheduling
- **Acceptance Criteria:**
  - Define maintenance schedules (daily, weekly, monthly, annual)
  - Generate work orders automatically
  - Send alerts to maintenance team
  - Track completion status
  - Record maintenance costs
- **User Story:** As an Asset Manager, I want scheduled maintenance so I can prevent breakdowns

**UR-MNT-002: Breakdown Recording**
- **Priority:** High
- **Description:** The system shall record equipment breakdowns
- **Acceptance Criteria:**
  - Report breakdown with description
  - Mark equipment as unavailable
  - Assign to maintenance team
  - Track repair time and costs
  - Record parts used
  - Mark as repaired when complete
- **User Story:** As a Maintenance Technician, I want to record breakdowns so I can track repair history

---

## 4. Non-Functional Requirements

### 4.1 Performance

**UR-PERF-001: Response Time**
- **Requirement:** Page load time shall be < 2 seconds for 95% of requests
- **Measurement:** Google Lighthouse performance score > 90
- **Priority:** Critical

**UR-PERF-002: API Response Time**
- **Requirement:** API endpoints shall respond within 500ms for 99% of requests
- **Measurement:** Application Performance Monitoring (APM) tools
- **Priority:** Critical

**UR-PERF-003: Concurrent Users**
- **Requirement:** System shall support 100+ concurrent users without degradation
- **Measurement:** Load testing with JMeter/Locust
- **Priority:** High

**UR-PERF-004: Database Query Performance**
- **Requirement:** Database queries shall execute within 100ms for 95% of queries
- **Measurement:** PostgreSQL slow query log
- **Priority:** High

### 4.2 Availability & Reliability

**UR-AVAIL-001: System Uptime**
- **Requirement:** System uptime shall be > 99.5% (excluding planned maintenance)
- **Measurement:** Uptime monitoring tools (Pingdom, UptimeRobot)
- **Priority:** Critical

**UR-AVAIL-002: Planned Maintenance Window**
- **Requirement:** Planned maintenance shall be limited to 4 hours/month, scheduled during off-peak hours
- **Measurement:** Maintenance logs
- **Priority:** High

**UR-AVAIL-003: Disaster Recovery**
- **Requirement:** System shall be recoverable within 4 hours in case of disaster
- **Measurement:** Disaster recovery drills (quarterly)
- **Priority:** High

**UR-AVAIL-004: Data Backup**
- **Requirement:** Automated daily backups with 30-day retention
- **Measurement:** Backup success logs
- **Priority:** Critical

### 4.3 Security

**UR-SEC-001: Authentication**
- **Requirement:** Multi-factor authentication (MFA) for admin users
- **Measurement:** MFA adoption rate > 100% for admins
- **Priority:** Critical

**UR-SEC-002: Authorization**
- **Requirement:** Role-based access control (RBAC) with principle of least privilege
- **Measurement:** Security audit reports
- **Priority:** Critical

**UR-SEC-003: Data Encryption**
- **Requirement:** TLS 1.3 for data in transit, AES-256 for data at rest
- **Measurement:** SSL Labs A+ rating
- **Priority:** Critical

**UR-SEC-004: Password Policy**
- **Requirement:** Minimum 8 characters, complexity requirements, 90-day expiry
- **Measurement:** Password policy compliance reports
- **Priority:** High

**UR-SEC-005: Audit Logging**
- **Requirement:** All user actions shall be logged with timestamp, user, and IP address
- **Measurement:** Audit log completeness checks
- **Priority:** Critical

**UR-SEC-006: Session Management**
- **Requirement:** Automatic session timeout after 30 minutes of inactivity
- **Measurement:** Session timeout tests
- **Priority:** High

### 4.4 Usability

**UR-USE-001: Mobile Responsiveness**
- **Requirement:** All functions shall be accessible on mobile devices (iOS/Android)
- **Measurement:** Mobile usability testing
- **Priority:** Critical

**UR-USE-002: Browser Compatibility**
- **Requirement:** Support latest versions of Chrome, Firefox, Safari, Edge
- **Measurement:** Cross-browser testing
- **Priority:** High

**UR-USE-003: User Training**
- **Requirement:** New users shall be productive within 2 hours of training
- **Measurement:** User feedback surveys
- **Priority:** High

**UR-USE-004: Help Documentation**
- **Requirement:** Context-sensitive help available on all screens
- **Measurement:** Help documentation coverage > 90%
- **Priority:** Medium

**UR-USE-005: Error Messages**
- **Requirement:** Error messages shall be clear, actionable, and user-friendly
- **Measurement:** User feedback on error clarity
- **Priority:** High

### 4.5 Scalability

**UR-SCAL-001: Data Volume**
- **Requirement:** System shall handle 10,000+ port calls per year without performance degradation
- **Measurement:** Performance testing with production-like data volumes
- **Priority:** High

**UR-SCAL-002: User Growth**
- **Requirement:** System shall scale to 500+ users without infrastructure changes
- **Measurement:** Load testing
- **Priority:** Medium

**UR-SCAL-003: Storage Growth**
- **Requirement:** Database shall support 5+ years of historical data
- **Measurement:** Database size monitoring
- **Priority:** High

### 4.6 Compliance

**UR-COMP-001: Data Protection**
- **Requirement:** Comply with PDPA (Personal Data Protection Act) Malaysia
- **Measurement:** PDPA compliance audit
- **Priority:** Critical

**UR-COMP-002: Maritime Regulations**
- **Requirement:** Support ISPS Code and IMDG Code requirements
- **Measurement:** Regulatory compliance checklist
- **Priority:** Critical

**UR-COMP-003: Audit Trail**
- **Requirement:** Maintain immutable audit trail for financial transactions
- **Measurement:** Audit trail integrity checks
- **Priority:** Critical

---

## 5. Integration Requirements

### 5.1 ERP Systems

**UR-INT-001: SAP Integration**
- **Requirement:** Bidirectional integration with SAP FI/CO modules
- **Interface:** REST API or IDoc
- **Data:** Invoices, payments, customer master data
- **Frequency:** Real-time for invoices, daily batch for master data

**UR-INT-002: Oracle Integration**
- **Requirement:** Integration with Oracle E-Business Suite
- **Interface:** REST API or XML file exchange
- **Data:** Invoices, receipts, GL accounts
- **Frequency:** Real-time for invoices, hourly for receipts

### 5.2 External Systems

**UR-INT-003: AIS Integration**
- **Requirement:** Receive vessel position data from AIS providers
- **Interface:** REST API or NMEA data stream
- **Data:** Vessel positions, speed, heading
- **Frequency:** Real-time updates every 30 seconds

**UR-INT-004: Weather Services**
- **Requirement:** Integrate with marine weather forecast providers
- **Interface:** REST API
- **Data:** Wind, wave, visibility forecasts
- **Frequency:** Hourly updates

**UR-INT-005: Email/SMS Gateway**
- **Requirement:** Send automated notifications via email and SMS
- **Interface:** SMTP for email, API for SMS
- **Data:** Alerts, confirmations, reports
- **Frequency:** Event-driven (immediate)

---

## 6. Reporting Requirements

### 6.1 Operational Reports

**UR-RPT-001: Daily Berth Schedule**
- **Description:** List of all vessels scheduled for the day
- **Frequency:** Daily, generated at 06:00
- **Format:** PDF, Excel
- **Distribution:** Port Operations team, Pilots, Tugboat operators

**UR-RPT-002: Vessel Movement Report**
- **Description:** Log of all vessel arrivals and departures
- **Frequency:** Daily
- **Format:** PDF, Excel
- **Distribution:** Port Authority, Customs

**UR-RPT-003: Berth Utilization Report**
- **Description:** Berth occupancy statistics and trends
- **Frequency:** Weekly, Monthly
- **Format:** PDF with charts
- **Distribution:** Management, Operations team

### 6.2 Financial Reports

**UR-RPT-004: Revenue Summary**
- **Description:** Revenue breakdown by service type
- **Frequency:** Daily, Monthly
- **Format:** Excel, PDF
- **Distribution:** Finance team, Management

**UR-RPT-005: Aging Report**
- **Description:** Outstanding invoices by aging bucket (0-30, 31-60, 61-90, 90+ days)
- **Frequency:** Weekly
- **Format:** Excel
- **Distribution:** Finance team, Credit control

**UR-RPT-006: Invoice Register**
- **Description:** Complete list of invoices with status
- **Frequency:** Monthly
- **Format:** Excel
- **Distribution:** Finance team, Auditors

### 6.3 Safety Reports

**UR-RPT-007: Safety KPI Dashboard**
- **Description:** Safety metrics (incidents, permits, inspections)
- **Frequency:** Real-time dashboard, Monthly PDF
- **Format:** Dashboard, PDF
- **Distribution:** HSE team, Management

**UR-RPT-008: Incident Report**
- **Description:** Detailed incident investigation reports
- **Frequency:** Per incident
- **Format:** PDF
- **Distribution:** HSE team, Management, Authorities (if required)

**UR-RPT-009: Permit Register**
- **Description:** List of all permits issued with validity
- **Frequency:** Daily
- **Format:** Excel
- **Distribution:** HSE team

### 6.4 Asset Reports

**UR-RPT-010: Asset Utilization Report**
- **Description:** Equipment rental statistics and revenue
- **Frequency:** Monthly
- **Format:** Excel with charts
- **Distribution:** Asset Manager, Finance team

**UR-RPT-011: Maintenance Report**
- **Description:** Maintenance activities and costs
- **Frequency:** Monthly
- **Format:** Excel
- **Distribution:** Asset Manager, Maintenance team

---

## 7. Data Migration Requirements

### 7.1 Legacy Data Migration

**UR-MIG-001: Vessel Data**
- **Source:** Existing vessel database (Excel/Access/Legacy system)
- **Target:** PortFlow vessel registry
- **Volume:** ~500 vessels
- **Validation:** IMO number verification, duplicate detection

**UR-MIG-002: Organization Data**
- **Source:** Customer master data from ERP
- **Target:** PortFlow organizations table
- **Volume:** ~100 organizations
- **Validation:** Unique code, valid contact information

**UR-MIG-003: Historical Port Calls**
- **Source:** Legacy port call records (last 2 years)
- **Target:** PortFlow port_calls table
- **Volume:** ~5,000 port calls
- **Validation:** Date consistency, vessel linkage

**UR-MIG-004: Tariff Rates**
- **Source:** Current tariff schedule
- **Target:** PortFlow configuration
- **Volume:** ~50 tariff items
- **Validation:** Rate accuracy, effective dates

### 7.2 Data Quality Requirements

**UR-DQ-001: Completeness**
- **Requirement:** 95% of migrated records shall have all mandatory fields populated
- **Validation:** Data completeness reports

**UR-DQ-002: Accuracy**
- **Requirement:** 99% of migrated data shall match source data
- **Validation:** Sample verification (10% of records)

**UR-DQ-003: Consistency**
- **Requirement:** All foreign key relationships shall be valid
- **Validation:** Referential integrity checks

---

## 8. Training Requirements

### 8.1 Training Programs

**UR-TRN-001: Administrator Training**
- **Duration:** 3 days
- **Participants:** IT staff, System administrators
- **Content:** System configuration, user management, troubleshooting
- **Delivery:** On-site, hands-on

**UR-TRN-002: End-User Training**
- **Duration:** 2 days
- **Participants:** Port operators, agents, HSE officers, finance staff
- **Content:** Daily operations, module-specific workflows
- **Delivery:** On-site, hands-on with test environment

**UR-TRN-003: Train-the-Trainer**
- **Duration:** 5 days
- **Participants:** Selected power users
- **Content:** Comprehensive system knowledge, training delivery skills
- **Delivery:** On-site, includes training materials

### 8.2 Training Materials

**UR-TRN-004: User Manual**
- **Format:** PDF, searchable
- **Length:** 200+ pages
- **Language:** English
- **Content:** Step-by-step guides, screenshots, FAQs

**UR-TRN-005: Video Tutorials**
- **Format:** MP4, HD quality
- **Duration:** 5-10 minutes per video
- **Content:** Key workflows, common tasks
- **Accessibility:** Embedded in system, YouTube channel

**UR-TRN-006: Quick Reference Cards**
- **Format:** PDF, printable
- **Length:** 1-2 pages per module
- **Content:** Shortcuts, key functions, troubleshooting tips

---

## 9. Acceptance Criteria

### 9.1 Functional Acceptance

**UR-ACC-001: Feature Completeness**
- **Criteria:** 100% of critical requirements implemented and tested
- **Verification:** Requirements traceability matrix

**UR-ACC-002: User Acceptance Testing (UAT)**
- **Criteria:** 95% of UAT test cases passed
- **Verification:** UAT sign-off from business users

**UR-ACC-003: Data Migration**
- **Criteria:** 100% of critical data migrated successfully
- **Verification:** Data migration report, sample verification

### 9.2 Performance Acceptance

**UR-ACC-004: Load Testing**
- **Criteria:** System performs within SLA under peak load (100 concurrent users)
- **Verification:** Load test report

**UR-ACC-005: Stress Testing**
- **Criteria:** System remains stable under 150% of expected load
- **Verification:** Stress test report

### 9.3 Security Acceptance

**UR-ACC-006: Security Audit**
- **Criteria:** No critical or high-severity vulnerabilities
- **Verification:** Penetration testing report

**UR-ACC-007: Compliance Audit**
- **Criteria:** 100% compliance with PDPA and maritime regulations
- **Verification:** Compliance audit report

---

## 10. Glossary

| Term | Definition |
|------|------------|
| Agent Portal | Client-facing interface for shipping agents |
| Anchorage | Designated area for vessels to wait before berthing |
| Berth | Designated location where a vessel is secured |
| Dockage | Charge for vessel occupying berth space |
| GRT | Gross Registered Tonnage |
| IMDG | International Maritime Dangerous Goods Code |
| ISPS | International Ship and Port Facility Security Code |
| Line Handling | Service of securing vessel to berth |
| Manifest | Document listing all cargo on a vessel |
| PDPA | Personal Data Protection Act (Malaysia) |
| Pilotage | Service of guiding vessel into/out of port |
| PTW | Permit to Work |
| Towage | Service of tugboat assistance |
| Wharfage | Charge for using wharf facilities |

---

## 11. Appendices

### Appendix A: User Role Matrix

| Function | Admin | Operator | Agent | HSE | Finance | Asset Mgr |
|----------|-------|----------|-------|-----|---------|-----------|
| Berth Planning | ✅ | ✅ | 👁️ | ❌ | ❌ | ❌ |
| Vessel Registry | ✅ | ✅ | ➕ | ❌ | ❌ | ❌ |
| Cargo Manifest | ✅ | ✅ | ✅ | 👁️ | ❌ | ❌ |
| Yard Management | ✅ | ✅ | 👁️ | ✅ | ❌ | ❌ |
| Permit Approval | ✅ | ❌ | ❌ | ✅ | ❌ | ❌ |
| Incident Reporting | ✅ | ✅ | ❌ | ✅ | ❌ | ❌ |
| Invoice Generation | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ |
| Asset Booking | ✅ | ❌ | ✅ | ❌ | ❌ | ✅ |
| System Config | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |

**Legend:**
- ✅ Full Access
- 👁️ View Only
- ➕ Create Only (own records)
- ❌ No Access

### Appendix B: Screen Mockups

*(Refer to separate design document for detailed UI mockups)*

### Appendix C: API Specifications

*(Refer to separate API documentation)*

---

**Document Approval:**

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Product Owner | __________ | __________ | __________ |
| IT Director | __________ | __________ | __________ |
| Operations Manager | __________ | __________ | __________ |
| HSE Manager | __________ | __________ | __________ |
| Finance Director | __________ | __________ | __________ |

---

*End of User Requirements Specification*
