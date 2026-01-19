@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-body">

        <h5>Appointment Details</h5>

        <p><strong>Patient:</strong> {{ $appointment->patient->name }}</p>
        <p><strong>Date:</strong> {{ $appointment->appointment_date }}</p>
        <p><strong>Time:</strong> {{ $appointment->appointment_time }}</p>
        <p><strong>Status:</strong> {{ ucfirst($appointment->status) }}</p>

        {{-- ================= DIAGNOSIS SECTION ================= --}}

        <hr>
        <h5>Diagnosis</h5>

        {{-- ADD DIAGNOSIS BUTTON --}}
        @if(!$appointment->diagnosis && $appointment->status !== 'completed')
            <a href="{{ route('doctors.appointments.diagnosis.form', $appointment) }}"
               class="btn btn-outline-primary mt-2">
                Add Diagnosis
            </a>
        @endif

        {{-- SHOW DIAGNOSIS --}}
        @if($appointment->diagnosis)
            <div class="mt-3">
                <p><strong>Symptoms:</strong><br>
                    {{ $appointment->diagnosis->symptoms ?? '-' }}
                </p>

                <p><strong>Diagnosis:</strong><br>
                    {{ $appointment->diagnosis->diagnosis }}
                </p>

                <p><strong>Notes:</strong><br>
                    {{ $appointment->diagnosis->notes ?? '-' }}
                </p>

                <p class="text-muted">
                    Added on {{ $appointment->diagnosis->created_at->format('d M Y, h:i A') }}
                </p>
            </div>

            {{-- ADD PRESCRIPTION BUTTON --}}
            <a href="{{ route('doctors.prescription.form', $appointment->diagnosis) }}"
               class="btn btn-outline-success mt-2">
                Add Prescription
            </a>

            {{-- SHOW PRESCRIPTIONS --}}
            @if($appointment->diagnosis->prescriptions->count())
                <hr>
                <h5>Prescriptions</h5>

                <table class="table table-bordered mt-2">
                    <thead>
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
                                <td>{{ $prescription->duration }}</td>
                                <td>{{ $prescription->instructions ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-muted mt-3">
                    No medicines prescribed yet.
                </p>
            @endif
        @endif

        {{-- ================= COMPLETE APPOINTMENT ================= --}}

        @if($appointment->diagnosis && $appointment->status !== 'completed')
            <form method="POST"
                  action="{{ route('doctor.appointments.complete', $appointment) }}"
                  class="mt-4">
                @csrf
                @method('PATCH')

                <button class="btn btn-danger">
                    Mark as Complete
                </button>
            </form>
        @endif

    </div>
</div>
@endsection
