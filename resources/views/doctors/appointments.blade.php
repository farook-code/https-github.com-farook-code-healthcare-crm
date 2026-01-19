@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h5>My Appointments</h5>
    </div>

    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($appointments as $appointment)
                    <tr>
                        <td>{{ $appointment->patient->name }}</td>
                        <td>{{ $appointment->appointment_date }}</td>
                        <td>{{ $appointment->appointment_time }}</td>
                        <td>
                            <span class="badge bg-info">
                                {{ ucfirst($appointment->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('doctor.appointments.show', $appointment) }}"
                               class="btn btn-sm btn-primary">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5"
                            class="text-center text-muted">
                            No appointments found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
