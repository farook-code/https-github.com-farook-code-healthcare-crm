@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h5>Create Appointment</h5>
    </div>

    <div class="card-body">
        <form method="POST"
              action="{{ route('reception.appointments.store') }}">
            @csrf

            <div class="mb-3">
                <label>Patient</label>
                <select name="patient_id" class="form-control" required>
                    @foreach($patients as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Doctor</label>
                <select name="doctor_id" class="form-control" required>
                    @foreach($doctors as $d)
                        <option value="{{ $d->id }}">{{ $d->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Department</label>
                <select name="department_id" class="form-control" required>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Date</label>
                <input type="date"
                       name="appointment_date"
                       class="form-control"
                       required>
            </div>

            <div class="mb-3">
                <label>Time</label>
                <input type="time"
                       name="appointment_time"
                       class="form-control"
                       required>
            </div>

            <button class="btn btn-success">
                Save Appointment
            </button>
        </form>
    </div>
</div>
@endsection
