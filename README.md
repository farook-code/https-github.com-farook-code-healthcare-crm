# 🏥 CareSync - Healthcare CRM System

![Version](https://img.shields.io/badge/version-2.0.0-blue.svg)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?logo=php)
![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?logo=laravel)
![License](https://img.shields.io/badge/license-MIT-green.svg)

**CareSync** is a comprehensive, HIPAA-compliant Healthcare CRM system designed for hospitals, clinics, and medical networks. Built with Laravel 11, it provides complete patient management, appointment scheduling, billing, pharmacy, lab reports, and much more.

---

## 📋 Table of Contents

- [Features](#-features)
- [User Roles](#-user-roles)
- [System Architecture](#-system-architecture)
- [Workflow Diagrams](#-workflow-diagrams)
- [Technology Stack](#-technology-stack)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Usage Guide](#-usage-guide)
- [API Documentation](#-api-documentation)
- [Performance](#-performance)
- [Security](#-security)
- [Screenshots](#-screenshots)
- [Contributing](#-contributing)
- [License](#-license)

---

## ✨ Features

### 🩺 Core Medical Features
- ✅ **Patient Management** - Complete patient profiles with medical history
- ✅ **Appointment Scheduling** - Calendar view with conflict detection
- ✅ **Diagnosis & Prescriptions** - Digital prescription management
- ✅ **Lab Reports** - Upload, manage, and track lab results
- ✅ **Vital Signs Recording** - Blood pressure, temperature, weight tracking
- ✅ **Vaccination Tracking** - Immunization records
- ✅ **Prescription Refills** - Patient requests, doctor approval workflow
- ✅ **Telemedicine** - Virtual consultation support
- ✅ **AI Medical Scribe** - AI-powered diagnosis assistance

### 💼 Hospital Operations
- ✅ **IPD Management** - In-patient department admissions
- ✅ **Bed & Ward Management** - Track availability and assignments
- ✅ **Operation Theater Booking** - Schedule and manage surgeries
- ✅ **Queue Management** - Digital waiting list with TV display
- ✅ **Kiosk Check-in** - QR code-based self check-in
- ✅ **Patient Flow Board** - Kanban-style patient tracking (Nurse view)

### 💊 Pharmacy & Inventory
- ✅ **Medicine Inventory** - Stock management with low-stock alerts
- ✅ **Stock Deduction** - Automatic inventory updates on invoicing
- ✅ **Drug Interaction Checker** - Safety alerts for prescriptions
- ✅ **Import/Export** - Bulk medicine data management

### 💰 Billing & Finance
- ✅ **Itemized Invoicing** - Detailed billing with services and medicines
- ✅ **Payment Processing** - Multiple payment methods
- ✅ **Insurance Claims** - Management and tracking
- ✅ **Payment History** - Complete financial records
- ✅ **Email Receipts** - Automatic payment confirmation emails

### 👥 User Management
- ✅ **Multi-Role System** - 8 different user roles
- ✅ **Role-Based Access Control** - Granular permissions
- ✅ **Department Management** - Organize by medical departments
- ✅ **Doctor Profiles** - Specialization, schedule, availability
- ✅ **Staff Management** - Nurses, receptionists, lab techs

### 📊 Analytics & Reports
- ✅ **Admin Dashboard** - Real-time statistics and KPIs
- ✅ **Financial Reports** - Revenue, expenses, profit analysis
- ✅ **Appointment Analytics** - Trends and patterns
- ✅ **Patient Demographics** - Age, gender, location insights
- ✅ **Stock Reports** - Inventory levels and usage
- ✅ **Audit Logs** - Complete activity tracking

### 🌍 Multi-Tenancy & Scaling
- ✅ **Multi-Branch Support** - Manage multiple clinic locations
- ✅ **Multi-Clinic Management** - Network-wide administration
- ✅ **Subscription Plans** - 4 pricing tiers (Solo, Small, Hospital, Network)
- ✅ **Feature-Based Licensing** - Enable/disable features per plan
- ✅ **White-Label Branding** - Custom logo, colors, domain

### 💬 Communication
- ✅ **Internal Chat** - Real-time staff messaging
- ✅ **Notifications** - In-app and email notifications
- ✅ **Email Automation** - Appointment confirmations, reminders
- ✅ **WhatsApp Integration** - Patient communication (optional)
- ✅ **SMS Alerts** - Appointment reminders (configurable)

### 🌐 Internationalization
- ✅ **Multi-Language** - English, Spanish, French, Arabic
- ✅ **RTL Support** - Right-to-left layout for Arabic
- ✅ **Localized Content** - Translations for all UI elements

### 🔐 Security & Compliance
- ✅ **HIPAA Compliant** - Healthcare data protection standards
- ✅ **Audit Logging** - Track all user actions
- ✅ **Encrypted Data** - Patient information encryption
- ✅ **Role Permissions** - Granular access control
- ✅ **Session Management** - Secure authentication
- ✅ **Rate Limiting** - Prevent abuse and attacks

---

## 👥 User Roles

### 1. 🔧 Super Admin
**Full system control across all clinics**
- Manage all clinics and branches
- Configure global settings
- View all reports and analytics
- Manage subscriptions and plans

### 2. 👨‍💼 Admin
**Clinic-level administration**
- Manage users and permissions
- View reports and analytics
- Configure clinic settings
- Approve major operations

### 3. 🩺 Doctor
**Medical professional interface**
- View appointments and patient history
- Create diagnoses and prescriptions
- Record patient vitals
- Request lab tests
- Approve prescription refills
- Access telemedicine

### 4. 👩‍⚕️ Nurse
**Patient care and monitoring**
- Record patient vitals
- Update patient status
- Manage patient flow board
- Assist with appointments
- View medical records (limited)

### 5. 🛎️ Reception
**Front desk operations**
- Schedule appointments
- Register new patients
- Check-in patients
- Create invoices
- Process payments
- Manage waitlist

### 6. 💊 Pharmacist
**Pharmacy management**
- Manage medicine inventory
- View prescriptions
- Process invoices (medicine items)
- Stock alerts and reordering

### 7. 🔬 Lab Technician
**Laboratory operations**
- Upload lab reports
- Manage test results
- Process lab requests
- Create lab invoices

### 8. 👤 Patient
**Patient portal**
- View medical history
- Book appointments
- View prescriptions
- Download lab reports
- Request prescription refills
- View invoices and payments
- Access patient ID card (QR code)

---

## 🏗️ System Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                     User Interface Layer                     │
│  (Blade Templates, Tailwind CSS, Alpine.js, Livewire)       │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                    Application Layer                         │
│  (Laravel Controllers, Services, Events, Jobs, Middleware)  │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                      Business Logic                          │
│     (Models, Repositories, Cache Service, Validators)       │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                       Data Layer                             │
│              (MySQL/PostgreSQL, Redis Cache)                 │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                  External Services                           │
│  (Email, SMS, WhatsApp, Payment Gateway, Storage - S3)      │
└─────────────────────────────────────────────────────────────┘
```

### Database Schema Overview

```
┌──────────────┐       ┌──────────────┐       ┌──────────────┐
│    Users     │◄─────►│   Patients   │◄─────►│ Appointments │
└──────────────┘       └──────────────┘       └──────────────┘
       │                      │                       │
       │                      │                       │
       ▼                      ▼                       ▼
┌──────────────┐       ┌──────────────┐       ┌──────────────┐
│    Roles     │       │  Diagnoses   │       │   Invoices   │
└──────────────┘       └──────────────┘       └──────────────┘
                              │                       │
                              │                       │
                              ▼                       ▼
                       ┌──────────────┐       ┌──────────────┐
                       │Prescriptions │       │ Medicines    │
                       └──────────────┘       └──────────────┘
```

---

## 📊 Workflow Diagrams

### 1. Patient Appointment Workflow

```
┌─────────────┐
│   Patient   │ Books Appointment Online
└──────┬──────┘
       │
       ▼
┌─────────────────┐
│ System Checks   │ → Check Doctor Availability
│ Availability    │ → Check for Conflicts
└──────┬──────────┘
       │
       ▼
┌─────────────────┐
│  Appointment    │ → Send Confirmation Email
│    Booked       │ → Add to Calendar
└──────┬──────────┘
       │
       ▼
┌─────────────────┐
│Reception Check-in│ → Patient Arrives
│   (On Day)      │ → Update Status
└──────┬──────────┘
       │
       ▼
┌─────────────────┐
│  Nurse Records  │ → Record Vitals
│    Vitals       │ → Add to Patient Record
└──────┬──────────┘
       │
       ▼
┌─────────────────┐
│Doctor Diagnosis │ → Create Diagnosis
│ & Prescription  │ → Issue Prescription
└──────┬──────────┘
       │
       ▼
┌─────────────────┐
│Reception Billing│ → Create Invoice
│  & Payment      │ → Process Payment
└──────┬──────────┘
       │
       ▼
┌─────────────────┐
│   Completed     │ → Email Receipt
│                 │ → Update Records
└─────────────────┘
```

### 2. Prescription Workflow

```
Doctor Creates      Patient Views       Patient Requests
Prescription   →    Prescription    →      Refill
     │                   │                   │
     ▼                   ▼                   ▼
Digital Rx         Email Copy          Doctor Reviews
Saved to DB        Sent                Request
     │                   │                   │
     ▼                   ▼                   ▼
Auto-Generate      Download PDF        Approve/Reject
Print Version      Available           Refill
     │                   │                   │
     ▼                   ▼                   ▼
Add to Patient     Patient ID          New Rx Created
Medical History    Card Updated        (if approved)
```

### 3. Billing & Payment Workflow

```
┌─────────────────────────────────────────────────────────┐
│                   Invoice Creation                       │
│                                                          │
│  Reception selects:                                     │
│  • Patient                                              │
│  • Services (Consultation, Procedures)                  │
│  • Medicines (from Pharmacy Stock)                      │
│  • Lab Tests                                            │
└─────────────────┬───────────────────────────────────────┘
                  │
                  ▼
┌─────────────────────────────────────────────────────────┐
│              System Auto-Calculates                      │
│  • Subtotal                                             │
│  • Tax                                                  │
│  • Discounts (if applicable)                            │
│  • Insurance Coverage (if applicable)                   │
│  • Total Amount Due                                     │
└─────────────────┬───────────────────────────────────────┘
                  │
                  ▼
┌─────────────────────────────────────────────────────────┐
│                  Payment Processing                      │
│  • Cash                                                 │
│  • Card (Stripe/Razorpay)                               │
│  • Insurance Claim                                      │
│  • Online Payment                                       │
└─────────────────┬───────────────────────────────────────┘
                  │
                  ▼
┌─────────────────────────────────────────────────────────┐
│              Post-Payment Actions                        │
│  • Deduct Medicine Stock                                │
│  • Email Receipt to Patient                             │
│  • Update Financial Reports                             │
│  • Create Audit Log Entry                               │
│  • Update Appointment Status                            │
└─────────────────────────────────────────────────────────┘
```

### 4. User Authentication Flow

```
User Login Attempt
       │
       ▼
┌─────────────┐
│ Credentials │ Email + Password
│   Check     │ OR Magic Link
└──────┬──────┘
       │
       ├─── Valid? ───────┐
       │                  │
       NO                YES
       │                  │
       ▼                  ▼
┌─────────────┐    ┌─────────────┐
│Show Error   │    │Check Role   │
│Try Again    │    │& Permissions│
└─────────────┘    └──────┬──────┘
                          │
                          ▼
                   ┌─────────────────┐
                   │ Redirect to     │
                   │ Role Dashboard: │
                   │ • Admin         │
                   │ • Doctor        │
                   │ • Patient       │
                   │ • Reception     │
                   │ • etc.          │
                   └─────────────────┘
```

---

## 🛠️ Technology Stack

### Backend
- **Framework:** Laravel 11.x
- **PHP:** 8.2+
- **Database:** MySQL 8.0+ / PostgreSQL 14+
- **Cache:** Redis 7.0+
- **Queue:** Redis Queue
- **Session:** Redis

### Frontend
- **Template Engine:** Blade
- **CSS Framework:** Tailwind CSS 3.x
- **JavaScript:** Alpine.js 3.x
- **Build Tool:** Vite 5.x
- **Icons:** Heroicons

### DevOps & Infrastructure
- **Web Server:** Nginx / Apache
- **Queue Worker:** Laravel Queue Worker
- **Task Scheduler:** Laravel Scheduler (Cron)
- **File Storage:** Local / AWS S3
- **Email:** SMTP / Mailgun / SendGrid
- **Payment:** Stripe / Razorpay

### Development Tools
- **Version Control:** Git
- **Package Manager:** Composer / NPM
- **Code Quality:** PHP CS Fixer
- **Testing:** PHPUnit / Pest
- **API Testing:** Postman

---

## 🚀 Installation

### Prerequisites
```bash
- PHP >= 8.2
- Composer
- Node.js >= 18.x
- MySQL >= 8.0 or PostgreSQL >= 14
- Redis (optional but recommended)
```

### Step 1: Clone Repository
```bash
git clone https://github.com/farook-code/healthcare-crm.git
cd healthcare-crm
```

### Step 2: Install Dependencies
```bash
# Install PHP dependencies
composer install

#Install Node dependencies
npm install
```

### Step 3: Environment Setup
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Configure Database
Edit `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=healthcare_crm
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Step 5: Run Migrations & Seed
```bash
# Run migrations
php artisan migrate

# Seed database with demo data
php artisan db:seed
```

### Step 6: Build Assets
```bash
npm run build
```

### Step 7: Start Application
```bash
# Development server
php artisan serve

# Queue worker (separate terminal)
php artisan queue:work

# Task scheduler (add to cron)
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

### Step 8: Access Application
```
http://localhost:8000
```

### Default Login Credentials (Demo Data)
```
Super Admin:
Email: admin@admin.com
Password: password

Doctor:
Email: drsarahconnor@healthcare.com
Password: password

Patient:
Email: alicewonderland@example.com
Password: password

Reception:
Email: reception1@healthcare.com
Password: password
```

---

## ⚙️ Configuration

### Email Configuration
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@caresync.com
```

### Redis Configuration
```env
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

### Payment Gateway Setup
```env
# Stripe
STRIPE_KEY=pk_test_xxxxx
STRIPE_SECRET=sk_test_xxxxx

# Razorpay
RAZORPAY_KEY=rzp_test_xxxxx
RAZORPAY_SECRET=xxxxx
```

### WhatsApp Integration (Optional)
```env
WHATSAPP_API_URL=https://api.whatsapp.com
WHATSAPP_TOKEN=your_token
```

---

## 📖 Usage Guide

### For Receptionists

#### 1. Register New Patient
1. Navigate to **Reception → Patients → Create**
2. Fill in patient details
3. System auto-generates Medical Record Number
4. Save patient

#### 2. Schedule Appointment
1. Navigate to **Reception → Appointments → Create**
2. Select patient (or create new)
3. Choose doctor and date/time
4. System checks for conflicts
5. Confirm booking
6. Email confirmation sent automatically

#### 3. Create Invoice
1. Navigate to **Reception → Invoices → Create**
2. Select patient and appointment
3. Add services, medicines, lab tests
4. System calculates total
5. Process payment
6. Email receipt sent

### For Doctors

#### 1. View Appointments
1. Navigate to **Doctor Dashboard**
2. See today's schedule
3. Click appointment to view details
4. Access patient medical history

#### 2. Create Diagnosis
1. Open appointment
2. Click **Create Diagnosis**
3. Enter symptoms, diagnosis, notes
4. Save

#### 3. Write Prescription
1. From diagnosis page, click **Create Prescription**
2. Add medicines with dosage
3. Add instructions
4. Save and print

### For Patients

#### 1. Book Appointment
1. Login to patient portal
2. Navigate to **Appointments → Book**
3. Select doctor and preferred date/time
4. View available slots
5. Confirm booking

#### 2. View Medical Records
1. Navigate to **Dashboard**
2. View all appointments
3. Access prescriptions
4. Download lab reports

#### 3. Request Prescription Refill
1. Go to **Prescriptions**
2. Find prescription
3. Click **Request Refill**
4. Doctor receives notification
5. Receive approval notification

---

## 🔒 Security

### Authentication
- Secure password hashing (Bcrypt)
- Session-based authentication
- Magic link login option
- Rate limiting on login attempts

### Authorization
- Role-based access control (RBAC)
- Middleware protection on all routes
- Database-level permissions

### Data Protection
- Encrypted patient data
- HTTPS enforcement
- CSRF protection
- XSS prevention
- SQL injection protection

### Audit Logging
- All user actions logged
- IP address tracking
- Timestamp tracking
- Model change tracking

---

## 📈 Performance

### Current Capabilities
- ✅ **Concurrent Users:** 10,000-100,000
- ✅ **Page Load Time:** 100-200ms
- ✅ **Database Queries:** Optimized with 40+ indexes
- ✅ **Caching:** Redis-based caching layer
- ✅ **Queue System:** Background job processing

### Optimization Features
- Database query optimization
- Eager loading for relationships
- Redis caching
- CDN for static assets
- Image optimization
- Gzip compression
- Lazy loading

See `DEPLOYMENT_GUIDE.md` for scaling to 1 billion traffic.

---

## 📸 Screenshots

### Admin Dashboard
```
┌─────────────────────────────────────────────────────────┐
│  CareSync Admin                          🔔 👤 ⚙️     │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  📊 Quick Stats                                         │
│  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐ │
│  │ Patients │ │  Today   │ │ Pending  │ │   Low    │ │
│  │  1,234   │ │   45     │ │ Invoices │ │  Stock   │ │
│  │          │ │Appts     │ │    12    │ │    8     │ │
│  └──────────┘ └──────────┘ └──────────┘ └──────────┘ │
│                                                          │
│  📈 Revenue Chart (Last 30 Days)                        │
│  ┌──────────────────────────────────────────────────┐  │
│  │ ••••  Bar Chart showing daily revenue  ••••      │  │
│  └──────────────────────────────────────────────────┘  │
│                                                          │
│  📅 Recent Appointments                                 │
│  ┌──────────────────────────────────────────────────┐  │
│  │ Patient      Doctor      Time      Status        │  │
│  │ John Doe     Dr. Smith   10:00    Completed      │  │
│  └──────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────┘
```

---

## 🤝 Contributing

We welcome contributions! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Code Style
- Follow PSR-12 coding standards
- Write meaningful commit messages
- Add tests for new features
- Update documentation

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 💬 Support

- **Email:** support@caresync.com
- **Documentation:** [docs.caresync.com](https://docs.caresync.com)
- **GitHub Issues:** [Create an issue](https://github.com/farook-code/healthcare-crm/issues)

---

## 🙏 Acknowledgments

- Laravel Framework
- Tailwind CSS
- Alpine.js
- The open-source community

---

## 🗺️ Roadmap

### Version 2.1 (Q2 2026)
- [ ] Mobile app (iOS & Android)
- [ ] Advanced AI diagnostics
- [ ] Blockchain-based medical records
- [ ] Multi-currency support

### Version 2.2 (Q3 2026)
- [ ] Telehealth video conferencing
- [ ] Wearable device integration
- [ ] Advanced analytics with ML
- [ ] Patient health scores

---

**Built with ❤️ by the CareSync Team**
