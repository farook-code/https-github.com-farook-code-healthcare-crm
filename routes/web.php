<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Doctor\DashboardController;
use App\Http\Controllers\Doctor\DoctorAppointmentController;
use App\Http\Controllers\Reception\AppointmentController;
use App\Http\Controllers\Patient\DashboardController as PatientDashboardController;
use App\Http\Controllers\Patient\AppointmentController as PatientAppointmentController;
use App\Models\Appointment;

/*
|--------------------------------------------------------------------------
| Public Route
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Role-based Dashboards (STATIC – OPTIONAL)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::get('/admin/dashboard', function () {
        return view('dashboards.admin');
    })->middleware('role:admin');

    Route::get('/doctor/dashboard', function () {
        return view('dashboards.doctor');
    })->middleware('role:doctor');

    Route::get('/nurse/dashboard', function () {
        return view('dashboards.nurse');
    })->middleware('role:nurse');

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

        Route::get('/appointments', [PatientAppointmentController::class, 'index'])
            ->name('appointments.index');

        Route::get('/appointments/{appointment}', [PatientAppointmentController::class, 'show'])
            ->name('appointments.show');

        Route::get('/prescriptions',
    [\App\Http\Controllers\Patient\PrescriptionController::class, 'index']
)->name('prescriptions.index');

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

        Route::get('/appointments', function () {
            $appointments = Appointment::where('doctor_id', auth()->id())
                ->latest()
                ->get();

            return view('doctors.appointments', compact('appointments'));
        })->name('appointments');

        Route::get('/appointments/{appointment}',
            [DoctorAppointmentController::class, 'show']
        )->name('appointments.show');

        Route::patch('/appointments/{appointment}/complete',
            [DoctorAppointmentController::class, 'complete']
        )->name('appointments.complete');
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

/*
|--------------------------------------------------------------------------
| Reception Module
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','role:reception'])
    ->prefix('reception')
    ->name('reception.')
    ->group(function () {

        Route::get('/appointments', [AppointmentController::class, 'index'])
            ->name('appointments.index');

        Route::get('/appointments/create', [AppointmentController::class, 'create'])
            ->name('appointments.create');

        Route::post('/appointments', [AppointmentController::class, 'store'])
            ->name('appointments.store');
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



    Route::middleware(['auth','role:doctor|nurse'])
    ->group(function () {

        Route::get('/lab-reports/{appointment}/create',
            [LabReportController::class, 'create']
        )->name('lab-reports.create');

        Route::post('/lab-reports/{appointment}',
            [LabReportController::class, 'store']
        )->name('lab-reports.store');
    });



require __DIR__.'/auth.php';
