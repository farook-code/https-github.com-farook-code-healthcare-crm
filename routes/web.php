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
    return view('welcome');
});

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

    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        // User Management
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);

        // Doctor Management (Profiles)
        Route::resource('doctors', \App\Http\Controllers\Admin\DoctorProfileController::class);

        // Department Management
        Route::resource('departments', \App\Http\Controllers\Admin\DepartmentController::class);

        Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])
            ->name('reports.index');

        // Medicines / Pharmacy Stock
        Route::get('/medicines', [\App\Http\Controllers\Admin\MedicineController::class, 'index'])->name('medicines.index');
        Route::post('/medicines', [\App\Http\Controllers\Admin\MedicineController::class, 'store'])->name('medicines.store');
        Route::put('/medicines/{medicine}', [\App\Http\Controllers\Admin\MedicineController::class, 'update'])->name('medicines.update');

        // Audit Logs
        Route::get('/audit-logs', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('logs.index');
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

    // Internal Messaging
    Route::resource('messages', \App\Http\Controllers\MessageController::class)->only(['index', 'create', 'store', 'show']);
    Route::get('/messages/{message}/reply', [\App\Http\Controllers\MessageController::class, 'reply'])->name('messages.reply');
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
        });


/*
|--------------------------------------------------------------------------
| Diagnosis & Prescription
|--------------------------------------------------------------------------
*/
Route::get('/appointments/{appointment}/diagnosis',
    [DoctorAppointmentController::class, 'diagnosisForm']
)->name('doctors.appointments.diagnosis.form');

Route::post('/appointments/{appointment}/diagnosis',
    [DoctorAppointmentController::class, 'storeDiagnosis']
)->name('doctors.appointments.diagnosis.store');

Route::get('/diagnosis/{diagnosis}/prescription',
    [DoctorAppointmentController::class, 'prescriptionForm']
)->name('doctors.prescription.form');

Route::post('/diagnosis/{diagnosis}/prescription',
    [DoctorAppointmentController::class, 'storePrescription']
)->name('doctors.prescription.store');

Route::get('/diagnosis/{diagnosis}/print',
    [DoctorAppointmentController::class, 'printPrescription']
)->name('doctors.prescription.print');

/*
|--------------------------------------------------------------------------
| Reception Module
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','role:reception'])
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

        // Appointment Management
        Route::get('/appointments/{appointment}/edit', [AppointmentController::class, 'edit'])
            ->name('appointments.edit');
        Route::put('/appointments/{appointment}', [AppointmentController::class, 'update'])
            ->name('appointments.update');
        Route::patch('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])
            ->name('appointments.cancel');

        // Billing
        Route::post('/appointments/{appointment}/invoice',
            [\App\Http\Controllers\Reception\InvoiceController::class, 'store']
        )->name('invoices.store');

        Route::get('/invoices/{invoice}/checkout',
            [\App\Http\Controllers\Reception\PaymentController::class, 'checkout']
        )->name('invoices.checkout'); // Overriding previous logic with PaymentController

        Route::get('/invoices/{invoice}/payment-success',
            [\App\Http\Controllers\Reception\PaymentController::class, 'success']
        )->name('payments.success');

        Route::post('/invoices/{invoice}/process',
            [\App\Http\Controllers\Reception\InvoiceController::class, 'processPayment']
        )->name('invoices.process');

        Route::patch('/invoices/{invoice}/pay',
            [\App\Http\Controllers\Reception\InvoiceController::class, 'markAsPaid']
        )->name('invoices.pay');

        // Insurance Claims
        Route::get('/insurance', [\App\Http\Controllers\Reception\InsuranceClaimController::class, 'index'])->name('insurance.index');
        Route::get('/invoices/{invoice}/insurance/create', [\App\Http\Controllers\Reception\InsuranceClaimController::class, 'create'])->name('insurance.create'); // Specific entry point
        Route::post('/insurance', [\App\Http\Controllers\Reception\InsuranceClaimController::class, 'store'])->name('insurance.store');
        Route::get('/insurance/{claim}', [\App\Http\Controllers\Reception\InsuranceClaimController::class, 'show'])->name('insurance.show');
        Route::put('/insurance/{claim}', [\App\Http\Controllers\Reception\InsuranceClaimController::class, 'update'])->name('insurance.update');

        Route::get('/invoices/{invoice}/print',
            [\App\Http\Controllers\Reception\InvoiceController::class, 'print']
        )->name('invoices.print');

        // Waitlist Management
        Route::get('/waitlist', [\App\Http\Controllers\Reception\WaitlistController::class, 'index'])->name('waitlist.index');
        Route::post('/waitlist', [\App\Http\Controllers\Reception\WaitlistController::class, 'store'])->name('waitlist.store');
        Route::put('/waitlist/{waitlist}', [\App\Http\Controllers\Reception\WaitlistController::class, 'update'])->name('waitlist.update');
        Route::delete('/waitlist/{waitlist}', [\App\Http\Controllers\Reception\WaitlistController::class, 'destroy'])->name('waitlist.destroy');

        // Kiosk Mode
        Route::get('/kiosk', [\App\Http\Controllers\Reception\KioskController::class, 'index'])->name('kiosk.index');
        Route::post('/kiosk/checkin', [\App\Http\Controllers\Reception\KioskController::class, 'checkIn'])->name('kiosk.checkin');
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



    Route::middleware(['auth','role:doctor,nurse'])
    ->group(function () {

        Route::get('/lab-reports/{appointment}/create',
            [LabReportController::class, 'create'] // Staff Only
        )->name('lab-reports.create');

        Route::post('/lab-reports/{appointment}',
            [LabReportController::class, 'store'] // Staff Only
        )->name('lab-reports.store');

    });

    // Public Auth Routes (Controller handles specific authorization)
    Route::middleware(['auth'])->group(function() {
        Route::get('/lab-reports/{labReport}/download',
            [LabReportController::class, 'download']
        )->name('lab-reports.download');

        Route::get('/patients/{patient}/medical-records',
            [LabReportController::class, 'index']
        )->name('lab-reports.index');

        // Refill Request (Patient)
        Route::post('/prescription/{prescription}/refill', [\App\Http\Controllers\RefillController::class, 'requestRefill'])
            ->name('patient.prescription.refill');

        Route::get('/consultation/{appointment}',
            [\App\Http\Controllers\TelemedicineController::class, 'join']
        )->name('telemedicine.join');
    });

Route::middleware(['auth', 'role:admin,reception'])->group(function () {
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


require __DIR__.'/auth.php';
