# Sales Funnel CRM Project Context

## Project Overview
This project is a brand-new, web-based Sales Funnel CRM built from scratch. It aims to manage the entire sales lifecycle, from lead generation to deal closure, ensuring no lead or opportunity is forgotten.

## Architecture & Tech Stack
*   **Backend:** Core PHP 8+, MVC Pattern, Modular Design. No third-party PHP frameworks.
*   **Database:** MySQL 8+. Interactions via PDO and prepared statements.
*   **Frontend:** HTML5, Tailwind CSS (via Node.js build process), Vanilla JavaScript. No frontend frameworks like React or Vue.
*   **Routing:** Custom lightweight routing system.
*   **Migrations:** Custom PHP-based migration system.

## Database Entities (Phase 1 focus)
*   `users`, `roles`, `permissions`, `role_permissions`, `user_roles`, `audit_logs`

## Development Roadmap

### Phase 1: Foundation & Security (Current)
* Project Structure, MVC Architecture, Custom Router, Custom Migration System, PDO Database Layer, Authentication (Login, Logout, Forgot Password), RBAC, Audit Logging, Tailwind Setup, Documentation.
* Modules: User Management, Role Management, Permission Management.

### Phase 2: Lead Management
* Create, Edit, Delete, Search, Filter, Assign, Reassign Lead. Lead Import (CSV), Duplicate Detection, Timeline, Notes, Attachments.
* Lead Status: New, Contacted, Qualified, Unqualified, Converted, Lost.
* Lead Priority: High, Medium, Low. Dashboard Indicators.

### Phase 3: Follow-Up Management (Critical Module)
* Follow-Up Creation, Edit, Reschedule, Complete, Cancel. Types: Call, Meeting, WhatsApp, Email, Site Visit, Demo, Proposal Discussion.
* Due Lead Indicators (Green, Yellow, Orange, Red color coding).
* Dashboard Widgets & Automation (Auto Reminders, Escalations).

### Phase 4: Opportunity Management
* Convert Lead to Opportunity, Pipeline, Revenue Forecast, Opportunity Activities/Notes/Attachments.
* Opportunity Stages: Lead Generated, Qualification, Requirement Gathering, Proposal Shared, Negotiation, Decision Pending, Won, Lost.
* Opportunity KPIs.

### Phase 5: Sales Funnel Kanban Board
* Drag & Drop, Stage Movement, Stage History, Revenue Per Stage, Opportunity Count Per Stage.
* Analytics: Conversion %, Funnel Leakage, Average Time In Stage.

### Phase 6: Customer Management
* Customer Master, Contacts, Addresses, GST Information, Linked Opportunities, Communication History. Customer Timeline.

### Phase 7: Activities & Communication Center
* Activities: Calls, Meetings, Emails, WhatsApp Messages, Site Visits.
* Email Integration (SMTP) & WhatsApp Integration (Meta Cloud API).

### Phase 8: Task Management
* Create, Assign, Due Dates, Priorities, Status Tracking. Notifications.

### Phase 9: Dashboard & Analytics
* Sales Dashboard Widgets, Charts (Funnel, Revenue, Lead Source, Follow-Up, Sales Performance).

### Phase 10: Notification Center
* Channels: In-App, Email, WhatsApp. Trigger Events. Notification Center Features.

### Phase 11: Reports
* Lead Reports, Follow-Up Reports, Opportunity Reports. Exports (Excel, CSV, PDF).

### Phase 12: Automation Engine
* Auto Lead Assignment, Auto Follow-Up Creation, Auto Reminder Creation, Escalation Rules.

### Phase 13: File & Document Management
* Upload Documents/Proposals/Contracts, Download History, Version Tracking.

### Phase 14: Audit Logs
* Track Login, Logout, Lead Changes, Opportunity Changes, Follow-Up Changes, User Changes.

### Phase 15: Production Hardening
* Security (CSRF, XSS, Rate Limiting, Session Security). Performance (Database Indexing, Query Optimization, Caching Strategy). Documentation.

### MUST-HAVE CRM FEATURE: Lead Health Score
* Calculate based on Last Contact Date, Follow-Up Completion, Opportunity Progress, Response Activity.
* Indicators: 🟢 Healthy, 🟡 Attention Needed, 🟠 At Risk, 🔴 Overdue / No Follow-Up.
