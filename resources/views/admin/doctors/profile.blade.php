@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h5>Doctor Profile – {{ $user->name }}</h5>
    </div>

    <div class="card-body">
        <form method="POST"
              action="{{ route('admin.doctors.profile.update', $user) }}">
            @csrf

            <div class="mb-3">
                <label>Department</label>
                <select name="department_id" class="form-control" required>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}"
                            {{ optional($profile)->department_id == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Specialization</label>
                <input type="text" name="specialization"
                       class="form-control"
                       value="{{ $profile->specialization ?? '' }}" required>
            </div>

            <div class="mb-3">
                <label>Qualification</label>
                <input type="text" name="qualification"
                       class="form-control"
                       value="{{ $profile->qualification ?? '' }}">
            </div>

            <div class="mb-3">
                <label>Experience (Years)</label>
                <input type="number" name="experience_years"
                       class="form-control"
                       value="{{ $profile->experience_years ?? '' }}">
            </div>

            <div class="mb-3">
                <label>Consultation Fee</label>
                <input type="number" name="consultation_fee"
                       class="form-control"
                       value="{{ $profile->consultation_fee ?? '' }}">
            </div>

            <button class="btn btn-success">Save Profile</button>
        </form>
    </div>
</div>
@endsection
