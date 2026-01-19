@extends('layouts.app') {{-- or patient layout later --}}

@section('content')
<div class="container">
    <h2>Patient Dashboard</h2>

    <div class="card mt-3">
        <div class="card-body">
            <p><strong>Patient Code:</strong> {{ $patient->patient_code }}</p>
            <p><strong>Name:</strong> {{ $patient->name }}</p>
            <p><strong>Gender:</strong> {{ ucfirst($patient->gender) }}</p>
            <p><strong>Status:</strong> {{ $patient->status ? 'Active' : 'Inactive' }}</p>
        </div>
    </div>
</div>
@endsection
