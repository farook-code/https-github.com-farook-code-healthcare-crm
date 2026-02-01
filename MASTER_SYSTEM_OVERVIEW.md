# HealthFlow CRM - Master System Overview

## 1. Project Introduction
**HealthFlow** is a comprehensive, Multi-Tenant Healthcare CRM and Hospital Management System built on **Laravel 10/11**. It is designed to manage the entire patient lifecycle, from registration and appointment scheduling to diagnosis, billing, and pharmacy management.

**Tech Stack:**
*   **Backend:** Laravel (PHP 8.2+)
*   **Frontend:** Blade Templates, Tailwind CSS, Alpine.js
*   **Database:** MySQL / SQLite
*   **Real-time:** Jitsi Meet (Telemedicine), AJAX Polling (Queues)

---

## 2. User Roles & Access Control
The system relies on a rigid Role-Based Access Control (RBAC) system.

| Role | Access Level | Description |
| :--- | :--- | :--- |
| **Super Admin** | unlimited | Platform owner. Can manage **Branches**, Global Users, and SaaS Plans. |
| **Admin** | restricted | Clinic/Hospital administrator. Manages Staff, Reports, and Settings for their facility. |
| **Doctor** | restricted | Conducts consultations, writes prescriptions, views patient history. |
| **Nurse** | restricted | Triages patients, records Vitals, manages patient flow. |
| **Reception** | restricted | Front-desk operations: Registration, Scheduling, Billing, Queue Management. |
| **Patient** | restricted | Self-service portal: Book appointments, view history, access lab reports. |

---

## 3. Key Modules & Features

### A. Patient Management
*   **Registration:** Detailed profile creation (DOB, Gender, Contact).
*   **Medical History:** Timeline view of past appointments and diagnoses.
*   **Documents:** Upload/Download Lab Reports (PDF/Images).

### B. Appointments & Telemedicine
*   **Scheduling:** Calendar view (Day/Week/Month) and List view.
*   **Status Workflow:** Scheduled -> In Progress -> Completed (or Cancelled).
*   **Telemedicine:** Integrated **Jitsi Meet** video conferencing.
    *   Secure, unique room generation per appointment.
    *   Consent forms for legal compliance.
*   **Queue Management:**
    *   **TV Display:** Public waiting room screen showing "Now Serving" and "Up Next".
    *   **Kiosk Mode:** Tablet-friendly self-check-in for patients arriving at the clinic.

### C. Clinical & EMR (Electronic Medical Records)
*   **Vitals Tracking:** Pulse, BP, Temperature logs.
*   **Doctor interface:**
    *   **Diagnosis:** Rich text notes, symptoms tagging.
    *   **Prescriptions:** Digital Rx with dosage instructions.
    *   **Vaccinations:** Immunization tracking.
    *   **AI Scribe:** (Prototype) Voice-to-text notes.

### D. Pharmacy & Inventory
*   **Medicine Management:** Stock tracking, pricing, and expiration dates.
*   **Dispensing:** Auto-deduct stock upon invoicing.
*   **Low Stock Alerts:** Visual indicators for running low.

### E. Billing & Insurance
*   **Invoicing:** Generate PDF invoices (A4 or **Thermal POS** format).
*   **Insurance:** Claim management with provider details and status tracking (Pending/Approved).

### F. Administrative Tools
*   **Audit Logs:** Track who did what (Login, Update, Delete) for security.
*   **Advanced Reports:**
    *   Appointment Analytics (Breakdown by status).
    *   Financial Reports (Revenue, Pending Payments).
*   **User Management:** Create/Edit users with automatic scoping (Admin only sees their branch users).

---

## 4. SaaS / Subscription System
The system supports a Multi-Tenant SaaS model via the `plans` and `subscriptions` tables.

**Plans:**
1.  **Solo Practice ($49)**: Basic Appointments & Patient History. (Pharmacy Hidden).
2.  **Small Clinic ($199)**: Adds Pharmacy, Lab Reports, Chat.
3.  **Hospital ($499)**: Adds Insurance, Audit Logs, Unlimited Staff.
4.  **Medical Network ($1499)**: Adds **Multi-Branch Support**.

**Security:**
*   Middleware checks active subscriptions.
*   `hasFeature()` helper functions gate UI elements (e.g., hiding the "Pharmacy" sidebar link).

---

## 5. Workflows

### **The Patient Journey**
1.  **Arrival**: Patient walks in or uses the **Kiosk** to check in.
2.  **Triage (Nurse)**: Nurse sees patient in "Patient Flow", records **Vitals**.
3.  **Consultation (Doctor)**:
    *   Doctor opens "My Appointments".
    *   Reviews Vitals & History.
    *   Start Video Call (if remote) or In-Person.
    *   Enters **Diagnosis** & **Prescription**.
    *   Clicks "Finalize Appointment".
4.  **Pharmacy/Billing**:
    *   Receptionist sees "Completed" status.
    *   Generates **Invoice** (Services + Medicines).
    *   Prints Receipt (POS/A4).
5.  **Post-Care**: Patient logs in to portal to download Rx or Lab Reports.

---

## 6. Installation & Credentials (Local)

**Login URL:** `/login`

**Default Super Admin:**
*   Email: `admin@admin.com`
*   Password: `password`

**Common Users (Seeded):**
*   Doctor: `doctor@doctor.com`
*   Nurse: `nurse@nurse.com`
*   Reception: `reception@reception.com`
*   Patient: `patient@patient.com`

**Printing:**
*   Use `Ctrl+P` on Ticket/Invoice pages.
*   **Kiosk URL:** `/reception/kiosk` (Run on tablet/full screen).
*   **Queue TV:** `/reception/queue/display` (Run on large monitor).
