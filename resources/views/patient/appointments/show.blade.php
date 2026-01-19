@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Page Header --}}
    <div class="mb-4">
        <h2 class="fw-bold">Appointment Details</h2>
        <a href="{{ route('patient.appointments.index') }}" class="text-decoration-none">
            ← Back to My Appointments
        </a>
    </div>

 @role(['nurse','doctor'])
    <a href="{{ route('vitals.create', $appointment->id) }}"
       class="btn btn-primary">
        🩺 Record Vitals
    </a>
@endrole



    {{-- Appointment Info --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header fw-semibold">
            Appointment Information
        </div>
        <div class="card-body">

            <div class="row mb-2">
                <div class="col-md-4 fw-semibold">Appointment Date</div>
                <div class="col-md-8">
                    {{ $appointment->appointment_date ?? '-' }}
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-4 fw-semibold">Status</div>
                <div class="col-md-8">
                    <span class="badge bg-secondary">
                        {{ ucfirst($appointment->status ?? 'pending') }}
                    </span>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 fw-semibold">Doctor</div>
                <div class="col-md-8">
                    {{ $appointment->doctor->name ?? 'Not assigned' }}
                    @if($appointment->doctor)
                        <span class="badge bg-success ms-2">Doctor</span>
                    @endif
                </div>
            </div>

        </div>
    </div>

    {{-- Diagnosis Section --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header fw-semibold">
            Diagnosis
        </div>
        <div class="card-body">
            @if($appointment->diagnosis)
                <p class="mb-0">
                    {{ $appointment->diagnosis->notes ?? 'No diagnosis notes provided.' }}
                </p>
            @else
                <p class="text-muted mb-0">
                    Diagnosis not yet available.
                </p>
            @endif
        </div>
    </div>

    {{-- Medicines Section --}}
    <div class="card shadow-sm">
        <div class="card-header fw-semibold">
            Medicines
        </div>

        <div class="card-body">
            @if($appointment->diagnosis && $appointment->diagnosis->prescriptions->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Medicine</th>
                                <th>Dosage</th>
                                <th>Duration</th>
                                <th>Instructions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appointment->diagnosis->prescriptions as $prescription)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $prescription->medicine_name }}</td>
                                    <td>{{ $prescription->dosage }}</td>
                                    <td>{{ $prescription->duration ?? '-' }}</td>
                                    <td>{{ $prescription->instructions ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted mb-0">
                    No medicines prescribed.
                </p>
            @endif
        </div>
    </div>
{{-- Vitals Section --}}
@if($appointment->vitals->count())
<div class="card mt-4 shadow-sm">
    <div class="card-header fw-semibold">
        Vitals
    </div>

    <div class="card-body">
        @foreach($appointment->vitals as $vital)
            <div class="row mb-2">
                <div class="col-md-4 fw-semibold">Blood Pressure</div>
                <div class="col-md-8">{{ $vital->blood_pressure ?? '-' }}</div>
            </div>

            <div class="row mb-2">
                <div class="col-md-4 fw-semibold">Pulse</div>
                <div class="col-md-8">{{ $vital->pulse ?? '-' }}</div>
            </div>

            <div class="row mb-2">
                <div class="col-md-4 fw-semibold">Temperature</div>
                <div class="col-md-8">{{ $vital->temperature ?? '-' }} °C</div>
            </div>

            <div class="row mb-2">
                <div class="col-md-4 fw-semibold">SpO₂</div>
                <div class="col-md-8">{{ $vital->oxygen_level ?? '-' }} %</div>
            </div>

            <div class="row">
                <div class="col-md-4 fw-semibold">Recorded By</div>
                <div class="col-md-8">
                    {{ $vital->recorder->name }}
                    <span class="badge ms-2
                        {{ $vital->recorder->role === 'nurse' ? 'bg-primary' : 'bg-success' }}">
                        {{ ucfirst($vital->recorder->role) }}
                    </span>
                    <div class="text-muted small">
                        {{ $vital->created_at->format('d M Y, h:i A') }}
                    </div>
                </div>
            </div>

            @if(!$loop->last)
                <hr>
            @endif
        @endforeach
    </div>
</div>
@endif

</div>
@endsection
