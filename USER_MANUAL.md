# Healthcare CRM - User Manual

## 🏥 Application Overview
This Healthcare CRM is a comprehensive system designed to manage patients, appointments, medical records, and billing. It features dedicated dashboards for Administrators, Doctors, Nurses, Receptionists, and Patients.

---

## 🔑 Roles & Login Credentials
(Use the credentials you set up during seeding)
- **Admin**: Full system access.
- **Doctor**: Clinical tools, appointments, prescriptions.
- **Reception**: Scheduling, patient registration, billing.
- **Nurse**: Vitals, vaccinations, basic patient care.
- **Patient**: View own records, appointments, prescriptions.

---

## 1. 🖥️ Administrator Guide
**URL:** `/admin/dashboard`

### Features:
- **User Management**: Create/Edit Doctors, Nurses, and Staff accounts.
- **Department Management**: Add hospital departments (Cardiology, Pediatrics, etc.).
- **Reports & Analytics**:
    - Go to **Analytics > Reports** to view Revenue, Appointment volume, and Patient counts.
- **Audit Logs**: View system-wide activity logs for security.

### How to add a Doctor:
1. Go to **Management > Doctors**.
2. Click **+ Add Doctor**.
3. Fill in details (Name, Email, Department, Schedule).
4. The doctor can now login.

---

## 2. 👩‍💼 Receptionist Guide
**URL:** `/reception/dashboard`

### Key Tasks:
1.  **Patient Registration**:
    - Click **+ Register Patient**.
    - Enter full demographics (Name, DOB, Phone, Address).
    - *Note:* New users must be registered here before booking appointments.

2.  **Booking Appointments**:
    - Go to **Calendar** or **Appointments**.
    - Click **+ New Appointment**.
    - Select Patient, Doctor, and Time.
    - System checks for availability automatically.

3.  **Billing & Invoicing**:
    - Go to **Appointments**.
    - Look for the **Billing** column.
    - Click **🧾 (Generate)** to create an invoice.
    - Click **💳 (Card)** or **Cash** to process payment.
    - Click **🖨️ (Print)** to generate a PDF receipt.

---

## 3. 👨‍⚕️ Doctor Guide
**URL:** `/doctor/dashboard`

### Clinical Workflow:
1.  **My Schedule**:
    - View upcoming appointments in **List** or **Calendar** view.
2.  **Consultation**:
    - Click **View** on an appointment.
    - **Vitals**: Review BP, Weight, Height (recorded by Nurse).
    - **History**: See previous visits and lab reports.
    - **Diagnosis**: Click **Add Diagnosis** to record symptoms and findings.
    - **Prescription**: After diagnosis, click **Write Prescription** to add meds.
3.  **Lab Reports**:
    - Upload X-Rays or Test Results directly to the patient's file.

---

## 4. 🚑 Nurse Guide
**URL:** `/nurse/dashboard`

### Tasks:
- **Triage**: Search for a patient.
- **Vitals**: Record generic health data (Temperature, Pulse, BP) *before* the doctor sees them.
- **Vaccinations**: Log administered vaccines.

---

## 5. 🤕 Patient Portal
**URL:** `/patient/dashboard`

### Features:
- **Dashboard**: See health overview (BMI, Blood Group, Allergies).
- **Appointments**: View upcoming visits or history.
- **Prescriptions**:
    - View active medications.
    - Click **Request Refill** to notify your doctor.
- **Lab Results**: Download uploaded reports.

---

## 🛠️ Troubleshooting
- **"Patient profile not found"**:
    - This happens if a User exists but hasn't been "Registered" as a patient.
    - **Fix**: Have Reception go to **Register Patient** and create a profile for that User.
- **"Invoice Error"**:
    - Ensure the patient profile exists before generating an invoice.

## 🚀 Technical Support
For support, contact the system administrator.
