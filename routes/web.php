<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Doctor\DashboardController;
use App\Http\Controllers\Doctor\DoctorAppointmentController;
use App\Http\Controllers\Reception\AppointmentController;
use App\Http\Controllers\Patient\DashboardController as PatientDashboardController;
use App\Http\Controllers\Patient\AppointmentController as PatientAppointmentController;
use App\Models\Appointment;
use App\Http\Controllers\Nurse\DashboardController as NurseDashboardController;
use App\Http\Controllers\LabReportController;
use App\Http\Controllers\ChatController;

/*
|--------------------------------------------------------------------------
| Public Route
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    $plans = \App\Models\Plan::all();
    return view('welcome', compact('plans'));
});

// Generic Dashboard Redirect
Route::get('/dashboard', function () {
    $user = auth()->user();
    if (!$user) return redirect('/login');
    
    $role = $user->role?->slug;
    
    return match ($role) {
        'super-admin', 'admin' => redirect()->route('admin.dashboard'),
        'reception' => redirect()->route('reception.dashboard'),
        'doctor' => redirect()->route('doctor.dashboard'),
        'nurse' => redirect()->route('nurse.dashboard'),
        'pharmacist' => redirect()->route('pharmacist.dashboard'),
        'lab_technician' => redirect()->route('lab.dashboard'),
        default => redirect()->route('patient.dashboard'),
    };
})->middleware(['auth'])->name('dashboard');

Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'es', 'fr', 'ar'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');

Route::middleware(['auth','role:nurse'])
    ->prefix('nurse')
    ->name('nurse.')
    ->group(function () {

        Route::get('/dashboard',
            [NurseDashboardController::class, 'index']
        )->name('dashboard');

        // Kanban Board
        Route::get('/patient-flow',
            [\App\Http\Controllers\Nurse\PatientFlowController::class, 'index']
        )->name('flow.index');

        Route::post('/patient-flow/update',
            [\App\Http\Controllers\Nurse\PatientFlowController::class, 'update']
        )->name('flow.update');

    });

/*
|--------------------------------------------------------------------------
| Role-based Dashboards (STATIC – OPTIONAL)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::middleware(['role:admin,super-admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        // User Management
        Route::patch('/users/{user}/status', [\App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.status');
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);

        // Doctor Management (Profiles)
        Route::get('/doctors/{user}/profile', [\App\Http\Controllers\Admin\DoctorProfileController::class, 'edit'])->name('doctors.profile.edit');
        Route::post('/doctors/{user}/profile', [\App\Http\Controllers\Admin\DoctorProfileController::class, 'update'])->name('doctors.profile.update');
        Route::resource('doctors', \App\Http\Controllers\Admin\DoctorProfileController::class);

        // Department Management
         Route::patch('/departments/{department}/status', [\App\Http\Controllers\Admin\DepartmentController::class, 'toggleStatus'])->name('departments.status');
        Route::resource('departments', \App\Http\Controllers\Admin\DepartmentController::class);

        Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])
            ->name('reports.index');

        // Analytics
        Route::get('/analytics', [\App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('/analytics/data', [\App\Http\Controllers\Admin\AnalyticsController::class, 'getData'])->name('analytics.data');

        // Medicines / Pharmacy Stock - Moved to Shared Group


        // Audit Logs
        Route::get('/audit-logs', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('logs.index');

        // Plan / Package Management
        Route::resource('plans', \App\Http\Controllers\Admin\PlanController::class);
        Route::get('/subscriptions', [\App\Http\Controllers\Admin\SubscriptionController::class, 'index'])->name('subscriptions.index');

        // Billing / Invoices Management
        Route::get('/invoices', [\App\Http\Controllers\Admin\InvoiceController::class, 'index'])->name('invoices.index');
        Route::get('/invoices/export', [\App\Http\Controllers\Admin\InvoiceExportController::class, 'export'])->name('invoices.export');
        Route::get('/invoices/{invoice}', [\App\Http\Controllers\Admin\InvoiceController::class, 'show'])->name('invoices.show');

        // Branch Management (Super Admin)
        Route::resource('branches', \App\Http\Controllers\Admin\BranchController::class);
        
        // Multi-Tenancy (Super Admin)
        Route::resource('clinics', \App\Http\Controllers\Admin\ClinicController::class);

        // INSURANCE (Admin Side)
        Route::resource('insurance/providers', \App\Http\Controllers\Admin\InsuranceProviderController::class, ['as' => 'insurance']);
        Route::get('insurance/policies', [\App\Http\Controllers\Admin\PatientInsuranceController::class, 'index'])->name('insurance.policies.index');

        // Settings (White Labeling)
        Route::get('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
        Route::post('/settings/test-whatsapp', [\App\Http\Controllers\Admin\SettingsController::class, 'testWhatsApp'])->name('settings.test-whatsapp');
    });

    // --- HOSPITAL OPERATIONS (Shared: Admin & Reception) ---
    Route::middleware(['role:admin,super-admin,reception'])->prefix('admin')->name('admin.')->group(function () {
        // IPD
        Route::get('ipd/admissions', [\App\Http\Controllers\Admin\IpdAdmissionController::class, 'index'])->name('ipd.admissions.index');
        Route::get('ipd/admissions/create', [\App\Http\Controllers\Admin\IpdAdmissionController::class, 'create'])->name('ipd.admissions.create');
        Route::post('ipd/admissions', [\App\Http\Controllers\Admin\IpdAdmissionController::class, 'store'])->name('ipd.admissions.store');
        Route::get('ipd/admissions/{admission}', [\App\Http\Controllers\Admin\IpdAdmissionController::class, 'show'])->name('ipd.admissions.show');
        Route::patch('ipd/admissions/{admission}/discharge', [\App\Http\Controllers\Admin\IpdAdmissionController::class, 'discharge'])->name('ipd.admissions.discharge');
        
        Route::resource('ipd/beds', \App\Http\Controllers\Admin\BedController::class, ['as' => 'ipd']);
        Route::resource('ipd/wards', \App\Http\Controllers\Admin\WardController::class, ['as' => 'ipd']);

        // OT
        Route::resource('ot/theaters', \App\Http\Controllers\Admin\OperationTheaterController::class, ['as' => 'ot']);
        Route::resource('ot/bookings', \App\Http\Controllers\Admin\OtBookingController::class, ['as' => 'ot']);
    });

    Route::get('/doctor/dashboard', function () {
        return view('dashboards.doctor');
    })->middleware('role:doctor');



    Route::get('/reception/dashboard', function () {
        return view('dashboards.reception');
    })->middleware('role:reception')
      ->name('reception.dashboard');

});

/*
|--------------------------------------------------------------------------
| Patient Module (CONTROLLER-BASED — CORRECT WAY)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])
    ->prefix('patient')
    ->name('patient.')
    ->group(function () {

        Route::get('/dashboard', [PatientDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/id-card', [\App\Http\Controllers\Patient\PatientCardController::class, 'show'])->name('card.show');

        Route::get('/appointments', [PatientAppointmentController::class, 'index'])
            ->name('appointments.index');

        Route::get('/statement', [PatientDashboardController::class, 'statement'])
            ->name('statement');

        Route::get('/appointments/create', [PatientAppointmentController::class, 'create'])
            ->name('appointments.create');
            
        Route::get('/appointments/slots', [PatientAppointmentController::class, 'getSlots'])
            ->name('appointments.slots'); // AJAX
            
        Route::post('/appointments', [PatientAppointmentController::class, 'store'])
            ->name('appointments.store');

        Route::get('/appointments/{appointment}', [PatientAppointmentController::class, 'show'])
            ->name('appointments.show');

        Route::get('/prescriptions',
            [\App\Http\Controllers\Patient\PrescriptionController::class, 'index']
        )->name('prescriptions.index');

        Route::post('/prescriptions/{prescription}/refill',
            [\App\Http\Controllers\Patient\PrescriptionController::class, 'requestRefill']
        )->name('prescriptions.refill');

Route::get('/vitals',
            [\App\Http\Controllers\Patient\VitalController::class, 'index']
        )->name('vitals.index');

        Route::get('/admissions', [\App\Http\Controllers\Patient\AdmissionController::class, 'index'])
            ->name('admissions.index');

    });

/*
|--------------------------------------------------------------------------
| Profile
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Subscriptions
    Route::get('/subscriptions', [\App\Http\Controllers\SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::get('/subscriptions/{plan}/checkout', [\App\Http\Controllers\SubscriptionController::class, 'checkout'])->name('subscriptions.checkout');
    Route::post('/subscriptions', [\App\Http\Controllers\SubscriptionController::class, 'store'])->name('subscriptions.store');
    
    // Direct Upload (No Appointment ID in URL)
    Route::post('/lab-reports/direct', [\App\Http\Controllers\LabReportController::class, 'storeDirect'])->name('lab-reports.store-direct');

    Route::post('/lab-reports/{appointment}', [\App\Http\Controllers\LabReportController::class, 'store'])->name('lab-reports.store');
    Route::put('/lab-reports/{labReport}', [\App\Http\Controllers\LabReportController::class, 'update'])->name('lab-reports.update');


    // Internal Messaging
    Route::resource('messages', \App\Http\Controllers\MessageController::class)->only(['index', 'create', 'store', 'show']);
    Route::get('/messages/{message}/reply', [\App\Http\Controllers\MessageController::class, 'reply'])->name('messages.reply');

    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'readAll'])->name('notifications.readAll');
    Route::get('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'read'])->name('notifications.read');
});

/*
|--------------------------------------------------------------------------
| Doctor Module
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','role:doctor'])
    ->prefix('doctor')
    ->name('doctor.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/patients', [DashboardController::class, 'patients'])
            ->name('patients');

        Route::get('/billing', [DashboardController::class, 'invoices'])
            ->name('invoices');

        Route::get('/appointments', [DoctorAppointmentController::class, 'index'])
            ->name('appointments');

        Route::get('/appointments/calendar', [DoctorAppointmentController::class, 'calendar'])
            ->name('appointments.calendar');

        Route::get('/appointments/events', [DoctorAppointmentController::class, 'events'])
            ->name('appointments.events');

        Route::post('/appointments/scribe', [\App\Http\Controllers\Doctor\AIScribeController::class, 'process'])
            ->name('ai.scribe');

        Route::patch('/appointments/{appointment}/complete',
            [DoctorAppointmentController::class, 'complete']
        )->name('appointments.complete');
    });

    // Shared Doctor/Nurse Appointment Access
    Route::middleware(['auth','role:doctor,nurse'])
        ->prefix('doctor')
        ->name('doctor.')
        ->group(function () {
             Route::get('/appointments/{appointment}',
                [DoctorAppointmentController::class, 'show']
            )->name('appointments.show');

            Route::post('/appointments/{appointment}/vaccinations',
                [DoctorAppointmentController::class, 'storeVaccination']
            )->name('appointments.vaccinations.store');

            // Telemedicine
            Route::get('/appointments/{appointment}/meet', [\App\Http\Controllers\TelemedicineController::class, 'join'])
                ->name('telemedicine.join');
            
            // Refill Management (Doctor)
            Route::get('/refills', [\App\Http\Controllers\RefillController::class, 'indexRequests'])->name('refills.index');
            Route::put('/refills/{prescription}', [\App\Http\Controllers\RefillController::class, 'updateStatus'])->name('refills.update');

            // Medicine Interactions
            Route::post('/medicines/check-interactions', [\App\Http\Controllers\Doctor\InteractionController::class, 'check'])
                ->name('medicines.check-interactions');
        });


/*
|--------------------------------------------------------------------------
| Diagnosis & Prescription
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/appointments/{appointment}/diagnosis',
        [DoctorAppointmentController::class, 'diagnosisForm']
    )->name('doctors.appointments.diagnosis.form')->middleware('role:doctor');

    Route::post('/appointments/{appointment}/diagnosis',
        [DoctorAppointmentController::class, 'storeDiagnosis']
    )->name('doctors.appointments.diagnosis.store')->middleware('role:doctor');

    Route::get('/diagnosis/{diagnosis}/prescription',
        [DoctorAppointmentController::class, 'prescriptionForm']
    )->name('doctors.prescription.form')->middleware('role:doctor');

    Route::post('/diagnosis/{diagnosis}/prescription',
        [DoctorAppointmentController::class, 'storePrescription']
    )->name('doctors.prescription.store')->middleware('role:doctor');

    // Shared: Patient needs to print too
    Route::get('/diagnosis/{diagnosis}/print',
        [DoctorAppointmentController::class, 'printPrescription']
    )->name('doctors.prescription.print');

    // Lab Request
    Route::post('/appointments/{appointment}/lab-request',
        [DoctorAppointmentController::class, 'storeLabRequest']
    )->name('doctors.appointments.lab.store')->middleware('role:doctor');
});

/*
|--------------------------------------------------------------------------
| Reception Module
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','role:reception,admin,super-admin'])
    ->prefix('reception')
    ->name('reception.')
    ->group(function () {

        Route::get('/appointments/calendar', [AppointmentController::class, 'calendar'])
            ->name('appointments.calendar');

        Route::get('/appointments/events', [AppointmentController::class, 'events'])
            ->name('appointments.events');

        Route::post('/appointments/move', [AppointmentController::class, 'move'])
            ->name('appointments.move');

        Route::get('/appointments', [AppointmentController::class, 'index'])
            ->name('appointments.index');

        Route::get('/appointments/create', [AppointmentController::class, 'create'])
            ->name('appointments.create');

        Route::post('/appointments', [AppointmentController::class, 'store'])
            ->name('appointments.store');

        // Patient Management
        Route::get('/patients', [\App\Http\Controllers\Reception\PatientController::class, 'index'])
            ->name('patients.index');
        Route::get('/patients/create', [\App\Http\Controllers\Reception\PatientController::class, 'create'])
            ->name('patients.create');
        Route::post('/patients', [\App\Http\Controllers\Reception\PatientController::class, 'store'])
            ->name('patients.store');
        Route::get('/patients/{patient}/edit', [\App\Http\Controllers\Reception\PatientController::class, 'edit'])
            ->name('patients.edit');
        Route::put('/patients/{patient}', [\App\Http\Controllers\Reception\PatientController::class, 'update'])
            ->name('patients.update');
        Route::get('/patients/{patient}', [\App\Http\Controllers\Reception\PatientController::class, 'show'])
            ->name('patients.show');

        // Appointment Management
        Route::get('/appointments/{appointment}/edit', [AppointmentController::class, 'edit'])
            ->name('appointments.edit');
        Route::put('/appointments/{appointment}', [AppointmentController::class, 'update'])
            ->name('appointments.update');
        Route::patch('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])
            ->name('appointments.cancel');

        // Invoice Routes moved to Shared Billing Group below (to allow Pharmacist/Lab Tech access)

        // Insurance Claims
        Route::get('/insurance', [\App\Http\Controllers\Reception\InsuranceClaimController::class, 'index'])->name('insurance.index');
        Route::get('/invoices/{invoice}/insurance/create', [\App\Http\Controllers\Reception\InsuranceClaimController::class, 'create'])->name('insurance.create'); // Specific entry point
        Route::post('/insurance/verify', [\App\Http\Controllers\Reception\InsuranceClaimController::class, 'verify'])->name('insurance.verify');
        Route::post('/insurance', [\App\Http\Controllers\Reception\InsuranceClaimController::class, 'store'])->name('insurance.store');
        Route::get('/insurance/{claim}', [\App\Http\Controllers\Reception\InsuranceClaimController::class, 'show'])->name('insurance.show');
        Route::put('/insurance/{claim}', [\App\Http\Controllers\Reception\InsuranceClaimController::class, 'update'])->name('insurance.update');

        // Invoice Print route moved to Shared Billing Group

        // Waitlist Management
        Route::get('/waitlist', [\App\Http\Controllers\Reception\WaitlistController::class, 'index'])->name('waitlist.index');
        Route::post('/waitlist', [\App\Http\Controllers\Reception\WaitlistController::class, 'store'])->name('waitlist.store');
        Route::put('/waitlist/{waitlist}', [\App\Http\Controllers\Reception\WaitlistController::class, 'update'])->name('waitlist.update');
        Route::delete('/waitlist/{waitlist}', [\App\Http\Controllers\Reception\WaitlistController::class, 'destroy'])->name('waitlist.destroy');

        // Kiosk Mode
        Route::get('/kiosk', [\App\Http\Controllers\Reception\KioskController::class, 'index'])->name('kiosk.index');
        Route::post('/kiosk/checkin', [\App\Http\Controllers\Reception\KioskController::class, 'checkIn'])->name('kiosk.checkin');

        // TV Queue Display (New)
        Route::get('/queue/display', [\App\Http\Controllers\Reception\QueueController::class, 'display'])->name('queue.display');
    });

    Route::middleware(['auth','role:nurse,doctor'])
    ->prefix('vitals')
    ->name('vitals.')
    ->group(function () {

        Route::get('/appointments/{appointment}/create',
            [\App\Http\Controllers\VitalController::class, 'create']
        )->name('create');

        Route::post('/appointments/{appointment}',
            [\App\Http\Controllers\VitalController::class, 'store']
        )->name('store');
    });



    Route::middleware(['auth','role:doctor,nurse,lab_technician'])
    ->group(function () {

        Route::get('/lab-reports/{appointment}/create',
            [LabReportController::class, 'create'] // Staff Only
        )->name('lab-reports.create');

        Route::post('/lab-reports/{appointment}',
            [LabReportController::class, 'store'] // Staff Only
        )->name('lab-reports.store');

        // Allow Lab Tech to access Index view too (generic)
        Route::get('/lab-reports', [LabReportController::class, 'index'])->name('lab-reports.index');

    });

    // Staff Only: Lab Quick Scan
    Route::middleware(['auth', 'role:admin,super-admin,doctor,nurse,reception,lab_technician'])
        ->group(function() {
            Route::get('/lab-reports/quick-upload', [\App\Http\Controllers\LabReportController::class, 'quickUploadScan'])
                ->name('lab-reports.quick-upload');
            Route::get('/lab-reports/scan-lookup', [\App\Http\Controllers\LabReportController::class, 'scanLookup'])
                ->name('lab-reports.scan-lookup');
    });

    // Public Auth Routes (Controller handles specific authorization)
    Route::middleware(['auth'])->group(function() {
        Route::get('/lab-reports/{labReport}/download',
            [LabReportController::class, 'download']
        )->name('lab-reports.download');

        // Patient access to records
        Route::get('/patients/{patient}/medical-records',
            [LabReportController::class, 'index']
        )->name('lab-reports.patient-records'); // Renamed to avoid name conflict with generic index

        // Refill Request (Patient)
        Route::post('/prescription/{prescription}/refill', [\App\Http\Controllers\RefillController::class, 'requestRefill'])
            ->name('patient.prescription.refill');

        Route::get('/consultation/{appointment}',
            [\App\Http\Controllers\TelemedicineController::class, 'join']
        )->name('telemedicine.join');
    });

Route::middleware(['auth', 'role:admin,reception,pharmacist'])->group(function () {
    // Pharmacy / Medicines
    Route::get('medicines/list', [\App\Http\Controllers\Admin\MedicineController::class, 'list'])->name('admin.medicines.list');
    Route::post('medicines/import', [\App\Http\Controllers\Admin\MedicineController::class, 'import'])->name('admin.medicines.import');
    Route::get('medicines/export', [\App\Http\Controllers\Admin\MedicineController::class, 'export'])->name('admin.medicines.export');
    Route::resource('medicines', \App\Http\Controllers\Admin\MedicineController::class, ['as' => 'admin']);
});

Route::middleware('auth')->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/search', [ChatController::class, 'searchUsers'])->name('chat.search');
    Route::get('/chat/unread', [ChatController::class, 'unreadCount'])->name('chat.unread');
    Route::get('/chat/open/{user}', [ChatController::class, 'open'])->name('chat.open');
    Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');
    Route::get('/chat/fetch/{chat}', [ChatController::class, 'fetch'])->name('chat.fetch');
});


/*
|--------------------------------------------------------------------------
| Shared Billing (Reception, Pharmacist, Lab Tech) & System Alerts
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:reception,admin,super-admin,pharmacist,lab_technician,doctor,nurse'])
    ->group(function () {
        // System Alerts (No prefix)
        Route::get('/alerts', [\App\Http\Controllers\Admin\SystemAlertController::class, 'index'])->name('alerts.index');
        Route::patch('/alerts/{alert}/resolve', [\App\Http\Controllers\Admin\SystemAlertController::class, 'markAsResolved'])->name('alerts.resolve');
        Route::get('/alerts/fetch', [\App\Http\Controllers\Admin\SystemAlertController::class, 'fetchActive'])->name('alerts.fetch');
    });

Route::middleware(['auth', 'role:reception,admin,super-admin,pharmacist,lab_technician,doctor,nurse'])
    ->prefix('reception') // Prefix kept for compatibility
    ->name('reception.')
    ->group(function () {
        // Invoices
        Route::get('/invoices', [\App\Http\Controllers\Reception\InvoiceController::class, 'index'])->name('invoices.index');
        Route::get('/invoices/create', [\App\Http\Controllers\Reception\InvoiceController::class, 'create'])->name('invoices.create');
        Route::post('/invoices', [\App\Http\Controllers\Reception\InvoiceController::class, 'store'])->name('invoices.store');
        Route::get('/invoices/{invoice}/print', [\App\Http\Controllers\Reception\InvoiceController::class, 'print'])->name('invoices.print');
        
        // Payment Processing
        Route::get('/invoices/{invoice}/checkout', [\App\Http\Controllers\Reception\PaymentController::class, 'checkout'])->name('invoices.checkout');
        Route::get('/invoices/{invoice}/payment-success', [\App\Http\Controllers\Reception\PaymentController::class, 'success'])->name('payments.success');
        Route::post('/invoices/{invoice}/process', [\App\Http\Controllers\Reception\InvoiceController::class, 'processPayment'])->name('invoices.process');
        Route::patch('/invoices/{invoice}/pay', [\App\Http\Controllers\Reception\InvoiceController::class, 'markAsPaid'])->name('invoices.pay');

        // Helpers
        Route::get('/appointments/{appointment}/details', [\App\Http\Controllers\Reception\InvoiceController::class, 'getAppointmentDetails'])->name('appointments.details');
        Route::get('/patients/{patient}/pending-items', [\App\Http\Controllers\Reception\InvoiceController::class, 'getPendingItems'])->name('patients.pending-items');
    });

/*
|--------------------------------------------------------------------------
| Pharmacist & Lab Dashboards
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:pharmacist'])->prefix('pharmacist')->name('pharmacist.')->group(function () {
    Route::get('/dashboard', function () {
        $lowStockMedicines = \App\Models\Medicine::where('stock_quantity', '<=', 10)->limit(5)->get();
        return view('dashboards.pharmacist', compact('lowStockMedicines'));
    })->name('dashboard');
});

Route::middleware(['auth', 'role:lab_technician'])->prefix('lab')->name('lab.')->group(function () {
    Route::view('/dashboard', 'dashboards.lab_technician')->name('dashboard');
});

require __DIR__.'/auth.php';
require __DIR__.'/magic_login.php';

