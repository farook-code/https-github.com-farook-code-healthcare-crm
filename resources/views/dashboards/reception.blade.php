@extends('layouts.app')

@section('content')
<div class="container">

    <h3 class="mb-4">Reception Dashboard</h3>

    <div class="row">

        <!-- Appointments -->
        <div class="col-md-4">
            <a href="{{ route('reception.appointments.index') }}"
               class="text-decoration-none text-dark">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Appointments</h5>
                        <p class="mb-0">Create & manage appointments</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Patients (future) -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Patients</h5>
                    <p class="mb-0 text-muted">Coming soon</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
