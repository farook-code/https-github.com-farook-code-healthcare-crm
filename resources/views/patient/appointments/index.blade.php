@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">My Appointments</h2>
        <a href="{{ route('patient.dashboard') }}" class="btn btn-outline-secondary btn-sm">
            ← Back to Dashboard
        </a>
    </div>

    {{-- Appointments Table --}}
    <div class="card">
        <div class="card-body p-0">

            @if($appointments->count() === 0)
                <div class="p-4 text-center text-muted">
                    No appointments found.
                </div>
            @else
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Appointment Date</th>
                            <th>Doctor</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $appointment)
                            <tr>
                                <td>
                                    {{ $appointment->appointment_date ?? '-' }}
                                </td>

                                <td>
                                    {{ $appointment->doctor->name ?? 'Not Assigned' }}
                                </td>

                                <td>
                                    <span class="badge
                                        @if($appointment->status === 'completed') bg-success
                                        @elseif($appointment->status === 'cancelled') bg-danger
                                        @else bg-warning
                                        @endif
                                    ">
                                        {{ ucfirst($appointment->status ?? 'pending') }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    <a href="{{ route('patient.appointments.show', $appointment->id) }}"
                                       class="btn btn-sm btn-primary">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-3">
        {{ $appointments->links() }}
    </div>

    {{-- Lab Reports --}}
@if($appointment->labReports->count())
<div class="card mt-4">
    <div class="card-header fw-semibold">
        Lab Reports
    </div>
    <div class="card-body">
        @foreach($appointment->labReports as $report)
            <div class="mb-2">
                <strong>{{ $report->title }}</strong><br>
                <a href="{{ asset('storage/'.$report->file_path) }}"
                   target="_blank">
                    View Report
                </a>
                <div class="text-muted small">
                    Uploaded by {{ $report->uploader->name }}
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif


</div>
@endsection
