@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-body">

        <h5>Add Diagnosis</h5>

        <form method="POST"
              action="{{ route('doctors.appointments.diagnosis.store', $appointment) }}">
            @csrf

            <div class="mb-3">
                <label>Symptoms</label>
                <textarea name="symptoms"
                          class="form-control"></textarea>
            </div>

            <div class="mb-3">
                <label>Diagnosis <span class="text-danger">*</span></label>
                <textarea name="diagnosis"
                          class="form-control"
                          required></textarea>
            </div>

            <div class="mb-3">
                <label>Notes</label>
                <textarea name="notes"
                          class="form-control"></textarea>
            </div>

            <button class="btn btn-primary">
                Save Diagnosis
            </button>
        </form>

    </div>
</div>
@endsection
