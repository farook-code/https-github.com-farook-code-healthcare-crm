@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-body">

        <h5>Add Prescription</h5>

        <form method="POST"
              action="{{ route('doctors.prescription.store', $diagnosis) }}">
            @csrf

            <div class="mb-3">
                <label>Medicine Name</label>
                <input type="text" name="medicine_name"
                       class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Dosage</label>
                <input type="text" name="dosage"
                       class="form-control"
                       placeholder="1-0-1" required>
            </div>

            <div class="mb-3">
                <label>Duration</label>
                <input type="text" name="duration"
                       class="form-control"
                       placeholder="5 days" required>
            </div>

            <div class="mb-3">
                <label>Instructions</label>
                <textarea name="instructions"
                          class="form-control"></textarea>
            </div>

            <button class="btn btn-success">
                Add Medicine
            </button>
        </form>

    </div>
</div>
@endsection
