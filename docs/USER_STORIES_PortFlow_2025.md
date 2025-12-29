# User Stories & Epics
# PortFlow 2.0

**Document Version:** 2.0  
**Date:** December 29, 2025  
**Format:** Agile User Stories  

---

## Epic 1: Berth Planning & Vessel Management

### Story 1.1: Real-Time Berth Visibility
**As a** Port Operator  
**I want to** see all berth occupancy in real-time  
**So that** I can make informed scheduling decisions quickly

**Acceptance Criteria:**
- [ ] All berths displayed on visual planner
- [ ] Status updates within 2 seconds
- [ ] Color-coded: Green (Available), Yellow (Reserved), Red (Occupied)
- [ ] Vessel details visible on hover
- [ ] Remaining time displayed for occupied berths

**Priority:** Critical  
**Story Points:** 8  
**Sprint:** 1

---

### Story 1.2: Drag-and-Drop Berth Assignment
**As a** Port Operator  
**I want to** assign vessels to berths by dragging and dropping  
**So that** I can schedule efficiently without multiple clicks

**Acceptance Criteria:**
- [ ] Smooth drag-and-drop interface
- [ ] Visual feedback during drag
- [ ] Automatic conflict detection
- [ ] Confirmation dialog before saving
- [ ] Undo capability

**Priority:** Critical  
**Story Points:** 13  
**Sprint:** 1

---

### Story 1.3: AI Berth Recommendations
**As a** Shipping Agent  
**I want to** receive AI-powered berth recommendations  
**So that** I can get the optimal berth for my vessel

**Acceptance Criteria:**
- [ ] Analyze vessel LOA, draft, and cargo type
- [ ] Consider berth availability and suitability
- [ ] Provide top 3 recommendations
- [ ] Display confidence scores
- [ ] Explain reasoning for each recommendation

**Priority:** High  
**Story Points:** 21  
**Sprint:** 3

---

### Story 1.4: Vessel Registration
**As a** Shipping Agent  
**I want to** register new vessels to my fleet online  
**So that** I can submit berth requests without delays

**Acceptance Criteria:**
- [ ] Form with vessel details (Name, IMO, LOA, Draft, Type)
- [ ] IMO number validation
- [ ] Duplicate detection
- [ ] Immediate availability for booking
- [ ] Confirmation email sent

**Priority:** High  
**Story Points:** 5  
**Sprint:** 2

---

### Story 1.5: Conflict Prevention
**As a** Port Operator  
**I want to** be warned of scheduling conflicts  
**So that** I can avoid double-booking berths

**Acceptance Criteria:**
- [ ] Detect time overlaps for same berth
- [ ] Detect LOA/draft incompatibility
- [ ] Display clear warning messages
- [ ] Prevent saving conflicting schedules
- [ ] Suggest alternative berths

**Priority:** Critical  
**Story Points:** 8  
**Sprint:** 1

---

## Epic 2: Cargo Logistics & Yard Management

### Story 2.1: Digital Manifest Submission
**As a** Shipping Agent  
**I want to** submit cargo manifests digitally  
**So that** I can reduce paperwork and processing time

**Acceptance Criteria:**
- [ ] Link manifest to vessel/port call
- [ ] Add multiple cargo items in one session
- [ ] Specify cargo details (description, weight, volume, DG class)
- [ ] Upload supporting documents (PDF, images)
- [ ] Receive manifest number immediately

**Priority:** Critical  
**Story Points:** 13  
**Sprint:** 2

---

### Story 2.2: DG Cargo Safety Flagging
**As an** HSE Officer  
**I want** DG cargo automatically flagged  
**So that** I can ensure safety compliance without manual checks

**Acceptance Criteria:**
- [ ] Auto-detect DG cargo from UN number
- [ ] Display prominent warning icon
- [ ] Require additional safety information
- [ ] Send automatic alert to HSE team
- [ ] Enforce segregation rules

**Priority:** Critical  
**Story Points:** 8  
**Sprint:** 2

---

### Story 2.3: Yard Storage Allocation
**As a** Yard Manager  
**I want to** allocate cargo to specific zones  
**So that** I can organize the yard efficiently

**Acceptance Criteria:**
- [ ] View real-time zone capacity
- [ ] Drag-and-drop cargo to zones
- [ ] Prevent over-allocation
- [ ] Consider DG segregation rules
- [ ] Update capacity instantly

**Priority:** High  
**Story Points:** 13  
**Sprint:** 3

---

### Story 2.4: Automated Storage Billing
**As a** Finance Officer  
**I want** storage charges calculated automatically  
**So that** I can ensure billing accuracy

**Acceptance Criteria:**
- [ ] Calculate based on volume (m³) × days
- [ ] Apply tiered pricing (free days, then daily rate)
- [ ] Update charges in real-time
- [ ] Include DG surcharges
- [ ] Display running total on dashboard

**Priority:** Critical  
**Story Points:** 13  
**Sprint:** 4

---

### Story 2.5: Cargo Tracking
**As a** Shipping Agent  
**I want to** track my cargo location and status  
**So that** I can inform my clients accurately

**Acceptance Criteria:**
- [ ] Search by tracking number
- [ ] Display current zone and status
- [ ] Show receipt and discharge dates
- [ ] Display pending storage charges
- [ ] Export cargo report

**Priority:** High  
**Story Points:** 8  
**Sprint:** 3

---

## Epic 3: Maritime Services Management

### Story 3.1: Online Service Requests
**As a** Shipping Agent  
**I want to** request maritime services online  
**So that** I can coordinate vessel needs efficiently

**Acceptance Criteria:**
- [ ] Select service type (Pilotage, Towage, Bunker, Water, Waste)
- [ ] Specify quantity and required date/time
- [ ] Link to port call
- [ ] Receive request confirmation
- [ ] Track request status

**Priority:** High  
**Story Points:** 8  
**Sprint:** 4

---

### Story 3.2: Pilotage Coordination
**As a** Marine Superintendent  
**I want to** coordinate pilotage operations  
**So that** I can ensure safe vessel movements

**Acceptance Criteria:**
- [ ] View pilot availability
- [ ] Assign pilot to port call
- [ ] Track boarding/disembarking times
- [ ] Calculate pilotage fees automatically
- [ ] Generate pilot dispatch report

**Priority:** High  
**Story Points:** 13  
**Sprint:** 5

---

### Story 3.3: Towage Management
**As a** Port Operator  
**I want to** manage towage operations  
**So that** I can optimize tugboat utilization

**Acceptance Criteria:**
- [ ] View tugboat fleet status
- [ ] Assign tugs to port calls
- [ ] Track deployment and return times
- [ ] Calculate towage fees based on vessel size
- [ ] Monitor tug availability

**Priority:** High  
**Story Points:** 13  
**Sprint:** 5

---

### Story 3.4: Bunker Fuel Management
**As a** Fuel Coordinator  
**I want to** manage bunker fuel deliveries  
**So that** I can track inventory and revenue

**Acceptance Criteria:**
- [ ] Record fuel deliveries
- [ ] Track inventory levels
- [ ] Calculate delivery charges
- [ ] Generate delivery notes
- [ ] Alert on low inventory

**Priority:** Medium  
**Story Points:** 8  
**Sprint:** 6

---

## Epic 4: HSE & Safety Management

### Story 4.1: Digital Permit Submission
**As a** Contractor  
**I want to** submit permit requests online  
**So that** I can start work faster

**Acceptance Criteria:**
- [ ] Select permit type (Hot Work, Confined Space, etc.)
- [ ] Specify location, duration, work description
- [ ] Upload risk assessment
- [ ] Submit for approval
- [ ] Receive permit number

**Priority:** Critical  
**Story Points:** 8  
**Sprint:** 3

---

### Story 4.2: Permit Approval Workflow
**As an** HSE Officer  
**I want to** approve permits digitally  
**So that** I can reduce approval time from hours to minutes

**Acceptance Criteria:**
- [ ] View pending permits in queue
- [ ] Review permit details and attachments
- [ ] Approve with validity period
- [ ] Reject with mandatory comments
- [ ] Send automated notification
- [ ] Generate permit certificate (PDF)

**Priority:** Critical  
**Story Points:** 13  
**Sprint:** 3

---

### Story 4.3: Incident Reporting
**As an** HSE Officer  
**I want to** report safety incidents immediately  
**So that** I can initiate investigation quickly

**Acceptance Criteria:**
- [ ] Select incident type and severity
- [ ] Specify location, date/time, description
- [ ] Upload photos and evidence
- [ ] Submit for investigation
- [ ] Receive incident number

**Priority:** Critical  
**Story Points:** 8  
**Sprint:** 4

---

### Story 4.4: Incident Investigation
**As an** HSE Manager  
**I want to** track incident investigations  
**So that** I can prevent recurrence

**Acceptance Criteria:**
- [ ] Assign investigator
- [ ] Record investigation findings
- [ ] Identify root causes
- [ ] Define corrective actions with deadlines
- [ ] Track action completion
- [ ] Close incident with final report

**Priority:** High  
**Story Points:** 13  
**Sprint:** 4

---

### Story 4.5: Safety Dashboard
**As an** HSE Manager  
**I want to** view safety KPIs in real-time  
**So that** I can monitor safety performance

**Acceptance Criteria:**
- [ ] Display incident statistics
- [ ] Show permit approval metrics
- [ ] Track inspection completion
- [ ] Display trend charts
- [ ] Export safety reports

**Priority:** High  
**Story Points:** 13  
**Sprint:** 5

---

## Epic 5: Financial Management & Billing

### Story 5.1: Real-Time Charge Calculation
**As a** Finance Officer  
**I want** charges calculated in real-time  
**So that** I can monitor revenue accurately

**Acceptance Criteria:**
- [ ] Calculate dockage (LOA × hours × rate)
- [ ] Calculate wharfage and line handling
- [ ] Calculate storage (volume × days × rate)
- [ ] Calculate service charges
- [ ] Update totals every 30 seconds
- [ ] Display on live dashboard

**Priority:** Critical  
**Story Points:** 13  
**Sprint:** 4

---

### Story 5.2: Automated Invoice Generation
**As a** Finance Officer  
**I want** invoices generated automatically  
**So that** I can eliminate manual work

**Acceptance Criteria:**
- [ ] Generate when vessel departs (ATD recorded)
- [ ] Consolidate all charges
- [ ] Assign unique invoice number
- [ ] Calculate totals and taxes
- [ ] Generate PDF invoice
- [ ] Send email to agent

**Priority:** Critical  
**Story Points:** 13  
**Sprint:** 4

---

### Story 5.3: Live Billing Dashboard
**As a** Shipping Agent  
**I want to** see live charges for my vessels  
**So that** I can manage my budget

**Acceptance Criteria:**
- [ ] Display running charges for active vessels
- [ ] Show breakdown by service type
- [ ] Update every 30 seconds
- [ ] Forecast final charges
- [ ] Export billing summary

**Priority:** High  
**Story Points:** 13  
**Sprint:** 5

---

### Story 5.4: ERP Integration
**As a** Finance Director  
**I want** invoices synced to our ERP  
**So that** I can streamline accounting

**Acceptance Criteria:**
- [ ] Generate XML payload in ERP format
- [ ] Send via API or file export
- [ ] Receive confirmation from ERP
- [ ] Update sync status
- [ ] Retry failed syncs automatically
- [ ] Download payload for manual upload

**Priority:** High  
**Story Points:** 21  
**Sprint:** 6

---

### Story 5.5: Payment Tracking
**As a** Finance Officer  
**I want to** track invoice payments  
**So that** I can manage accounts receivable

**Acceptance Criteria:**
- [ ] Mark invoices as paid
- [ ] Record payment date and method
- [ ] View aging reports
- [ ] Send payment reminders
- [ ] Export payment history

**Priority:** High  
**Story Points:** 8  
**Sprint:** 5

---

## Epic 6: Asset & Equipment Management

### Story 6.1: Online Equipment Booking
**As a** Client  
**I want to** book equipment online  
**So that** I can secure resources in advance

**Acceptance Criteria:**
- [ ] Browse available equipment with photos
- [ ] Check availability calendar
- [ ] Select rental period
- [ ] Submit booking request
- [ ] Receive confirmation email

**Priority:** High  
**Story Points:** 13  
**Sprint:** 5

---

### Story 6.2: Rental Approval
**As an** Asset Manager  
**I want to** approve equipment bookings  
**So that** I can manage allocation

**Acceptance Criteria:**
- [ ] View pending booking requests
- [ ] Check equipment availability
- [ ] Approve with rental agreement
- [ ] Reject with reason
- [ ] Send notification to client

**Priority:** High  
**Story Points:** 8  
**Sprint:** 5

---

### Story 6.3: Rental Billing
**As a** Finance Officer  
**I want** rental charges calculated automatically  
**So that** I can ensure accuracy

**Acceptance Criteria:**
- [ ] Calculate based on hourly/daily rate
- [ ] Track actual usage time
- [ ] Apply minimum charge periods
- [ ] Include delivery/pickup fees
- [ ] Update charges in real-time

**Priority:** Critical  
**Story Points:** 8  
**Sprint:** 6

---

### Story 6.4: Maintenance Scheduling
**As an** Asset Manager  
**I want** preventive maintenance scheduled automatically  
**So that** I can prevent equipment breakdowns

**Acceptance Criteria:**
- [ ] Define maintenance schedules
- [ ] Generate work orders automatically
- [ ] Send alerts to maintenance team
- [ ] Track completion status
- [ ] Record maintenance costs

**Priority:** High  
**Story Points:** 13  
**Sprint:** 6

---

### Story 6.5: Asset Utilization Analytics
**As an** Asset Manager  
**I want to** view equipment utilization analytics  
**So that** I can optimize our fleet

**Acceptance Criteria:**
- [ ] Display utilization percentage by asset
- [ ] Show revenue per asset
- [ ] Identify underutilized equipment
- [ ] Display trend charts
- [ ] Export utilization reports

**Priority:** Medium  
**Story Points:** 8  
**Sprint:** 7

---

## Epic 7: Advanced Features (Phase 2)

### Story 7.1: Tank Farm Monitoring
**As a** Tank Farm Operator  
**I want to** monitor tank levels in real-time  
**So that** I can prevent overflows and shortages

**Acceptance Criteria:**
- [ ] Display tank levels on dashboard
- [ ] Show capacity and current volume
- [ ] Alert on high/low levels
- [ ] Track transfer operations
- [ ] Generate inventory reports

**Priority:** Medium  
**Story Points:** 21  
**Sprint:** 8

---

### Story 7.2: MHE Fleet Tracking
**As an** Operations Manager  
**I want to** track mobile handling equipment  
**So that** I can optimize deployment

**Acceptance Criteria:**
- [ ] Display equipment locations on map
- [ ] Show equipment status (idle, in-use, maintenance)
- [ ] Track operator assignments
- [ ] Monitor fuel consumption
- [ ] Generate utilization reports

**Priority:** Medium  
**Story Points:** 21  
**Sprint:** 9

---

### Story 7.3: Container Tracking
**As a** Container Yard Operator  
**I want to** track container locations  
**So that** I can find containers quickly

**Acceptance Criteria:**
- [ ] Display container positions on yard map
- [ ] Search by container number
- [ ] Track container movements
- [ ] Monitor reefer containers
- [ ] Generate yard occupancy reports

**Priority:** Medium  
**Story Points:** 21  
**Sprint:** 10

---

### Story 7.4: GIS Port Map
**As a** Port Operator  
**I want to** view vessel positions on an interactive map  
**So that** I can monitor port operations visually

**Acceptance Criteria:**
- [ ] Display port layout with berths
- [ ] Show vessel positions in real-time
- [ ] Display anchorage zones
- [ ] Show cargo storage areas
- [ ] Zoom and pan capabilities

**Priority:** Medium  
**Story Points:** 21  
**Sprint:** 11

---

### Story 7.5: Predictive Analytics
**As a** Port Manager  
**I want** predictive insights on operations  
**So that** I can make proactive decisions

**Acceptance Criteria:**
- [ ] Predict berth demand
- [ ] Forecast revenue
- [ ] Identify bottlenecks
- [ ] Suggest optimizations
- [ ] Display confidence intervals

**Priority:** Low  
**Story Points:** 34  
**Sprint:** 12

---

## Story Mapping Summary

### Sprint Plan (16 weeks)

**Sprint 1-2 (Weeks 1-4): Foundation**
- Berth planning core features
- User management
- Basic reporting

**Sprint 3-4 (Weeks 5-8): Operations**
- Cargo management
- HSE permit system
- Billing foundation

**Sprint 5-6 (Weeks 9-12): Integration**
- Maritime services
- Asset management
- ERP integration

**Sprint 7-8 (Weeks 13-16): Optimization**
- Advanced analytics
- Mobile optimization
- Performance tuning

---

## Prioritization Matrix

### MoSCoW Method

**Must Have (Critical):**
- Berth planning and scheduling
- Vessel management
- Cargo manifest submission
- Digital permit system
- Automated billing
- Invoice generation

**Should Have (High):**
- AI berth recommendations
- Maritime service requests
- Asset booking system
- ERP integration
- Safety dashboard

**Could Have (Medium):**
- Tank farm monitoring
- MHE fleet tracking
- Container tracking
- GIS port map

**Won't Have (This Release):**
- Blockchain integration
- Advanced AI predictions
- Mobile native apps
- Multi-language support

---

## Definition of Done

A user story is considered "Done" when:

- [ ] Code is written and peer-reviewed
- [ ] Unit tests written (80% coverage)
- [ ] Integration tests passed
- [ ] User acceptance testing completed
- [ ] Documentation updated
- [ ] Deployed to staging environment
- [ ] Product owner approval received
- [ ] No critical or high-severity bugs

---

**Document Control:**
- **Author**: Product Team
- **Last Updated**: December 29, 2025
- **Next Review**: Monthly during development

---

*End of User Stories Document*
