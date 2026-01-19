@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between">
        <h5>Appointments</h5>
        <a href="{{ route('reception.appointments.create') }}"
           class="btn btn-primary btn-sm">
            + New Appointment
        </a>
    </div>

    <table class="table mb-0">
        <thead>
            <tr>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Department</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($appointments as $a)
                <tr>
                    <td>{{ $a->patient->name }}</td>
                    <td>{{ $a->doctor->name }}</td>
                    <td>{{ $a->department->name }}</td>
                    <td>{{ $a->appointment_date }}</td>
                    <td>{{ $a->appointment_time }}</td>
                    <td>{{ ucfirst($a->status) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">
                        No appointments found
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
