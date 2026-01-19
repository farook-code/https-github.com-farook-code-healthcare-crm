@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h3>Record Patient Vitals</h3>

    <form method="POST" action="{{ route('vitals.store', $appointment->id) }}">
        @csrf

        <div class="mb-2">
            <input class="form-control" name="height" placeholder="Height (cm)">
        </div>

        <div class="mb-2">
            <input class="form-control" name="weight" placeholder="Weight (kg)">
        </div>

        <div class="mb-2">
            <input class="form-control" name="blood_pressure" placeholder="BP (120/80)">
        </div>

        <div class="mb-2">
            <input class="form-control" name="pulse" placeholder="Pulse">
        </div>

        <div class="mb-2">
            <input class="form-control" name="temperature" placeholder="Temperature">
        </div>

        <div class="mb-3">
            <input class="form-control" name="oxygen_level" placeholder="SpO2">
        </div>

        <button class="btn btn-primary" type="submit">
            Save Vitals
        </button>
    </form>
</div>
@endsection
