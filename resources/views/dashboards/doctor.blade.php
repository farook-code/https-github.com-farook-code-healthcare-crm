@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    <div class="mb-4">
        <h4 class="fw-bold">Doctor Dashboard</h4>
        <p class="text-muted mb-0">
            Welcome Dr. {{ auth()->user()->name }}
        </p>
    </div>

    {{-- Top Cards --}}
    <div class="row mb-4">

        <div class="col-md-4">
            <a href="{{ route('doctor.appointments') }}"
               class="text-decoration-none text-dark">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="fw-semibold">Appointments</h5>
                        <p class="text-muted mb-0">
                            View & manage appointments
                        </p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="fw-semibold">Patients</h5>
                    <p class="text-muted mb-0">
                        View assigned patients
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="fw-semibold">Today</h5>
                    <p class="text-muted mb-0">
                        Review vitals & start consultations
                    </p>
                </div>
            </div>
        </div>

    </div>

    {{-- Recent Vitals --}}
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header fw-semibold">
                    Recent Patient Vitals
                </div>

                <div class="card-body p-0">
                    @if($recentVitals->count())
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Patient</th>
                                        <th>BP</th>
                                        <th>Pulse</th>
                                        <th>Temp</th>
                                        <th>SpO₂</th>
                                        <th>Recorded At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentVitals as $vital)
                                        <tr>
                                            <td>{{ $vital->patient->name }}</td>
                                            <td>{{ $vital->blood_pressure ?? '-' }}</td>
                                            <td>{{ $vital->pulse ?? '-' }}</td>
                                            <td>{{ $vital->temperature ?? '-' }}</td>
                                            <td>{{ $vital->oxygen_level ?? '-' }}</td>
                                            <td>{{ $vital->created_at->format('d M Y, h:i A') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            No vitals recorded yet.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
